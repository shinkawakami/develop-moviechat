<?php

namespace App\Http\Controllers;

use App\Models\Era;
use App\Models\Genre;
use App\Models\Group;
use App\Models\Message;
use App\Models\Movie;
use App\Models\Platform;
use App\Models\User;
use App\Models\ViewGroup;
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
        $viewGroups = ViewGroup::where('group_id', $groupId)->get();
        
        foreach ($viewGroups as $viewGroup) {
            // ユーザーが申請者であるかどうかをチェック
            $isRequester = Auth::user()->id == $viewGroup->requester_id;
        
            // ユーザーがすでに承認者であるかどうかをチェック
            $hasApproved = $viewGroup->approvers()->where('user_id', Auth::user()->id)->exists();
            
            // チェック結果を各viewGroupオブジェクトに追加
            $viewGroup->is_requester = $isRequester;
            $viewGroup->has_approved = $hasApproved;
        }


        return view('chat.index', compact('group', 'viewGroups', 'movies'));
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
    
    public function request(Request $request, $groupId)
    {
        $user = $request->user();
        $group = Group::findOrFail($groupId);
        $movieId = $request->input('movie_id');
        $movie = Movie::findOrFail($movieId);

        $viewGroup = new ViewGroup();
        $viewGroup->group()->associate($group);
        $viewGroup->requester()->associate($user);
        $viewGroup->movie()->associate($movie);
        $viewGroup->start_time = $request->input('start_time');
        $viewGroup->save();
        $viewGroupId = $viewGroup->id;
        $viewGroup->view_link = url("/moviechat/groups/$groupId/view/$viewGroupId");
        $viewGroup->save();
        

        return redirect()->back();
    }

    public function approve(Request $request, $groupId, $viewGroupId)
    {
        $user = $request->user();
        $viewGroup = ViewGroup::findOrFail($viewGroupId);
        $viewGroup->approvers()->attach($user);

        return redirect()->back();
    }
    
    public function view($groupId, $viewGroupId)
    {
        $viewGroup = ViewGroup::findOrFail($viewGroupId);
        
        $group = Group::findOrFail($groupId);

        return view('chat.view', compact('group', 'viewGroup'));
    }
    
    public function viewChat(Request $request, $groupId, $viewGroupId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|max:20',
        ]);
    
        $user = $request->user();
        $viewGroup = ViewGroup::findOrFail($viewGroupId);
    
        $chatMessage = new Message();
        $chatMessage->content = $request->input('message');
        $chatMessage->user()->associate($user);
        $chatMessage->viewGroup()->associate($viewGroup);
        $chatMessage->save();
    
        return redirect()->back();
    }
    
    public function cancel($groupId, $viewGroupId)
    {
        $viewGroup = ViewGroup::findOrFail($viewGroupId);
    
        // ユーザーが申請者である場合のみ申請を取り消すことができます
        if (Auth::user()->id == $viewGroup->requester_id) {
            $viewGroup->delete();
        }
    
        return redirect()->back();
    }
    
    // ユーザーがグループから脱退するメソッド
    public function leaveGroup($groupId)
    {
        $group = Group::findOrFail($groupId);
        $group->users()->detach(Auth::user()->id);
    
        return redirect()->route('groups.myList');
    }
}