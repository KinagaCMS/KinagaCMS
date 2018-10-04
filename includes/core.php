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
$contents = get_dirs('contents', false);
$dl = is_dir($downloads_dir = 'downloads') ? true : false;
$form = 'includes/form.php';

if (!empty($contents))
{
	$title_name = d($get_title);
	$categ_name = d($get_categ);
	$page_name = d($get_page);
	foreach($contents as $categ)
	{
		$count = count($contents);
		if ($categ === reset($contents))
			$nav .= '<li class=nav-first>';
		elseif ($categ === end($contents))
			$nav .= '<li class=nav-last>';
		else
			$nav .= '<li>';
		$nav .= '<a'. ($categ_name === $categ ? ' class="nav-item nav-link active"' : ' class="nav-item nav-link"'). ' href="'. $url. r($categ). '/">'. h($categ). '</a></li>'. $n;
	}
}
if ($get_page && !is_numeric($get_page))
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
	'<input placeholder="Search..." type=search id=search name=query required class=form-control tabindex=1 accesskey=i>'. $n.
	'</form>'. $n;

include 'sideboxes.php';

$footer .=
'<small><span class="text-muted text-center">&copy; '. date('Y'). ' '. $site_name. '. Powered by kinaga.</span></small>'. $n;
if ($use_benchmark === true)
	$footer .= sprintf($benchmark_results, round(microtime(true) - $time_start, 6), size_unit(memory_get_usage() - $base_mem));
$header .=
'<meta name=application-name content=kinaga>'. $n.
'<link rel=alternate type="application/atom+xml" href="'. $url. 'atom.php">'. $n.
(!is_file('favicon.ico') ? '<link href="'. $url. 'images/icon.php" rel=icon type="image/svg+xml" sizes=any>' : '<link rel="shortcut icon" href="'. $url. 'favicon.ico">'). $n;
