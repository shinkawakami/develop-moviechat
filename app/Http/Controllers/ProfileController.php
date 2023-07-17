<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Era;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        
        $user->favoriteGenres = $user->favoriteGenres->pluck('id')->toArray();
        $user->favoritePlatforms = $user->favoritePlatforms->pluck('id')->toArray();
        $user->favoriteEras = $user->favoriteEras->pluck('id')->toArray();
        
        return view('profile.edit', compact('user', 'genres', 'platforms', 'eras'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        
        $apiKey = config('tmdb.api_key');
        $attachedMovieIds = [];
        foreach ($request->favorite_movies as $movieId) {
            $response = Http::get("https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=ja-JP");
            $movieData = $response->json();

            $movie = Movie::updateOrCreate(
                ['tmdb_id' => $movieData['id']],
                ['title' => $movieData['title']]
            );

            array_push($attachedMovieIds, $movie->id);
        }
        
        $user->favoriteMovies()->sync($attachedMovieIds);
        $user->favoriteGenres()->sync($request->favorite_genres);
        $user->favoritePlatforms()->sync($request->favorite_platforms);
        $user->favoriteEras()->sync($request->favorite_eras);
        $user->introduction = $request->introduction;

        $request->user()->save();

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
    
    public function home()
    {
        return view('home');
    }
}
