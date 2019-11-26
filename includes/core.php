<?php
$header = $nav = $article = $aside = $footer = $search = '';
$get_title = !filter_has_var(INPUT_GET, 'title') ? '' : get_uri(basename($request_uri), 'title');
$get_categ = !filter_has_var(INPUT_GET, 'categ') ? '' : !$get_title ? get_uri(basename($request_uri), 'categ') : get_uri(basename(dirname($request_uri)), 'categ');
$get_page = !filter_has_var(INPUT_GET, 'page') ? '' : get_uri(basename($request_uri), 'page');
$get_dl = !filter_has_var(INPUT_GET, 'dl') ? '' : basename(filter_input(INPUT_GET, 'dl', FILTER_SANITIZE_ENCODED));
$pages = !filter_has_var(INPUT_GET, 'pages') ? 1 : (int)filter_input(INPUT_GET, 'pages', FILTER_SANITIZE_NUMBER_INT);
$query = !filter_has_var(INPUT_GET, 'query') ? '' : trim(mb_convert_kana(filter_input(INPUT_GET, 'query', FILTER_SANITIZE_SPECIAL_CHARS), 'rnsK', $encoding));
$comment_pages = !filter_has_var(INPUT_GET, 'comments') ? 1 : (int)filter_input(INPUT_GET, 'comments', FILTER_SANITIZE_NUMBER_INT);
$breadcrumb = '<li class=breadcrumb-item><a href="'. $url. '">'. $home. '</a></li>';
$dl = is_dir($downloads_dir = 'downloads') ? true : false;
$form = 'includes/form.php';
$ticket = 'images/ticket.jpg';
$title_name = d($get_title);
$categ_name = d($get_categ);
$page_name = d($get_page);
$this_year = date('Y');

$session_c = !filter_has_var(INPUT_POST, 'c') ? '' : filter_input(INPUT_POST, 'c', FILTER_SANITIZE_STRING);
$session_e = !filter_has_var(INPUT_POST, 'e') ? '' : enc(filter_input(INPUT_POST, 'e', FILTER_SANITIZE_EMAIL));
$session_f = !filter_has_var(INPUT_POST, 'f') ? '' : filter_input(INPUT_POST, 'f', FILTER_SANITIZE_STRING);
$user = !filter_has_var(INPUT_GET, 'user') ? '' : filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING);
$session_t = !filter_has_var(INPUT_POST, 't') ? '' : filter_input(INPUT_POST, 't', FILTER_SANITIZE_STRING);

if (!extension_loaded('imagick'))
{
	$imagick_so = 'extension=imagick.so';
	if (is_file($php_ini = $_SERVER['DOCUMENT_ROOT']. '/php.ini'))
	{
		if (strpos(file_get_contents($php_ini), $imagick_so) === false)
			file_put_contents($php_ini, $imagick_so. $n, FILE_APPEND | LOCK_EX);
		else
			file_put_contents('./error.log', 'ImageMagick is not installed. See https://www.php.net/manual/'. $lang. '/imagick.setup.php'. $n, LOCK_EX);
	}
	else
		file_put_contents($php_ini, $imagick_so. $n, LOCK_EX);
}

if (is_file($conf = $tpl_dir. 'config.php')) include $conf;

include 'session.php';

if ($contents = get_dirs('contents', false))
{
	foreach($contents as $categ)
		$nav .= '<li><a'. ($categ_name === $categ ? ' class="nav-item nav-link active"' : ' class="nav-item nav-link"'). ' href="'. $url. r($categ). '/">'. h($categ). '</a></li>'. $n;
}

if ($user)
	include 'profile.php';
elseif ($get_page && !is_numeric($get_page))
	include 'page.php';
elseif ($get_categ && !$get_title)
	include 'categ.php';
elseif ($get_categ && $get_title)
	include 'article.php';
elseif (!$get_categ && !$get_title)
{
	if ($use_search && $query)
		include 'search.php';
	else
		include 'home.php';
}
else
	not_found();

$article .= '<div class="clearfix mb-5"></div>';

if ($use_search)
	$search .=
	'<form method=get action="'. $url. '">'. $n.
	'<input placeholder="'. $placeholder[0]. '" type=search id=search name=query required class=form-control accesskey=i>'. $n.
	'</form>'. $n;

include 'sideboxes.php';

$header .=
'<meta name=application-name content=KinagaCMS>'. $n.
'<link rel=alternate type="application/atom+xml" href="'. $url. 'atom.php">'. $n.
(!is_file('favicon.ico') ? '<link href="'. $url. 'images/icon.php" rel=icon type="image/svg+xml" sizes=any>' : '<link rel="shortcut icon" href="'. $url. 'favicon.ico">'). $n;
$footer .= '<small class="d-block text-muted">&copy; '. $this_year. ' '. $site_name. '.</small>'. $n.
($use_benchmark ? sprintf($benchmark_results, round(microtime(true) - $time_start, 6), size_unit(memory_get_usage() - $base_mem)) : '').
'<img class="mx-auto my-2" alt=K data-toggle=modal data-target="#powered-by-kinaga" src='. $url. 'images/icon.php width=30 height=24><div class="modal fade" id=powered-by-kinaga aria-hidden=true><div class="modal-dialog modal-dialog-centered modal-sm"><div class=modal-content><button type=button class="close position-absolute" data-dismiss=modal style="right:5px;z-index:1100" accesskey=k tabindex=-1><span aria-hidden=true>&times;</span></button><div class="modal-body text-black-50"><img src="'. $url. 'images/icon.php" alt="Powered by KinagaCMS"></div><div class=modal-footer><small class=text-black-50>Powered by</small> <a class="h5 border-0 modal-title text-dark" href="https://github.com/KinagaCMS/">KinagaCMS</a></div></div></div></div>';
