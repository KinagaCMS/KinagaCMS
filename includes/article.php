<?php
if (!filter_has_var(INPUT_GET, 'categ') && !filter_has_var(INPUT_GET, 'title') || (!is_admin() && !is_subadmin() && '!' === d($get_categ)[0] && '!' === d($get_title)[0])) exit;

if (is_dir($current_article_dir = 'contents/'. $categ_name. '/'. $title_name) && is_file($current_article = $current_article_dir. '/index.html'))
{
	$login_txt = $current_article_dir. '/login.txt';
	$categ_login_txt = 'contents/'. $categ_name. '/login.txt';
	$breadcrumb .=
	'<li class=breadcrumb-item><a href="'. $url. $get_categ. '">'. h($categ_name). '</a></li>'. $n.
	'<li class="breadcrumb-item active">'. h($title_name). '</li>';
	$article_encode_title = h($title_name);
	$header .= '<title>'. $article_encode_title. ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;
	$article_filemtime = filemtime($current_article);
	$article .= '<header>';
	if (is_file($author_txt = $current_article_dir. '/author.txt') && is_dir($author_prof = 'users/'. basename(file_get_contents($author_txt)). '/prof/'))
		$article .=
		'<a class=mr-3 href="'. $url. '?user='. str_rot13($author = basename(dirname($author_prof))). '">'. avatar($author_prof, 20). ' '. handle($author_prof). '</a>'.
		'<small class="text-muted">'. sprintf($date_created, date($time_format, filemtime($author_txt))). '</small>';
	$article .= '<small class="mx-2 text-muted">'. sprintf($last_modified, date($time_format, $article_filemtime)). '</small>';

	if ($count = counter($current_article_dir. '/counter.txt'))
		$article .= '<small class="text-muted">'. sprintf($views, $count). '</small>';

	$article .= '<h1 class="'. $h1_title[0]. '">'.
	(is_admin() || (isset($author, $_SESSION['l']) && $author === $_SESSION['l']) ? '!' !== $title_name[0] ?
		'<a class="btn btn-sm btn-danger mr-2" href="'. $url. $get_categ. '&amp;delete='. $get_title. '">'. $btn[4]. '</a>'
	:
		'<a class="btn btn-sm btn-success mr-2" href="'. $url. $get_categ. '&amp;post='. $get_title. '">'. $btn[6]. '</a>'.
		'<a class="btn btn-sm btn-info mr-2" href="'. $url. $get_categ. '&amp;edit='. $get_title. '#create-article-form">'. $btn[7]. '</a>'
	: '').
	$article_encode_title;
	if ($use_comment && is_dir($comment_dir = $current_article_dir. '/comments') && $glob_comment_files = glob($comment_dir. '/*'. $delimiter. '*.txt', GLOB_NOSORT))
	{
		$count_comments = count($glob_comment_files);
		$article .= '<small class=ml-3><a href=#comment>'. sprintf($comments_count_title, $count_comments). '</a></small>';
	}
	$article .=
	'</h1>';
	if (is_file($editor_txt = $current_article_dir. '/editor.txt') && is_dir($editor_prof = 'users/'. basename($editor = file_get_contents($editor_txt)). '/prof/'))
	{
		$article .=
		'<div class="small text-right">'.
		'<a href="'. $url. '?user='. str_rot13($editor = basename(dirname($editor_prof))). '">'. avatar($editor_prof, 15). ' '. handle($editor_prof). '</a>'.
		'<span class=ml-3>'. $btn[7]. ' '. date($time_format, filemtime($editor_txt)). '</span>';
		'</div>';
	}
	$article .= '</header>';
	ob_start();
	include $current_article;
	$current_article_content = trim(ob_get_clean());
	$header .= '<meta name=description content="'. get_description($current_article_content). '">'. $n;
	if (is_file($ticket) && (is_file($login_txt) || is_file($categ_login_txt)) && !isset($_SESSION['l']))
	{
		$article .= '<article class="'. $article_wrapper_class. ' article clearfix">';
		if (is_file($categ_login_txt) && filesize($categ_login_txt)) $article .= file_get_contents($categ_login_txt);
		elseif (is_file($login_txt) && filesize($login_txt)) $article .= file_get_contents($login_txt);
		elseif ((is_file($login_txt) && !filesize($login_txt)) || (is_file($categ_login_txt) && !filesize($categ_login_txt)))
			$article .= get_summary($current_article). '<p class="alert alert-warning my-4">'. $login_required[0]. '</p>';
		$article .= '</article>';
	}
	else
	{
		if (is_dir($background_images_dir = $current_article_dir. '/background-images') && $glob_background_images = glob($background_images_dir. '/*', GLOB_NOSORT))
		{
			foreach ($glob_background_images as $background_images)
			{
				if (list($width, $height) = @getimagesize($background_images))
				{
					$extention = get_extension($background_images);
					$exif = @exif_read_data($background_images, '', '', true);
					if ('.png' === strtolower($extention)) $bg_text = get_png_tEXt($background_images);
					$classname = '.'. basename($background_images, $extention);
					$aspect = round($height / $width * 100, 1);
					$stylesheet .= '@media(max-width:'. ($width * 1.5). 'px){'. $classname. '{'. ($height > 400 ? 'height:0px!important;padding-bottom:'. $aspect. '%' : 'height:'. $height. 'px'). '}}'. $classname. '{max-width:'. $width. 'px;background-image:url('. $url. r($background_images). ');background-size:100%;background-repeat:no-repeat;'. ($height > 1000 ? 'height:0px!important;padding-bottom:'. $aspect. '%' : 'height:'. $height. 'px'). '}'. (isset($exif['COMMENT']) || isset($bg_text) ? $classname. ':after{background-color:rgba(0,0,0,.3);color:white;content:"'. str_replace($line_breaks, '\00a', h(strip_tags($exif['COMMENT'][0] ?? $bg_text))). '";display:block;line-height:1.1;padding:.7% 1%;word-wrap:break-word;white-space:pre-wrap}' : '');
				}
			}
		}
		if (is_dir($tooltip_images_dir = $current_article_dir. '/tooltip-images') && $glob_tooltip_images = glob($tooltip_images_dir. '/*', GLOB_NOSORT))
		{
			$tooltip_color = $color ? hsla($color) : 'black';
			$stylesheet .= '.tooltip.bs-tooltip-auto[x-placement^=top] .arrow::before,.tooltip.bs-tooltip-top .arrow::before{border-top-color:'. $tooltip_color. '}.tooltip.bs-tooltip-auto[x-placement^=bottom] .arrow::before,.tooltip.bs-tooltip-bottom .arrow::before{border-bottom-color:'. $tooltip_color. '}.tooltip.bs-tooltip-auto[x-placement^=right] .arrow::before,.tooltip.bs-tooltip-right .arrow::before{border-right-color:'. $tooltip_color. '}.tooltip.bs-tooltip-auto[x-placement^=left] .arrow::before,.tooltip.bs-tooltip-left .arrow::before{border-left-color:'. $tooltip_color. '}.tooltip-inner{background-color:'. $tooltip_color. ';padding:2px;max-width:inherit}';
			foreach ($glob_tooltip_images as $tooltip_images)
			{
				if (list($width, $height) = @getimagesize($tooltip_images))
				{
					$extention = get_extension($tooltip_images);
					$id = basename($tooltip_images, $extention);
					$javascript .= '$("#'. $id. '").attr({"style":"border-bottom:thin dotted;cursor:pointer"}).tooltip({html:true,placement:"auto",title:"<img src=\"'. $url. r($tooltip_images). '\" class=\"img-fluid rounded\">"});';
				}
			}
		}
		if (is_dir($slide_images_dir = $current_article_dir. '/slide-images'))
		{
			if ($slides = glob($slide_images_dir. '/*'))
			{
				$article .=
				'<div id=slide-images class="carousel slide carousel-fade mb-3" data-ride=carousel>'. $n.
				'<ol class=carousel-indicators>';
				foreach ($slides as $k => $v)
				{
					$extention = get_extension($v);
					$slides_exif = @exif_read_data($v, '', '', true);
					$bg_text = '.png' === strtolower($extention) ? get_png_tEXt($v) : '';
					$slides_exif_comment = (isset($slides_exif['COMMENT']) || $bg_text) ? '<div style="background:rgba(0,0,0,.2)" class="carousel-caption d-block wrap">'. h(strip_tags($slides_exif['COMMENT'][0] ?? $bg_text)). '</div>' : '';
					$article .= '<li data-target="#slide-images" data-slide-to='. $k. (0 === $k ? ' class=active' : ''). ' data-interval=10000><span class=sr-only>.</span></li>';
					$carousel_item[] = '<div class="carousel-item'. (0 === $k ? ' active' : ''). '"><img class="img-fluid img-thumbnail d-block w-100" src="'. $url. r($v). '">'. $slides_exif_comment. '</div>';
				}
				$article .=
				'</ol>'. $n.
				'<div class=carousel-inner>'. implode($n, $carousel_item).	'</div>'. $n.
				'<a class=carousel-control-prev href=#slide-images role=button data-slide=prev><span class=carousel-control-prev-icon aria-hidden=true></span></a>'. $n.
				'<a class=carousel-control-next href=#slide-images role=button data-slide=next><span class=carousel-control-next-icon aria-hidden=true></span></a>'. $n.
				'</div>';
			}
		}
		if ($current_article_content)
		{
			$article .= '<article class="'. $article_wrapper_class. ' article clearfix">';
			$separate_count = substr_count($current_article_content, $article_separator) + 1;
			if (1 < $separate_count)
			{
				$separate_images_count = count(glob($current_article_dir. '/images'. $glob_imgs, GLOB_BRACE+GLOB_NOSORT));
				$images_per_page = ceil($separate_images_count/$separate_count);
				for ($i = 0, $c = count($e = explode($article_separator, $current_article_content)); $i <= $c; ++$i)
				{
					if ($i+1 === $pages) $article .= $e[$i];
				}
			}
			else
				$article .= $current_article_content;
			$article .= '</article>';
		}
		if (1 <= $images_per_page && is_dir($images_dir = $current_article_dir. '/images') && $glob_image_files = glob($images_dir. $glob_imgs, GLOB_BRACE))
		{
			$glob_images_number = count($glob_image_files);
			$page_ceil = ceil($glob_images_number/$images_per_page);
			$max_pages = min($pages, $page_ceil);
			$images_in_page = array_slice($glob_image_files, ($max_pages-1) * $images_per_page, $images_per_page);

			if ($glob_images_number > $images_per_page) pager($max_pages, $page_ceil);

			$article .= '<div class="images '. $article_images_wrapper_class. '">'. $n;
			foreach ($images_in_page as $article_images)
				$article .= img($article_images, '', true);
			$article .= '</div>'. $n;

			if ($glob_images_number > $images_per_page) pager($max_pages, $page_ceil);
		}
		if ($use_comment && is_dir($comment_dir))
		{
			$article .=
			'<section class="'. $comment_wrapper_class[0]. '" id=comment>'. $n.
			'<h2 class=mb-4>'. $comment. '</h2>'. $n;

			if (is_admin() || is_subadmin())
			{
				if (filter_has_var(INPUT_POST, 'val') && is_file($permit_comment = $comment_dir. '/'. trim(basename(filter_input(INPUT_POST, 'cid', FILTER_SANITIZE_NUMBER_INT)), '-'). $delimiter. trim(basename(filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING))). '.txt'))
				{
					if (filter_input(INPUT_POST, 'val', FILTER_VALIDATE_BOOLEAN))
						chmod($permit_comment, 0755);
					else
						chmod($permit_comment, 0700);
				}
				$javascript .= 'function permit(e){let f=$(e).parents(".comment");if(e.checked)f.removeClass("banned");else f.addClass("banned");$.post("'. $scheme. $server. $port. $request_uri. '","user="+$(e).parents(".c-user").data("user")+"&cid="+f.attr("id")+"&val="+(e.checked?true:false))}';
			}
			if (is_admin() || (isset($author, $_SESSION['l']) && $author === $_SESSION['l']))
			{
				if (isset($_FILES['b']['error'][0], $_FILES['b']['name'][0], $_FILES['b']['tmp_name'][0]) && UPLOAD_ERR_OK === $_FILES['b']['error'][0])
				{
					foreach ($_FILES['b']['error'] as $key => $error)
					{
						echo $_FILES['b']['name'][$key];
						if (UPLOAD_ERR_OK === $error && 'text/plain' === $_FILES['b']['type'][$key] && (false !== strpos($_FILES['b']['name'][$key], $delimiter) || 'end.txt' === $_FILES['b']['name'][$key]))
						{
							move_uploaded_file($_FILES['b']['tmp_name'][$key], $comment_dir. '/'. basename($_FILES['b']['name'][$key]));
						}
					}
					exit (header('Location: '. $current_url. '#comment'));
				}
				if (is_file($end_txt = $comment_dir. '/end.txt'))
				{
					if (filter_has_var(INPUT_GET, 'del') && 'end' === filter_input(INPUT_GET, 'del', FILTER_SANITIZE_STRING))
					{
						unlink($end_txt);
						exit (header('Location: '. $current_url. '#comment'));
					}
					$article .= '<a href="'. $current_url. '&del=end#comment" class="btn btn-danger">'. $btn[4]. '</a> end.txt';
				}
				else
					$article .=
					'<form class=mb-4 enctype="multipart/form-data" method=post>'.
					'<div class=input-group>'.
					'<div class=custom-file>'.
					'<input type=file class="custom-file-input" id=b name=b[] accesskey=b multiple required accept="text/plain">'.
					'<label class=custom-file-label for=b>'. $placeholder[11]. '</label>'.
					'</div>'.
					'<div class=input-group-append>'.
					'<input class="btn btn-primary" type=submit>'.
					'</div>'.
					'</div>'.
					'</form>';
			}
			if (isset($glob_comment_files) && 0 < $comments_per_page)
			{
				rsort($glob_comment_files);
				foreach ($glob_comment_files as $comment_files)
				{
					if (false !== stripos($comment_files, $delimiter))
					{
						$permitted = '700' === substr(decoct(fileperms($comment_files)), 3) ? 0 : 1;
						$comment_file = explode($delimiter, $comment_files);
						$comment_time = basename($comment_file[0]);
						$comment_user = $comment_user_bk = basename($comment_file[1], '.txt');

						if (is_dir($comment_user_profdir = 'users/'. $comment_user. '/prof/'))
						{
							$comment_user = '<a href="'. $url. '?user='. str_rot13($comment_user). '">'. handle($comment_user_profdir). '</a>';
							$comment_user_avatar = avatar($comment_user_profdir);
						}
						else
							$comment_user_avatar = avatar($comment_user);

						$comment_content = str_replace($line_breaks, '&#10;', h(file_get_contents($comment_files)));

						$comments_array[] =
						'<div class="'. $comment_wrapper_class[1]. ($permitted ? '' : ' banned'). '" id=cid-'. $comment_time. '>'. $n.
						'<div class="'. $comment_content_class. '">'. $n.
						'<div class="d-table mr-4 text-center">'.
						$comment_user_avatar. '</div>'. $n.
						'<div class="'. $comment_body_class. '">'. $n.
						'<div class="c-user '. $comment_user_class. '" data-user="'. $comment_user_bk. '">'.
						'<span class="h5 text-truncate">'. $comment_user. '</span>'.
						'<span class="text-muted text-nowrap">'. timeformat($comment_time, $intervals). '</span>'.
						(is_admin() || is_subadmin() ?
						'<div class="input-group-append btn-group-toggle" data-toggle=buttons>'.
						'<label class="btn btn-danger" for=del-'. $comment_time. '>'.
						'<input class="custom-control-input" type=checkbox id=del-'. $comment_time. ' name=del-'. $comment_time. ' onchange="permit(this)"'. ($permitted ? ' checked' : ''). '>'. $btn[4].
						'</label></div>' : '').
						'</div>'.
						'<p class=wrap>'. (is_admin() || is_subadmin() || $permitted ? $comment_content : str_repeat('*', mb_strlen($comment_content))). '</p>'. $n.
						'</div>'. $n.
						'</div>'. $n.
						'</div>'. $n;
					}
				}
				if (isset($comments_array))
				{
					$article .= '<div class="'. $comment_class. '">'. $n;
					$sliced_comments = array_slice($comments_array, ($comment_pages - 1) * $comments_per_page, $comments_per_page);

					foreach ($sliced_comments as $number_of_comments)
						$article .= $number_of_comments;

					$article .= '</div>'. $n;

					if ($count_comments > $comments_per_page)
					{
						$article .= '<nav class=mb-5>'. $n;
						if ($comment_pages < ceil($count_comments/$comments_per_page))
							$article .= '<a class="float-left badge badge-pill badge-primary" href="'. $current_url. '&amp;comments='. ($comment_pages+1). '#comment">'. $comments_next. '</a>'. $n;
						if (1 < $comment_pages)
							$article .= '<a class="float-right badge badge-pill badge-primary" href="'. $current_url. '&amp;comments='. ($comment_pages-1). '#comment">'. $comments_prev. '</a>'. $n;
						$article .= '</nav>'. $n;
					}
				}
			}
			if (is_file($comment_dir. '/end.txt'))
				$article .= '<p class="alert alert-warning my-4">'. $comments_not_allow. '</p>'. $n;
			else
			{
				if (is_file($ticket) && (is_file($login_txt) || is_file($categ_login_txt)) && !isset($_SESSION['l']))
					$article .= '<p class="alert alert-warning my-4">'. $login_required[1]. '</p>';
				else
				{
					if ($comment_privacy_policy)
						$article .= '<p id=privacy-policy class="alert alert-warning my-4 wrap">'. $comment_privacy_policy. '</p>'. $n;
					ob_start();
					include $form;
					$article .= trim(ob_get_clean());
				}
			}
			$article .= '</section>';
		}
	}
	if ($glob_prev_next = glob('contents/'. $categ_name. '/[!!]*/index.html', GLOB_NOSORT))
	{
		$similar_article = [];
		foreach ($glob_prev_next as $prev_next)
		{
			$similar_titles = get_title($prev_next);
			similar_text($title_name, $similar_titles, $percent);
			$per = round($percent);

			if (100 > $per && 20 <= $per)
				$similar_article[] = $per. $delimiter. $similar_titles;

			$sort_prev_next[] = filemtime($prev_next). $delimiter. $prev_next;
		}
		if ($use_prevnext && 1 < $c = count($sort_prev_next))
		{
			$prev_link = '';
			rsort($sort_prev_next);
			$article .= '<nav id=article-nav class="'. $article_nav_wrapper_class. '">';
			foreach ($sort_prev_next as $prevnext)
			{
				$prev_next_parts = explode($delimiter, $prevnext);
				$prev_next_title = get_title($prev_next_parts[1]);
				$prev_next_href = $url. $get_categ. r($prev_next_title);
				$prev_next_encode_title = h($prev_next_title);
				if ((int)$prev_next_parts[0] > $article_filemtime)
				{
					$header_prev = '<link rel=prev href="'. $prev_next_href. '">'. $n;
					$prev_link =
					'<a class="'. $article_nav_next_href_class. '" title="'. $prev_next_encode_title. '" href="'. $prev_next_href. '">'. $n.
					'<span class="'. $article_nav_title_class. '">'. $article_prevnext[0]. '</span>'.
					'<span class="'. $article_nav_content_class. '">'. mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding). '</span>'. $n.
					'</a>'. $n.
					'<span class="'. $article_nav_xaquo_class. '">'. $nav_raquo. '</span>'. $n;
				}
				if ((int)$prev_next_parts[0] < $article_filemtime)
				{
					$header_next = '<link rel=next href="'. $prev_next_href. '">'. $n;
					$article .=
					'<span class="'. $article_nav_xaquo_class. '">'. $nav_laquo. '</span>'. $n.
					'<a class="'. $article_nav_prev_href_class. '" title="'. $prev_next_encode_title. '" href="'. $prev_next_href. '">'. $n.
					'<span class="'. $article_nav_title_class. '">'. $article_prevnext[1]. '</span>'. $n.
					'<span class="'. $article_nav_content_class. '">'. mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding). '</span>'. $n.
					'</a>'. $n;
					break;
				}
			}
			if (isset($header_prev)) $header .= $header_prev;
			if (isset($header_next)) $header .= $header_next;
			$article .= $prev_link. '</nav>'. $n;
		}
		if ($use_similars && $similar_article)
		{
			$similar_counts = count($similar_article);
			if (1 <= $similar_counts)
			{
				$aside .=
				'<div id=similars class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[1]. '">'. $n.
				'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[6]. '</div>'. $n;
				rsort($similar_article);
				foreach ($similar_article as $k => $v)
				{
					if ($number_of_similars > $k)
					{
						$similar = explode($delimiter, $v);
						$aside .= '<a class="'. $sidebox_content_class[0]. '" href="'. $url. $get_categ. r($similar[1]). '">'.
						($use_thumbnails && !is_dir($similar_images = 'contents/'. basename(filter_input(INPUT_GET, 'categ', FILTER_SANITIZE_STRING)). '/'. $similar[1]. '/images/') ? '' : get_thumbnail(glob($similar_images. '*', GLOB_NOSORT)[0])).
						h($similar[1]).
						'</a>'. $n;
					}
				}
				$aside .= '</div>';
			}
		}
	}
	if ($use_social)
		social(rawurlencode($title_name. ' - '. $site_name), rawurlencode($url. $categ_name. '/'. $title_name));
	if ($use_permalink)
		permalink($article_encode_title. ' - '. $site_name, $current_url);
}
else
	not_found();
