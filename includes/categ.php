<?php
if (is_dir($current_categ = 'contents/'. $categ_name))
{
	$categ_login_txt = $current_categ. '/login.txt';
	$categ_title = h($categ_name);
	$breadcrumb .= '<li class="breadcrumb-item active">'. $categ_title. '</li>';
	$categ_contents = get_dirs($current_categ);
	$categ_contents_number = $categ_contents ? count($categ_contents) : 0;

	if (is_file($categ_file = $current_categ. '/index.html'))
	{
		ob_start();
		include $categ_file;
		$categ_content = trim(ob_get_clean());
		$article .= '<h1 class="h3 mb-4">'. $categ_title. ' <small class="ml-3 wrap text-muted">'. $categ_content. '</small></h1>';
		$header .= '<meta name=description content="'. get_description($categ_content). '">'. $n;
	}
	if ($categ_contents_number > 0)
	{
		foreach ($categ_contents as $articles_name)
			$articles_sort[] = is_file($article_files = $current_categ. '/'. $articles_name. '/index.html') ? filemtime($article_files). $delimiter. $article_files : '';

		$articles_sort = array_filter($articles_sort);
		rsort($articles_sort);
		$page_ceil = ceil($categ_contents_number / $categ_sections_per_page);
		$max_pages = min($pages, $page_ceil);
		$sections_in_categ = array_slice($articles_sort, ($max_pages - 1) * $categ_sections_per_page, $categ_sections_per_page);

		$header .= '<title>'. $categ_title. ' - '. ($max_pages > 1 ? sprintf($page_prefix, $max_pages). ' - ' : ''). $site_name. '</title>'. $n;

		if ($categ_contents_number > $categ_sections_per_page) pager($max_pages, $page_ceil);

		$article .= '<div class="'. $categ_class. '">';
		foreach ($sections_in_categ as $sections)
		{
			$articles = explode($delimiter, $sections);
			$articles_link = explode('/', $articles[1]);
			$categ_link = r($articles_link[1]);
			$title_link = r($articles_link[2]);
			$article_dir = dirname($articles[1]);
			$count_images = '';
			$counter = is_file($counter_txt = $article_dir. '/counter.txt') ?
			'<span class=card-link>'. sprintf($views, (int)file_get_contents($counter_txt)). '</span>' : '';
			$comments = $use_comment && is_dir($comments_dir = $article_dir. '/comments') ?
			'<a class=card-link href="'. $url. $categ_link. '/'. $title_link. '#comment">'. $n.
			sprintf($comment_counts, count(glob($comments_dir. '/*'. $delimiter. '*.txt', GLOB_NOSORT))) .
			'</a>' : '';

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

			$article .=
			'<div class="'. $categ_wrapper_class. '">'. $n.
			$default_image.
			'<div class="'. $categ_content_class. '">'. $n.
			'<h2 class="'. $categ_title_class. '"><a href="'. $url. $categ_link. '/'. $title_link. '">'. ht($articles_link[2]);
			if ($total_images > 0)
				$article .= '<small>'. sprintf($images_count_title, $total_images). '</small>';

			if (is_file($ticket) && (is_file($categ_login_txt) || is_file($article_dir. '/login.txt')) && !isset($_SESSION['l']))
				$article .= '<sup class="d-inline-block lock"></sup>';

			$article .= '</a></h2>'. $n;

			if ($use_summary) $article .= '<p class="categ-summary wrap">'. get_summary($articles[1]). '</p>'. $n;
			$article .=
			'</div>'. $n.
			'<div class="'. $categ_footer_class. '">'. $n.
			'<time class=card-link datetime="'. date('c', $articles[0]). '">'. timeformat($articles[0], $intervals). '</time>';
			if ($counter || $comments) $article .= $counter. $comments;
			$article .= '</div></div>'. $n;
		}
		$article .= '</div>';

		if ($categ_contents_number > $categ_sections_per_page)
			pager($max_pages, $page_ceil);
	}
	elseif (!$categ_file)
		not_found();
	else
		$header .= '<title>'. $categ_title. ' - '. $site_name. '</title>'. $n;
}
else
	not_found();
