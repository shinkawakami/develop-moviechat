<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Movie;
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
        $movies = Movie::all();
        $genres = Genre::all();
        $platforms = Platform::all();
        $eras = Era::all();
        
        $user->favoriteMovies = $user->favoriteMovies->pluck('id')->toArray();
        $user->favoriteGenres = $user->favoriteGenres->pluck('id')->toArray();
        $user->favoritePlatforms = $user->favoritePlatforms->pluck('id')->toArray();
        $user->favoriteEras = $user->favoriteEras->pluck('id')->toArray();
        
        return view('profile.edit', compact('user', 'movies', 'genres', 'platforms', 'eras'));
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
        
        $user->favoriteMovies()->sync($request->favorite_movies);
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
