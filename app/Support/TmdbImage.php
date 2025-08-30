<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TmdbImage
{
    public function url(?string $path, string $type = 'poster', string $size = 'w500'): string
    {
        if (!$path) {
            return asset('images/no-image.svg'); // フォールバック
        }

        $images = Cache::remember('tmdb:images', 86400, function () {
            return Http::baseUrl('https://api.themoviedb.org/3')
                ->withToken(config('tmdb.tmdb.token'))
                ->get('/configuration')
                ->throw()
                ->json('images');
        });

        $base  = $images['secure_base_url'] ?? 'https://image.tmdb.org/t/p/';
        $sizes = match ($type) {
            'poster'   => $images['poster_sizes'] ?? [],
            'backdrop' => $images['backdrop_sizes'] ?? [],
            'profile'  => $images['profile_sizes'] ?? [],
            'logo'     => $images['logo_sizes'] ?? [],
            'still'    => $images['still_sizes'] ?? [],
            default    => $images['poster_sizes'] ?? [],
        };

        // 指定サイズが未提供なら近いサイズに寄せる
        if (!in_array($size, $sizes, true)) {
            $size = in_array('w500', $sizes, true)
                ? 'w500'
                : (end($sizes) ?: 'original');
        }

        return rtrim($base, '/') . '/' . $size . $path; // $path は先頭スラッシュ付き
    }
}
