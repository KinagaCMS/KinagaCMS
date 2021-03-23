<?php
if (__FILE__ === implode(get_included_files())) exit;
function r($path)
{
	if (false !== strpos($path, '%'))
		return $path;
	else
		return str_replace(['%2F', '%3A'], ['/', ':'], rawurlencode($path));
}

function d($enc)
{
	global $get_page;
	if (false !== strpos($enc ,'/&pages')) $enc = dirname($enc);
	if (false !== strpos($enc , $get_page. '&pages')) $enc = explode('&', $enc)[0];
	return rawurldecode(html_entity_decode(basename($enc)));
}

function h($str)
{
	global $encoding;
	return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, $encoding, false);
}

function size_unit($num, $filesize=true)
{
	if (900 > $num)
	{
		$num_format = number_format($num, 1);
		$unit = $filesize ? 'B' : '';
	}
	elseif (900000 > $num)
	{
		$num_format = number_format($num/1000, 1);
		$unit = $filesize ? 'kB' : 'K';
	}
	elseif (900000000 > $num)
	{
		$num_format = number_format($num/1000000, 1);
		$unit = $filesize ? 'MB' : 'M';
	}
	elseif (900000000000 > $num)
	{
		$num_format = number_format($num/1000000000, 1);
		$unit = $filesize ? 'GB' : 'B';
	}
	else
	{
		$num_format = number_format($num/1000000000000, 1);
		$unit = $filesize ? 'TB' : 'T';
	}
	return str_replace('.'. str_repeat(0, 1), '', str_replace('.'. str_repeat(0, 1), '', $num_format)). $unit;
}

function timestamp()
{
	return gmdate('D, d M Y H:i:s T', getlastmod());
}

function is_ssl()
{
	return isset($_SERVER['HTTPS']) && isset($_SERVER['SSL']) || isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR']) ? true : false;
}

function complementary($hsla)
{
	if (list ($h, $s, $l, $a) = explode(',', str_replace([' ', 'hsla', '(', ')', '%'], '', $hsla)))
	{
		if (10 === (int)$l) $l = 100;
		return 'hsla('. ($h += (180 < $h) ? -180 : 180). ', '. $s. '%, '. $l. '%, '. $a. ')';
	}
}

function get_hsl($colour)
{
	if ('h' === $colour[0])
		if (list ($h, $s, $l) = explode(',', str_replace(['hsl', '(', ')', '%'], '', $colour))) return [$h, $s, $l];
	if ('r' === $colour[0])
		list ($r, $g, $b) = explode(',', str_replace(['rgb', '(', ')'], '', $colour));
	if ('#' === $colour[0])
	{
		$colour = ltrim($colour, '#');
		if (3 === strlen($colour))
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
	if (isset($r, $g, $b))
	{
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
}

function get_dirs($dir, $nosort=true)
{
	if ($dirs = glob($dir. '/'. (is_admin() || is_subadmin() ? '*' : '[!!]*'), !$nosort ? GLOB_ONLYDIR : GLOB_ONLYDIR + GLOB_NOSORT))
	{
		foreach($dirs as $dir_names) if ($dir_names) $all_dirs[] = basename($dir_names);
		if (isset($all_dirs)) return $all_dirs;
	}
}

function get_summary($file)
{
	global $summary_length, $encoding, $n, $ellipsis;
	ob_start();
	echo preg_replace('/<script.*?\/script>/s', '', file_get_contents($file));
	$text = strip_tags(ob_get_clean());
	$text = str_replace("\t", '', $text);
	$text = str_replace([$n. $n. $n, $n. $n], $n, $text);
	$text = mb_strimwidth($text, 0, $summary_length, $ellipsis, $encoding);
	return trim($text);
}

function get_description($str)
{
	global $description_length, $encoding, $line_breaks, $ellipsis;
	$text = strip_tags(preg_replace('/<script.*?\/script>/s', '', $str));
	$text = str_replace("\t", '', $text);
	$text = str_replace($line_breaks, '', $text);
	$text = mb_strimwidth($text, 0, $description_length, $ellipsis, $encoding);
	return h(trim($text));
}

function get_categ($str)
{
	return basename(dirname(dirname($str)));
}

function get_title($str)
{
	$title = basename(dirname($str));
	if ('contents' === $title) $title = basename($str, '.html');
	return $title;
}

function get_page($nr)
{
	global $url, $get_categ, $get_title, $page_name, $current_url, $query, $fquery, $download_contents, $forum, $forum_thread;
	if ($get_categ && $get_title)
		return $current_url. '&amp;pages='. $nr;
	elseif ($get_categ && !$get_title)
		return $url. $get_categ. (!$query ? '' : '?query='. $query). '&amp;pages='. $nr;
	elseif ($query && 1 !== $fquery)
		return $url. '?query='. $query. '&amp;pages='. $nr;
	elseif ($page_name === $download_contents)
		return $url. r($download_contents). '&amp;pages='. $nr;
	elseif ($page_name === $forum)
		return $url. r($forum). (!$forum_thread ? '' : '&amp;thread='. r($forum_thread)). (!$fquery? '' : '?fquery='. $fquery). '&amp;pages='. $nr;
	else
		return $url. '?pages='. $nr;
}

function ht($str)
{
	return h(basename($str));
}

function social($t, $u)
{
	global $aside, $sidebox_order, $sidebox_title, $social_medias, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class, $n;
	if ($social_medias)
	{
		$aside .=
		'<div id=social class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[2]. '">'. $n.
		'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[7]. '</div>'. $n.
		'<div class="'. $sidebox_content_class[3]. '">'. $n;
		$social_link = include 'socials.php';
		foreach($social_medias as $social_name)
			$aside .= $social_link[$social_name]. $n;
		$aside .= '</div></div>'. $n;
	}
}

function permalink($t, $u)
{
	global $aside, $sidebox_order, $sidebox_title, $permalink, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class, $n;
	$aside .=
	'<div id=permalink class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[3]. '">'. $n.
	'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[8]. '</div>'. $n.
	'<div class="'. $sidebox_content_class[3]. '">'. $n.
	'<div class="input-group input-group-sm mb-3">'. $n.
	'<div class=input-group-prepend><label class=input-group-text for=html>'. $permalink[0]. '</label></div>'. $n.
	'<input readonly onclick="this.select()" id=html type=text class=form-control value="&lt;a href=&quot;'. $u. '&quot; target=&quot;_blank&quot;&gt;'. htmlentities(h($t)). '&lt;/a&gt;">'. $n.
	'</div>'. $n.
	'<div class="input-group input-group-sm mb-3">'. $n.
	'<div class=input-group-prepend><label class=input-group-text for=wiki>'. $permalink[1]. '</label></div>'. $n.
	'<input readonly onclick="this.select()" id=wiki type=text class=form-control value="['. $u. ' '. htmlentities(h($t)). ']">'. $n.
	'</div>'. $n.
	'<div class="input-group input-group-sm mb-2">'. $n.
	'<div class=input-group-prepend><label class=input-group-text for=forum>'. $permalink[2]. '</label></div>'. $n.
	'<input readonly onclick="this.select()" id=forum type=text class=form-control value="[URL='. $u. ']'. htmlentities(h($t)). '[/URL]">'. $n.
	'</div>'. $n.
	'</div>'. $n.
	'</div>'. $n;
}

function a($uri, $name='', $target='_blank', $class='', $title='', $position='')
{
	return
	'<a href="'. $uri. '" target="'. $target. '"' .
	($class ? ' class="'. $class. '"' : '') .
	($title ? ' data-toggle="tooltip" title="'. $title. '" data-html="true"' : '') .
	($position ? ' data-placement="'. $position. '"' : '') .
	('_blank' === $target ? ' rel="noopener noreferrer"' : ''). '>' .
	(!$name ? h($uri) : h($name)) .
	'</a>';
}

function img($src, $class='', $show_exif_comment=false, $per=1)
{
	global $url, $source, $n, $classname, $get_categ, $get_title, $index_type, $get_page, $use_thumbnails, $use_categ_thumbnails, $line_breaks, $use_datasrc;
	if ($extension = get_extension($src))
	{
		$image_extensions = ['.gif', '.jpg', '.jpeg', '.png', '.svg'];
		$video_extensions = ['.mp4', '.ogg', '.webm'];
		if ($src_scheme = strpos($src, '://')) $addr = parse_url($src);
		$data = !$use_datasrc ? '' : 'data-';
		if (false !== array_search($lower_ext = strtolower($extension), $image_extensions))
		{
			$alt = h(basename($src));
			$exif = @exif_read_data($src, '', '', true);
			$exif_thumbnail = isset ($exif['THUMBNAIL']['THUMBNAIL']) ? $exif['THUMBNAIL']['THUMBNAIL'] : '';
			$exif_comment = !$show_exif_comment ? '' : (isset($exif['COMMENT']) && 'png' !== $lower_ext ? h(strip_tags($exif['COMMENT'][0])) : h(strip_tags(get_png_tEXt($src))));
			list ($width, $height, $type, $attr) = @getimagesize($src);
			if ($exif_thumbnail) list ($width_sm, $height_sm, $type_sm, $attr_sm) = getimagesizefromstring($exif_thumbnail);
			$img = $exif_comment ?
			'<figure class="align-top img-thumbnail text-center d-inline-block" style="max-width:'. $width. 'px">'. $n.
			'<img class="img-fluid '. $class. '" '. $data. 'src="'. $url. r($src). '" alt="'. $alt. '" '. $attr. '>'. $n.
			'<p class="text-center wrap my-2">'. $exif_comment. '</p>'. $n.
			'</figure>'. $n :
			'<img class="align-top img-fluid img-thumbnail '. $class. '" '. $data. 'src="'. $url. r($src). '" alt="'. $alt. '" '. $attr. '>'. $n;
			if ($get_title || $get_page)
			{
				if (false !== $src_scheme)
					return
					'<figure class="img-thumbnail text-center d-inline-block '. $class. '" style="max-width:'. $width. 'px">'. $n.
					'<a data-fancybox=gallery href="'. $src. '">'. $n.
					'<img class=img-fluid '. $data. 'src="'. $addr['scheme']. '://'. $addr['host']. r($addr['path']). '" alt="'. $alt. '" '. $attr. '>'. $n.
					'</a>'. $n.
					'<small class="blockquote-footer my-2 text-right">'. $n.
					'<a href="'. $addr['scheme']. '://'. $addr['host']. '/" target="_blank" rel="noopener noreferrer">'. sprintf($source, h($addr['host'])). '</a>'. $n.
					'</small>'. $n.
					'</figure>'. $n;
				elseif ($exif_comment && !$exif_thumbnail)
					return
					'<figure class="align-top img-thumbnail text-center d-inline-block" style="max-width:'. $width. 'px">'. $n.
					'<a data-fancybox=gallery class="d-inline-block mb-2 mr-1" data-caption="'. $exif_comment. '" href="'. $url. r($src). '">'.
					'<img class="align-top img-fluid '. $class. '" '. $data. 'src="'. $url. r($src). '" alt="'. $alt. '" '. $attr. '>'.
					'</a>'. $n.
					'<figcaption class="text-center mb-2 wrap">'. $exif_comment. '</figcaption>'. $n.
					'</figure>'. $n;
				else
					return
					'<figure class="d-inline-block m-2">'. $n.
					'<a data-fancybox=gallery href="'. $url. r($src). '" data-caption="'. $exif_comment. '">'. $n.
					($exif_thumbnail && $use_thumbnails ?
					'<img class="'. $class. ' align-top img-thumbnail" '. $data. 'src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '" '. $attr_sm. '>' :
					$img).
					'</a>'. $n.
					'</figure>'. $n;
			}
			else
			{
				if ($exif_thumbnail && $use_categ_thumbnails)
				{
					if ($get_categ || 1 === $index_type)
						$img =
						'<span class="card-header d-block text-center">'. $n.
						'<img class="img-fluid card-img-top '. $class. '"'. $data. 'src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '" '. $attr_sm. '>'. $n.
						'</span>';
					else
						$img =
						'<span'. ($classname ? ' class="d-block '. $classname. ' position-relative" style="max-width:'. $width_sm. 'px"' : ''). '>'. $n.
						'<img class="img-fluid '. $class. '" '. $data. 'src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '" '. $attr_sm. '>'. $n.
						'</span>';
				}
				else
					$img =
					'<span'. ($classname ? ' class="d-block '. $classname. ' position-relative" style="max-width:'. $width. 'px"' : ''). '>'. $n.
					'<img class="img-fluid '. (1 === $index_type ? 'card-img-top ' : ''). $class. '" '. $data. 'src="'. $url. r($src). '" alt="'. $alt. '" width="'. ($width * $per). '" height="'. ($height * $per). '">'. $n.
					'</span>';

				return '<a href="'. $url. r(basename(dirname($dirname = dirname(dirname($src)))). '/'. basename($dirname)). '">'. $img. '</a>';
			}
		}
		elseif (false !== array_search(strtolower($extension), $video_extensions))
		{
			$vtt = str_replace($extension, '.vtt', $src);
			if ($get_title || $get_page)
			{
				if (false !== $src_scheme)
					return
					'<figure class="align-top img-thumbnail text-center d-inline-block '. $class. '">'. $n.
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
				return '<video class="align-top w-100 '. $class. '" controls preload=none><source src="'. $url. r($src). '"><track src="'. $url. r($vtt). '" default=default></video>'. $n;
		}
	}
}

function timeformat($time, array $intervals)
{
	$now = new DateTime;
	$ago = new DateTime('@'. $time);
	$diff = $now->diff($ago);
	$diff->w = floor($diff->d/7);
	$diff->d -= $diff->w*7;
	foreach ($intervals as $k => &$v)
	{
		if ($diff->$k)
			$v = $diff->$k. $v;
		else
			unset($intervals[$k]);
	}
	return implode(array_slice($intervals, 0, 1));
}

function pager(int $num, int $max)
{
	global $number_of_pager, $article, $nav_laquo, $nav_raquo, $pager_wrapper, $n;
	$article .= '<ul class="pagination '. $pager_wrapper. '">'. $n;

	if(2 < $num)
		$article .= '<li class=page-item><a class=page-link href="'. get_page(1). '">'. $nav_laquo. $nav_laquo. '</a></li>';
	if (1 < $num)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($num-1). '">'. $nav_laquo. '</a></li>'. $n;

	$i = 1;
	while ($i <= $number_of_pager)
	{
		$half_page = $number_of_pager/2;
		$ceil = (int)ceil($half_page);
		if (0 > $num - $ceil)
		{
			if ($num === $i)
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

			if ($num === $j)
				$article .= '<li class="active page-item"><a class=page-link>'. $num. '</a></li>'. $n;
			else
				$article .= '<li class=page-item><a class=page-link href="'. get_page($j). '">'. $j. '</a></li>'. $n;
		}
		else
		{
			if ($i === $ceil)
				$article .= '<li class="disable active page-item"><a class=page-link>'. $num. '</a></li>'. $n;
			else
			{
				$j = $num - $ceil + $i;
				$article .= '<li class=page-item><a class=page-link href="'. get_page($j). '">'. $j. '</a></li>'. $n;
			}
		}
		if ($max === $i) break;
		++$i;
	}
	if ($max > $num)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($num+1). '">'. $nav_raquo. '</a></li>'. $n;
	if ($num < $max - 1)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($max). '">'. $nav_raquo. $nav_raquo. '</a></li>'. $n;
	$article .= '</ul>'. $n;
}

function sideless($hide=false, $force=false)
{
	global $stylesheet, $get_title, $get_page, $template;
	if ($get_title || $get_page || $force)
	{
		if ($hide)
			$stylesheet .= '#main,#main>article,#main>div,#main.col-lg-8,#main.col-lg-9{margin:0!important;padding:0!important;max-width:100%!important;flex:0 0 100%}#main>header{margin:0}'. ('lightside' === $template ? '' : '#side{display:none!important}');
		else
			$stylesheet .= '#main,#side{max-width:inherit;flex:0 0 100%}';
	}
}

function nowrap()
{
	global $stylesheet;
	$stylesheet .= '.article,header .wrap{white-space:normal}';
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

function get_logo($name=false, $class='')
{
	global $site_name, $url;
	if (!is_file($logo = 'images/logo.svg') && !is_file($logo = 'images/logo.png'))
		return $site_name;
	else
		return '<img src="'. $url. $logo. '" class=img-fluid alt="'. $site_name. '">'.
		(!$name ? '' : '<span class="'. (!$class ? 'border-0 d-block h1 mt-4' : $class). '">'. $site_name. '</span>');
}


function not_found()
{
	global $article, $breadcrumb, $header, $site_name, $not_found, $h1_title, $n;
	http_response_code(404);
	$header .= '<title>'. $not_found[0]. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .= '<li class="breadcrumb-item active">'. http_response_code(). '</li>'. $n;
	$article .=
	'<h1 class="'. $h1_title[0]. '">'. $not_found[0]. '</h1>'. $n.
	'<div class="'. $h1_title[1]. ' not-found">'. $not_found[1]. '</div>'. $n;
}

function toc($in_article=false)
{
	global $article, $aside, $javascript, $sidebox_title, $sidebox_order, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class, $get_title, $get_page;
	if ($get_title || $get_page)
	{
		$toc_content = '<div class="'. $sidebox_title_class[4]. '">'. $sidebox_title[9];
		if ($in_article)
			$toc_content .= '<button class="navbar-toggler btn-sm" data-toggle=collapse data-target=#toctoggle accesskey=p tabindex=0><span class=navbar-toggler-icon></span></button>';
		$toc_content .= '</div>';
		$toc_content .= '<div id=toctoggle class="'. $sidebox_wrapper_class[1]. (!$in_article ? '' : ' collapse pb-3'). '"></div>';

		if ($in_article)
			$article .= '<div id=toc class="'. $sidebox_wrapper_class[2]. '">'. $toc_content. '</div>';
		else
			$aside .= '<div id=toc class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[8]. '">'. $toc_content. '</div>';

		$javascript .= 'let num=1,toc="",toclv=lv=0;$(".article :header").each(function(){this.id="toc"+num;tag=this.nodeName.toLowerCase();num++;if(tag==="h2")lv=1;else if(tag==="h3")lv=2;else if(tag==="h4")lv=3;else if(tag==="h5")lv=4;else if(tag==="h6")lv=5;while(toclv<lv){toc+="<ul>";toclv++}while(toclv>lv){toc+="<\/ul>";toclv--}toc+="<li><a class=\"'. $sidebox_content_class[4]. '\" href=\"#"+this.id+"\" title=\""+$(this).text()+"\">"+$(this).text()+"<\/a><\/li>"});while(toclv>0){toc+="<\/ul>";toclv--}$("#toctoggle").html(toc);';
	}
}

function unsession()
{
	if (isset($_SESSION['l']))
	{
		global $session_name;
		session_unset();
		setcookie(session_name(), '', 1);
		session_destroy();
		session($session_name);
	}
}

function session($session_name)
{
	if (!isset($_SESSION[$session_name]))
	{
		global $dir, $server;
		session_name($session_name);
		session_set_cookie_params(
		[
			'lifetime' => 86400,
			'path' => '/' === $dir ? '/' : $dir. '/',
			'domain' => $server,
			'secure' => is_ssl(),
			'httponly' => true,
			'samesite' => 'Strict',
		]);
		session_start();
		session_regenerate_id(true);
	}
}

function get_uri($uri, $get)
{
	if (false !== strpos($uri, '%23') || false !== strpos($uri, '%26'))
		return $uri;
	else
		return basename(filter_input(INPUT_GET, $get, FILTER_SANITIZE_ENCODED));
}

function sort_time($a, $b)
{
	return filemtime($b) - filemtime($a);
}

function sort_name($a, $b)
{
	return explode_delimiter($a, 0) < explode_delimiter($b, 0);
}

function explode_delimiter($str, int $i)
{
	global $delimiter;
	return explode($delimiter, basename($str))[$i];
}

function get_extension($f)
{
	$info = pathinfo($f);
	if (isset($info['extension'])) return '.'. $info['extension'];
}

function enc($str)
{
	global $session_txt;
	if ($str && is_file($session_txt))
		return trim(strtr(base64_encode(openssl_encrypt(serialize(basename($str)), 'AES-256-CBC', hash('whirlpool', file_get_contents($session_txt)), OPENSSL_RAW_DATA, substr(sha1_file($session_txt), 5, 16))), '+/', '-_'), '=');
}

function dec($str)
{
	global $session_txt;
	if ($str && is_file($session_txt))
		return @unserialize(rtrim(openssl_decrypt(base64_decode(strtr(basename($str), '-_', '+/')), 'AES-256-CBC', hash('whirlpool', file_get_contents($session_txt)), OPENSSL_RAW_DATA, substr(sha1_file($session_txt), 5, 16)), "\0"));
}

function sess_err($str)
{
	global $aside, $javascript, $login_try_again, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class, $ticket_warning, $​ask_admin, $n;
	$aside .=
	'<div id=login class="'. $sidebox_wrapper_class[0]. '">'. $n.
	'<div class="'. $sidebox_title_class[3]. '">'. $str. '</div>'. $n.
	'<p class="'. $sidebox_content_class[3]. '">'. ($str === $ticket_warning[3] ? $​ask_admin : $login_try_again). '</p>'. $n.
	'</div>'. $n;
	$javascript .= 'location.hash="login";';
}

function handle($dir)
{
	if (filter_var($usermail = dec($userstr = basename(dirname($dir))), FILTER_VALIDATE_EMAIL))
	{
		global $mail_address;
		if (is_file($handle = $dir. 'handle') && filesize($handle))
			$handle = h(file_get_contents($handle));
		else
			$handle = h(explode('@', $usermail)[0]);
		return $handle;
	}
}

function avatar($dir, $size=100)
{
	if (is_file($img = $dir. 'avatar') && filesize($img) && false !== strpos($base64_img = file_get_contents($img), 'base64'))
		$avatar = '<img'. (!$size ? '' : ' style="object-fit:cover;width:'. $size. 'px;height:'. $size. 'px"'). ' src="'. strip_tags($base64_img). '" class="align-text-bottom d-inline-block rounded-circle mx-auto" alt="">';
	elseif (is_file($bgcolor = $dir. '/bgcolor') && filesize($bgcolor))
		$avatar = '<span style="'. (!$size ? '' : 'font-size:'. ($size * 70 / 100). 'px;width:'. $size. 'px;height:'. $size. 'px;'). 'background-color:'. h(file_get_contents($bgcolor)). '" class="d-inline-flex justify-content-center font-weight-bold rounded-circle mx-auto text-center text-white">'. mb_substr(handle($dir), 0, 1). '</span>';
	else
	{
		global $color;
		$avatar = '<span style="background-color:'. ($color ? hsla($color, 5.5, -7,.9) : 'rgba(0,0,0,.5)'). ';'. (!$size ? '': 'font-size:'. ($size * 70 / 100). 'px;width:'. $size. 'px;height:'. $size. 'px;'). '" class="d-inline-flex justify-content-center font-weight-bold rounded-circle mx-auto text-center text-white">'. mb_substr($dir, 0, 1). '</span>';
	}
	if (100 <= $size)
	{
		global $mail_address, $admin_suffix;
		if ($mail_address === dec(basename(dirname($dir)))) $avatar .= '<div class="badge badge-pill badge-primary my-2 mx-auto" style="width:'. mb_strlen($admin_suffix[0]). 'rem;display:inherit">'. $admin_suffix[0]. '</div>';
		elseif (is_file($dir. 'subadmin')) $avatar .= '<div class="badge badge-pill badge-success my-2 mx-auto" style="width:'. mb_strlen($admin_suffix[1]). 'rem;display:inherit">'. $admin_suffix[1]. '</div>';
	}
	return $avatar;
}

function flow($a, $b, $c, $d)
{
	return
	'<h3>'. $a[0]. '</h3>'.
	'<ol class="flow list-unstyled d-flex justify-content-between my-4">'.
	'<li class="w-25 pt-3 border-top border-10 '. (1 === $d ? 'border-success' : 'border-light'). '">'. sprintf($a[1], $b, $c). '</li>'.
	'<li class="w-25 pt-3 mx-5 border-top border-10 '. (2 === $d ? 'border-success' : 'border-light'). '">'. sprintf($a[2], $c). '</li>'.
	'<li class="w-25 pt-3 mr-5 border-top border-10 '. (3 === $d ? 'border-success' : 'border-light'). '">'. sprintf($a[3], $b). '</li>'.
	'<li class="w-25 pt-3 border-top border-10 '. (4 === $d ? 'border-success' : 'border-light'). '">'. sprintf($a[4], $c). '</li>'.
	'</ol>';
}

function counter($txt, $put=false)
{
	if (is_file($txt) && is_writeable($txt))
	{
		$counter = (int)file_get_contents($txt);
		++$counter;
		file_put_contents($txt, $counter, LOCK_EX);
		return $counter;
	}
	elseif ($put)
		return file_put_contents($txt, 1, LOCK_EX);
}

function is_permitted($dir)
{
	return is_dir($dir) && '700' === substr(decoct(fileperms($dir)), 2) ? 0 : 1;
}

function hs($s)
{
	$s = str_replace("\t", '    ', h($s));
	foreach (['autofocus', 'disabled', 'multiple', 'required', 'selected'] as $o)
		if (false !== strpos($s, $o)) $s = str_replace($o, '<span style="color:#44AA00">'. $o. '</span>', $s);
	if (false !== strpos($s, '&amp;nbsp;')) $s = str_replace('&amp;nbsp;', '<span style="color:#888A85">&amp;nbsp;</span>', $s);
	if (false !== strpos($s, '=')) $s = preg_replace('/(?!!|=)([\w-]+) ?= ?(&quot;|&#039;)/is', '<span style="color:#44AA00">\\1</span>=\\2', $s);
	if (false !== strpos($s, '&lt;') && false !== strpos($s, '&gt;')) $s = preg_replace('/&lt;(?!!--)(.*?)&gt;/s', '<span style="color:#5F8DD3">&lt;\\1&gt;</span>', $s);
	if (false !== strpos($s, '&#039;')) $s = preg_replace_callback('/&#039;(.*?)&#039;/s', function ($t){return '&#039;<span style="color:#FD3301">' . strip_tags($t[1]) . '</span>&#039;';}, $s);
	if (false !== strpos($s, '&quot;')) $s = preg_replace_callback('/&quot;(.*?)&quot;/s', function ($t){return '&quot;<span style="color:#FD3301">' . strip_tags($t[1]) . '</span>&quot;';}, $s);
	if (false !== strpos($s, '&lt;script')) $s = preg_replace_callback('/(&lt;script.*?&gt;)(.*?)(&lt;\/script&gt;)/is', function ($t){return $t[1] . '<span style="color:#888A85">' . strip_tags($t[2]) . '</span>' . $t[3];}, $s);
	if (false !== strpos($s, '&lt;style')) $s = preg_replace_callback('/(&lt;style.*?&gt;)(.*?)(&lt;\/style&gt;)/is', function ($t){return $t[1] . '<span style="color:#888A85">' . strip_tags($t[2]) . '</span>' . $t[3];}, $s);
	if (false !== strpos($s, '「')) $s = preg_replace_callback('/(「)(.*?)(」)/is', function ($t){return $t[1] . '<strong>' . strip_tags($t[2]) . '</strong>' . $t[3];}, $s);
	if (false !== strpos($s, '『')) $s = preg_replace_callback('/(『)(.*?)(』)/is', function ($t){return $t[1] . '<strong>' . strip_tags($t[2]) . '</strong>' . $t[3];}, $s);
	if (false !== strpos($s, '【')) $s = preg_replace_callback('/(【)(.*?)(】)/is', function ($t){return $t[1] . '<strong>' . strip_tags($t[2]) . '</strong>' . $t[3];}, $s);
	if (false !== strpos($s, '&lt;?')) $s = preg_replace_callback('/(&lt;\?.*?\?&gt;)/is', function ($t){return highlight_string(html_entity_decode(strip_tags($t[1]), ENT_QUOTES), true);}, $s);
	if (false !== strpos($s, '['))
	{
		$s = preg_replace_callback('/\[url=?(http.*?)?\](.*?)\[\/url\]/i', function ($t)
		{
			if (!$t[1]) return '<a href="'. $t[2]. '" target="_blank" rel="noopener noreferrer">'. $t[2]. '</a>';
			else return '<a href="'. $t[1]. '" target="_blank" rel="noopener noreferrer">'. $t[2]. '</a>';
		}, $s);
	}
	if (false !== strpos($s, '/*')) $s = preg_replace_callback('|(/\*.*?\*/)|s', function ($t){return '<span style="color:#FF7F2A">' . strip_tags($t[1]) . '</span>';}, $s);
	if (false !== strpos($s, '&lt;!--')) $s = preg_replace_callback('/(&lt;!--.*?--&gt;)/s', function ($t){return '<span style="color:#FF7F2A">' . strip_tags($t[1]) . '</span>';}, $s);
	if (false !== strpos($s, '//')) $s = preg_replace_callback('|(?<![:(>&quot;&#039;])(//.*?&#10;)|is', function ($t){return '<span style="color:#FF7F2A">' . strip_tags($t[1]) . '</span>';}, $s);
	return $s;
}

function blacklist($email, $blacklist = './forum/#blacklist.txt')
{
	$list = file($blacklist, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if (!in_array($email, $list, true)) return $email;
}

function booking(
	$bookings_per_cell = 2,
	$accepts_per_person = 2,
	$term = '1 week',
	$start_hour = '10:00am',
	$end_hour = '4:00pm',
	$interval = '1 hour',
	$denial_week = ['', ''],
	$denial_day = ['', ''],
	$denial_time = ['12:00', ''],
	$cancel = false,
	$sideless = false
){
	global $accepting, $btn, $current_url, $denial_attrs, $javascript, $stylesheet, $line_breaks, $mail_address, $n, $session_usermail, $week;
	if ($sideless) sideless(0, 1);
	$first_day = strtotime('today');
	$last_day = strtotime("today + $term");
	$start_time = strtotime($start_hour) - $first_day;
	$end_time = strtotime($end_hour) - $first_day;
	$time_range = strtotime($interval) - time();
	$hours = range(strtotime($start_hour), strtotime($end_hour), $time_range);
	$days = range($first_day, $last_day, 86400);
	if (!is_dir($bookings_dir = './bookings/')) mkdir($bookings_dir, 0757);
	if (isset($_SESSION['l']) && filter_has_var(INPUT_POST, 'date') && filter_has_var(INPUT_POST, 'booking'))
	{
		$date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_NUMBER_INT);
		$booking = trim(filter_input(INPUT_POST, 'booking'));
		$booking = filter_var($booking, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_LOW);
		$booking = enc(str_replace($line_breaks, '&#10;', $booking));
		if (!is_dir($date_dir = $bookings_dir. $date. '/')) mkdir($date_dir, 0757);
		file_put_contents($booking_txt = $date_dir. $_SESSION['l']. '.txt', $booking, LOCK_EX);
		if (!$booking && is_file($booking_txt)) unlink($booking_txt);
		header('Location: '. $current_url. '#booking');
	}
	$stylesheet .= '.a{opacity:1!important;visibility:visible!important}.b{opacity:0;visibility:hidden}.c{opacity:.8;cursor:not-allowed}.g{background:#3ddb80}.h{cursor:pointer;text-align:center;width:5%}.i{background:mediumseagreen;border:none;bottom:0;cursor:pointer;opacity:0;padding:5px 10px;position:absolute;right:0;text-align:center;transition:opacity .1s,visibility .1s;visibility:hidden}[data-placeholder]:empty:before{content:attr(data-placeholder);display:block;text-align:center}[contenteditable=false][data-placeholder]:empty:before{opacity:.8;cursor:not-allowed}[contenteditable=true][data-placeholder]:empty:before{cursor:pointer}#booking td{position:relative}#booking th,#booking td{padding:.8em .6em}#booking th:empty{width:5em}#booking th{background:dimgray;color:#fff;font-weight:inherit;vertical-align:middle;white-space:nowrap}#booking td div{word-wrap:break-word;white-space:pre-wrap}.g:hover{opacity:.8}#booking tr:hover,#booking tr:nth-child(odd){filter:brightness(105%)}@media print{*,a{color:#000000!important;font-size:8pt!important;opacity:1!important}aside,#side,footer,form,header,nav,p,.breadcrumb{display:none!important}html,body{background:#ffffff!important;margin:0!important;padding:0!important}th,td{background:#ffffff!important}th{white-space:nowrap!important}}';

	if (is_admin() || is_subadmin())
	{
		global $url, $long_time_format, $reminder, $remind_header, $separator;
		if (filter_has_var(INPUT_POST, 'remind-addr') && filter_has_var(INPUT_POST, 'remind-date') && filter_has_var(INPUT_POST, 'remind-title') && filter_has_var(INPUT_POST, 'remind-contents'))
		{
			global $mime, $encoding, $site_name;
			$to = filter_input(INPUT_POST, 'remind-addr', FILTER_SANITIZE_STRING);
			$subject = filter_input(INPUT_POST, 'remind-title', FILTER_SANITIZE_STRING);
			$body = sprintf(filter_input(INPUT_POST, 'remind-contents', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'remind-date', FILTER_SANITIZE_STRING));
			$body .= $n. $n. $separator. $n. $site_name. $n. $url;
			$headers = $mime. 'From: '. $site_name. '<'. $mail_address. '>'. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n;
			mail($to, $subject, $body, $headers);
		}
		if (filter_has_var(INPUT_POST, 'del') && is_file($file = filter_input(INPUT_POST, 'del'))) unlink($file);
		if ($g = glob($bookings_dir. '*/*.txt'))
		{
			echo
			'<div class="card nowrap mb-4">', $n,
			'<div class=card-header>', $reminder[0], '</div>', $n,
			'<div class=card-body>', $n,
			'<pre contenteditable=true id=title>', $reminder[1], '</pre>', $n,
			'<pre contenteditable=true id=contents>', $reminder[2], '</pre>', $n,
			'</div>', $n,
			'</div>', $n,
			'<table class="small w-100" id=booking>', $n,
			'<tr><th class=bg-danger></th><th>', $remind_header[0], '</th><th>', $remind_header[1], '</th><th>', $remind_header[2], '</th><th>', $remind_header[3], '</th></tr>', $n;
			foreach ($g as $remind)
			{
				if (is_file($remind))
				{
					list ($remind_y, $remind_m, $remind_d, $remind_h, $remind_i) = explode('-', basename(dirname($remind)));
					if ($reserved_mail = dec($user = basename($remind, '.txt')))
					{
						$reserved_time = sprintf($long_time_format, $remind_y, $remind_m, $remind_d, $remind_h, $remind_i);
						echo
						'<tr>', $n,
						'<td class="bg-danger text-white h" onclick="if(!confirm(\'', sprintf($reminder[5], $reserved_time), '\'))return false;d(\''. $remind. '\')">', $btn[4], '</td>', $n,
						'<th class=w-25>', $reserved_time, '</th>', $n,
						'<td><a href="', $url, '?user=', str_rot13($user), '">', handle($user. '/prof'), '</a></td>', $n,
						'<td><div>', dec(file_get_contents($remind)), '</div></td>', $n,
						'<td class="g text-white h" onclick="if(!confirm(\'', sprintf($reminder[3], $reserved_mail), '\'))return false;a(\'', $reserved_mail,'\',\'', sprintf($long_time_format, $remind_y, $remind_m, $remind_d, $remind_h, $remind_i), '\',$(\'#title\').text().replace(/^\s+|\s+$/g,\'\'),d.getElementById(\'contents\').innerText.replace(/^\s+|\s+$/g,\'\'))">', $btn[1], '</td>', $n,
						'</tr>', $n;
					}
				}
			}
			echo '</table>', $n;
			$javascript .= 'function a(b,c,d,e){const f=new FormData();f.append("remind-addr",b);f.append("remind-date",c);f.append("remind-title",d);f.append("remind-contents",e);const x=new XMLHttpRequest();x.open("post","'. $current_url. '");x.send(f);x.addEventListener("loadend",function(){if(x.status===200){d.querySelector("table").insertAdjacentHTML("beforebegin","<p style=\"background:#3ddb80;color:#ffffff;margin:0;padding:1em 2em\" id="+b+">'. sprintf($reminder[4], '"+b+"').'<\/p>")}});setTimeout(function(){d.getElementById(b).remove()},5000)}function d(f){$.post("'. $current_url. '",{"del":f},function(data,status){if(status==="success"){let c=$(data).find("#booking").html();$("#booking").html(c)}})}';
		}
		else
		{
			global $booking_msg;
			echo '<table class="small w-100" id=booking><tr><th class=bg-danger>', $booking_msg[4], '</th></tr></table>', $n;
		}
	}
	else
	{
		global $booking_msg, $short_time_format;
		echo '<table id=booking class="small w-100">', $n;
		if (isset($_SESSION['l']))
		{
			$accepted = count(glob($bookings_dir. '*/'. $_SESSION['l']. '.txt', GLOB_NOSORT));
			if ($accepted === $accepts_per_person)
				echo '<caption class="bg-success px-3 py-2 text-right text-white">', sprintf($booking_msg[0], $accepted), '</caption>', $n;
			elseif (0 === $accepted)
				echo '<caption class="bg-primary px-3 py-2 text-right text-white">', sprintf($booking_msg[2], $accepts_per_person), '</caption>', $n;
			else
				echo '<caption class="bg-info px-3 py-2 text-right text-white">', sprintf($booking_msg[1], $accepted), '</caption>', $n;
		}
		else
			echo '<caption class="bg-warning px-3 py-2 text-right text-white">', $booking_msg[3], '</caption>', $n;

		echo
		'<thead class=text-center>', $n,
		'<tr>', $n,
		'<th></th>', $n;
		if (2 === count($hours))
		{
			global $meridian;
			echo '<th>', $meridian[0], '</th><th>', $meridian[1], '</th>';
		}
		else
		{
			foreach ($hours as $h)
			{
				$hi = date('H:i', $h);
				echo '<th', (!in_array($hi, $denial_time, true) ? '' : $denial_attrs),'>', $hi, '</th>', $n;
			}
		}
		echo
		'</tr>', $n,
		'</thead>', $n,
		'<tbody>', $n;
		foreach ($days as $d)
		{
			$times = range($d+$start_time, $d+$end_time, $time_range);
			$w = date('w', $d);
			echo
			'<tr>', $n,
			'<th', (!in_array($week[$w], $denial_week, true) && !in_array(date('Y-m-d', $d), $denial_day, true) ? '' : $denial_attrs), '>', sprintf($short_time_format, date('n', $d), date('j', $d), $week[$w]), '</th>', $n;
			foreach ($times as $t)
			{
				$time_dir = date('Y-m-d-H-i', $t);
				$received = $bookings_per_cell - count(glob($bookings_dir. $time_dir. '/*.txt', GLOB_NOSORT));
				$percent = round($received/$bookings_per_cell*100). '%';
				$is_denial_week = in_array($week[$w], $denial_week, true);
				$is_denial_day = in_array(date('j', $t), $denial_day, true);
				$is_denial_time = in_array(date('H:i', $t), $denial_time, true);
				if (isset($_SESSION['l']))
				{
					if (is_file($f = $bookings_dir. $time_dir. '/'. $_SESSION['l']. '.txt'))
					{
						$books = str_replace($line_breaks, '&#10;', dec(file_get_contents($f)));
						$attr = ' contenteditable="'. (($t < time()) && !$cancel ? 'false' : 'true'). '"';
						$abtn = ($t < time()) && !$cancel ? '>' : ' onclick="this.lastElementChild.focus()"><a class=i onclick="a(this.nextElementSibling.innerText,\''. $time_dir. '\');this.className=\'i b\'">'. $btn[2]. '</a>';
					}
					else
					{
						$books = '';
						$attr = ' contenteditable="'. ($accepted >= $accepts_per_person ? 'false' : 'true'). '" data-placeholder="'. sprintf($accepting, $received). '"';
						$abtn = ($accepted >= $accepts_per_person) ? '>' : ' onclick="this.lastElementChild.focus()"><a class=i onclick="a(this.nextElementSibling.innerText,\''. $time_dir. '\');this.className=\'i b\'">'. $btn[2]. '</a>';
					}
				}
				else
				{
					$books = '';
					$attr = ' data-placeholder="'. sprintf($accepting, $received). '"';
					$abtn = '>';
				}
				echo '<td', (isset($f) && is_file($f) || $t > time() && $received > 0 && !$is_denial_week && !$is_denial_day && !$is_denial_time ?
				' style="background:hsl(210,'. $percent. ',70%);color:#ffffff"'. $abtn. '<div'. $attr. '>' : '><div'. (!$books ? $denial_attrs : ' contenteditable=true style="opacity:.6"'). '>'), $books, '</div></td>', $n;
				$a[] = is_dir($e = $bookings_dir. $time_dir) ? $e : '';
			}
			echo '</tr>', $n;
		}
		echo
		'</tbody>', $n,
		'</table>', $n;
		$javascript .= (isset($_SESSION['l']) ? 'd.querySelectorAll("[contenteditable]").forEach(n=>{new MutationObserver(r=>{n.previousElementSibling.setAttribute("class","i a")}).observe(n,{childList:true})});function a(y,z){const f=d.createElement("form");f.style.display="none";f.method="post";let g=d.createElement("input"),h=d.createElement("textarea");g.name="date",h.name="booking",h.value=y,g.value=z;f.appendChild(g);f.appendChild(h);d.body.appendChild(f);f.submit()}' : ''). 'const t=d.querySelectorAll("td,th"),b=function(e,f=""){for(j=e.parentNode.parentNode.rows.length;--j>=0;)e.parentNode.parentNode.rows[j].cells[e.cellIndex].style.filter=f;e.parentNode.style.filter= f};for(i=t.length;--i>=0;){t[i].onmouseover=function(){b(this,"sepia(20%)")};t[i].onmouseout=function(){b(this)}}';

		if ($g = glob($bookings_dir. '*', GLOB_ONLYDIR+GLOB_NOSORT))
		{
			if ($b = array_diff($g, $a)) foreach ($b as $c) if (is_dir($c)) `rm -rf $c`;
			`find $bookings_dir -type d -empty -delete`;
		}
	}
}

function put_png_tEXt($png, $key, $val='', $enc=true)
{
	if ('image/png' === getimagesize($png)['mime'])
	{
		$str = $key. "\0". $val;
		$iend = hex2bin('0000000049454e44ae426082');
		$rep = str_replace($iend, pack('N', strlen($str)). 'tEXt'. $str. pack('N', crc32('tEXt'. $str)). $iend, file_get_contents($png));
		if ($enc) return base64_encode($rep);
		else file_put_contents($png, $rep, LOCK_EX);
	}
}

function get_png_tEXt($png)
{
	global $pngtext;
	$fp = fopen($png, 'rb');
	if ("\x89PNG\x0d\x0a\x1a\x0a" === fread($fp, 8))
	{
		while ($fr = fread($fp, 8))
		{
			$chunk = unpack('Nlength/a4type', $fr);
			if ('IEND' === $chunk['type']) break;
			if ('tEXt' === $chunk['type'])
			{
				list ($key, $val) = explode("\0", fread($fp, $chunk['length']));
				fseek($fp, 4, SEEK_CUR);
			}
			else
				fseek($fp, $chunk['length']+4, SEEK_CUR);
		}
	}
	fclose($fp);
	if (isset($key, $val) && $key === $pngtext) return $val;
}

function is_admin()
{
	global $session_usermail, $mail_address;
	return isset($_SESSION['l']) && filter_var_array([$session_usermail, $mail_address], FILTER_VALIDATE_EMAIL) && $mail_address === $session_usermail ? $_SESSION['l'] : false;
}

function is_subadmin($userstr='')
{
	if ($userstr && is_file('users/'. basename($userstr). '/prof/subadmin')) return 2;
	elseif (isset($_SESSION['l']) && is_file('users/'. $_SESSION['l']. '/prof/subadmin')) return 1;
}

function is_author($dir)
{
	return isset($_SESSION['l']) && is_file($author_txt = $dir. '/author.txt') && is_dir('users/'. basename($author = file_get_contents($author_txt)). '/prof/') && $_SESSION['l'] === $author ? $_SESSION['l'] : false;
}

function get_thumbnail($src)
{
	global $use_datasrc;
	$data = !$use_datasrc ? '' : 'data-';
	if ($src) $exif = @exif_read_data($src, '', '', true);
	if (isset($exif['THUMBNAIL']['THUMBNAIL'])) return '<img class="d-block img-fluid mb-1" '. $data. 'src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif['THUMBNAIL']['THUMBNAIL']). '" alt="">';
}
function html_assist()
{
	global $footer, $form_label, $html_assist, $javascript;
	$footer .=
	'<div class="dropup flex-grow-1" id=h>'.
	'<button class="btn btn-outline-primary dropdown-toggle" id=a data-toggle=dropdown tabindex=1 accesskey=h>'. $html_assist[0]. '</button>'.
	'<div class="dropdown-menu p-3" style="max-height:200px;overflow-x:hidden">'.
	'<ul class=list-inline>'.
	'<li class="list-inline-item"><a href="#" data-select=success class="dropdown-item mb-2 bg-success text-white">'. $html_assist[2]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=green class="dropdown-item mb-2 bg-success text-success">'. $html_assist[3]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=danger class="dropdown-item mb-2 bg-danger text-white">'. $html_assist[4]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=pink class="dropdown-item mb-2 bg-danger text-danger">'. $html_assist[5]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=primary class="dropdown-item mb-2 bg-primary text-white">'. $html_assist[6]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=info class="dropdown-item mb-2 bg-info text-info">'. $html_assist[7]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=warning class="dropdown-item mb-2 bg-warning text-white">'. $html_assist[8]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=yellow class="dropdown-item mb-2 bg-warning text-warning">'. $html_assist[9]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=dark class="dropdown-item mb-2 bg-dark text-white">'. $html_assist[10]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=secondary class="dropdown-item mb-2 bg-secondary text-white">'. $html_assist[11]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=light class="dropdown-item mb-2 bg-light text-body">'. $html_assist[12]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=white class="dropdown-item mb-2 bg-white text-body">'. $html_assist[13]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=hr class="dropdown-item">'. $html_assist[14]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=table class="dropdown-item">'. $html_assist[15]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=li class="dropdown-item">'. $html_assist[16]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=a class="dropdown-item">'. $html_assist[17]. '</a></li>'.
	'</ul>'.
	'<div class=dropdown-divider></div>'.
	'<ul class=list-inline>'.
	'<li class="list-inline-item"><a href="#" data-select=h1 class="dropdown-item">'. $html_assist[18]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=h2 class="dropdown-item">'. $html_assist[19]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=h3 class="dropdown-item">'. $html_assist[20]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=h4 class="dropdown-item">'. $html_assist[21]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=h5 class="dropdown-item">'. $html_assist[22]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=h6 class="dropdown-item">'. $html_assist[23]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=right class="dropdown-item">'. $html_assist[24]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=center class="dropdown-item">'. $html_assist[25]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=lead class="dropdown-item">'. $html_assist[26]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=small class="dropdown-item">'. $html_assist[27]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=b class="dropdown-item">'. $html_assist[28]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=em class="dropdown-item">'. $html_assist[29]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=strong class="dropdown-item">'. $html_assist[30]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=del class="dropdown-item">'. $html_assist[31]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=ins class="dropdown-item">'. $html_assist[32]. '</a></li>'.
	'</ul>'.
	'<div class=dropdown-divider></div>'.
	'<ul class=list-inline>'.
	'<li class="list-inline-item"><a href="#" data-select=ltgt class="dropdown-item">'. $html_assist[33]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=br class="dropdown-item">'. $html_assist[34]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=address class="dropdown-item">'. $html_assist[35]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=blockquote class="dropdown-item">'. $html_assist[36]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=cite class="dropdown-item">'. $html_assist[37]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=code class="dropdown-item">'. $html_assist[38]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=dl class="dropdown-item">'. $html_assist[39]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=kbd class="dropdown-item">'. $html_assist[40]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=mark class="dropdown-item">'. $html_assist[41]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=samp class="dropdown-item">'. $html_assist[42]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=sub class="dropdown-item">'. $html_assist[43]. '</a></li>'.
	'<li class="list-inline-item"><a href="#" data-select=sup class="dropdown-item">'. $html_assist[44]. '</a></li>'.
	'</ul>'.
	'</div>'.
	'</div>';
	$javascript = '$(".creates").on("change keyup mouseup paste",function(){l=encodeURIComponent($(this).val()).replace(/%../g,"x").length,m=200;$(".max").text("'. sprintf($form_label[5], '"+(m-l)+"'). '");if(l>m){$(".creates").addClass("is-invalid");$(":submit").prop("disabled",true)}else{$(".creates").removeClass("is-invalid");$(":submit").prop("disabled",false)}});$(".dropdown-item").on("click",function(){add(this)});function h(select){return select.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/\'/g,"&#039;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}function add(tag){let select=tag.dataset.select;if("hr"===select||"br"===select)single(select);else between(select)}function single(tag){let target=d.getElementById("textarea"),pos=getAreaRange(target),val=target.value,range=val.slice(pos.start,pos.end),beforeNode=val.slice(0,pos.start),afterNode= val.slice(pos.end),insertNode;if(range||pos.start!==pos.end){insertNode=range+"<"+tag+">"}else if(pos.start===pos.end){insertNode="<"+tag+">"}target.value=beforeNode+insertNode+afterNode;localStorage.setItem("textarea",target.value);target.setSelectionRange(pos.end,pos.end);target.focus()}function between(tag){let target=d.getElementById("textarea"),pos=getAreaRange(target),val=target.value,range=val.slice(pos.start,pos.end),beforeNode=val.slice(0,pos.start),afterNode=val.slice(pos.end),insertNode;target.focus();if("code"===tag)range=h(range);if("a"===tag){if(range||pos.start!==pos.end){insertNode="<a href=\"\">"+range+"<\/a>"}else if(pos.start===pos.end){insertNode="<a href=\"\"><\/a>"}}else if("right"===tag){if(range||pos.start!==pos.end){insertNode="<p class=text-right>"+range+"<\/p>"}else if(pos.start===pos.end){insertNode="<p class=text-right><\/p>"}}else if("lead"===tag){if(range||pos.start!==pos.end){insertNode="<span class=lead>"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=lead><\/span>"}}else if("center"===tag){if(range||pos.start!==pos.end){insertNode="<p class=text-center>"+range+"<\/p>"}else if(pos.start===pos.end){insertNode="<p class=text-center><\/p>"}}else if("primary"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-primary text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-primary text-white px-1\"><\/span>"}}else if("success"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-success text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-success text-white px-1\"><\/span>"}}else if("green"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-success text-success px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-success text-success px-1\"><\/span>"}}else if("info"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-info text-info px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-info text-info px-1\"><\/span>"}}else if("warning"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-warning text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-warning text-white px-1\"><\/span>"}}else if("yellow"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-warning text-warning px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-warning text-warning px-1\"><\/span>"}}else if("danger"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-danger text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-danger text-white px-1\"><\/span>"}}else if("pink"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-danger text-danger px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-danger text-danger px-1\"><\/span>"}}else if("secondary"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-secondary text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-secondary text-white px-1\"><\/span>"}}else if("dark"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-dark text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-dark text-white px-1\"><\/span>"}}else if("light"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-light text-body px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-light text-body px-1\"><\/span>"}}else if("white"===tag){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-white text-body px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-white text-body px-1\"><\/span>"}}else if("dl"===tag){if(range||pos.start!==pos.end){insertNode="\u000D<dl class=dl-horizontal>\u000D<dt>"+range+"<\/dt>\u000D<dd><\/dd>\u000D<\/dl>\u000D"}else if(pos.start===pos.end){insertNode="<dl class=dl-horizontal>\u000D<dt><\/dt>\u000D<dd><\/dd>\u000D<\/dl>\u000D"}}else if("li"===tag){if(range||pos.start!==pos.end){insertNode="\u000D<ul class=list-group>\u000D<li class=\"list-group-item bg-transparent\">"+range+"<\/li>\u000D<li class=\"list-group-item bg-transparent\"><\/li>\u000D<li class=\"list-group-item bg-transparent\"><\/li>\u000D<\/ul>\u000D"}else if(pos.start===pos.end){insertNode="<ul class=list-group>\u000D<li class=\"list-group-item bg-transparent\"><\/li>\u000D<li class=\"list-group-item bg-transparent\"><\/li>\u000D<li class=\"list-group-item bg-transparent\"><\/li>\u000D<\/ul>\u000D"}}else if("table"===tag){if(range||pos.start!==pos.end){insertNode="\u000D<table class=\"table table-striped table-dark\">\u000D<tr>\u000D<td>"+range+"<\/td>\u000D<\/tr>\u000D<\/table>\u000D"}else if(pos.start===pos.end){insertNode="\u000D<table class=\"table table-striped table-dark\">\u000D<tr>\u000D<td><\/td>\u000D<\/tr>\u000D<\/table>\u000D"}}else if("ltgt"===tag){if(range||pos.start!==pos.end){insertNode="&lt;"+range+"&gt;"}else if(pos.start===pos.end){insertNode="&lt;&gt;"}}else{if(range||pos.start!==pos.end){insertNode="<"+tag+">"+range+"<\/"+tag+">"}else if(pos.start===pos.end){insertNode="<"+tag+">"+"<\/"+tag+">"}}target.value=beforeNode+insertNode+afterNode;localStorage.setItem("textarea",target.value);target.setSelectionRange(pos.end,pos.end);target.focus()}function getAreaRange(obj){const pos=new Object();if(window.getSelection()){pos.start=obj.selectionStart;pos.end=obj.selectionEnd}return pos}$(".dropdown-menu").on({"click":function(e){e.stopPropagation();return false}});';
}
