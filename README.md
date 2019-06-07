<p align="center"><a href="https://xn--5rwx17a.xn--v8jtdudb.com/"><img src="https://user-images.githubusercontent.com/25574701/52907838-fc4a4400-32ac-11e9-8099-d4b7eaa042a8.png" alt="kinaga" width="300"></a><br><b>Kinaga</b> v6.5 → <a href="https://xn--5rwx17a.xn--v8jtdudb.com/">Demo</a></p>

---

## 概要

Linux ユーザーに最適化されたコンテンツ管理システム

ファイルシステムからのコンテンツ管理を特徴とし、簡単なルールに沿ってフォルダやファイルを配置するだけで Web サイトが完成します

---

## 動作環境

- PHP ７ 以上
- Apache 2.2 以上
- .htaccess 及び RewriteEngine が利用可能であること

---

## スクリーンショット

![screenshot](https://user-images.githubusercontent.com/25574701/54064868-324f6800-425c-11e9-9afd-765b198375e6.png)

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

1.  Linux ユーザーはファイルマネージャ、Windows ユーザーは [WinSCP](https://winscp.net/) などでサーバーに接続
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
		│	│	└── index.html (ここに記事を書いて下さい。必須)
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


