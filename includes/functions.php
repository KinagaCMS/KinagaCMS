<?php
$header = $nav = $article = $aside = $footer = $search = '';
$get_title = !filter_has_var(INPUT_GET, 'title') ? '' : basename(filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRIPPED));
$get_categ = !filter_has_var(INPUT_GET, 'categ') ? '' : basename(filter_input(INPUT_GET, 'categ', FILTER_SANITIZE_STRIPPED));
$get_page = !filter_has_var(INPUT_GET, 'page') ? '' : basename(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED));
$get_dl = !filter_has_var(INPUT_GET, 'dl') ? '' : basename(filter_input(INPUT_GET, 'dl', FILTER_SANITIZE_STRIPPED));
$pages = filter_input(INPUT_GET, 'pages') == 0 ? 1 : (int)filter_input(INPUT_GET, 'pages', FILTER_SANITIZE_NUMBER_INT);
$comment_pages = filter_input(INPUT_GET, 'comments') == 0 ? 1 : (int)filter_input(INPUT_GET, 'comments', FILTER_SANITIZE_NUMBER_INT);
$breadcrumb = '<li class=breadcrumb-item><a href="' . $url . '">' . $home . '</a></li>';

function get_dirs($dir, $nosort=true)
{
	if ($dirs = glob($dir . '/*' , ! $nosort ? GLOB_ONLYDIR : GLOB_ONLYDIR + GLOB_NOSORT))
	{
		foreach($dirs as $dir_names)
		{
			if (isset($dir_names))
				$all_dirs[] = basename($dir_names);
		}
		if (isset($all_dirs))
			return $all_dirs;
	}
}

function get_summary($file)
{
	global $summary_length, $encoding, $n, $ellipsis;
	error_reporting(~E_NOTICE);
	ob_start();
	include_once $file;
	$text = ob_get_clean();
	$text = strip_tags(preg_replace('/<script.*?\/script>/s', '', $text));
	$text = str_replace(array($n . $n . $n, $n . $n), $n, $text);
	$text = mb_strimwidth($text, 0, $summary_length, $ellipsis, $encoding);
	return trim($text);
}

function get_description($str)
{
	global $description_length, $encoding, $line_breaks, $ellipsis;
	$text = strip_tags(preg_replace('/<script.*?\/script>/s', '', $str));
	$text = mb_strimwidth($text, 0, $description_length, $ellipsis, $encoding);
	$text = str_replace($line_breaks, '', $text);
	return trim($text);
}

function get_categ($str)
{
	return strip_tags(basename(dirname(dirname($str))));
}

function get_title($str)
{
		return strip_tags(basename(dirname($str)));
}

function get_page($nr)
{
	global $url, $get_categ, $current_url, $word, $download_contents;
	if (filter_has_var(INPUT_GET, 'categ') && filter_has_var(INPUT_GET, 'title'))
		return $current_url . '&amp;pages=' . $nr;
	elseif (filter_has_var(INPUT_GET, 'categ') && ! filter_has_var(INPUT_GET, 'title'))
		return $url . $get_categ . '/' . $nr . '/';
	elseif (filter_has_var(INPUT_GET, 'query'))
		return $url . '?query=' . $word . '&amp;pages=' . $nr;
	elseif (filter_has_var(INPUT_GET, 'page') == $download_contents)
		return $url . r($download_contents) . '&amp;pages=' . $nr;
	else
		return $url . '?pages=' . $nr;
}

function ht($str)
{
	return h(strip_tags(basename($str)));
}

function social($t, $u)
{
	global $article, $social, $social_icon_size, $n;
	$article .=
	'<section id=social class=mb-5>' . $n .
	'<h2>' . $social . '</h2><div class="btn-group btn-group-lg">' . $n .
	'<a class="btn t" href="https://twitter.com/intent/tweet?text=' . $t . '&amp;url=' . $u . '" target="_blank" rel="noopener noreferrer">' . $n .
	'<svg class="img-fluid w-100" xmlns="http://www.w3.org/2000/svg" height="' . $social_icon_size . '" width="' . $social_icon_size . '" viewBox="0 0 10 10" fill="white"><path d="m7.5146 4.1384c0.075 1.631-1.143 3.45-3.2965 3.45-0.655 0-1.2645-0.19-1.7775-0.521 0.615 0.075 1.2295-0.1 1.717-0.48-0.5075-0.0093-0.9355-0.345-1.083-0.8055 0.18 0.0347 0.36 0.02455 0.5235-0.01975-0.558-0.11-0.9425-0.6145-0.93-1.152 0.155 0.085 0.335 0.14 0.5255 0.145-0.5165-0.345-0.663-1.027-0.36-1.548 0.572 0.7015 1.4265 1.1635 2.39 1.212-0.17-0.7255 0.38-1.424 1.1295-1.424 0.335 0 0.635 0.14 0.8465 0.365 0.265-0.05 0.5125-0.15 0.7365-0.28-0.085 0.27-0.27 0.5-0.51 0.6415 0.235-0.0279 0.46-0.09 0.666-0.185-0.155 0.235-0.35 0.435-0.5785 0.6005m-2.5136-4.1372c-2.7615 0-5 2.2385-5 5s2.2385 5 5 5 5-2.2385 5-5-2.2385-5-5-5"/></svg>' . $n .
	'</a>' . $n .
	'<a class="btn g" href="https://plus.google.com/share?url=' . $u . '" target="_blank" rel="noopener noreferrer">' . $n .
	'<svg class="img-fluid w-100" xmlns="http://www.w3.org/2000/svg" height="' . $social_icon_size . '" width="' . $social_icon_size . '" viewBox="-241.9 332.3 10 10"><path fill="white" d="m-236.9 332.3c-2.7578 0-5 2.2422-5 5s2.2422 5 5 5 5-2.2422 5-5-2.2422-5-5-5zm1.3906 5.0859c0.008 1.5312-1.0078 2.5625-2.5312 2.5625-1.4844 0-2.6953-1.1875-2.6953-2.6484s1.2109-2.6484 2.6953-2.6484c0.64843 0 1.2812 0.23438 1.7656 0.64844l-0.6875 0.77344c-0.29687-0.25782-0.6875-0.39844-1.0859-0.39844-0.91406 0-1.6484 0.72656-1.6484 1.625 0 0.89062 0.74218 1.625 1.6484 1.625 0.77344 0 1.2734-0.36719 1.4375-1.0312h-1.4219v-1.0234h2.5234v0.51563zm2.0391 0.10156v0.78125h-0.625v-0.78125h-0.78125v-0.625h0.78125v-0.78125h0.625v0.78125h0.78125v0.625h-0.78125z"/></svg>' . $n .
	'</a>' . $n .
	'<a class="btn f" href="https://www.facebook.com/sharer/sharer.php?u=' . $u . '&amp;title=' . $t . '" target="_blank" rel="noopener noreferrer">' . $n .
	'<svg class="img-fluid w-100" xmlns="http://www.w3.org/2000/svg" height="' . $social_icon_size . '" width="' . $social_icon_size . '" fill="white" viewBox="0 0 0.2 0.2"><path d="m0.1296 0.05883h-0.01398c-0.004936 0-0.005964 0.00202-0.005964 0.00713v0.012333h0.019939l-0.00192 0.02165h-0.01802v0.064672h-0.025824v-0.0644h-0.013432v-0.021927h0.013431v-0.017264c0-0.016203 0.0086666-0.024664 0.027884-0.024664h0.017881v0.02247zm-0.0296-0.05883c-0.05523 0-0.1 0.04477-0.1 0.1s0.04477 0.1 0.1 0.1 0.1-0.04477 0.1-0.1-0.04477-0.1-0.1-0.1"/></svg>' . $n .
	'</a>' . $n .
	'</div>' . $n .
	'</section>' . $n;
}

function permalink($t, $u)
{
	global $article, $permalink, $for_html, $for_wiki, $for_forum, $n;
	$article .=
	'<section id=permalink class=mb-5>' . $n .
	'<h2>' . $permalink . '</h2>' . $n .
	'<ul class="nav nav-tabs" role=tablist>' . $n .
	'<li class=nav-item><a class="nav-link active" href=#html data-toggle=tab aria-controls=html role=tab>' . $for_html . '</a></li>' . $n .
	'<li class=nav-item><a class=nav-link href=#wiki data-toggle=tab aria-controls=wiki role=tab>' . $for_wiki . '</a></li>' . $n .
	'<li class=nav-item><a class=nav-link href=#forum data-toggle=tab aria-controls=forum role=tab>' . $for_forum . '</a></li>' . $n .
	'</ul>' . $n .
	'<div class=tab-content>' . $n .
	'<div class="tab-pane active" id=html>' . $n .
	'<textarea readonly onclick="this.select()" class="form-control bg-white border-0 rounded-0" rows=3 tabindex=10 accesskey=h>' . h('<a href="' . $u . '" target="_blank">' . $t . '</a>') . '</textarea>' . $n .
	'</div>' . $n .
	'<div class=tab-pane id=wiki>' . $n .
	'<textarea readonly onclick="this.select()" class="form-control bg-white border-0 rounded-0" rows=3 tabindex=11 accesskey=w>' . h('[' . $u . ' ' . $t . ']') . '</textarea>' . $n .
	'</div>' . $n .
	'<div class=tab-pane id=forum>' . $n .
	'<textarea readonly onclick="this.select()" class="form-control bg-white border-0 rounded-0" rows=3 tabindex=12 accesskey=f>' . h('[URL=' . $u . ']' . $t . '[/URL]') . '</textarea>' . $n .
	'</div>' . $n .
	'</div>' . $n .
	'</section>' . $n;
}

function a($uri, $name='', $target='_blank', $class='', $title='', $position='')
{
	return
	'<a href="' . $uri . '" target="' . $target . '"' .
	($class ? ' class="' . $class . '"' : '') .
	($title ? ' data-toggle="tooltip" title="' . $title . '" data-html="true"' : '') .
	($position ? ' data-placement="' . $position . '"' : '') .
	($target === '_blank' ? ' rel="noopener noreferrer"' : '') . '>' .
	(!$name ? h($uri) : h($name)) .
	'</a>';
}

function img($src, $class='', $comment=true, $thumbnail=true)
{
	global $url, $source, $n, $get_title, $get_page, $use_thumbnails, $line_breaks;
	$info = pathinfo($src);

	if (isset($info['extension']))
	{
		if ($scheme = strpos($src, '://'))
			$addr = parse_url($src);
		$extension = strtolower($info['extension']);
		if (array_search($extension, array('gif', 'jpg', 'jpeg', 'png', 'svg')) !== false)
		{
			$exif = @exif_read_data($src, '', '', true);
			$exif_thumbnail = isset($exif['THUMBNAIL']['THUMBNAIL']) ? $exif['THUMBNAIL']['THUMBNAIL'] : '';
			$exif_comment = isset($exif['COMMENT']) && $comment ? str_replace($line_breaks, '&#10;', h(trim(strip_tags($exif['COMMENT'][0])))) : '';

			if ($get_title || $get_page)
			{
				if ($use_thumbnails && $exif_thumbnail && $thumbnail)
					$img = '<img class="align-top ' . $class . ' img-thumbnail" src="data:' . image_type_to_mime_type(exif_imagetype($src)) . ';base64,' . base64_encode($exif_thumbnail) . '" alt="' . h(basename($src)) . '">';
				else
					$img = $exif_comment ?
						'<figure class="align-top img-thumbnail text-center d-inline-block mb-5"><img class="img-fluid ' . $class . '" src="' . $url . r($src) . '" alt="' . h(basename($src)) . '"><p class="text-center wrap my-2">' . $exif_comment . '</p></figure>' :
						'<img class="img-fluid img-thumbnail ' . $class . '" src="' . $url . r($src) . '" alt="' . h(basename($src)) . '">';
				if ($scheme !== false)
					return '<figure class="img-thumbnail text-center d-inline-block ' . $class . '"><a class=expand href="' . $src . '" target="_blank" onclick="return false" title="' . $exif_comment . '"><img class="img-fluid" src="' . $addr['scheme'] . '://' . $addr['host'] . r($addr['path']) . '" alt="' . h(basename($src)) . '"></a><small class="blockquote-footer my-2 text-right"><a href="' . $addr['scheme'] . '://' . $addr['host'] . '/" target="_blank" rel="noopener noreferrer">' . sprintf($source, h($addr['host'])) . '</a></small></figure>';
				else
				{
					$expand = strpos($class, 'expand') !== false ? ' class=expand' : '';
					return $exif_comment ?
						'<a href="' . $url . r($src) . '" target="_blank" onclick="return false" title="' . $exif_comment . '"' . $expand . '>' . $img . '</a>' :
						'<a href="' . $url . r($src) . '" target="_blank" onclick="return false"' . $expand . '>' . $img . '</a>';
				}
			}
			else
			{
				$dirname = dirname(dirname($src));
				$img_size = @getimagesize($src);
				return '<a href="' . $url . r(basename(dirname($dirname)) . '/' . basename($dirname)) . '"><img class="d-block mx-auto img-fluid ' . $class . '" src="' . $url . r($src) . '" alt="' . h(basename($src)) . '"' . (isset($img_size[0]) && $img_size[0] < 450 ? ' style="width:' . $img_size[0] . 'px"' : '') . '></a>';
			}
		}
		elseif (array_search($extension, array('mp4', 'ogg', 'webm')) !== false)
		{
			$vtt = str_replace($extension, 'vtt', $src);
			if ($get_title || $get_page)
			{
				if ($scheme !== false)
					return '<figure class="align-top img-thumbnail text-center d-inline-block ' . $class . '"><video controls preload=none><source src="' . $addr['scheme'] . '://' . $addr['host'] . r($addr['path']) . '"><track src="' . str_replace($extension, 'vtt', $addr['scheme'] . '://' . $addr['host'] . r($addr['path'])) . '"></video><small class="blockquote-footer my-2 text-right"><a href="' . $addr['scheme'] . '://' . $addr['host'] . '/" target="_blank" rel="noopener noreferrer">' . sprintf($source, h($addr['host'])) . '</a></small></figure>' . $n;
				else
					return '<a href="' . $url . r($src) . '" class="sr-only mfp-iframe">video-iframe</a><video class="align-top img-thumbnail ' . $class . '" controls preload=none><source src="' . $url . r($src) . '"><track src="' . $url . r($vtt) . '"></video>';
			}
			else
				return '<video class="align-top ' . $class . '" controls preload=none><source src="' . $url . r($src) . '"><track src="' . $url. r($vtt) . '"></video>' . $n;
		}
	}
}

function timeformat($time)
{
	global $now, $seconds_ago, $minutes_ago, $hours_ago, $days_ago, $present_format, $time_format;
	$diff = $now - $time;
	if ($diff < 60)
		return (int)$diff . $seconds_ago;
	elseif ($diff < 3600)
		return (int)($diff / 60) . $minutes_ago;
	elseif ($diff < 86400)
		return (int)($diff / 3600) . $hours_ago;
	elseif ($diff < 2764800)
		return (int)($diff / 86400) . $days_ago;
	elseif (date('Y') !== date('Y', $time))
		return date($time_format, $time);
	else
		return date($present_format, $time);
}

function pager($num, $max)
{
	global $number_of_pager, $article, $nav_laquo, $nav_raquo, $n;
	$article .=
	'<ul class="justify-content-center pagination my-4">' . $n;

	if($num > 2)
		$article .= '<li class=page-item><a class=page-link href="' . get_page(1) . '">' . $nav_laquo . $nav_laquo . '</a></li>';
	if ($num > 1)
		$article .= '<li class=page-item><a class=page-link href="' . get_page((int)$num-1) . '">' . $nav_laquo . '</a></li>' . $n;

	$i = 1;
	while ($i <= $number_of_pager)
	{
		if ($num - ceil($number_of_pager/2) < 0)
		{
			if ($i == $num)
				$article .= '<li class="active page-item"><a class=page-link>' . (int)$num . '</a></li>' . $n;
			else
				$article .= '<li class=page-item><a class=page-link href="' . get_page($i) . '">' . $i . '</a></li>' . $n;
		}
		elseif ($num + floor($number_of_pager/2) > $max)
		{
			if ($max > $number_of_pager)
				$j = (int)$max - $number_of_pager + $i;
			else
				$j = $i;

			if ($j == $num)
				$article .= '<li class="active page-item"><a class=page-link>' . (int)$num . '</a></li>' . $n;
			else
				$article .= '<li class=page-item><a class=page-link href="' . get_page($j) . '">' . $j . '</a></li>' . $n;
		}
		else
		{
			if ($i == ceil($number_of_pager/2))
				$article .= '<li class="disable active page-item"><a class=page-link>' . (int)$num . '</a></li>' . $n;
			else
			{
				$j = $num - ceil($number_of_pager/2) + $i;
				$article .= '<li class=page-item><a class=page-link href="' . get_page($j) . '">' . $j . '</a></li>' . $n;
			}
		}
		if ($i == $max)
			break;
		++$i;
	}
	if ($num < $max)
		$article .= '<li class=page-item><a class=page-link href="' . get_page((int)$num+1) . '">' . $nav_raquo . '</a></li>' . $n;
	if ($num < $max - 1)
		$article .= '<li class=page-item><a class=page-link href="' . get_page((int)$max) . '">' . $nav_raquo . $nav_raquo . '</a></li>' . $n;
	$article .=
	'</ul>' . $n;
}

function sideless()
{
	global $header, $get_title, $get_page;
	if ($get_title || $get_page)
		$header .= '<style>.col-lg-9{max-width:100%;flex:0 0 100%}.col-lg-3{max-width:100%;flex:0 0 100%}</style>';
}

function nowrap()
{
	global $header, $get_title, $get_page;
	if ($get_title || $get_page)
		$header .= '<style>.article{white-space:normal}</style>';
}

function redirect($link)
{
	global $get_title, $get_page;
	if ($get_title || $get_page)
	{
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $link);
		exit;
	}
}

function get_logo()
{
	global $site_name, $url;
	if (is_file($logo = 'images/logo.png'))
		return '<img src="' . $url . $logo . '" alt="' . $site_name . '">';
	else
		return $site_name;
}

function not_found()
{
	global $header, $article, $error, $site_name, $not_found, $n;
	http_response_code(404);
	$header .= '<title>' . $error . ' - ' . $site_name . '</title>' . $n;
	$article .=
	'<h1 class="h2 mb-4">' . $error . '</h1>' . $n .
	'<div class=article>' . $not_found . '</div>' . $n;
}

$contents = get_dirs('contents', false);
$dl = is_dir($downloads_dir = 'downloads') ? true : false;
$form = 'includes/form.php';

if (! empty($contents))
{
	foreach($contents as $categ)
		$nav .= '<a ' . (filter_has_var(INPUT_GET, 'categ') && $get_categ == $categ ? 'class="nav-item nav-link active"' : 'class="nav-item nav-link"') . ' href="' . $url . r($categ) . '/">' . h($categ) . '</a>' . $n;
}
if (filter_has_var(INPUT_GET, 'page') && ! is_numeric($get_page))
{
	if (is_file($pages_file = 'contents/' . $get_page . '.html'))
	{
		$basetitle = h($get_page);
		$header .=
		'<title>' . $basetitle . ' - ' . $site_name . '</title>' . $n;
		$breadcrumb .=
		'<li class="breadcrumb-item active">' . $basetitle . '</li>';
		$article .=
		'<small class=text-muted>' . sprintf($last_modified, date($time_format, filemtime($pages_file))) . '</small>' . $n .
		'<h1 class="h2 mb-4">' . $basetitle . '</h1>';

		ob_start();
		include_once $pages_file;
		$pages_content = trim(ob_get_clean());
		$header .= '<meta name=description content="' . get_description($pages_content) . '">' . $n;
		$article .= '<div class="article mb-5">' . $pages_content . '</div>' . $n;

		if ($use_social)
			social(rawurlencode($basetitle . ' - ' . $site_name), rawurlencode($url . $basetitle));

		if ($use_permalink)
			permalink($basetitle . ' - ' . $site_name, $url . rawurlencode($basetitle));
	}
	elseif ($use_contact && $get_page === $contact_us)
	{
		$header .= '<title>' . $contact_us . ' - ' . $site_name . '</title>' . $n;
		$breadcrumb .= '<li class="breadcrumb-item active">' . $contact_us . '</li>';
		if ($contact_subtitle)
			$article .= '<h1 class="h2 mb-4">' . $contact_us . ' <small class="wrap text-muted">' . $contact_subtitle . '</small></h1>' . $n;
		ob_start();
		include_once $form;
		$article .= trim(ob_get_clean());
	}
	elseif ($dl && $get_page === $download_contents)
	{
		if (is_file($dl_file = $downloads_dir . '/' . $get_dl) && pathinfo($dl_file, PATHINFO_EXTENSION))
		{
			header('Content-Length: ' . filesize($dl_file));
			header('Content-Type: ' . mime_content_type($dl_file));

			if (strpos($user_agent_lang, 'ja') !== false && strpos($user_agent, 'MSIE') !== false && strpos($user_agent, 'rv:11.0') !== false)
			{
				header('X-Download-Options: noopen');
				header('Content-Disposition: attachment; filename="' . mb_convert_encoding($get_dl, $encoding_win, $encoding) . '"');
			}
			else
				header('Content-Disposition: attachment; filename="' . $get_dl . '"');

			readfile($dl_file);
			exit;
		}
		$breadcrumb .= '<li class="breadcrumb-item active">' . $download_contents . '</li>';
		$header .= '<title>' . $download_contents . ' - ' . $site_name . '</title>' . $n;

		if ($download_subtitle)
			$article .= '<h1 class="h2 mb-4">' . $download_contents . ' <small class="wrap text-muted">' . $download_subtitle . '</small></h1>' . $n;

		$dl_files = glob($downloads_dir . '/*.*', GLOB_NOSORT);

		if ($dl_files)
		{
			for($i = 0, $c = count($dl_files); $i < $c; ++$i)
				$dls_sort[] = ($di_filesize = filesize($dl_files[$i])) > 0 ? filemtime($dl_files[$i]) . '-~-' . $dl_files[$i] . '-~-' . size_unit($di_filesize) : '';

			$dls_sort = array_filter($dls_sort);
			rsort($dls_sort);
			$dls_number = count($dls_sort);
			$page_ceil = ceil($dls_number / $number_of_downloads);
			$max_pages = min($pages, $page_ceil);
			$dls_in_page = array_slice($dls_sort, ($max_pages - 1) * $number_of_downloads, $number_of_downloads);

			if ($dls_number > $number_of_downloads)
			{
				$footer .= '<script>document.title="' . $download_contents . ' - ' . sprintf($page_prefix, $max_pages) . ' - ' . $site_name . '"</script>';
				pager($max_pages, $page_ceil);
			}
			$article .= '<div class="list-group list-group-flush">';

			for($i = 0, $c = count($dls_in_page); $i < $c; ++$i)
			{
				$dl_uri = explode('-~-', $dls_in_page[$i]);
				$article .=
				'<a class=list-group-item href="' . $url . r($download_contents) . '&amp;dl=' . rawurlencode(strip_tags(basename($dl_uri[1]))) . '" target="_blank">' . $n .
				'<span class=mr-3>' . date($time_format, $dl_uri[0]) . '</span>' . $n .
				'<span class=mr-3>' . ht($dl_uri[1]) . '</span>' . $n .
				'<span class=mr-3>' . $dl_uri[2] . '</span>' . $n .
				'</a>' . $n;
			}
			$article .= '</div>';

			if ($dls_number > $number_of_downloads)
				pager($max_pages, $page_ceil);
		}
	}
	else
		not_found();
}
elseif (filter_has_var(INPUT_GET, 'categ') && ! filter_has_var(INPUT_GET, 'title'))
{
	if (is_dir($current_categ = 'contents/' . $get_categ))
	{
		$categ_title = h($get_categ);
		$breadcrumb .= '<li class="breadcrumb-item active">' . $categ_title . '</li>';
		$categ_contents = get_dirs($current_categ);
		$categ_contents_number = $categ_contents ? count($categ_contents) : 0;

		if (is_file($categ_file = $current_categ . '/index.html'))
		{
			ob_start();
			include_once $categ_file;
			$categ_content = trim(ob_get_clean());
			$article .= '<h1 class="h2 mb-4">' . $categ_title . ' <small class="wrap text-muted">' . $categ_content . '</small></h1>';
			$header .= '<meta name=description content="' . get_description($categ_content) . '">' . $n;
		}
		if ($categ_contents_number > 0)
		{
			for($i = 0; $i < $categ_contents_number; ++$i)
				$articles_sort[] = is_file($article_files = $current_categ . '/' . $categ_contents[$i] . '/index.html') ? filemtime($article_files) . '-~-' . $article_files : '';

			$articles_sort = array_filter($articles_sort);
			rsort($articles_sort);
			$page_ceil = ceil($categ_contents_number / $number_of_categ_sections);
			$max_pages = min($pages, $page_ceil);
			$sections_in_categ_page = array_slice($articles_sort, ($max_pages - 1) * $number_of_categ_sections, $number_of_categ_sections);

			if (filter_has_var(INPUT_GET, 'pages') && is_numeric($max_pages))
				$header .= '<title>' . $categ_title . ' - ' . sprintf($page_prefix, $max_pages) . ' - ' . $site_name . '</title>' . $n;
			else
				$header .= '<title>' . $categ_title . ' - ' . $site_name . '</title>' . $n;

			if ($categ_contents_number > $number_of_categ_sections)
				pager($max_pages, $page_ceil);

			$article .= '<div class=card-columns>';
			for($i = 0, $c = count($sections_in_categ_page); $i < $c; ++$i)
			{
				$articles = explode('-~-', $sections_in_categ_page[$i]);
				$articles_link = explode('/', $articles[1]);
				$categ_link = r($articles_link[1]);
				$title_link = r($articles_link[2]);
				$article_dir = dirname($articles[1]);
				$count_images = '';
				$counter = is_file($counter_txt = $article_dir . '/counter.txt') ?
				'<span class=card-link>' . sprintf($display_counts, (int)trim(strip_tags(file_get_contents($counter_txt)))) . '</span>' : '';
				$comments = $use_comment && is_dir($comments_dir = $article_dir . '/comments') ?
				'<a class=card-link href="' . $url . $categ_link . '/' . $title_link . '#comment">' . $n .
				sprintf($comment_counts, count(glob($comments_dir . '/*-~-*.txt', GLOB_NOSORT))) .
				'</a>' : '';

				if (is_dir($default_imgs_dir = $article_dir . '/images') && $glob_default_imgs = glob($default_imgs_dir . $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
				{
					sort($glob_default_imgs);
					$default_image = img($glob_default_imgs[0], 'card-img-top', false, false);
					$count_images = count($glob_default_imgs);
				}
				else
					$default_image = $count_images = '';

				if (is_dir($default_background_dir = $article_dir . '/background-images') && $glob_default_background_imgs = glob($default_background_dir . '/*', GLOB_NOSORT))
				{
					sort($glob_default_background_imgs);
					$default_background_image = img($glob_default_background_imgs[0], 'card-img-top', false, false);
					$count_background_images = count($glob_default_background_imgs);
				}
				else
					$default_background_image = $count_background_images = '';

				if (is_dir($default_popup_dir = $article_dir . '/popup-images') && $glob_default_popup_imgs = glob($default_popup_dir . '/*', GLOB_NOSORT))
					$count_popup_images = count($glob_default_popup_imgs);
				else
					$count_popup_images = 0;

				$total_images = (int)$count_images + (int)$count_background_images + (int)$count_popup_images;
				$article .=
				'<div class=card>' . $n .
				$default_image . $default_background_image .
				'<div class=card-body>' . $n .
				'<time class="small card-subtitle mb-2 text-muted">' . timeformat($articles[0]) . '</time>' .
				'<h2 class="h4 card-title"><a href="' . $url . $categ_link . '/' . $title_link . '">' . ht($articles_link[2]);

				if ($total_images > 0)
					$article .= '<small>' . sprintf($images_count_title, $total_images) . '</small>';

				$article .=
				'</a></h2>' . $n;
				if ($use_summary)
					$article .=
					'<p class=wrap>' . get_summary($articles[1]) . '</p>' . $n;
				$article .= '</div>' . $n;
				if ($counter || $comments)
					$article .=
					'<div class="card-footer bg-transparent">' . $n .
					$counter . $comments .
					'</div>' . $n;
				$article .= '</div>' . $n;

			}
			$article .= '</div>';

			if ($categ_contents_number > $number_of_categ_sections)
				pager($max_pages, $page_ceil);
		}
		elseif (!$categ_file)
			not_found();
		else
			$header .= '<title>' . $categ_title . ' - ' . $site_name . '</title>' . $n;
	}
	else
		not_found();
}
elseif (filter_has_var(INPUT_GET, 'categ') && filter_has_var(INPUT_GET, 'title'))
{
	$breadcrumb .=
	'<li class=breadcrumb-item><a href="' . $url . r($get_categ) . '/">' . h($get_categ) . '</a></li>' . $n .
	'<li class="breadcrumb-item active">' . h($get_title) . '</li>';

	if (is_dir($current_article_dir = 'contents/' . $get_categ . '/' . $get_title) && is_file($current_article = $current_article_dir . '/index.html'))
	{
		if (is_dir($background_images_dir = $current_article_dir . '/background-images') && $glob_background_images = glob($background_images_dir . '/*', GLOB_NOSORT))
		{
			$header .= '<style>';

			foreach($glob_background_images as $background_images)
			{
				if (list($width, $height) = @getimagesize($background_images))
				{
					$info = pathinfo($background_images);
					$exif = @exif_read_data($background_images, '', '', true);
					$classname = '.' . basename($background_images, '.' . $info['extension']);
					$aspect = round($height / $width * 100, 1);
					$header .= '@media(max-width:' . ($width * 1.5) . 'px){' . $classname . '{' . ($height > 400 ? 'height:0px!important;padding-bottom:' . $aspect . '%' : 'height:' . $height . 'px') . '}}' . $classname . '{max-width:' . $width . 'px;background-image:url(' . $url . r($background_images) . ');background-size:100%;background-repeat:no-repeat;' . ($height > 1000 ? 'height:0px!important;padding-bottom:' . $aspect . '%' : 'height:' . $height . 'px') . '}' . (isset($exif['COMMENT']) ? $classname . ':after{background-color:rgba(0,0,0,.3);color:white;content:"' . str_replace($line_breaks, '\00a', h(trim(strip_tags($exif['COMMENT'][0])))) . '";display:block;line-height:1.1;padding:.7% 1%;word-wrap:break-word;white-space:pre-wrap}' : '');
				}
			}
			$header .= '</style>' . $n;
		}
		if (is_dir($popup_images_dir = $current_article_dir . '/popup-images') && $glob_popup_images = glob($popup_images_dir . '/*', GLOB_NOSORT))
		{
			$header .= '<style>.tooltip-inner{max-width:inherit}</style>';
			$footer .= '<script>';

			foreach($glob_popup_images as $popup_images)
			{
				if (list($width, $height) = @getimagesize($popup_images))
				{
					$info = pathinfo($popup_images);
					$classname = basename($popup_images, '.' . $info['extension']);
					$footer .= '$("#' . $classname . '").attr("data-html", true).attr("title", "<img src=\"' . $url . r($popup_images) . '\" style=\"max-width:600px\">").tooltip();';
				}
			}
			$footer .= '</script>' . $n;
		}
		$article_encode_title = h($get_title);
		$header .= '<title>' . $article_encode_title . ' - ' . $site_name . '</title>' . $n;
		$article_filemtime = filemtime($current_article);
		$current_url = $url . r($get_categ) . '/' . r($get_title);
		$article .=
		'<small class=text-muted>' . sprintf($last_modified, date($time_format, $article_filemtime)) . '</small>';

		if (is_file($counter_txt = $current_article_dir . '/counter.txt') && is_writeable($counter_txt))
		{
			if (flock($fr = fopen($counter_txt, 'r+'), LOCK_EX | LOCK_NB))
			{
				$view_count = fgetss($fr) +1;
				$article .= '<small class="ml-2 text-muted">' . sprintf($view, (int)$view_count) . '</small>';
				rewind($fr);
				fwrite($fr, $view_count);
				flock($fr, LOCK_UN);
			}
			fclose($fr);
		}
		$article .=
		'<h1 class="h2 mb-4">' . $article_encode_title;

		if ($use_comment && is_dir($comment_dir = $current_article_dir . '/comments') && $glob_comment_files = glob($comment_dir . '/*-~-*.txt', GLOB_NOSORT))
		{
			$count_comments = count($glob_comment_files);
			$article .= '<small><a href=#comment>' . sprintf($comments_count_title, $count_comments) . '</a></small>';
		}
		$article .=
		'</h1>';

		ob_start();
		include_once $current_article;
		$current_article_content = trim(ob_get_clean());
		$header .=
		'<meta name=description content="' . get_description($current_article_content) . '">' . $n;
		$article .=
		'<div class="mb-2 article clearfix">' . $current_article_content . '</div>' . $n;

		if (is_dir($images_dir = $current_article_dir . '/images') && $glob_image_files = glob($images_dir . $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
		{
			sort($glob_image_files);
			$glob_images_number = count($glob_image_files);
			$page_ceil = ceil($glob_images_number / $number_of_images);
			$max_pages = min($pages, $page_ceil);
			$images_in_page = array_slice($glob_image_files, ($max_pages - 1) * $number_of_images, $number_of_images);

			if ($glob_images_number > $number_of_images)
			{
				$footer .= '<script>document.title="' . $article_encode_title . ' - ' . sprintf($page_prefix, $max_pages) . ' - ' . $site_name . '"</script>';
				pager($max_pages, $page_ceil);
			}
			$article .= '<div class="gallery text-center">' . $n;
			for($i = 0, $c = count($images_in_page); $i < $c; ++$i)
				$article .= img($images_in_page[$i]);
			$article .= '</div>' . $n;

			if ($glob_images_number > $number_of_images)
				pager($max_pages, $page_ceil);
		}

		if ($glob_prev_next = glob('contents/' . $get_categ . '/*/index.html', GLOB_NOSORT))
		{
			$similar_article = [];
			foreach($glob_prev_next as $prev_next)
			{
				$similar_titles = get_title($prev_next);
				similar_text($get_title, $similar_titles, $percent);
				$per = round($percent);

				if ($per < 100 && $per >= 20)
					$similar_article[] = $per . '-~-' . $similar_titles;

				$sort_prev_next[] = filemtime($prev_next) . '-~-' . $prev_next;
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
					$prev_href = $url . r($get_categ) . '/' . r($prev_next_title);
					$prev_next_encode_title = h($prev_next_title);
					$header_prev =
					'<link rel=prev href="' . $prev_href . '">' . $n;
					$prev_link =
					'<a class="btn btn-outline-primary" title="' . $prev_next_encode_title . '" href="' . $prev_href . '">' . mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding) . '</a>' . $n;
				}
				if ((int)$prev_next_parts[0] < $article_filemtime)
				{
					$next_href = $url . r($get_categ) . '/' . r($prev_next_title);
					$prev_next_encode_title = h($prev_next_title);
					$header_next =
					'<link rel=next href="' . $next_href . '">' . $n;
					$article .=
					'<a class="float-right btn btn-outline-primary" title="' . $prev_next_encode_title . '" href="' . $next_href . '">' . mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding) . '</a>' . $n;
					break;
				}
			}
			if (isset($header_prev))
				$header .= $header_prev;
			if (isset($header_next))
				$header .= $header_next;
			$article .= $prev_link . '</div>';

			if ($use_similars && $similar_article)
			{

				$similar_counts = count($similar_article);

				if ($similar_counts >= 1)
				{
					$article .= '<section class=mb-5>';
					$article .= '<h2>' . $similar_title . '</h2>';
					rsort($similar_article);
					for($i = 0; $i < $similar_counts && $i < $number_of_similars; ++$i)
					{
						$similar = explode('-~-', $similar_article[$i]);
						$article .=
						'<div class="progress similar-article mb-2">' . $n .
						'<a class="progress-bar progress-bar-striped bg-primary" style="width:' . $similar[0] . '%" href="' . $url . r($get_categ) . '/' . r($similar[1]) . '">' . h($similar[1]) . ' - ' . $similar[0] . '%</a>' . $n .
						'</div>';
					}
					$article .= '</section>';
				}
			}
		}
		if ($use_social)
			social(rawurlencode($get_title . ' - ' . $site_name), rawurlencode($url . $get_categ . '/' . $get_title));

		if ($use_permalink)
			permalink($article_encode_title . ' - ' . $site_name, $current_url);

		if ($use_comment && is_dir($comment_dir))
		{
			$article .=
			'<section class=mb-5 id=comment><h2>' . $comment_title . '</h2>' . $n;

			if (isset($glob_comment_files) && $number_of_comments > 0)
			{
				rsort($glob_comment_files);
				if (! filter_has_var(INPUT_GET, 'comments') && ! is_numeric($comment_pages))
					$comment_pages = 1;

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
						'<div class="col-md-6 mb-3">' . $n .
						'<div class="card comment" id=cid-' . $comment_time . '>' . $n .
						'<div class="card-body wrap">' . $comment_content . '</div>' . $n .
						'<div class=card-footer><span class=mr-3>' . basename($comment_file[1], '.txt') . '</span>' . timeformat($comment_time) . '</div>' . $n .
						'</div>' . $n .
						'</div>' . $n;
					}
				}
				if (isset($comments_array))
				{
					$article .=
					'<div class=row>' . $n;
					$comments_in_page = array_slice($comments_array, ($comment_pages - 1) * $number_of_comments, $number_of_comments);

					for($i = 0, $c = count($comments_in_page); $i < $c; ++$i)
						$article .= $comments_in_page[$i];

					$article .=
					'</div>' . $n;

					if ($count_comments > $number_of_comments)
					{
						$article .=
						'<nav>' . $n .
						'<ul class=pager>' . $n;

						if ($comment_pages > 1)
							$article .=
							'<li class=previous>' . $n .
							'<a href="' . $current_url . '&amp;comments=' . ($comment_pages - 1) . '#comment">' . $comments_prev . '</a>' . $n .
							'</li>' . $n;

						if ($comment_pages < ceil($count_comments / $number_of_comments))
							$article .=
							'<li class=next>' . $n .
							'<a href="' . $current_url . '&amp;comments=' . ($comment_pages + 1) . '#comment">' . $comments_next . '</a>' . $n .
							'</li>' . $n;

						$article .=
						'</ul>' . $n .
						'</nav>' . $n;
					}
				}
			}
			if (is_file($comment_dir . '/end.txt'))
				$article .= '<strong class=mb-5>' . $comments_not_allow . '</strong>' . $n;
			else
			{
				ob_start();
				include_once $form;
				$article .= trim(ob_get_clean());
			}
			$article .= '</section>';
		}
	}
	else
		not_found();
}
elseif (! filter_has_var(INPUT_GET, 'categ') && ! filter_has_var(INPUT_GET, 'title'))
{
	if ($use_search && filter_has_var(INPUT_GET, 'query'))
	{
		$no_results = '';
		$word = trim(mb_convert_kana(filter_input(INPUT_GET, 'query', FILTER_SANITIZE_SPECIAL_CHARS), 'rnsK', 'UTF-8'));
		$result_title = sprintf($result, $word);
		$breadcrumb .= '<li class="breadcrumb-item active">' . $result_title . '</li>';
		$header .= '<title>' . $result_title . ' - ' . $site_name . '</title>' . $n;
		$article .= '<h1 class="h2 mb-4">' . $result_title . '</h1>' . $n;
		$outputs = [];
		$glob_search = glob('{' . $glob_dir . 'index.html,contents/*.html}', GLOB_BRACE + GLOB_NOSORT);

		if ($glob_search && $word)
		{
			foreach($glob_search as $search_files)
				$sort_search[] = $search_files;

			sort($sort_search);

			foreach($sort_search as $filename)
			{
				$temp = h(file_get_contents($filename));
				$file_title = get_title($filename);
				$temp.= '<p class="blockquote-footer small">';
				$temp.= $file_title === 'contents' ? trim(strip_tags(basename($filename, '.html'))) : get_categ($filename) . ' ' . $file_title;
				$temp.= '</p>';
				$first_pos = mb_stripos($temp, $word);

				if ($first_pos !== false)
				{
					$start = max(0, $first_pos - 150);
					$length = $summary_length + mb_strlen($word, $encoding);
					$str = mb_substr($temp, $start, $length, $encoding);
					$str = $str === null ? mb_strimwidth($temp, 0, $summary_length, $ellipsis, $encoding) : mb_strimwidth($str, 0, $summary_length, $ellipsis, $encoding);
					$str = str_replace($word, '<span class=highlight>' . $word . '</span>', $str);
					$outputs[] = array(filemtime($filename), $filename, $str);
				}
			}
			if ($outputs)
			{
				rsort($outputs);
				$results_number = count($outputs);
				$page_ceil = ceil($results_number / $number_of_results);
				$max_pages = min($pages, $page_ceil);
				$results_in_page = array_slice($outputs, ($max_pages - 1) * $number_of_results, $number_of_results);

				if ($results_number > $number_of_results)
				{
					$footer .= '<script>document.title="' . $result_title . ' - ' . sprintf($page_prefix, $max_pages) . ' - ' . $site_name . '"</script>';
					pager($max_pages, $page_ceil);
				}
				for($i = 0, $c = count($results_in_page); $i < $c; ++$i)
				{
					$output = $results_in_page[$i];
					$title = get_title($output[1]);

					$article .= '<section class=mb-5>' . $n;
					if ($title === 'contents')
					{
						$pagename = basename($output[1], '.html');

						if ($pagename === 'index')
							$article .=
							'<h2><a href="' . $url . '">' . $home . '</a></h2>' . $n .
							'<div class=wrap>' . $output[2] . '</div>' . $n;
						else
							$article .=
							'<h2><a href="' . $url . r($pagename) . '">' . h($pagename) . '</a></h2>' . $n .
							'<div class=wrap>' . $output[2] . '</div>' . $n;
					}
					else
						$article .=
						'<h2><a href="' . $url . r(get_categ($output[1])) . '/' . r($title) . '">' . h($title) . '</a></h2>' . $n .
						'<div class=wrap>' . $output[2] . '</div>' . $n;
					$article .= '</section>' . $n;

				}
				if ($results_number > $number_of_results)
					pager($max_pages, $page_ceil);
			}
			else
				$no_results = true;
		}
		else
			$no_results = true;

		if ($no_results)
			$article .= '<h2>' . $no_results_found . '</h2>' . $n;
	}
	elseif (is_file($default_file = 'contents/index.html'))
	{
		$header .=
		'<title>' . $site_name . ($subtitle ? ' - ' . $subtitle : '') . '</title>' . $n .
		'<meta name=description content="' . $meta_description . '">' . $n;
		if ($subtitle)
			$article .= '<h1 class="h2 mb-4">' . $site_name . ' <small class="wrap text-muted">' . $subtitle . '</small></h1>' . $n;
		$article .= '<div class=article>';
		ob_start();
		include_once $default_file;
		$article .= trim(ob_get_clean());
		$article .= '</div>' . $n;
	}
	else
	{
		$header .=
		'<title>' . $site_name . ($subtitle ? ' - ' . $subtitle : '') . '</title>' . $n;
		if ($subtitle)
			$article .= '<h1 class="h2 mb-4">' . $site_name . ' <small class="wrap text-muted">' . $subtitle . '</small></h1>' . $n;
		$header .= '<meta name=description content="' . $meta_description . '">' . $n;

		if ($glob_files = glob($glob_dir . 'index.html', GLOB_NOSORT))
		{
			foreach($glob_files as $all_files)
				$all_sort[] = filemtime($all_files) . '-~-' . $all_files;

			$all_sort = array_filter($all_sort);
			rsort($all_sort);
			$default_contents_number = count($all_sort);
			$page_ceil = ceil($default_contents_number / $number_of_default_sections);
			$max_pages = min($pages, $page_ceil);
			$sections_in_default_page = array_slice($all_sort, ($max_pages - 1) * $number_of_default_sections, $number_of_default_sections);

			if ($default_contents_number > $number_of_default_sections)
			{
				$footer .= '<script>document.title="' . $site_name . ' - ' . sprintf($page_prefix, $max_pages) . '"</script>';
				pager($max_pages, $page_ceil);
			}
			$article .= '<div class=card-columns>';
			for($i = 0, $c = count($sections_in_default_page); $i < $c; ++$i)
			{
				$all_articles = explode('-~-', $sections_in_default_page[$i]);
				$all_link = explode('/', $all_articles[1]);
				$categ_link = r($all_link[1]);
				$title_link = r($all_link[2]);
				$article_dir = dirname($all_articles[1]);
				$counter = is_file($counter_txt = $article_dir . '/counter.txt') ?
				'<span class=card-link>' . sprintf($display_counts, (int)trim(strip_tags(file_get_contents($counter_txt)))) . '</span>' : '';
				$comments = is_dir($comments_dir = $article_dir . '/comments') && $use_comment ?
				'<a class=card-link href="' . $url . $categ_link . '/' . $title_link . '#comment"> ' . sprintf($comment_counts, count(glob($comments_dir . '/*-~-*.txt'), GLOB_NOSORT)) . '</a>' : '';

				if (is_dir($default_imgs_dir = $article_dir . '/images') && $glob_default_imgs = glob($default_imgs_dir . $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
				{
					sort($glob_default_imgs);
					$default_image = img($glob_default_imgs[0], 'card-img-top', false, false);
					$count_images = count($glob_default_imgs);
				}
				else
					$default_image = $count_images = '';

				if (is_dir($default_background_dir = $article_dir . '/background-images') && $glob_default_background_imgs = glob($default_background_dir . '/*', GLOB_NOSORT))
				{
					sort($glob_default_background_imgs);
					$default_background_image = img($glob_default_background_imgs[0], 'card-img-top', false, false);
					$count_background_images = count($glob_default_background_imgs);
				}
				else
					$default_background_image = $count_background_images = '';

				if (is_dir($default_popup_dir = $article_dir . '/popup-images') && $glob_default_popup_imgs = glob($default_popup_dir . '/*', GLOB_NOSORT))
					$count_popup_images = count($glob_default_popup_imgs);
				else
					$count_popup_images = 0;

				$total_images = (int)$count_images + (int)$count_background_images + (int)$count_popup_images;

				$article .=
				'<div class=card>' . $n .
				$default_image . $default_background_image .
				'<div class=card-body>' . $n .
				'<time class="small card-subtitle mb-2 text-muted">' . timeformat($all_articles[0]) . '</time>' . $n .
				'<h2 class="h4 card-title"><a href="' . $url . $categ_link. '/' . $title_link . '">' . ht($all_link[2]);

				if ($total_images > 0)
					$article .= '<small>' . sprintf($images_count_title, $total_images) . '</small>';

				$article .=
				'</a></h2>';
				if ($use_summary)
					$article .=
					'<p class=wrap>' . get_summary($all_articles[1]) . '</p>' . $n;
				$article .=
				'<span class="blockquote-footer text-right"><a href="' . $url . $categ_link . '/" class=card-link>' . h($all_link[1]) . '</a></span>' . $n .
				'</div>' . $n;
				if ($counter || $comments)
					$article .=
					'<div class="card-footer bg-transparent">' . $counter . $comments . '</div>' . $n;
				$article .=
				'</div>' . $n;
			}
			$article .= '</div>';

			if ($default_contents_number > $number_of_default_sections)
				pager($max_pages, $page_ceil);
		}
	}
}
else
	not_found();

$article .= '<div class="clearfix mb-5"></div>';

if ($use_search)
	$search .=
	'<form class="form-inline my-2 my-lg-0" method=get action="' . $url . '">' . $n .
	'<input placeholder="Search..." type=search id=search name=query required class="form-control mr-sm-2" tabindex=1 accesskey=i>' . $n .
	'</form>' . $n;

if ($use_recents && $recent_dirs = glob($glob_dir, GLOB_ONLYDIR + GLOB_NOSORT))
{
	$aside .= '<div class="list-group mb-5"><div class="list-group-item bg-primary title">' . $recents . '</div>' . $n;

	foreach($recent_dirs as $recents_name)
	{
		if (is_file($recents_index = $recents_name . '/index.html'))
			$recents_sort[] = filemtime($recents_index) . '-~-' . $recents_name;
	}
	if (isset($recents_sort))
	{
		rsort($recents_sort);
		for($i = 0, $c = count($recents_sort); $i < $c && $i < $number_of_recents; ++$i)
		{
			$recent_name = explode('-~-', $recents_sort[$i]);
			$recent_basename = basename($recent_name[1]);
			$aside .=
			'<a class="list-group-item list-group-item-action' . ($get_categ . $get_title === basename(dirname($recent_name[1])) . $recent_basename ? ' bg-light' : '') . '" href="' . $url . r(get_title($recent_name[1]) . '/' . $recent_basename) . '">' . h($recent_basename) . '</a>' . $n;
		}
	}
	$aside .= '</div>';
}
$glob_info_files = glob('contents/*.html', GLOB_NOSORT);
if ($glob_info_files || $dl || $use_contact)
{
	$glob_info_flips = array_flip($glob_info_files);

	if (isset($glob_info_flips['contents/index.html']))
		unset($glob_info_flips['contents/index.html']);

	$aside .=
	'<div class="list-group mb-5"><div class="list-group-item bg-primary title">' . $informations . '</div>' . $n;

	if ($info_flips = array_flip($glob_info_flips))
	{
		foreach($info_flips as $info_files)
			$infos_sort[] = filemtime($info_files) . '-~-' . basename($info_files, '.html');

		$infos_sort = array_filter($infos_sort);
		rsort($infos_sort);
		for($i = 0, $c = count($infos_sort); $i < $c; ++$i)
		{
			$infos_uri = explode('-~-', $infos_sort[$i]);
			$aside .=
			'<a class="list-group-item list-group-item-action' . ($get_page === $infos_uri[1] ? ' bg-light' : '') . '" href="' . $url . r($infos_uri[1]) . '">' . h($infos_uri[1]) . '</a>' . $n;
		}
	}
	if ($dl)
		$aside .= '<a class="list-group-item list-group-item-action' . ($get_page === $download_contents ? ' bg-light' : '') . '" href="' . $url . r($download_contents) . '">' . $download_contents . '</a>' . $n;

	if ($use_contact)
		$aside .= '<a class="list-group-item list-group-item-action' . ($get_page === $contact_us ? ' bg-light' : '') . '" href="' . $url . r($contact_us) . '">' . $contact_us . '</a>' . $n;
	$aside .= '</div>';
}

if ($address)
	$aside .=
	'<div class="card mb-5">' . $n .
	'<div class="card-header">' . ($address_title ? $address_title : $site_name) . '</div>' . $n .
	'<div class="card-body wrap">' . $address . '</div>' . $n .
	'</div>';

if ($use_popular_articles && $number_of_popular_articles > 0 &&$glob_all_counter_files = glob($glob_dir . 'counter.txt', GLOB_NOSORT))
{
	$aside .=
	'<div class="list-group mb-5">' . $n .
	'<div class="list-group-item list-group-item-primary">' . $popular_articles . '</div>' . $n;

	foreach($glob_all_counter_files as $all_counter_files)
		$counter_sort[] = (int)trim(strip_tags(file_get_contents($all_counter_files))) . $all_counter_files;

	$counter_sort = array_filter($counter_sort);
	rsort($counter_sort, SORT_NUMERIC);
	for($i = 0, $c = count($counter_sort); $i < $c && $i < $number_of_popular_articles; ++$i)
	{
		$popular_titles = explode('/', $counter_sort[$i]);
		$aside .=
		'<a class="list-group-item list-group-item-action' . ($get_categ . $get_title === $popular_titles[1] . $popular_titles[2] ? ' bg-light' : '') . '" href="' . $url . r($popular_titles[1]) . '/' . r($popular_titles[2]) . '">' . h($popular_titles[2]) . '</a>' . $n;
	}
	$aside .= '</div>' . $n;
}

if ($use_comment && $number_of_new_comments > 0 && $glob_all_comment_files = glob($glob_dir . 'comments/*-~-*.txt', GLOB_NOSORT))
{
	$aside .=
	'<div class="list-group mb-5">' . $n .
	'<div class="list-group-item list-group-item-primary">' . $recent_comments . '</div>';

	foreach($glob_all_comment_files as $all_comment_files)
		$new_comments_sort[] = $all_comment_files;

	$new_comments_sort = array_filter($new_comments_sort);
	rsort($new_comments_sort);
	for($i = 0, $c = count($new_comments_sort); $i < $c && $i < $number_of_new_comments; ++$i)
	{
		$comments_content = trim(strip_tags(file_get_contents($new_comments_sort[$i])));
		$comments_content = str_replace($line_breaks, ' ', $comments_content);
		$new_comments = explode('-~-', $new_comments_sort[$i]);
		$comment_link = explode('/', $new_comments[0]);
		$aside .=
		'<a class="list-group-item list-group-item-action" href="' . $url . r($comment_link[1]) . '/' . r($comment_link[2]) . '#cid-' . basename($new_comments[0]) . '">' . $n .
		'<p class="comment-text wrap list-group-item-text">' . mb_strimwidth($comments_content, 0, $comment_length, $ellipsis, $encoding) . '</p>' . $n .
		'<small class="blockquote-footer text-right">' . h(basename($new_comments[1], '.txt')) . ' (' . timeformat(basename($new_comments[0])) . ')</small>' . $n .
		'</a> ' . $n;
	}
	$aside .=
	'</div>' . $n;
}
$footer.=
'<small><span class="text-muted text-center">&copy; ' . date('Y') . ' ' . $site_name . '. Powered by kinaga.</span></small>' . $n;
$header .=
'<meta name=application-name content=kinaga>' . $n .
'<link rel=alternate type="application/atom+xml" href="' . $url . 'atom.php">' . $n .
(! is_file('favicon.ico') ? '<link href="' . $url . 'images/icon.php" rel=icon type="image/svg+xml" sizes=any>' : '<link rel="shortcut icon" href="' . $url . 'favicon.ico">') . $n;
