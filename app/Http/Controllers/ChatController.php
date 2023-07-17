<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
use App\Models\Movie;
use App\Models\Viewing;
use App\Http\Requests\Chat\SendRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Events\RequestSent;
use App\Events\ApproveSent;


class ChatController extends Controller
{
    public function index($groupId)
    {
        $group = Group::findOrFail($groupId);
        $movies = Movie::all();
        $viewings = Viewing::where('group_id', $groupId)->get();
        
        foreach ($viewings as $viewing) {
            // ユーザーが申請者であるかどうかをチェック
            $isRequester = Auth::user()->id == $viewing->requester_id;
        
            // ユーザーがすでに承認者であるかどうかをチェック
            $hasApproved = $viewing->approvers()->where('user_id', Auth::user()->id)->exists();
            
            $isOwner = Auth::user()->id == $group->owner_id;
            // チェック結果を各viewGroupオブジェクトに追加
            $viewing->is_requester = $isRequester;
            $viewing->has_approved = $hasApproved;
            $group->is_owner = $isOwner;
        }

        return view('chats.index', compact('group', 'viewings', 'movies'));
    }
    
    public function send(SendRequest $request, $groupId)
    {
        $validatedData = $request->validated();
    
        $user = $request->user();
        $group = Group::findOrFail($groupId);
    
        $chatMessage = new Message();
        $chatMessage->content = $validatedData['message'];
        $chatMessage->user()->associate($user);
        $chatMessage->group()->associate($group);
        $chatMessage->save();
    
        event(new MessageSent($chatMessage)); // メッセージ送信イベントを発行
    
        return redirect()->back();
    }
    
    public function destroy($groupId, $messageId)
    {
        $group = Group::findOrFail($groupId);
        $message = Message::findOrFail($messageId);
        
        $message->delete();
        return redirect()->route('chats.index', compact('group'));
    }
}