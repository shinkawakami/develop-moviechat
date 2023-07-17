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
use Illuminate\Support\Facades\Http;
use App\Events\MessageSent;

class GroupController extends Controller
{
    private function getMovieDetails($movieId)
    {
        $apiKey = config('tmdb.api_key');
        $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
        return $response->json();
    }
    
    // ユーザーが指定したグループのメンバーであるかを'is_member'にセットする
    private function getGroupWithMember($groups = null)
    {
        $groups = $groups ?? Group::with('users')->get();
        foreach ($groups as $group) {
            $group->setAttribute('is_member', $group->users->contains(Auth::id()));
        }
        return $groups;
    }
    
    public function index()
    {
        $groups = $this->getGroupWithMember();
        
        return view('groups.index', compact('groups'));
    }
        
    public function create(Request $request)
    {
        // セッションから選択された映画を取得
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.create', compact('genres', 'platforms', 'eras'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|max:255',
            'group_capacity' => 'required|integer|min:2|max:10',
            'genres' => 'required',
            'eras' => 'required',
            'platforms' => 'required',
            'movies' => 'required',
        ]);

        $group = new Group;
        $group->name = $request->group_name;
        $group->owner()->associate(Auth::user());
        $group->capacity = $request->group_capacity;
        $group->save();
        
        $group->users()->attach(Auth::user());
        $group->genres()->attach($request->genres);
        $group->eras()->attach($request->eras);
        $group->platforms()->attach($request->platforms);
        
        $movieIds = explode(',', $request->movies);
        $apiKey = config('tmdb.api_key');
    
        foreach ($movieIds as $movieId) {
            if ($movieId !== '') {
                $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
                $movieData = $response->json();
    
                $movie = Movie::firstOrCreate(
                    ['tmdb_id' => $movieData['id']],
                    ['title' => $movieData['title']]
                );
    
                $group->movies()->attach($movie->id);
            }
        }
        return redirect()->route('groups.index', $group);
    }
    
    public function showSearch()
    {
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.search', compact('genres', 'platforms', 'eras'));
    }
    
    public function searchResults(Request $request)
    {
        $groups = Group::query();
    
        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
    
            $groups->whereHas('movies', function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%');
            })->orWhere('name', 'like', '%' . $keyword . '%');
        }
    
        if ($request->has('group.movie_era_ids')) {
            $eraIds = array_filter($request->input('group.movie_era_ids'));
            
            if (!empty($eraIds)) {
                $groups->whereHas('eras', function ($query) use ($eraIds) {
                $query->whereIn('era_id', $eraIds);
                });
            }
        }
    
        if ($request->has('group.movie_genre_ids')) {
            $genreIds = array_filter($request->input('group.movie_genre_ids'));
        
            if (!empty($genreIds)) {
                $groups->whereHas('genres', function ($query) use ($genreIds) {
                    $query->whereIn('genre_id', $genreIds);
                });
            }
        }
        
        if ($request->has('group.movie_platform_ids')) {
            $platformIds = array_filter($request->input('group.movie_platform_ids'));
        
            if (!empty($platformIds)) {
                $groups->whereHas('platforms', function ($query) use ($platformIds) {
                    $query->whereIn('platform_id', $platformIds);
                });
            }
        }
    
        $groups = $groups->get();
    
        $groups = $this->getGroupWithMember($groups);
    
        return view('groups.index', compact('groups'));
    }
    
    public function myGroups()
    {
        $user = Auth::user();
        $groups = $user->groups;
        
        $groups = $this->getGroupWithMember($groups);
        
        return view('groups.index', compact('groups'));
    }

    
    public function join($groupId)
    {
        $user = Auth::user();
        $group = Group::findOrFail($groupId);
        
        // ユーザーをグループに参加させる
        $group->users()->attach($user);
        
        return redirect()->route('chats.index', $group->id);
    }
    
    public function show($groupId)
    {
        $group = Group::findOrFail($groupId);
        return view('groups.show', compact('group'));
    }
    
    public function destroy(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);

        // グループ作成者のみが削除できることを確認
        if ($group->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $group->delete();

        // グループ削除後のリダイレクト先などの処理を追加する場合はここに記述

        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }
    
    public function removeUser(Request $request, $groupId, $userId)
    {
        $group = Group::findOrFail($groupId);

        $user = User::findOrFail($userId);
        
        foreach ($group->viewings as $viewing) {
            
            if ($viewing->requester_id == $user->id) {
                $viewing->delete();
            }
            else {
                $viewing->approvers()->detach($user->id);
            }
        }

        if (Auth::user()->id != $group->owner_id) {
            return redirect()->back()->with('error', 'You are not authorized to do this action');
        }
    
        $group->users()->detach($userId);
    
        return redirect()->back()->with('message', 'User has been removed from the group');
    }
    
    public function edit(Request $request, Group $group)
    {
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.edit', ['genres' => $genres, 'platforms' => $platforms, 'eras' => $eras, 'group' => $group]);
    }
    
    public function update(Request $request, Group $group)
    {
        $request->validate([
            'group_name' => 'required|max:255',
            'group_capacity' => 'required|integer|min:2|max:10',
            'genres' => 'required',
            'eras' => 'required',
            'platforms' => 'required',
            'movies' => 'required',
        ]);
    
        $group->name = $request->group_name;
        $group->capacity = $request->group_capacity;
        $group->save();
    
        $group->genres()->sync($request->genres);
        $group->eras()->sync($request->eras);
        $group->platforms()->sync($request->platforms);
    
        $movieIds = explode(',', $request->movies);
        $apiKey = config('tmdb.api_key');
        $attachedMovieIds = [];
    
        foreach ($movieIds as $movieId) {
            if ($movieId !== '') {
                $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
                $movieData = $response->json();
    
                $movie = Movie::firstOrCreate(
                    ['tmdb_id' => $movieData['id']],
                    ['title' => $movieData['title']]
                );
    
                array_push($attachedMovieIds, $movie->id);
            }
        }
    
        $group->movies()->sync($attachedMovieIds);
    
        return redirect()->route('groups.show', $group);
    }

}
    
    