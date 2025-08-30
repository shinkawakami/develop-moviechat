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
                <p class="subtitle">「異なる映画を楽しみたいあなたに」</p>

                <div class="content">
                    <div class="box">
                        <h2 class="subtitle">映画の感想や意見を共有したいユーザー</h2>
                        <div class="text-container">
                            自分の好みに合うグループを作成するか、検索して参加できるよ。
                            そのグループで自分の好きな映画を語り合おう！
                        </div>
                    </div>
                    <div class="box">
                        <h2 class="subtitle">誰かと映画の同時視聴をしたいユーザー</h2>
                        <div class="text-container">
                            グループ内で一緒に視聴したいユーザーを探して、同時視聴を申請しよう！
                            申請が承諾されると、指定のURLで外部アプリで視聴しながら、
                            視聴中のリアクションや感想を共有できるよ。
                        </div>
                    </div>
                    <div class="box">
                        <h2 class="subtitle">映画探索をしたいユーザー</h2>
                        <div class="text-container">
                            映画の検索やグループでのチャット、投稿を閲覧しよう！！
                            自分では気づけないジャンルの映画や映画の魅力に触れることができるかも！？
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- TMDB attribution -->
        <footer class="text-xs text-gray-500 text-center py-6">
            This product uses the TMDB API but is not endorsed or certified by TMDB.
            <br>
            <a href="https://www.themoviedb.org" target="_blank" rel="noopener">
                <img src="{{ asset('images/tmdb.svg') }}" alt="TMDB logo" class="inline h-4 align-text-bottom">
            </a>
        </footer>
    </x-app-layout>
</body>
</html>