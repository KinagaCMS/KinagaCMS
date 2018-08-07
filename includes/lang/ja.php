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

#色相：'緋', '薔薇', '甚三紅', '蜜柑', '黄水晶', '萌黄', '苔', '薄緑', '千歳緑', '青碧', '瑠璃', '濃藍', '紺桔梗', '菫', '葡萄', '焦茶', '珈琲', '白', '月白', '白藤', '金色', '白柳', '灰', '青鈍', '紺鼠', '源氏鼠', '砂鼠', '黒', または空欄
$color = '紺鼠';

#キャッチコピー：トップページのメタ情報とヘッダーに表示
$meta_description = 'ここにはサイトの概要を記入して下さい。';

#サブタイトル：H1タイトルの横とタイトルタグに表示
$subtitle = '';


#トップページの名称
$home = 'ホーム';


#サイドボックスのタイトル
$recents = '新着記事';

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

$display_counts = '閲覧回数：%s';

$view = '%s Views';

$images_count_title = ' （画像：%s枚）';

$source = '出典：%s';

$browse = '選択';

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

$time_format = 'Y年n月j日';

$present_format = 'n月j日';

$seconds_ago = '秒前';

$minutes_ago = '分前';

$hours_ago = '時間前';

$days_ago = '日前';


#/images/index.php
$images_title = '画像一覧 - %s';

$images_heading = '画像一覧 <small class=text-muted>タグをコピーしてお使い下さい</small>';

$images_aligner = '画像の位置指定 <small class=text-muted>回り込み解除には「&lt;div class=clearfix&gt;&lt;/div&gt;」を必要とする場合があります</small>';

$noscript = '<strong>javascript</strong> を有効にして下さい';

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

function hsla($h, $cal_s=0, $cal_l=0, $a=1)
{
	if ($h === '緋')
	{
		$hue = 355;
		$s = 65;
		$l = 40;
	}
	elseif ($h === '薔薇')
	{
		$hue = 330;
		$s = 75;
		$l = 40;
	}
	elseif ($h === '甚三紅')
	{
		$hue = 3;
		$s = 77;
		$l = 71;
	}
	elseif ($h === '蜜柑')
	{
		$hue = 30;
		$s = 98;
		$l = 42;
	}
	elseif ($h === '黄水晶')
	{
		$hue = 48;
		$s = 86;
		$l = 40;
	}
	elseif ($h === '萌黄')
	{
		$hue = 80;
		$s = 75;
		$l = 40;
	}
	elseif ($h === '苔')
	{
		$hue = 70;
		$s = 65;
		$l = 30;
	}
	elseif ($h === '薄緑')
	{
		$hue = 131;
		$s = 45;
		$l = 40;
	}
	elseif ($h === '千歳緑')
	{
		$hue = 142;
		$s = 36;
		$l = 30;
	}
	elseif ($h === '青碧')
	{
		$hue = 190;
		$s = 60;
		$l = 37;
	}
	elseif ($h === '瑠璃')
	{
		$hue = 214;
		$s = 69;
		$l = 38;
	}
	elseif ($h === '濃藍')
	{
		$hue = 222;
		$s = 65;
		$l = 10;
	}
	elseif ($h === '紺桔梗')
	{
		$hue = 232;
		$s = 44;
		$l = 40;
	}
	elseif ($h === '菫')
	{
		$hue = 259;
		$s = 40;
		$l = 40;
	}
	elseif ($h === '葡萄')
	{
		$hue = 290;
		$s = 40;
		$l = 38;
	}
	elseif ($h === '焦茶')
	{
		$hue = 16;
		$s = 28;
		$l = 34;
	}
	elseif ($h === '珈琲')
	{
		$hue = 39;
		$s = 56;
		$l = 30;
	}
	elseif ($h === '白')
	{
		$hue = 0;
		$s = 0;
		$l = 42;
	}
	elseif ($h === '月白')
	{
		$hue = 200;
		$s = 18;
		$l = 42;
	}
	elseif ($h === '白藤')
	{
		$hue = 270;
		$s = 18;
		$l = 42;
	}
	elseif ($h === '金色')
	{
		$hue = 53;
		$s = 45;
		$l = 42;
	}
	elseif ($h === '白柳')
	{
		$hue = 75;
		$s = 45;
		$l = 42;
	}
	elseif ($h === '灰')
	{
		$hue = 0;
		$s = 0;
		$l = 40;
	}
	elseif ($h === '青鈍')
	{
		$hue = 131;
		$s = 10;
		$l = 40;
	}
			elseif ($h === '紺鼠')
	{
		$hue = 210;
		$s = 30;
		$l = 40;
	}
	elseif ($h === '源氏鼠')
	{
		$hue = 320;
		$s = 10;
		$l = 40;
	}
	elseif ($h === '砂鼠')
	{
		$hue = 50;
		$s = 10;
		$l = 40;
	}
	elseif ($h === '黒')
	{
		$hue = 0;
		$s = 0;
		$l = 0;
	}
	if (isset($hue, $s, $l))
		return 'hsla(' . $hue . ', ' . ($s + (int)$cal_s) . '%, ' . ($l + (int)$cal_l) . '%, ' . $a . ')';
}

function color2class($colour)
{
	if ($colour === '白' || $colour === '月白' || $colour === '白藤'|| $colour === '金色' || $colour === '白柳' )
		return 'white';
	elseif ($colour === '灰' || $colour === '青鈍' || $colour === '紺鼠' || $colour === '源氏鼠' || $colour === '砂鼠')
		return 'secondary';
	elseif ($colour === '黒' || $colour === '濃藍')
		return 'dark';
	elseif ($colour === '焦茶' || $colour === '珈琲')
		return 'muted';
	elseif ($colour === '薄緑' || $colour === '苔' || $colour === '萌黄' || $colour === '千歳緑')
		return 'success';
	elseif ($colour === '蜜柑' || $colour === '黄水晶' )
		return 'warning';
	elseif ($colour === '緋' || $colour === '薔薇' || $colour === '甚三紅' || $colour === '葡萄' || $colour === '菫')
		return 'danger';
	elseif ($colour === '青碧' || $colour === '瑠璃' || $colour === '紺桔梗')
		return 'info';
	else
		return 'primary';
}
