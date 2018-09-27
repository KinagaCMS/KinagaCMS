<?php
function get_dirs($dir, $nosort=true)
{
	if ($dirs = glob($dir. '/*' , !$nosort ? GLOB_ONLYDIR : GLOB_ONLYDIR + GLOB_NOSORT))
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
	include $file;
	$text = ob_get_clean();
	$text = strip_tags(preg_replace('/<script.*?\/script>/s', '', $text));
	$text = str_replace(array($n. $n. $n, $n. $n), $n, $text);
	$text = mb_strimwidth($text, 0, $summary_length, $ellipsis, $encoding);
	return trim($text);
}

function get_description($str)
{
	global $description_length, $encoding, $line_breaks, $ellipsis;
	$text = strip_tags(preg_replace('/<script.*?\/script>/s', '', $str));
	$text = mb_strimwidth($text, 0, $description_length, $ellipsis, $encoding);
	$text = str_replace($line_breaks, '', $text);
	return h(trim($text));
}

function get_categ($str)
{
	return strip_tags(basename(dirname(dirname($str))));
}

function get_title($str)
{
	$title = strip_tags(basename(dirname($str)));
	if ($title === 'contents')
		$title = strip_tags(basename($str, '.html'));
	return $title;
}

function get_page(int $nr)
{
	global $url, $get_categ, $get_title, $get_page, $current_url, $query, $download_contents;
	if ($get_categ && $get_title)
		return $current_url. '&amp;pages='. $nr;
	elseif ($get_categ && !$get_title)
		return $url. r($get_categ). '/'. $nr. '/';
	elseif ($query)
		return $url. '?query='. r($query). '&amp;pages='. $nr;
	elseif ($get_page === $download_contents)
		return $url. r($download_contents). '&amp;pages='. $nr;
	else
		return $url. '?pages='. $nr;
}

function ht($str)
{
	return h(strip_tags(basename($str)));
}

function social($t, $u)
{
	global $article, $social, $social_medias, $n;
	if ($social_medias)
	{
		$article .= '<section id=social class=mb-5>'. $n.
		'<h2>'. $social. '</h2>'. $n;
		$social_link = include 'socials.php';
		foreach($social_medias as $social_name)
			$article .= $social_link[$social_name]. $n;
		$article .= '</section>'. $n;
	}
}

function permalink($t, $u)
{
	global $article, $permalink, $for_html, $for_wiki, $for_forum, $n;
	$article .=
	'<section id=permalink class=mb-5>'. $n.
	'<h2>'. $permalink. '</h2>'. $n.
	'<ul class="nav nav-tabs" role=tablist>'. $n.
	'<li class=nav-item><a class="nav-link active" href=#html data-toggle=tab aria-controls=html role=tab>'. $for_html. '</a></li>'. $n.
	'<li class=nav-item><a class=nav-link href=#wiki data-toggle=tab aria-controls=wiki role=tab>'. $for_wiki. '</a></li>'. $n.
	'<li class=nav-item><a class=nav-link href=#forum data-toggle=tab aria-controls=forum role=tab>'. $for_forum. '</a></li>'. $n.
	'</ul>'. $n.
	'<div class=tab-content>'. $n.
	'<div class="tab-pane active" id=html>'. $n.
	'<textarea readonly onclick="this.select()" class="form-control bg-white border-0 rounded-0" rows=3 tabindex=10 accesskey=h>'. h('<a href="'. $u. '" target="_blank">'. $t. '</a>'). '</textarea>'. $n.
	'</div>'. $n.
	'<div class=tab-pane id=wiki>'. $n.
	'<textarea readonly onclick="this.select()" class="form-control bg-white border-0 rounded-0" rows=3 tabindex=11 accesskey=w>'. h('['. $u. ' '. $t. ']'). '</textarea>'. $n.
	'</div>'. $n.
	'<div class=tab-pane id=forum>'. $n.
	'<textarea readonly onclick="this.select()" class="form-control bg-white border-0 rounded-0" rows=3 tabindex=12 accesskey=f>'. h('[URL='. $u. ']'. $t. '[/URL]'). '</textarea>'. $n.
	'</div>'. $n.
	'</div>'. $n.
	'</section>'. $n;
}

function a($uri, $name='', $target='_blank', $class='', $title='', $position='')
{
	return
	'<a href="'. $uri. '" target="'. $target. '"' .
	($class ? ' class="'. $class. '"' : '') .
	($title ? ' data-toggle="tooltip" title="'. $title. '" data-html="true"' : '') .
	($position ? ' data-placement="'. $position. '"' : '') .
	($target === '_blank' ? ' rel="noopener noreferrer"' : ''). '>' .
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
					$img = '<img class="align-top '. $class. ' img-thumbnail" src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. h(basename($src)). '">';
				else
					$img = $exif_comment ?
						'<figure class="align-top img-thumbnail text-center d-inline-block mb-5"><img class="img-fluid '. $class. '" src="'. $url. r($src). '" alt="'. h(basename($src)). '"><p class="text-center wrap my-2">'. $exif_comment. '</p></figure>' :
						'<img class="img-fluid img-thumbnail '. $class. '" src="'. $url. r($src). '" alt="'. h(basename($src)). '">';
				if ($scheme !== false)
					return '<figure class="img-thumbnail text-center d-inline-block '. $class. '"><a class=expand href="'. $src. '" target="_blank" onclick="return false" title="'. $exif_comment. '"><img class="img-fluid" src="'. $addr['scheme']. '://'. $addr['host']. r($addr['path']). '" alt="'. h(basename($src)). '"></a><small class="blockquote-footer my-2 text-right"><a href="'. $addr['scheme']. '://'. $addr['host']. '/" target="_blank" rel="noopener noreferrer">'. sprintf($source, h($addr['host'])). '</a></small></figure>';
				else
				{
					$expand = strpos($class, 'expand') !== false ? ' class=expand' : '';
					return $exif_comment ?
						'<a href="'. $url. r($src). '" target="_blank" onclick="return false" title="'. $exif_comment. '"'. $expand. '>'. $img. '</a>' :
						'<a href="'. $url. r($src). '" target="_blank" onclick="return false"'. $expand. '>'. $img. '</a>';
				}
			}
			else
			{
				$dirname = dirname(dirname($src));
				$img_size = @getimagesize($src);
				return '<a href="'. $url. r(basename(dirname($dirname)). '/'. basename($dirname)). '"><img class="d-block mx-auto img-fluid '. $class. '" src="'. $url. r($src). '" alt="'. h(basename($src)). '"'. (isset($img_size[0]) && $img_size[0] < 450 ? ' style="width:'. $img_size[0]. 'px"' : ''). '></a>';
			}
		}
		elseif (array_search($extension, array('mp4', 'ogg', 'webm')) !== false)
		{
			$vtt = str_replace($extension, 'vtt', $src);
			if ($get_title || $get_page)
			{
				if ($scheme !== false)
					return '<figure class="align-top img-thumbnail text-center d-inline-block '. $class. '"><video controls preload=none><source src="'. $addr['scheme']. '://'. $addr['host']. r($addr['path']). '"><track src="'. str_replace($extension, 'vtt', $addr['scheme']. '://'. $addr['host']. r($addr['path'])). '"></video><small class="blockquote-footer my-2 text-right"><a href="'. $addr['scheme']. '://'. $addr['host']. '/" target="_blank" rel="noopener noreferrer">'. sprintf($source, h($addr['host'])). '</a></small></figure>'. $n;
				else
					return '<a href="'. $url. r($src). '" class="sr-only mfp-iframe">video-iframe</a><video class="align-top img-thumbnail '. $class. '" controls preload=none><source src="'. $url. r($src). '"><track src="'. $url. r($vtt). '"></video>';
			}
			else
				return '<video class="align-top '. $class. '" controls preload=none><source src="'. $url. r($src). '"><track src="'. $url. r($vtt). '"></video>'. $n;
		}
	}
}

function timeformat(int $time)
{
	global $now, $seconds_ago, $minutes_ago, $hours_ago, $days_ago, $present_format, $time_format;
	$diff = $now - $time;
	if ($diff < 60)
		return (int)$diff. $seconds_ago;
	elseif ($diff < 3600)
		return (int)($diff / 60). $minutes_ago;
	elseif ($diff < 86400)
		return (int)($diff / 3600). $hours_ago;
	elseif ($diff < 2764800)
		return (int)($diff / 86400). $days_ago;
	elseif (date('Y') !== date('Y', $time))
		return date($time_format, $time);
	else
		return date($present_format, $time);
}

function pager(int $num, int $max)
{
	global $number_of_pager, $article, $nav_laquo, $nav_raquo, $n;
	$article .=
	'<ul class="justify-content-center pagination my-4">'. $n;

	if($num > 2)
		$article .= '<li class=page-item><a class=page-link href="'. get_page(1). '">'. $nav_laquo. $nav_laquo. '</a></li>';
	if ($num > 1)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($num-1). '">'. $nav_laquo. '</a></li>'. $n;

	$i = 1;
	while ($i <= $number_of_pager)
	{
		$half_page = $number_of_pager/2;
		$ceil = ceil($half_page);
		if ($num - $ceil < 0)
		{
			if ($i == $num)
				$article .= '<li class="active page-item"><a class=page-link>'. $num. '</a></li>'. $n;
			else
				$article .= '<li class=page-item><a class=page-link href="'. get_page($i). '">'. $i. '</a></li>'. $n;
		}
		elseif ($num + floor($half_page) > $max)
		{
			if ($max > $number_of_pager)
				$j = $max - $number_of_pager + $i;
			else
				$j = $i;

			$get_page_link = get_page($j);

			if ($j == $num)
				$article .= '<li class="active page-item"><a class=page-link>'. $num. '</a></li>'. $n;
			else
				$article .= '<li class=page-item><a class=page-link href="'. $get_page_link. '">'. $j. '</a></li>'. $n;
		}
		else
		{
			if ($i == $ceil)
				$article .= '<li class="disable active page-item"><a class=page-link>'. $num. '</a></li>'. $n;
			else
			{
				$j = $num - $ceil + $i;
				$article .= '<li class=page-item><a class=page-link href="'. $get_page_link. '">'. $j. '</a></li>'. $n;
			}
		}
		if ($i == $max)
			break;
		++$i;
	}
	if ($num < $max)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($num+1). '">'. $nav_raquo. '</a></li>'. $n;
	if ($num < $max - 1)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($max). '">'. $nav_raquo. $nav_raquo. '</a></li>'. $n;
	$article .=
	'</ul>'. $n;
}

function sideless($hide = false)
{
	global $header, $get_title, $get_page;

	if (!$hide)
	{
		if ($get_title || $get_page)
			$header .= '<style>.col-lg-9{max-width:100%;flex:0 0 100%}.col-lg-3{max-width:100%;flex:0 0 100%}</style>';
	}
	else
		$header .= '<style>.col-lg-9{max-width:100%;flex:0 0 100%}.col-lg-3{display:none}</style>';
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
		header('Location: '. $link);
		exit;
	}
}

function get_logo()
{
	global $site_name, $url;
	if (is_file($logo = 'images/logo.png'))
		return '<img src="'. $url. $logo. '" alt="'. $site_name. '">';
	else
		return $site_name;
}

function not_found()
{
	global $header, $article, $error, $site_name, $not_found, $n;
	http_response_code(404);
	$header .= '<title>'. $error. ' - '. $site_name. '</title>'. $n;
	$article .=
	'<h1 class="h2 mb-4">'. $error. '</h1>'. $n.
	'<div class="article not-found">'. $not_found. '</div>'. $n;
}

function toc($sticky=true)
{
	global $header, $article, $footer, $toc, $get_title, $get_page;
	if ($get_title || $get_page)
	{
		$header .= '<style>#toc{display:none;width:30%}@media(max-width:768px){#toc{width:100%!important}}</style>';
		$article .= '<div id=toc class="text-truncate float-md-right'. ($sticky ? ' sticky-top' : ''). ' mb-3 card" style="overflow:auto">';
		$article .= '<nav class="navbar navbar-dark bg-primary">';
		$article .= '<span class="navbar-brand">'. $toc. '</span><button class=navbar-toggler data-toggle=collapse data-target="#toctoggle"><span class=navbar-toggler-icon></span></button>';
		$article .= '</nav>';
		$article .= '<div data-spy=scroll data-target=.article data-offset=0 id=toctoggle class="collapse show mr-2 mt-3"></div>';
		$article .= '</div>';
		$footer .= '<script>toc()</script>';
	}
}

function unsession()
{
	global $url, $session_name;
	$_SESSION = array();
	setcookie(session_name(), '', 1);
	session_destroy();
	session($session_name);
}

function session($session_name)
{
	if (!isset($_SESSION[$session_name]) && session_name() === 'kinaga')
	{
		global $dir, $server;
		session_set_cookie_params('8640', $dir === '/' ? '/' : $dir. '/', $server, is_ssl(), true);
		session_start();
		session_regenerate_id(true);
	}
}