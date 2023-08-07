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


class ChatController extends Controller
{
    public function index($groupId)
    {
        $group = Group::with('messages.user')->findOrFail($groupId);
        $movies = Movie::all();
        $viewings = Viewing::where('group_id', $groupId)->get();
        $user = Auth::user();
        
        foreach ($viewings as $viewing) {
            $viewing->is_requester = $viewing->isRequester($user);
            $viewing->is_approver = $viewing->isApprover($user);
        }
        
        $group->is_owner = $group->isOwner($user);
        
        return view('chats.index', compact('group', 'viewings', 'movies'));
    }
    
    public function send(SendRequest $request, $groupId)
    {
        $validatedData = $request->validated();
    
        $user = Auth::user();
        $group = Group::findOrFail($groupId);
    
        $chatMessage = new Message();
        $chatMessage->content = $validatedData['message'];
        $chatMessage->user()->associate($user);
        $chatMessage->group()->associate($group);
        $chatMessage->save();
        
        event(new MessageSent($chatMessage));
    
        return redirect()->back();
    }
    
    public function receive() 
    {
        return response()->json([
            'auth_id' => Auth::id(),
            'csrf_token' => csrf_token(),
            'pusher_app_key' => config('broadcasting.connections.pusher.key'),
            'pusher_app_cluster' => config('broadcasting.connections.pusher.options.cluster')
        ]);
    }
    
    public function destroy($groupId, $messageId)
    {
        $group = Group::findOrFail($groupId);
        $message = Message::findOrFail($messageId);
        
        $message->delete();
        
        return redirect()->route('chats.index', compact('group'));
    }
}