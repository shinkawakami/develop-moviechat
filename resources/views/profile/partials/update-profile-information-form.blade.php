<section>
    <header>
        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <!-- Select2 CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        
        <link href="{{ asset('css/auth/edit.css') }}" rel="stylesheet">
        
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

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        
        @if(empty($user->image_url))
            <i class="fas fa-user rounded-icon"></i>
        @else
            <img src="{{ $user->image_url }}" alt="Profile Image" class="rounded-icon">
        @endif
        
        <div>
            <x-input-label for="profile_image" :value="__('Profile Image')" />
            <input type="file" id="profile_image" name="image" class="mt-1 block w-full">
            <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
        </div>
        
        <div>
            <x-input-label for="movies" :value="__('Movies')" />

            <select id="movies" name="movies[]" class="mt-1 block w-full" multiple>
                @foreach ($user->movies as $movie)
                    <option value="{{ $movie->tmdb_id }}" selected>{{ $movie->title }}</option>
                @endforeach
            </select>
            
            <x-input-error class="mt-2" :messages="$errors->get('movies')" />
        </div>
        
        
        <div>
            <x-input-label for="genres" :value="__('Genres')" />
            <select id="genres" name="genres[]" class="mt-1 block w-full" multiple>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ in_array($genre->id, $user->genres) ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('genres')" />
        </div>
        
        <div>
            <x-input-label for="platforms" :value="__('Platforms')" />
            <select id="platforms" name="platforms[]" class="mt-1 block w-full" multiple>
                @foreach ($platforms as $platform)
                    <option value="{{ $platform->id }}" {{ in_array($platform->id, $user->platforms) ? 'selected' : '' }}>{{ $platform->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('platforms')" />
        </div>
        
        <div>
            <x-input-label for="eras" :value="__('Eras')" />
            <select id="eras" name="eras[]" class="mt-1 block w-full" multiple>
                @foreach ($eras as $era)
                    <option value="{{ $era->id }}" {{ in_array($era->id, $user->eras) ? 'selected' : '' }}>{{ $era->era }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('eras')" />
        </div>
        
        <script>
        $(document).ready(function() {
            $('#movies').select2();
            $('#genres').select2();
            $('#platforms').select2();
            $('#eras').select2();
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
    <script src="{{ asset('js/editProfile.js') }}"></script>
</section>
