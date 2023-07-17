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
        
    public function create()
    {
        // セッションから選択された映画を取得
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.create', compact('genres', 'platforms', 'eras'));
    }
    
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
        
        $movieIds = explode(',', $validatedData['movies']);
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
    
    public function searchResults(SearchRequest $request)
    {
        $validatedData = $request->validated();
        
        $groups = Group::query();
        
        if (isset($validatedData['keyword'])) {
            $keyword = $validatedData['keyword'];
    
            $groups->whereHas('movies', function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%');
            })->orWhere('name', 'like', '%' . $keyword . '%');
        }
    
        if (isset($validatedData['eras'])) {
            $eraIds = array_filter($validatedData['eras']);
            
            if (!empty($eraIds)) {
                $groups->whereHas('eras', function ($query) use ($eraIds) {
                $query->whereIn('era_id', $eraIds);
                });
            }
        }
    
        if (isset($validatedData['genres'])) {
            $genreIds = array_filter($validatedData['genres']);
        
            if (!empty($genreIds)) {
                $groups->whereHas('genres', function ($query) use ($genreIds) {
                    $query->whereIn('genre_id', $genreIds);
                });
            }
        }
        
        if (isset($validatedData['platforms'])) {
            $platformIds = array_filter($validatedData['platforms']);
        
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
    
    public function destroy($groupId)
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

        if (Auth::user()->id != $group->owner_id) {
            return redirect()->back()->with('error', 'You are not authorized to do this action');
        }
    
        $group->users()->detach($userId);
    
        return redirect()->back()->with('message', 'User has been removed from the group');
    }
    
    public function edit($groupId)
    {
        $group = Group::findOrFail($groupId);
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.edit', ['genres' => $genres, 'platforms' => $platforms, 'eras' => $eras, 'group' => $group]);
    }
    
    public function update(EditRequest $request, $groupId)
    {
        $validatedData = $request->validated();
    
        $group = Group::findOrFail($groupId);
        $group->name = $validatedData['group_name'];
        $group->capacity = $validatedData['group_capacity'];
        $group->save();
        
        $genres = $validatedData['genres'] ?? [];
        $eras = $validatedData['eras'] ?? [];
        $platforms = $validatedData['platforms'] ?? [];
    
        $group->genres()->sync($genres);
        $group->eras()->sync($eras);
        $group->platforms()->sync($platforms);
    
        $movieIds = isset($validatedData['movies']) ? explode(',', $validatedData['movies']) : [];
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
    
}
    
    