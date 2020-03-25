<?php
$header .= '<meta name=description content="'. $meta_description. '">'. $n;

if (is_file($index_file = 'contents/index.html'))
{
	$header .= '<title>'. $site_name. ($subtitle ? ' - '. $subtitle : ''). '</title>'. $n;
	if ($subtitle)
		$article .= '<h1 class="h4 mb-4">'. $site_name. ' <small class="ml-3 wrap text-muted">'. $subtitle. '</small></h1>'. $n;
	$article .= '<div class=article>';
	ob_start();
	include $index_file;
	$article .= trim(ob_get_clean());
	$article .= '</div>'. $n;
}
else	if ($glob_files = glob($glob_dir. 'index.html', GLOB_NOSORT))
{
	usort($glob_files, 'sort_time');

	if ($index_type === 1)
	{
		$header .= '<title>'. $site_name. ($subtitle ? ' - '. $subtitle : ''). ($pages > 1 ? ' - '. sprintf($page_prefix, $pages) : ''). '</title>'. $n;

		if ($subtitle)
			$article .= '<h1 class="h4 mb-4">'. $site_name. ' <small class="ml-3 wrap text-muted">'. $subtitle. '</small></h1>'. $n;

		$count_glob_files = count($glob_files);
		$page_ceil = ceil($count_glob_files / $default_sections_per_page);
		$max_pages = min($pages, $page_ceil);
		$sections_in_home = array_slice($glob_files, ($max_pages - 1) * $default_sections_per_page, $default_sections_per_page);

		if ($count_glob_files > $default_sections_per_page) pager($max_pages, $page_ceil);

		$article .= '<div class="'. $index_class. '">';

		foreach($sections_in_home as $sections)
		{
			$all_link = explode('/', $sections);
			$categ_link = r(get_categ($sections));
			$title_link = r(get_title($sections));
			$categ_dir = dirname(dirname($sections));
			$article_dir = dirname($sections);
			$filemtime = filemtime($sections);
			$counter = is_file($counter_txt = $article_dir. '/counter.txt') ?
			'<span class=card-link>'. sprintf($views, (int)file_get_contents($counter_txt)). '</span>' : '';
			$comments = is_dir($comments_dir = $article_dir. '/comments') && $use_comment ?
			'<a class=card-link href="'. $url. $categ_link. '/'. $title_link. '#comment"> '. sprintf($comment_counts, count(glob($comments_dir. '/*'. $delimiter. '*.txt'), GLOB_NOSORT)). '</a>' : '';

			if (is_dir($default_imgs_dir = $article_dir. '/images') && $glob_default_imgs = glob($default_imgs_dir. $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
			{
				sort($glob_default_imgs);
				$default_image = img($glob_default_imgs[0]);
				$count_images = count($glob_default_imgs);
			}
			else
				$default_image = $count_images = '';

			if (is_dir($default_background_dir = $article_dir. '/background-images'))
				$count_background_images = count(glob($default_background_dir. $glob_imgs, GLOB_NOSORT+GLOB_BRACE));
			else
				$count_background_images = 0;

			$total_images = (int)$count_images + (int)$count_background_images;
			preg_match('/width="(\d+)"/', $default_image, $width);

			$article .=
			'<div class="'. $index_wrapper_class. '">'. $n.
			$default_image.
			'<div class="'. $index_content_class. '">'. $n.
			'<h2 class="'. $index_title_class. '"><a href="'. $url. $categ_link. '/'. $title_link. '">'. ht($all_link[2]);

			if ($total_images > 0)
				$article .= '<small>'. sprintf($images_count_title, $total_images). '</small>';

			if (is_file($ticket) && (is_file($categ_dir. '/login.txt') || is_file($article_dir. '/login.txt')) && !isset($_SESSION['l']))
				$article .= '<sup class="d-inline-block lock"></sup>';

			$article .=
			'</a></h2>';
			if ($use_summary)
				$article .= '<p class="index-summary wrap">'. get_summary($sections). '</p>'. $n;
			$article .=
			'<span class="'. $index_categ_link_class. '"><a href="'. $url. $categ_link. '/" class=card-link>'. h($all_link[1]). '</a></span>'. $n.
			'</div>'. $n.
			'<div class="'. $index_footer_class. '"><time class=card-link datetime="'. date('c', $filemtime). '">'. timeformat($filemtime, $intervals). '</time>';
			if ($counter || $comments) $article .= $counter. $comments;
			$article .=
			'</div></div>'. $n;
		}
		$article .= '</div>';

		if ($count_glob_files > $default_sections_per_page) pager($max_pages, $page_ceil);
	}
	else
	{
		$header .= '<title>'. $site_name. ($subtitle ? ' - '. $subtitle : ''). '</title>'. $n;
		if ($subtitle)
			$article .= '<h1 class="h4 mb-4">'. $site_name. ' <small class="ml-3 wrap text-muted">'. $subtitle. '</small></h1>'. $n;
		$article .= '<div class=container><div class=row>';
		$c = [];
		$header .= '<style>';
		foreach($glob_files as $a)
		{
			$c = get_categ($a);
			$t = get_title($a);

			if ($i = glob('contents/'. $c. '/'. $t. '/images'. $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
			{
				sort($i);
				$img = img($i[0]);
				if ($index_type === 2)
					$a = $img. '<span class="d-inline-block mt-2">'. h($t). '</span>';
				if ($index_type === 3)
					$a = $img. h($t);
				if ($index_type === 4)
				{
					preg_match('/width="(\d+)"/', $img, $width);
					$a = $img. '<span class="d-inline-block text-truncate" style="max-width:'. ($width[1] ?? '200'). 'px">'. h($t). '</span>';
				}
			}
			else
				$a = '<a href="'. $url. r($c. '/'. $t). '">'. h($t). '</a>';

			if (is_file($ticket) && (is_file('contents/'. $c. '/login.txt') || is_file('contents/'. $c. '/'. $t. '/login.txt')) && !isset($_SESSION['l']))
				$a .= '<sup class="d-inline-block lock"></sup>';

			$b['<a href="'. $url. r($c). '/">'. h($c). '</a>'][] = $a;
		}
		$header .= '</style>';
		if (isset($b))
		{
			foreach($b as $k => $v)
			{
				$s = array_slice($v, 0, $index_items);
				if ($index_type === 2)
					$article .= '<div class="col-md-6 mb-5 text-center"><div class=h5>'. $k. '</div><ul class="list-group list-group-flush"><li class="list-group-item bg-transparent">'. implode('</li><li class="list-group-item bg-transparent">', $s). '</li></ul></div>';
				if ($index_type === 3)
					$article .= '<div class=col-md-6><div class=h5>'. $k. '</div><div class="my-4 position-relative">'. implode('</div><div class=my-4>', $s). '</div></div>';
				if ($index_type === 4)
					$article .= '<div class="col-md-12 mb-4"><div class="h5 border-bottom px-2 py-1">'. $k. '</div><div class=row><div class="m-2 col-md-auto">'. implode('</div><div class="m-2 col-md-auto">', $s). '</div></div></div>';
			}
		}
		$article .= '</div></div>';
	}
}
else
{
	$header .= '<title>'. $site_name. '</title>'. $n;
	if (!$index_file || !$contents)
		$article .= '<img src="'. $url. 'images/icon.php" class="d-block w-75 p-3 m-auto" style="opacity:.05">';
}
