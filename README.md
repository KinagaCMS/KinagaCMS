<p align="center"><a href="https://xn--5rwx17a.xn--v8jtdudb.com/"><img src="https://user-images.githubusercontent.com/25574701/68562174-d6dd1600-048b-11ea-9e72-c82b51ae28e6.png" alt="kinaga" width="300"><br><b>KinagaCMS8</b> デモサイト</a></p>

---

## 概要

簡単なルールに沿ってフォルダやファイルを配置するだけでウェブサイトを構築できる他、
パスワード不要の独自ログイン・システムによる複数人での記事の作成・管理や、
会員および有料会員向け記事の作成、ショッピング機能、フォーラム等の多数機能を備えるシンプルで軽量なコンテンツ管理システム。

---

## 動作環境

- PHP 8 以上
- <a href="https://www.php.net/manual/ja/book.exif.php">Exif</a>、<a href="https://www.php.net/manual/ja/book.image.php">GD</a>、<a href="https://www.php.net/manual/ja/book.imagick.php">ImageMagick</a> がインストールされていること
- .htaccess 及び RewriteEngine が利用可能であること
- <a href="https://github.com/DOlDNa/lapi">lapi</a> 推奨

---

## スクリーンショット

![screenshot](https://user-images.githubusercontent.com/25574701/106701937-dbf72980-662a-11eb-867d-5ca376733587.png)

---

## インストール

- docker pull kinaga/kinaga

または

- [ダウンロード](https://github.com/KinagaCMS/KinagaCMS/archive/master.zip)

- パーミッションなどを適宜変更してから公開ディレクトリにアップロードして下さい

---

## 初期設定

1.  /includes/config.php

　　テンプレート、表示言語、文字コードなどを設定

2.  /includes/languages/○○.php

　　サイト名、管理者メールアドレス、テーマカラーなどを設定

---

## 簡単な使い方

管理者メールアドレスが有効な場合は、ログイン後にカテゴリの作成や記事の投稿ができます。
詳しくは、<a href="https://xn--5rwx17a.xn--v8jtdudb.com/Kinaga%20Tips/%E2%96%A0%E3%82%B7%E3%82%B9%E3%83%86%E3%83%A0%E8%A7%A3%E8%AA%AC%E2%96%A0%20%E3%83%AD%E3%82%B0%E3%82%A4%E3%83%B3%E3%81%AB%E3%81%A4%E3%81%84%E3%81%A6" target="_blank">■システム解説■ ログインについて - KinagaCMS8</a> や <a href="https://xn--5rwx17a.xn--v8jtdudb.com/Kinaga%20Tips/%E3%80%90%E7%B4%80%E6%B0%B88%E6%96%B0%E6%A9%9F%E8%83%BD%E3%80%91%E7%AE%A1%E7%90%86%E8%80%85%E3%81%8A%E3%82%88%E3%81%B3%E5%89%AF%E7%AE%A1%E7%90%86%E8%80%85%E3%81%8C%E3%81%A7%E3%81%8D%E3%82%8B%E3%81%93%E3%81%A8" target="_blank">【紀永8新機能】管理者および副管理者ができること - KinagaCMS8</a> を参照して下さい。
従来の作成方法は下記の通りです。

1.  Linux ユーザーはファイルマネージャでサーバーに接続
2.  contents フォルダ内に「<b>カテゴリ名</b>」フォルダを作成
3.  カテゴリ名フォルダ内に「<b>記事名</b>」フォルダを作成
4.  記事名フォルダの中に「<b>index.html</b>」ファイルを作成し、[ReText](https://github.com/retext-project/retext)、[Geany](https://github.com/geany/geany/) などのテキストエディタで文章を作成
5.  また、フォルダやテキストを以下のように配置することで追加機能を利用することも出来ます


		contents
		│
		├── カテゴリ名フォルダ
		│	│
		│	├── 記事名フォルダ
		│	│	│
		│	│	├── comments (コメント欄が表示されます。必須ではありません)
		│	│	│
		│	│	├── counter.txt (カウンター。よく読まれている記事が表示されます。必須ではありません)
		│	│	│
		│	│	├── images (または background-images など。必須ではありません)
		│	│	│	│
		│	│	│	├── sample01.jpg (名前の順で自動掲載されます)
		│	│	│	│
		│	│	│	└── sample02.jpg
		│	│	│
		│	│	├── login.txt (記事を会員制限します。必須ではありません)
		│	│	│
		│	│	└── index.html (ここに記事を書いて下さい。必須)
		│	│
		│	├── login.txt (カテゴリ全体を会員制限します。必須ではありません)
		│	│
		│	└── index.html (カテゴリのサブタイトルです。必須ではありません)
		│
		├── サイドページ.html (インフォメーションに表示されます。必須ではありません)
		│
		├── サイドページ２.html (サイドページはファイル名がタイトルとなります)
		│
		└── index.html (トップページ。必須ではありません)
---

## ライセンス
-  [GPL v3](https://github.com/KinagaCMS/KinagaCMS/blob/master/LICENSE)


