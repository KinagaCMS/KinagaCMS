<?php
include '../includes/functions.php';
include '../includes/config.php';
$img_array = [];
$page = !filter_has_var(INPUT_GET, 'p') ? 1 : (int)filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.', FilesystemIterator::SKIP_DOTS));
if (1 > $page) $page = 1;
foreach ($files as $images)
{
	if (!$images->isFile()) continue;
	$sort[] = false === strpos($images, '.php') && false === strpos($images, '.vtt') && false === strpos($images, '.md') ? $images->getMTime(). $delimiter. trim($images, './') : '';
}
$sort = array_filter($sort);
rsort($sort);
$count_imgs = count($sort);
$maxpage = ceil($count_imgs/$imgs_per_page);
if ($page > $maxpage) $page = $maxpage;
echo
'<!doctype html>',
'<html lang=', $lang, '>',
'<head>',
'<meta charset=', $encoding, '>',
'<meta name=viewport content="width=device-width,initial-scale=1">',
'<title>', $images_title, ' - ', sprintf($page_prefix, $page), ' - ', $site_name, '</title>',
'<link href="../', $tpl_dir, 'css/bootstrap.min.css" rel=stylesheet>',
'<link href=icon.php rel=icon type="image/svg+xml" sizes=any>',
'</head>',
'<body>',
'<ol class="breadcrumb bg-dark mb-0 p-4 rounded-0">',
'<li class=breadcrumb-item><a class="text-white fs-5 text-decoration-none" href="', dirname($url), '">', $site_name, '</a></li>',
'<li class=breadcrumb-item><a class="text-white text-decoration-none" href="', $url, '">', basename(__DIR__), '</a></li>',
'<li class="breadcrumb-item active">', ($maxpage > 1 ? sprintf($page_prefix, $page). '/'. $maxpage : ''), '</li>',
'</ol>',
'<header class="bg-secondary mb-4 p-4 text-white"><h1 class="h5 m-0">', $images_heading, '</h1></header>',
'<main class="container-fluid bg-light" style="min-height:calc(100vh - 222px)">';
foreach ($sort as $image)
{
	$img = explode($delimiter, $image);
	$info = pathinfo($img[1]);
	$formats = strtolower($info['extension']);
	$extensions = ['gif', 'jpg', 'jpeg', 'png', 'svg', 'pdf', 'mp4', 'ogg', 'webm'];
	if (false !== array_search($formats, $extensions))
	{
		$uri = $url. r($img[1]);
		$basename = basename($img[1], '.'. $formats);
		$vtt = is_file($vtt = r($basename). '.vtt') ? '<track src="'.$url .$vtt.'">' : '';
		$img_info = '<small class=text-uppercase>'. date($time_format, $img[0]). ' '. size_unit(filesize($img[1])). ' '. $formats. '</small>';
		if ('mp4' === $formats  || 'ogg' === $formats || 'webm' === $formats)
		{
			$img_array[] =
			'<div class="bg-light card"><video class=card-img-top controls><source src="'. $uri. '">'. $vtt. '</video>'.
			'<div class=card-body>'. $img_info.
			'<div class="input-group input-group-lg">'.
			'<input type=text readonly value="'. h('<video class="img-thumbnail img-fluid" controls><source src="'. $uri. '">'. $vtt. '</video>'). '" class="form-control input-lg bg-white" onclick="this.select()">'.
			'</div>'.
			'</div>'.
			'</div>';
		}
		elseif ('pdf' === $formats)
		{
			$img_array[] =
			'<div class="bg-light card">'.
			'<div class=card-body>'. $img_info.
			'<div class="input-group input-group-lg">'.
			'<input type=text readonly value="'. h('<a href="'. $uri. '" download>'. h($basename). '</a>'). '" class="form-control bg-white" onclick="this.select()">'.
			'</div>'.
			'</div>'.
			'</div>';
		}
		else
		{
			$exif = @exif_read_data($img[1], '','' , true);
			$exif_thumbnail = !isset($exif['THUMBNAIL']['THUMBNAIL']) ? '' : trim($exif['THUMBNAIL']['THUMBNAIL']);
			$exif_comment = !isset($exif['COMMENT']) ? '' : str_replace($line_breaks, '&#10;', h(trim(strip_tags($exif['COMMENT'][0]))));
			$data_caption = !$exif_comment ? '' : ' data-caption="'. $exif_comment. '"';
			$img_array[] =
			'<div class="card">'.
			'<img class=card-img-top src="'. $uri. '" alt="'. h($basename). '">'.
			'<div class=card-body>'.
			'<h2 class="card-title h5">'. h($basename). '</h2>'.
			'<h3 class="card-subtitle mb-2 text-muted h6">'. $img_info. '</h3>'.
			(!$exif_comment ? '' : '<p class="card-text wrap p-2">'. $exif_comment. '</p>').
			(!$exif_thumbnail ? '' :
			'<div class="input-group mb-2">'.
			'<span class=input-group-text>'. $size[0]. '</span>'.
			'<input type=text readonly value="'. h('<a href="'. $uri. '" data-fancybox=gallery'. $data_caption. '><img class="img-thumbnail img-fluid" src="data:'. image_type_to_mime_type(exif_imagetype($img[1])). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. h($basename). '"></a>'). '" class="form-control bg-white" onclick="this.select()">'.
			'</div>') .
			'<div class="input-group input-group-lg">'.
			'<span class="input-group-text">'. $size[1]. '</span>'.
			'<input class="form-control bg-white" type=text readonly value="'. h('<a href="'. $uri. '" data-fancybox=gallery'. $data_caption. '><img class="img-thumbnail img-fluid" src="'. $uri. '" alt="'. h($basename). '"></a>'). '" onclick="this.select()"></div>'.
			'</div>'.
			'</div>';
		}
	}
}
if ($img_array)
{
	echo '<div class="row row-cols-1 row-cols-md-2 g-4">';
	$imgs_in_page = array_slice($img_array, ($page - 1) * $imgs_per_page, $imgs_per_page);
	foreach ($imgs_in_page as $imgs) echo '<div class=col>', $imgs, '</div>';
	echo '</div>';
	if ($count_imgs > $imgs_per_page)
	{
		echo
		'<div class="text-center m-4">',
		'<nav class="btn-group btn-group-lg">',
		'<a class="btn btn-outline-secondary', ($page >= 2 ? '' : ' disabled'), '" href="', $url, '?p=1">', $first, '</a>',
		'<a class="btn btn-outline-secondary', ($page > 1 ? '' : ' disabled'), '" href="', $url, '?p=', ($page - 1), '">', $prev, '</a>',
		'<a class="btn btn-outline-secondary', ($page < $maxpage ? '' : ' disabled'), '" href="', $url, '?p=', ($page + 1), '">', $next, '</a>',
		'<a class="btn btn-outline-secondary', ($page <= $maxpage-1 ? '' : ' disabled'), '" href="', $url, '?p=', $maxpage, '">', $last, '</a>',
		'</nav>',
		'</div>';
	}
}
echo
'</main>',
'<footer class="bg-dark py-4 text-center text-white"><small>&copy; ', date('Y'), ' ', $site_name, '. Powered by Kinaga.</small></footer>',
'</body>',
'</html>';
