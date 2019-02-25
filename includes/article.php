<?php
if (is_dir($current_article_dir = 'contents/'. $categ_name. '/'. $title_name) && is_file($current_article = $current_article_dir. '/index.html'))
{
	$breadcrumb .=
	'<li class=breadcrumb-item><a href="'. $url. $get_categ. '/">'. h($categ_name). '</a></li>'. $n.
	'<li class="breadcrumb-item active">'. h($title_name). '</li>';

	if (is_dir($background_images_dir = $current_article_dir. '/background-images') && $glob_background_images = glob($background_images_dir. '/*', GLOB_NOSORT))
	{
		$header .= '<style>';

		foreach($glob_background_images as $background_images)
		{
			if (list($width, $height) = @getimagesize($background_images))
			{
				$extention = get_extension($background_images);
				$exif = @exif_read_data($background_images, '', '', true);
				$classname = '.'. basename($background_images, $extention);
				$aspect = round($height / $width * 100, 1);
				$header .= '@media(max-width:'. ($width * 1.5). 'px){'. $classname. '{'. ($height > 400 ? 'height:0px!important;padding-bottom:'. $aspect. '%' : 'height:'. $height. 'px'). '}}'. $classname. '{max-width:'. $width. 'px;background-image:url('. $url. r($background_images). ');background-size:100%;background-repeat:no-repeat;'. ($height > 1000 ? 'height:0px!important;padding-bottom:'. $aspect. '%' : 'height:'. $height. 'px'). '}'. (isset($exif['COMMENT']) ? $classname. ':after{background-color:rgba(0,0,0,.3);color:white;content:"'. str_replace($line_breaks, '\00a', h(trim(strip_tags($exif['COMMENT'][0])))). '";display:block;line-height:1.1;padding:.7% 1%;word-wrap:break-word;white-space:pre-wrap}' : '');
			}
		}
		$header .= '</style>'. $n;
	}
	if (is_dir($tooltip_images_dir = $current_article_dir. '/tooltip-images') && $glob_tooltip_images = glob($tooltip_images_dir. '/*', GLOB_NOSORT))
	{
		$tooltip_color = $color ? hsla($color) : 'black';
		$header .= '<style>.tooltip.bs-tooltip-auto[x-placement^=top] .arrow::before,.tooltip.bs-tooltip-top .arrow::before{border-top-color:'. $tooltip_color. '}.tooltip.bs-tooltip-auto[x-placement^=bottom] .arrow::before,.tooltip.bs-tooltip-bottom .arrow::before{border-bottom-color:'. $tooltip_color. '}.tooltip.bs-tooltip-auto[x-placement^=right] .arrow::before,.tooltip.bs-tooltip-right .arrow::before{border-right-color:'. $tooltip_color. '}.tooltip.bs-tooltip-auto[x-placement^=left] .arrow::before,.tooltip.bs-tooltip-left .arrow::before{border-left-color:'. $tooltip_color. '}.tooltip-inner{background-color:'. $tooltip_color. ';max-width:inherit}</style>';
		$footer .= '<script>';
		foreach($glob_tooltip_images as $tooltip_images)
		{
			if (list($width, $height) = @getimagesize($tooltip_images))
			{
				$extention = get_extension($tooltip_images);
				$id = basename($tooltip_images, $extention);
				$footer .= '$("#'. $id. '").attr({"style":"border-bottom:thin dotted;cursor:pointer"}).tooltip({html:true,placement:"auto",title:"<img src=\"'. $url. r($tooltip_images). '\" class=\"img-fluid rounded\">"});';
			}
		}
		$footer .= '</script>'. $n;
	}
	$article_encode_title = h($title_name);
	$header .= '<title>'. $article_encode_title. ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;
	$article_filemtime = filemtime($current_article);
	$current_url = $url. $get_categ. '/'. $get_title;
	$article .= '<small class=text-muted>'. sprintf($last_modified, date($time_format, $article_filemtime)). '</small>';

	if (is_file($counter_txt = $current_article_dir. '/counter.txt') && is_writeable($counter_txt))
	{
		if (flock($fr = fopen($counter_txt, 'r+'), LOCK_EX))
		{
			$view_count = (int)fgets($fr) +1;
			$article .= '<small class="ml-2 text-muted">'. sprintf($views, $view_count). '</small>';
			rewind($fr);
			fwrite($fr, $view_count);
			flock($fr, LOCK_UN);
		}
		fclose($fr);
	}
	$article .= '<h1 class="h2 mb-4">'. $article_encode_title;

	if ($use_comment && is_dir($comment_dir = $current_article_dir. '/comments') && $glob_comment_files = glob($comment_dir. '/*-~-*.txt', GLOB_NOSORT))
	{
		$count_comments = count($glob_comment_files);
		$article .= '<small class=ml-3><a href=#comment>'. sprintf($comments_count_title, $count_comments). '</a></small>';
	}
	$article .= '</h1>';

	ob_start();
	include $current_article;
	$current_article_content = trim(ob_get_clean());
	$header .= '<meta name=description content="'. get_description($current_article_content). '">'. $n;
	$article .= '<div class="mb-2 px-2 article clearfix">'. $current_article_content. '</div>'. $n;

	if (is_dir($images_dir = $current_article_dir. '/images') && $glob_image_files = glob($images_dir. $glob_imgs, GLOB_BRACE))
	{
		$glob_images_number = count($glob_image_files);
		$page_ceil = ceil($glob_images_number / $number_of_images);
		$max_pages = min($pages, $page_ceil);
		$images_in_page = array_slice($glob_image_files, ($max_pages - 1) * $number_of_images, $number_of_images);

		if ($glob_images_number > $number_of_images)
			pager($max_pages, $page_ceil);

		$article .= '<div class="gallery text-center">'. $n;
		for($i = 0, $c = count($images_in_page); $i < $c; ++$i)
			$article .= img($images_in_page[$i]);
		$article .= '</div>'. $n;

		if ($glob_images_number > $number_of_images)
			pager($max_pages, $page_ceil);
	}

	if ($glob_prev_next = glob('contents/'. $categ_name. '/*/index.html', GLOB_NOSORT))
	{
		$similar_article = [];
		foreach($glob_prev_next as $prev_next)
		{
			$similar_titles = get_title($prev_next);
			similar_text($title_name, $similar_titles, $percent);
			$per = round($percent);

			if ($per < 100 && $per >= 20)
				$similar_article[] = $per. '-~-'. $similar_titles;

			$sort_prev_next[] = filemtime($prev_next). '-~-'. $prev_next;
		}

		$prev_link = '';
		rsort($sort_prev_next);
		$article .= '<div class="my-5 clearfix">';

		for($i = 0, $c = count($sort_prev_next); $i < $c; ++$i)
		{
			$prev_next_parts = explode('-~-', $sort_prev_next[$i]);
			$prev_next_title = get_title($prev_next_parts[1]);

			if ((int)$prev_next_parts[0] > $article_filemtime)
			{
				$prev_href = $url. $get_categ. '/'. r($prev_next_title);
				$prev_next_encode_title = h($prev_next_title);
				$header_prev = '<link rel=prev href="'. $prev_href. '">'. $n;
				$prev_link = '<a class="btn btn-outline-primary" title="'. $prev_next_encode_title. '" href="'. $prev_href. '">'. mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding). '</a>'. $n;
			}
			if ((int)$prev_next_parts[0] < $article_filemtime)
			{
				$next_href = $url. $get_categ. '/'. r($prev_next_title);
				$prev_next_encode_title = h($prev_next_title);
				$header_next = '<link rel=next href="'. $next_href. '">'. $n;
				$article .= '<a class="float-right btn btn-outline-primary" title="'. $prev_next_encode_title. '" href="'. $next_href. '">'. mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding). '</a>'. $n;
				break;
			}
		}
		if (isset($header_prev))
			$header .= $header_prev;
		if (isset($header_next))
			$header .= $header_next;
		$article .= $prev_link. '</div>';

		if ($use_similars && $similar_article)
		{
			$similar_counts = count($similar_article);

			if ($similar_counts >= 1)
			{
				$article .= '<section class=mb-5>';
				$article .= '<h2>'. $similar_title. '</h2>';
				rsort($similar_article);
				for($i = 0; $i < $similar_counts && $i < $number_of_similars; ++$i)
				{
					$similar = explode('-~-', $similar_article[$i]);
					$article .=
					'<div class="progress similar-article mb-2">'. $n.
					'<a class="progress-bar progress-bar-striped bg-primary" style="width:'. $similar[0]. '%" href="'. $url. $get_categ. '/'. r($similar[1]). '">'. h($similar[1]). ' - '. $similar[0]. '%</a>'. $n.
					'</div>';
				}
				$article .= '</section>';
			}
		}
	}
	if ($use_social)
		social(rawurlencode($title_name. ' - '. $site_name), rawurlencode($url. $categ_name. '/'. $title_name));

	if ($use_permalink)
		permalink($article_encode_title. ' - '. $site_name, $current_url);

	if ($use_comment && is_dir($comment_dir))
	{
		$article .= '<section class=mb-5 id=comment><h2>'. $comment_title. '</h2>'. $n;
		if ($comment_notice)
			$article .= '<p class="alert alert-warning wrap">'. $comment_notice. '</p>'. $n;

		if (isset($glob_comment_files) && $number_of_comments > 0)
		{
			rsort($glob_comment_files);

			foreach($glob_comment_files as $comment_files)
			{
				$pos_comment_files = stripos($comment_files, '-~-');

				if ($pos_comment_files !== false)
				{
					$comment_file = explode('-~-', $comment_files);
					$comment_time = basename($comment_file[0]);
					$comment_content = h(strip_tags(file_get_contents($comment_files)));
					$comment_content = str_replace($line_breaks, '&#10;', $comment_content);
					$comments_array[] =
					'<div class="col-md-6 mb-3">'. $n.
					'<div class="card comment" id=cid-'. $comment_time. '>'. $n.
					'<div class="card-body wrap">'. $comment_content. '</div>'. $n.
					'<div class=card-footer><span class=mr-3>'. basename($comment_file[1], '.txt'). '</span>'. timeformat($comment_time). '</div>'. $n.
					'</div>'. $n.
					'</div>'. $n;
				}
			}
			if (isset($comments_array))
			{
				$article .= '<div class=row>'. $n;
				$comments_in_page = array_slice($comments_array, ($comment_pages - 1) * $number_of_comments, $number_of_comments);

				for($i = 0, $c = count($comments_in_page); $i < $c; ++$i)
					$article .= $comments_in_page[$i];

				$article .= '</div>'. $n;

				if ($count_comments > $number_of_comments)
				{
					$article .= '<nav class=mb-5>'. $n;
					if ($comment_pages < ceil($count_comments / $number_of_comments))
						$article .= '<a class="float-left badge badge-pill badge-primary" href="'. $current_url. '&amp;comments='. ($comment_pages + 1). '#comment">'. $comments_next. '</a>'. $n;
					if ($comment_pages > 1)
						$article .= '<a class="float-right badge badge-pill badge-primary" href="'. $current_url. '&amp;comments='. ($comment_pages - 1). '#comment">'. $comments_prev. '</a>'. $n;
					$article .= '</nav>'. $n;
				}
			}
		}
		if (is_file($comment_dir. '/end.txt'))
			$article .= '<strong class=mb-5>'. $comments_not_allow. '</strong>'. $n;
		else
		{
			ob_start();
			include $form;
			$article .= trim(ob_get_clean());
		}
		$article .= '</section>';
	}
}
else
	not_found();
