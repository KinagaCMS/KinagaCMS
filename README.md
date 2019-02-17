<p align="center"><img src="https://user-images.githubusercontent.com/25574701/52907838-fc4a4400-32ac-11e9-8099-d4b7eaa042a8.png" alt="kinaga" width="300"><br><b>Kinaga</b> v6.5 → <a href="https://xn--5rwx17a.xn--v8jtdudb.com/">Demo</a></p>

---

## 概要

Linux ユーザーに最適化された CMS

ファイルシステムからのコンテンツ管理を特徴とし、簡単なルールに沿ってフォルダやファイルを配置するだけで Web サイトが完成します

---

## 動作環境

- PHP ７ 以上
- Apache 2.2 以上
- .htaccess 及び RewriteEngine が利用可能であること

---

## スクリーンショット

![screenshot](https://user-images.githubusercontent.com/25574701/52907862-898d9880-32ad-11e9-8bbc-7d3142a4b719.jpg)

---

## インストール

- [ダウンロード](https://github.com/KinagaCMS/KinagaCMS/releases)

- パーミッションなどを適宜変更してから公開ディレクトリにアップロードして下さい

---

## 初期設定

1.  /includes/config.php

　　テンプレート、表示言語、文字コードなどを設定することができます

2.  /includes/languages/○○.php

　　サイト名やメールアドレス、テーマカラーなどを設定することができます

---

## 簡単な使い方

1.  ファイルマネージャまたは[WinSCP](https://winscp.net/)でサーバーに接続します
2.  contents フォルダの中に「<b>カテゴリ名</b>」のフォルダを作成して下さい
3.  カテゴリ名フォルダの中に「<b>記事名</b>」のフォルダを作成して下さい
4.  記事名フォルダの中に「<b>index.html</b>」ファイルを作成し、[ReText](https://github.com/retext-project/retext)、[Geany](https://github.com/geany/geany/)などのテキストエディタで文章を作成して下さい
5.  また、フォルダやテキストを以下のように配置することで追加機能を利用することも出来ます


		contents
		│
		├── カテゴリ名フォルダ
		│	│
		│	├── 記事名フォルダ
		│	│	│
		│	│	├── comments ( コメント欄が表示されます。必須ではありません )
		│	│	│
		│	│	├── counter.txt ( カウンター。よく読まれている記事が表示されます。必須ではありません )
		│	│	│
		│	│	├── images ( または background-images など。必須ではありません )
		│	│	│
		│	│	└── index.html ( ここに記事を書いて下さい )
		│	│
		│	└── index.html ( カテゴリのサブタイトルです。必須ではありません )
		│
		├── サイドページ.html ( インフォメーションに表示されます。必須ではありません )
		│
		├── サイドページ２.html ( サイドページはファイル名がタイトルとなります )
		│
		└── index.html ( トップページ。必須ではありません )
---

## ライセンス
-  [GPL v3](https://github.com/KinagaCMS/KinagaCMS/blob/master/LICENSE)
