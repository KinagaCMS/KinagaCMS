<?php
include '../includes/functions.php';
include '../includes/config.php';
$img_array = [];
$page = !filter_has_var(INPUT_GET, 'p') ? 1 : (int)filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.', FilesystemIterator::SKIP_DOTS));
if ($page < 1) $page = 1;
foreach ($files as $images)
{
	if (!$images->isFile()) continue;
	$sort[] = strpos($images, '.php') === false && strpos($images, '.vtt') === false && strpos($images, '.md') === false ? $images->getMTime(). $delimiter. trim($images, './') : '';
}
$sort = array_filter($sort);
rsort($sort);
$count_imgs = count($sort);
$maxpage = ceil($count_imgs/$imgs_per_page);
if ($page > $maxpage) $page = $maxpage;
echo
'<!doctype html>
<html lang=', $lang, '>
<head>
<meta charset=', $encoding, '>
<meta name=viewport content="width=device-width,initial-scale=1">
<title>', $images_title, ' - ', sprintf($page_prefix, $page), ' - ', $site_name, '</title>
<link href="../', $tpl_dir, 'css/bootstrap.min.css" rel=stylesheet>
<style>
body{color:#555}
main{min-height:calc(100vh - 264px)}
</style>
<link href=icon.php rel=icon type="image/svg+xml" sizes=any>
</head>
<body>
<ol class="breadcrumb bg-dark mb-0 py-4 rounded-0">
<li class=breadcrumb-item><a class=text-white href="', dirname($url), '">', $site_name, '</a></li>
<li class=breadcrumb-item><a class=text-white href="', $url, '">', basename(__DIR__, '.php'), '</a></li>
<li class="breadcrumb-item active">', ($maxpage > 1 ? sprintf($page_prefix, $page). '/'. $maxpage : ''), '</li>
</ol>
<header class="bg-secondary p-4 text-white"><h1 class="h5 m-0">', $images_heading, '</h1></header>
<main>', $n;
foreach ($sort as $image)
{
	$img = explode($delimiter, $image);
	$info = pathinfo($img[1]);
	$formats = strtolower($info['extension']);
	$extensions = ['gif', 'jpg', 'jpeg', 'png', 'svg', 'pdf', 'mp4', 'ogg', 'webm'];

	if (array_search($formats, $extensions) !== false)
	{
		$uri = $url. r($img[1]);
		$basename = basename($img[1], '.'. $formats);
		$vtt = is_file($vtt = r($basename). '.vtt') ? '<track src="'.$url .$vtt.'">' : '';
		$img_info = '<small class=text-uppercase>'. date($time_format, $img[0]). ' '. size_unit(filesize($img[1])). ' '. $formats. '</small>';

		if ($formats === 'mp4' || $formats === 'ogg' || $formats === 'webm')
		{
			$img_array[] =
			'<div class="bg-light card"><video class=card-img-top controls><source src="'. $uri. '">'. $vtt. '</video>'. $n.
			'<div class=card-body>'. $img_info. $n.
			'<div class="input-group input-group-lg mt-2">'. $n.
			'<input type=text readonly value="'. h('<video class="img-thumbnail img-fluid" controls><source src="'. $uri. '">'. $vtt. '</video>'). '" class="form-control input-lg bg-white" onclick="this.select()">'. $n.
			'</div>'. $n.
			'</div>'. $n.
			'</div>'. $n;
		}
		elseif ($formats === 'pdf')
		{
			$img_array[] =
			'<div class="bg-light card">'. $n.
			'<div class=card-body>'. $img_info. $n.
			'<div class="input-group input-group-lg mt-2">'. $n.
			'<input type=text readonly value="'. h('<a href="'. $uri. '" download>'. h($basename). '</a>'). '" class="form-control bg-white" onclick="this.select()">'. $n.
			'</div>'. $n.
			'</div>'. $n.
			'</div>'. $n;
		}
		else
		{
			$exif = @exif_read_data($img[1], '','' , true);
			$exif_thumbnail = isset($exif['THUMBNAIL']['THUMBNAIL']) ? trim($exif['THUMBNAIL']['THUMBNAIL']) : '';
			$exif_comment = isset($exif['COMMENT']) ? ' data-caption="'. str_replace($line_breaks, '&#10;', h(trim(strip_tags($exif['COMMENT'][0])))). '"' : '';
			$img_array[] =
			'<div class="bg-light card"><img class=card-img-top src="'. $uri. '" alt="'. h($basename). '">'. $n.
			'<div class=card-body>'. $img_info. $n.
			($exif_thumbnail ?
			'<div class="input-group mt-2">'. $n.
			'<div class="input-group-prepend"><span class=input-group-text>'. $size[0]. '</span></div>'. $n.
			'<input type=text readonly value="'. h('<a href="'. $uri. '" data-fancybox=gallery'. $exif_comment. '><img class="img-thumbnail img-fluid" src="data:'. image_type_to_mime_type(exif_imagetype($img[1])). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. h($basename). '"></a>'). '" class="form-control bg-white" onclick="this.select()">'. $n.
			'</div>' : '') . $n.
			'<div class="input-group input-group-lg mt-2">'. $n.
			'<div class="input-group-prepend"><span class="input-group-text">'. $size[1]. '</span></div>'. $n.
			'<input class="form-control bg-white" type=text readonly value="'. h('<a href="'. $uri. '" data-fancybox=gallery'. $exif_comment. '><img class="img-thumbnail img-fluid" src="'. $uri. '" alt="'. h($basename). '"></a>'). '" onclick="this.select()"></div>'. $n.
			'</div>'. $n.
			'</div>'. $n;
		}
	}
}
if ($img_array)
{
	echo
	'<div class="bg-light p-4 mb-3 text-center">', $n,
	'<h2 class="h6 mb-3">', $images_aligner, '</h2>', $n,
	'<noscript><div class="alert alert-danger">', $noscript, '</div></noscript>', $n,
	'<div class="btn-group btn-group-lg btn-group-toggle" data-toggle="buttons">', $n,
	'<label class="btn btn-outline-secondary" id=left><input type=radio value=left name=l>', $align[0], '</label>', $n,
	'<label class="btn btn-outline-secondary" id=right><input type=radio value=right name=r >', $align[1], '</label>', $n,
	'</div>', $n,
	'</div>', $n,
	'<div class=container-fluid>', $n,
	'<div class=card-deck>', $n;
	$imgs_in_page = array_slice($img_array, ($page - 1) * $imgs_per_page, $imgs_per_page);

	foreach ($imgs_in_page as $imgs) echo $imgs;

	echo
	'</div>', $n,
	'</div>';

	if ($count_imgs > $imgs_per_page)
	{
		echo
		'<div class="text-center m-4">', $n,
		'<nav class="btn-group btn-group-lg">', $n,
		'<a class="btn btn-outline-secondary', ($page >= 2 ? '' : ' disabled'), '" href="', $url, '?p=1">', $first, '</a>', $n,
		'<a class="btn btn-outline-secondary', ($page > 1 ? '' : ' disabled'), '" href="', $url, '?p=', ($page - 1), '">', $prev, '</a>', $n,
		'<a class="btn btn-outline-secondary', ($page < $maxpage ? '' : ' disabled'), '" href="', $url, '?p=', ($page + 1), '">', $next, '</a>', $n,
		'<a class="btn btn-outline-secondary', ($page <= $maxpage-1 ? '' : ' disabled'), '" href="', $url, '?p=', $maxpage, '">', $last, '</a>', $n,
		'</nav>', $n,
		'</div>', $n;
	}
}
echo '
</main>
<footer class="bg-dark mt-5 py-4 text-center text-white"><small>&copy; ', date('Y'), ' ', $site_name, '. Powered by Kinaga.</small></footer>
<script src="../', $tpl_dir, 'js/jquery.min.js"></script>
<script>function c(c){$(".form-control").val(function(index,value){return value.replace(/img-thumbnail(.*?)img-fluid/g,"img-thumbnail "+c+" img-fluid")});$(".input-group").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100)}$("#left").click(function()
{$("#right").removeClass("active");$(this).addClass("active");c("float-left mr-2")});$("#right").click(function(){$("#left").removeClass("active");$(this).addClass("active");c("float-right ml-2")})</script>
</body>
</html>';
