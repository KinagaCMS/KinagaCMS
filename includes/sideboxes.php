<?php
if ($use_recents && $recent_files = glob($glob_dir. 'index.html', GLOB_NOSORT))
{
	usort($recent_files, 'sort_time');

	$aside .=
	'<div id=recents class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[4]. '">'. $n.
	'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[0]. '</div>'. $n;
	$j = 0;
	foreach ($recent_files as $recents_name)
	{
		if ($j === $number_of_recents) break;
		$recent_categ = get_categ($recents_name);
		$recent_title = get_title($recents_name);
		$aside .= '<a class="'. $sidebox_content_class[0]. ($categ_name. $title_name === $recent_categ. $recent_title ? ' bg-light current' : ''). '" href="'. $url. r($recent_categ. '/'. $recent_title). '">'. h($recent_title). '</a>'. $n;
		++$j;
	}
	$aside .= '</div>';
}

$glob_info_files = glob('contents/[!index]*.html', GLOB_NOSORT);
if ($glob_info_files || $dl || $use_contact && $mail_address)
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

	if ($dl)
		$aside .= '<a class="'. $sidebox_content_class[0]. ($page_name === $download_contents ? ' bg-light current' : ''). '" href="'. $url. r($download_contents). '">'. $download_contents. '</a>'. $n;

	if ($use_contact && $mail_address)
		$aside .= '<a class="'. $sidebox_content_class[0]. ($page_name === $contact_us ? ' bg-light current' : ''). '" href="'. $url. r($contact_us). '">'. $contact_us. '</a>'. $n;
	$aside .= '</div>';
}

if ($use_popular_articles && $number_of_popular_articles > 0 && $glob_all_counter_files = glob($glob_dir. 'counter.txt', GLOB_NOSORT))
{
	$aside .=
	'<div id=popular-articles class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[6]. '">'. $n.
	'<div class="'. $sidebox_title_class[1]. '">'. $sidebox_title[2]. '</div>'. $n;

	foreach ($glob_all_counter_files as $all_counter_files)
		$counter_sort[] = (int)trim(file_get_contents($all_counter_files)). $all_counter_files;

	$counter_sort = array_filter($counter_sort);
	rsort($counter_sort, SORT_NUMERIC);
	for ($i = 0, $c = count($counter_sort); $i < $c && $i < $number_of_popular_articles; ++$i)
	{
		$popular_titles = explode('/', $counter_sort[$i]);
		$aside .=
		'<a class="'. $sidebox_content_class[0]. ($categ_name. $title_name === $popular_titles[1]. $popular_titles[2] ? ' bg-light current' : ''). '" href="'. $url. r($popular_titles[1]. '/'. $popular_titles[2]). '">'. h($popular_titles[2]). '</a>'. $n;
	}
	$aside .= '</div>'. $n;
}

if ($use_comment && $number_of_new_comments > 0 && $all_comments = glob($glob_dir. 'comments/*'. $delimiter. '*.txt', GLOB_NOSORT))
{
	usort($all_comments, 'sort_name');

	$aside .=
	'<div id=recent-comments class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[7]. '">'. $n.
	'<div class="'. $sidebox_title_class[1]. '">'. $sidebox_title[3]. '</div>';

	foreach (range(0, $number_of_new_comments) as $j)
	{
		if (isset($all_comments[$j]) && is_file($all_comments[$j]))
		{
			$new_comments = explode($delimiter, $all_comments[$j]);
			$comment_link = explode('/', $new_comments[0]);
			$comment_time = (int)end($comment_link);
			$comments_content = str_replace($line_breaks, ' ', trim(strip_tags(file_get_contents($all_comments[$j]))));
			$new_comment_user = basename($new_comments[1], '.txt');
			if (is_dir($new_comment_user_handle = $usersdir. $new_comment_user. '/prof/'))
				$new_comment_user = handle($new_comment_user_handle);
			$aside .=
			'<a class="'. $sidebox_content_class[0]. '" href="'. $url. r($comment_link[1]. '/'. $comment_link[2]). '#cid-'. $comment_time. '">'. $n.
			'<p class="'. $sidebox_content_class[1]. '">'. mb_strimwidth($comments_content, 0, $comment_length, $ellipsis, $encoding). '</p>'. $n.
			'<small class="blockquote-footer text-break text-right">'. h($new_comment_user). ' <span class=text-nowrap>('. timeformat($comment_time, $intervals). ')</span></small>'. $n.
			'</a> '. $n;
		}
	}
	$aside .= '</div>'. $n;
}

if ($address)
{
	$aside .=
	'<div id=address class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[9]. '">'. $n.
	'<div class="'. $sidebox_title_class[1]. '">'. ($address_title ?: $site_name). '</div>'. $n.
	'<div class="'. $sidebox_content_class[2]. '">'. trim($address). '</div>'. $n;
	foreach ($additional_address as $adds)
		if ($adds)
			$aside .= '<div class="'. $sidebox_content_class[2]. '">'. trim($adds). '</div>';
	$aside .='</div>';
}