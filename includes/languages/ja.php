<?php
setlocale(LC_ALL, 'ja_JP.UTF-8');
date_default_timezone_set('Asia/Tokyo');

#サイト名：
$site_name = 'kinaga';

#メールアドレス：お問い合わせとコメントを利用する場合は必須
$mail_address = '';

#住所：サイドボックスに表示
$address = '';
#空欄の場合はサイト名を表示
$address_title = '';

#色：'赤', '桃', '茶', '黄緑', '緑', '青', '水', '紫', '灰', '黒', または空欄
#hex, rgb, hsl も指定可。但し、テンプレートによっては明度が高すぎると可読性低下
$color = '青';

#キャッチコピー：トップページのメタ情報とヘッダーに表示
$meta_description = 'ここにはサイトの概要を記入して下さい。';

#サブタイトル：H1タイトルの横とタイトルタグに表示
$subtitle = '';

#トップページの名称
$home = 'ホーム';

#サイドボックスのタイトル
$recents = '新着記事';

$category = 'カテゴリ';

$informations = 'インフォメーション';

$recent_comments = '最近のコメント';

$popular_articles = '良く読まれている記事';

$download_contents = 'ダウンロード';
#ダウンロード小見出し
$download_subtitle = '';
#ダウンロード注釈
$download_notice = '';

$contact_us = 'お問い合わせ';
#お問い合わせ小見出し
$contact_subtitle = '';
#お問い合わせ注釈
$contact_notice = '当フォームより収集される個人情報は、返信を要する際に使用されるものであり、法令に基づく行政機関等への提供を除き、ご本人の同意を得ずに第三者に提供することはありません。';

#類似記事のタイトル
$similar_title = '類似する記事';

#目次
$toc = '目次';

#ページナビゲーション：記号
$nav_laquo = '&laquo;';

$nav_raquo = '&raquo;';


#ページナビゲーション：コメント
$comments_prev = '最近のコメント';

$comments_next = '過去のコメント';


#接頭辞：ページ
$page_prefix = 'Page %s';

#ソーシャルアイコン
$social = 'シェア';

#引用リンク
$permalink = '引用リンク';

$for_html = 'HTML';

$for_wiki = 'Wiki';

$for_forum = 'フォーラム';

#セパレーター兼ページトップ
$top = '<a class="page-top text-right d-block p-0 small" href="#TOP"> </a>';
$pagetop = 'このページのトップへ';

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

$views = '閲覧回数：%s';

$images_count_title = ' （画像：%s枚）';

$source = '出典：%s';

$browse = '選択';

$result = '%s の検索結果';

$no_results_found = '該当する記事が見つかりませんでした';

$comments_not_allow = '※現在コメントは受け付けておりません';

$comments_count_title = ' （コメント：%s件）';

$comment_title = 'コメント';

$comment_notice = $contact_notice . 'また、コメントが掲載される場合であってもメールアドレスが本サイト内に記載されることはありません。';

$comment_counts = 'コメント：%s';

$contact_caution = '承認されたコメントのみ掲載されます';

$separator = '────────────────────────────────────';

$comment_acceptance =
	'このコメントを掲載するには、' . $n .
	'添付ファイルの「%s」を保存してから、' . $n .
	'下記のフォルダ内にアップロードして下さい' . $n . $n .
	'/contents/%s/%s/comments/';

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

$time_format = 'Y年n月j日 H時i分';

$present_format = 'n月j日';

$seconds_ago = '%s秒前';

$minutes_ago = '%s分前';

$hours_ago = '%s時間前';

$days_ago = '%s日前';

$benchmark_results = '<span class="d-block text-muted text-center small">処理時間：%s秒　消費メモリ：%s</span>';


#/images/index.php
$images_title = '画像一覧 - %s';

$images_heading = '画像一覧 <small class="text-muted ml-2">タグをコピーしてお使い下さい</small>';

$images_aligner = '画像の位置指定 <small class="text-muted ml-2">回り込み解除には「&lt;div class=clearfix&gt;&lt;/div&gt;」を必要とする場合があります</small>';

$noscript = '<strong>Javascript</strong> を有効にして下さい';

$align_left = '左寄せ';

$align_center = '中央寄せ';

$align_right = '右寄せ';

#1ページあたりの表示枚数
$number_of_imgs = 4;

$large_image = 'Large';

$small_image = 'Small';

$imgs_first= '最初';
$imgs_prev = '前';

$imgs_next = '次';
$imgs_last = '最後';

function hsla($colour, $cal_s=0, $cal_l=0, $a=1)
{
	if ($colour === '赤')
	{
		$h = 355;
		$s = 70;
		$l = 50;
	}
		elseif ($colour === '桃')
	{
		$h = 330;
		$s = 70;
		$l = 70;
	}
	elseif ($colour === '茶')
	{
		$h = 10;
		$s = 70;
		$l = 50;
	}
	elseif ($colour === '黄緑')
	{
		$h = 80;
		$s = 60;
		$l = 50;
	}
	elseif ($colour === '緑')
	{
		$h = 120;
		$s = 60;
		$l = 40;
	}
	elseif ($colour === '青')
	{
		$h = 220;
		$s = 60;
		$l = 60;
	}
		elseif ($colour === '水')
	{
		$h = 195;
		$s = 60;
		$l = 60;
	}
	elseif ($colour === '紫')
	{
		$h = 250;
		$s = 60;
		$l = 70;
	}
	elseif ($colour === '灰')
	{
		$h = 200;
		$s = 5;
		$l = 60;
	}
	elseif ($colour === '黒')
	{
		$h = 0;
		$s = 0;
		$l = 10;
	}
	else list($h, $s, $l) = get_hsl($colour);
	if (isset($h, $s, $l))
		return 'hsla('. $h. ', '. ($s + (int)$cal_s). '%, '. ($l + (int)$cal_l). '%, '. $a. ')';
}

function color2class($colour)
{
	if ($colour === '灰')
		return 'secondary';
	elseif ($colour === '黒' )
		return 'dark';
	elseif ($colour === '茶')
		return 'muted';
	elseif ($colour === '黄緑' || $colour === '緑')
		return 'success';
	elseif ($colour === '桃')
		return 'warning';
	elseif ($colour === '赤' || $colour === '紫')
		return 'danger';
	elseif ($colour === '青' || $colour === '水')
		return 'info';
	else
		return 'primary';
}
