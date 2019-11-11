<?php
if (is_dir($current_article_dir = 'contents/'. $categ_name. '/'. $title_name) && is_file($current_article = $current_article_dir. '/index.html'))
{
	$login_txt = $current_article_dir. '/login.txt';
	$categ_login_txt = 'contents/'. $categ_name. '/login.txt';
	$breadcrumb .=
	'<li class=breadcrumb-item><a href="'. $url. $get_categ. '/">'. h($categ_name). '</a></li>'. $n.
	'<li class="breadcrumb-item active">'. h($title_name). '</li>';

	if (is_dir($background_images_dir = $current_article_dir. '/background-images') && $glob_background_images = glob($background_images_dir. '/*', GLOB_NOSORT))
	{
		$header .= '<style>';

		foreach ($glob_background_images as $background_images)
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
		$header .= '<style>.tooltip.bs-tooltip-auto[x-placement^=top] .arrow::before,.tooltip.bs-tooltip-top .arrow::before{border-top-color:'. $tooltip_color. '}.tooltip.bs-tooltip-auto[x-placement^=bottom] .arrow::before,.tooltip.bs-tooltip-bottom .arrow::before{border-bottom-color:'. $tooltip_color. '}.tooltip.bs-tooltip-auto[x-placement^=right] .arrow::before,.tooltip.bs-tooltip-right .arrow::before{border-right-color:'. $tooltip_color. '}.tooltip.bs-tooltip-auto[x-placement^=left] .arrow::before,.tooltip.bs-tooltip-left .arrow::before{border-left-color:'. $tooltip_color. '}.tooltip-inner{background-color:'. $tooltip_color. ';padding:2px;max-width:inherit}</style>';
		$footer .= '<script>';
		foreach ($glob_tooltip_images as $tooltip_images)
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
	if ($count = counter($current_article_dir. '/counter.txt'))
		$article .= '<small class="ml-2 text-muted">'. sprintf($views, $count). '</small>';
	$article .= '<h1 class="h3 mb-4">'. $article_encode_title;

	if ($use_comment && is_dir($comment_dir = $current_article_dir. '/comments') && $glob_comment_files = glob($comment_dir. '/*'. $delimiter. '*.txt', GLOB_NOSORT))
	{
		$count_comments = count($glob_comment_files);
		$article .= '<small class=ml-3><a href=#comment>'. sprintf($comments_count_title, $count_comments). '</a></small>';
	}
	$article .= '</h1>';

	ob_start();
	include $current_article;
	$current_article_content = trim(ob_get_clean());

	$header .= '<meta name=description content="'. get_description($current_article_content). '">'. $n;
	$article .= '<div class="mb-2 px-2 article clearfix">';
	if (is_file($ticket) && (is_file($login_txt) || is_file($categ_login_txt)) && !isset($_SESSION['l']))
	{
		if (is_file($login_txt) && filesize($login_txt)) $article .= file_get_contents($login_txt);
		if (is_file($categ_login_txt) && filesize($categ_login_txt)) $article .= file_get_contents($categ_login_txt);
		if (is_file($login_txt) && !filesize($login_txt) || is_file($categ_login_txt) && !filesize($categ_login_txt))
			$article .= get_summary($current_article). '<p class="alert alert-warning my-3">'. $login_required[0]. '</p>';
	}
	else
		$article .= $current_article_content;

	$article .='</div>'. $n;

	if ($images_per_page >= 1 && is_dir($images_dir = $current_article_dir. '/images') && $glob_image_files = glob($images_dir. $glob_imgs, GLOB_BRACE))
	{
		$glob_images_number = count($glob_image_files);
		$page_ceil = ceil($glob_images_number / $images_per_page);
		$max_pages = min($pages, $page_ceil);
		$images_in_page = array_slice($glob_image_files, ($max_pages - 1) * $images_per_page, $images_per_page);

		if ($glob_images_number > $images_per_page)
			pager($max_pages, $page_ceil);

		$article .= '<div class="images text-center">'. $n;
		foreach ($images_in_page as $article_images)
			$article .= img($article_images, '', true);
		$article .= '</div>'. $n;

		if ($glob_images_number > $images_per_page)
			pager($max_pages, $page_ceil);
	}

	if ($glob_prev_next = glob('contents/'. $categ_name. '/*/index.html', GLOB_NOSORT))
	{
		$similar_article = [];
		foreach ($glob_prev_next as $prev_next)
		{
			$similar_titles = get_title($prev_next);
			similar_text($title_name, $similar_titles, $percent);
			$per = round($percent);

			if ($per < 100 && $per >= 20)
				$similar_article[] = $per. $delimiter. $similar_titles;

			$sort_prev_next[] = filemtime($prev_next). $delimiter. $prev_next;
		}

		if ($use_prevnext)
		{
			$prev_link = '';
			rsort($sort_prev_next);
			$article .= '<nav class="d-flex border mt-5">';

			for ($i = 0, $c = count($sort_prev_next); $i < $c; ++$i)
			{
				$prev_next_parts = explode($delimiter, $sort_prev_next[$i]);
				$prev_next_title = get_title($prev_next_parts[1]);
				$prev_next_href = $url. $get_categ. '/'. r($prev_next_title);
				$prev_next_encode_title = h($prev_next_title);

				if ((int)$prev_next_parts[0] > $article_filemtime)
				{
					$header_prev = '<link rel=prev href="'. $prev_next_href. '">'. $n;
					$prev_link =
					'<a class="flex-fill p-2 text-decoration-none w-50" title="'. $prev_next_encode_title. '" href="'. $prev_next_href. '">'. $n.
					'<span class="d-block mb-1 text-secondary">'. $article_prevnext[0]. '</span>'.
					'<span class="d-block pb-3 px-3">'. mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding). '</span>'. $n.
					'</a>'. $n.
					'<span class="px-1 d-flex align-items-center bg-secondary text-white">'. $nav_raquo. '</span>'. $n;
				}
				if ((int)$prev_next_parts[0] < $article_filemtime)
				{
					$header_next = '<link rel=next href="'. $prev_next_href. '">'. $n;
					$article .=
					'<span class="px-1 d-flex align-items-center bg-secondary text-white">'. $nav_laquo. '</span>'. $n.
					'<a class="border-right flex-fill p-2 text-decoration-none w-50" title="'. $prev_next_encode_title. '" href="'. $prev_next_href. '">'. $n.
					'<span class="d-block mb-1 text-secondary">'. $article_prevnext[1]. '</span>'. $n.
					'<span class="d-block pb-3 px-3">'. mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding). '</span>'. $n.
					'</a>'. $n;
					break;
				}
			}
			if (isset($header_prev))
				$header .= $header_prev;
			if (isset($header_next))
				$header .= $header_next;
			$article .= $prev_link. '</nav>'. $n;
		}

		if ($use_similars && $similar_article)
		{
			$similar_counts = count($similar_article);

			if ($similar_counts >= 1)
			{
				$aside .=
				'<div id=similars class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[1]. '">'. $n.
				'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[6]. '</div>'. $n;
				rsort($similar_article);
				for ($i = 0; $i < $similar_counts && $i < $number_of_similars; ++$i)
				{
					$similar = explode($delimiter, $similar_article[$i]);
					$aside .= '<a class="'. $sidebox_content_class[0]. '" href="'. $url. $get_categ. '/'. r($similar[1]). '">'. h($similar[1]). '</a>'. $n;
				}
				$aside .= '</div>';
			}
		}
	}
	if ($use_social)
		social(rawurlencode($title_name. ' - '. $site_name), rawurlencode($url. $categ_name. '/'. $title_name));

	if ($use_permalink)
		permalink($article_encode_title. ' - '. $site_name, $current_url);

	if ($use_comment && is_dir($comment_dir))
	{
		$article .=
		'<section class=my-5 id=comment>'. $n.
		'<h2 class=mb-4>'. $comment. '</h2>'. $n;

		if (is_file($ticket) && (is_file($login_txt) || is_file($categ_login_txt)) && !isset($_SESSION['l']))
			$article .= '<p class="alert alert-warning my-3">'. $login_required[1]. '</p>';
		else
		{
			if (isset($glob_comment_files) && $comments_per_page > 0)
			{
				rsort($glob_comment_files);

				foreach ($glob_comment_files as $comment_files)
				{
					if (stripos($comment_files, $delimiter) !== false)
					{
						$comment_file = explode($delimiter, $comment_files);
						$comment_time = basename($comment_file[0]);
						$comment_user = basename($comment_file[1], '.txt');

						if (is_dir($comment_user_profdir = $usersdir. $comment_user. '/prof/'))
						{
							$comment_user = '<a href="'. $url. '?user='. str_rot13($comment_user). '">'. handle($comment_user_profdir). '</a>';
							$comment_user_avatar =avatar($comment_user_profdir);
						}
						else
							$comment_user_avatar = '<span class="align-middle comment-icon d-table-cell font-weight-bold display-3 h-100 rounded text-white w-100">'. mb_substr($comment_user, 0, 1). '</span>';

						$comment_content = str_replace($line_breaks, '&#10;', h(strip_tags(file_get_contents($comment_files))));

						$comments_array[] =
						'<div class="d-flex mb-4 position-relative">'. $n.
						'<div class="avatar d-table mr-4 rounded text-center">'. $comment_user_avatar. '</div>'. $n.
						'<div class=card-arrow></div>'. $n.
						'<div class="card comment w-100" id=cid-'. $comment_time. '>'. $n.
						'<div class="card-body wrap">'. $comment_content. '</div>'. $n.
						'<div class=card-footer><span class=mr-3>'. $comment_user. '</span><span class=text-nowrap>'. timeformat($comment_time, $intervals). '</span></div>'. $n.
						'</div>'. $n.
						'</div>'. $n;
					}
				}
				if (isset($comments_array))
				{
					$article .= '<div class=card-columns>'. $n;
					$sliced_comments = array_slice($comments_array, ($comment_pages - 1) * $comments_per_page, $comments_per_page);

					foreach ($sliced_comments as $number_of_comments)
						$article .= $number_of_comments;

					$article .= '</div>'. $n;

					if ($count_comments > $comments_per_page)
					{
						$article .= '<nav class=mb-5>'. $n;
						if ($comment_pages < ceil($count_comments / $comments_per_page))
							$article .= '<a class="float-left badge badge-pill badge-primary" href="'. $current_url. '&amp;comments='. ($comment_pages + 1). '#comment">'. $comments_next. '</a>'. $n;
						if ($comment_pages > 1)
							$article .= '<a class="float-right badge badge-pill badge-primary" href="'. $current_url. '&amp;comments='. ($comment_pages - 1). '#comment">'. $comments_prev. '</a>'. $n;
						$article .= '</nav>'. $n;
					}
				}
			}
			if (is_file($comment_dir. '/end.txt'))
				$article .= '<p class="alert alert-warning mb-5">'. $comments_not_allow. '</p>'. $n;
			else
			{
				if ($comment_privacy_policy)
					$article .= '<p id=privacy-policy class="alert alert-warning mt-4 wrap">'. $comment_privacy_policy. '</p>'. $n;
				ob_start();
				include $form;
				$article .= trim(ob_get_clean());
			}
		}
		$article .= '</section>';
	}
}
else
	not_found();
