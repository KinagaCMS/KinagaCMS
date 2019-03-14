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
$title_name = d($get_title);
$categ_name = d($get_categ);
$page_name = d($get_page);
$this_year = date('Y');

if ($contents = get_dirs('contents', false))
{
	foreach($contents as $categ)
		$nav .= '<li><a'. ($categ_name === $categ ? ' class="nav-item nav-link active"' : ' class="nav-item nav-link"'). ' href="'. $url. r($categ). '/">'. h($categ). '</a></li>'. $n;
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

if (is_file($boxtpl = $tpl_dir. 'sideboxes.php'))
	include $boxtpl;
else
	include 'sideboxes.php';

$footer .=
'<small><span class="text-muted text-center align-text-bottom">&copy; '. $this_year. ' '. $site_name. '. Powered by <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 88.875 45.062499" fill="currentColor" id="kinaga" data-toggle="modal" data-target="#about-kinaga"><path d="m8.25-2.2702e-7c-0.816 3.024-1.8525 6.3477-2.8125 8.8437-1.152-0.672-2.7028-1.318-4.7188-1.75l-0.4375 0.34375c1.68 1.968 3.568 5.0308 4 7.7188 3.312 2.496 6.484-1.967 2.5-5.375 2.256-1.68 4.657-3.8668 6.625-6.2188 1.056 0.096 1.6662-0.299 1.9062-0.875l-7.062-2.6874zm51.062 0.15625c-0.55878 0.0007-1.1172 0.0071-1.7188 0.03125l-0.15625 0.59375c5.281 2.352 9.353 5.8568 10.938 7.9688 5.347 2.186 8.26-8.6156-9.063-8.5938zm-20.906 1-2.5938 2.875h-15.562l0.4375 1.4062h15.594v13.281h-7.9688l-6.2812-2.4062v22.531c0 4.128 1.345 5.0625 6.625 5.0625h5.4688c8.736 0 11.125-0.94151 11.125-3.4375 0-0.0735 0.004-0.14918 0-0.21875 8.381-4.321 12.739-11.273 14.811-18.75 1.056-0.096 1.4452-0.27 1.7812-0.75l-4.875-4.1562-2.7812 2.8438h-9.9062l0.4375 1.4062h9.75c-1.3527 6.8508-4.3343 13.575-9.625 18.312-0.2969-0.34589-0.76405-0.63525-1.5312-0.9375l-0.15625-7.9688h-0.53125c-1.056 3.648-1.9095 6.5065-2.4375 7.5625-0.336 0.624-0.5757 0.7645-1.3438 0.8125-0.72 0.048-2.253 0.0625-4.125 0.0625h-5c-1.68 0-2.0312-0.2443-2.0312-1.1562v-17.469h8.5938v4.0938h0.96875c1.824 0 4.6082-1.0558 4.6562-1.3438v-16.469c0.96-0.24 1.6182-0.6473 1.9062-1.0312l-5.4062-4.1562zm-24.688 4.6562c-1.775 4.3676-4.8022 10.08-7.8742 14.688-2.448 0.096-4.502 0.125-5.75 0.125l1.5312 5.5c0.576-0.096 1.1182-0.487 1.4062-1.063 1.728-0.48 3.3558-0.957 4.8438-1.437v4.25l-4.5625-1.125c-0.48 5.184-1.7285 10.631-3.3125 14.375l0.71875 0.40625c3.072-2.88 5.4762-7.212 7.1562-12.156v15.687h0.90625c2.592 0 4.2188-1.1015 4.2188-1.4375v-21.53l2.25-0.782c0.288 1.152 0.48325 2.3022 0.53125 3.4062 4.224 3.84 9.126-4.661-2.25-9.125l-0.46875 0.25c0.624 1.2 1.3012 2.6515 1.7812 4.1875-2.304 0.144-4.6235 0.2165-6.6875 0.3125 4.08-3.504 7.8666-7.6136 10.219-10.781 1.104 0.144 1.7288-0.19075 1.9688-0.71875l-6.625-3.0312zm51.688 2.5938-2.5312 2.875h-11.438l0.4375 1.3438h11.469v25.094c0 0.72-0.2297 1.0625-1.0938 1.0625-1.152 0-6.9375-0.34375-6.9375-0.34375v0.6875c2.688 0.384 3.886 1.0422 4.75 1.9062 0.816 0.864 1.1205 2.2072 1.3125 4.0312 6.768-0.576 7.7188-2.8164 7.7188-6.6562v-22.281c2.448 13.392 7.449 19.82 14.938 24.812 0.672-2.736 2.3522-4.7532 4.6562-5.2812l0.1875-0.53125c-5.807-2.017-11.794-5.365-15.874-11.845 4.416-2.112 8.822-5.0565 11.75-7.3125 1.104 0.24 1.587 0.0113 1.875-0.46875l-6.4375-4.2812c-1.584 2.976-4.8365 7.655-7.8125 11.062-1.392-2.448-2.5132-5.2964-3.2812-8.6562v-0.125c0.912-0.192 1.5078-0.58475 1.8438-0.96875l-5.532-4.1248zm-51.687 18.188-0.531 0.187c1.104 2.736 2.2355 6.5195 2.1875 9.6875 4.08 4.176 9.2876-4.355-1.6562-9.875z"/></svg></span></small><div class="modal fade" id=about-kinaga aria-hidden=true><div class="modal-dialog modal-dialog-centered"><div class=modal-content><div class=modal-header><a class="h5 modal-title text-secondary" href="https://github.com/KinagaCMS/">KinagaCMS</a><button accesskey=c tabindex=1000 type=button class=close data-dismiss=modal><span aria-hidden=true>&times;</span></button></div><div class="modal-body text-black-50"><img src="'. $url. 'images/icon.php" alt=k height=370 width=460>Copyright &copy; '. $this_year.' KinagaCMS.</div></div></div></div>'. $n;
if ($use_benchmark === true)
	$footer .= sprintf($benchmark_results, round(microtime(true) - $time_start, 6), size_unit(memory_get_usage() - $base_mem));
$header .=
'<meta name=application-name content=kinaga>'. $n.
'<link rel=alternate type="application/atom+xml" href="'. $url. 'atom.php">'. $n.
(!is_file('favicon.ico') ? '<link href="'. $url. 'images/icon.php" rel=icon type="image/svg+xml" sizes=any>' : '<link rel="shortcut icon" href="'. $url. 'favicon.ico">'). $n;
