<?php
if ('!' === d($request_query[0])[0] || (isset($query) && '!' === $query[0]) || (isset($fquery) && '!' === $fquery[0])) $no_results = true;
if (false !== $pos && $query)
{
	$result_title = sprintf($result, $query);
	$breadcrumb .= '<li class="breadcrumb-item active">'. $result_title. '</li>';
	$header .= '<title>'. $result_title. ' - '. ($pages > 1 ? sprintf($page_prefix, (int)$pages). ' - ' : ''). $site_name. '</title>';
	$article .= '<header><h1 class="'. $h1_title[0]. '">'. $result_title. '</h1></header>';
	$glob_search = filter_has_var(INPUT_GET, 'categ') ?
	glob('contents/'. ($site_name !== d($request_query[0]) && !$get_title ? d($request_query[0]) : $categ_name). '/'. (is_admin() || is_subadmin() ? '' : '[!!]'). '*/index.html', GLOB_NOSORT) :
	glob('{'. $glob_dir. 'index.html,contents/'. (is_admin() || is_subadmin() ? '' : '[!!]'). '*.html}', GLOB_BRACE + GLOB_NOSORT);
	if ($glob_search)
	{
		usort($glob_search, 'sort_time');
		foreach($glob_search as $filename)
		{
			$temp = file_get_contents($filename);
			$file_title = get_title($filename);
			$author = !is_file($author_txt = dirname($filename). '/author.txt') ? '' : handle('users/'. basename(file_get_contents($author_txt)). '/prof/');
			$temp_title = 'index' === $file_title ? strip_tags(basename($filename, '.html')) : get_categ($filename). $file_title;
			$timestamp = date($time_format, filemtime($filename));
			$first_pos = mb_stripos($temp. $temp_title. $timestamp. $author, $query);
			if (false !== $first_pos)
			{
				$start = max(0, $first_pos - 150);
				$length = $summary_length + mb_strlen($query, $encoding);
				$str = mb_substr($temp, $start, $length, $encoding);
				$str = !$str ? mb_strimwidth($temp, 0, $summary_length, $ellipsis, $encoding) : mb_strimwidth($str, 0, $summary_length, $ellipsis, $encoding);
				$str = str_replace($query, '<strong class=highlight>'. $query. '</strong>', strip_tags($str));
				$outputs[] = [$timestamp, $filename, $str];
			}
		}
		if (isset($outputs))
		{
			$results_number = count($outputs);
			$page_ceil = ceil($results_number / $results_per_page);
			$max_pages = min($pages, $page_ceil);
			$results_in_page = array_slice($outputs, ($max_pages - 1) * $results_per_page, $results_per_page);
			if ($results_number > $results_per_page) pager($max_pages, $page_ceil);
			foreach ($results_in_page as $results)
			{
				$output_title = get_title($results[1]);
				$output_categ = get_categ($results[1]);
				$pagename = basename($results[1], '.html');
				$article .= '<section class="'. $results_wrapper_class. ' position-relative">';
				if ('index' === $output_title)
					$article .=
					'<h2 class=h4><a class=stretched-link href="'. $url. '">'. $home. '</a></h2>'.
					'<div class="wrap p-2">'. str_replace($line_breaks, '&#10;', $results[2]). '</div>'.
					'<small class="blockquote-footer text-end">'. $results[0].'</small>';
				elseif ('index' !== $pagename)
					$article .=
					'<h2 class=h4><a class=stretched-link href="'. $url. r($pagename). '">'. h($pagename). '</a></h2>'.
					'<div class="wrap p-2">'. str_replace($line_breaks, '&#10;', $results[2]). '</div>'.
					'<small class="blockquote-footer text-end">'. $results[0].'</small>';
				else
					$article .=
					'<h2 class=h4><a class=stretched-link href="'. $url. r($output_categ. '/'. $output_title). '">'. h($output_title). '</a></h2>'.
					'<div class="wrap p-2">'. str_replace($line_breaks, '&#10;', $results[2]). '</div>'.
					'<small class="blockquote-footer text-end">'. $results[0]. ' - '. h($output_categ). '</small>';
				$article .= '</section>';
			}
			if ($results_number > $results_per_page) pager($max_pages, $page_ceil);
		}
		else
			$no_results = true;
	}
	else
		$no_results = true;
	if ($no_results) $article .= '<section class="alert alert-warning my-4">'. $not_found[2]. '</section>';
}
elseif ($use_forum && false !== $fpos && $fquery)
{
	$result_title = sprintf($result, h($fquery));
	$breadcrumb =
	'<li class=breadcrumb-item><a href="'. $url. '">'. $home. '</a></li>'.
	'<li class="breadcrumb-item"><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
	(!filter_has_var(INPUT_GET, 'thread') ? '' : '<li class=breadcrumb-item><a href="'. $thread_url. '">'. $thread_title. '</a></li>').
	'<li class="breadcrumb-item active">'. $result_title. '</li>';
	$header .= '<title>'. $result_title. ' - '. ($pages > 1 ? sprintf($page_prefix, (int)$pages). ' - ' : ''). $site_name. '</title>';
	$article .= '<header><h1 class="'. $h1_title[0]. '">'. $result_title. '</h1></header>';
	$forum_search_area = 'forum/'. (filter_has_var(INPUT_GET, 'thread') ? $forum_thread. '/[!#]*' : '[!#]*/[!#]*');
	$forum_search_glob = glob($forum_search_area, GLOB_NOSORT);
	if ($forum_search_glob)
	{
		usort($forum_search_glob, 'sort_time');
		foreach($forum_search_glob as $topics)
		{
			if (is_file($topics))
			{
				foreach (file($topics) as $topic_line)
				{
					$topic_str = str_getcsv($topic_line, ',', "\"", "\\");
					if ('#' === $topic_str[0][0]) continue;
					$topic_contents = html_entity_decode($topic_str[2]);
					$timestamp = date($time_format, $topic_str[0]);
					$resser = handle('users/'. $topic_str[1]. '/prof/');
					$first_pos = strpos($topic_contents. $timestamp. $resser, $fquery);
					if (false !== $first_pos)
					{
						$start = max(0, $first_pos - 150);
						$length = $summary_length + mb_strlen($fquery, $encoding);
						$str = mb_substr($topic_contents, $start, $length, $encoding);
						$str = !$str ? mb_strimwidth($topic_contents, 0, $summary_length, $ellipsis, $encoding) : mb_strimwidth($str, 0, $summary_length, $ellipsis, $encoding);
						$str = str_replace($fquery, '<strong class=highlight>'. $fquery. '</strong>', strip_tags($str));
						$outputs[] = [$timestamp, $topics, $str];
						break;
					}
				}
			}
		}
		if (isset($outputs))
		{
			$results_number = count($outputs);
			$page_ceil = ceil($results_number / $results_per_page);
			$max_pages = min($pages, $page_ceil);
			$results_in_page = array_slice($outputs, ($max_pages - 1) * $results_per_page, $results_per_page);
			if ($results_number > $results_per_page) pager($max_pages, $page_ceil);
			foreach ($results_in_page as $output)
			{
				$base_title = basename($output[1]);
				$topic_title = '!' === $base_title[0] || '@' === $base_title[0] ? substr($base_title, 1) : $base_title;
				$dir_title = get_title($output[1]);
				$thread_title = '!' === $dir_title[0] || '@' === $dir_title[0] ? substr($dir_title, 1) : $dir_title;
				$article .=
				'<section class="p-2 mb-5 border-bottom position-relative">'.
				'<h2 class=h4><a class=stretched-link href="'. $url. r($forum. '/'. $dir_title. '/'. $base_title). '">'. h($topic_title). '</a></h2>'.
				'<div class="wrap p-2">'. str_replace($line_breaks, '&#10;', strip_tags($output[2], '<strong>')). '</div>'.
				'<small class="blockquote-footer text-end">'. h($thread_title). ' - '. $output[0]. '</small>'.
				'</section>';
			}
			if ($results_number > $results_per_page) pager($max_pages, $page_ceil);
		}
		else
			$no_results = true;
	}
	else
		$no_results = true;
	if ($no_results) $article .= '<section class="alert alert-warning my-4">'. $not_found[2]. '</section>';
}
