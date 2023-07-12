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
        
        foreach ($groups as $group) {
            $movies = $group->movies()->get(); // リレーションを使ってグループに関連する映画を取得
            $movieDetails = [];
    
            foreach ($movies as $movie) {
                $movieId = $movie->tmdb_id;
                $movieDetails[$movieId] = $this->getMovieDetails($movieId);
            }
    
            $group->movies = $movieDetails;
        }
        
        return view('groups.index', compact('groups'));
    }
        
    public function create(Request $request)
    {
        // セッションから選択された映画を取得
        $selectedMovies = $request->session()->get('selected_movies', []);
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.create', compact('selectedMovies', 'genres', 'platforms', 'eras'));
    }
    
    public function store(Request $request)
    {
        // フォームデータからGroupモデルのインスタンス作成
        $group = new Group($request->input('group'));
        // 作成者を関連付け
        $group->creator()->associate(Auth::user());
        $group->save();
        
        $group->users()->attach(Auth::user());
        $group->movies()->attach($request->session()->get('selected_movie_ids'));
        $group->genres()->attach($request->input('group.movie_genre_ids'));
        $group->platforms()->attach($request->input('group.movie_platform_ids'));
        $group->eras()->attach($request->input('group.movie_era_ids'));
        
        // セッションに選択していた映画情報の配列を取得
        $selectedMovies = $request->session()->get('selected_movies');

        // 選択した映画がmoviesテーブルのレコードに存在しなかったらレコード作成
        foreach ($selectedMovies as $movie) {
            $movieTitle = $movie['title'];
            $movieId = $movie['id'];
            
            $existingMovie = Movie::where('tmdb_id', $movieId)->first();
            
            if (!$existingMovie) {
                $movie = new Movie();
                $movie->title = $movieTitle;
                $movie->tmdb_id = $movieId;
                $movie->save();
                $group->movies()->attach($movie->id);
            }
        }
        
        // すべてのグループを取得し，ユーザーがメンバーであるかを調べる
        $groups = $this->getGroupWithMember();
        
        // セッションデータを消去
        $request->session()->forget('selected_movies');
    
        return redirect()->route('groups.index');
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
    
        return view('groups.search_results', compact('groups'));
    }

    
    public function joinGroup($groupId)
    {
        // ログインしているユーザーを取得
        $user = Auth::user();
        
        // グループを取得
        $group = Group::findOrFail($groupId);
        
        // ユーザーをグループに参加させる
        $group->users()->attach($user);
        
        // 成功時の処理（例: 成功メッセージを表示してリダイレクト）
        return redirect()->route('chat.index', ['groupId' => $groupId]);
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
        if ($group->creator_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $group->delete();

        // グループ削除後のリダイレクト先などの処理を追加する場合はここに記述

        return redirect()->route('group.index')->with('success', 'Group deleted successfully.');
    }
    
    public function myList()
    {
        $user = Auth::user();
        $groups = $user->groups;
        
        $groups = $this->getGroupWithMember($groups);
        
        return view('groups.index', compact('groups'));
    }
    
    
}
    
    