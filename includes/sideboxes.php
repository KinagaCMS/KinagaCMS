<?php
if ($use_recents && $recent_files = glob($glob_dir. 'index.html', GLOB_NOSORT))
{
	usort($recent_files, function($a, $b){return filemtime($a) < filemtime($b);});

	$aside .= '<div id=recents class="list-group mb-5"><div class="list-group-item bg-primary title">'. $recents. '</div>'. $n;

	foreach($recent_files as $recents_name)
	{
		$recent_categ = get_categ($recents_name);
		$recent_title = get_title($recents_name);
		$aside .=
			'<a class="list-group-item list-group-item-action'. ($get_categ. $get_title === $recent_categ. $recent_title ? ' bg-light' : ''). '" href="'. $url. r($recent_categ. '/'. $recent_title). '">'. h($recent_title). '</a>'. $n;
	}
	$aside .= '</div>';
}

$glob_info_files = glob('contents/*.html', GLOB_NOSORT);
if ($glob_info_files || $dl || $use_contact)
{
	usort($glob_info_files, function($a, $b){return filemtime($a) < filemtime($b);});

	$glob_info_flips = array_flip($glob_info_files);

	if (isset($glob_info_flips['contents/index.html']))
		unset($glob_info_flips['contents/index.html']);

	$aside .=
	'<div id=informations class="list-group mb-5"><div class="list-group-item bg-primary title">'. $informations. '</div>'. $n;

	foreach(array_flip($glob_info_flips) as $info_files)
	{
		$info_uri = basename($info_files, '.html');
		$aside .=
		'<a class="list-group-item list-group-item-action'. ($get_page === $info_uri ? ' bg-light' : ''). '" href="'. $url. r($info_uri). '">'. h($info_uri). '</a>'. $n;
	}

	if ($dl)
		$aside .= '<a class="list-group-item list-group-item-action'. ($get_page === $download_contents ? ' bg-light' : ''). '" href="'. $url. r($download_contents). '">'. $download_contents. '</a>'. $n;

	if ($use_contact)
		$aside .= '<a class="list-group-item list-group-item-action'. ($get_page === $contact_us ? ' bg-light' : ''). '" href="'. $url. r($contact_us). '">'. $contact_us. '</a>'. $n;
	$aside .= '</div>';
}

if ($address)
	$aside .=
	'<div id=address class="list-group mb-5">'. $n.
	'<div class="list-group-item list-group-item-primary title">'. ($address_title ? $address_title : $site_name). '</div>'. $n.
	'<div class="list-group-item wrap">'. $address. '</div>'. $n.
	'</div>';

if ($use_popular_articles && $number_of_popular_articles > 0 &&$glob_all_counter_files = glob($glob_dir. 'counter.txt', GLOB_NOSORT))
{
	$aside .=
	'<div id=popular-articles class="list-group mb-5">'. $n.
	'<div class="list-group-item list-group-item-primary title">'. $popular_articles. '</div>'. $n;

	foreach($glob_all_counter_files as $all_counter_files)
		$counter_sort[] = (int)trim(file_get_contents($all_counter_files)). $all_counter_files;

	$counter_sort = array_filter($counter_sort);
	rsort($counter_sort, SORT_NUMERIC);
	for($i = 0, $c = count($counter_sort); $i < $c && $i < $number_of_popular_articles; ++$i)
	{
		$popular_titles = explode('/', $counter_sort[$i]);
		$aside .=
		'<a class="list-group-item list-group-item-action'. ($get_categ. $get_title === $popular_titles[1]. $popular_titles[2] ? ' bg-light' : ''). '" href="'. $url. r($popular_titles[1]. '/'. $popular_titles[2]). '">'. h($popular_titles[2]). '</a>'. $n;
	}
	$aside .= '</div>'. $n;
}

if ($use_comment && $number_of_new_comments > 0 && $glob_all_comment_files = glob($glob_dir. 'comments/*-~-*.txt', GLOB_NOSORT))
{
	usort($glob_all_comment_files, function($a, $b){return basename($a) < basename($b);});

	$aside .=
	'<div id=recent-comments class="list-group mb-5">'. $n.
	'<div class="list-group-item list-group-item-primary title">'. $recent_comments. '</div>';

	$j = 0;
	foreach($glob_all_comment_files as $all_comments)
	{
		if ($j === $number_of_new_comments) break;
		$comments_content = str_replace($line_breaks, ' ', trim(strip_tags(file_get_contents($all_comments))));
		$new_comments = explode('-~-', $all_comments);
		$comment_link = explode('/', $new_comments[0]);
		$aside .=
		'<a class="list-group-item list-group-item-action" href="'. $url. r($comment_link[1]. '/'. $comment_link[2]). '#cid-'. basename($new_comments[0]). '">'. $n.
		'<p class="comment-text wrap list-group-item-text">'. mb_strimwidth($comments_content, 0, $comment_length, $ellipsis, $encoding). '</p>'. $n.
		'<small class="blockquote-footer text-right">'. h(basename($new_comments[1], '.txt')). ' ('. timeformat(basename($new_comments[0])). ')</small>'. $n.
		'</a> '. $n;
		++$j;
	}
	$aside .= '</div>'. $n;
}