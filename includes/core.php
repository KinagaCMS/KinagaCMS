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
$session_c = !filter_has_var(INPUT_POST, 'c') ? '' : filter_input(INPUT_POST, 'c', FILTER_SANITIZE_STRING);
$session_e = !filter_has_var(INPUT_POST, 'e') ? '' : enc(filter_input(INPUT_POST, 'e', FILTER_SANITIZE_EMAIL));
$session_f = !filter_has_var(INPUT_POST, 'f') ? '' : filter_input(INPUT_POST, 'f', FILTER_SANITIZE_STRING);
$user = !filter_has_var(INPUT_GET, 'user') ? '' : basename(filter_input(INPUT_GET, 'user', FILTER_SANITIZE_STRING));
$session_t = !filter_has_var(INPUT_POST, 't') ? '' : filter_input(INPUT_POST, 't', FILTER_SANITIZE_STRING);
$request_query = explode('?', $request_uri);
if (isset($request_query[1])) foreach (explode('&', $request_query[1]) as $rquery) if ($rquery) list (, $v[]) = explode('=', $rquery);
if (is_file($conf = $tpl_dir. 'config.php')) include $conf;
if (is_file($ticket = 'images/ticket.png')) if (!is_dir($usersdir = './users/')) mkdir($usersdir, 0757);
if ($use_forum)
{
	$forum_url = $url. r($forum);
	if ($forum_thread = !filter_has_var(INPUT_GET, 'thread') ? '' : basename(filter_input(INPUT_GET, 'thread', FILTER_SANITIZE_STRING)))
	{
		if ('@' === $forum_thread[0]) $allow_guest_creates = false;
		$thread_title = '!' === $forum_thread[0] || '@' === $forum_thread[0] ? h(substr($forum_thread, 1)) : h($forum_thread);
		$thread_url = $forum_url. '/'. r($forum_thread). '/';
	}
	if ($forum_topic = !filter_has_var(INPUT_GET, 'topic') ? '' : basename(filter_input(INPUT_GET, 'topic', FILTER_SANITIZE_STRING)))
	{
		$topic_title = '!' === $forum_topic[0] || '@' === $forum_topic[0] ? h(substr($forum_topic, 1)) : h($forum_topic);
		$topic_url = $thread_url. r($forum_topic);
	}
	if (!is_dir($forum_dir = './forum/')) mkdir($forum_dir, 0757);
	if (!is_file($blacklist = $forum_dir. '#blacklist.txt')) file_put_contents($blacklist, '');
}
include 'session.php';
$glob_dir = 'contents/'. (is_admin() || is_subadmin() ? '*/*' : '[!!]*/[!!]*'). '/';
if ($contents = get_dirs('contents', false))
{
	foreach($contents as $categ)
		$nav .= '<li'. ($categ_nav_class ?? ''). '><a'. ($categ_name === $categ ? ' class="nav-item nav-link active"' : ' class="nav-item nav-link"'). ' href="'. $url. r($categ). '/">'. h($categ). '</a></li>'. $n;
}
if ($user)
	include 'profile.php';
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
	'<form method=get>'. $n.
	'<input placeholder="'. $placeholder[$num]. '" type=search id=search name='. ($forum !== filter_input(INPUT_GET, 'page') ? '' : 'f'). 'query required class=form-control accesskey=f>'. $n.
	'</form>'. $n;
}

include 'sideboxes.php';

if ($checklist === $title_name || $checklist === $page_name) $javascript .= 'let s=$("<section class=checklist>"),o=$("<ol class=\"list-group list-group-flush mb-5 mt-2\">");s.append(o);$.each($("article.article").html().split("\n"),function(i,v){if(v){if("H2"===$(v).prop("tagName"))l=$("<li class=\"list-group-item active\">").append($(v));else l=$("<li class=\"list-group-item list-group-item-action\">").append($("<label class=\"d-block mb-0 user-select-none\">").text(v).prepend($("<input class=mr-3 type=checkbox>")))}else l=$("<li class=list-group-item>");l.appendTo(o)});$("article.article").html(s);$(".checklist").each(function(){$("input[type=checkbox]",this).each(function(i,v){v.after(i+". ")})});let a=JSON.parse(localStorage.getItem("checked"))||[];a.forEach((c,i)=>$(".checklist input[type=checkbox]").eq(i).prop("checked",c));$(".checklist input[type=checkbox]").click(()=>{a=$(".checklist input[type=checkbox]").map((i,e)=>e.checked).get();localStorage.setItem("checked",JSON.stringify(a))});';

$header .=
'<meta name=application-name content=KinagaCMS>'. $n.
'<link rel=alternate type="application/atom+xml" href="'. $url. 'atom.php">'. $n.
(!is_file($favicon = 'favicon.ico') && !is_file($favicon = $favicon_svg = 'images/favicon.svg') ? '<link href="'. $url. 'images/icon.php" rel=icon type="image/svg+xml" sizes=any>' : '<link rel=icon'. (!isset($favicon_svg) ? '' : ' type="image/svg+xml" sizes=any'). ' href="'. $url. $favicon. '">'). $n;

if ($stylesheet) $header .= '<style>'. $stylesheet. '</style>';
$footer .= '<script defer>const d=document;$("img").onerror=null;'. $javascript.
(!$use_datasrc ? '' : 'const x=new IntersectionObserver(entries=>{entries.forEach(entry=>{if(entry.isIntersecting){const y=entry.target;if(y.dataset.src){y.src=y.dataset.src}}})}),z=d.querySelectorAll("img[data-src]");z.forEach(z=>x.observe(z));').
(!$use_wikipedia_popover ? '' : '$("dfn").css("border-bottom","thin dotted");$("dfn").mouseover(function(e){$(this).css("cursor","progress");const l="https://"+($(this).attr("lang")||"'. $lang. '")+".wikipedia.org";if(navigator.onLine)$.getJSON(l+"/w/api.php?action=query&format=json&origin=*&prop=extracts&exintro&explaintext&redirects=1&titles="+$(this).text(),function(w,s,x){if("success"===s&&200===x.status){$(e.currentTarget).css("cursor","pointer").css("border-bottom","thin solid");for(i in w.query.pages)$(e.currentTarget).popover({placement:"auto",title:w.query.pages[i].title||"",content:w.query.pages[i].extract||"",template:"<div class=popover><div class=arrow><\/div><div class=\"d-flex justify-content-between\"><h3 class=\"popover-header flex-grow-1\"><\/h3><a class=\"btn btn-dark lead btn-close\">&times;</a><\/div><div class=popover-body><\/div><small class=\"d-flex justify-content-between bg-info px-2 py-1\"><a href=\"http://www.gnu.org/licenses/fdl-1.3.html\" target=_blank title=\"GNU Free Documentation License\">GFDL<\/a><a href=\""+l+"/wiki/"+w.query.pages[i].title+"\" target=_blank>"+l+"/wiki/"+w.query.pages[i].title+"<\/a><\/small><\/div>"})}})}).mouseout(function(){$(this).css("border-bottom","thin dotted")});$(document).on("click",".btn-close",function(){$(this).parents(".popover").popover("hide")});').
'</script>'.
'<div id=copyright class="d-flex justify-content-center align-items-center h-100">'.
'<img alt=K data-toggle=modal data-target="#powered-by-kinaga" src='. $url. 'images/icon.php width=53 height=43>'.
'<small class="ml-3 text-muted">&copy; '. date('Y'). ' '. $site_name. '. '. ($copyright ?? '').
(!$use_benchmark ? '' : '<br>'. sprintf($benchmark_results, round((hrtime(true) - $time_start)/1e+9, 4), size_unit(memory_get_usage() - $base_mem))).
'</small>'.
'</div>'.
'<div class="modal fade" id=powered-by-kinaga aria-hidden=true>'.
'<div class="modal-dialog modal-dialog-centered modal-sm">'.
'<div class=modal-content>'.
'<button type=button class="close position-absolute" data-dismiss=modal style="right:5px;z-index:1100" accesskey=k tabindex=-1><span aria-hidden=true>&times;</span></button>'.
'<div class="modal-body text-black-50 text-center"><img src="'. $url. 'images/icon.php" alt="Powered by KinagaCMS" width=266 height=213></div>'.
'<div class=modal-footer><small class=text-black-50>Powered by</small> <a class="h5 border-0 modal-title text-dark" href="https://github.com/KinagaCMS/">KinagaCMS</a></div>'.
'</div>'.
'</div>'.
'</div>';
