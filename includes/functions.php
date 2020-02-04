<?php
function r($path)
{
	if (strpos($path, '%') !== false)
		return $path;
	else
		return str_replace(['%2F', '%3A'], ['/', ':'], rawurlencode($path));
}

function d($enc)
{
	return rawurldecode(basename(html_entity_decode($enc)));
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
		$unit = ['B', 'KB', 'MB', 'GB'];
		return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2). $unit[$i];
	}
}

function timestamp()
{
	return gmdate('D, d M Y H:i:s T', getlastmod());
}

function is_ssl()
{
	if (isset($_SERVER['HTTPS']) && isset($_SERVER['SSL']) || isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR']))
		return true;
}

function complementary($hsla)
{
	if (list($h, $s, $l, $a) = explode(',', str_replace([' ', 'hsla', '(', ')', '%'], '', $hsla)))
	{
		if ((int)$l === 10) $l = 100;
		return 'hsla('. ($h += ($h > 180) ? -180 : 180). ', '. $s. '%, '. $l. '%, '. $a. ')';
	}
}

function get_hsl($colour)
{
	if ($colour[0] === 'h')
	{
		if (list($h, $s, $l) = explode(',', str_replace(['hsl', '(', ')', '%'], '', $colour))) return [$h, $s, $l];
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
	else list($r, $g, $b) = explode(',', str_replace(['rgb', '(', ')'], '', $colour));
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
	ob_start();
	echo preg_replace('/<script.*?\/script>/s', '', file_get_contents($file));
	$text = strip_tags(ob_get_clean());
	$text = str_replace([$n. $n. $n, $n. $n], $n, $text);
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
	global $url, $get_categ, $get_title, $page_name, $current_url, $query, $download_contents, $forum, $forum_thread;
	if ($get_categ && $get_title)
		return $current_url. '&amp;pages='. $nr;
	elseif ($get_categ && !$get_title)
		return $url. $get_categ. '&amp;pages='. $nr;
	elseif ($query)
		return $url. '?query='. r($query). '&amp;pages='. $nr;
	elseif ($page_name === $download_contents)
		return $url. r($download_contents). '&amp;pages='. $nr;
	elseif ($page_name === $forum)
		return $url. r($forum). (!$forum_thread ? '' :  '&amp;thread='. r($forum_thread)). '&amp;pages='. $nr;
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
	($target === '_blank' ? ' rel="noopener noreferrer"' : ''). '>' .
	(!$name ? h($uri) : h($name)) .
	'</a>';
}

function img($src, $class='', $show_exif_comment=false)
{
	global $url, $source, $n, $classname, $get_categ, $get_title, $index_type, $get_page, $use_thumbnails, $use_categ_thumbnails, $line_breaks;
	if ($extension = get_extension($src))
	{
		$image_extensions = ['.gif', '.jpg', '.jpeg', '.png', '.svg'];
		$video_extensions = ['.mp4', '.ogg', '.webm'];
		if ($scheme = strpos($src, '://')) $addr = parse_url($src);
		if (array_search(strtolower($extension), $image_extensions) !== false)
		{
			$alt = h(basename($src));
			$exif = @exif_read_data($src, '', '', true);
			$exif_thumbnail = isset($exif['THUMBNAIL']['THUMBNAIL']) ? $exif['THUMBNAIL']['THUMBNAIL'] : '';
			$exif_comment = isset($exif['COMMENT']) && $show_exif_comment ? str_replace($line_breaks, '<br>', h(trim(strip_tags($exif['COMMENT'][0])))) : '';
			list($width, $height, $type, $attr) = @getimagesize($src);
			if ($exif_thumbnail) list($width_sm, $height_sm, $type_sm, $attr_sm) = getimagesizefromstring($exif_thumbnail);
			$img = $exif_comment ?
			'<figure class="align-top img-thumbnail text-center d-inline-block" style="max-width:'. $width. 'px">'. $n.
			'<img class="img-fluid '. $class. '" src="'. $url. r($src). '" alt="'. $alt. '" '. $attr. '>'. $n.
			'<p class="text-center wrap my-2">'. $exif_comment. '</p>'. $n.
			'</figure>'. $n :
			'<img class="align-top img-fluid img-thumbnail '. $class. '" src="'. $url. r($src). '" alt="'. $alt. '" '. $attr. '>'. $n;
			if ($get_title || $get_page)
			{
				if ($scheme !== false)
					return
					'<figure class="img-thumbnail text-center d-inline-block '. $class. '" style="max-width:'. $width. 'px">'. $n.
					'<a data-fancybox=gallery href="'. $src. '">'. $n.
					'<img class=img-fluid src="'. $addr['scheme']. '://'. $addr['host']. r($addr['path']). '" alt="'. $alt. '" '. $attr. '>'. $n.
					'</a>'. $n.
					'<small class="blockquote-footer my-2 text-right">'. $n.
					'<a href="'. $addr['scheme']. '://'. $addr['host']. '/" target="_blank" rel="noopener noreferrer">'. sprintf($source, h($addr['host'])). '</a>'. $n.
					'</small>'. $n.
					'</figure>'. $n;
				elseif ($exif_comment && !$exif_thumbnail)
					return
					'<figure class="align-top img-thumbnail text-center d-inline-block" style="max-width:'. $width. 'px">'. $n.
					'<a data-fancybox=gallery class="d-inline-block mb-2 mr-1" data-caption="'. $exif_comment. '" href="'. $url. r($src). '">'.
					'<img class="align-top img-fluid '. $class. '" src="'. $url. r($src). '" alt="'. $alt. '" '. $attr. '>'.
					'</a>'. $n.
					'<figcaption class="text-center mb-2">'. $exif_comment. '</figcaption>'. $n.
					'</figure>'. $n;
				else
					return
					'<figure class="d-inline-block m-2">'. $n.
					'<a data-fancybox=gallery href="'. $url. r($src). '" data-caption="'. $exif_comment. '">'. $n.
					($exif_thumbnail && $use_thumbnails ?
					'<img class="'. $class. ' align-top img-thumbnail" src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '" '. $attr_sm. '>' :
					$img).
					'</a>'. $n.
					'</figure>'. $n;
			}
			else
			{
				if (!$get_categ && !$get_title)
				{
					switch($index_type)
					{
						case 2: $class .= 'd-block mx-auto rounded-circle'; break;
						case 3: $class .= 'rounded-sm'; break;
						case 4: $class .= 'd-block mx-auto mr-4 rounded-lg'; break;
					}
				}
				if ($exif_thumbnail && $use_categ_thumbnails)
				{
					if ($get_categ || $index_type === 1)
						$img =
						'<span class="card-header d-block text-center">'. $n.
						'<img class="img-fluid '. $class. '" src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '" '. $attr_sm. '>'. $n.
						'</span>';
					else
						$img =
						'<span'. ($classname ? ' class="d-block '. $classname. ' position-relative" style="max-width:'. $width_sm. 'px"' : ''). '>'. $n.
						'<img class="img-fluid '. $class. '" src="data:'. image_type_to_mime_type(exif_imagetype($src)). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. $alt. '" '. $attr_sm. '>'. $n.
						'</span>';
				}
				else
					$img =
					'<span'. ($classname ? ' class="d-block '. $classname. ' position-relative" style="max-width:'. $width. 'px"' : ''). '>'. $n.
					'<img class="img-fluid '. $class. ' card-img-top" src="'. $url. r($src). '" alt="'. $alt. '" '. $attr. '>'. $n.
					'</span>';

				return '<a href="'. $url. r(basename(dirname($dirname = dirname(dirname($src)))). '/'. basename($dirname)). '">'. $img. '</a>';
			}
		}
		elseif (array_search(strtolower($extension), $video_extensions) !== false)
		{
			$vtt = str_replace($extension, '.vtt', $src);
			if ($get_title || $get_page)
			{
				if ($scheme !== false)
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
				return '<video class="align-top '. $class. '" controls preload=none><source src="'. $url. r($src). '"><track src="'. $url. r($vtt). '" default=default></video>'. $n;
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
	return implode('', array_slice($intervals, 0, 1));
}

function pager($num, $max)
{
	global $number_of_pager, $article, $nav_laquo, $nav_raquo, $n;
	$article .= '<ul class="justify-content-center pagination my-4">'. $n;

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
		if ($i == $max) break;
		++$i;
	}
	if ($num < $max)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($num+1). '">'. $nav_raquo. '</a></li>'. $n;
	if ($num < $max - 1)
		$article .= '<li class=page-item><a class=page-link href="'. get_page($max). '">'. $nav_raquo. $nav_raquo. '</a></li>'. $n;
	$article .= '</ul>'. $n;
}

function sideless($hide=false, $force=false)
{
	global $header, $get_title, $get_page;
	if ($get_title || $get_page || $force)
	{
		$header .= '<style>';
		if ($hide)
			$header .= '#side{display:none!important}#main{max-width:100%;flex:0 0 100%}';
		else
			$header .= '#main,#side{max-width:100%;flex:0 0 100%}';
		$header .= '</style>';
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

function get_logo($name=false, $class='')
{
	global $site_name, $url;
	if (is_file($logo = 'images/logo.png'))
		return '<img src="'. $url. $logo. '" class=img-fluid alt="'. $site_name. '">'.
		(!$name ? '' : '<span class="'. (!$class ? 'border-0 d-block h1 mt-4' : $class). '">'. $site_name. '</span>');
	else
		return $site_name;
}

function not_found()
{
	global $article, $breadcrumb, $header, $site_name, $not_found, $n;
	http_response_code(404);
	$header .= '<title>'. $not_found[0]. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .= '<li class="breadcrumb-item active">'. http_response_code(). '</li>'. $n;
	$article .=
	'<h1 class="h3 mb-4">'. $not_found[0]. '</h1>'. $n.
	'<div class="article not-found">'. $not_found[1]. '</div>'. $n;
}

function toc($in_article=false)
{
	global $header, $article, $aside, $footer, $sidebox_title, $sidebox_order, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class, $get_title, $get_page;
	if ($get_title || $get_page)
	{
		$toc_content = '<div class="'. $sidebox_title_class[4]. '">'. $sidebox_title[9];
		if ($in_article)
			$toc_content .= '<button class="navbar-toggler btn-sm" data-toggle=collapse data-target=#toctoggle accesskey=p tabindex=0><span class=navbar-toggler-icon></span></button>';
		$toc_content .= '</div><div data-spy=scroll data-target=".article" data-offset=0 id=toctoggle class="'. $sidebox_wrapper_class[1]. '"></div>';

		if ($in_article)
			$article .= '<div id=toc class="'. $sidebox_wrapper_class[2]. '">'. $toc_content. '</div>';
		else
			$aside .= '<div id=toc class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[8]. '">'. $toc_content. '</div>';
		$footer .= '<script>let num=1,toc="",toclv=lv=0;$(".article :header").each(function(){this.id="toc"+num;tag=this.nodeName.toLowerCase();num++;if(tag==="h2")lv=1;else if(tag==="h3")lv=2;else if(tag==="h4")lv=3;else if(tag==="h5")lv=4;else if(tag==="h6")lv=5;while(toclv<lv){toc+="<ul>";toclv++}while(toclv>lv){toc+="<\/ul>";toclv--}toc+="<li><a class=\"'. $sidebox_content_class[4]. '\" href=\"#"+this.id+"\" title=\""+$(this).text()+"\">"+$(this).text()+"<\/a><\/li>"});while(toclv>0){toc+="<\/ul>";toclv--}$("#toc").fadeIn("slow");$("#toctoggle").html(toc)</script>';
	}
}

function unsession()
{
	if (isset($_SESSION['l'],$_SESSION['h']))
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
		session_set_cookie_params(86400, $dir === '/' ? '/' : $dir. '/', $server, is_ssl(), true);
		session_start((['cookie_lifetime' => 86400]));
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
	if ($str && isset($session_txt))
		return trim(strtr(base64_encode(openssl_encrypt(serialize(basename($str)), 'AES-256-CBC', hash('whirlpool', file_get_contents($session_txt)), OPENSSL_RAW_DATA, substr(sha1_file($session_txt), 5, 16))), '+/', '-_'), '=');
}

function dec($str)
{
	global $session_txt;
	if ($str && isset($session_txt))
		return @unserialize(rtrim(openssl_decrypt(base64_decode(strtr(basename($str), '-_', '+/')), 'AES-256-CBC', hash('whirlpool', file_get_contents($session_txt)), OPENSSL_RAW_DATA, substr(sha1_file($session_txt), 5, 16)), "\0"));
}

function sess_err($str)
{
	global $aside, $footer, $login_try_again, $sidebox_wrapper_class, $sidebox_title_class, $sidebox_content_class, $ticket_warning, $​ask_admin, $n;
	$aside .=
	'<div id=login class="'. $sidebox_wrapper_class[0]. '">'. $n.
	'<div class="'. $sidebox_title_class[3]. '">'. $str. '</div>'. $n.
	'<p class="'. $sidebox_content_class[3]. '">'. ($str === $ticket_warning[3] ? $​ask_admin : $login_try_again). '</p>'. $n.
	'</div>'. $n;
	$footer .= '<script>location.hash="login"</script>';
}

function handle($dir)
{
	global $mail_address, $admin_suffix;
	if (is_file($handle = $dir. 'handle') && filesize($handle))
		$handle = h(file_get_contents($handle));
	else
	{
		$lp = explode('@', dec(basename(dirname($dir))));
		$handle = h($lp[0]);
	}
	if (dec(basename(dirname($dir))) === $mail_address)
		$handle .= $admin_suffix;
	return $handle;

}

function avatar($dir)
{
	if (is_file($img = $dir. 'avatar') && filesize($img) && strpos($base64_img = file_get_contents($img), 'base64') !== false)
		return '<img src="'. strip_tags($base64_img). '" class="d-block rounded mx-auto" alt="">';
	else
		return '<span style="background-color:'. (is_file($bgcolor = $dir. '/bgcolor') && filesize($bgcolor) ? h(file_get_contents($bgcolor)) : ''). '" class="avatar align-items-center d-flex justify-content-center font-weight-bold display-3 rounded mx-auto text-center text-white">'. mb_substr(handle($dir), 0, 1). '</span>';
}

function flow($a, $b, $c, $d)
{
	global $n;
	return
	'<h3>'. $a[0]. '</h3>'. $n.
	'<ol class=flow>'. $n.
	'<li'. ($d === 1 ? ' class=active' : ''). '>'. sprintf($a[1], $b, $c). '</li>'. $n.
	'<li'. ($d === 2 ? ' class=active' : ''). '>'. sprintf($a[2], $c). '</li>'. $n.
	'<li'. ($d === 3 ? ' class=active' : ''). '>'. sprintf($a[3], $b). '</li>'. $n.
	'<li'. ($d === 4 ? ' class=active' : ''). '>'. sprintf($a[4], $c). '</li>'. $n.
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
	if (is_dir($dir) && substr(decoct(fileperms($dir)), 2) !== '700') return true;
}

function hs($s)
{
	$s = str_replace("\t", '    ', $s);
	foreach (['autofocus', 'disabled', 'multiple', 'required', 'selected'] as $o)
	{
		if (strpos($s, $o) !== false)
			$s = str_replace($o, '<span style="color:ForestGreen">' . $o . '</span>', $s);
	}

	if (strpos($s, '=') !== false)
		$s = preg_replace('/(?!!|=)([\w-]+) ?= ?(&quot;|&#039;)/is', '<span style="color:ForestGreen">\\1</span>=\\2', $s);

	if (strpos($s, '&lt;') !== false && strpos($s, '&gt;') !== false)
		$s = preg_replace('/&lt;(?!!--)(.*?)&gt;/s', '<span style="color:DarkBlue">&lt;\\1&gt;</span>', $s);

	if (strpos($s, '&amp;nbsp;') !== false)
		$s = str_replace('&amp;nbsp;', '<span style="color:DimGray">&amp;nbsp;</span>', $s);

	if (strpos($s, '&#039;') !== false)
		$s = preg_replace_callback('/&#039;(.*?)&#039;/s', function ($t){return '&#039;<span style="color:Crimson">' . strip_tags($t[1]) . '</span>&#039;';}, $s);

	if (strpos($s, '&quot;') !== false)
		$s = preg_replace_callback('/&quot;(.*?)&quot;/s', function ($t){return '&quot;<span style="color:Crimson">' . strip_tags($t[1]) . '</span>&quot;';}, $s);

	if (strpos($s, '/*') !== false)
		$s = preg_replace_callback('|(/\*.*?\*/)|s', function ($t){return '<span style="color:DarkOrange">' . strip_tags($t[1]) . '</span>';}, $s);

	if (strpos($s, '//') !== false)
		$s = preg_replace_callback('|^(.*?)?(?<![:(>&quot;&#039;])(//.*?)$|mi', function ($t){return $t[1] . '<span style="color:DarkOrange">' . strip_tags($t[2]) . '</span>';}, $s);

	if (strpos($s, '&lt;!--') !== false)
		$s = preg_replace_callback('/(&lt;!--.*?--&gt;)/s', function ($t){return '<span style="color:DarkOrange">' . strip_tags($t[1]) . '</span>';}, $s);

	if (strpos($s, '&lt;script') !== false)
		$s = preg_replace_callback('/(&lt;script.*?&gt;)(.*?)(&lt;\/script&gt;)/is', function ($t){return $t[1] . '<span style="color:DimGray">' . strip_tags($t[2]) . '</span>' . $t[3];}, $s);

	if (strpos($s, '&lt;style') !== false)
		$s = preg_replace_callback('/(&lt;style.*?&gt;)(.*?)(&lt;\/style&gt;)/is', function ($t){return $t[1] . '<span style="color:Gray">' . strip_tags($t[2]) . '</span>' . $t[3];}, $s);

	if (strpos($s, '「') !== false)
		$s = preg_replace_callback('/(「)(.*?)(」)/is', function ($t){return $t[1] . '<strong>' . strip_tags($t[2]) . '</strong>' . $t[3];}, $s);

	if (strpos($s, '『') !== false)
		$s = preg_replace_callback('/(『)(.*?)(』)/is', function ($t){return $t[1] . '<strong>' . strip_tags($t[2]) . '</strong>' . $t[3];}, $s);

	if (strpos($s, '【') !== false)
		$s = preg_replace_callback('/(【)(.*?)(】)/is', function ($t){return $t[1] . '<strong>' . strip_tags($t[2]) . '</strong>' . $t[3];}, $s);

	if (strpos($s, '&lt;?') !== false)
		$s = preg_replace_callback('/(&lt;\?.*?\?&gt;)/is', function ($t){return highlight_string(html_entity_decode(strip_tags($t[1]), ENT_QUOTES), true);}, $s);

	if (strpos($s, '[') !== false)
	{
		$s = preg_replace_callback('/\[url=?(http.*?)?\](.*?)\[\/url\]/i', function ($t)
		{
			if (!$t[1])
				return '<a href="'. $t[2]. '" target="_blank" rel="noopener noreferrer">'. h($t[2]). '</a>';
			else
				return '<a href="'. $t[1]. '" target="_blank" rel="noopener noreferrer">'. h($t[2]). '</a>';
		}, $s);
	}
	return $s;
}

function blacklist($email, $blacklist = './forum/blacklist.txt')
{
	if (filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$list = file($blacklist, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if (!in_array($email, $list, true)) return $email;
	}
}
