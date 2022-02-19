<?php
if (__FILE__ === implode(get_included_files())) exit;
setlocale(LC_ALL, 'ja_JP.UTF-8');
date_default_timezone_set('Asia/Tokyo');

#サイト名
#第一階層の images に logo.png があればヘッダーロゴ表示
#テンプレートで get_logo(true) とした場合はサイト名を併記、get_logo(true, 'クラス名') でクラス指定可
$site_name = 'KinagaCMS8';

#赤, 桃, 茶, 黄緑, 緑, 青, 水, 紫, 灰, 黒, 空欄
#hex, rgb, hsl も指定可。但し、テンプレートによっては明度が高すぎると可読性低下
$color = '水';

#メールアドレス：お問い合わせ、コメント、ログインを利用する場合は必須
#マルチバイトドメインは Punycode 変換が必須
$mail_address = '';

#管理者接尾語
$admin_suffix = ['管理者', '副管理者'];

#住所：サイドボックス内に表示
$address = '';
#空欄の場合はサイト名を表示
$address_title = '';
#住所下の追加情報：配列を増やすことで複数行に表示
$additional_address = ['', '', '', '', ];
#ジオコーダ：住所を座標に変換するサイト
#空欄の場合は地図非表示
$geocoder = 'https://msearch.gsi.go.jp/address-search/AddressSearch?q=';

#キャッチコピー：トップページのメタ情報とヘッダーに表示
$meta_description = 'ここにはサイトの概要を記入して下さい。';

#サブタイトル：H1の横とtitleタグに表示
$subtitle = '';

#トップページの名称
$home = 'ホーム';

#サイドボックスのタイトル
$sidebox_title = ['新着記事', 'インフォメーション', '良く読まれている記事', '最近のコメント', 'ログイン', 'こんにちは。%sさん', '関連記事', 'シェア', '引用リンク', '目次', $category = 'カテゴリ', '最近のトピック'];

#ダウンロード
$download_contents = 'ダウンロード';
#ダウンロード小見出し
$download_subtitle = '';
#ダウンロード注釈
$download_notice = '';

#お問い合わせ
$contact_us = 'お問い合わせ';
#お問い合わせ小見出し
$contact_subtitle = '';
#お問い合わせ：個人情報保護方針
$privacy_policy = '当フォームより収集される個人情報は、返信を要する際に使用されるものであり、法令に基づく行政機関等への提供を除き、ご本人の同意を得ずに第三者に提供することはありません。';

$forum = 'フォーラム';

#ページナビゲーション：記号
$nav_laquo = '&laquo;';
$nav_raquo = '&raquo;';

#接頭辞：ページ
$page_prefix = 'Page %d';


#各種引用リンク
$permalink = ['HTML', 'Wiki', $forum];

#セパレーター兼ページトップ
$top = '<a class="page-top text-end d-block p-0 small" href="#TOP"> </a>';
$pagetop = 'このページのトップへ';

#記事
$date_created = '作成 %s';
$last_modified = '更新 %s';

$article_prevnext = ['次の記事', '前の記事'];

$not_found = [
'エラーが発生しました',
'ページが削除されているか、アドレスが間違っている可能性があります。'. $n. 'トップページに戻ってから、もう一度ご確認下さい。',
'該当する記事が見つかりませんでした'
];

$ellipsis = '…';

$views = '閲覧 %s';

$images_count_title = ' （画像 %d枚）';

$source = '出典 %s';

$browse = '選択';
$custom_file_label = '参照';

$result = '%s の検索結果';

$comment = 'コメント';
$comments_not_allow = '※現在'. $comment. 'は受け付けておりません';
$comments_count_title = ' （'. $comment. '：%d件）';
$comment_privacy_policy = $privacy_policy. 'また、'. $comment. 'が掲載される場合であってもメールアドレスが本サイト内に記載されることはありません。';
$comment_counts = $comment. ' %d';
$comment_note = [
	'承認された'. $comment. 'のみ掲載されます',
	'end.txt をアップロードすると、コメントの受付を停止します'
];

$comment_subject = '「%s」の「%s」に'. $comment. ' - ';

#ページナビゲーション：コメント
$comments_prev = '最近の'. $comment;
$comments_next = '過去の'. $comment;

$separator = '────────────────────────────────────';

$hitcount = '%d件';

$comment_acceptance =
	'この'. $comment. 'を掲載するには、'. $n.
	'添付ファイルの「%s」を保存してから、'. $n.
	'下記のフォルダ内にアップロードして下さい'. $n. $n.
	'/contents/%s/%s/comments/';

$contact_label = ['お名前', 'メールアドレス', '内容'];

$contact_message = [
	$contact_label[0]. 'を記入して下さい',
	$contact_label[1]. 'を記入して下さい',
	$contact_label[2]. 'を記入して下さい',
	'Cookie を有効にして下さい',
	'送信しました',
	'送信に失敗しました',
	];

$contact_preview = '内容確認';

$contact_subject_suffix = '%s様よりお問い合わせ - ';

$time_format = 'Y.n.j H:i';
$short_time_format = '%d月%d日（%s）';
$long_time_format = '%d年%02d月%02d日%02d時%02d分';

$btn = ['訂正', '送信', '保存', '拒否', '削除', '承認', '掲載', '編集', 'プレビュー', '続きを読む…'];

$intervals = ['y' => '年前', 'm' => 'ヶ月前', 'w' => '週間前', 'd' => '日前', 'h' => '時間前', 'i' => '分前', 's' => '秒前'];

#ログイン
$login = 'ログイン';
$login_message = ['ログインにはメールアドレスが必要となります。', 'メールアドレス宛に「ログイン・チケット」を送付します。', 'ログイン成功', 'もう一度ログインする際は、ログイン・チケットを取得して下さい。'];
$login_warning = ['不承認ファイル', '不正ファイル', 'ファイル失効', 'アップロードエラー'];
$ticket_warning = ['送信エラー', 'セッションエラー', 'ファイル失効', '内部エラー'];
$logout = 'ログアウト';

$login_try_again = '<a class=text-danger href="&'. r($login). '='. $now. '#login">こちら</a>からもう一度メールアドレスを入力して下さい。';

$login_required = ['続きを読むにはログインが必要です。', $comment. 'を投稿するにはログインが必要です。', $comment. 'の閲覧および投稿にはログインが必要です。'];

$ticket_subject = 'ログイン・チケットの発行';
$ticket_body =
	'このメールは「%s」からの要求により送信しています。'. $n. $n.
	'添付ファイル（%s）を保存し、'. $n. $time_limit. '分以内（%sまで）に所定の場所にアップロードして下さい。'. $n. $n.
	'アップロード完了後は、保存したファイルと本メールを削除することができます。';

$ticket_title = ['ログイン・チケットを送信しました', 'ログイン・チケットのアップロード'];
$ticket_process = [
	'<strong>'. $time_limit. '分以内<small>（%sまで）</small></strong>に以下の操作を行って下さい。',
	'メールチェック<small>（迷惑メールとなっている場合があります）</small>',
	'添付ファイルの保存',
	'保存ファイルのアップロード',
	'保存ファイルと受信メールの削除'
	];

$prof_title = [
	'Myプロフィール',
	'特にありません',
	'%sさんのプロフィール',
	'コンタクト<small>の承認と拒否</small>',
	'ユーザー管理',
	'直近<small>のログイン</small>',
	'ログイン・チケット<small>を今すぐゲット！</small>',
	'%sさんの実績',
	'最近の記事',
	'アクティビティ',
	'まだ実績はありません',
	];
$prof_label = ['ハンドルネーム', 'アバター', '画像の削除／色の変更', '自己紹介', 'アプローチ', '他のユーザーからコンタクトを受ける'];
$prof_note = [
	'空欄の場合はメールアドレスのローカル部が表示されます',
	'他のユーザーと重複することもあります',
	'100KB以下のJPEGまたはPNGに限ります',
	'HTMLタグは使用できません',
	'ログインすると%sさんにアプローチすることもできます。',
	'ログインして'. $admin_suffix[0]. 'の%sさんにアプローチすると「'. $admin_suffix[1]. '」として共同執筆することもできるようになります。'
	];

$approach_subject = [
	'%sさんからコンタクトの承認がありました',
	'%sさんからコンタクトを拒否されました',
	'%sさんからコンタクトの着信を停止されました',
	'%sさんからメッセージがあります',
	'%sさんからアプローチがありました'
	];
$approach_body = [
	'ログインしてから、%1$sさんにメッセージを送信して下さい。'. $n. $n. '%1$sさんのプロフィールはこちらです。',
	'再度コンタクトする場合は、下記のURLから連絡通知を取り下げ、もう一度通知を行って下さい。'. $n. $n. '%sさんのプロフィールはこちらです。',
	'以降、%sさんのプロフィールからアプローチすることはできなくなります。',
	'%sさんのメッセージ: ',
	'メッセージへの返事を含め、%sさんと遣り取りする際は、このメールに直接返信して下さい。'. $n,
	'ログインしてから、%1$sさんのプロフィールを確認し、「%2$s」より承認／拒否を設定して下さい。'. $n. $n. '%1$sさんのプロフィールはこちらです。'
	];
$approach_info = [
	'%1$sさんと連絡を取りたいユーザーがいます。承認または拒否を選び、保存ボタンを押して下さい。'.
	'承認されたユーザーからはメールを受け取ることができるようになります<small>（%1$sさんのメールアドレスが通知されることはありません）</small>。'.
	'また、拒否した場合は相手からの削除待ちとなり、削除した場合はそのユーザーからのコンタクトを停止することができます。',
	'%sさんからアプローチを受けています。<a class=alert-link href="'. $url. '?user=%s#approval">'. $prof_title[0]. '</a>を確認して下さい。'
	];

$users_info = ['ユーザー情報の確認と設定ができます。', 'アカウント', $admin_suffix[1]. '指定'];

$status = [
	'最終ログイン：',
	'ログイン回数：',
	'アプローチ回数：',
	'アプローチ撤回数：',
	'メッセージ送信回数：',
	'メッセージエラー回数：',
	$comment. '送信回数：',
	$comment. 'エラー回数：',
	'お問い合わせ送信回数：',
	'お問い合わせエラー回数：',
	'スレッド作成回数：',
	'トピック作成回数：',
	'レス数：',
	'アップロード数：',
	'カテゴリ作成回数：',
	'記事作成回数：',
	'サイドページ作成回数：',
	];

$approach_form = ['送信成功', '送信失敗', '連絡通知を取り下げる', '理解した上で送信する'];
$approach_form_title = [
	'%s<small>さんにメールを送る</small>',
	'%s<small>さんからコンタクトを拒否されました</small>',
	'%s<small>さんがコンタクトの着信を停止しています</small>',
	'%s<small>さんと連絡を取る</small>',
	'%s<small>さんからの承認待ち</small>'
	];
$approach_form_message = [
	'%sさんにメッセージを送信しました。',
	'このフォームからメッセージを送信した場合、記入内容及び%sさんのハンドルネームとメールアドレスが%sさんに通知されます。当該情報が第三者に転送されたり保存されることはありません。',
	'再度コンタクトする場合は、連絡通知を取り下げてから、もう一度通知を行って下さい。',
	'%sさんが着信を停止しているため、このページからコンタクトすることはできません。<br>既に承認されている場合は、引き続きメールにて遣り取りして下さい。',
	'下のボタンを押すと%1$sさんに%2$sさんのハンドルネームとプロフィールのURL、必要に応じて一言メッセージを通知しますが、必ずしも%1$sさんから承認されるとは限りません。承認された場合は、自動送信メールで%2$sさんに通知します。',
	'%sさんからの承認前に下のボタンを押せば連絡通知を取り下げることができます。また、承認された場合には送信フォームが表示されます。',
	'送信に失敗しました。',
	];

$approach_flow = ['コンタクト<small>の流れ</small>', '%sさんから%sさんに通知', '%sさんの承認待ち', '%sさんがフォームから送信', '%sさんから返信があれば以降はメールでの遣り取り'];

$user_not_found = '不明なユーザー';
$user_not_found_title = ['ユーザーが見つからないかアカウントが凍結されています', 'メールアドレスがブロックされています'];
$​ask_admin = '問題が解決しない場合は、<a class=text-danger href="'. $url. $contact_us. '">こちら</a>からお問い合わせ下さい。';

$form_label = ['返信', 'トピック作成', 'トピック名', 'スレッド作成', 'スレッド名', '残り%s文字', '要ログイン'];
$disallow_symbols = ['"','#','$','%','&','(',')','*','+',',','.','/',':',';','>','<','=','?','[','\\',']','^','_','`','{','|','}','~'];
$replace_symbols = ['”','＃','＄','％','＆','（','）','＊','＋','，','．','／','：','；','＞','＜','＝','？','［','￥','］','＾','＿','｀','｛','｜','｝','～'];

$forum_title = ['トピック', 'スレッド', 'レス', '書き込み件数'];
$forum_guests = [
	$forum. 'からURLのお知らせ',
	$time_limit. '分以内（%sまで）に、下記のURLにアクセスして'. $forum. 'のプロセスを完了して下さい。',
	'メールを送信しました。メールを確認して'. $forum. 'のプロセスを完了して下さい。',
	'先頭に記号を入力した場合、以下のようになります。',
	'<b class=\"h3 p-3\">!<\/b>ゲストの閲覧および書き込みを禁止します。<br><b class=\"h3 p-1\">@<\/b>ゲストの書き込みを禁止します。<br>'.
	(filter_has_var(INPUT_GET, 'thread') ? '' : '<strong class=\"text-danger d-block text-center\">当該スレッド内の全トピックに継承されます。<\/strong>'),
	'ゲスト書き込みでは、ローカル部がユーザー名となります。<br>'. $forum. '内にメールアドレスが掲載されることはありません。<br>自動送信メールで書き込みプロセスを通知します。',
	'スレッド／トピックを作成するにはログインが必要です。'
	];

$denial_attrs = ' data-placeholder="受付終了" class=c';
$accepting = '残り%s件';
$week = ['日', '月', '火', '水', '木', '金', '土'];
$meridian = ['午前', '午後'];
$booking_msg = ['「%d件」の予約を受付中。', '残り「%d件」入力することができます。', '予約は「%d件」まで入力することができます。', '予約を入力するにはログインが必要です。', '現在、予約は「０件」です。'];
$reminder = [
	'リマインドメール編集',
	'予約日時のご確認 - '. $site_name,
	'この度はご予約頂きありがとうございました。&#10;予約日時は %s です。&#10;ご来場の際はお気をつけてお越し下さい。',
	'%s にリマインドメールを送信しますか？',
	'%s にリマインドメールを送信しました。',
	'%s の予約を削除しても宜しいですか？',
	];
$remind_header = ['日時', '予約者', '内容', 'リマインド'];

$admin_menus = [
	$category. '作成', $category. '名', '記事作成', '記事名', 'カウンター', '閲覧回数を取得します', $comment, $comment. 'を受け付けます', '記事内容', 'サブタイトル', 'サイドページ作成', 'サイドページ名', 'サイドページ内容',
	'記事編集', '移動先', '同名のフォルダまたはファイルが存在します'
	];

#プレースホルダー
#0.検索 1.ログインメールアドレス 2.お問い合わせ名前 3.お問い合わせメール 4.お問い合わせ内容 5.アプローチメッセージ 6.サイト内 7.カテゴリ内 8.フォーラム内 9.スレッド内 10.アップロード画像 11.コメントアップロード
$placeholder = ['Search...', 'example@yourmail.com', '', '', '', '一言メッセージ（必須ではありません）', 'サイト検索', $category. '検索', $forum. '検索', $forum_title[1]. '検索', '画像の説明', 'コメントファイルのアップロード'];

$html_assist = ['HTMLタグ入力補助', '自動改行', '緑地白文字', '黄緑地緑文字', '赤地白文字', 'ピンク地赤文字', '青地白文字', '水色地青文字', '黄色地白色文字', '淡黄地黄文字', '黒地白文字', '灰地白文字', '銀地黒文字', '白地黒文字', '区切り線', 'テーブル', 'リスト', 'リンク', '見出し H1', '見出し H2', '見出し H3', '見出し H4', '見出し H5', '見出し H6', '右寄せ', '中央寄せ', '大きい文字', '小さい文字', '太字', '斜体', '強調', '削除（打ち消し線）', '追加（下線）', '&amp;lt;&amp;gt;', 'br', 'address', 'blockquote', 'cite', 'code', 'dl', 'kbd', 'mark', 'samp', 'sub', 'sup', 'dfn（Wikipedia）', 'コメントアウト（アンケート）', 'border', 'border-top', 'border-end', 'border-bottom', 'border-start', 'border-2', 'border-3', 'border-4', 'border-5', 'border-primary', 'border-secondary', 'border-success', 'border-danger', 'border-warning', 'border-info', 'border-light', 'border-dark', 'border-white', 'rounded', 'rounded-top', 'rounded-end', 'rounded-bottom', 'rounded-start', 'rounded-circle', 'rounded-pill', 'rounded-1', 'rounded-2', 'rounded-3'];

$benchmark_results = '<span class=text-nowrap>処理時間：%s秒</span> <span class=text-nowrap>消費メモリ：%s</span>';

$checklist_message = [
	'このページではアンケートを実施しています。<a href="#login" class=alert-link onclick="focusLogin()">ログインチケット</a>を取得して回答にご協力下さい。',
	'ご協力ありがとうございました',
	'回答者数：%d'
];

#/images/index.php

#1ページあたりの表示枚数
$imgs_per_page = 3;

$images_title = '画像一覧';
$images_heading = '画像一覧 <small class="ms-2">タグをコピーしてお使い下さい</small>';
$size = ['Small', 'Large'];
$first= '&laquo; 最初';
$prev = '&lsaquo; 前';
$next = '次 &rsaquo;';
$last = '最後 &raquo;';

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
		$l = 35;
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
