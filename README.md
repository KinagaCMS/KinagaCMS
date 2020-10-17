<p align="center"><a href="https://xn--5rwx17a.xn--v8jtdudb.com/"><img src="https://user-images.githubusercontent.com/25574701/68562174-d6dd1600-048b-11ea-9e72-c82b51ae28e6.png" alt="kinaga" width="300"><br><b>KinagaCMS7</b> デモサイト</a></p>

---

## 概要

Linux ユーザーに最適化されたコンテンツ管理システム

ファイルシステムからのコンテンツ管理を特徴とし、簡単なルールに沿ってフォルダやファイルを配置するだけで Web サイトが完成します

---

## 動作環境

- PHP ７ 以上
- <a href="https://www.php.net/manual/ja/book.exif.php">Exif</a>、<a href="https://www.php.net/manual/ja/book.image.php">GD</a> がインストールされていること
- .htaccess 及び RewriteEngine が利用可能であること
- <a href="https://github.com/DOlDNa/lapi">lapi</a> 推奨

---

## スクリーンショット

![screenshot](https://user-images.githubusercontent.com/25574701/77500292-f5223c00-6e97-11ea-814e-4775411f18d2.png)

---

## インストール

- [ダウンロード](https://github.com/KinagaCMS/KinagaCMS/archive/master.zip)

- パーミッションなどを適宜変更してから公開ディレクトリにアップロードして下さい

---

## 初期設定

1.  /includes/config.php

　　テンプレート、表示言語、文字コードなどを設定

2.  /includes/languages/○○.php

　　サイト名、メールアドレス、テーマカラーなどを設定

---

## 簡単な使い方

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


