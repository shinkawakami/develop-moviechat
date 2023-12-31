<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Era;
use App\Models\Movie;
use Cloudinary;

class ProfileController extends Controller
{
    public function home()
    {
        return view('home');
    }
    
    public function show($userId)
    {
        $user = User::with(['movies', 'genres', 'eras', 'platforms', 'groups', 'posts'])->findOrFail($userId);
        $isSelfProfile = Auth::id() == $user->id;
        $isFollowing = Auth::user()->isFollowing($user);
        return view('profile.show', compact('user', 'isSelfProfile', 'isFollowing'));
    }
    
    public function follow(User $user)
    {
        Auth::user()->followings()->attach($user->id);
        return back();
    }
    
    public function unfollow(User $user)
    {
        Auth::user()->followings()->detach($user->id);
        return back();
    }
    
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        
        $user->genres = $user->genres->pluck('id')->toArray();
        $user->platforms = $user->platforms->pluck('id')->toArray();
        $user->eras = $user->eras->pluck('id')->toArray();
        
        return view('profile.edit', compact('user', 'genres', 'platforms', 'eras'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        
        $user = $request->user();
        $user->fill($validatedData);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        
        if ($request->image) {
            $user->image_url = Cloudinary::upload($request->image->getRealPath())->getSecurePath();
        }
        
        $apiKey = config('tmdb.api_key');
        $attachedMovieIds = [];
        if ($request->movies) {
            foreach ($request->movies as $movieId) {
                $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
                $movieData = $response->json();
    
                $movie = Movie::updateOrCreateFromTMDB($movieData);
    
                array_push($attachedMovieIds, $movie->id);
            }
        }
        
        $user->movies()->sync($attachedMovieIds);
        $user->genres()->sync($request->genres ?? []);
        $user->platforms()->sync($request->platforms ?? []);
        $user->eras()->sync($request->eras ?? []);
        $user->introduction = $request->introduction;

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
