<?php
if (__FILE__ === implode(get_included_files())) exit;
function r($path)
{
	if (str_contains($path, '%'))
		return $path;
	else
		return str_replace(['%2F', '%3A', '%20'], ['/', ':', '+'], rawurlencode($path));
}

function d($enc)
{
	global $get_page;
	if (str_contains($enc ,'/&pages')) $enc = dirname($enc);
	if (str_contains($enc , $get_page. '&pages')) $enc = explode('&', $enc)[0];
	if (str_contains($enc ,'+')) $enc = str_replace('+', '%20', $enc);
	return rawurldecode(html_entity_decode(basename($enc)));
}

function h($str)
{
	global $encoding;
	return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, $encoding, false);
}

function size_unit(int $num, $filesize=true)
{
	if (9e2 > $num)
	{
		$num_format = number_format($num, 1);
		$unit = $filesize ? ' B' : '';
	}
	elseif (9e5 > $num)
	{
		$num_format = number_format($num/1e3, 1);
		$unit = $filesize ? ' kB' : 'K';
	}
	elseif (9e8 > $num)
	{
		$num_format = number_format($num/1e6, 1);
		$unit = $filesize ? ' MB' : 'M';
	}
	elseif (9e11 > $num)
	{
		$num_format = number_format($num/1e9, 1);
		$unit = $filesize ? ' GB' : 'B';
	}
	else
	{
		$num_format = number_format($num/1e12, 1);
		$unit = $filesize ? ' TB' : 'T';
	}
	return str_replace('.'. str_repeat(0, 1), '', str_replace('.'. str_repeat(0, 1), '', $num_format)). $unit;
}

function timestamp()
{
	return gmdate('D, d M Y H:i:s T', getlastmod());
}

function is_ssl()
{
	return isset($_SERVER['HTTPS']) || isset($_SERVER['SSL']) || isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR']) ? true : false;
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
function hsl2rgb($h, $s, $l)
{
	$c = (1 - abs(2 * ($l / 100) - 1)) * $s / 100;
	$x = $c * (1 - abs(fmod(($h / 60), 2) - 1));
	$m = ($l / 100) - ($c / 2);
	if (60 > $h)
	{
		$r = $c;
		$g = $x;
		$b = 0;
	}
	elseif (120 > $h)
	{
		$r = $x;
		$g = $c;
		$b = 0;
	}
	elseif (180 > $h)
	{
		$r = 0;
		$g = $c;
		$b = $x;
	}
	elseif (240 > $h)
	{
		$r=0;
		$g=$x;
		$b=$c;
	}
	elseif (300 > $h)
	{
		$r = $x;
		$g = 0;
		$b = $c;
	}
	else
	{
		$r = $c;
		$g = 0;
		$b = $x;
	}
	return [floor(($r + $m) * 255), floor(($g + $m) * 255), floor(($b + $m) * 255)];
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
	global $summary_length, $encoding, $n, $ellipsis, $line_breaks;
	$content = file_get_contents($file);
	$content = preg_replace('/<script.*?\/script>/s', '', $content);
	$content = preg_replace('/\/\*.*?\*\//s', '', $content);
	ob_start();
	echo $content;
	$text = strip_tags(ob_get_clean());
	$text = str_replace("\t", '', $text);
	$text = str_replace([$n. $n. $n, $n. $n], $n, $text);
	$text = str_replace($line_breaks, '&#10;', $text);
	$text = mb_strimwidth($text, 0, $summary_length, $ellipsis, $encoding);
	return trim($text);
}

function get_description($str)
{
	global $description_length, $encoding, $line_breaks, $ellipsis;
	$text = strip_tags(preg_replace('/<script.*?\/script>/s', '', $str));
	$text = preg_replace('/\/\*.*?\*\//s', '', $str);
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
	global $aside, $sidebox_order, $sidebox_title, $social_medias, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class;
	if ($social_medias)
	{
		$aside .=
		'<div id=social class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[2]. '">'.
		'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[7]. '</div>'.
		'<div class="'. $sidebox_content_class[3]. '">';
		$social_link = include 'socials.php';
		foreach($social_medias as $social_name)
			$aside .= $social_link[$social_name];
		$aside .= '</div></div>';
	}
}

function permalink($t, $u)
{
	global $aside, $sidebox_order, $sidebox_title, $permalink, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class;
	$aside .=
	'<div id=permalink class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[3]. '">'.
	'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[8]. '</div>'.
	'<div class="'. $sidebox_content_class[3]. '">'.
	'<div class="input-group input-group-sm mb-3">'.
	'<label class=input-group-text for=html>'. $permalink[0]. '</label>'.
	'<input readonly onclick="this.select()" id=html type=text class="form-control bg-white" value="&lt;a href=&quot;'. $u. '&quot; target=&quot;_blank&quot;&gt;'. htmlentities(h($t)). '&lt;/a&gt;">'.
	'</div>'.
	'<div class="input-group input-group-sm mb-3">'.
	'<label class=input-group-text for=wiki>'. $permalink[1]. '</label>'.
	'<input readonly onclick="this.select()" id=wiki type=text class="form-control bg-white" value="['. $u. ' '. htmlentities(h($t)). ']">'.
	'</div>'.
	'<div class="input-group input-group-sm mb-2">'.
	'<label class=input-group-text for=forum>'. $permalink[2]. '</label>'.
	'<input readonly onclick="this.select()" id=forum type=text class="form-control bg-white" value="[URL='. $u. ']'. htmlentities(h($t)). '[/URL]">'.
	'</div>'.
	'</div>'.
	'</div>';
}

function a($uri, $name='', $target='_blank', $class='', $title='', $position='')
{
	return
	'<a href="'. (str_contains($uri, '%') || str_contains($uri, '://') ? $uri : r($uri)). '" target="'. $target. '"' .
	(!$class ? '' : ' class="'. $class. '"') .
	(!$title ? '' : ' data-bs-toggle=tooltip title="'. $title. '" data-html=true') .
	(!$position ? '' : ' data-placement="'. $position. '"') .
	('_blank' !== $target ? '' : ' rel="noopener noreferrer"'). '>' .
	(!$name ? h(urldecode($uri)) : h($name)) .
	'</a>';
}

function img($src, $class='', $show_exif_comment=false, $per=1)
{
	global $url, $source, $classname, $get_categ, $get_title, $index_type, $get_page, $use_thumbnails, $use_categ_thumbnails, $line_breaks, $use_datasrc;
	if ($extension = get_extension($src))
	{
		$image_extensions = ['.gif', '.jpg', '.jpeg', '.png', '.svg'];
		$video_extensions = ['.mp4', '.ogg', '.webm'];
		if ($src_scheme = str_contains($src, '://')) $addr = parse_url($src);
		$data = !$use_datasrc ? '' : 'data-';
		if (false !== array_search($lower_ext = strtolower($extension), $image_extensions))
		{
			$alt = h(basename($src));
			$exif = @exif_read_data($src, '', '', true);
			$exif_comment = '';
			$exif_thumbnail = !isset($exif['THUMBNAIL']['THUMBNAIL']) ? '' : $exif['THUMBNAIL']['THUMBNAIL'];
			if ($show_exif_comment)
			{
				if (isset($exif['COMMENT'][0])) $exif_comment = h($exif['COMMENT'][0]);
				if ('.png' === $lower_ext) $exif_comment = get_png_tEXt($src);
			}
			[$width, $height, $type, $attr] = @getimagesize($src);
			if ($exif_thumbnail) [$width_sm, $height_sm, $type_sm, $attr_sm] = getimagesizefromstring($exif_thumbnail);
			$img = $exif_comment ?
			'<figure class="align-top img-thumbnail text-center d-inline-block" style="max-width:'. $width. 'px">'.
			'<img class="img-fluid '. $class. '" '. $data. 'src="'. $url. $src. '" alt="'. $alt. '" '. $attr. '>'.
			'<p class="text-center wrap my-2">'. $exif_comment. '</p>'.
			'</figure>' :
			'<img class="align-top img-fluid img-thumbnail '. $class. '" '. $data. 'src="'. $url. $src. '" alt="'. $alt. '" '. $attr. '>';
			if ($get_title || $get_page)
			{
				if (false !== $src_scheme)
					return
					'<figure class="img-thumbnail text-center d-inline-block '. $class. '" style="max-width:'. $width. 'px">'.
					'<a data-fancybox=gallery href="'. $src. '">'.
					'<img class=img-fluid '. $data. 'src="'. $addr['scheme']. '://'. $addr['host']. $addr['path']. '" alt="'. $alt. '" '. $attr. '>'.
					'</a>'.
					'<small class="blockquote-footer my-2 text-end">'.
					'<a href="'. $addr['scheme']. '://'. $addr['host']. '/" target="_blank" rel="noopener noreferrer">'. sprintf($source, h($addr['host'])). '</a>'.
					'</small>'.
					'</figure>';
				elseif (!$exif_thumbnail)
					return
					'<figure class="align-top img-thumbnail text-center d-inline-block" style="max-width:'. $width. 'px">'.
					'<a data-fancybox=gallery class="d-inline-block mb-2 me-1"'. (!$exif_comment ? '' : ' data-caption="'. $exif_comment. '"'). ' href="'. $url. r($src). '">'.
					'<img class="align-top img-fluid '. $class. '" '. $data. 'src="'. $url. $src. '" alt="'. $alt. '" '. $attr. '>'.
					'</a>'.
					'<figcaption class="text-center mb-2 wrap">'. $exif_comment. '</figcaption>'.
					'</figure>';
				else
					return
					'<figure class="d-inline-block m-2">'.
					'<a data-fancybox=gallery href="'. $url. r($src). '"'. (!$exif_comment ? '' : ' data-caption="'. $exif_comment. '"'). '>'.
					($exif_thumbnail && $use_thumbnails ?
					'<img class="'. $class. ' align-top img-thumbnail" '. $data. 'src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '" '. $attr_sm. '>' :
					$img).
					'</a>'.
					'</figure>';
			}
			else
			{
				if ($exif_thumbnail && $use_categ_thumbnails)
				{
					if ($get_categ || 1 === $index_type)
						$img =
						'<span class="card-header d-block text-center">'.
						'<img class="img-fluid card-img-top '. $class. '" '. $data. 'src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '" '. $attr_sm. '>'.
						'</span>';
					else
						$img =
						'<span'. ($classname ? ' class="d-block '. $classname. ' position-relative" style="max-width:'. $width_sm. 'px"' : ''). '>'.
						'<img class="img-fluid '. $class. '" '. $data. 'src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '" '. $attr_sm. '>'.
						'</span>';
				}
				else
					$img =
					'<span'. ($classname ? ' class="d-block '. $classname. ' position-relative" style="max-width:'. $width. 'px"' : ''). '>'.
					'<img class="img-fluid '. (1 === $index_type ? 'card-img-top ' : ''). $class. '" '. $data. 'src="'. $url. $src. '" alt="'. $alt. '" width="'. ($width * $per). '" height="'. ($height * $per). '">'.
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
					'<figure class="align-top img-thumbnail text-center d-inline-block '. $class. '">'.
					'<video controls preload=none>'.
					'<source src="'. $addr['scheme']. '://'. $addr['host']. $addr['path']. '">'.
					'<track src="'. str_replace($extension, '.vtt', $addr['scheme']. '://'. $addr['host']. $addr['path']). '" default=default>'.
					'</video>'.
					'<small class="blockquote-footer my-2 text-end">'.
					'<a href="'. $addr['scheme']. '://'. $addr['host']. '/" target="_blank" rel="noopener noreferrer">'. sprintf($source, h($addr['host'])). '</a>'.
					'</small>'.
					'</figure>';
				else
					return
					'<a href="'. $url. r($src). '" class="sr-only mfp-iframe visually-hidden">video-iframe</a>'.
					'<video class="align-top img-thumbnail '. $class. '" controls preload=none>'.
					'<source src="'. $url. $src. '">'.
					'<track src="'. $url. $vtt. '" default=default>'.
					'</video>';
			}
			else
				return '<video class="align-top w-100 '. $class. '" controls preload=none><source src="'. $url. $src. '"><track src="'. $url. $vtt. '" default=default></video>';
		}
	}
}

function timeformat($time, array $intervals)
{
	$now = new DateTime;
	$ago = new DateTime('@'. $time);
	$diff = (array)$now->diff($ago);
	$diff['w'] = floor($diff['d']/7);
	$diff['d'] -= $diff['w']*7;
	foreach ($intervals as $k => &$v)
	{
		if ($diff[$k])
			$v = $diff[$k]. $v;
		else
			unset($intervals[$k]);
	}
	return implode(array_slice($intervals, 0, 1));
}

function pager(int $num, int $max)
{
	global $number_of_pager, $article, $nav_laquo, $nav_raquo, $pager_wrapper;
	$article .= '<ul class="pagination '. $pager_wrapper. '">';

	if(2 < $num)
		$article .= '<li class=page-item><a class=page-link href="'. get_page(1). '">'. $nav_laquo. $nav_laquo. '</a></li>';
	if (1 < $num)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($num-1). '">'. $nav_laquo. '</a></li>';

	$i = 1;
	while ($i <= $number_of_pager)
	{
		$half_page = $number_of_pager/2;
		$ceil = (int)ceil($half_page);
		if (0 > $num - $ceil)
		{
			if ($num === $i)
				$article .= '<li class="active page-item"><a class=page-link>'. $num. '</a></li>';
			else
				$article .= '<li class=page-item><a class=page-link href="'. get_page($i). '">'. $i. '</a></li>';
		}
		elseif ($num + floor($half_page) > $max)
		{
			if ($max > $number_of_pager)
				$j = $max - $number_of_pager + $i;
			else
				$j = $i;

			if ($num === $j)
				$article .= '<li class="active page-item"><a class=page-link>'. $num. '</a></li>';
			else
				$article .= '<li class=page-item><a class=page-link href="'. get_page($j). '">'. $j. '</a></li>';
		}
		else
		{
			if ($i === $ceil)
				$article .= '<li class="disable active page-item"><a class=page-link>'. $num. '</a></li>';
			else
			{
				$j = $num - $ceil + $i;
				$article .= '<li class=page-item><a class=page-link href="'. get_page($j). '">'. $j. '</a></li>';
			}
		}
		if ($max === $i) break;
		++$i;
	}
	if ($max > $num)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($num+1). '">'. $nav_raquo. '</a></li>';
	if ($num < $max - 1)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($max). '">'. $nav_raquo. $nav_raquo. '</a></li>';
	$article .= '</ul>';
}

function sideless($hide=false, $force=false)
{
	global $stylesheet, $get_title, $get_page, $template;
	if ($get_title || $get_page || $force)
	{
		if ($hide)
			$stylesheet .= '#main,#main>article,#main>div,#main.col-lg-8,#main.col-lg-9{margin:0!important;padding:0!important;max-width:100%!important;flex:0 0 100%}#main>header{margin:0}'. ('lightside' === $template ? '' : '#side{display:none!important}');
		else
			$stylesheet .= '#wrapper{flex-direction:column!important}#main,#side{max-width:inherit;flex:0 0 100%}';
	}
}

function widemain($padding=0)
{
	global $stylesheet;
	$stylesheet .= '#main,#article,#clearfix{margin:0!important;padding:'. $padding. 'px!important;max-width:100%!important;flex:0 0 100%}';
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

function get_logo($name=false, $class='', $width='', $height='')
{
	global $site_name, $url, $logo_found;
	if (!is_file($logo = 'images/logo.png') && !is_file($logo = 'images/logo.svg') && !is_file($logo = 'images/icon.svg'))
	{
		$logo_found = false;
		return $site_name;
	}
	else
	{
		$logo_found = true;
		return '<img src="'. $url. $logo. '" class="img-fluid'. ($name ? '' : ' '. $class). '" alt="'. $site_name. '"'. (!$width ? '' : ' width="'. $width. '"'). (!$height ? '' : ' height="'. $height. '"'). '>'.
		(!$name ? '' : '<span class="'. (!$class ? '' : $class). '">'. $site_name. '</span>');
	}
}


function not_found()
{
	global $article, $breadcrumb, $header, $site_name, $not_found, $h1_title;
	http_response_code(404);
	$header .= '<title>'. $not_found[0]. ' - '. $site_name. '</title>';
	$breadcrumb .= '<li class="breadcrumb-item active">'. http_response_code(). '</li>';
	$article .=
	'<h1 class="'. $h1_title[0]. '">'. $not_found[0]. '</h1>'.
	'<div class="'. $h1_title[1]. ' not-found">'. $not_found[1]. '</div>';
}

function toc($in_article=false)
{
	global $article, $aside, $javascript, $sidebox_title, $sidebox_order, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class, $get_title, $get_page;
	if ($get_title || $get_page)
	{
		$toc_content = '';
		if ($in_article)
			$toc_content .= '<div class="'. $sidebox_title_class[5]. '"><button class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle=collapse data-bs-target=#toctoggle accesskey=p tabindex=0><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16" width="1.5em" height="1.5em"><path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/></svg></button>'. $sidebox_title[9]. '</div>';
		else
			$toc_content .= '<div class="'. $sidebox_title_class[4]. '">'. $sidebox_title[9]. '</div>';
		$toc_content .= '<div id=toctoggle class="'. $sidebox_wrapper_class[1]. (!$in_article ? '' : ' collapse pb-3'). '"></div>';
		if ($in_article)
			$article .= '<div id=toc class="'. $sidebox_wrapper_class[2]. '">'. $toc_content. '</div>';
		else
			$aside .= '<div id=toc class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[8]. '">'. $toc_content. '</div>';
		$javascript .= 'let num=1,toc="",toclv=lv=0,hx=document.querySelectorAll(".article h2, .article h3, .article h4, .article h5, .article h6");for(let elm of hx){elm.id="toc"+num;tag=elm.nodeName.toLowerCase();num++;if(tag==="h2")lv=1;else if(tag==="h3")lv=2;else if(tag==="h4")lv=3;else if(tag==="h5")lv=4;else if(tag==="h6")lv=5;while(toclv<lv){toc+="<ul>";toclv++}while(toclv>lv){toc+="<\/ul>";toclv--}toc+="<li><a class=\"'. $sidebox_content_class[4]. '\" href=\"#"+elm.id+"\" title=\""+elm.textContent+"\">"+elm.textContent+"<\/a><\/li>"};while(toclv>0){toc+="<\/ul>";toclv--}document.getElementById("toctoggle").innerHTML=toc;';
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
			'samesite' => 'lax',
		]);
		session_start();
		session_regenerate_id(true);
	}
}

function get_uri($uri, $get)
{
	if (str_contains($uri, '%23') || str_contains($uri, '%26'))
		return $uri;
	else
		return basename(filter_input(INPUT_GET, $get, FILTER_CALLBACK, ['options' => 'r']));
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
	global $aside, $javascript, $login_try_again, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class, $ticket_warning, $ask_admin;
	$aside .=
	'<div id=login class="'. $sidebox_wrapper_class[0]. '">'.
	'<div class="'. $sidebox_title_class[3]. '">'. $str. '</div>'.
	'<p class="'. $sidebox_content_class[3]. '">'. ($str === $ticket_warning[3] ? $ask_admin : $login_try_again). '</p>'.
	'</div>';
	$javascript .= 'if(document.getElementById("side").classList.contains("offcanvas"))new bootstrap.Offcanvas(document.getElementById("side")).show();';
}

function handle($dir)
{
	if ($usermail = filter_var(dec($userstr = basename(dirname($dir))), FILTER_VALIDATE_EMAIL))
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
	if (is_file($img = $dir. 'avatar') && filesize($img) && str_contains($base64_img = file_get_contents($img), 'base64'))
		$avatar = '<img'. (!$size ? '' : ' style="object-fit:cover;width:'. $size. 'px;height:'. $size. 'px"'). ' src="'. strip_tags($base64_img). '" class="align-text-bottom d-inline-block rounded-circle mx-auto" alt="">';
	elseif (is_file($bgcolor = $dir. '/bgcolor') && filesize($bgcolor) && $handle = handle($dir))
		$avatar = '<span style="'. (!$size ? '' : 'font-size:'. ($size * 70 / 100). 'px;width:'. $size. 'px;height:'. $size. 'px;'). 'background-color:'. h(file_get_contents($bgcolor)). '" class="d-inline-flex justify-content-center font-weight-bold fw-bold rounded-circle mx-auto text-center text-white">'. mb_substr($handle, 0, 1). '</span>';
	else
	{
		global $color;
		$avatar = '<span style="background-color:'. ($color ? hsla($color, 5.5, -7,.9) : 'rgba(0,0,0,.5)'). ';'. (!$size ? '': 'font-size:'. ($size * 70 / 100). 'px;width:'. $size. 'px;height:'. $size. 'px;'). '" class="d-inline-flex justify-content-center font-weight-bold fw-bold rounded-circle mx-auto text-center text-white">'. mb_substr($dir, 0, 1). '</span>';
	}
	if (100 <= $size)
	{
		global $mail_address, $admin_suffix;
		if ($mail_address === dec(basename(dirname($dir)))) $avatar .= '<div class="badge badge-pill rounded-pill badge-primary bg-primary my-2 mx-auto" style="width:'. mb_strlen($admin_suffix[0]). 'rem;display:inherit">'. $admin_suffix[0]. '</div>';
		elseif (is_file($dir. 'subadmin')) $avatar .= '<div class="badge badge-pill rounded-pill badge-success bg-success my-2 mx-auto" style="width:'. mb_strlen($admin_suffix[1]). 'rem;display:inherit">'. $admin_suffix[1]. '</div>';
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
	'<li class="w-25 pt-3 me-5 border-top border-10 '. (3 === $d ? 'border-success' : 'border-light'). '">'. sprintf($a[3], $b). '</li>'.
	'<li class="w-25 pt-3 border-top border-10 '. (4 === $d ? 'border-success' : 'border-light'). '">'. sprintf($a[4], $c). '</li>'.
	'</ol>';
}

function counter($txt, $put=false)
{
	if (is_file($txt) && is_writeable($txt))
	{
		$counter = (int)file_get_contents($txt);
		++$counter;
		if ($fp = fopen($txt, 'c'))
		{
			flock($fp, LOCK_EX);
			ftruncate($fp, 0);
			fwrite($fp, $counter);
			fclose($fp);
		}
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
		if (str_contains($s, $o)) $s = str_replace($o, '<span style="color:#44AA00">'. $o. '</span>', $s);
	if (str_contains($s, '&amp;nbsp;')) $s = str_replace('&amp;nbsp;', '<span style="color:#888A85">&amp;nbsp;</span>', $s);
	if (str_contains($s, '=')) $s = preg_replace('/(?!!|=)([\w-]+) ?= ?(&quot;|&#039;)/is', '<span style="color:#44AA00">\\1</span>=\\2', $s);
	if (str_contains($s, '&lt;') && str_contains($s, '&gt;')) $s = preg_replace('/&lt;(?!!--)(.*?)&gt;/s', '<span style="color:#5F8DD3">&lt;\\1&gt;</span>', $s);
	if (str_contains($s, '&#039;')) $s = preg_replace_callback('/&#039;(.*?)&#039;/s', function ($t){return '&#039;<span style="color:#FD3301">' . strip_tags($t[1]) . '</span>&#039;';}, $s);
	if (str_contains($s, '&quot;')) $s = preg_replace_callback('/&quot;(.*?)&quot;/s', function ($t){return '&quot;<span style="color:#FD3301">' . strip_tags($t[1]) . '</span>&quot;';}, $s);
	if (str_contains($s, '&lt;script')) $s = preg_replace_callback('/(&lt;script.*?&gt;)(.*?)(&lt;\/script&gt;)/is', function ($t){return $t[1] . '<span style="color:#888A85">' . strip_tags($t[2]) . '</span>' . $t[3];}, $s);
	if (str_contains($s, '&lt;style')) $s = preg_replace_callback('/(&lt;style.*?&gt;)(.*?)(&lt;\/style&gt;)/is', function ($t){return $t[1] . '<span style="color:#888A85">' . strip_tags($t[2]) . '</span>' . $t[3];}, $s);
	if (str_contains($s, '「')) $s = preg_replace_callback('/(「)(.*?)(」)/is', function ($t){return $t[1] . '<strong>' . strip_tags($t[2]) . '</strong>' . $t[3];}, $s);
	if (str_contains($s, '『')) $s = preg_replace_callback('/(『)(.*?)(』)/is', function ($t){return $t[1] . '<strong>' . strip_tags($t[2]) . '</strong>' . $t[3];}, $s);
	if (str_contains($s, '【')) $s = preg_replace_callback('/(【)(.*?)(】)/is', function ($t){return $t[1] . '<strong>' . strip_tags($t[2]) . '</strong>' . $t[3];}, $s);
	if (str_contains($s, '&lt;?')) $s = preg_replace_callback('/(&lt;\?.*?\?&gt;)/is', function ($t){return highlight_string(html_entity_decode(strip_tags($t[1]), ENT_QUOTES), true);}, $s);
	if (str_contains($s, '['))
	{
		$s = preg_replace_callback('/\[url=?(http.*?)?\](.*?)\[\/url\]/i', function ($t)
		{
			if (!$t[1]) return '<a href="'. $t[2]. '" target="_blank" rel="noopener noreferrer">'. $t[2]. '</a>';
			else return '<a href="'. $t[1]. '" target="_blank" rel="noopener noreferrer">'. $t[2]. '</a>';
		}, $s);
	}
	if (str_contains($s, '/*')) $s = preg_replace_callback('|(/\*)(.*?)(\*/)|s', function ($t){return '<span style="color:#FF7F2A">/&#042;' . strip_tags($t[2]) . '&#042;/</span>';}, $s);
	if (str_contains($s, '&lt;!--')) $s = preg_replace_callback('/(&lt;!--.*?--&gt;)/s', function ($t){return '<span style="color:#FF7F2A">' . strip_tags($t[1]) . '</span>';}, $s);
	if (str_contains($s, '//')) $s = preg_replace_callback('|(?<![:(>&quot;&#039;])(//.*?&#10;)|is', function ($t){return '<span style="color:#FF7F2A">' . strip_tags($t[1]) . '</span>';}, $s);
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
	global $accepting, $btn, $current_url, $denial_attrs, $footer, $javascript, $stylesheet, $line_breaks, $mail_address, $n, $session_usermail, $week;
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
		$booking = trim(filter_input(INPUT_POST, 'booking', FILTER_SANITIZE_SPECIAL_CHARS));
		$booking = enc(str_replace($line_breaks, '&#10;', $booking));
		if (!is_dir($date_dir = $bookings_dir. $date. '/')) mkdir($date_dir, 0757);
		file_put_contents($booking_txt = $date_dir. $_SESSION['l']. '.txt', $booking, LOCK_EX);
		if (!$booking && is_file($booking_txt)) unlink($booking_txt);
		header('Location: '. $current_url. '#booking');
	}
	$stylesheet .= '.a{opacity:1!important;visibility:visible!important}.b{opacity:0;visibility:hidden}.c{opacity:.8;cursor:not-allowed}.g{background:#3ddb80}.h{cursor:pointer;text-align:center;width:5%}.i{background:mediumseagreen;border:none;bottom:0;cursor:pointer;opacity:0;padding:5px 10px;position:absolute;right:0;text-align:center;transition:opacity .1s,visibility .1s;visibility:hidden}[data-placeholder]:empty:before{content:attr(data-placeholder);display:block;text-align:center}[contenteditable=false][data-placeholder]:empty:before{opacity:.8;cursor:not-allowed}[contenteditable=true][data-placeholder]:empty:before{cursor:pointer}#booking td{position:relative}#booking th,#booking td{padding:.8em .6em}#booking th:empty{width:5em}#booking th{background:dimgray;color:#fff;font-weight:inherit;vertical-align:middle;white-space:nowrap}#booking td div{word-wrap:break-word;white-space:pre-wrap}.g:hover{opacity:.8}#booking tr:hover,#booking tr:nth-child(odd){filter:brightness(105%)}@media print{*,a{color:#000000!important;font-size:8pt!important;opacity:1!important}aside,#side,footer,form,header,nav,p,.breadcrumb{display:none!important}html,body{background:#ffffff!important;margin:0!important;padding:0!important}th,td{background:#ffffff!important}th{white-space:nowrap!important}}';

	if (is_admin())
	{
		global $url, $long_time_format, $reminder, $remind_header, $separator;
		if (filter_has_var(INPUT_POST, 'remind-addr') && filter_has_var(INPUT_POST, 'remind-date') && filter_has_var(INPUT_POST, 'remind-title') && filter_has_var(INPUT_POST, 'remind-contents'))
		{
			global $mime, $encoding, $site_name;
			$to = strip_tags(filter_input(INPUT_POST, 'remind-addr'));
			$subject = strip_tags(filter_input(INPUT_POST, 'remind-title'));
			$body = sprintf(strip_tags(filter_input(INPUT_POST, 'remind-contents')), strip_tags(filter_input(INPUT_POST, 'remind-date')));
			$body .= $n. $n. $separator. $n. $site_name. $n. $url;
			$headers = $mime. 'From: '. $site_name. '<'. $mail_address. '>'. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n;
			mail($to, $subject, $body, $headers);
		}

		if (is_file($file = !filter_has_var(INPUT_POST, 'del') ? '' : filter_input(INPUT_POST, 'del', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))) unlink($file);
		if ($g = glob($bookings_dir. '*/*.txt'))
		{
			echo
			'<div class="card nowrap mb-4">',
			'<div class=card-header>', $reminder[0], '</div>',
			'<div class=card-body>',
			'<pre contenteditable=true id=title>', $reminder[1], '</pre>',
			'<pre contenteditable=true id=contents>', $reminder[2], '</pre>',
			'</div>',
			'</div>',
			'<table class="small w-100" id=booking>',
			'<tr><th class=bg-danger></th><th>', $remind_header[0], '</th><th>', $remind_header[1], '</th><th>', $remind_header[2], '</th><th>', $remind_header[3], '</th></tr>';
			foreach ($g as $remind)
			{
				if (is_file($remind))
				{
					list ($remind_y, $remind_m, $remind_d, $remind_h, $remind_i) = explode('-', basename(dirname($remind)));
					if ($reserved_mail = dec($user = basename($remind, '.txt')))
					{
						$reserved_time = sprintf($long_time_format, $remind_y, $remind_m, $remind_d, $remind_h, $remind_i);
						echo
						'<tr>',
						'<td class="bg-danger text-white h" onclick="if(!confirm(\'', sprintf($reminder[5], $reserved_time), '\'))return false;d(\''. $remind. '\')">', $btn[4], '</td>',
						'<th class=w-25>', $reserved_time, '</th>',
						'<td><a href="', $url, '?user=', str_rot13($user), '">', handle($user. '/prof'), '</a></td>',
						'<td><div>', dec(file_get_contents($remind)), '</div></td>',
						'<td class="g text-white h" onclick="if(!confirm(\'', sprintf($reminder[3], $reserved_mail), '\'))return false;a(\'', $reserved_mail,'\',\'', sprintf($long_time_format, $remind_y, $remind_m, $remind_d, $remind_h, $remind_i), '\',document.querySelector(\'#title\').textContent.replace(/^\s+|\s+$/g,\'\'),document.getElementById(\'contents\').innerText.replace(/^\s+|\s+$/g,\'\'))">', $btn[1], '</td>',
						'</tr>';
					}
				}
			}
			echo '</table>';
			$javascript .= 'function a(b,c,d,e){const f=new FormData();f.append("remind-addr",b);f.append("remind-date",c);f.append("remind-title",d);f.append("remind-contents",e);fetch("'. $current_url. '",{method:"POST",cache:"no-cache",body:f}).then(()=>{document.querySelector("table").insertAdjacentHTML("beforebegin","<p style=\"background:#3ddb80;color:#ffffff;margin:0;padding:1em 2em\" id="+b+">'. sprintf($reminder[4], '"+b+"'). '<\/p>")});setTimeout(()=>{document.getElementById(b).remove()},5000)}function d(g){const fd=new FormData();fd.append("del",g);fetch("'. $current_url. '",{method:"POST",cache:"no-cache",body:fd}).then(()=>{location.reload()})}';
		}
		else
		{
			global $booking_msg;
			echo '<table class="small w-100" id=booking><tr><th class=bg-danger>', $booking_msg[4], '</th></tr></table>';
		}
	}
	else
	{
		global $booking_msg, $short_time_format;
		echo '<div class=table-responsive><table id=booking class="small w-100">';
		if (isset($_SESSION['l']))
		{
			$accepted = count(glob($bookings_dir. '*/'. $_SESSION['l']. '.txt', GLOB_NOSORT));
			if ($accepted >= $accepts_per_person)
				echo '<caption class="bg-success px-3 py-2 text-end text-white">', sprintf($booking_msg[0], $accepted), '</caption>';
			elseif (0 === $accepted)
				echo '<caption class="bg-primary px-3 py-2 text-end text-white">', sprintf($booking_msg[2], $accepts_per_person), '</caption>';
			else
				echo '<caption class="bg-info px-3 py-2 text-end text-white">', sprintf($booking_msg[1], $accepts_per_person - $accepted), '</caption>';
		}
		else
			echo '<caption class="bg-warning px-3 py-2 text-end text-white">', $booking_msg[3], '</caption>';

		echo
		'<thead class=text-center>',
		'<tr>',
		'<th></th>';
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
				echo '<th', (!in_array($hi, $denial_time, true) ? '' : $denial_attrs),'>', $hi, '</th>';
			}
		}
		echo
		'</tr>',
		'</thead>',
		'<tbody>';
		foreach ($days as $d)
		{
			$times = range($d+$start_time, $d+$end_time, $time_range);
			$w = date('w', $d);
			echo
			'<tr>',
			'<th', (!in_array($week[$w], $denial_week, true) && !in_array(date('Y-m-d', $d), $denial_day, true) ? '' : $denial_attrs), '>', sprintf($short_time_format, date('n', $d), date('j', $d), $week[$w]), '</th>';
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
				' style="background:hsl(210,'. $percent. ',70%);color:#ffffff"'. $abtn. '<div'. $attr. '>' : '><div'. (!$books ? $denial_attrs : ' contenteditable=true style="opacity:.6"'). '>'), $books, '</div></td>';
				$a[] = is_dir($e = $bookings_dir. $time_dir) ? $e : '';
			}
			echo '</tr>';
		}
		echo
		'</tbody>',
		'</table></div>';
		$javascript .= (isset($_SESSION['l']) ? 'document.querySelectorAll("[contenteditable]").forEach(n=>{new MutationObserver(r=>{n.previousElementSibling.setAttribute("class","i a")}).observe(n,{childList:true})});function a(y,z){const f=document.createElement("form");f.style.display="none";f.method="post";let g=document.createElement("input"),h=document.createElement("textarea");g.name="date",h.name="booking",h.value=y,g.value=z;f.appendChild(g);f.appendChild(h);document.body.appendChild(f);f.submit()}' : ''). 'const t=document.querySelectorAll("td,th"),b=function(e,f=""){for(let j=e.parentNode.parentNode.rows.length;--j>=0;)e.parentNode.parentNode.rows[j].cells[e.cellIndex].style.filter=f;e.parentNode.style.filter= f};for(let i=t.length;--i>=0;){t[i].onmouseover=function(){b(this,"sepia(20%)")};t[i].onmouseout=function(){b(this)}}';
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
	if (isset($key, $val) && $key === $pngtext) return h($val);
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
	global $footer, $form_label, $html_assist, $javascript, $title_length;
	$footer .=
	'<div class="dropup flex-grow-1" id=h>'.
	'<button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle=dropdown data-bs-auto-close=inside tabindex=1>'. $html_assist[0]. '</button>'.
	'<div class="dropdown-menu p-3" style="max-height:200px;overflow-x:hidden;z-index:2000">'.
	'<ul class=list-inline>'.
	'<li class=list-inline-item><a href="#" data-select=success class="dropdown-item mb-2 bg-success text-white">'. $html_assist[2]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=green class="dropdown-item mb-2 bg-success text-success">'. $html_assist[3]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=danger class="dropdown-item mb-2 bg-danger text-white">'. $html_assist[4]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=pink class="dropdown-item mb-2 bg-danger text-danger">'. $html_assist[5]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=primary class="dropdown-item mb-2 bg-primary text-white">'. $html_assist[6]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=info class="dropdown-item mb-2 bg-info text-info">'. $html_assist[7]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=warning class="dropdown-item mb-2 bg-warning text-white">'. $html_assist[8]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=yellow class="dropdown-item mb-2 bg-warning text-warning">'. $html_assist[9]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=dark class="dropdown-item mb-2 bg-dark text-white">'. $html_assist[10]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=secondary class="dropdown-item mb-2 bg-secondary text-white">'. $html_assist[11]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=light class="dropdown-item mb-2 bg-light text-body">'. $html_assist[12]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=white class="dropdown-item mb-2 bg-white text-body">'. $html_assist[13]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=hr class=dropdown-item>'. $html_assist[14]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=table class=dropdown-item>'. $html_assist[15]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=li class=dropdown-item>'. $html_assist[16]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=a class=dropdown-item>'. $html_assist[17]. '</a></li>'.
	'</ul>'.
	'<div class=dropdown-divider></div>'.
	'<ul class=list-inline>'.
	'<li class=list-inline-item><a href="#" data-select=h1 class=dropdown-item>'. $html_assist[18]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=h2 class=dropdown-item>'. $html_assist[19]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=h3 class=dropdown-item>'. $html_assist[20]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=h4 class=dropdown-item>'. $html_assist[21]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=h5 class=dropdown-item>'. $html_assist[22]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=h6 class=dropdown-item>'. $html_assist[23]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=right class=dropdown-item>'. $html_assist[24]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=center class=dropdown-item>'. $html_assist[25]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=lead class=dropdown-item>'. $html_assist[26]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=small class=dropdown-item>'. $html_assist[27]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=b class=dropdown-item>'. $html_assist[28]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=em class=dropdown-item>'. $html_assist[29]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=strong class=dropdown-item>'. $html_assist[30]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=del class=dropdown-item>'. $html_assist[31]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=ins class=dropdown-item>'. $html_assist[32]. '</a></li>'.
	'</ul>'.
	'<div class=dropdown-divider></div>'.
	'<ul class=list-inline>'.
	'<li class=list-inline-item><a href="#" data-select=ltgt class=dropdown-item>'. $html_assist[33]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=br class=dropdown-item>'. $html_assist[34]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=address class=dropdown-item>'. $html_assist[35]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=blockquote class=dropdown-item>'. $html_assist[36]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=cite class=dropdown-item>'. $html_assist[37]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=code class=dropdown-item>'. $html_assist[38]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=dl class=dropdown-item>'. $html_assist[39]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=kbd class=dropdown-item>'. $html_assist[40]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=mark class=dropdown-item>'. $html_assist[41]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=samp class=dropdown-item>'. $html_assist[42]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=sub class=dropdown-item>'. $html_assist[43]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=sup class=dropdown-item>'. $html_assist[44]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=dfn class=dropdown-item>'. $html_assist[45]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=comment class=dropdown-item>'. $html_assist[46]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=multi-line-comment class=dropdown-item>'. $html_assist[75]. '</a></li>'.
	'</ul>'.
	'<div class=dropdown-divider></div>'.
	'<ul class=list-inline>'.
	'<li class="list-inline-item my-1"><a href="#" data-select=border class="dropdown-item border">'. $html_assist[47]. '</a>'.
	'<li class=list-inline-item><a href="#" data-select=border-top class="dropdown-item border-top">'. $html_assist[48]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-end class="dropdown-item border-end">'. $html_assist[49]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-bottom class="dropdown-item border-bottom">'. $html_assist[50]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-start class="dropdown-item border-start">'. $html_assist[51]. '</a></li>'.
	'<li><ul style="all:revert">'.
	'<li class=list-inline-item><a href="#" data-select=border-2 class="dropdown-item border border-2">'. $html_assist[52]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-3 class="dropdown-item border border-3">'. $html_assist[53]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-4 class="dropdown-item border border-4">'. $html_assist[54]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-5 class="dropdown-item border border-5 my-1">'. $html_assist[55]. '</a></li>'.
	'</ul>'.
	'<ul style="all:revert">'.
	'<li class=list-inline-item><a href="#" data-select=border-primary class="dropdown-item border border-primary mb-1">'. $html_assist[56]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-secondary class="dropdown-item border border-secondary mb-1">'. $html_assist[57]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-success class="dropdown-item border border-success mb-1">'. $html_assist[58]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-danger class="dropdown-item border border-danger mb-1">'. $html_assist[59]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-warning class="dropdown-item border border-warning mb-1">'. $html_assist[60]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-info class="dropdown-item border border-info mb-1">'. $html_assist[61]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-light class="dropdown-item border border-light mb-1">'. $html_assist[62]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-dark class="dropdown-item border border-dark mb-1">'. $html_assist[63]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=border-white class="dropdown-item border border-white mb-1">'. $html_assist[64]. '</a></li>'.
	'</ul>'.
	'</li>'.
	'<li class="list-inline-item my-1"><a href="#" data-select=rounded class="dropdown-item rounded bg-info text-info">'. $html_assist[65]. '</a>'.
	'<li class=list-inline-item><a href="#" data-select=rounded-top class="dropdown-item rounded-top bg-info text-info">'. $html_assist[66]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=rounded-end class="dropdown-item rounded-end bg-info text-info">'. $html_assist[67]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=rounded-bottom class="dropdown-item rounded-bottom bg-info text-info">'. $html_assist[68]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=rounded-start class="dropdown-item rounded-start bg-info text-info">'. $html_assist[69]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=rounded-circle class="dropdown-item rounded-circle bg-info text-info">'. $html_assist[70]. '</a>'.
	'<li class=list-inline-item><a href="#" data-select=rounded-pill class="dropdown-item rounded-pill bg-info text-info">'. $html_assist[71]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=rounded-1 class="dropdown-item rounded-1 bg-info text-info">'. $html_assist[72]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=rounded-2 class="dropdown-item rounded-2 bg-info text-info">'. $html_assist[73]. '</a></li>'.
	'<li class=list-inline-item><a href="#" data-select=rounded-3 class="dropdown-item rounded-3 bg-info text-info">'. $html_assist[74]. '</a></li>'.
	'</ul>'.
	'</div>'.
	'</div>';
	$javascript .= 'window.addEventListener("load",()=>{[].slice.call(document.querySelectorAll(".creates")||[]).map(el=>el.addEventListener("input",ev=>{l=encodeURIComponent(ev.target.value).replace(/%../g,"x").length,m='. $title_length. ';[].slice.call(document.querySelectorAll(".max")||[]).map(el=>el.innerText="'. sprintf($form_label[5], '"+(m-l)+"'). '");if(l>=m){[].slice.call(document.querySelectorAll(".creates")||[]).map(el=>el.classList.add("is-invalid"));[].slice.call(document.querySelectorAll("input[type=submit]")||[]).map(el=>el.setAttribute("disabled",true))}else{[].slice.call(document.querySelectorAll(".creates")||[]).map(el=>el.classList.remove("is-invalid"));[].slice.call(document.querySelectorAll("input[type=submit]")||[]).map(el=>el.removeAttribute("disabled"))}}));[].slice.call(document.querySelectorAll(".dropdown-item")||[]).map(el=>{el.addEventListener("click",e=>{e.preventDefault();e.stopPropagation();add(e.target,el.closest(\'div[id]\').id)})});function h(select){return select.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/\'/g,"&#039;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}function add(tag,id){let select=tag.dataset.select,textarea=document.getElementById(("ch"!==id?"":"login-")+"textarea"),pos=getAreaRange(textarea),val=textarea.value,range=val.slice(pos.start,pos.end),beforeNode=val.slice(0,pos.start),afterNode=val.slice(pos.end),insertNode;if(-1!==select.indexOf("border")){if(false!==/-(?!top|end|bottom|start)/.test(select))insertNode=range+" "+select;else insertNode=range+" class=\""+select+"\""}else if(-1!==select.indexOf("rounded"))insertNode=range+" class=\""+select+"\"";else if("hr"===select||"br"===select){if(range||pos.start!==pos.end){insertNode=range+"<"+select+">"}else if(pos.start===pos.end){insertNode="<"+select+">"}}else{if("code"===select)range=h(range);if("a"===select){if(range||pos.start!==pos.end){insertNode="<a href=\"\">"+range+"<\/a>"}else if(pos.start===pos.end){insertNode="<a href=\"\"><\/a>"}}else if("right"===select){if(range||pos.start!==pos.end){insertNode="<p class=\"text-end\">"+range+"<\/p>"}else if(pos.start===pos.end){insertNode="<p class=\"text-end\"><\/p>"}}else if("lead"===select){if(range||pos.start!==pos.end){insertNode="<span class=lead>"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=lead><\/span>"}}else if("center"===select){if(range||pos.start!==pos.end){insertNode="<p class=text-center>"+range+"<\/p>"}else if(pos.start===pos.end){insertNode="<p class=text-center><\/p>"}}else if("primary"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-primary text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-primary text-white px-1\"><\/span>"}}else if("success"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-success text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-success text-white px-1\"><\/span>"}}else if("green"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-success text-success px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-success text-success px-1\"><\/span>"}}else if("info"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-info text-info px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-info text-info px-1\"><\/span>"}}else if("warning"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-warning text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-warning text-white px-1\"><\/span>"}}else if("yellow"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-warning text-warning px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-warning text-warning px-1\"><\/span>"}}else if("danger"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-danger text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-danger text-white px-1\"><\/span>"}}else if("pink"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-danger text-danger px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-danger text-danger px-1\"><\/span>"}}else if("secondary"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-secondary text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-secondary text-white px-1\"><\/span>"}}else if("dark"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-dark text-white px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-dark text-white px-1\"><\/span>"}}else if("light"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-light text-body px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-light text-body px-1\"><\/span>"}}else if("white"===select){if(range||pos.start!==pos.end){insertNode="<span class=\"bg-white text-body px-1\">"+range+"<\/span>"}else if(pos.start===pos.end){insertNode="<span class=\"bg-white text-body px-1\"><\/span>"}}else if("dl"===select){if(range||pos.start!==pos.end){insertNode="\n<dl class=dl-horizontal>\n<dt>"+range+"<\/dt>\n<dd><\/dd>\n<\/dl>\n"}else if(pos.start===pos.end){insertNode="<dl class=dl-horizontal>\n<dt><\/dt>\n<dd><\/dd>\n<\/dl>\n"}}else if("li"===select){if(range||pos.start!==pos.end){insertNode="\n<ul class=list-group>\n<li class=\"list-group-item bg-transparent\">"+range+"<\/li>\n<li class=\"list-group-item bg-transparent\"><\/li>\n<li class=\"list-group-item bg-transparent\"><\/li>\n<\/ul>\n"}else if(pos.start===pos.end){insertNode="<ul class=list-group>\n<li class=\"list-group-item bg-transparent\"><\/li>\n<li class=\"list-group-item bg-transparent\"><\/li>\n<li class=\"list-group-item bg-transparent\"><\/li>\n<\/ul>\n"}}else if("table"===select){if(range||pos.start!==pos.end){insertNode="\n<table class=\"table table-striped table-dark\">\n<tr>\n<td>"+range+"<\/td>\n<\/tr>\n<\/table>\n"}else if(pos.start===pos.end){insertNode="\n<table class=\"table table-striped table-dark\">\n<tr>\n<td><\/td>\n<\/tr>\n<\/table>\n"}}else if("ltgt"===select){if(range||pos.start!==pos.end){insertNode="&lt;"+range+"&gt;"}else if(pos.start===pos.end){insertNode="&lt;&gt;"}}else if("comment"===select){if(range||pos.start!==pos.end){insertNode="<!--\n"+range+"\n-->"}else if(pos.start===pos.end){insertNode="<!--\n-->"}}else if("multi-line-comment"===select){if(range||pos.start!==pos.end){insertNode="/*\n"+range+"\n*/"}else if(pos.start===pos.end){insertNode="/*\n*/"}}else{if(range||pos.start!==pos.end){insertNode="<"+select+">"+range+"<\/"+select+">"}else if(pos.start===pos.end){insertNode="<"+select+">"+"<\/"+select+">"}}}textarea.value=beforeNode+insertNode+afterNode;textarea.setSelectionRange(pos.end,pos.end);textarea.focus()}function getAreaRange(obj){const pos=new Object();if(window.getSelection()){pos.start=obj.selectionStart;pos.end=obj.selectionEnd}return pos}});';
}

function sanitize_mail($e)
{
	if (str_contains($e, '@'))
	{
		$ex = explode('@', $e);
		return filter_var($ex[0]. '@'. idn_to_ascii($ex[1]), FILTER_SANITIZE_EMAIL);
	}
	elseif (filter_var(dec($e), FILTER_VALIDATE_EMAIL)) return $e;
}

function strip_tags_basename($str)
{
	return trim(strip_tags(basename($str)));
}

function trim_str_replace_basename($str)
{
	global $disallow_symbols, $replace_symbols;
	return trim(str_replace($disallow_symbols, $replace_symbols, basename($str)));
}

function scriptentities($str)
{
	if (!is_admin())
	{
		if (str_contains($str, '<?'))
			$str = str_replace(['<?', '?>'], ['&lt;?', '?&gt;'], $str);
		if (str_contains($str, '<script'))
			$str = preg_replace_callback('|(<script.*?/script[^>]*>)|is', function ($m) {return hs($m[1]);}, $str);
		if (str_contains($str, '<form'))
			$str = preg_replace_callback('|(<form.*?/form[^>]*>)|is', function ($m) {return hs($m[1]);}, $str);
		if (str_contains($str, 'frame>'))
			$str = preg_replace_callback('|(<i?frame.*?/i?frame[^>]*>)|is', function ($m) {return hs($m[1]);}, $str);
		if (str_contains($str, '/*'))
			$str = str_replace(['/*', '*/'], ['/&#042;', '&#042;/'], $str);
	}
	return $str;
}

function price($price, $shipping)
{
	global $tax;
	$cost = (int)$price + (int)$shipping;
	return !isset($tax) ? $cost : round($cost + $cost * $tax / 100);
}

function expiry(int $time, int $til=0)
{
	global $days_left, $valid_for, $now;
	return sprintf($til ? $days_left : $valid_for, round(($time - $now) / 86400));
}

function paypal_form($m)
{
	global $sandbox_mail_address, $business_mail_address, $current_article_dir, $time_format, $naming_rights_message,
	$mail_address, $basetitle, $currency_code, $current_url, $delivery_times, $btn, $url, $javascript, $separator, $n,
	$purchased_dir, $login, $now, $price_format, $price_short_format, $shipping, $item_total, $locale_code, $placeholder;
	static $i; ++$i;
	$m = array_filter(array_map('trim', $m));
	$ppf = '<form'. (!isset($_SESSION['l']) ? '' : ' action="https://www.'. (!isset($sandbox_mail_address) ? '' : 'sandbox.'). 'paypal.com/cgi-bin/webscr"'). ' method=post name="form'. $i. '" class=paypal-form>';
	if (is_file($item_img = $current_article_dir. '/item-images/'. $i. '.jpg')) $ppf .= img($item_img, '', true);
	if (!str_contains($m[1], '&#10;'))
	{
		if (!str_contains($m[1], '|'))
		{
			$price = price($m[1], $shipping);
			$formated_price = sprintf($price_format, $price, $shipping);
			$ppf .= '<input type=hidden name=item_name value="'. enc($basetitle. $i). '">';
			$ppf .= '<input type=hidden name=amount value="'. $price. '">';
			$ppf .= '<div class="item-price mb-3">'. $formated_price. '</div>';
		}
		else
		{
			$pn = array_map('strip_tags_basename', explode('|', $m[1], 4));
			$name = $pn[1] ?? ' ';
			$item_count = $pn[2] ?? 0;
			if (false !== ($timestamp = strtotime($name))) $name = $timestamp;
			$sold_dir = $purchased_dir. '/'. $name;
			$glob_sold = !is_dir($sold_dir) ? '' : glob($sold_dir. '/*', GLOB_NOSORT);
			$sold_count = !is_dir($sold_dir) ? 0 : count($glob_sold);
			if (isset($pn[2], $pn[3]) && !is_numeric($pn[3]))
			{
				$shipping = $delivery_times = null;
				$intangible_products = !is_numeric($pn[2]) ? ($item_count = 1) : 2;
				if ($glob_sold)
				{
					foreach ($glob_sold as $sold_files)
					{
						$sold_file = !is_file($sold_files) ? [] : file($sold_files, FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);
						$sold_title = get_title($sold_files);
						if (isset($sold_file[0], $sold_file[1], $_SESSION['l']) && $separator === trim($sold_file[1]) && !isset($sold_file[2]) && $_SESSION['l'] === trim($sold_file[0]))
							naming_form($sold_title, $sold_files);
						if (isset($sold_file[2]))
						{
							$buyer = 'users/'. trim($sold_file[0]). '/prof/';
							$ppf .=
							'<div class="w-100 h-100 position-absolute top-0 start-0 bg-dark" style="--bs-bg-opacity:.3;z-index:5">'.
							'<div class="card position-absolute top-50 start-50 translate-middle text-start shadow w-100">'.
							'<div class="card-header d-flex flex-wrap bg-white small">'.
							'<a class="text-secondary me-auto" href="'. $url. '?user='. str_rot13(trim($sold_file[0])). '">'. avatar($buyer, 20). ' '. handle($buyer). '</a>'.
							'<small>'. date($time_format, basename($sold_files)). '</small>'.
							'</div>'.
							'<div class="card-body bg-info text-info">'. sprintf($naming_rights_message[3], get_title($sold_files), ($naming = h($sold_file[2]))). '</div>'.
							'</div>'.
							'</div>';
							$javascript .= '(document.form'. $i. '.closest(".card")||document.form'. $i. ').classList.add("position-relative");';
						}
					}
				}
			}
			$price = price($pn[0] ?? 0, $pn[3] ?? $shipping);
			$formated_price = sprintf((!$shipping && !$delivery_times ? $price_short_format : $price_format), $price, $pn[3] ?? $shipping);
			$ppf .= '<input type=hidden name=item_name value="'. enc(!isset($pn[1]) ? $basetitle. $i : $name). '">';
			$ppf .= '<input type=hidden name=amount value="'. $price. '">';
			$ppf .= '<div class="item-name-'. $i. ' paypal-form-title mb-3">'. (is_numeric($name) && 10 === strlen($name) ? expiry($name) : $name). '</div>';
			if (isset($pn[2]))
			{
				$sold = (int)$item_count - (int)$sold_count;
				$stock = (0 >= $sold) ? 0 : $item_count;
				if (is_numeric($pn[2])) $ppf .= '<div class="item-total-'. $i. ' mb-3">'. sprintf($item_total, $sold). '</div>';
			}
			$ppf .= '<div class="item-price-'. $i. ' paypal-form-price mb-3">'. $formated_price. '</div>';
		}
	}
	else
	{
		$ppf .= '<input type=hidden name=item_name id="item_name_'. $i. '">';
		$exn = array_values(array_filter(array_map('trim', explode('&#10;', $m[1]))));
		$radio = true;
		foreach ($exn as $k => $p)
		{
			$ppf .= '<div class="form-check mb-3" id="f'. $i. 'c'. $k. '">';
			if (!str_contains($p, '|'))
			{
				$price = price($p, $shipping);
				$formated_price = sprintf($price_format, $price, $shipping);
				$ppf .= '<input required class=form-check-input type=radio id="f'. $i. 'r'. $k. '" name=amount title="'. $formated_price. '" value="'. $price. '"'.
				(0 !== $k ? '' : ' checked'). ' data-value="'. enc($basetitle. $i. '-'. $k). '">';
				$ppf .= '<label class=form-check-label for="f'. $i. 'r'. $k. '">'. $formated_price. '</label>';
			}
			else
			{
				$pn = array_map('strip_tags_basename', explode('|', $p, 4));
				$name = $pn[1] ?? ' ';
				$item_count = $pn[2] ?? 0;
				if (false !== ($timestamp = strtotime($name))) $name = $timestamp;
				$sold_dir = $purchased_dir. '/'. $name;
				$glob_sold = !is_dir($sold_dir) ? '' : glob($sold_dir. '*/*', GLOB_NOSORT);
				$sold_count = !is_dir($sold_dir) ? 0 : count($glob_sold);
				if (isset($pn[2], $pn[3]) && !is_numeric($pn[3]))
				{
					$shipping = $delivery_times = null;
					$intangible_products = !is_numeric($pn[2]) ? ($item_count = 1) : 2;
					if ($glob_sold)
					{
						foreach ($glob_sold as $sold_files)
						{
							$sold_file = !is_file($sold_files) ? [] : file($sold_files, FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);
							$sold_title = get_title($sold_files);
							if (isset($sold_file[2]))
							{
								$buyer = 'users/'. trim($sold_file[0]). '/prof/';
								$ppf .=
								'<div class="w-100 h-100 position-absolute top-0 start-0" style="z-index:5">'.
								'<div class="card position-absolute top-50 start-50 translate-middle text-start shadow w-100">'.
								'<div class="card-header d-flex flex-wrap bg-white small">'.
								'<a class="text-secondary me-auto" href="'. $url. '?user='. str_rot13(trim($sold_file[0])). '">'. avatar($buyer, 20). ' '. handle($buyer). '</a>'.
								'<small>'. date($time_format, basename($sold_files)). '</small>'.
								'</div>'.
								'<div class="card-body bg-info text-info">'. sprintf($naming_rights_message[3], get_title($sold_files), ($naming = h($sold_file[2]))). '</div>'.
								'</div>'.
								'</div>';
								$javascript .= '(document.getElementById("f'. $i. 'c'. $k. '")||document.form'. $i. ').classList.add("position-relative","py-4");';
							}
							if (isset($sold_file[0], $sold_file[1], $_SESSION['l']) && $separator === trim($sold_file[1]) && !isset($sold_file[2]) && $_SESSION['l'] === trim($sold_file[0]))
								naming_form($sold_title, $sold_files);
						}
					}
				}
				$price = price($pn[0] ?? 0, $pn[3] ?? $shipping);
				$formated_price = sprintf((!$shipping && !$delivery_times ? $price_short_format : $price_format), $price, $pn[3] ?? $shipping);
				$ppf .= '<input required class=form-check-input type=radio id="f'. $i. 'r'. $k. '" name=amount title="'. $formated_price. '" value="'. $price. '"'.
				(isset($pn[2]) && (0 >= ((int)$item_count - (int)$sold_count)) ? ' disabled' : (0 !== $k ? '' : ' checked')). ' data-value="'. enc(!isset($pn[1]) ? $basetitle. $i. '-'. $k : $name). '">';
				$ppf .=
				'<label class=form-check-label for="f'. $i. 'r'. $k. '">'.
				(!isset($pn[1]) ? '' : (is_numeric($name) && 10 === strlen($name) ? expiry($name) : $name). '<br>').
				(!isset($pn[2]) || !is_numeric($pn[2]) ? '' : sprintf($item_total, ((int)$item_count - (int)$sold_count)). '<br>').
				$formated_price.
				'</label>';
			}
			$ppf .= '</div>';
		}
	}
	if (isset($delivery_times))
	{
		$ppf .= '<select name=item_number class="form-select mb-4">';
		foreach ($delivery_times as $j => $v) $ppf .= '<option value='. $j. (0 === $j ? ' selected' : ''). '>'. $v. '</option>';
		$ppf .= '</select>';
	}
	if (isset($_SESSION['l']))
	{
		if (is_file($shop_logo = 'images/shop-logo.png'))
		{
			[$w, $h] = getimagesize($shop_logo);
			if (150 >= $w && 50 >= $h) $ppf .= '<input type=hidden name=image_url value="'. $url. $shop_logo. '">';
		}
		if (!$shipping) $ppf .= '<input type=hidden name=no_shipping value=1>';
		$ppf .= '<input type=hidden name=lc value="'. $locale_code. '">';
		$ppf .= '<input type=hidden name=charset value=utf-8>';
		$ppf .= '<input type=hidden name=cmd value="_xclick">';
		$ppf .= '<input type=hidden name=business value="'. ($sandbox_mail_address ?? $business_mail_address ?? $mail_address). '">';
		$ppf .= '<input type=hidden name=currency_code value="'. $currency_code. '">';
		$ppf .= '<input type=hidden name=return value="'. $current_url. '&amp;success=1">';
		$ppf .= '<input type=hidden name=cancel_return value="'. $current_url. '&amp;cancel=1">';
		$ppf .= '<input type=hidden name=notify_url value="'. $url. '?i='. str_rot13($_SESSION['l']). '&amp;c='.
		r(filter_input(INPUT_GET, 'categ', FILTER_CALLBACK, ['options' => 'strip_tags_basename'])). '&amp;t='.
		r(filter_input(INPUT_GET, 'title', FILTER_CALLBACK, ['options' => 'strip_tags_basename'])).
		(isset($delivery_times) ? '' : (isset($intangible_products) && 1 === $intangible_products ? '&amp;n=1' : '&amp;n=2')). '">';
		$ppf .= '<input type=hidden name=custom value="'. enc((int)$price. ','. $_SESSION['l']). '">';
		$ppf .= (isset($stock) && 0 === $stock) ?
			'<button disabled class="btn btn-outline-danger"><svg xmlns="http://www.w3.org/2000/svg" width="1.4em" height="1.4em" fill="currentColor" class="bi bi-bag-dash" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M5.5 10a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z"/><path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/></svg> '. $btn[isset($intangible_products) && 1 === $intangible_products ? 16: 11]. '</button>'
		:
			'<button class="btn btn-outline-primary" type=submit id="submit'. $i. '"><svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-paypal" viewBox="0 0 16 16"><path d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.351.351 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91.379-.27.712-.603.993-1.005a4.942 4.942 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.687 2.687 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.695.695 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016c.217.124.4.27.548.438.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.873.873 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.352.352 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32.845-5.214Z"/></svg> '. $btn[10]. '</button>';
	}
	else
		$ppf .= '<a class="btn btn-primary" href="'. $current_url. '&amp;'. r($login). '='. $now. '#login" id="submit'. $i. '">'. $btn[12]. '</a>';
	if (isset($radio)) $javascript .= 'if(document.form'. $i. '.amount.length===document.form'. $i. '.querySelectorAll("input[type=radio]:disabled").length)document.getElementById("submit'. $i. '").disabled=true;document.form'. $i. '.querySelectorAll("input[type=radio][name=amount]").forEach(r=>{if(r.checked)document.form'. $i. '.item_name_'. $i. '.value=r.dataset.value;r.addEventListener("change",e=>document.form'. $i. '.item_name_'. $i. '.value=e.target.dataset.value)});';
	$ppf .= '</form>';
	return $ppf;
}

function naming_form($sold_title, $sold_files)
{
	global $btn, $current_url, $javascript, $n, $naming_rights_message, $placeholder;
	echo
	'<div id=naming-rights-section class="modal fade">'.
	'<div class="modal-dialog modal-dialog-centered modal-xl">'.
	'<div class=modal-content>'.
	'<div class=modal-header>'.
	'<h2 class="modal-title h4">'. sprintf($naming_rights_message[0], $_SESSION['h'], $sold_title). '</h2>'.
	'<button type=button class=btn-close data-bs-dismiss=modal></button>'.
	'</div>'.
	'<div id=naming-rights-body class=modal-body>'.
	'<div class="input-group input-group-lg mb-3">'.
	'<input id=naming-rights-input class=form-control type=text placeholder="'. sprintf($placeholder[12], $sold_title). '">'.
	'<button id=naming-rights-submit class="btn btn-outline-primary">'. $btn[15]. '</button>'.
	'</div>'.
	'</div>'.
	'</div>'.
	'</div>'.
	'</div>';
	$javascript .= 'new bootstrap.Modal(document.getElementById("naming-rights-section")).show();document.getElementById("naming-rights-submit").onclick=()=>{let fd=new FormData(),fdv=strip_tags(document.getElementById("naming-rights-input").value);if(!fdv){document.getElementById("naming-rights-input").classList.add("is-invalid");return false}fd.append("naming-rights",fdv);if(!confirm("'. sprintf($naming_rights_message[1], '"+fdv+"'). '"))return false;fetch("'. $current_url. '",{method:"POST",cache:"no-cache",body:fd}).then(()=>document.getElementById("naming-rights-body").innerHTML="<div class=\"alert alert-success\">'. sprintf($naming_rights_message[2], '"+fdv+"'). '<\/div>")};function strip_tags(str){let tmp=document.createElement("div");tmp.innerHTML=str;return tmp.textContent}';
	if ($naming_rights = !filter_has_var(INPUT_POST, 'naming-rights') ? '' : filter_input(INPUT_POST, 'naming-rights', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
		file_put_contents($sold_files, $n. $naming_rights, FILE_APPEND);
}

function shopping_info()
{
	global $aside, $btn, $contact_us, $footer, $javascript, $url, $shopping_info, $lang, $paypal_logo, $use_datasrc;
	if (filter_has_var(INPUT_GET, 'success'))
	{
		$footer .=
		'<div class="modal fade" id=success aria-hidden=true aria-labelledby=successModal tabindex=-1>'.
		'<div class="modal-dialog modal-dialog-centered">'.
		'<div class=modal-content>'.
		'<div class=modal-header>'.
		'<h5 class=modal-title id=successModal>'. $shopping_info[2]. '</h5>'.
		'<button type=button class=btn-close data-bs-dismiss=modal></button>'.
		'</div>'.
		'<div class=modal-body>'. $shopping_info[3]. '</div>'.
		'<div class=modal-footer>'.
		'<button type=button class="btn btn-secondary" data-bs-dismiss=modal>'. $btn[13]. '</button>'.
		'<a class="btn btn-danger" href="'. $url. $contact_us. '">'. $contact_us. '</a>'.
		'</div>'.
		'</div>'.
		'</div>'.
		'</div>';
		$javascript .= 'new bootstrap.Modal(document.getElementById("success")).show();';
	}
	$aside .= '<div id=paypal-logo class="card p-3"><div class="h5 mb-3">'. $shopping_info[0]. '</div><p class=mb-3>'. $shopping_info[1]. '</p>';
	if (isset($paypal_logo))
		$aside .= '<a href="https://www.paypal.com/'. $lang. '/webapps/mpp/logo/about" target="_blank"><img class=img-fluid '. (!$use_datasrc ? '' : 'data-'). 'src="'. $paypal_logo. '"></a>';
	$aside .= '</div>';
}
