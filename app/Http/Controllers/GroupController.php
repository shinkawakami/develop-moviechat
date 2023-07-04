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

class GroupController extends Controller
{
    public function create()
    {
        $movies = Movie::all();
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.create', compact('movies', 'genres', 'platforms', 'eras'));
    }
    
    public function search()
    {
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        return view('groups.search', compact('genres', 'platforms', 'eras'));
    }
    
    public function index()
    {
        $groups = Group::all();
        return view('groups.list', compact('groups'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
        }
        
        $groupName = $request->input('group_name');
        $capacity = $request->input('group_capacity');
        $titles = $request->input('group_movie_title_id');
        $genres = $request->input('group_movie_genre_id');
        $platforms = $request->input('group_movie_platform_id');
        $eras = $request->input('group_movie_era_id');
        
        $user = Auth::user();
        
        // Groupモデルの作成と保存
        $group = new Group();
        $group->name = $groupName;
        $group->creator()->associate($user);
        $group->capacity = $capacity;
        $group->save();
        
        $group->users()->attach($user);
        $group->movies()->attach($titles);
        
        // ジャンルの関連付け
        if (!empty($genres)) {
            $group->genres()->attach($genres);
        }

        // プラットフォームの関連付け
        if (!empty($platforms)) {
            $group->platforms()->attach($platforms);
        }
        
        // 年代の関連付け
        if (!empty($eras)) {
            $group->eras()->attach($eras);
        }
        
        // 成功時の処理
        $groups = Group::all();
        return view('groups.list', compact('groups'));
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
        return view('groups.profile', compact('group'));
    }
    
    public function result(Request $request)
    {
        if ($request->has('group_name')) {
            $name = $request->input('group_name');
            $groups = Group::where('name', 'like', '%' . $name . '%')->take(5)->get();
            return view('groups.list', compact('groups'));
        }
        
        $groups = Group::query();

        if ($request->has('group_movie_era_id')) {
            $eraIds = $request->input('group_movie_era_id');
            $groups->where(function ($query) use ($eraIds) {
                foreach ($eraIds as $eraId) {
                    if (!empty($eraId)) {
                        $query->whereHas('eras', function ($subQuery) use ($eraId) {
                            $subQuery->where('era_id', $eraId);
                        });
                    }
                }
            });
        }
        
        if ($request->has('group_movie_genre_id')) {
            $genreIds = $request->input('group_movie_genre_id');
            $groups->where(function ($query) use ($genreIds) {
                foreach ($genreIds as $genreId) {
                    if (!empty($genreId)) {
                        $query->whereHas('genres', function ($subQuery) use ($genreId) {
                            $subQuery->where('genre_id', $genreId);
                        });
                    }
                }
            });
        }
        
        if ($request->has('group_movie_platform_id')) {
            $platformIds = $request->input('group_movie_platform_id');
            $groups->where(function ($query) use ($platformIds) {
                foreach ($platformIds as $platformId) {
                    if (!empty($platformId)) {
                        $query->whereHas('platforms', function ($subQuery) use ($platformId) {
                            $subQuery->where('platform_id', $platformId);
                        });
                    }
                }
            });
        }
        
        $groups = $groups->get();
        
        return view('groups.list', compact('groups'));
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
}
    
    