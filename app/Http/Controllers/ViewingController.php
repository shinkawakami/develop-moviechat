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
use Carbon\Carbon;

class ViewingController extends Controller
{
    // 同時視聴画面の表示
    public function index($groupId, $viewingId)
    {
        $viewing = Viewing::with('messages.user')->findOrFail($viewingId);
        
        $group = Group::findOrFail($groupId);

        return view('viewings.index', compact('group', 'viewing'));
    }
    
    // 同時視聴申請の処理
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
        
        $viewing->recipients()->attach($validatedData['recipients']);
        
        return redirect()->back();
    }

    // 同時視聴承諾の処理
    public function approve($groupId, $viewingId)
    {
        $userId = Auth::id();
        $viewing = Viewing::findOrFail($viewingId);
        $viewing->recipients()->updateExistingPivot($userId, ['approved' => true]);
        return redirect()->back();
    }
    
    // 同時視聴取り消しの処理
    public function cancel($groupId, $viewingId)
    {
        $group = Group::findOrFail($groupId);
        $viewing = Viewing::findOrFail($viewingId);
    
        $viewing->delete();
    
        return redirect()->back();
    }
    
    // 同時視聴でのチャットの処理
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
    
    // メッセージ削除の処理
    public function destroy($groupId, $viewingId, $messageId)
    {
        $viewing = Viewing::findOrFail($viewingId);
        $message = Message::findOrFail($messageId);
        
        $message->delete();
        return redirect()->back();
    }
    
    // 映画視聴開始時間の通知
    public function notice($groupId, $viewingId)
    {
        $viewing = Viewing::findOrFail($viewingId);
        
        $current_time_carbon = Carbon::now();
        $start_time_carbon = Carbon::parse($viewing->start_time);
        $minutes_to_start = $current_time_carbon->diffInMinutes($start_time_carbon, false);
    
        if ($minutes_to_start > 0 && $minutes_to_start <= 30) {
            return response()->json(['message' => '視聴時間が' . $minutes_to_start . '分前になりました。', 'status' => $viewing->status]);
        } 
        elseif ($minutes_to_start == 0) {
            return response()->json(['message' => '視聴時間になりました。視聴を開始してください。', 'status' => $viewing->status]);
        } 
        elseif ($minutes_to_start < 0) {
            $viewing->status = '視聴中';
            $viewing->save();
            return response()->json(['message' => '視聴中です。', 'status' => $viewing->status]);
        }
        else {
            return response()->json(['message' => '視聴開始は' . $viewing->start_time . 'です。', 'status' => $viewing->status]);
        }
    }
    
    // 視聴の終了
    public function end($groupId, $viewingId)
    {
        $viewing = Viewing::findOrFail($viewingId);
        $viewing->status = '視聴終了';
        $viewing->save();
        return redirect()->back();
    }

}