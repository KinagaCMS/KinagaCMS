<?php
if (__FILE__ === implode(get_included_files())) exit;
$header = $nav = $article = $aside = $footer = $search = '';
$get_title = !filter_has_var(INPUT_GET, 'title') ? '' : get_uri(basename($request_uri), 'title');
$get_categ = !filter_has_var(INPUT_GET, 'categ') ? '' : (!filter_has_var(INPUT_GET, 'title') ? get_uri(basename($request_uri), 'categ') : get_uri(basename(dirname($request_uri)), 'categ')). '/';
$get_page = !filter_has_var(INPUT_GET, 'page') ? '' : get_uri(basename($request_uri), 'page');
$get_dl = !filter_has_var(INPUT_GET, 'dl') ? '' : basename(filter_input(INPUT_GET, 'dl', FILTER_SANITIZE_ENCODED));
$pages = !filter_has_var(INPUT_GET, 'pages') ? 1 : (int)filter_input(INPUT_GET, 'pages', FILTER_SANITIZE_NUMBER_INT);
$query = !filter_has_var(INPUT_GET, 'query') ? '' : trim(filter_input(INPUT_GET, 'query', FILTER_SANITIZE_SPECIAL_CHARS));
$comment_pages = !filter_has_var(INPUT_GET, 'comments') ? 1 : (int)filter_input(INPUT_GET, 'comments', FILTER_SANITIZE_NUMBER_INT);
$breadcrumb = '<li class=breadcrumb-item><a href="'. $url. '">'. $home. '</a></li>';
$dl = is_dir($downloads_dir = 'downloads') ? true : false;
$form = 'includes/form.php';
$title_name = d($get_title);
$categ_name = d($get_categ);
$page_name = d($get_page);
$this_year = date('Y');

$session_c = !filter_has_var(INPUT_POST, 'c') ? '' : filter_input(INPUT_POST, 'c', FILTER_SANITIZE_STRING);
$session_e = !filter_has_var(INPUT_POST, 'e') ? '' : enc(filter_input(INPUT_POST, 'e', FILTER_SANITIZE_EMAIL));
$session_f = !filter_has_var(INPUT_POST, 'f') ? '' : filter_input(INPUT_POST, 'f', FILTER_SANITIZE_STRING);
$user = !filter_has_var(INPUT_GET, 'user') ? '' : basename(filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING));
$session_t = !filter_has_var(INPUT_POST, 't') ? '' : filter_input(INPUT_POST, 't', FILTER_SANITIZE_STRING);

if (is_file($conf = $tpl_dir. 'config.php')) include $conf;

if (is_file($ticket = 'images/ticket.png')) if (!is_dir($usersdir = './users/')) mkdir($usersdir, 0757);

if ($use_forum)
{
	if (!is_dir($forum_dir = './forum/')) mkdir($forum_dir, 0757);
	if (!is_file($blacklist = $forum_dir. 'blacklist.txt')) file_put_contents($blacklist, '');
}
include 'session.php';

if ($contents = get_dirs('contents', false))
{
	foreach($contents as $categ)
		$nav .= '<li'. ($categ_nav_class ?? ''). '><a'. ($categ_name === $categ ? ' class="nav-item nav-link active"' : ' class="nav-item nav-link"'). ' href="'. $url. r($categ). '/">'. h($categ). '</a></li>'. $n;
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
	include $use_search && $query ? 'search.php' : 'home.php';
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
(!is_file($favicon = 'favicon.ico') && !is_file($favicon = 'images/favicon.svg') ? '<link href="'. $url. 'images/icon.php" rel=icon type="image/svg+xml" sizes=any>' : '<link rel="shortcut icon" href="'. $url. $favicon. '">'). $n;
$footer .=
(!$use_datasrc ? '' : '<script>const x=new IntersectionObserver(entries=>{entries.forEach(entry=>{if(entry.isIntersecting){const y=entry.target;if(y.dataset.src){y.src=y.dataset.src}}})}),z=document.querySelectorAll("img[data-src]");z.forEach(z=>x.observe(z))</script>').
'<div id=copyright class="d-flex justify-content-center align-items-center h-100">'. $n.
'<img alt=K data-toggle=modal data-target="#powered-by-kinaga" src='. $url. 'images/icon.php width=53 height=43>'. $n.
'<small class="ml-3 text-muted">&copy; '. $this_year. ' '. $site_name. '. '. ($copyright ?? '').
(!$use_benchmark ? '' : '<br>'. sprintf($benchmark_results, round((hrtime(true) - $time_start)/1e+9, 4), size_unit(memory_get_usage() - $base_mem)). $n).
'</small>'. $n.
'</div>'. $n.
'<div class="modal fade" id=powered-by-kinaga aria-hidden=true>'. $n.
'<div class="modal-dialog modal-dialog-centered modal-sm">'. $n.
'<div class=modal-content>'. $n.
'<button type=button class="close position-absolute" data-dismiss=modal style="right:5px;z-index:1100" accesskey=k tabindex=-1><span aria-hidden=true>&times;</span></button>'. $n.
'<div class="modal-body text-black-50 text-center"><img src="'. $url. 'images/icon.php" alt="Powered by KinagaCMS" width=266 height=213></div>'. $n.
'<div class=modal-footer>'. $n.
'<small class=text-black-50>Powered by</small> <a class="h5 border-0 modal-title text-dark" href="https://github.com/KinagaCMS/">KinagaCMS</a>'. $n.
'</div>'. $n.
'</div>'. $n.
'</div>'. $n.
'</div>'. $n;
