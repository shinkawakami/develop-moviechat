<?php
/**
 * app/helpers.php
 *
 * ※ Laravel 標準には含まれない “薄い表示用ヘルパ” を集約するファイル。
 *   - 外部I/O（HTTP/DB/Cache）は行わないこと
 *   - 重い処理は App\Support\* や App\Services\* に置くこと
 *
 * 使い方：
 *   1) composer.json にオートロードを追加し、`composer dump-autoload` を実行
 *      {
 *        "autoload": {
 *          "psr-4": { "App\\": "app/" },
 *          "files": ["app/helpers.php"]
 *        }
 *      }
 *   2) 画像URLは Blade で： <img src="{{ tmdb_img($posterPath, 'poster', 'w500') }}">
 */

declare(strict_types=1);

use App\Support\TmdbImage;

if (!function_exists('tmdb_img')) {
    /**
     * TMDB の file_path（/xxxx.jpg 等）から、/configuration に基づく完全URLを返す薄いラッパ。
     * - 実際の基底URL決定・サイズ妥当性チェック・フォールバックは TmdbImage 側で実施
     *
     * @param string|null $path  TMDB の *_path（先頭スラッシュ想定／null可）
     * @param string      $type  poster|backdrop|profile|logo|still
     * @param string      $size  例: w500, w780, original など
     * @return string 完全な画像URL（またはフォールバック画像のURL）
     */
    function tmdb_img(?string $path, string $type = 'poster', string $size = 'w500'): string
    {
        /** @var TmdbImage $builder */
        $builder = app(TmdbImage::class);
        return $builder->url($path, $type, $size);
    }
}

if (!function_exists('tmdb_poster')) {
    /**
     * ポスター画像のショートハンド。
     */
    function tmdb_poster(?string $path, string $size = 'w500'): string
    {
        return tmdb_img($path, 'poster', $size);
    }
}

if (!function_exists('tmdb_backdrop')) {
    /**
     * バックドロップ（背景）画像のショートハンド。
     */
    function tmdb_backdrop(?string $path, string $size = 'w780'): string
    {
        return tmdb_img($path, 'backdrop', $size);
    }
}

if (!function_exists('tmdb_profile')) {
    /**
     * 人物プロフィール画像のショートハンド。
     */
    function tmdb_profile(?string $path, string $size = 'w185'): string
    {
        return tmdb_img($path, 'profile', $size);
    }
}

if (!function_exists('tmdb_logo')) {
    /**
     * ロゴ画像のショートハンド。
     */
    function tmdb_logo(?string $path, string $size = 'w300'): string
    {
        return tmdb_img($path, 'logo', $size);
    }
}

if (!function_exists('tmdb_still')) {
    /**
     * スチル画像（エピソード静止画）のショートハンド。
     */
    function tmdb_still(?string $path, string $size = 'w300'): string
    {
        return tmdb_img($path, 'still', $size);
    }
}

if (!function_exists('tmdb_attribution_html')) {
    /**
     * TMDB 必須の帰属表記（英語原文）の HTML（安全な固定文）を返す。
     * Blade 側では `{!! tmdb_attribution_html() !!}` で出力。
     *
     * 表示例：
     *   This product uses the TMDB API but is not endorsed or certified by TMDB.
     */
    function tmdb_attribution_html(): string
    {
        $tmdbUrl = 'https://www.themoviedb.org';
        $text    = 'This product uses the TMDB API but is not endorsed or certified by TMDB.';
        return sprintf(
            '<small class="tmdb-attribution" style="display:block;opacity:.72">%s <a href="%s" target="_blank" rel="noopener">The Movie Database (TMDB)</a></small>',
            htmlspecialchars($text, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($tmdbUrl, ENT_QUOTES, 'UTF-8')
        );
    }
}

if (!function_exists('no_image_asset')) {
    /**
     * プロジェクト共通の「画像なし」アセットを返す。
     * 例：resources/images/no-image.svg → public/images/no-image.svg（`php artisan storage:link` 等の運用に合わせて調整）
     */
    function no_image_asset(): string
    {
        return asset('images/no-image.svg');
    }
}
