# ![](https://avatars3.githubusercontent.com/u/26238188?v=3&s=35) 紀永（草案）

「紀永」とは、気長の読みを当て字した造語に由来し、「末永く書く」という意味を持たせた縁起の良い名称のコンテンツマネジメントシステムです。

閲覧者には、基本的なメニュー等構造の他、コメント等のレスポンス機能や、幅広いデバイスに対応するレスポンシブなデザインを提供し、一方の執筆者には、デスクトップ作業の要領で管理できる構造の他、テキストエディタに書いた通りの記事レイアウトを基本として、HTMLタグやPHPを補記することもでき、かつデジカメで撮影した画像をフォルダに入れるだけでスライドショーとして公開することができる機能等も提供します。

また外部機能として、既存サイトからの移行に役立つ変換システムや、HTMLタグの入力補助機能を備えつつ動的にレイアウトを確認することが出来るシステムの他、HTMLタグの入力補助を目的としたテキストエディタのプラグインも提供しています。

[デモサイト](http://xn--5rwx17a.xn--v8jtdudb.com/)、[紀永起草](http://xn--vl1al2s.xn--v8jtdudb.com)、[既存サイトからの移行について](http://xn--5rwx17a.xn--v8jtdudb.com/%E6%97%A2%E5%AD%98%E3%82%B5%E3%82%A4%E3%83%88%E3%81%8B%E3%82%89%E3%81%AE%E7%A7%BB%E8%A1%8C%E3%81%AB%E3%81%A4%E3%81%84%E3%81%A6)、[Pluma-Bootstrap-Snippet](https://github.com/KinagaCMS/Pluma-Bootstrap-Snippet)、[Pluma-Bootstrap-Taglist](https://github.com/KinagaCMS/Pluma-Bootstrap-Taglist)



## 動作環境

- PHP 5.5 かそれ以上
- Apache 2.2 かそれ以上
- .htaccess をサポートしていること



## インストール
	git clone https://github.com/KinagaCMS/KinagaCMS.git



## 初期設定

1.  /includes/config.php

　　言語、文字コード、テンプレート等を設定して下さい

2.  /includes/lang/○○.php

　　該当する言語ファイルを編集。もしくは既存ファイルを元に作成して下さい
  
　　サイト名や概要を記入して下さい


## 簡単な使い方

1.  ファイルマネージャの NFS か [WinSCP](https://winscp.net/) でサーバーに接続します
2.  「contents」フォルダの中に「カテゴリ名」のフォルダを作成して下さい
3.  「カテゴリ名」フォルダの中に「記事名」のフォルダを作成して下さい
4.  「記事名」フォルダの中に「index.html」ファイルを作成し、文章を入力して下さい

		contents
		│
		├── カテゴリ名フォルダ
		│	│
		│	├── 記事名フォルダ
		│	│	│
		│	│	├── comments ( コメント欄が表示されます。必須ではありません )
		│	│	│
		│	│	├── counter.txt ( よく読まれている記事が表示されます。必須ではありません )
		│	│	│
		│	│	├── images ( または background-images。必須ではありません )
		│	│	│
		│	│	└── index.html ( ここに記事を書いて下さい )
		│	│
		│	└── index.html ( カテゴリのサブタイトルです。必須ではありません )
		│
		├── サイドページ.html ( インフォメーションに表示されます。必須ではありません )
		│
		└── index.html ( トップページになります。必須ではありません )



## コントリビューション
各国語翻訳、デザイン等修正、バグフィクスなどを歓迎します。



## ライセンス

- 紀永には GPL v3 かそれ以上のライセンスが適用されています。 詳しくは、LICENSE をご一読下さい。
- 紀永に使用した [Bootstrap](http://getbootstrap.com/)、[jQuery](http://jquery.com/)、[Magnific Popup](http://dimsemenov.com/plugins/magnific-popup/) には MIT ライセンスが適用されています。
