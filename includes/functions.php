<?php
$header = $nav = $article = $aside = $footer = $search = '';
$get_title = !filter_has_var(INPUT_GET, 'title') ? '' : basename(filter_input(INPUT_GET, 'title', FILTER_SANITIZE_STRIPPED));
$get_categ = !filter_has_var(INPUT_GET, 'categ') ? '' : basename(filter_input(INPUT_GET, 'categ', FILTER_SANITIZE_STRIPPED));
$get_page = !filter_has_var(INPUT_GET, 'page') ? '' : basename(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED));
$get_dl = !filter_has_var(INPUT_GET, 'dl') ? '' : basename(filter_input(INPUT_GET, 'dl', FILTER_SANITIZE_STRIPPED));
$pages = !filter_has_var(INPUT_GET, 'pages') ? '' : basename(filter_input(INPUT_GET, 'pages', FILTER_SANITIZE_NUMBER_INT));
$comment_pages = !filter_has_var(INPUT_GET, 'comments') ? '' : basename(filter_input(INPUT_GET, 'comments', FILTER_SANITIZE_NUMBER_INT));
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
	$text = str_replace([ $n . $n . $n, $n . $n ], $n, $text);
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
		return $url . $get_categ.'/'. $nr . '/';
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
	global $social, $social_icon_size, $n, $url, $color;
	return
	'<section id=social class=mb-5>' . $n .
	'<h2>' . $social . '</h2><div class="btn-group btn-group-lg">' . $n .
	'<a class="btn t" href="https://twitter.com/intent/tweet?text=' . $t . '&amp;url=' . $u . '" target="_blank" rel="noopener noreferrer">' . $n .
	'<svg width="' . $social_icon_size . 'px" height="' . $social_icon_size . 'px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><g transform="translate(-277.86 -690.22)"><g transform="matrix(.13889 0 0 -.13889 285.37 694.36)"><path d="m0 0c0.527-11.744-8.23-24.838-23.733-24.838-4.715 0-9.104 1.383-12.799 3.752 4.429-0.522 8.851 0.706 12.361 3.458-3.653 0.067-6.737 2.481-7.799 5.798 1.308-0.25 2.595-0.177 3.77 0.142-4.016 0.808-6.787 4.425-6.697 8.293 1.126-0.625 2.414-1.001 3.783-1.043-3.719 2.484-4.772 7.394-2.584 11.147 4.117-5.052 10.269-8.376 17.209-8.725-1.217 5.224 2.745 10.253 8.134 10.253 2.402 0 4.572-1.013 6.094-2.636 1.902 0.374 3.689 1.069 5.302 2.026-0.623-1.95-1.947-3.587-3.671-4.619 1.689 0.201 3.298 0.65 4.796 1.314-1.119-1.675-2.535-3.145-4.166-4.322m-18.105 29.797c-19.882 0-36-16.117-36-36s16.118-36 36-36 36 16.117 36 36-16.118 36-36 36"/></g></g></svg>' . $n .
	'</a>' . $n .
	'<a class="btn g" href="https://plus.google.com/share?url=' . $u . '" target="_blank" rel="noopener noreferrer">' . $n .
	'<svg width="' . $social_icon_size . 'px" height="' . $social_icon_size . 'px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><g transform="translate(-297.63 -816.16)"><path d="m302.59 819.97c-0.0749-0.5696-0.44606-1.0369-0.87954-1.0501-0.43377-0.0127-0.72464 0.42312-0.64961 0.99285 0.0749 0.5696 0.4871 0.96729 0.92086 0.98056 0.43349 0.0129 0.68305-0.35375 0.60829-0.92335m3.1025-0.49498h-0.74247v-0.74246h-0.37116v0.74246h-0.74233v0.37117h0.74233v0.74246h0.37116v-0.74246h0.74247v-0.37117zm-2.0988 3.4416c0-0.45352-0.16941-0.74993-0.68194-1.134-0.51999-0.37904-0.64698-0.59322-0.15076-0.97682 0.27969-0.21626 0.4755-0.50451 0.4755-0.8602 0-0.38747-0.1585-0.73915-0.45657-0.91008h0.42257l0.35942-0.37725h-1.6092c-1.0046 0-1.4972 0.60193-1.4972 1.2644 0 0.67697 0.4643 1.2101 1.3712 1.2101-0.14068 0.28383-0.0843 0.54597 0.14578 0.73349-1.5488 0-1.8801 0.67959-1.8801 1.203 0 0.67835 0.77964 1.0816 1.7146 1.0816 1.2786 0 1.7867-0.67738 1.7867-1.2343m4.0318-1.7548c0 2.7613-2.2386 5.0001-4.9999 5.0001-2.7616 0-5.0001-2.2387-5.0001-5.0001 0-2.7615 2.2384-5.0001 5.0001-5.0001 2.7613 0 4.9999 2.2386 4.9999 5.0001m-4.5886 1.8122c0 0.49139-0.39894 0.87526-1.2253 0.87526-0.64628 0-1.1129-0.40902-1.1129-0.90041 0-0.48157 0.54762-0.89709 1.1938-0.8899 0.71718 0 1.1444 0.44661 1.1444 0.91505"/></g></svg>' . $n .
	'</a>' . $n .
	'<a class="btn f" href="https://www.facebook.com/sharer/sharer.php?u=' . $u . '&amp;title=' . $t . '" target="_blank" rel="noopener noreferrer">' . $n .
	'<svg width="' . $social_icon_size . 'px" height="' . $social_icon_size . 'px" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><g transform="translate(-288.98 -698.79)"><path d="m295.46 701.73h-0.69875c-0.24681 0-0.29819 0.10111-0.29819 0.35625v0.61667h0.99694l-0.096 1.0825h-0.90097v3.2336h-1.2912v-3.22h-0.67153v-1.0961h0.67153v-0.8632c0-0.81014 0.43333-1.2332 1.3942-1.2332h0.89403v1.1235zm-1.4799-2.9415c-2.7615 0-5 2.2385-5 5s2.2385 5 5 5c2.7614 0 5-2.2385 5-5s-2.2386-5-5-5"/></g></svg>' . $n .
	'</a>' . $n .
	'</div>' . $n .
	'</section>' . $n;
}

function permalink($t, $u)
{
	global $permalink, $for_html, $for_wiki, $for_forum, $n;
	return
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
	if ($title)
		$class .= ' note';
	return
	'<a href="' . $uri . '"' . ($class ? ' class="' . $class . '"' : '') . ($title ? ' title="' . $title . '" data-html="true"' : '') . ($position ? ' data-placement="' . $position . '"' : '') . ' target="' . $target . '"' . ($target==='_blank' ? ' rel="noopener noreferrer"' : '') . '>' .
	(!$name ? h($uri) : h($name)) .
	'</a>';
}

function img($src, $link='', $class='', $comment=true, $thumbnail=true)
{
	global $url, $source, $n, $get_title, $get_page, $use_thumbnails, $line_breaks;
	$info = pathinfo($src);
	if (strpos($src, '://') !== false)
	{
		$addr = parse_url($src);
		$uri = '';
		$link = $src;
		$src = rawurldecode($src);
		$img_source = '<p class="blockquote-footer my-2"><a href="' . $addr['scheme'].'://'.$addr['host'] . '/" target="_blank" rel="noopener noreferrer">' . sprintf($source, h($addr['host'])) . '</a></p>';
	}
	else
	{
		$uri = $url;
		$img_source = '';
	}
	if (isset($info['extension']))
	{
		$extension = strtolower($info['extension']);
		if (array_search($extension, array('gif', 'jpg', 'jpeg', 'png', 'svg')) !== false)
		{
			$exif = @exif_read_data($src, '', '', true);
			$exif_thumbnail = isset($exif['THUMBNAIL']['THUMBNAIL']) ? $exif['THUMBNAIL']['THUMBNAIL'] : '';
			$exif_comment = isset($exif['COMMENT']) && $comment ?str_replace($line_breaks, '&#10;', h(trim(strip_tags($exif['COMMENT'][0])))) : '';

			if ($use_thumbnails && $exif_thumbnail && $thumbnail)
				$img = '<img class="align-bottom img-fluid ' . $class . ' img-thumbnail" src="data:' . image_type_to_mime_type(exif_imagetype($src)) . ';base64,' . base64_encode($exif_thumbnail) . '" alt="' . h(basename($src)) . '">';
			elseif ($get_title || $get_page)
				$img = $exif_comment ? '<figure class="img-thumbnail text-center d-inline-block mb-5"><img class="align-bottom img-fluid ' . $class . '" src="' . $uri . r($src) . '" alt="' . h(basename($src)) . '"><p class="text-center wrap my-2">' . $exif_comment . '</p></figure>' : '<img class="img-fluid ' . $class . '" src="' . $uri . r($src) . '" alt="' . h(basename($src)) . '">';
			else
				$img = '<img class="img-fluid ' . $class . '" src="' . $uri . r($src) . '" alt="' . h(basename($src)) . '">';
			if ($get_title || $get_page)
				return $exif_comment ? '<a href="' . $uri . $link . '" target="_blank" onclick="return false" title="' . $exif_comment . '"' . (strpos($class, 'expand') !== false ? ' class=expand' : '') . '>' . $img . '</a>' : $img;
			elseif ($img_source)
				return '<figure class="img-thumbnail text-center d-inline-block mb-5"><a class=expand href="' . $uri . $link . '" target="_blank" onclick="return false" title="' . $exif_comment . '">' . $img . '</a>' . $img_source . '</figure>';
			else
				return '<a href="' . $uri . $link . '">' . $img . '</a>';
		}
		elseif (array_search($extension, array('mp4', 'ogg', 'webm')) !== false)
		{
			$vtt = is_file($vtt = str_replace($extension, 'vtt', $src)) ? r($vtt) : '';
			if ($get_title || $get_page)
				return '<figure class="img-thumbnail text-center d-inline-block mb-5"><video class="align-middle img-fluid ' . $class . '" controls preload=none><source src="' . $uri . r($src) . '"><track src="' . $url. $vtt . '"></video>' . $img_source . '</figure>' . $n;
			else
				return '<video class="align-middle img-fluid ' . $class . '" controls preload=none><source src="' . $uri . r($src) . '"><track src="' . $url. $vtt . '"></video>' . $n;
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

function pager($num, $max, $visible)
{
	global $article, $nav_laquo, $nav_raquo, $n;
	$article .=
	'<ul class="justify-content-center pagination mt-3">' . $n;

	if($num > 2)
		$article .= '<li class=page-item><a class=page-link href="' . get_page(1) . '">' . $nav_laquo . $nav_laquo . '</a></li>';
	if ($num > 1)
		$article .= '<li class=page-item><a class=page-link href="' . get_page($num-1) . '">' . $nav_laquo . '</a></li>' . $n;

	$i = 1;
	while ($i <= $visible)
	{
		if ($num - ceil($visible/2) < 0)
		{
			if ($i == $num)
				$article .= '<li class="active page-item"><a class=page-link>' . $num . '</a></li>' . $n;
			else
				$article .= '<li class=page-item><a class=page-link href="' . get_page($i) . '">' . $i . '</a></li>' . $n;
		}
		elseif ($num + floor($visible/2) > $max)
		{
			if ($max > $visible)
				$j = $max - $visible + $i;
			else
				$j = $i;

			if ($j == $num)
				$article .= '<li class="active page-item"><a class=page-link>' . $num . '</a></li>' . $n;
			else
				$article .= '<li class=page-item><a class=page-link href="' . get_page($j) . '">' . $j . '</a></li>' . $n;
		}
		else
		{
			if ($i == ceil($visible/2))
				$article .= '<li class="disable active page-item"><a class=page-link>' . $num . '</a></li>' . $n;
			else
			{
				$j = $num - ceil($visible/2) + $i;
				$article .= '<li class=page-item><a class=page-link href="' . get_page($j) . '">' . $j . '</a></li>' . $n;
			}
		}
		if ($i == $max)
			break;
		++$i;
	}
	if ($num < $max)
		$article .= '<li class=page-item><a class=page-link href="' . get_page($num+1) . '">' . $nav_raquo . '</a></li>' . $n;
	if ($num < $max - 1)
		$article .= '<li class=page-item><a class=page-link href="' . get_page($max) . '">' . $nav_raquo . $nav_raquo . '</a></li>' . $n;
	$article .=
	'</ul>' . $n;
}

function sideless()
{
	global $header, $get_title;
	if ($get_title)
		return $header .= '<style>.col-lg-9{max-width:100%;flex:0 0 100%}.col-lg-3{max-width:100%;flex:0 0 100%}</style>';
}

function nowrap()
{
	global $header, $get_title;
	if ($get_title)
		return $header .= '<style>.article{white-space:normal}</style>';
}

function redirect($link)
{
	global $header, $get_title;
	if ($get_title)
		$header .= '<script>location.replace("' . $link . '")</script><meta http-equiv=refresh content="0;URL=' . $link . '">';
}

function get_logo()
{
	global $site_name, $url;
	if (is_file($logo = 'images/logo.png'))
		return '<img src="' . $url . $logo . '" alt="' . $site_name . '">';
	else
		return $site_name;
}

$contents = get_dirs('contents', false);
$dl = is_dir($downloads_dir = 'downloads') ? true : false;
$form = 'includes/form.php';

if (! empty($contents))
{
	foreach($contents as $categ)
		$nav .= '<a ' . (filter_has_var(INPUT_GET, 'categ') && $get_categ == $categ ? 'class="nav-item nav-link active"' : 'class="nav-item nav-link"') . ' href="' . $url . r($categ) .'/">' . h($categ) . '</a>' . $n;
}
if (filter_has_var(INPUT_GET, 'page') && ! is_numeric($get_page))
{
	if (is_file($pages_file = 'contents/'. $get_page . '.html'))
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
			$article .= social(rawurlencode($basetitle . ' - ' . $site_name), rawurlencode($url . $basetitle));

		if ($use_permalink)
			$article .= permalink($basetitle . ' - ' . $site_name, $url . rawurlencode($basetitle));
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
		if (is_file($dl_file = $downloads_dir .'/'. $get_dl) && pathinfo($dl_file, PATHINFO_EXTENSION))
		{
			header('Content-Length: ' . filesize($dl_file) . '');
			header('Content-Type: ' . mime_content_type($dl_file) . '');

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

		if (filter_has_var(INPUT_GET, 'pages') && is_numeric($pages))
			$header .= '<title>' . $download_contents . ' - ' . sprintf($page_prefix, $pages) . ' - ' . $site_name . '</title>' . $n;
		else
		{
			$pages = 1;
			$header .= '<title>' . $download_contents . ' - ' . $site_name . '</title>' . $n;
		}
		if ($download_subtitle)
			$article .= '<h1 class="h2 mb-4">' . $download_contents . ' <small class="wrap text-muted">' . $download_subtitle . '</small></h1>' . $n;

		$dl_files = glob($downloads_dir .'/*.*', GLOB_NOSORT);

		if ($dl_files)
		{
			for($i = 0, $c = count($dl_files); $i < $c; ++$i)
				$dls_sort[] = ($di_filesize = filesize($dl_files[$i])) > 0 ? filemtime($dl_files[$i]) . '-~-' . $dl_files[$i] . '-~-' . size_unit($di_filesize) : '';

			$dls_sort = array_filter($dls_sort);
			rsort($dls_sort);
			$dls_number = count($dls_sort);
			$dls_in_page = array_slice($dls_sort, ($pages - 1) * $number_of_downloads, $number_of_downloads);

			if ($dls_number > $number_of_downloads)
			{
				$page_ceil = ceil($dls_number / $number_of_downloads);
				pager($pages, $page_ceil, $number_of_pager);
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
				pager($pages, $page_ceil, $number_of_pager);
		}
	}
	else
	{
		$header .= '<title>' . $error . ' - ' . $site_name . '</title>' . $n;
		$article .=
		'<h1 class="h2 mb-4">' . $error . '</h1>' . $n .
		'<div class=article>' . $not_found . '</div>' . $n;
	}
}
elseif (filter_has_var(INPUT_GET, 'categ') && ! filter_has_var(INPUT_GET, 'title'))
{
	if (is_dir($current_categ = 'contents/'. $get_categ))
	{
		$categ_title = h($get_categ);
		$breadcrumb .= '<li class="breadcrumb-item active">' . $categ_title . '</li>';
		$categ_contents = get_dirs($current_categ);
		$categ_contents_number = $categ_contents ? count($categ_contents) : 0;

		if (is_file($categ_file = $current_categ .'/index.html'))
		{
			ob_start();
			include_once $categ_file;
			$categ_content = trim(ob_get_clean());
			$article .= '<h1 class="h2 mb-4">' . $categ_title . ' <small class="wrap text-muted">' . $categ_content . '</small></h1>';
			$header .= '<meta name=description content="' . get_description($categ_content) . '">' . $n;
		}
		if ($categ_contents_number > 0)
		{
			if (filter_has_var(INPUT_GET, 'pages') && is_numeric($pages))
				$header .= '<title>' . $categ_title . ' - ' . sprintf($page_prefix, $pages) . ' - ' . $site_name . '</title>' . $n;
			else
			{
				$pages = 1;
				$header .= '<title>' . $categ_title . ' - ' . $site_name . '</title>' . $n;
			}

			for($i = 0; $i < $categ_contents_number; ++$i)
				$articles_sort[] = is_file($article_files = $current_categ .'/'. $categ_contents[$i] .'/index.html') ? filemtime($article_files) . '-~-' . $article_files : '';

			$articles_sort = array_filter($articles_sort);
			rsort($articles_sort);
			$sections_in_categ_page = array_slice($articles_sort, ($pages - 1) * $number_of_categ_sections, $number_of_categ_sections);

			if ($categ_contents_number > $number_of_categ_sections)
			{
				$page_ceil = ceil($categ_contents_number / $number_of_categ_sections);
				pager($pages, $page_ceil, $number_of_pager);
			}

			$article .= '<div class=card-columns>';
			for($i = 0, $c = count($sections_in_categ_page); $i < $c; ++$i)
			{
				$articles = explode('-~-', $sections_in_categ_page[$i]);
				$section = '<p class=wrap>' . get_summary($articles[1]) . '</p>' . $n;
				$articles_link = explode('/', $articles[1]);
				$categ_link = r($articles_link[1]);
				$title_link = r($articles_link[2]);
				$article_dir = dirname($articles[1]);
				$article_link_title = ht($articles_link[2]);
				$count_images = '';

				$counter = is_file($counter_txt = $article_dir .'/counter.txt') ?
				'<span class=card-link>' . sprintf($display_counts, (int)trim(strip_tags(file_get_contents($counter_txt)))).'</span>' : '';
				$comments = $use_comment && is_dir($comments_dir = $article_dir .'/comments') ?
				'<a class=card-link href="' . $url . $categ_link .'/'. $title_link . '#comment">' . $n .
				'' . sprintf($comment_counts, count(glob($comments_dir .'/*-~-*.txt', GLOB_NOSORT))) .
				'</a>' : '';

				if (is_dir($default_imgs_dir = $article_dir .'/images') && $glob_default_imgs = glob($default_imgs_dir . $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
				{
					sort($glob_default_imgs);
					$default_image = img($glob_default_imgs[0], $categ_link .'/'. $title_link, 'card-img-top', false, false);
					$count_images = count($glob_default_imgs);
				}
				else
					$default_image = $count_images = '';

				if (is_dir($default_background_dir = $article_dir .'/background-images') && $glob_default_background_imgs = glob($default_background_dir .'/*', GLOB_NOSORT))
				{
					sort($glob_default_background_imgs);
					$default_background_image = img($glob_default_background_imgs[0], $categ_link.'/'. $title_link, 'card-img-top', false, false);
					$count_background_images = count($glob_default_background_imgs);
				}
				else
					$default_background_image = $count_background_images = '';

				if (is_dir($default_popup_dir = $article_dir .'/popup-images') && $glob_default_popup_imgs = glob($default_popup_dir .'/*', GLOB_NOSORT))
					$count_popup_images = count($glob_default_popup_imgs);
				else
					$count_popup_images = 0;

				$total_images = (int)$count_images + (int)$count_background_images + (int)$count_popup_images;
				$article .=
				'<div class=card>' . $n .
				$default_image . $default_background_image .
				'<div class=card-body>' . $n .
				'<small class="card-subtitle mb-2 text-muted">' . timeformat($articles[0]) . '</small>'.
				'<h2 class="h4 card-title"><a href="' . $url . $categ_link .'/'. $title_link . '">' . $article_link_title;

				if ($total_images > 0)
					$article .= '<small>' . sprintf($images_count_title, $total_images) . '</small>';

				$article .=
				'</a></h2>' . $n .
				$section . $n .
				'</div>' . $n;
				if ($counter || $comments)
					$article .=
					'<div class="card-footer bg-transparent">' . $n .
					$counter . $comments .
					'</div>' . $n;
				$article .= '</div>' . $n;

			}
			$article .= '</div>';

			if ($categ_contents_number > $number_of_categ_sections)
				pager($pages, $page_ceil, $number_of_pager);
		}
		elseif (!$categ_file)
		{
			$header .=
			'<title>' . $no_article . ' - ' . $categ_title . ' - ' . $site_name . '</title>' . $n;
			$article .=
			'<h1 class="h2 mb-4">' . $no_article . '</h1>' . $n .
			'<div class=article>' . $not_found . '</div>' . $n;
		}
		else
			$header .= '<title>' . $categ_title . ' - ' . $site_name . '</title>' . $n;
	}
	else
	{
		$header .=
		'<title>' . $no_categ . ' - ' . $site_name . '</title>' . $n;
		$article .=
		'<h1 class="h2 mb-4">' . $no_categ . '</h1>' . $n .
		'<div class=article>' . $not_found . '</div>' . $n;
	}
}
elseif (filter_has_var(INPUT_GET, 'categ') && filter_has_var(INPUT_GET, 'title'))
{
	$breadcrumb .=
	'<li class=breadcrumb-item><a href="' . $url . r($get_categ) .'/">' . h($get_categ) . '</a></li>' . $n .
	'<li class="breadcrumb-item active">' . h($get_title) . '</li>';

	if (is_dir($current_article_dir = 'contents/'. $get_categ .'/'. $get_title) && is_file($current_article = $current_article_dir .'/index.html'))
	{
		if (is_dir($background_images_dir = $current_article_dir .'/background-images') && $glob_background_images = glob($background_images_dir .'/*', GLOB_NOSORT))
		{
			$header .= '<style>';

			foreach($glob_background_images as $background_images)
			{
				if (list($width, $height) = @getimagesize($background_images))
				{
					$info = pathinfo($background_images);
					$classname = '.' . basename($background_images, '.' . $info['extension']);
					$aspect = round($height / $width * 100, 1);
					$header .= '@media(max-width:' . ($width * 1.5) . 'px){' . $classname . '{' . ($height > 400 ? 'height:0px!important;padding-bottom:' . $aspect . '%' : 'height:' . $height . 'px') . '}}' . $classname . '{max-width:' . $width . 'px;background-image:url(' . $url . r($background_images) . ');background-size:100%;background-repeat:no-repeat;' . ($height > 1000 ? 'height:0px!important;padding-bottom:' . $aspect . '%' : 'height:' . $height . 'px') . '}';
				}
			}
			$header .= '</style>' . $n;
		}
		if (is_dir($popup_images_dir = $current_article_dir .'/popup-images') && $glob_popup_images = glob($popup_images_dir .'/*', GLOB_NOSORT))
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

		if (filter_has_var(INPUT_GET, 'pages') && is_numeric($pages))
			$header .= '<title>' . $article_encode_title . ' - ' . sprintf($page_prefix, $pages) . ' - ' . $site_name . '</title>' . $n;
		else
		{
			$pages = 1;
			$header .= '<title>' . $article_encode_title . ' - ' . $site_name . '</title>' . $n;
		}
		$article_filemtime = filemtime($current_article);
		$current_url = $url . r($get_categ) .'/'. r($get_title);
		$article .=
		'<small class=text-muted>' . sprintf($last_modified, date($time_format, $article_filemtime)) . '</small>';

		if (is_file($counter_txt = $current_article_dir .'/counter.txt') && is_writeable($counter_txt))
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

		if ($use_comment && is_dir($comment_dir = $current_article_dir .'/comments') && $glob_comment_files = glob($comment_dir .'/*-~-*.txt', GLOB_NOSORT))
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
		'<div class="mb-2 article">' . $current_article_content . '</div>' . $n;

		if (is_dir($images_dir = $current_article_dir .'/images') && $glob_image_files = glob($images_dir . $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
		{
			sort($glob_image_files);
			$glob_images_number = count($glob_image_files);
			$images_in_page = array_slice($glob_image_files, ($pages - 1) * $number_of_images, $number_of_images);

			if ($glob_images_number > $number_of_images)
			{
				$page_ceil = ceil($glob_images_number / $number_of_images);
				pager($pages, $page_ceil, $number_of_pager);
			}
			$article .= '<div class="gallery text-center">' . $n;
			for($i = 0, $c = count($images_in_page); $i < $c; ++$i)
				$article .= img($images_in_page[$i], r($images_in_page[$i]), '', true, true);
			$article .= '</div>' . $n;

			if ($glob_images_number > $number_of_images)
				pager($pages, $page_ceil, $number_of_pager);
		}

		if ($glob_prev_next = glob('contents/'. $get_categ .'/*/index.html', GLOB_NOSORT))
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
			$article .= '<div class="mt-5 mb-5 clearfix">';

			for($i = 0, $c = count($sort_prev_next); $i < $c; ++$i)
			{
				$prev_next_parts = explode('-~-', $sort_prev_next[$i] . '-~-' . $i);

				$prev_next_title = get_title($prev_next_parts[1]);

				if ((int)$prev_next_parts[0] > $article_filemtime)
				{
					$prev_href = $url . r($get_categ) .'/'. r($prev_next_title);
					$prev_next_encode_title = h($prev_next_title);
					$header .=
					'<link rel=prev href="' . $prev_href . '">' . $n;
					$prev_link =
					'<a class="btn btn-outline-primary" title="' . $prev_next_encode_title . '" href="' . $prev_href . '">' . mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding) . '</a>' . $n;
				}

				$prev_next_count = (int)$prev_next_parts[0] == $article_filemtime ? (int)$prev_next_parts[2] +1: '';

				if ((int)$prev_next_parts[0] < $article_filemtime)
				{
					$next_href = $url . r($get_categ) .'/'. r($prev_next_title);
					$prev_next_encode_title = h($prev_next_title);
					$header .=
					'<link rel=next href="' . $next_href . '">' . $n;
					$article .=
					'<a class="float-right btn btn-outline-primary" title="' . $prev_next_encode_title . '" href="' . $next_href . '">' . mb_strimwidth($prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding) . '</a>' . $n;
					break;
				}
			}
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
						'<a class="progress-bar progress-bar-striped bg-primary" style="width:' . $similar[0] . '%" href="' . $url . r($get_categ) .'/'. r($similar[1]) . '">' . h($similar[1]) . ' - ' . $similar[0] . '%</a>' . $n .
						'</div>';
					}
					$article .= '</section>';
				}
			}
		}
		if ($use_social)
			$article .= social(rawurlencode($get_title . ' - ' . $site_name), rawurlencode($url . $get_categ .'/'. $get_title));

		if ($use_permalink)
			$article .= permalink($article_encode_title . ' - ' . $site_name, $current_url);

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
			if (is_file($comment_dir .'/end.txt'))
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
	{
		$header .=
		'<title>' . $no_article . ' - ' . $site_name . '</title>' . $n;
		$article .=
		'<h1 class="h2 mb-4">' . $no_article . '</h1>' . $n .
		'<div class=article>' . $not_found . '</div>' . $n;
	}
}
elseif (! filter_has_var(INPUT_GET, 'categ') && ! filter_has_var(INPUT_GET, 'title'))
{
	if ($use_search && filter_has_var(INPUT_GET, 'query'))
	{
		$no_results = '';
		$word = trim(mb_convert_kana(filter_input(INPUT_GET, 'query', FILTER_SANITIZE_SPECIAL_CHARS), 'rnsK', 'UTF-8'));
		$result_title = sprintf($result, $word);
		$breadcrumb .= '<li class="breadcrumb-item active">' . $result_title . '</li>';

		if (filter_has_var(INPUT_GET, 'pages') && is_numeric($pages))
			$header .= '<title>' . $result_title . ' - ' . sprintf($page_prefix, $pages) . ' - ' . $site_name . '</title>' . $n;
		else
		{
			$pages = 1;
			$header .= '<title>' . $result_title . ' - ' . $site_name . '</title>' . $n;
		}
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
				$results_in_page = array_slice($outputs, ($pages - 1) * $number_of_results, $number_of_results);
				if ($results_number > $number_of_results)
				{
					$page_ceil = ceil($results_number / $number_of_results);
					pager($pages, $page_ceil, $number_of_pager);
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
						'<h2><a href="' . $url . r(get_categ($output[1])) .'/'. r($title) . '">' . h($title) . '</a></h2>' . $n .
						'<div class=wrap>' . $output[2] . '</div>' . $n;
					$article .= '</section>' . $n;

				}
				if ($results_number > $number_of_results)
					pager($pages, $page_ceil, $number_of_pager);
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
		if (filter_has_var(INPUT_GET, 'pages') && is_numeric($pages))
		{
			$header .=
			'<title>' . $site_name . ' - ' . sprintf($page_prefix, $pages) . '</title>' . $n;
			#$article .= '<h1 class="h2 mb-4">' . $site_name . ' <small class=text-muted>' . sprintf($page_prefix, $pages) . '</small></h1>' . $n;
		}
		else
		{
			$pages = 1;
			$header .=
			'<title>' . $site_name . ($subtitle ? ' - ' . $subtitle : '') . '</title>' . $n;
			if ($subtitle)
				$article .= '<h1 class="h2 mb-4">' . $site_name . ' <small class="wrap text-muted">' . $subtitle . '</small></h1>' . $n;
		}
		$header .= '<meta name=description content="' . $meta_description . '">' . $n;

		if ($glob_files = glob($glob_dir . 'index.html', GLOB_NOSORT))
		{
			foreach($glob_files as $all_files)
				$all_sort[] = filemtime($all_files) . '-~-' . $all_files;

			$all_sort = array_filter($all_sort);
			rsort($all_sort);
			$default_contents_number = count($all_sort);
			$sections_in_default_page = array_slice($all_sort, ($pages - 1) * $number_of_default_sections, $number_of_default_sections);

			if ($default_contents_number > $number_of_default_sections)
			{
				$page_ceil = ceil($default_contents_number / $number_of_default_sections);
				pager($pages, $page_ceil, $number_of_pager);
			}

			$article .= '<div class=card-columns>';
			for($i = 0, $c = count($sections_in_default_page); $i < $c; ++$i)
			{
				$all_articles = explode('-~-', $sections_in_default_page[$i]);
				$section = '<p class=wrap>' . get_summary($all_articles[1]) . '</p>' . $n;
				$all_link = explode('/', $all_articles[1]);
				$categ_link = r($all_link[1]);
				$title_link = r($all_link[2]);
				$article_link_title = ht($all_link[2]);
				$article_dir = dirname($all_articles[1]);
				$counter = is_file($counter_txt = $article_dir .'/counter.txt') ?
				'<span class=card-link>' . sprintf($display_counts, (int)trim(strip_tags(file_get_contents($counter_txt)))) . '</span>' : '';
				$comments = is_dir($comments_dir = $article_dir .'/comments') && $use_comment ?
				'<a class=card-link href="' . $url . $categ_link .'/'. $title_link . '#comment"> ' . sprintf($comment_counts, count(glob($comments_dir .'/*-~-*.txt'), GLOB_NOSORT)) . '</a>' : '';

				if (is_dir($default_imgs_dir = $article_dir .'/images') && $glob_default_imgs = glob($default_imgs_dir . $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
				{
					sort($glob_default_imgs);
					$default_image = img($glob_default_imgs[0], $categ_link.'/'. $title_link, 'card-img-top', false, false);
					$count_images = count($glob_default_imgs);
				}
				else
					$default_image = $count_images = '';

				if (is_dir($default_background_dir = $article_dir .'/background-images') && $glob_default_background_imgs = glob($default_background_dir .'/*', GLOB_NOSORT))
				{
					sort($glob_default_background_imgs);
					$default_background_image = img($glob_default_background_imgs[0], $categ_link.'/'. $title_link, 'card-img-top', false, false);
					$count_background_images = count($glob_default_background_imgs);
				}
				else
					$default_background_image = $count_background_images = '';

				if (is_dir($default_popup_dir = $article_dir .'/popup-images') && $glob_default_popup_imgs = glob($default_popup_dir .'/*', GLOB_NOSORT))
					$count_popup_images = count($glob_default_popup_imgs);
				else
					$count_popup_images = 0;

				$total_images = (int)$count_images + (int)$count_background_images + (int)$count_popup_images;

				$article .=
				'<div class=card>' . $n .
				$default_image . $default_background_image .
				'<div class=card-body>' . $n .
				'<small class="card-subtitle mb-2 text-muted">'.timeformat($all_articles[0]).'</small>' . $n .
				'<h2 class="h4 card-title"><a href="' . $url . $categ_link.'/'. $title_link . '">' . $article_link_title;

				if ($total_images > 0)
					$article .= '<small>' . sprintf($images_count_title, $total_images) . '</small>';

				$article .=
				'</a></h2>' . $n .
				'' . $section .
				'<span class="blockquote-footer text-right"><a href="' . $url . $categ_link .'/" class=card-link>' . h($all_link[1]) . '</a></span>' . $n .
				'</div>' . $n;
				if ($counter || $comments)
					$article .=
					'<div class="card-footer bg-transparent">' . $n .
					$counter . $comments .
					'</div>' . $n;
				$article .=
				'</div>' . $n;
			}
			$article .= '</div>';

			if ($default_contents_number > $number_of_default_sections)
				pager($pages, $page_ceil, $number_of_pager);
		}
	}
}
else
{
	$header .=
	'<title>' . $error . ' - ' . $site_name . '</title>' . $n;
	$article .=
	'<h1 class="h2 mb-4">' . $error . '</h1>' . $n .
	'<div class=article>' . $not_found . '</div>' . $n;
}

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
		if (is_file($recents_index = $recents_name .'/index.html'))
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
			'<a class="list-group-item list-group-item-action' . ($get_categ . $get_title === basename(dirname($recent_name[1])) . $recent_basename ? ' bg-light' : '') . '" href="' . $url . r(get_title($recent_name[1]) .'/'. $recent_basename) . '">' . h($recent_basename) . '</a>' . $n;
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
	'<div class="card-header bg-light">' . ($address_title ? $address_title : $site_name) . '</div>' . $n .
	'<div class="card-body wrap">' . $address . '</div>' . $n .
	'</div>';

if ($use_popular_articles && $number_of_popular_articles > 0 &&$glob_all_counter_files = glob($glob_dir . 'counter.txt', GLOB_NOSORT))
{
	$aside .=
	'<div class="list-group mb-5">' . $n .
	'<div class="list-group-item active">' . $popular_articles . '</div>' . $n;

	foreach($glob_all_counter_files as $all_counter_files)
		$counter_sort[] = (int)trim(strip_tags(file_get_contents($all_counter_files))) . $all_counter_files;

	$counter_sort = array_filter($counter_sort);
	rsort($counter_sort, SORT_NUMERIC);
	for($i = 0, $c = count($counter_sort); $i < $c && $i < $number_of_popular_articles; ++$i)
	{
		$popular_titles = explode('/', $counter_sort[$i]);
		$aside .=
		'<a class="list-group-item list-group-item-action' . ($get_categ . $get_title === $popular_titles[1] . $popular_titles[2] ? ' bg-light' : '') . '" href="' . $url . r($popular_titles[1]) .'/'. r($popular_titles[2]) . '">' . h($popular_titles[2]) . '</a>' . $n;
	}
	$aside .= '</div>' . $n;
}

if ($use_comment && $number_of_new_comments > 0 && $glob_all_comment_files = glob($glob_dir . 'comments/*-~-*.txt', GLOB_NOSORT))
{
	$aside .=
	'<div class="list-group mb-5">' . $n .
	'<div class="list-group-item bg-light">' . $recent_comments . '</div>';

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
		'<a class="list-group-item list-group-item-action" href="' . $url . r($comment_link[1]) .'/'. r($comment_link[2]) . '#cid-' . basename($new_comments[0]) . '">' . $n .
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
