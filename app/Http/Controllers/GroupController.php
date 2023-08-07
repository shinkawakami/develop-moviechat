<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Movie;
use App\Models\Era;
use App\Models\Genre;
use App\Models\Platform;
use App\Http\Requests\Group\CreateRequest;
use App\Http\Requests\Group\SearchRequest;
use App\Http\Requests\Group\EditRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GroupController extends Controller
{
    // グループ一覧画面の表示
    public function index()
    {
        $groups = Group::with(['movies', 'genres', 'eras', 'platforms'])->get();
        
        foreach ($groups as $group) {
            $group->is_member = $group->isMember(Auth::id());
            $group->is_full = $group->isFull();
        }
        
        return view('groups.index', compact('groups'));
    }
        
    // グループ作成画面の表示
    public function create()
    {
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.create', compact('genres', 'eras', 'platforms'));
    }
    
    // グループ保存の処理
    public function store(CreateRequest $request)
    {
        $validatedData = $request->validated();
    
        $group = new Group;
        $group->name = $validatedData['group_name'];
        $group->owner()->associate(Auth::user());
        $group->capacity = $validatedData['group_capacity'];
        $group->save();
        
        $group->users()->attach(Auth::user());
        if (isset($validatedData['genres'])) {
            $group->genres()->attach($validatedData['genres']);
        }
        
        if (isset($validatedData['eras'])) {
            $group->eras()->attach($validatedData['eras']);
        }
        
        if (isset($validatedData['platforms'])) {
            $group->platforms()->attach($validatedData['platforms']);
        }
         
        if (isset($validatedData['movies'])) {
            $tmdbIds = $validatedData['movies'];
            $apiKey = config('tmdb.api_key');
           
            foreach ($tmdbIds as $tmdbId) {
                $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}?api_key={$apiKey}&language=ja-JP");
                $movieData = $response->json();
        
                $movie = Movie::updateOrCreateFromTMDB($movieData);
        
                $group->movies()->attach($movie->id);
            }
        }
        
        return redirect()->route('groups.index', $group);
    }
    
    // グループ検索画面の表示
    public function showSearch()
    {
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.search', compact('genres', 'platforms', 'eras'));
    }
    
    // グループ検索処理とその結果の表示
    public function searchResults(SearchRequest $request)
    {
        $validatedData = $request->validated();
        
        $groups = Group::query();
        
        if (isset($validatedData['keyword'])) {
            $groups->withKeyword($validatedData['keyword']);
        }
        
        if (isset($validatedData['genres'])) {
            $groups->withGenres($validatedData['genres']);
        }
    
        if (isset($validatedData['eras'])) {
            $groups->withEras($validatedData['eras']);
        }
        
        if (isset($validatedData['platforms'])) {
            $groups->withPlatforms($validatedData['platforms']);
        }
    
        $groups = $groups->with(['movies', 'genres', 'eras', 'platforms'])->get();
    
        foreach ($groups as $group) {
            $group->is_member = $group->isMember(Auth::id());
            $group->is_full = $group->isFull();
        }
    
        return view('groups.index', compact('groups'));
    }
    
    // 自分のグループの表示
    public function user()
    {
        $user = Auth::user();
        $groups = $user->groups()->with(['movies', 'genres', 'eras', 'platforms'])->get();
        
        foreach ($groups as $group) {
            $group->is_member = $group->isMember($user->id);
            $group->is_full = $group->isFull();
        }
        
        return view('groups.index', compact('groups'));
    }
    
    // グループ参加の処理
    public function join($groupId)
    {
        $user = Auth::user();
        $group = Group::findOrFail($groupId);
        $group->users()->attach($user);
        
        return redirect()->route('chats.index', $group->id);
    }
    
    // グループ詳細画面の表示
    public function show($groupId)
    {
        $user = Auth::user();
        $group = Group::with('users')->findOrFail($groupId);
        $group->is_member = $group->isMember($user->id);
        $group->is_full = $group->isFull();
        $group->is_owner = $group->isOwner($user);
        
        return view('groups.show', compact('group'));
    }
    
    // グループ削除の処理
    public function destroy($groupId)
    {
        $group = Group::findOrFail($groupId);
        
        $group->delete();
        
        return redirect()->route('groups.index');
    }
    
    // オーナーがユーザーをグループから退会させる処理
    public function removeUser($groupId, $userId)
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
    
        $group->users()->detach($userId);
    
        return redirect()->back();
    }
    
    // グループ編集画面の表示
    public function edit($groupId)
    {
        $group = Group::with(['movies', 'genres', 'eras', 'platforms'])->findOrFail($groupId);
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.edit', compact('group', 'genres', 'eras', 'platforms'));
    }
    
    // グループ更新の処理
    public function update(EditRequest $request, $groupId)
    {
        $validatedData = $request->validated();
    
        $group = Group::findOrFail($groupId);
        $group->name = $validatedData['group_name'];
        $group->capacity = $validatedData['group_capacity'];
        $group->save();
        
        if (isset($validatedData['genres'])) {
            $group->genres()->sync($validatedData['genres']);
        } else {
            $group->genres()->detach();
        }
    
        if (isset($validatedData['eras'])) {
            $group->eras()->sync($validatedData['eras']);
        } else {
            $group->eras()->detach();
        }
    
        if (isset($validatedData['platforms'])) {
            $group->platforms()->sync($validatedData['platforms']);
        } else {
            $group->platforms()->detach();
        }
        
        if (isset($validatedData['movies'])) {
            $tmdbIds = $validatedData['movies'];
            $movieIds = [];
            $apiKey = config('tmdb.api_key');
        
            foreach ($tmdbIds as $tmdbId) {
                $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}?api_key={$apiKey}&language=ja-JP");
                $movieData = $response->json();
        
                $movie = Movie::updateOrCreateFromTMDB($movieData);
                
                $movieIds[] = $movie->id;
            }
            
            $group->movies()->sync($movieIds);
            
        } else {
            $group->movies()->detach();
        }

        return redirect()->route('groups.show', $group);
    }
    
    // グループ退会の処理
    public function leave($groupId)
    {
        $user = Auth::user();
        $group = Group::findOrFail($groupId);
        
        if ($group->isOwner($user)) {
            $group->newOwnerOrDelete($user->id);
            if(!$group->exists) {
                return redirect()->route('groups.index');
            }
        }
        
        foreach ($group->viewings as $viewing) {
            $user = Auth::user();
            if ($viewing->isRequester) {
                $viewing->delete();
            }
            else {
                $viewing->approvers()->detach($user->id);
            }
        }
    
        $group->users()->detach($user->id);
        
        return redirect()->route('groups.index');
    }
    
}
    
    