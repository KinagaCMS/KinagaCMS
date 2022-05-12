<?php
if (__FILE__ === implode(get_included_files())) exit;
$header = $nav = $article = $aside = $search = $javascript = $stylesheet = $footer = '';
$pos = strpos($request_uri, '?query=');
$fpos = strpos($request_uri, '?fquery=');
$get_title = !filter_has_var(INPUT_GET, 'title') ? '' : get_uri(basename($request_uri), 'title');
$get_categ = !filter_has_var(INPUT_GET, 'categ') ? '' : (!filter_has_var(INPUT_GET, 'title') && false === $pos ? get_uri(basename($request_uri), 'categ') : get_uri(basename(dirname($request_uri)), 'categ')). '/';
$get_page = !filter_has_var(INPUT_GET, 'page') ? '' : get_uri(basename($request_uri), 'page');
$get_dl = !filter_has_var(INPUT_GET, 'dl') ? '' : basename(filter_input(INPUT_GET, 'dl', FILTER_SANITIZE_ENCODED));
$pages = !filter_has_var(INPUT_GET, 'pages') ? 1 : (int)filter_input(INPUT_GET, 'pages', FILTER_SANITIZE_NUMBER_INT);
$comment_pages = !filter_has_var(INPUT_GET, 'comments') ? 1 : (int)filter_input(INPUT_GET, 'comments', FILTER_SANITIZE_NUMBER_INT);
$breadcrumb = '<li class=breadcrumb-item><a href="'. $url. '">'. $home. '</a></li>';
$dl = is_dir($downloads_dir = 'downloads') ? true : false;
$form = 'includes/form.php';
$title_name = d($get_title);
$categ_name = d($get_categ);
$page_name = d($get_page);
$session_c = !filter_has_var(INPUT_POST, 'c') ? '' : filter_input(INPUT_POST, 'c', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
$session_e = !filter_has_var(INPUT_POST, 'e') ? '' : enc(filter_input(INPUT_POST, 'e', FILTER_CALLBACK, ['options' => 'sanitize_mail']));
$session_f = !filter_has_var(INPUT_POST, 'f') ? '' : filter_input(INPUT_POST, 'f', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
$user = !filter_has_var(INPUT_GET, 'user') ? '' : filter_input(INPUT_GET, 'user', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
$session_t = !filter_has_var(INPUT_POST, 't') ? '' : filter_input(INPUT_POST, 't', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
$request_query = explode('?', $request_uri);
$forum_thread = !filter_has_var(INPUT_GET, 'thread') ? '' : filter_input(INPUT_GET, 'thread', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
$create_with_kisou = '<button type=button class="btn btn-primary mb-3" data-bs-toggle=modal data-bs-target=#kisou><svg width=25 viewBox="0 0 50.184 40" xmlns="http://www.w3.org/2000/svg"><g transform="translate(-354.91 -692.36)" fill="#fff"><g transform="matrix(0 -1 -1 0 971.2 1023.7)" fill="#fff"><path d="m306.4 593.82-8.3151 6.2402 1.6537 1.248 13.447-10.115-13.447-10.115-1.6537 1.248 8.3151 6.2402zm24.961-26.221c0-0.80756-0.69175-1.4498-1.5601-1.4508l-36.88-0.0468c-0.86839-9e-4 -1.5601 0.64328-1.5601 1.4509v47.282c0 0.80762 0.69171 1.4518 1.5601 1.4509l36.88-0.0468c0.86835-9.9e-4 1.5601-0.64324 1.5601-1.4508-2.9e-4 -15.73-1.3e-4 -31.459 0-47.189zm-17.474 33.709 14.977 12.48h-12.48l-9.9844-7.4883-6.6615-4.9922-1.6537 1.248 8.3151 6.2402v4.9922h-9.9844v-9.9844l1.6693-1.248-1.6693-1.248 1.6693-1.248-1.6693-1.248v-15.235l1.6693-1.248-1.6693-1.248 1.6693-1.248-1.6693-1.248v-9.9844h9.9844v4.9922l-8.3151 6.2402 1.6537 1.248 16.646-12.48h12.48l-14.977 12.48 12.066 10.206z" fill="#fff"/></g></g></svg> '. $btn[14]. '</button>';
if (isset($request_query[1])) foreach (explode('&', $request_query[1]) as $rquery) if (false !== strpos($rquery, '=')) list (, $v[]) = explode('=', $rquery);
if (is_file($conf = $tpl_dir. 'config.php')) include $conf;
if (is_file($ticket = 'images/ticket.png')) if (!is_dir($usersdir = './users/')) mkdir($usersdir, 0757);
if ($use_forum)
{
	$forum_url = $url. r($forum);
	if ($forum_thread)
	{
		if ('@' === $forum_thread[0]) $allow_guest_creates = false;
		$thread_title = '!' === $forum_thread[0] || '@' === $forum_thread[0] ? h(substr($forum_thread, 1)) : h($forum_thread);
		$thread_url = $forum_url. '/'. r($forum_thread). '/';
	}
	if ($forum_topic = !filter_has_var(INPUT_GET, 'topic') ? '' : filter_input(INPUT_GET, 'topic', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
	{
		$topic_title = '!' === $forum_topic[0] || '@' === $forum_topic[0] ? h(substr($forum_topic, 1)) : h($forum_topic);
		$topic_url = $thread_url. r($forum_topic);
	}
	if (!is_dir($forum_dir = './forum/')) mkdir($forum_dir, 0757);
	if (!is_file($blacklist = $forum_dir. '#blacklist.txt')) file_put_contents($blacklist, '');
}
include 'session.php';
$glob_dir = 'contents/'. (is_admin() || is_subadmin() ? ($get_categ ? $categ_name : '*'). '/' : ($get_categ ? $categ_name : '[!!]*'). '/[!!]'). '*/';
if ($contents = get_dirs('contents', false))
{
	foreach($contents as $categ)
		$nav .= '<li'. ($categ_nav_class ?? ''). '><a class="'. $categ_nav_a_class. ($categ_name === $categ ? $categ_nav_active_class : ''). '" href="'. $url. r($categ). '/">'. h($categ). '</a></li>';
}
if ($user)
	include 'profile.php';
elseif (filter_has_var(INPUT_GET, 'i') && is_dir('users/'. (!filter_has_var(INPUT_GET, 'i') ? ' ' : basename(str_rot13(filter_input(INPUT_GET, 'i')))))) include 'ipn.php';
elseif ($use_search && (false !== $pos || false !== $fpos))
{
	if (isset($v[0]))
	{
		if (false !== $pos) $query = h(str_replace('+', ' ', d($v[0])));
		if (false !== $fpos) $fquery = h(str_replace('+', ' ', d($v[0])));
		$pages = isset($v[1]) && is_numeric($v[1]) ? (int)$v[1] : 1;
		$no_results = '';
		include 'search.php';
	}
}
elseif ($get_page && !is_numeric($get_page))
	include 'page.php';
elseif ($get_categ && !$get_title)
	include 'categ.php';
elseif ($get_categ && $get_title)
	include 'article.php';
elseif (!$get_categ && !$get_title)
	include 'home.php';
else
	not_found();

if ($use_search)
{
	if ($forum === filter_input(INPUT_GET, 'page') && $forum_thread) $num = 9;
	elseif ($forum === filter_input(INPUT_GET, 'page') && !$forum_thread) $num = 8;
	elseif ($get_categ) $num = 7;
	else $num = 6;
	$search .=
	'<form method=get>'.
	'<input placeholder="'. $placeholder[$num]. '" type=search id=search name='. ($forum !== filter_input(INPUT_GET, 'page') ? '' : 'f'). 'query required class=form-control accesskey=f>'.
	'</form>';
}

include 'sideboxes.php';

$header .=
'<meta name=application-name content=KinagaCMS>'.
'<link rel=alternate type="application/atom+xml" href="'. $url. 'atom.php">'.
(!is_file($favicon = 'favicon.ico') && !is_file($favicon = $favicon_svg = 'images/icon.svg') ?
	'<link href="'. $url. 'images/icon.php" rel=icon type="image/svg+xml" sizes=any>' :
	'<link rel=icon'. (!isset($favicon_svg) ? '' : ' type="image/svg+xml" sizes=any'). ' href="'. $url. $favicon. '">');
if ($stylesheet) $header .= '<style>'. $stylesheet. '</style>';
if (is_file($ads_txt = 'ads.txt'))
{
	$ads_str = str_getcsv(file($ads_txt)[0]);
	if ('google.com' === $ads_str[0])
		if (preg_match('/\d+/', $ads_str[1], $ads_num))
			$header .= '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-'. $ads_num[0]. '" crossorigin="anonymous"></script>';
}
$article .= '<div class="clearfix my-5" id=clearfix></div>';
$footer .= (!$javascript ? '' : '<script>'. $javascript. '</script>').
'<div id=copyright class="d-flex justify-content-center align-items-center">'.
'<img alt=K data-bs-toggle=modal data-bs-target="#powered-by-kinaga" src='. $url. 'images/icon.php width=53 height=43>'.
'<small class="ms-3 text-muted">&copy; '. date('Y'). ' '. $site_name. '. '. ($copyright ?? '').
(!$use_benchmark ? '' : '<br>'. sprintf($benchmark_results, round((hrtime(true) - $time_start)/1e+9, 4), size_unit(memory_get_usage() - $base_mem))).
'</small>'.
'</div>'.
'<div class="modal fade" id=powered-by-kinaga aria-hidden=true>'.
'<div class="modal-dialog modal-dialog-centered modal-sm">'.
'<div class=modal-content>'.
'<button type=button class="btn bg-white position-absolute p-1 top-0 start-100 translate-middle text-primary rounded-circle" data-bs-dismiss=modal style="z-index:1100" accesskey=k tabindex=-1>'.
'<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg>'.
'</button>'.
'<div class="modal-body text-center"><img src="'. $url. 'images/icon.php" alt="Powered by KinagaCMS" width=266 height=213></div>'.
'<div class=modal-footer><small>Powered by <a class="h5 border-0 text-black-50" href="https://github.com/KinagaCMS/">KinagaCMS</a></small></div>'.
'</div>'.
'</div>'.
'</div>';
