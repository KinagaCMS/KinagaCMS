<?php
if (__FILE__ === implode(get_included_files())) exit;
if ($use_recents && $recent_files = glob($glob_dir. 'index.html', GLOB_NOSORT))
{
	usort($recent_files, 'sort_time');
	$aside .=
	'<div id=recents class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[4]. '">'. $n.
	'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[0]. '</div>'. $n;
	foreach ($recent_files as $k => $recents_name)
	{
		if ($number_of_recents > $k)
		{
			$recent_categ = get_categ($recents_name);
			$recent_title = get_title($recents_name);
			$aside .=
			'<a class="'. $sidebox_content_class[0]. ($categ_name. $title_name === $recent_categ. $recent_title ? ' bg-light current' : ''). '" href="'. $url. r($recent_categ. '/'. $recent_title). '">'.
			($use_thumbnails && !is_dir($recent_images = dirname($recents_name). '/images/') ? '' : get_thumbnail(glob($recent_images. '*', GLOB_NOSORT)[0] ?? '')).
			h($recent_title).
			'</a>'. $n;
		}
	}
	$aside .= '</div>';
}
$glob_info_files = glob('contents/'. (is_admin() || is_subadmin() ? '' : '[!!i]'). '*.html', GLOB_NOSORT);
if ($glob_info_files || $dl || $use_forum || ($use_contact && $mail_address))
{
	usort($glob_info_files, 'sort_time');
	$aside .=
	'<div id=informations class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[5]. '">'. $n.
	'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[1]. '</div>'. $n;

	foreach ($glob_info_files as $info_files)
	{
		$info_uri = basename($info_files, '.html');
		$aside .= '<a class="'. $sidebox_content_class[0]. ($page_name === $info_uri ? ' bg-light current' : ''). '" href="'. $url. r($info_uri). '">'. h($info_uri). '</a>'. $n;
	}
	if ($use_forum)
		$aside .= '<a class="'. $sidebox_content_class[0]. ($page_name === $forum ? ' bg-light current' : ''). '" href="'. $url. r($forum). '">'. $forum. '</a>'. $n;
	if ($dl)
		$aside .= '<a class="'. $sidebox_content_class[0]. ($page_name === $download_contents ? ' bg-light current' : ''). '" href="'. $url. r($download_contents). '">'. $download_contents. '</a>'. $n;
	if ($use_contact && $mail_address)
		$aside .= '<a class="'. $sidebox_content_class[0]. ($page_name === $contact_us ? ' bg-light current' : ''). '" href="'. $url. r($contact_us). '">'. $contact_us. '</a>'. $n;
	$aside .= '</div>';
}

if ($use_popular_articles && $number_of_popular_articles > 0)
{
	if ($glob_all_counter_files = glob($glob_dir. 'counter.txt', GLOB_NOSORT))
	{
		$aside .=
		'<div id=popular-articles class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[6]. '">'. $n.
		'<div class="'. $sidebox_title_class[1]. '">'. $sidebox_title[2]. '</div>'. $n;

		foreach ($glob_all_counter_files as $all_counter_files)
			$counter_sort[] = (int)file_get_contents($all_counter_files). $all_counter_files;

		$counter_sort = array_filter($counter_sort);
		rsort($counter_sort, SORT_NUMERIC);
		foreach ($counter_sort as $k => $v)
		{
			if ($number_of_popular_articles > $k)
			{
				$popular_titles = explode('/', $v);
				$aside .=
				'<a class="'. $sidebox_content_class[0]. ($categ_name. $title_name === $popular_titles[1]. $popular_titles[2] ? ' bg-light current' : ''). '" href="'. $url. r($popular_titles[1]. '/'. $popular_titles[2]). '">'.
				($use_thumbnails && !is_dir($popular_images = 'contents/'. $popular_titles[1]. '/'. $popular_titles[2]. '/images/') ? '' : get_thumbnail(glob($popular_images. '*', GLOB_NOSORT)[0] ?? '')).
				h($popular_titles[2]).
				'</a>'. $n;
			}
		}
		$aside .= '</div>'. $n;
	}
}
if ($use_comment && $number_of_new_comments > 0)
{
	if ($all_comments = glob($glob_dir. 'comments/*'. $delimiter. '*.txt', GLOB_NOSORT))
	{
		usort($all_comments, 'sort_time');
		$all_comments = array_filter($all_comments, function($v) {
			return '700' === substr(decoct(fileperms($v)), 3) ? '' : $v;
		});
		$aside .=
		'<div id=recent-comments class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[7]. '">'. $n.
		'<div class="'. $sidebox_title_class[1]. '">'. $sidebox_title[3]. '</div>';
		foreach ($all_comments as $k => $v)
		{
			if ($number_of_new_comments > $k)
			{
				$new_comments = explode($delimiter, $v);
				$comment_link = explode('/', $new_comments[0]);
				$comment_time = (int)end($comment_link);
				$comments_content = str_replace($line_breaks, ' ', trim(strip_tags(file_get_contents($v))));
				$new_comment_user = basename($new_comments[1], '.txt');
				$comment_user_avatar = avatar($new_comment_user, 20);
				if (is_dir($new_comment_user_handle = 'users/'. $new_comment_user. '/prof/'))
				{
					$new_comment_user = handle($new_comment_user_handle);
					$comment_user_avatar = avatar($new_comment_user_handle, 20);
				}
				$aside .=
				'<a class="'. $sidebox_content_class[0]. '" href="'. $url. r($comment_link[1]. '/'. $comment_link[2]). '#cid-'. $comment_time. '">'. $n.
				'<p class="'. $sidebox_content_class[1]. '">'. mb_strimwidth($comments_content, 0, $comment_length, $ellipsis, $encoding). '</p>'. $n.
				'<small class="d-flex justify-content-between align-items-baseline"><span>'. $comment_user_avatar. ' '. ($new_comment_user). '</span><span class="badge badge-pill badge-success text-nowrap">'. timeformat($comment_time, $intervals). '</span></small>'. $n.
				'</a> '. $n;
			}
		}
		$aside .= '</div>'. $n;
	}
}
if ($address)
{
	$aside .=
	'<div id=address class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[9]. '">'. $n.
	'<div class="'. $sidebox_title_class[1]. '">'. ($address_title ?: $site_name). '</div>'. $n.
	'<div class="'. $sidebox_content_class[2]. '">'. trim($address). '</div>'. $n;
	foreach ($additional_address as $adds)
		if ($adds) $aside .= '<div class="'. $sidebox_content_class[2]. '">'. trim($adds). '</div>';
	$aside .='</div>';
}
if ($use_forum)
{
	if ($forum_topic_glob = glob('./forum/[!#]*/[!#]*', GLOB_NOSORT))
	{
		usort($forum_topic_glob, 'sort_time');
		$aside .= '<div id=recent-topics class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[11]. '">'. $n.
		'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[11]. '</div>'. $n;
		foreach ($forum_topic_glob as $k => $v)
		{
			if ($number_of_topics > $k)
			{
				$newtopic = basename($v);
				$newtopic_title = $newtopic[0] === '!' || $newtopic[0] === '@' ? h(substr($newtopic, 1)) : h($newtopic);
				$topic_dir = basename(dirname($v));
				$topic_time = timeformat(filemtime($v), $intervals);
				$aside .= '<a class="d-flex justify-content-between align-items-baseline '. $sidebox_content_class[0]. (isset($forum_topic) && $forum_topic === $newtopic ? ' bg-light current' : ''). '" href="'. $url. r($forum). '/'. r($topic_dir). '/'. r($newtopic). '">'. $newtopic_title. ' <small class="badge badge-primary badge-pill ml-2">'. $topic_time. '</small></a>'. $n;
			}
		}
		$aside .= '</div>';
	}
}
