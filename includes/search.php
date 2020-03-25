<?php
if ($query)
{
	$no_results = '';
	$result_title = sprintf($result, $query);
	$breadcrumb .= '<li class="breadcrumb-item active">'. $result_title. '</li>';
	$header .= '<title>'. $result_title. ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;
	$article .= '<h1 class="h3 mb-4">'. $result_title. '</h1>'. $n;
	$glob_search = glob('{'. $glob_dir. 'index.html,contents/*.html}', GLOB_BRACE + GLOB_NOSORT);

	if ($glob_search)
	{
		usort($glob_search, 'sort_time');

		foreach($glob_search as $filename)
		{
			$temp = file_get_contents($filename);
			$file_title = get_title($filename);
			$temp_title = $file_title === 'index' ? strip_tags(basename($filename, '.html')) : get_categ($filename). $file_title;
			$timestamp = date($time_format, filemtime($filename));
			$first_pos = mb_stripos($temp. $temp_title. $timestamp, html_entity_decode($query));

			if ($first_pos !== false)
			{
				$start = max(0, $first_pos - 150);
				$length = $summary_length + mb_strlen($query, $encoding);
				$str = mb_substr($temp, $start, $length, $encoding);
				$str = $str === '' ? mb_strimwidth($temp, 0, $summary_length, $ellipsis, $encoding) : mb_strimwidth($str, 0, $summary_length, $ellipsis, $encoding);
				$str = str_replace($query, '<span class=highlight>'. $query. '</span>', $str);
				$outputs[] = [$timestamp, $filename, $str];
			}
		}
		if (isset($outputs))
		{
			$results_number = count($outputs);
			$page_ceil = ceil($results_number / $results_per_page);
			$max_pages = min($pages, $page_ceil);
			$results_in_page = array_slice($outputs, ($max_pages - 1) * $results_per_page, $results_per_page);

			if ($results_number > $results_per_page)
				pager($max_pages, $page_ceil);

			for($i = 0, $c = count($results_in_page); $i < $c; ++$i)
			{
				$output = $results_in_page[$i];
				$output_title = get_title($output[1]);
				$output_categ = get_categ($output[1]);
				$pagename = basename($output[1], '.html');

				$article .= '<section class="p-2 mb-5 border-bottom position-relative">'. $n;

				if ($output_title === 'index')
					$article .=
					'<h2 class=h4><a class=stretched-link href="'. $url. '">'. $home. '</a></h2>'. $n.
					'<div class="wrap p-2">'. h($output[2]). '</div>'. $n.
					'<small class="blockquote-footer text-right">'. $output[0].'</small>';

				elseif ($pagename !== 'index')
					$article .=
					'<h2 class=h4><a class=stretched-link href="'. $url. r($pagename). '">'. h($pagename). '</a></h2>'. $n.
					'<div class="wrap p-2">'. h($output[2]). '</div>'. $n.
					'<small class="blockquote-footer text-right">'. $output[0].'</small>';

				else
					$article .=
					'<h2 class=h4><a class=stretched-link href="'. $url. r($output_categ. '/'. $output_title). '">'. h($output_title). '</a></h2>'. $n.
					'<div class="wrap p-2">'. h($output[2]). '</div>'. $n.
					'<small class="blockquote-footer text-right">'. $output[0]. ' - '. h($output_categ). '</small>';

				$article .= '</section>'. $n;

			}
			if ($results_number > $results_per_page)
				pager($max_pages, $page_ceil);
		}
		else
			$no_results = true;
	}
	else
		$no_results = true;

	if ($no_results)
		$article .= '<h2 class=h4>'. $no_results_found. '</h2>'. $n;
}
