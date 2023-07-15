<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
use App\Models\Movie;
use App\Models\Viewing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
    
    public function sent(Request $request, $groupId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|max:20',
        ]);
    
        $user = $request->user();
        $group = Group::findOrFail($groupId);
    
        $chatMessage = new Message();
        $chatMessage->content = $request->input('message');
        $chatMessage->user()->associate($user);
        $chatMessage->group()->associate($group);
        $chatMessage->save();
    
        event(new MessageSent($chatMessage)); // メッセージ送信イベントを発行
    
        return redirect()->back();
    }
    
    // ユーザーがグループから脱退するメソッド
    public function leave($groupId)
    {
        $group = Group::findOrFail($groupId);

        // もし現在のユーザーがグループのオーナーである場合、次に参加したメンバーをオーナーに指定する
        if (Auth::user()->id == $group->owner_id) {
            // 次のメンバーを探す
            $nextOwner = $group->users()->where('users.id', '!=', Auth::user()->id)->orderBy('pivot_created_at')->first();
    
            // 次のメンバーが存在する場合、そのメンバーを新たなオーナーに指定する
            if ($nextOwner) {
                $group->owner_id = $nextOwner->id;
                $group->save();
            }
            // もし次のメンバーが存在しない場合、グループ自体を削除する
            else {
                $group->delete();
                return redirect()->route('groups.index');
            }
        }
        
        foreach ($group->viewings as $viewing) {
            
            if ($viewing->requester_id == Auth::user()->id) {
                $viewing->delete();
            }
            else {
                $viewing->approvers()->detach(Auth::user()->id);
            }
        }
    
        // ユーザーをグループから削除する
        $group->users()->detach(Auth::user()->id);
        
        return redirect()->route('groups.index');
    }
    
    public function destroy(Group $group, Message $message)
    {
        $message->delete();
        return redirect()->route('chats.index', compact('group'));
    }
}