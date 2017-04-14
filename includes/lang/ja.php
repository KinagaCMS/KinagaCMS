<?php
/*
  * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
  * @license    GPL 3.0 or later; see LICENSE file for details.
  */

date_default_timezone_set( 'Asia/Tokyo' );

setlocale( LC_ALL, 'ja_JP.' . $encoding );


#サイト名：
$site_name = 'kinaga';

#住所：サイドボックス、メールフッターに表示
$address = '';

#色相：赤、橙、黄、鶯、緑、碧、青、紺、紫、桃、茶、または空欄
$color = '緑';

#キャッチコピー：トップページのメタ情報とヘッダーに表示
$meta_description = 'ここにはサイトの概要を記入して下さい。';

#サブタイトル：H1タイトルの横とタイトルタグに表示
$subtitle = '簡易CMS「紀永」のインストールが完了しました';


#トップページの名称
$home = 'ホーム';


#サイドボックスのタイトル
$recents = '新着記事';

$informations = 'インフォメーション';

$recent_comments = '最近のコメント';

$popular_articles = '良く読まれている記事';

$download_contents = 'ダウンロード';

$contact_us = 'お問い合わせ';


#類似記事のタイトル
$similar_title = '類似する記事';


#ページナビゲーション：記号
$nav_laquo = '&laquo;';

$nav_raquo = '&raquo;';


#ページナビゲーション：コメント
$comments_prev = '最近のコメント';

$comments_next = '過去のコメント';


#お問い合わせ小見出し
$contact_subtitle = 'ご意見・ご質問を承ります';

#ダウンロード小見出し
$download_subtitle = '以下のファイル名をクリックして保存して下さい';

#接頭辞：ページ
$page_prefix = 'Page %s';


#引用リンク
$permalink = '引用リンク <small>下記のアドレスをコピーしてご利用下さい</small>';

$for_html = 'HTML 用';

$for_wiki = 'Wiki 用';

$for_forum = 'フォーラム 用';

#セパレーター兼ページトップ
$top = '<a class="text-primary page-top" href="#TOP"><span class="glyphicon glyphicon-chevron-up"></span></a>';

#記事とコメント
$last_modified = '最終更新日：%s';

$no_article = '記事が見つかりませんでした';

$no_categ = 'そのカテゴリは存在しません';

$error = 'エラーが発生しました';

$not_found =

	'ページが削除されているか、アドレスが間違っている可能性があります。' . $n .
	'トップページに戻ってから、もう一度ご確認下さい。';

$more_link_text = '続きを読む…';

$ellipsis = '…';

$display_counts = '閲覧回数：%s';

$view = '%s Views';

$images_count_title = ' （画像：%s枚）';

$source = '出典：%s';

$result = '%s の検索結果';

$no_results_found = '該当する記事が見つかりませんでした';

$comments_not_allow = '※現在コメントは受け付けておりません';

$comments_count_title = ' （コメント：%s件）';

$comment_title = 'コメント';

$comment_counts = 'コメント：%s';

$contact_caution = '承認されたコメントのみ掲載されます';

$separator = '────────────────────────────────────';

$comment_acceptance =

	'このコメントを掲載するには、' . $n .
	'添付ファイルの「%s」を保存してから、' . $n .
	'下記のフォルダ内にアップロードして下さい' . $n . $n .
	$s.'contents'.$s.'%s'.$s.'%s'.$s.'comments'.$s;

$contact_name = 'お名前';

$placeholder_name = 'お名前をご記入下さい';

$contact_mail = 'メールアドレス';

$placeholder_mail = 'メールアドレスをご記入下さい';

$contact_message = '内容';

$placeholder_message = 'ご質問などをご記入下さい';

$contact_preview = '内容確認';

$contact_cancel = '訂正';

$contact_send = '送信';

$cookie_disabled_error = 'Cookie を有効にして下さい';

$contact_subject_suffix = '%s様よりお問い合わせ - ';

$comment_subject = '「%s」の「%s」にコメント - ';

$contact_success = '送信しました';

$contact_error = '送信に失敗しました';

$time_format = 'Y年n月j日';

$present_format = 'n月j日';

$seconds_ago = '秒前';

$minutes_ago = '分前';

$hours_ago = '時間前';

$days_ago = '日前';


#/images/index.php
$images_title = '画像一覧 - %s';

$images_heading = '画像一覧 <small>タグをコピーしてお使い下さい</small>';

$images_aligner = '画像の位置指定 <small>回り込み解除には「&lt;div class=clearfix&gt;&lt;/div&gt;」を必要とする場合があります</small>';

$noscript = '<strong>javascript</strong> を有効にして下さい';

$align_left = '左寄せ';

$align_center = '中央寄せ';

$align_right = '右寄せ';

#1ページあたりの表示枚数
$number_of_imgs = '3';

$large_image = 'Large';

$small_image = 'Small';

$imgs_prev = '前のページ';

$imgs_next = '次のページ';


function hsla( $h, $s = 100, $l = 50, $a = 1 ) {

	$hue = array(
		'赤' => '0',
		'橙' => '35',
		'黄' => '50',
		'鶯' => '65',
		'緑' => '85',
		'碧' => '170',
		'青' => '195',
		'紺' => '220',
		'紫' => '265',
		'桃' => '330',
		'茶' => '25'
	);

	if ( isset( $hue[$h] ) ) return "hsla( $hue[$h], $s%, $l%, $a )";

}


function color2class( $colour ) {

	switch ( true ) {

		case $colour == '緑' || $colour == '鶯': return 'success';

		case $colour == '橙' || $colour == '黄' || $colour == '茶': return 'warning';

		case $colour == '赤' || $colour == '紫' || $colour == '桃': return 'danger';

		default: return 'info';
	}

}

