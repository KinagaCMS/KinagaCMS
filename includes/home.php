<?php
$header .= '<meta name=description content="'. $meta_description. '">'. $n;

if (is_file($index_file = 'contents/index.html'))
{
	$header .= '<title>'. $site_name. ($subtitle ? ' - '. $subtitle : ''). '</title>'. $n;
	if ($subtitle)
		$article .= '<h1 class="h2 mb-4">'. $site_name. ' <small class="ml-3 wrap text-muted">'. $subtitle. '</small></h1>'. $n;
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
			$article .= '<h1 class="h2 mb-4">'. $site_name. ' <small class="ml-3 wrap text-muted">'. $subtitle. '</small></h1>'. $n;

		$count_glob_files = count($glob_files);
		$page_ceil = ceil($count_glob_files / $number_of_default_sections);
		$max_pages = min($pages, $page_ceil);
		$sections_in_home = array_slice($glob_files, ($max_pages - 1) * $number_of_default_sections, $number_of_default_sections);

		if ($count_glob_files > $number_of_default_sections) pager($max_pages, $page_ceil);

		$article .= '<div class=card-columns>';

		foreach($sections_in_home as $sections)
		{
			$all_link = explode('/', $sections);
			$categ_link = r(get_categ($sections));
			$title_link = r(get_title($sections));
			$article_dir = dirname($sections);
			$filemtime = filemtime($sections);
			$counter = is_file($counter_txt = $article_dir. '/counter.txt') ?
			'<span class=card-link>'. sprintf($views, (int)file_get_contents($counter_txt)). '</span>' : '';
			$comments = is_dir($comments_dir = $article_dir. '/comments') && $use_comment ?
			'<a class=card-link href="'. $url. $categ_link. '/'. $title_link. '#comment"> '. sprintf($comment_counts, count(glob($comments_dir. '/*-~-*.txt'), GLOB_NOSORT)). '</a>' : '';

			if (is_dir($default_imgs_dir = $article_dir. '/images') && $glob_default_imgs = glob($default_imgs_dir. $glob_imgs, GLOB_BRACE))
			{
				$default_image = img($glob_default_imgs[0], 'card-img-top', false, false);
				$count_images = count($glob_default_imgs);
			}
			else
				$default_image = $count_images = '';

			if (is_dir($default_background_dir = $article_dir. '/background-images') && $glob_default_background_imgs = glob($default_background_dir. '/*'))
			{
				$default_background_image = img($glob_default_background_imgs[0], 'card-img-top', false, false);
				$count_background_images = count($glob_default_background_imgs);
			}
			else
				$default_background_image = $count_background_images = '';

			if (is_dir($default_popup_dir = $article_dir. '/popup-images') && $glob_default_popup_imgs = glob($default_popup_dir. '/*', GLOB_NOSORT))
				$count_popup_images = count($glob_default_popup_imgs);
			else
				$count_popup_images = 0;

			$total_images = (int)$count_images + (int)$count_background_images + (int)$count_popup_images;

			$article .=
			'<div class=card>'. $n.
			$default_image. $default_background_image .
			'<div class=card-body>'. $n.
			'<time class="small card-subtitle mb-2 text-muted" datetime="'. date('c', $filemtime). '">'. timeformat($filemtime). '</time>'. $n.
			'<h2 class="h4 card-title"><a href="'. $url. $categ_link. '/'. $title_link. '">'. ht($all_link[2]);

			if ($total_images > 0)
				$article .= '<small>'. sprintf($images_count_title, $total_images). '</small>';

			$article .=
			'</a></h2>';
			if ($use_summary)
				$article .= '<p class=wrap>'. get_summary($sections). '</p>'. $n;
			$article .=
			'<span class="blockquote-footer text-right"><a href="'. $url. $categ_link. '/" class=card-link>'. h($all_link[1]). '</a></span>'. $n.
			'</div>'. $n;
			if ($counter || $comments)
				$article .=
				'<div class="card-footer bg-transparent">'. $counter. $comments. '</div>'. $n;
			$article .=
			'</div>'. $n;
		}
		$article .= '</div>';

		if ($count_glob_files > $number_of_default_sections) pager($max_pages, $page_ceil);
	}
	else
	{
		$header .= '<title>'. $site_name. ($subtitle ? ' - '. $subtitle : ''). '</title>'. $n;
		if ($subtitle)
			$article .= '<h1 class="h2 mb-4">'. $site_name. ' <small class="ml-3 wrap text-muted">'. $subtitle. '</small></h1>'. $n;
		$article .= '<div class=container><div class=row>';
		$c = [];
		foreach($glob_files as $a)
		{
			$c = get_categ($a);
			$t = get_title($a);
			if ($i = glob('contents/'. $c. '/'. $t. '/{background-images,images}/*.[jJ][pP][gG]', GLOB_BRACE))
			{
				if (isset($i[0]))
				{
					$e = @exif_read_data($i[0], '', '', true);
					$m = image_type_to_mime_type(exif_imagetype($i[0]));
				}
			}
			if (isset($i[0]) && isset($e['THUMBNAIL']['THUMBNAIL']) && isset($m))
			{
				if ($index_type === 2)
					$a = '<a class=d-flex href="'. $url. r($c. '/'. $t). '"><img class="rounded-circle mr-3" src="data:'. $m. ';base64,'. base64_encode($e['THUMBNAIL']['THUMBNAIL']). '" alt=""><span class=align-top>'. h($t). '</span></a>';
				if ($index_type === 3)
					$a = '<a class=d-flex href="'. $url. r($c. '/'. $t). '"><img class=mr-3 src="data:'. $m. ';base64,'. base64_encode($e['THUMBNAIL']['THUMBNAIL']). '" alt=""><span class=align-top>'. h($t). '</span></a>';
				if ($index_type === 4)
					$a = '<a href="'. $url. r($c. '/'. $t). '"><img class="mr-4 rounded" src="data:'. $m. ';base64,'. base64_encode($e['THUMBNAIL']['THUMBNAIL']). '" alt=""><span class="d-block text-truncate" style="max-width:200px">'. h($t). '</span></a>';
			}
			else
			{
				if ($index_type === 2) $a = '<a class=d-block href="'. $url. r($c. '/'. $t). '">'. h($t). '</a>';
				if ($index_type === 3) $a = '<a class="d-block mb-3 mr-3 ml-1" href="'. $url. r($c. '/'. $t). '">'. h($t). '</a>';
				if ($index_type === 4) $a = '<a class="mb-3 mr-4" href="'. $url. r($c. '/'. $t). '">'. h($t). '</a>';
			}
			if ($index_type === 2) $b['<a href="'. $url. r($c). '/">'. h($c). '</a>'][] = $a;
			if ($index_type === 3) $b['<a href="'. $url. r($c). '/" class=text-white>'. h($c). '</a>'][] = $a;
			if ($index_type === 4) $b['<a href="'. $url. r($c). '/" class=text-white>'. h($c). '</a>'][] = $a;
		}
		foreach($b as $k => $v)
		{
			$s = array_slice($v, 0, $index_items);
			if ($index_type === 2)
				$article .= '<div class="col-md-6 mb-5"><div class=h4>'. $k. '</div><ul class="list-group list-group-flush"><li class="list-group-item bg-transparent">'. implode('</li><li class="list-group-item bg-transparent">', $s). '</li></ul></div>';
			if ($index_type === 3)
				$article .= '<div class=col-md-6><div class="h4 border-bottom bg-dark px-2 py-1">'. $k. '</div><div class=my-4>'. implode('</div><div class=my-4>', $s). '</div></div>';
			if ($index_type === 4)
				$article .= '<div class="col-md-12 mb-4"><div class="h4 border-bottom bg-dark px-2 py-1">'. $k. '</div><div class=row><div class="my-4 col-md-auto">'. implode('</div><div class="my-4 col-md-auto">', $s). '</div></div></div>';
		}
		$article .= '</div></div>';
	}
}
else
{
	$header .= '<title>'. $site_name. '</title>'. $n;
	if (!glob('contents/*.html', GLOB_NOSORT))
	{
		$header .= '<style>.container-fluid{display:none}</style>'. $n;
		$footer .= '<img src="'. $url. 'images/icon.php" class="d-block w-50 mb-5 mx-auto d-block" style="opacity:.2">';
	}
}
