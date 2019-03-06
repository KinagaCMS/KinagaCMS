<?php
function r($path)
{
	if (strpos($path, '%') !== false)
		return $path;
	else
		return str_replace(array('%2F', '%3A'), array('/', ':'), rawurlencode($path));
}

function d($enc)
{
	return rawurldecode(html_entity_decode($enc));
}

function h($str)
{
	global $encoding;
	return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, $encoding, false);
}

function size_unit($size)
{
	if ($size > 0)
	{
		$unit = array('B', 'KB', 'MB', 'GB');
		return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2). $unit[$i];
	}
}

function timestamp($file)
{
	return gmdate('D, d M Y H:i:s T', filemtime($file));
}

function is_ssl()
{
	if (isset($_SERVER['HTTPS']) && isset($_SERVER['SSL']) || isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR']))
		return true;
}

function complementary($hsla)
{
	if (list($h, $s, $l, $a) = explode(',', str_replace(array(' ', 'hsla', '(', ')', '%'), '', $hsla)))
	{
		if ((int)$l === 10) $l = 100;
		return 'hsla('. ($h += ($h > 180) ? -180 : 180). ', '. $s. '%, '. $l. '%, '. $a. ')';
	}
}

function get_hsl($colour)
{
	if ($colour[0] === 'h')
	{
		if (list($h, $s, $l) = explode(',', str_replace(array('hsl', '(', ')', '%'), '', $colour))) return [$h, $s, $l];
	}
	elseif ($colour[0] !== 'r')
	{
		$colour = ltrim($colour, '#');

		if (strlen($colour) === 3)
		{
			$r = hexdec(substr($colour, 0, 1). substr($colour, 0, 1));
			$g = hexdec(substr($colour, 1, 1). substr($colour, 1, 1));
			$b = hexdec(substr($colour, 2, 1). substr($colour, 2, 1));
		}
		else
		{
			$r = hexdec(substr($colour, 0, 2));
			$g = hexdec(substr($colour, 2, 2));
			$b = hexdec(substr($colour, 4, 2));
		}
	}
	else list($r, $g, $b) = explode(',', str_replace(array('rgb', '(', ')'), '', $colour));
	$r /= 255;
	$g /= 255;
	$b /= 255;
	$max = max($r, $g, $b);
	$min = min($r, $g, $b);
	$l = ($max + $min) / 2;
	if ($max === $min) $h = $s = 0;
	else
	{
		$d = $max - $min;
		$s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
		switch($max)
		{
			case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
			case $g: $h = ($b - $r) / $d + 2; break;
			case $b: $h = ($r - $g) / $d + 4; break;
		}
		$h /= 6;
	}
	$h = round($h * 360);
	$s = round($s * 100);
	$l = round($l * 100);
	return [$h, $s, $l];
}

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
	error_reporting(~E_NOTICE && ~E_WARNING);
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
	return basename(dirname(dirname($str)));
}

function get_title($str)
{
	$title = basename(dirname($str));
	if ($title === 'contents')
		$title = basename($str, '.html');
	return $title;
}

function get_page($nr)
{
	global $url, $get_categ, $get_title, $page_name, $current_url, $query, $download_contents;
	if ($get_categ && $get_title)
		return $current_url. '&amp;pages='. $nr;
	elseif ($get_categ && !$get_title)
		return $url. $get_categ. '/'. $nr. '/';
	elseif ($query)
		return $url. '?query='. r($query). '&amp;pages='. $nr;
	elseif ($page_name === $download_contents)
		return $url. r($download_contents). '&amp;pages='. $nr;
	else
		return $url. '?pages='. $nr;
}

function ht($str)
{
	return h(basename($str));
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
	'<textarea readonly onclick="this.select()" class="form-control border-0 rounded-0" rows=3 tabindex=10 accesskey=h>&lt;a href="'. $u. '" target="_blank"&gt;'. htmlentities(h($t)). '&lt;/a&gt;</textarea>'. $n.
	'</div>'. $n.
	'<div class=tab-pane id=wiki>'. $n.
	'<textarea readonly onclick="this.select()" class="form-control border-0 rounded-0" rows=3 tabindex=11 accesskey=w>['. $u. ' '. htmlentities(h($t)). ']</textarea>'. $n.
	'</div>'. $n.
	'<div class=tab-pane id=forum>'. $n.
	'<textarea readonly onclick="this.select()" class="form-control border-0 rounded-0" rows=3 tabindex=12 accesskey=f>[URL='. $u. ']'. htmlentities(h($t)). '[/URL]</textarea>'. $n.
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
	if ($extension = get_extension($src))
	{
		$image_extensions = array('.gif', '.jpg', '.jpeg', '.png', '.svg');
		$video_extensions = array('.mp4', '.ogg', '.webm');
		if ($scheme = strpos($src, '://')) $addr = parse_url($src);
		if (array_search($extension, $image_extensions) !== false)
		{
			$alt = h(basename($src));
			$exif = @exif_read_data($src, '', '', true);
			$exif_thumbnail = isset($exif['THUMBNAIL']['THUMBNAIL']) ? $exif['THUMBNAIL']['THUMBNAIL'] : '';
			$exif_comment = isset($exif['COMMENT']) && $comment ? str_replace($line_breaks, '&#10;', h(trim(strip_tags($exif['COMMENT'][0])))) : '';

			if ($get_title || $get_page)
			{
				if ($use_thumbnails && $exif_thumbnail && $thumbnail)
					$img = '<img class="align-top '. $class. ' img-thumbnail" src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '">';
				else
					$img = $exif_comment ?
					'<figure class="align-top img-thumbnail text-center d-inline-block mb-5 nowrap">'. $n.
					'<img class="img-fluid '. $class. '" src="'. $url. r($src). '" alt="'. $alt. '">'. $n.
					'<p class="text-center wrap my-2">'. $exif_comment. '</p>'. $n.
					'</figure>'. $n :
					'<img class="img-fluid img-thumbnail '. $class. '" src="'. $url. r($src). '" alt="'. $alt. '">'. $n;
				if ($scheme !== false)
					return
					'<figure class="img-thumbnail text-center d-inline-block nowrap '. $class. '">'. $n.
					'<a class=expand href="'. $src. '" target="_blank" onclick="return false" title="'. $exif_comment. '">'. $n.
					'<img class="img-fluid" src="'. $addr['scheme']. '://'. $addr['host']. r($addr['path']). '" alt="'. $alt. '">'. $n.
					'</a>'. $n.
					'<small class="blockquote-footer my-2 text-right">'. $n.
					'<a href="'. $addr['scheme']. '://'. $addr['host']. '/" target="_blank" rel="noopener noreferrer">'. sprintf($source, h($addr['host'])). '</a>'. $n.
					'</small>'. $n.
					'</figure>'. $n;
				else
				{
					$expand = strpos($class, 'expand') !== false ? ' class=expand' : '';
					return
					$exif_comment ?
					'<a class=m-1 href="'. $url. r($src). '" target="_blank" onclick="return false" title="'. $exif_comment. '"'. $expand. '>'. $img. '</a> '. $n :
					'<a class=m-1 href="'. $url. r($src). '" target="_blank" onclick="return false"'. $expand. '>'. $img. '</a> '. $n;
				}
			}
			else
			{
				$dirname = dirname(dirname($src));
				$img_size = @getimagesize($src);
				return
				'<a href="'. $url. r(basename(dirname($dirname)). '/'. basename($dirname)). '">'. $n.
				'<img class="d-block mx-auto img-fluid '. $class. '" src="'. $url. r($src). '" alt="'. $alt. '"'. (isset($img_size[0]) && $img_size[0] < 450 ? ' style="width:'. $img_size[0]. 'px"' : ''). '>'. $n.
				'</a>';
			}
		}
		elseif (array_search($extension, $video_extensions) !== false)
		{
			$vtt = str_replace($extension, '.vtt', $src);
			if ($get_title || $get_page)
			{
				if ($scheme !== false)
					return
					'<figure class="align-top img-thumbnail text-center d-inline-block nowrap '. $class. '">'. $n.
					'<video controls preload=none>'. $n.
					'<source src="'. $addr['scheme']. '://'. $addr['host']. r($addr['path']). '">'. $n.
					'<track src="'. str_replace($extension, '.vtt', $addr['scheme']. '://'. $addr['host']. r($addr['path'])). '" default=default>'. $n.
					'</video>'. $n.
					'<small class="blockquote-footer my-2 text-right">'. $n.
					'<a href="'. $addr['scheme']. '://'. $addr['host']. '/" target="_blank" rel="noopener noreferrer">'. sprintf($source, h($addr['host'])). '</a>'. $n.
					'</small>'. $n.
					'</figure>'. $n;
				else
					return
					'<a href="'. $url. r($src). '" class="sr-only mfp-iframe">video-iframe</a>'. $n.
					'<video class="align-top img-thumbnail '. $class. '" controls preload=none>'. $n.
					'<source src="'. $url. r($src). '">'. $n.
					'<track src="'. $url. r($vtt). '" default=default>'. $n.
					'</video>';
			}
			else
				return '<video class="align-top '. $class. '" controls preload=none><source src="'. $url. r($src). '"><track src="'. $url. r($vtt). '" default=default></video>'. $n;
		}
	}
}

function timeformat($time)
{
	global $now, $seconds_ago, $minutes_ago, $hours_ago, $days_ago, $present_format, $time_format;
	$diff = $now - $time;
	if ($diff < 60)
		return sprintf($seconds_ago, (int)$diff);
	elseif ($diff < 3600)
		return sprintf($minutes_ago, (int)($diff / 60));
	elseif ($diff < 86400)
		return sprintf($hours_ago, (int)($diff / 3600));
	elseif ($diff < 2764800)
		return sprintf($days_ago, (int)($diff / 86400));
	elseif (date('Y') !== date('Y', $time))
		return date($time_format, $time);
	else
		return date($present_format, $time);
}

function pager($num, $max)
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

			if ($j == $num)
				$article .= '<li class="active page-item"><a class=page-link>'. $num. '</a></li>'. $n;
			else
				$article .= '<li class=page-item><a class=page-link href="'. get_page($j). '">'. $j. '</a></li>'. $n;
		}
		else
		{
			if ($i == $ceil)
				$article .= '<li class="disable active page-item"><a class=page-link>'. $num. '</a></li>'. $n;
			else
			{
				$j = $num - $ceil + $i;
				$article .= '<li class=page-item><a class=page-link href="'. get_page($j). '">'. $j. '</a></li>'. $n;
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
	global $header, $tpl_dir, $get_title, $get_page;
	if ($get_title || $get_page)
	{
		if (preg_match_all('/col-lg-(\d{1,2})/', file_get_contents($tpl_dir. 'index.php'), $m))
		{
			$header .= '<style>';
			foreach($m[0] as $k => $v)
			{
				if ($hide && $m[1][$k] === min($m[1])) $header .= ".$v{display:none}";
				else $header .= ".$v{max-width:100%;flex:0 0 100%}";
			}
			$header .= '</style>';
		}
	}
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
		return '<img src="'. $url. $logo. '" class=img-fluid alt="'. $site_name. '">';
	else
		return $site_name;
}

function not_found()
{
	global $header, $article, $error, $breadcrumb, $site_name, $not_found, $n;
	http_response_code(404);
	$header .= '<title>'. $error. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .= '<li class="breadcrumb-item active">'. http_response_code(). '</li>'. $n;
	$article .=
	'<h1 class="h2 mb-4">'. $error. '</h1>'. $n.
	'<div class="article not-found">'. $not_found. '</div>'. $n;
}

function toc($sticky=true, $in_article=true)
{
	global $header, $article, $aside, $footer, $toc, $get_title, $get_page;
	if ($get_title || $get_page)
	{
		$toc_content = '<div class="list-group-item bg-primary navbar-dark d-flex justify-content-between">';
		$toc_content .= '<a class=navbar-brand href=#TOP>'. $toc. '</a> ';
		$toc_content .= '<button class=navbar-toggler data-toggle=collapse data-target=#toctoggle accesskey=p tabindex=50><span class=navbar-toggler-icon></span></button>';
		$toc_content .= '</div>';
		$toc_content .= '<div data-spy=scroll data-target=".article" data-offset=0 id=toctoggle class="list-group-item collapse show pl-0 pr-3"></div>';

		if ($in_article)
		{
			$header .= '<style>#toc{display:none;width:30%;overflow-x:auto}@media(max-width:768px){#toc{width:100%!important}}</style>';
			$article .= '<div id=toc class="list-group text-truncate float-md-right'. ($sticky ? ' sticky-top' : ''). ' mb-3">';
			$article .= $toc_content;
			$article .= '</div>';
		}
		else
		{
			$header .= '<style>#toc{display:none}</style>';
			$aside .= '<div id=toc class="list-group  '. ($sticky ? ' sticky-top' : ''). ' mb-5">';
			$aside .= $toc_content;
			$aside .= '</div>';
		}
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

function get_uri($uri, $get)
{
	if (strpos($uri, '%23') !== false || strpos($uri, '%26') !== false)
		return $uri;
	else
		return basename(filter_input(INPUT_GET, $get, FILTER_SANITIZE_ENCODED));
}

function sort_time($a, $b)
{
	return filemtime($a) < filemtime($b);
}

function get_extension($f)
{
	$info = pathinfo($f);
	if (isset($info['extension'])) return '.'. $info['extension'];
}
