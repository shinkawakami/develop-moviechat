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


class ViewingController extends Controller
{
    public function index($groupId, $viewingId)
    {
        $viewing = Viewing::findOrFail($viewingId);
        
        $group = Group::findOrFail($groupId);

        return view('viewings.index', compact('group', 'viewing'));
    }
    
    public function request(Request $request, $groupId)
    {
        $user = $request->user();
        $group = Group::findOrFail($groupId);
        $movieId = $request->input('movie_id');
        $movie = Movie::findOrFail($movieId);

        $viewing = new Viewing();
        $viewing->group()->associate($group);
        $viewing->requester()->associate($user);
        $viewing->movie()->associate($movie);
        $viewing->start_time = $request->input('start_time');
        $viewing->save();
        $viewingId = $viewing->id;
        $viewing->url = url("/moviechat/groups/$groupId/viewings/$viewingId");
        $viewing->save();
        

        return redirect()->back();
    }

    public function approve(Request $request, $groupId, $viewingId)
    {
        $user = $request->user();
        $viewing = Viewing::findOrFail($viewingId);
        $viewing->approvers()->attach($user);

        return redirect()->back();
    }
    
    public function cancel(Group $group, $viewingId)
    {
        $viewing = Viewing::findOrFail($viewingId);
    
        // ユーザーが申請者である場合のみ申請を取り消すことができます
        if (Auth::user()->id == $viewing->requester_id || Auth::user()->id == $group->owner_id) {
            $viewing->delete();
        }
    
        return redirect()->back();
    }
    
    public function chat(Request $request, $groupId, $viewingId)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|max:20',
        ]);
    
        $user = $request->user();
        $viewing = Viewing::findOrFail($viewingId);
    
        $chatMessage = new Message();
        $chatMessage->content = $request->input('message');
        $chatMessage->user()->associate($user);
        $chatMessage->viewing()->associate($viewing);
        $chatMessage->save();
    
        return redirect()->back();
    }
}