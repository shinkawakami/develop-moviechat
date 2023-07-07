<section>
    <header>
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <!-- Select2 CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        
        <!-- Select2 JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        
        <div>
            <x-input-label for="favorite_movies" :value="__('Favorite Movies')" />
            <select id="favorite_movies" name="favorite_movies[]" class="mt-1 block w-full" multiple>
                @foreach ($movies as $movie)
                    <option value="{{ $movie->id }}" {{ in_array($movie->id, $user->favoriteMovies) ? 'selected' : '' }}>{{ $movie->title }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('favorite_movies')" />
        </div>
        
        <div>
            <x-input-label for="favorite_genres" :value="__('Favorite Genres')" />
            <select id="favorite_genres" name="favorite_genres[]" class="mt-1 block w-full" multiple>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ in_array($genre->id, $user->favoriteGenres) ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('favorite_genres')" />
        </div>
        
        <div>
            <x-input-label for="favorite_platforms" :value="__('Favorite Platforms')" />
            <select id="favorite_platforms" name="favorite_platforms[]" class="mt-1 block w-full" multiple>
                @foreach ($platforms as $platform)
                    <option value="{{ $platform->id }}" {{ in_array($platform->id, $user->favoritePlatforms) ? 'selected' : '' }}>{{ $platform->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('favorite_platforms')" />
        </div>
        
        <div>
            <x-input-label for="favorite_eras" :value="__('Favorite Eras')" />
            <select id="favorite_eras" name="favorite_eras[]" class="mt-1 block w-full" multiple>
                @foreach ($eras as $era)
                    <option value="{{ $era->id }}" {{ in_array($era->id, $user->favoriteEras) ? 'selected' : '' }}>{{ $era->era }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('favorite_eras')" />
        </div>
        
        <script>
        $(document).ready(function() {
            $('#favorite_movies').select2();
            $('#favorite_genres').select2();
            $('#favorite_platforms').select2();
            $('#favorite_eras').select2();
        });
        </script>
        
        <div>
            <x-input-label for="introduction" :value="__('Introduction')" />
            <textarea id="introduction" name="introduction" class="mt-1 block w-full">{{ old('introduction', $user->introduction) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('introduction')" />
        </div

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
