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
        $viewing = Viewing::findOrFail($viewingId);
        
        $group = Group::findOrFail($groupId);

        return view('viewings.index', compact('group', 'viewing'));
    }
    
    public function request(RequestRequest $request, $groupId)
    {
        $validatedData = $request->validated();
        
        $user = $request->user();
        $group = Group::findOrFail($groupId);
        $movieId = $validatedData['movie'];

        $viewing = new Viewing();
        $viewing->group()->associate($group);
        $viewing->requester()->associate($user);
        $viewing->start_time = $validatedData['start_time'] . ":00";
        $movieId = $validatedData['movie'];
        $apiKey = config('tmdb.api_key');
        
        if ($movieId !== null) {
            $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
            $movieData = $response->json();

            $movie = Movie::firstOrCreate(
                ['tmdb_id' => $movieData['id']],
                ['title' => $movieData['title']]
            );

            $viewing->movie()->associate($movie);
        }
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
    
        // ユーザーが申請者である場合のみ申請を取り消すことができます
        if (Auth::user()->id == $viewing->requester_id || Auth::user()->id == $group->owner_id) {
            $viewing->delete();
        }
    
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
}