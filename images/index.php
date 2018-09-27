<?php
include '../includes/config.php';
$img_array = [];
$page = !filter_has_var(INPUT_GET, 'p') ? 1 : (int)filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.', FilesystemIterator::SKIP_DOTS));

foreach($files as $images)
{
	if (!$images -> isFile())
		continue;
	$sort[] = strpos($images, '.php') === false && strpos($images, '.vtt') === false && strpos($images, '.md') === false ? $images -> getMTime(). '-~-'. trim($images, './') : '';
}

$sort = array_filter($sort);
rsort($sort);
$count_imgs = count($sort);
$maxpage = ceil($count_imgs/$number_of_imgs);

echo
'<!doctype html>
<html lang='. $lang. '>
<head>
<meta charset='. $encoding. '>
<meta name=viewport content="width=device-width,initial-scale=1">
<title>'. sprintf($images_title, $site_name). '</title>
<link href="../'. $tpl_dir. 'css/bootstrap.min.css" rel=stylesheet>
<style>
body{font-family:Roboto, "Droid Sans", "Yu Gothic", YuGothic, "Hiragino Sans", sans-serif;color:#555;}
.card-columns{column-count:2}@media(max-width:767px){.card-columns{column-count:1}}
</style>
<link href=icon.php rel=icon type="image/svg+xml" sizes=any>'. $n.
'</head>
<body>
<div class=page-header>
<ol class=breadcrumb>
<li class=breadcrumb-item><a href='. dirname($url). '>'. $site_name. '</a></li>
<li class=breadcrumb-item><a href='. $url. '>'. basename(__DIR__, '.php'). '</a></li>
<li class="breadcrumb-item active">'. sprintf($page_prefix, $page). ' / '. $maxpage. '</li>
</ol>
</div>
<div class=container-fluid>
<h1 class="h4 mb-4">'. $images_heading. '</h1>'. $n;

if ($page > $maxpage)
	echo $not_found;
else
{
	foreach($sort as $image)
	{
		$img = explode('-~-', $image);
		$info = pathinfo($img[1]);
		$formats = strtolower($info['extension']);
		$extensions = array('gif', 'jpg', 'jpeg', 'png', 'svg', 'pdf', 'mp4', 'ogg', 'webm');

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
				$exif_comment = isset($exif['COMMENT']) ? ' title="'. str_replace($line_breaks, '&#10;', h(trim(strip_tags($exif['COMMENT'][0])))). '"' : '';
				$img_array[] =
				'<div class="bg-light card"><img class=card-img-top src="'. $uri. '" alt="'. h($basename). '">'. $n.
				'<div class=card-body>'. $img_info. $n.
				($exif_thumbnail ?
				'<div class="input-group mt-2">'. $n.
				'<div class="input-group-prepend"><span class=input-group-text>'. $small_image. '</span></div>'. $n.
				'<input type=text readonly value="'. h('<a class=expand href="'. $uri. '" target="_blank" onclick="return false"'. $exif_comment. '><img class="img-thumbnail img-fluid" src="data:'. image_type_to_mime_type(exif_imagetype($img[1])). ';base64,'. base64_encode($exif_thumbnail). '" alt="'. h($basename). '"></a>'). '" class="form-control bg-white" onclick="this.select()">'. $n.
				'</div>' : '') .
				'<div class="input-group input-group-lg mt-2">'. $n.
				'<div class="input-group-prepend"><span class="input-group-text">'. $large_image. '</span></div>'. $n.
				'<input class="form-control bg-white" type=text readonly value="'. h('<a class=expand href="'. $uri. '" target="_blank" onclick="return false"'. $exif_comment. '><img class="img-thumbnail img-fluid" src="'. $uri. '" alt="'. h($basename). '"></a>'). '" onclick="this.select()"></div>'. $n.
				'</div>'. $n.
				'</div>'. $n;
			}
		}
	}
	if ($img_array)
	{
		echo
		'<h2 class="h5 mb-3">'. $images_aligner. '</h2>'. $n.
		'<noscript><div class="alert alert-danger">'. $noscript. '</div></noscript>'. $n.
		'<div class="btn-group btn-group-lg btn-group-toggle mb-3" data-toggle="buttons">'. $n.
		'<label class="btn btn-outline-secondary" id=left><input type=radio value=left name=l>'. $align_left. '</label>'. $n.
		'<label class="btn btn-outline-secondary" id=right><input type=radio value=right name=r >'. $align_right. '</label>'. $n.
		'</div>'. $n;
		if ($count_imgs > $number_of_imgs)
		{
			echo
			'<div class="text-center m-4">'. $n.
			'<nav class="btn-group btn-group-lg">'. $n.
			'<a class="btn btn-outline-secondary'.($page > 2 ? '' : ' disabled').'" href="'. $url. '?p=1">'. $imgs_first. '</a>'. $n.
			'<a class="btn btn-outline-secondary'.($page > 1 ? '' : ' disabled').'" href="'. $url. '?p='. ($page - 1). '">'. $imgs_prev. '</a>'. $n.
			'<a class="btn btn-outline-secondary'.($page < $maxpage ? '' : ' disabled').'" href="'. $url. '?p='. ($page + 1). '">'. $imgs_next. '</a>'. $n.
			'<a class="btn btn-outline-secondary'.($page < $maxpage-1 ? '' : ' disabled').'" href="'. $url. '?p='. $maxpage. '">'. $imgs_last. '</a>'. $n.
			'</nav>'. $n.
			'</div>'. $n;
		}
		echo
		'<div class=card-columns>'. $n;
		$imgs_in_page = array_slice($img_array, ($page - 1) * $number_of_imgs, $number_of_imgs);

		for($i = 0, $c = count($imgs_in_page); $i < $c; ++$i)
			echo $imgs_in_page[$i];

		echo '</div>'. $n;

		if ($count_imgs > $number_of_imgs)
		{
			echo
			'<div class="text-center m-3">'. $n.
			'<nav class="btn-group btn-group-lg">'. $n.
			'<a class="btn btn-outline-secondary'.($page > 2 ? '' : ' disabled').'" href="'. $url. '?p=1">'. $imgs_first. '</a>'. $n.
			'<a class="btn btn-outline-secondary'.($page > 1 ? '' : ' disabled').'" href="'. $url. '?p='. ($page - 1). '">'. $imgs_prev. '</a>'. $n.
			'<a class="btn btn-outline-secondary'.($page < $maxpage ? '' : ' disabled').'" href="'. $url. '?p='. ($page + 1). '">'. $imgs_next. '</a>'. $n.
			'<a class="btn btn-outline-secondary'.($page < $maxpage-1 ? '' : ' disabled').'" href="'. $url. '?p='. $maxpage. '">'. $imgs_last. '</a>'. $n.
			'</nav>'. $n.
			'</div>'. $n;
		}
	}
}
echo
'
<footer class="mb-3 text-center"><small>&copy; '. date('Y'). ' '. $site_name. '. Powered by kinaga.</small></footer>
</div>
<script src="../'. $tpl_dir. 'js/jquery.min.js"></script>
<script>
function c(c)
{
$(".form-control").val(function(index,value){
return value.replace(/img-thumbnail(.*?)img-fluid/g,"img-thumbnail "+c+" img-fluid")});
$(".input-group").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100)
}
$("#left").click(function()
{
$("#right").removeClass("active");
$(this).addClass("active");
c("float-left mr-2")
});
$("#right").click(function()
{
$("#left").removeClass("active");
$(this).addClass("active");
c("float-right ml-2")
})</script>
</body>
</html>';
