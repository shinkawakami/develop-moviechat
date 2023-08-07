<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
use App\Models\Movie;
use App\Models\Viewing;
use App\Http\Requests\Viewing\RequestRequest;
use App\Http\Requests\Chat\SendRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Events\MessageSent;
use App\Events\RequestSent;
use App\Events\ApproveSent;

class ViewingController extends Controller
{
    public function index($groupId, $viewingId)
    {
        $viewing = Viewing::with('messages.user')->findOrFail($viewingId);
        
        $group = Group::findOrFail($groupId);

        return view('viewings.index', compact('group', 'viewing'));
    }
    
    public function request(RequestRequest $request, $groupId)
    {
        $validatedData = $request->validated();
        
        $user = $request->user();
        $group = Group::findOrFail($groupId);

        $viewing = new Viewing();
        $viewing->group()->associate($group);
        $viewing->requester()->associate($user);
        $viewing->start_time = $validatedData['start_time'];
        
        $tmdbId = $validatedData['movie'];
        $apiKey = config('tmdb.api_key');
        
        $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}?api_key={$apiKey}&language=ja-JP");
        $movieData = $response->json();
        $movie = Movie::updateOrCreateFromTMDB($movieData);
        
        $viewing->movie()->associate($movie);
        $viewing->save();
        
        $viewingId = $viewing->id;
        $viewing->url = url("/moviechat/groups/$groupId/viewings/$viewingId");
        $viewing->save();

        return redirect()->back();
    }

    public function approve($groupId, $viewingId)
    {
        $user = Auth::user();
        $viewing = Viewing::findOrFail($viewingId);
        $viewing->approvers()->attach($user);
        return redirect()->back();
    }
    
    public function cancel($groupId, $viewingId)
    {
        $group = Group::findOrFail($groupId);
        $viewing = Viewing::findOrFail($viewingId);
    
        $viewing->delete();
    
        return redirect()->back();
    }
    
    public function chat(SendRequest $request, $groupId, $viewingId)
    {
        $validatedData = $request->validated();
    
        $user = $request->user();
        $viewing = Viewing::findOrFail($viewingId);
    
        $chatMessage = new Message();
        $chatMessage->content = $validatedData['message'];
        $chatMessage->user()->associate($user);
        $chatMessage->viewing()->associate($viewing);
        $chatMessage->save();
    
        return redirect()->back();
    }
    
    public function destroy($groupId, $viewingId, $messageId)
    {
        $viewing = Viewing::findOrFail($viewingId);
        $message = Message::findOrFail($messageId);
        
        $message->delete();
        return redirect()->back();
    }
}