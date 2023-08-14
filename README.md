<h1 align="center">MovieChat</h1>

##  制作背景
近年，ストリーミングサービスの普及により，手軽に様々な映画を楽しむことが可能となりました．<br>
しかし，この状況がもたらす問題の一つは，映画の視聴が分散されることです．<br><br>
私自身も経験しているこの問題は，感動や興奮を得た映画が友人と異なることから，
映画に関する共通の話題を持ちにくく，コミュニケーションを取ることが難しくなっています．<br>
映画は，感じたことや考えたことを他者と共有することでその魅力が倍増すると感じています．<br><br>
この問題を解決するために，映画愛好者たちが感想や意見を共有し，
新しい映画を発見しながら楽しむためのプラットフォームが必要であると考えました．<br><br>
このアプリケーションは，映画愛好者たちが気軽にコミュニケーションを取りながら，映画の魅力を深めることを目的に制作しました。<br>

##  概要
「異なる映画を楽しみたいあなたに」
  MovieChatは以下のポイントを核心として設計されています．
- 好きな映画を選択しやすい映画検索機能
- 共有しやすい場になれるグループ作成機能
- 自分の興味あるグループを探しやすい検索機能
- 安全な映画同時視聴に向けた申請・承認システム

「多様性の中で映画の魅力を一緒に感じ，共有する」をコンセプトとしています．<br><br>
<a href="https://movie-chat-b904d14ac7cc.herokuapp.com" target="_blank">アプリへGO</a>

##  開発環境
<b>使用言語：</b><br>
- PHP
- HTML
- CSS
- JavaScript

<b>フレームワーク・ライブラリ：</b><br>
- Laravel (ver.9.52)
- Bulma (ver.0.9.3, CSSフレームワーク)
- Pusher (リアルタイムウェブアプリケーションツール)
- Cloudinary (画像管理サービス)

<b>外部API：</b><br>
- TMDB API (映画データベース)  

<b>開発環境・ツール：</b><br>
- AWS(Cloud9)
- MySQL(MariaDB) 

<b>バージョン管理・ホスティング：</b><br>
- Github

<b>デプロイ：</b><br>
- Heroku

##  データ構成
<b>テーブル構成・リレーション：</b><br>
![image](https://user-images.githubusercontent.com/117621598/260365909-9daf2976-252d-4ba6-8197-b74adcaae793.png)

##  機能
- ログイン
- 映画検索(TMDB API)
- グループの作成・検索・編集
- リアルタイムチャット
- 同時視聴の申請・承諾(視聴は外部アプリ)
- 投稿の作成・検索・編集・コメント
- プロフィール管理

##  こだわり
<b>リアルタイムチャット：</b><br>
WebSocketを活用することで，ユーザー同士がリアルタイムで意見交換や感想の共有が可能です．<br>
これにより，映画の同時視聴中でも，その場でのコミュニケーションが実現しています．<br><br>
<b>快適な映画の検索・選択：</b><br>
映画の検索と選択する機会が多いため，AJAXを利用したスムーズなページ遷移や，ページネーション機能を導入しました．<br>
また，映画やボタンの配置を工夫することで，直観的に選択することができます．<br><br>
<img src="https://github.com/shinkawakami/develop-moviechat/assets/117621598/e7d97df0-0964-4f7c-9b09-bb357edc7821" width="225"><br>
<b>高度な絞り込み検索：</b><br>
グループの検索時に，ジャンル・年代・視聴プラットフォームの指定で絞り込むことが可能です．<br>
これにより，自分の好みに合わせて迅速にグループの見つけることができます．<br><br>
<b>同時視聴の申請・承諾：</b><br>
映画の同時視聴をスムーズかつ安全に行うための申請・承認システムを採用しています．<br>
これにより，目的に合わないユーザーの参加を制限し，同時視聴をより楽しむことができます．<br>
<img src="https://github.com/shinkawakami/develop-moviechat/assets/117621598/466ebb2a-707c-4981-b979-18daddfc0f57" width="225">

##  楽しみ方
<b>映画の感想や意見を共有したいユーザー：</b><br>
自分の好みに合うグループの作成，または，検索を行い，グループに参加します．<br>
そのグループでリアルアイムチャットを行うことで，映画の魅力を一緒に感じることができます．<br><br>
<b>誰かと映画の同時視聴をしたいユーザー：</b><br>
グループ内で一緒に視聴したいユーザーを探し，同時視聴の申請を行います．<br>
申請が承諾されると，指定のURLで外部アプリで視聴しながら，視聴中のリアクションや感想を共有できます．<br><br>
<b>映画探索をしたいユーザー：</b><br>
映画の検索やグループでのチャット，投稿を閲覧することで，自分では気づけないジャンルの映画や映画の魅力に触れることができます．<br>

##  今後の計画
- 映画検索機能の強化（ジャンルや年代などの指定での絞り込み検索）
- ユーザーの好みの映画推薦システム（プロフィールの好きな映画を元に基にする）
- ページ遷移のユーザビリティ向上（ページを戻った際の前の情報の保持）
- ユーザーフォロー機能
- ユーザーのプロフィール閲覧
- 投稿への「いいね」機能


