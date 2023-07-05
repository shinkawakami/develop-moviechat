<?php

namespace App\Http\Controllers;

use App\Models\Era;
use App\Models\Genre;
use App\Models\Group;
use App\Models\Message;
use App\Models\Movie;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index($groupId)
    {
        $group = Group::findOrFail($groupId);
        return view('chat.index', compact('group'));
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
}