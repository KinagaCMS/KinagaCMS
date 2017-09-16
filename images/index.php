<?php
if ( is_file( $config = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'config.php' ) && !is_link( $config ) )
{
	include_once $config;
}
$page = filter_input( INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT );

if ( !$page )
{
	$page = 1;
}
echo
'<!doctype html>' . $n .
'<html lang=' . $lang . '>' . $n .
'<head>' . $n .
'<meta charset=' . $encoding . '>' . $n .
'<meta name=viewport content="width=device-width,initial-scale=1">' . $n .
'<title>' . sprintf( $images_title, $site_name ) . '</title>' . $n .
'<link href="..' . $s . $tpl_dir . 'css' . $s . 'bootstrap.min.css" rel=stylesheet>' . $n .
'<style>' . $n .
'h1 { font-size: 22pt }' . $n .
'h2 { font-size: 20pt }' . $n .
'.form-control[readonly] { background-color: #ffffff }' . $n .
'.thumbnail { margin-top: 20px }' . $n .
'.thumbnail:hover { background-color: #d9edf7 }' . $n .
'.input-group { margin-top: 5px }' . $n .
'.aligner input { display: none }' . $n .
'.aligner label { color: #777; border: 1px solid #ddd; width: 200px; padding: .8em 0; text-align: center; float: left; cursor: pointer }' . $n .
'@media (max-width: 768px) {' . $n .
'.aligner{ display: flex }' . $n .
'.aligner label { font-size:small; width: 100px; padding: .4em 0 }' . $n .
'}' . $n .
'.aligner label:hover { color: #444; background-color: #e6e6e6 }' . $n .
'.aligner label:first-of-type { border-radius: 4px 0 0 4px }' . $n .
'.aligner label:last-of-type { border-radius: 0 4px 4px 0 }' . $n .
'.aligner input[type=radio]:checked+label { color: #31708f; background-color: #d9edf7 }' . $n .
'h2, footer { margin-bottom: 1em }' . $n .
'</style>' . $n .
'</head>' . $n .
'<body>' . $n .
'<div class=container>' . $n .
'<div class=page-header>' . $n .
'<ol class=breadcrumb>' . $n .
'<li><a href=' . dirname( $url ) . '>' . $site_name . '</a></li>' . $n .
'<li><a href=' . $url . '>' . basename( __DIR__, '.php' ) . '</a></li>' . $n .
'<li class=active>' . sprintf( $page_prefix, $page ) . '</li>' . $n .
'</ol>' . $n .
'<h1><span class="glyphicon glyphicon-picture"></span> ' . $images_heading . '</h1>' . $n .
'</div>' . $n;

$img_array = [];
$files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( '.', FilesystemIterator::SKIP_DOTS ) );

foreach( $files as $images )
{
	if ( !$images -> isFile() )
	{
		continue;
	}
	$sort[] = strpos( $images, '.php' ) == false && !is_link( $images ) ? $images -> getMTime() . '-~-' . trim( $images, '.' . $s ) : '';
}

$sort = array_filter( $sort );
rsort( $sort );
$count_imgs = count( $sort );

foreach( $sort as $image )
{
	$img = explode( '-~-', $image );
	$info = pathinfo( $img[1] );
	$formats = strtolower( $info['extension'] );
	$extensions = array( 'gif', 'jpg', 'jpeg', 'png', 'svg', 'pdf', 'mp4', 'ogg', 'webm' );

	if ( array_search( $formats, $extensions ) !== false )
	{
		$uri = $url . r( $img[1] );
		$basename = h( basename( $img[1], '.' . $formats ) );
		$img_info = '<small class=text-uppercase>' . date( $time_format, $img[0] ) . ' ' . size_unit( filesize( $img[1] ) ) . ' ' . $formats . '</small>';

		if ( $formats == 'mp4' || $formats == 'ogg' || $formats == 'webm' )
		{
			$href = '<video class="img-thumbnail img-responsive individual-thumbnail" controls src="' . $uri . '"></video>';
			$img_array[] =
			'<div class="col-md-4 text-center">' . $n .
			'<div class=thumbnail>' . $img_info . $href . $n .
			'<div class="input-group input-group-lg">' . $n .
			'<span class=input-group-addon><span class="glyphicon glyphicon-film"></span></span>' . $n .
			'<input type=text readonly value="' . h( $href ) . '" class="form-control input-lg" onclick="this.select()">' . $n .
			'</div>' . $n .
			'</div>' . $n .
			'</div>' . $n;
		}
		elseif ( $formats == 'pdf' )
		{
			$href = '<a href="' . $uri . '">' . $basename . '</a>';
			$img_array[] =
			'<div class="col-md-4 text-center">' . $n .
			'<div class=thumbnail>' . $img_info . $n .
			'<div class="glyphicon glyphicon-picture" style="font-size:20rem"></div>' . $n .
			'<div class="input-group input-group-lg">' . $n .
			'<span class=input-group-addon><span class="glyphicon glyphicon-text-background"></span></span>' . $n .
			'<input type=text readonly value="' . h( $href ) . '" class=form-control onclick="this.select()">' . $n .
			'</div>' . $n .
			'</div>' . $n .
			'</div>' . $n;
		}
		else
		{
			$exif = @exif_read_data( $img[1], '','' , true );
			$exif_thumbnail = isset( $exif['THUMBNAIL']['THUMBNAIL'] ) ? trim( $exif['THUMBNAIL']['THUMBNAIL'] ) : '';
			$exif_comment = isset( $exif['COMMENT'] ) ? ' title="' . str_replace( $line_breaks, '&#10;', h( trim( strip_tags( $exif['COMMENT'][0] ) ) ) ) . '"' : '';
			$thumbnail = $exif_thumbnail ?
			'<div class="input-group input-group-lg">' . $n .
			'<span class=input-group-addon>' . $small_image . '</span>' . $n .
			'<input type=text readonly value="' . h( '<a href="' . $uri . '" target="_blank" class="discrete thumbnails" onclick="return false"' . $exif_comment . '><img class="img-thumbnail img-responsive individual-thumbnail" src="data:' . image_type_to_mime_type( exif_imagetype( $img[1] ) ) . ';base64,' . base64_encode( $exif_thumbnail ) . '" alt="' . $basename . '"></a>' ) . '" class=form-control onclick="this.select()">' . $n .
			'</div>' : '';
			$href = '<img class="img-thumbnail img-responsive individual-thumbnail" src="' . $uri . '" alt="' . $basename . '">';
			$img_array[] =
			'<div class="col-md-4 text-center">' . $n .
			'<div class=thumbnail>' . $img_info . $href . $thumbnail . $n .
			'<div class="input-group input-group-lg">' . $n .
			'<span class=input-group-addon>' . $large_image . '</span>' . $n .
			'<input type=text readonly value="' . h( '<a href="' . $uri . '" target="_blank" class="discrete thumbnails" onclick="return false"' . $exif_comment . '>' . $href. '</a>' ) . '" class=form-control onclick="this.select()">' . $n .
			'</div>' . $n .
			'</div>' . $n .
			'</div>' . $n;
		}
	}
}
if ( $img_array )
{
	echo
	'<h2><span class="glyphicon glyphicon-object-align-right"></span> ' . $images_aligner . '</h2>' . $n .
	'<noscript><div class="alert alert-danger"><span class="glyphicon glyphicon-alert"></span> ' . $noscript . '</div></noscript>' . $n .
	'<div class=aligner>' . $n .
	'<input type=radio value=left name=r id=left><label for=left>' . $align_left . '</label>' . $n .
	'<input type=radio value=center name=r id=center><label for=center>' . $align_center . '</label>' . $n .
	'<input type=radio value=right name=r id=right><label for=right>' . $align_right . '</label>' . $n .
	'<div class=clearfix></div>' . $n .
	'</div>' . $n .
	'<hr>' . $n .
	'<div class=row>' . $n;
	$imgs_in_page = array_slice( $img_array, ( $page - 1 ) * $number_of_imgs, $number_of_imgs );

	for( $i = 0, $c = count( $imgs_in_page ); $i < $c; ++$i )
	{
		echo $imgs_in_page[$i];
	}
	echo '</div>' . $n;

	if ( $count_imgs > $number_of_imgs )
	{
		echo
		'<hr>' . $n .
		'<nav>' . $n .
		'<ul class=pager>' . $n;

		if ( $page > 1 )
		{
			echo '<li class=previous><a href="' . $url . '?p=' . ( $page - 1 ) . '"><span class="glyphicon glyphicon-menu-left"></span> ' . $imgs_prev . '</a></li>' . $n;
		}
		if ( $page < ceil( $count_imgs / $number_of_imgs ) )
		{
			echo '<li class=next><a href="' . $url . '?p=' . ( $page + 1 ) . '">' . $imgs_next . ' <span class="glyphicon glyphicon-menu-right"></span></a></li>' . $n;
		}
		echo
		'</ul>' . $n .
		'</nav>' . $n;
	}
}
echo
'<hr>' . $n .
'<footer class=text-center><small>Copyright <span class="glyphicon glyphicon-copyright-mark"></span> ' . date( 'Y' ) . ' ' . $site_name . '. Powered by Kinaga.</small></footer>' . $n .
'</div>' . $n .
'<script src="..' . $s . $tpl_dir . 'js' . $s . 'jquery.min.js"></script>' . $n .
'<script>function c(c){ $( ".form-control" ).val( function( index, value ){ return value.replace( /img-thumbnail(.*?)img-responsive/g,"img-thumbnail "+c+" img-responsive" ) } );$( ".input-group-lg" ).fadeIn( 100 ).fadeOut( 100 ).fadeIn( 100 ).fadeOut( 100 ).fadeIn( 100 )}$( "#left" ).click( function(){ c( "pull-left" ) } );$( "#center" ).click( function(){ c( "center-block" ) } );$( "#right" ).click( function(){ c( "pull-right" ) } )</script>' . $n .
'</body>' . $n .
'</html>';
unset( $href, $img_array );
