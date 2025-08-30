<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>MovieChat - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
</head>

<body>
    <x-app-layout>
        <section class="section">
            <div class="container has-text-centered">
                <h1 class="title">MovieChat</h1>
                <p class="subtitle">「異なる映画体験を、もっと自由に。」</p>

                <div class="content">
                    <div class="box">
                        <h2 class="subtitle">映画を語りたい人へ</h2>
                        <div class="text-container">
                            気になる映画の感想や意見をシェアしよう。
                            自分の好みに合うグループを作成したり、検索して参加して、映画好き同士で語り合える場所です。
                        </div>
                    </div>
                    <div class="box">
                        <h2 class="subtitle">誰かと一緒に観たい人へ</h2>
                        <div class="text-container">
                            同じ映画を同時に楽しみたい仲間をグループで探せます。
                            申請が承認されれば、外部アプリのURLで一緒に再生しながら、チャットでリアクションや感想を共有できます。
                        </div>
                    </div>
                    <div class="box">
                        <h2 class="subtitle">新しい映画を探したい人へ</h2>
                        <div class="text-container">
                            映画検索やグループ内のチャット・投稿をチェック。
                            自分では出会えなかったジャンルや新たな魅力を発見できるかもしれません。
                        </div>
                    </div>
                </div>
            </div>

            <!-- TMDB attribution -->
            <footer class="text-xs text-gray-500 text-center py-5">
                This product uses the TMDB API but is not endorsed or certified by TMDB.
                <br>
                <a href="https://www.themoviedb.org" target="_blank" rel="noopener">
                    <img src="{{ asset('images/tmdb.svg') }}" alt="TMDB logo" class="inline h-4 align-text-bottom">
                </a>
            </footer>
        </section>
    </x-app-layout>
</body>
</html>