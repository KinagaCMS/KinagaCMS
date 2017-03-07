<?
/*
  * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
  * @license    GPL 3.0 or later; see LICENSE file for details.
  */

$header = '';

$nav = '';

$article = '';

$aside = '';

$footer = '';

$current = '';

$get_title = basename( filter_input( INPUT_GET, 'title', FILTER_SANITIZE_STRIPPED ) );

$get_categ = basename( filter_input( INPUT_GET, 'categ', FILTER_SANITIZE_STRIPPED ) );

$get_page = basename( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED ) );

$get_dl = basename( filter_input( INPUT_GET, 'dl', FILTER_SANITIZE_STRIPPED ) );

$pages = basename( filter_input( INPUT_GET, 'pages', FILTER_SANITIZE_NUMBER_INT ) );

$comment_pages = basename( filter_input( INPUT_GET, 'comments', FILTER_SANITIZE_NUMBER_INT ) );

$breadcrumb = '<li><a href="' . $url . '">' . $home . '</a></li>';


if ( ! function_exists( 'get_dirs' ) ) {

	function get_dirs( $dir, $nosort = true ) {

		$dirs = glob( $dir . DIRECTORY_SEPARATOR . '*' , ! $nosort ? GLOB_ONLYDIR : GLOB_ONLYDIR + GLOB_NOSORT );

		if ( $dirs ) {

			foreach( $dirs as $dir_names ) {

				if ( is_link( $dir_names ) ) unset( $dir_names );

				if ( isset( $dir_names ) ) $all_dirs[] = basename( $dir_names );

			}

		if ( isset( $all_dirs ) ) return $all_dirs;

		}

	}

}


if ( ! function_exists( 'summary' ) ) {

	function summary( $file ) {

		global $summary_length, $encoding, $n, $ellipsis;

		error_reporting( ~E_NOTICE );

		ob_start();

		include_once $file;

		$text = ob_get_clean();

		$text = strip_tags( preg_replace( '/<script.*?\/script>/s', '', $text ) );

		$text = str_replace( [ $n . $n . $n, $n . $n ], $n, $text );

		$text = mb_strimwidth( $text, 0, $summary_length, $ellipsis, $encoding );

		return trim( $text );

	}

}


if ( ! function_exists( 'description' ) ) {

	function description( $str ) {

		global $description_length, $encoding, $line_breaks, $ellipsis;

		$text = strip_tags( preg_replace( '/<script.*?\/script>/s', '', $str ) );

		$text = mb_strimwidth( $text, 0, $description_length, $ellipsis, $encoding );

		$text = str_replace( $line_breaks, '', $text );

		return trim( $text );

	}

}


if ( ! function_exists( 'categ' ) ) {

	function categ( $str ) {

		return strip_tags( basename( dirname( dirname( $str ) ) ) );
	}

}


if ( ! function_exists( 'title' ) ) {

	function title( $str ) {

		return strip_tags( basename( dirname( $str ) ) );

	}

}


if ( ! function_exists( 'htmlspecialchars_title' ) ) {

	function htmlspecialchars_title( $str ) {

		return h( strip_tags( basename( $str ) ) );

	}

}


if ( ! function_exists( 'social' ) ) {

	function social( $t, $u ) {

		global $n;

		return
		'<span id=social>' . $n .
		'<a class="label label-info" href="https://twitter.com/intent/tweet?text=' . $t . '&amp;url=' . $u . '" target="_blank" rel="noopener noreferrer">Twitter</a>' . $n .
		'<a class="label label-primary" href="https://www.facebook.com/sharer/sharer.php?u=' . $u . '&amp;title=' . $t . '" target="_blank" rel="noopener noreferrer">Facebook</a>' . $n .
		'<a class="label label-danger" href="https://plus.google.com/share?url=' . $u . '" target="_blank" rel="noopener noreferrer">Google+</a>' . $n .
		'</span>' . $n;

	}

}


if ( ! function_exists( 'permalink' ) ) {

	function permalink( $t, $u ) {

		global $for_html, $for_wiki, $for_forum, $n;

		return
		'<ul class="nav nav-tabs">' . $n .
		'<li class=active><a href=#html data-toggle=tab aria-controls=html>' . $for_html . '</a></li>' . $n .
		'<li><a href=#wiki data-toggle=tab aria-controls=wiki>' . $for_wiki . '</a></li>' . $n .
		'<li><a href=#forum data-toggle=tab aria-controls=forum>' . $for_forum . '</a></li>' . $n .
		'</ul>' . $n .
		'<div class=tab-content>' . $n .
		'<div class="tab-pane active" id=html>' . $n .
		'<textarea readonly onclick="this.select()" class="form-control input-sm" rows=5 tabindex=10 accesskey = h>' . h( '<a href="' . $u . '" target="_blank">' . $t . '</a>' ) . '</textarea>' . $n .
		'</div>' . $n .
		'<div class=tab-pane id=wiki>' . $n .
		'<textarea readonly onclick="this.select()" class="form-control input-sm" rows=5 tabindex=11 accesskey=f>' . h( '[' . $u . ' ' . $t . ']' ) . '</textarea>' . $n .
		'</div>' . $n .
		'<div class=tab-pane id=forum>' . $n .
		'<textarea readonly onclick="this.select()" class="form-control input-sm" rows=5 tabindex=11 accesskey=f>' . h( '[URL=' . $u . ']' . $t . '[/URL]' ) . '</textarea>' . $n .
		'</div>' . $n .
		'</div>' . $n;

	}

}


if ( ! function_exists( 'a' ) ) {

	function a( $uri, $name = '', $class = '' ) {

		$parsed_url = parse_url( $uri );

		$scheme = isset( $parsed_url['scheme'] ) ? $parsed_url['scheme'] . '://' : '';

		$host = isset( $parsed_url['host'] ) ? ( function_exists( 'idn_to_ascii' ) ? idn_to_ascii( $parsed_url['host'] ) : $parsed_url['host'] ) : '';

		$port = isset( $parsed_url['port'] ) ? ':' . $parsed_url['port'] : '';

		$user = isset( $parsed_url['user'] ) ? r( $parsed_url['user'] ) : '';

		$pass = isset( $parsed_url['pass'] ) ? ':' . r( $parsed_url['pass'] ) : '';

		$pass = $user || $pass ? "$pass@" : '';

		$path = isset( $parsed_url['path'] ) ? r( $parsed_url['path'] ) : '';

		$query = isset( $parsed_url['query'] ) ? '?' . r( $parsed_url['query'] ) : '';

		$fragment = isset( $parsed_url['fragment'] ) ? '#' . r( $parsed_url['fragment'] ) : '';

		$link = $scheme . $user . $pass . $host . $port . $path . $query . $fragment;

		return

		'<a href="' . $link . '" target="_blank" rel="noopener noreferrer"' . ( $class !== '' ? ' class="' . $class . '"' : '' ) . '>' . ( $name == '' ? h( $uri ) : h( $name ) ) .
		' <sup><small class="glyphicon glyphicon-new-window"></small></sup></a>';

	}

}


if ( ! function_exists( 'img' ) ) {

	function img( $src, $align = '', $comment = true, $thumbnail = true ) {

		global $url, $source, $n;

		$info = pathinfo( $src );

		if ( strpos( $src, '://' ) !== false ) {

			$addr = parse_url( $src );

			$uri = '';

			$img_source =

			'<p>' . sprintf( $source, $addr['host'] ) . '</p>';

		} else {

			$uri = $url;

			$img_source = '';

		}

		if ( isset( $info['extension'] ) && $extension = strtolower( $info['extension'] ) ) {

			if ( array_search( $extension, array( 'gif', 'jpg', 'jpeg', 'png', 'svg' ) ) !== false ) {

				$exif = @exif_read_data( $src, '', '', true );

				$exif_thumbnail = isset( $exif['THUMBNAIL']['THUMBNAIL'] ) ? $exif['THUMBNAIL']['THUMBNAIL'] : '';

				$exif_comment = isset( $exif['COMMENT'] ) && $comment ? '<p class="text-center wrap">' . h( trim( strip_tags( $exif['COMMENT'][0] ) ) ) . '</p>' : '';

				return $exif_thumbnail !== '' && $thumbnail ?

				'<img class="' . $align . ' img-thumbnail img-responsive individual-thumbnail" src="data:' . image_type_to_mime_type( exif_imagetype( $src ) ) . ';base64,' . base64_encode( $exif_thumbnail ) . '" alt="' . h( basename( $src ) ) . '">' :
				'<figure class="' . $align . ' img-thumbnail individual-thumbnail"><img class="img-responsive margin-auto" src="' . $uri . r( $src ) . '" alt="' . h( basename( $src ) ) . '">' . $exif_comment . $img_source . '</figure>';

			} elseif ( array_search( $extension, array( 'mp4', 'ogg', 'webm' ) ) !== false ) {

				return

				'<figure class="' . $align . ' img-thumbnail"><video class=img-responsive controls src="' . $uri . r( $src ) . '" preload=none></video>' . $img_source . '</figure>' . $n;

			}

		}

	}

}

$contact = is_file( $contact_file = 'includes' . $s . 'form.php' ) && ! is_link( $contact_file ) ? true : false;

$dl = is_dir( $downloads_dir = 'downloads' ) && ! is_link( $downloads_dir ) ? true : false;

$current = ! $get_categ && ! $get_page ? ' class=active' : '';

$session_name = 'kinaga_session';

$contents = get_dirs( 'contents', false );

if ( ! empty( $contents ) ) {

	foreach( $contents as $categ ) {

		$nav .=

		'<li' . ( filter_has_var( INPUT_GET, 'categ' ) && $get_categ == $categ ? ' class=active' : '' ) . '>' . $n .
		'<a href="' . $url . r( $categ ) . $s . '">' . h( $categ ) . '</a>' . $n .
		'</li>' . $n;

	}

}

if ( filter_has_var( INPUT_GET, 'page' ) && ! is_numeric( $get_page ) ) {


	if ( is_file( $pages_file = 'contents' . $s . $get_page . '.html' ) && ! is_link( $pages_file ) ) {

		$basetitle = h( $get_page );

		$header .=

		'<title>' . $basetitle . ' - ' . $site_name . '</title>' . $n;

		$breadcrumb .=

		'<li class=active>' . $basetitle . '</li>';

		$article .=

		'<h1>' . $basetitle . '</h1>' . $n .
		'<div class="text-right social">' . $n .
		'<span class="glyphicon glyphicon-edit"></span> <span>' . sprintf( $last_modified, date( $time_format, filemtime( $pages_file ) ) ) . '</span>' . $n;

		if ( $use_social ) $article .= social( rawurlencode( $basetitle . ' - ' . $site_name ), rawurlencode( $url . $basetitle ) );

		ob_start();

		include_once $pages_file;

		$pages_content = trim( ob_get_clean() );

		$header .= '<meta name=description content="' . description( $pages_content ) . '">' . $n;

		$article .=

		'</div>' . $n .
		'<div class=article>' . $pages_content . '</div>' . $n;

		if ( $use_permalink ) {

		$article .= $top .

		'<section id=permalink>' . $n .
		'<h2 class=section>' . $n .
		'<span class="glyphicon glyphicon-link"></span> ' . $permalink .
		'</h2>' . permalink( $basetitle . ' - ' . $site_name, $url . rawurlencode( $basetitle ) ) .
		'</section>' . $n;

		}


	} elseif ( $use_contact && $contact && $get_page === $contact_us ) {

		$header .=

		'<title>' . $contact_us . ' - ' . $site_name . '</title>' . $n;

		$breadcrumb .=

		'<li class=active>' . $contact_us . '</li>';

		$article .=

		'<h1 id=form class=page-title>' . $contact_us . ( $contact_subtitle ? ' <small class=wrap>' . $contact_subtitle . '</small>' : '' ) . '</h1>' . $n;

		ob_start();

		include_once $contact_file;

		$article .= trim( ob_get_clean() );


	} elseif ( $dl && $get_page === $download_contents ) {

		if ( is_file( $dl_file = $downloads_dir . $s . $get_dl ) && ! is_link( $dl_file ) && pathinfo( $dl_file, PATHINFO_EXTENSION ) ) {

			header( 'Content-Length: ' . filesize( $dl_file ) . '' );

			header( 'Content-Type: ' . mime_content_type( $dl_file ) . '' );

			if ( $lang !== 'en' || strpos( $user_agent, 'MSIE' ) !== false || strpos( $user_agent, 'rv:11.0' ) !== false ) {

				header( 'X-Download-Options: noopen' );

				header( 'Content-Disposition: attachment; filename="' . mb_convert_encoding( $get_dl, $encoding_win, $encoding ) . '"' );

			} else {

				header( 'Content-Disposition: attachment; filename="' . $get_dl . '"' );

			}

			readfile( $dl_file );

			exit;

		}

		$breadcrumb .=

		'<li class=active>' . $download_contents . '</li>';

		if ( filter_has_var( INPUT_GET, 'pages' ) && is_numeric( $pages ) ) {

			$header .=

			'<title>' . $download_contents . ' - ' . sprintf( $page_prefix, $pages ) . ' - ' . $site_name . '</title>' . $n;

		} else {

			$pages = 1;

			$header .=

			'<title>' . $download_contents . ' - ' . $site_name . '</title>' . $n;

		}

		$article .=

		'<h1 class=page-title>' . $download_contents . ( $download_subtitle ? ' <small class=wrap>' . $download_subtitle . '</small>' : '' ) . '</h1>' . $n;

		$dl_files = glob( $downloads_dir . $s . '*.*', GLOB_NOSORT );

		if ( $dl_files ) {

			for( $i = 0, $c = count( $dl_files ); $i < $c; ++$i ) {

				$dls_sort[] = ! is_link( $dl_files[$i] ) && ( $di_filesize = filesize( $dl_files[$i] ) ) > 0 ? filemtime( $dl_files[$i] ) . '–' . $dl_files[$i] . '–' . size_unit( $di_filesize ) : '';

			}

			$dls_sort = array_filter( $dls_sort );

			rsort( $dls_sort );

			$dls_number = count( $dls_sort );

			$dls_in_page = array_slice( $dls_sort, ( $pages - 1 ) * $number_of_downloads, $number_of_downloads );

			$article .=

			'<ul class=list-group>';

			for( $i = 0, $c = count( $dls_in_page ); $i < $c; ++$i ) {

				$dl_uri = explode( '–', $dls_in_page[$i] );

				$article .=

				'<li class=list-group-item>' . $n .
				'<span class=badge>' . $dl_uri[2] . '</span>' . $n .
				'<a href="' . $url . r( $download_contents ) . '&amp;dl=' . rawurlencode( strip_tags( basename( $dl_uri[1] ) ) ) . '" target="_blank" class=dl>' . $n .
				'<span class="glyphicon glyphicon-download-alt"></span> ' . date( $time_format, $dl_uri[0] ) . ' ' . htmlspecialchars_title( $dl_uri[1] ) . '</a>' . $n .
				'</li>' . $n;

			}

			$article .=

			'</ul>';

			if ( $dls_number > $number_of_downloads ) {

				$page_ceil = ceil( $dls_number / $number_of_downloads );

				numlinks( $pages, $page_ceil, $number_of_pager );

			}

		}

	} else {

	$header .=

	'<title>' . $error . ' - ' . $site_name . '</title>' . $n;

	$article .=

	'<h1 class=page-title>' . $error . '</h1>' . $n .
	'<div class=article>' . $not_found . '</div>' . $n;

	}


} elseif ( filter_has_var( INPUT_GET, 'categ' ) && ! filter_has_var( INPUT_GET, 'title' ) ) {

	if ( is_dir( $current_categ = 'contents' . $s . $get_categ ) && ! is_link( $current_categ ) ) {

		$categ_title = h( $get_categ );

		$breadcrumb .=

		'<li class=active>' . $categ_title . '</li>';

		$categ_contents = get_dirs( $current_categ );

		$categ_contents_number = count( $categ_contents );

		if ( $categ_contents_number === 0 && is_file( $categ_file = $current_categ . $s . 'index.html' ) && ! is_link( $categ_file ) ) {

			ob_start();

			include_once $categ_file;

			$categ_content = trim( ob_get_clean() );

			$header .=

			'<title>' . $categ_title . ' - ' . $site_name . '</title>' . $n .
			'<meta name=description content="' . description( $categ_content ) . '">' . $n;

			$article .=

			'<h1 class=page-title>' . $categ_title . ' <small class=wrap>' . $categ_content . '</small></h1>' . $n;

		} elseif ( $categ_contents_number > 0 ) {

			if ( filter_has_var( INPUT_GET, 'pages' ) && is_numeric( $pages ) ) {

				$header .=

				'<title>' . $categ_title . ' - ' . sprintf( $page_prefix, $pages ) . ' - ' . $site_name . '</title>' . $n;

			} else {

				$pages = 1;

				$header .=

				'<title>' . $categ_title . ' - ' . $site_name . '</title>' . $n;

			}

			$article .=

			'<h1 class=page-title>' . $categ_title;

			if ( is_file( $categ_file = $current_categ . $s . 'index.html' ) && ! is_link( $categ_file ) ) {

				ob_start();

				include_once $categ_file;

				$categ_content = trim( ob_get_clean() );

				$article .=

				' <small class=wrap>' . $categ_content . '</small>';

				$header .=

				'<meta name=description content="' . description( $categ_content ) . '">' . $n;

			}

			$article .=

			'</h1>' . $n;

			for( $i = 0; $i < $categ_contents_number; ++$i ) {

				$articles_sort[] = is_file( $article_files = $current_categ . $s . $categ_contents[$i] . $s . 'index.html' ) && ! is_link( $article_files ) ?

				filemtime( $article_files ) . '–' . $article_files : '';

			}

			$articles_sort = array_filter( $articles_sort );

			rsort( $articles_sort );

			$sections_in_categ_page = array_slice( $articles_sort, ( $pages - 1 ) * $number_of_categ_sections, $number_of_categ_sections );

			for( $i = 0, $c = count( $sections_in_categ_page ); $i < $c; ++$i ) {

				$articles = explode( '–', $sections_in_categ_page[$i] );

				$section =

				'<p class=wrap>' . summary( $articles[1] ) . '</p>' . $n;

				$articles_link = explode( $s, $articles[1] );

				$categ_link = r( $articles_link[1] );

				$title_link = r( $articles_link[2] );

				$article_dir = dirname( $articles[1] );

				$article_link_title = htmlspecialchars_title( $articles_link[2] );

				$count_images = '';

				$counter = is_file( $counter_txt = $article_dir . $s . 'counter.txt' ) ?

				'<span class=separator></span><span class="glyphicon glyphicon-eye-open"></span> ' . sprintf( $display_counts, ( int )trim( strip_tags( file_get_contents( $counter_txt ) ) ) ) : '';

				$comments = $use_comment && is_dir( $comments_dir = $article_dir . $s . 'comments' ) && ! is_link( $comments_dir ) ?

				'<span class=separator></span><a href="' . $url . $categ_link . $s . $title_link . '#form">' . $n .
				'<span class="glyphicon glyphicon-comment"></span> ' . sprintf( $comment_counts, count( glob( $comments_dir . $s . '*–*.txt', GLOB_NOSORT ) ) ) .
				'</a>' : '';


				if ( is_dir( $default_imgs_dir = $article_dir . $s . 'images' ) && ! is_link( $default_imgs_dir ) ) {

					$glob_default_imgs = glob( $default_imgs_dir . $s . '*', GLOB_NOSORT );

					if ( $glob_default_imgs ) {

						sort( $glob_default_imgs );

						if ( strpos( $thumbnail_left = img( $glob_default_imgs[0], 'pull-left', false ), 'video' ) !== false ) {

							$default_image = $thumbnail_left;

						} else {

							$default_image =

							'<a href="' . $url . $categ_link . $s . $title_link . '" class=thumbnails>' . $thumbnail_left . '</a> ' . $n;

						}

					$count_images = count( $glob_default_imgs );

					} else {

						$default_image = '';

						$count_images = '';

					}

				} else {

						$default_image = '';

						$count_images = '';

				}

				if ( is_dir( $default_background_dir = $article_dir . $s . 'background-images' ) && ! is_link( $default_background_dir ) ) {

					$glob_default_background_imgs = glob( $default_background_dir . $s . '*', GLOB_NOSORT );

					if ( $glob_default_background_imgs ) {

						sort( $glob_default_background_imgs );

						$default_background_image =

						'<a href="' . $url . $categ_link. $s . $title_link . '" class=thumbnails>' . img( $glob_default_background_imgs[0], 'pull-left', false ) . '</a> ' . $n;

						$count_background_images = count( $glob_default_background_imgs );

					} else {

						$default_background_image = '';

						$count_background_images = '';

					}

				} else {

					$default_background_image = '';

					$count_background_images = '';

				}

				$total_images = ( int )$count_images + ( int )$count_background_images;

				$article .=

				'<div class="panel panel-info">' . $n .
				'<div class=panel-heading>' . $n .
				'<h2 class=panel-title><a href="' . $url . $categ_link . $s . $title_link . '">' . $article_link_title;

				if ( $total_images > 0 ) $article .=

				'<small>' . sprintf( $images_count_title, $total_images ) . '</small>';

				$article .=

				'</a></h2>' . $n .
				'</div>' . $n .
				'<div class=panel-body>' . $default_image . $default_background_image . $section . '</div>' . $n .
				'<div class=panel-footer>' . $n .
				'<a href="' . $url . $categ_link . $s . $title_link . '"><span class="glyphicon glyphicon-play"></span> ' . $more_link_text . '</a><span class=separator></span><span class="glyphicon glyphicon-pencil"></span> ' . convert_to_fuzzy_time( $articles[0] ) . $counter . $comments .
				'</div>' . $n .
				'</div>' . $n;

			}

			if ( $categ_contents_number > $number_of_categ_sections ) {

				$page_ceil = ceil( $categ_contents_number / $number_of_categ_sections );

				numlinks( $pages, $page_ceil, $number_of_pager );

			}

		} else {

			$header .=

			'<title>' . $no_article . ' - ' . $categ_title . ' - ' . $site_name . '</title>' . $n;

			$article .=

			'<h1 class=page-title>' . $no_article . '</h1>' . $n .
			'<div class=article>' . $not_found . '</div>' . $n;

		}

	} else {

		$header .=

		'<title>' . $no_categ . ' - ' . $site_name . '</title>' . $n;

		$article .=

		'<h1 class=page-title>' . $no_categ . '</h1>' . $n .
		'<div class=article>' . $not_found . '</div>' . $n;

	}


} elseif ( filter_has_var( INPUT_GET, 'categ' ) && filter_has_var( INPUT_GET, 'title' ) ) {

	$breadcrumb .=

	'<li><a href="' . $url . r( $get_categ ) . $s . '">' . h( $get_categ ) . '</a></li>' . $n .
	'<li class=active>' . h( $get_title ) . '</li>';

	if ( is_dir( $current_article_dir = 'contents' . $s . $get_categ . $s . $get_title ) && ! is_link( $current_article_dir ) && is_file( $current_article = $current_article_dir . $s . 'index.html' ) && ! is_link( $current_article ) ) {

		if ( is_dir( $background_images_dir = $current_article_dir . $s . 'background-images' ) && ! is_link( $background_images_dir ) ) {

			$glob_background_images = glob( $background_images_dir . $s . '*', GLOB_NOSORT );

			if ( $glob_background_images ) {

				$header .= '<style>';

				foreach( $glob_background_images as $background_images ) {

					if ( list( $width, $height ) = @getimagesize( $background_images ) ) {

						$info = pathinfo( $background_images );

						$classname = '.' . basename( $background_images, '.' . $info['extension'] );

						$aspect = round( $height / $width * 100, 1 );

						$header.='@media(max-width:' . ( $width * 1.5 ) . 'px){' . $classname . '{' . ( $height > 400 ? 'height:0px!important;padding-bottom:' . $aspect . '%' : 'height:' . $height . 'px' ) . '}}' . $classname . '{max-width:' . $width . 'px;background-image:url(' . $url . r( $background_images ) . ');background-size:100%;background-repeat:no-repeat;' . ( $height > 1000 ? 'height:0px!important;padding-bottom:' . $aspect . '%' : 'height:' . $height . 'px' ) . '}';

					}

				}

				$header .= '</style>' . $n;

			}

		}

		$article_encode_title = h( $get_title );


		if ( filter_has_var( INPUT_GET, 'pages' ) && is_numeric( $pages ) ) {

			$header .=

			'<title>' . $article_encode_title . ' - ' . sprintf( $page_prefix, $pages ) . ' - ' . $site_name . '</title>' . $n;

		} else {

			$pages = 1;

			$header .=

			'<title>' . $article_encode_title . ' - ' . $site_name . '</title>' . $n;

		}

		$article_filemtime = filemtime( $current_article );

		$current_url = $url . r( $get_categ ) . $s . r( $get_title );

		$article .=

		'<h1>' . $article_encode_title;

		if ( $use_comment && is_dir( $comment_dir = $current_article_dir . $s . 'comments' ) ) {

			$comments_end = is_file( $comment_dir . $s . 'end.txt' ) ? true : false;

			$glob_comment_files = ! is_link( $comment_dir ) ? glob( $comment_dir . $s . '*–*.txt', GLOB_NOSORT ) : '';

			if ( $glob_comment_files ) {

				$count_comments = count( $glob_comment_files );

				$article .=

				'<small><a href=#form>' . sprintf( $comments_count_title, $count_comments ) . '</a></small>';

			}

		}

		$article .=

		'</h1>' . $n .
		'<div class="text-right social"><span class="glyphicon glyphicon-edit"></span> <span>' . sprintf( $last_modified, date( $time_format, $article_filemtime ) ) . '</span>';


		if ( is_file( $counter_txt = $current_article_dir . $s . 'counter.txt' ) && is_writeable( $counter_txt ) ) {

			$fr = fopen( $counter_txt, 'r+' );

			if ( flock( $fr, LOCK_EX | LOCK_NB ) ) {

				$view_count = fgetss( $fr ) +1;

				$article .=

				' <small class="label label-success">' . sprintf( $view, ( int )$view_count ) . '</small>';

				rewind( $fr );

				fwrite( $fr, $view_count );

				flock( $fr, LOCK_UN );

			}

			fclose( $fr );

		}

		if ( $use_social ) $article .= social( rawurlencode( $get_title . ' - ' . $site_name ), rawurlencode( $url . $get_categ . $s . $get_title ) );

		ob_start();

		include_once $current_article;

		$current_article_content = trim( ob_get_clean() );

		$header .=

		'<meta name=description content="' . description( $current_article_content ) . '">' . $n;

		$article .=

		'</div>' . $n .
		'<div class=article>' . $current_article_content . '</div>' . $n;

		if ( is_dir( $images_dir = $current_article_dir . $s . 'images' ) && ! is_link( $images_dir ) ) {

			$glob_image_files = glob( $images_dir . $s . '*', GLOB_NOSORT );

			if ( $glob_image_files ) {

				sort( $glob_image_files );

				$glob_images_number = count( $glob_image_files );

				$images_in_page = array_slice( $glob_image_files, ( $pages - 1 ) * $number_of_images, $number_of_images );

				$article .=

				'<div class=gallery>';

				for( $i = 0, $c = count( $images_in_page ); $i < $c; ++$i ) {

					if ( list( $width, $height, $type ) = @getimagesize( $images_in_page[$i] ) ) {

						$alt = htmlspecialchars_title( $images_in_page[$i] );

						$img_uri = $url . r( $images_in_page[$i] );

						if ( $type == 2 ) {

							$exif = @exif_read_data( $images_in_page[$i] );

							if ( isset( $exif['COMMENT'] ) ) {

								$exif_comment = h( trim( strip_tags( $exif['COMMENT'][0] ) ) );

								$usercomment = $exif_comment ? $exif_comment : $alt;

							} else {

								$usercomment = $alt;

							}

						} else {

							$usercomment = $alt;

						}

						$article .= $use_thumbnails ?

						'<a href="' . $img_uri . '" target="_blank" rel="noopener noreferrer" onclick="return false" title="' . str_replace( $line_breaks, '&#10;', $usercomment ) . '" class="thumbnail_left thumbnails">' . img( $images_in_page[$i] ) . '</a> ' :

						'<div class="text-center thumbnails">' . $n .
						'<figure class=img-thumbnail>' . $n .
						'<a href="' . $img_uri . '" target="_blank" rel="noopener noreferrer" onclick="return false" title="' . str_replace( $n, '&#10;', $usercomment ) . '">' . $n .
						'<img class="center-block img-responsive" src="' . $url . r( $images_in_page[$i] ) . '" alt="' . $alt . '">' . $n .
						'</a>' . $n .
						'<p class="text-center wrap">' . $usercomment . '</p>' . $n .
						'</figure>' . $n .
						'</div>' . $n;

					} else {

						$info = pathinfo( $images_in_page[$i] );

						$formats = array( 'mp4', 'ogg', 'webm' );

						$extension = strtolower( $info['extension'] );

						if ( isset( $extension ) && array_search( $extension, $formats ) !== false ) $article .=

						'<video class="thumbnail center-block img-responsive" controls src="' . $url . r( $images_in_page[$i] ) . '"></video>';

					}

				}

				$article .=

				'</div>';

				if ( $glob_images_number > $number_of_images ) {

					$page_ceil = ceil( $glob_images_number / $number_of_images );

					numlinks( $pages, $page_ceil, $number_of_pager );

				}

			}

		}

		$glob_prev_next = glob( 'contents' . $s . $get_categ . $s . '*' . $s . 'index.html', GLOB_NOSORT );

		if ( $glob_prev_next ) {

			$article .=

			'<div class=clearfix></div>' . $n;

			if ( $use_similars ) {

				$similar_article = [];

				foreach( $glob_prev_next as $prev_next ) {

					$similar_titles = title( $prev_next );

					similar_text( $get_title, $similar_titles, $percent );

					$per = round( $percent );

					if ( $per < 100 && $per >= 20 ) $similar_article[] = $per . '–' . $similar_titles;

					if ( ! is_link( $prev_next ) && ! is_link( dirname( $prev_next ) ) && ! is_link( dirname( dirname( $prev_next ) ) ) ) {

						$sort_prev_next[] = filemtime( $prev_next ) . '–' . $prev_next;

					}

				}

				if ( $similar_article ) {

					$similar_counts = count( $similar_article );

					if ( $similar_counts >= 1 ) {

						$article.='<h2 class=section>' . $similar_title . '</h2>';

						rsort( $similar_article );

						for( $i = 0; $i < $similar_counts && $i < $number_of_similars; ++$i ) {

							$similar = explode( '–', $similar_article[$i] );

							$article .=

							'<div class="progress similar-article">' . $n .
							'<a class="progress-bar progress-bar-' . color2class( $color ) . ' progress-bar-striped" style="width:' . $similar[0] . '%;" href="' . $url . r( $get_categ ) . $s . r( $similar[1] ) . '">' . h( $similar[1] ) . ' - ' . $similar[0] . '%</a>' . $n .
							'</div>';
						}

					}

				}

			}

			$prev_link = '';

			$article .=

			'<nav class=prev-next>' . $n .
			'<ul class=pager>' . $n;

			rsort( $sort_prev_next );

			for( $i = 0, $c = count( $sort_prev_next ); $i < $c; ++$i ) {

				$prev_next_parts = explode( '–', $sort_prev_next[$i] . '–' . $i );

				$prev_next_title = title( $prev_next_parts[1] );

				if ( $prev_next_parts[0] > $article_filemtime ) {

					$prev_href = $url . r( $get_categ ) . $s . r( $prev_next_title );

					$prev_next_encode_title = h( $prev_next_title );

					$header .=

					'<link rel=prev href="' . $prev_href . '">' . $n;

					$prev_link =

					'<li class=previous>' . $n .
					'<a title="' . $prev_next_encode_title . '" href="' . $prev_href . '">' . $n .
					'<span class="glyphicon glyphicon-menu-left"></span> ' . mb_strimwidth( $prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding ) .
					'</a>' . $n .
					'</li>' . $n;

				}

				if ( $prev_next_parts[0] == $article_filemtime ) $prev_next_count = $prev_next_parts[2];

				if ( $prev_next_parts[0] < $article_filemtime && $prev_next_parts[2] == $prev_next_count + 1 ) {

					$next_href = $url . r( $get_categ ) . $s . r( $prev_next_title );

					$prev_next_encode_title = h( $prev_next_title );

					$header .=

					'<link rel=next href="' . $next_href . '">' . $n;

					$article .=

					'<li class=next>' . $n .
					'<a title="' . $prev_next_encode_title . '" href="' . $next_href . '">' . mb_strimwidth( $prev_next_encode_title, 0, $prev_next_length, $ellipsis, $encoding ) .
					' <span class="glyphicon glyphicon-menu-right"></span>' . $n .
					'</a>' . $n .
					'</li>' . $n;

					break;

				}

			}

			$article .= $prev_link .

			'</ul>' . $n .
			'</nav>' . $n;

		}

		if ( $use_permalink ) {

			$article .= $top .

			'<section id=permalink>' . $n .
			'<h2 class=section>' . $n .
			'<span class="glyphicon glyphicon-link"></span> ' . $permalink .
			'</h2>' . permalink( $article_encode_title . ' - ' . $site_name, $current_url ) .
			'</section>' . $n;

		}

		if ( $use_comment && is_dir( $comment_dir ) && ! is_link( $comment_dir ) ) {

			$article .= $top .

			'<h2 id=form class=section>' . $n .
			'<span class="glyphicon glyphicon-comment"></span> ' . $comment_title .
			'</h2>' . $n;

			if ( isset( $glob_comment_files ) && $number_of_comments > 0 ) {

				rsort( $glob_comment_files );

				if ( ! filter_has_var( INPUT_GET, 'comments' ) && ! is_numeric( $comment_pages ) ) $comment_pages = 1;

				foreach( $glob_comment_files as $comment_files ) {

					if ( ! is_link( $comment_files ) ) {

						$pos_comment_files = stripos( $comment_files, '–' );

						if ( $pos_comment_files !== false ) {

							$comment_file = explode( '–', $comment_files );

							$comment_time = basename( $comment_file[0] );

							$comment_content = trim( strip_tags( file_get_contents( $comment_files ) ) );

							$comment_content = str_replace( $line_breaks, $n, $comment_content );

							$comments_array[] =

							'<div class=col-md-6>' . $n .
							'<div class="panel panel-default comment" id=cid-' . $comment_time . '>' . $n .
							'<div class="panel-body wrap comment_content">' . $comment_content . '</div>' . $n .
							'<div class=panel-footer>' . $n .
							'<span class="glyphicon glyphicon-user"></span> ' . basename( $comment_file[1], '.txt' ) .
							' <span class="glyphicon glyphicon-time"></span> ' . convert_to_fuzzy_time( $comment_time ) .
							'</div>' . $n .
							'</div>' . $n .
							'</div>' . $n;

						}

					}

				}

				if ( isset( $comments_array ) ) {

					$article .=

					'<div class=row>' . $n;

					$comments_in_page = array_slice( $comments_array, ( $comment_pages - 1 ) * $number_of_comments, $number_of_comments );

					for( $i = 0, $c = count( $comments_in_page ); $i < $c; ++$i ) {

						$article .= $comments_in_page[$i];

					}

					$article .=

					'</div>' . $n;

					if ( $count_comments > $number_of_comments ) {

						$article .=

						'<nav>' . $n .
						'<ul class=pager>' . $n;

						if ( $comment_pages > 1 ) $article .=

						'<li class=previous>' . $n .
						'<a href="' . $current_url . '&amp;comments=' . ( $comment_pages - 1 ) . '#form">' . $n .
						'<span class="glyphicon glyphicon-menu-left"></span> ' . $comments_prev .
						'</a>' . $n .
						'</li>' . $n;

						if ( $comment_pages < ceil( $count_comments / $number_of_comments ) ) $article .=

						'<li class=next>' . $n .
						'<a href="' . $current_url . '&amp;comments=' . ( $comment_pages + 1 ) . '#form">' . $comments_next .
						' <span class="glyphicon glyphicon-menu-right"></span>' . $n .
						'</a>' . $n .
						'</li>' . $n;

						$article .=

						'</ul>' . $n .
						'</nav>' . $n;

					}

				}

			}

			if ( $contact && ! $comments_end ) {

				ob_start();

				include_once $contact_file;

				$article .= trim( ob_get_clean() );

			} else {

				$article .=

				'<strong class=page-title>' . $comments_not_allow . '</strong>' . $n;

			}

		}

	} else {

		$header .=

		'<title>' . $no_article . ' - ' . $site_name . '</title>' . $n;

		$article .=

		'<h1 class=page-title>' . $no_article . '</h1>' . $n .
		'<div class=article>' . $not_found . '</div>' . $n;

	}


} elseif ( ! filter_has_var( INPUT_GET, 'categ' ) && ! filter_has_var( INPUT_GET, 'title' ) ) {

	if ( $use_search && filter_has_var( INPUT_GET, 'query' ) ) {

		$no_results = '';

		$word = trim( filter_input( INPUT_GET, 'query', FILTER_SANITIZE_SPECIAL_CHARS ) );

		$result_title = sprintf( $result, $word );

		$breadcrumb .=

		'<li class=active>' . $result_title . '</li>';

		if ( filter_has_var( INPUT_GET, 'pages' ) && is_numeric( $pages ) ) {

			$header .=

			'<title>' . $result_title . ' - ' . sprintf( $page_prefix, $pages ) . ' - ' . $site_name . '</title>' . $n;

		} else {

			$pages = 1;

			$header .=

			'<title>' . $result_title . ' - ' . $site_name . '</title>' . $n;

		}

		$article .=

		'<h1 class=page-title>' . $result_title . '</h1>' . $n;

		$outputs = [];

		$glob_search = glob( '{' . $glob_dir . 'index.html,contents' . $s . '*.html}', GLOB_BRACE + GLOB_NOSORT );

		if ( $glob_search && $word !== null && $word !== '' ) {

			foreach( $glob_search as $search_files ) {

				$sort_search[] = ! is_link( $search_files ) && ! is_link( dirname( $search_files ) ) && ! is_link( dirname( dirname( $search_files ) ) ) ? $search_files : '';

			}

			sort( $sort_search );

			foreach( $sort_search as $filename ) {

				$temp = summary( $filename );

				$file_title = title( $filename );

				$temp.= $file_title == 'contents' ?

				'<br>' . trim( strip_tags( basename( $filename, '.html' ) ) ) :

				'<br>' . categ( $filename ) . ' ' . $file_title;

				$first_pos = mb_stripos( $temp, $word );

				if ( $first_pos !== false ) {

					$start = max( 0, $first_pos - 150 );

					$length = $summary_length + mb_strlen( $word, $encoding );

					$str = mb_substr( $temp, $start, $length, $encoding );

					$str = $str == null ? mb_strimwidth( $temp, 0, $summary_length, $ellipsis, $encoding ) : mb_strimwidth( $str, 0, $summary_length, $ellipsis, $encoding );

					$str = str_replace( $word, '<span class = highlight>' . $word . '</span>', $str );

					$outputs[] = array( filemtime( $filename ), $filename, $str );

				}

			}

			if ( $outputs !== '' ) {

				rsort( $outputs );

				$results_calc = ( $pages - 1 ) * $number_of_results;

				$results_in_page = array_slice( $outputs, $results_calc, $number_of_results );

				for( $i = 0, $c = count( $results_in_page ), $results_number = count( $outputs ); $i < $c; ++$i ) {

					$output = $results_in_page[$i];

					$title = title( $output[1] );

					if ( $title == 'contents' ) {

						$pagename = basename( $output[1], '.html' );

						$article .= $pagename == 'index' ?

						'<h2>' . $n .
						'<a href="' . $url . '">' . $home . '</a>' . $n .
						'</h2>' . $n .
						'<p>' . $output[2] . $top . '</p>' . $n :

						'<h2>' . $n .
						'<a href="' . $url . r( $pagename ) . '">' . h( $pagename ) . '</a>' . $n .
						'</h2>' . $n .
						'<p>' . $output[2] . $top . '</p>' . $n;

					} else {

						$article .=

						'<h2>' . $n .
						'<a href="' . $url . r( categ( $output[1] ) ) . $s . r( $title ) . '">' . h( $title ) . '</a>' . $n .
						'</h2>' . $n .
						'<p>' . $output[2] . $top . '</p>' . $n;

					}

				}

				if ( $results_number > $number_of_results ) {

					$page_ceil = ceil( $results_number / $number_of_results );

					numlinks( $pages, $page_ceil, $number_of_pager );

				}

			} else {

				$no_results = true;

			}

		} else {

			$no_results = true;

		}

		if ( $no_results ) $article .=

		'<h2>' . $no_results_found . '</h2>' . $n;


	} elseif ( is_file( $default_file = 'contents' . $s . 'index.html' ) && ! is_link( $default_file ) ) {

		$header .=

		'<title>' . $site_name . ( $subtitle ? ' - ' . $subtitle : '' ) . '</title>' . $n .
		'<meta name=description content="' . $meta_description . '">' . $n;

		$article .=

		'<h1 class=page-title>' . $site_name . ( $subtitle ? ' <small class=wrap>' . $subtitle . '</small>' : '' ) . '</h1>' . $n .
		'<div class=article>';

		ob_start();

		include_once $default_file;

		$article .= trim( ob_get_clean() );

		$article .=

		'</div>' . $n;

	} else {

		if ( filter_has_var( INPUT_GET, 'pages' ) && is_numeric( $pages ) ) {

			$header .=

			'<title>' . $site_name . ' - ' . sprintf( $page_prefix, $pages ) . '</title>' . $n;

			$article .=

			'<h1 class=page-title>' . $site_name . ' <small>' . sprintf( $page_prefix, $pages ) . '</small></h1>' . $n;

		} else {

			$pages = 1;

			$header .=

			'<title>' . $site_name . ( $subtitle ? ' - ' . $subtitle : '' ) . '</title>' . $n;

			$article .=

			'<h1 class=page-title>' . $site_name . ( $subtitle ? ' <small class=wrap>' . $subtitle . '</small>' : '' ) . '</h1>' . $n;

		}

		$header .=

		'<meta name=description content="' . $meta_description . '">' . $n;


		$glob_files = glob( $glob_dir . 'index.html', GLOB_NOSORT );

		if ( $glob_files ) {

			foreach( $glob_files as $all_files ) {

				$all_sort[] = ! is_link( $all_files ) && ! is_link( dirname( $all_files ) ) && ! is_link( dirname( dirname( $all_files ) ) ) ? filemtime( $all_files ) . '–' . $all_files :'';

			}

			$all_sort = array_filter( $all_sort );

			rsort( $all_sort );

			$default_contents_number = count( $all_sort );

			$sections_in_default_page = array_slice( $all_sort, ( $pages - 1 ) * $number_of_default_sections, $number_of_default_sections );

			for( $i = 0, $c = count( $sections_in_default_page ); $i < $c; ++$i ) {

				$all_articles = explode( '–', $sections_in_default_page[$i] );

				$section =

				'<p class=wrap>' . summary( $all_articles[1] ) . '</p>' . $n;

				$all_link = explode( $s, $all_articles[1] );

				$categ_link = r( $all_link[1] );

				$title_link = r( $all_link[2] );

				$article_link_title = htmlspecialchars_title( $all_link[2] );

				$article_dir = dirname( $all_articles[1] );

				$counter = is_file( $counter_txt = $article_dir . $s . 'counter.txt' ) ?

				'<span class=separator></span><span class="glyphicon glyphicon-eye-open"></span> ' . sprintf( $display_counts, ( int )trim( strip_tags( file_get_contents( $counter_txt ) ) ) ) : '';

				$comments = is_dir( $comments_dir = $article_dir . $s . 'comments' ) && ! is_link( $comments_dir ) && $use_comment ?

				'<span class=separator></span><a href="' . $url . $categ_link . $s . $title_link . '#form"><span class="glyphicon glyphicon-comment"></span> ' . sprintf( $comment_counts, count( glob( $comments_dir . $s . '*–*.txt' ), GLOB_NOSORT ) ) . '</a>' : '';


				if ( is_dir( $default_imgs_dir = $article_dir . $s . 'images' ) && ! is_link( $default_imgs_dir ) ) {

					$glob_default_imgs = glob( $default_imgs_dir . $s . '*', GLOB_NOSORT );

					if ( $glob_default_imgs ) {

						sort( $glob_default_imgs );

						$default_image = strpos( $thumbnail_left = img( $glob_default_imgs[0], 'pull-left', false ), 'video' ) !== false ? $thumbnail_left :

						'<a href="' . $url . $categ_link. $s . $title_link . '" class=thumbnails>' . $thumbnail_left . '</a> ' . $n;

						$count_images = count( $glob_default_imgs );

					} else {

						$default_image = '';

						$count_images = '';

					}

				} else {

					$default_image = '';

					$count_images = '';

				}

				if ( is_dir( $default_background_dir = $article_dir . $s . 'background-images' ) && ! is_link( $default_background_dir ) ) {

					$glob_default_background_imgs = glob( $default_background_dir . $s . '*', GLOB_NOSORT );

					if ( $glob_default_background_imgs ) {

						sort( $glob_default_background_imgs );

						$default_background_image =

						'<a href="' . $url . $categ_link. $s . $title_link . '" class=thumbnails>' . img( $glob_default_background_imgs[0], 'pull-left', false ) . '</a> ' . $n;

						$count_background_images = count( $glob_default_background_imgs );

					} else {

						$default_background_image = '';

						$count_background_images = '';

					}

				} else {

					$default_background_image = '';

					$count_background_images = '';

				}

				$total_images = ( int )$count_images + ( int )$count_background_images;

				$article .=

				'<div class="panel panel-info">' . $n .
				'<div class=panel-heading>' . $n .
				'<h2 class=panel-title>' . $n .
				'<a href="' . $url . $categ_link. $s . $title_link . '">' . $article_link_title;

				if ( $total_images > 0 ) $article .=

				'<small>' . sprintf( $images_count_title, $total_images ) . '</small>';

				$article .=

				'</a>' . $n .
				'</h2>' . $n .
				'</div>' . $n .
				'<div class=panel-body>' . $default_image . $default_background_image . $section . '</div>' . $n .
				'<div class=panel-footer>' . $n .
				'<a href="' . $url . $categ_link. $s . $title_link . '"><span class="glyphicon glyphicon-play"></span> ' . $more_link_text . '</a><span class=separator></span><span class="glyphicon glyphicon-pencil"></span> ' . convert_to_fuzzy_time( $all_articles[0] ) .
				'<span class=separator></span><a href="' . $url . $categ_link . $s . '"><span class="glyphicon glyphicon-folder-open"></span> ' . h( $all_link[1] ) . '</a>' . $counter . $comments .
				'</div>' . $n .
				'</div>' . $n;

			}

			if ( $default_contents_number > $number_of_default_sections ) {

				$page_ceil = ceil( $default_contents_number / $number_of_default_sections );

				numlinks( $pages, $page_ceil, $number_of_pager );

			}

		}

	}

} else {

	$header .=

	'<title>' . $error . ' - ' . $site_name . '</title>' . $n;

	$article .=

	'<h1 class=page-title>' . $error . '</h1>' . $n .
	'<div class=article>' . $not_found . '</div>' . $n;

}

$article .=

'<div id=end-of-article class=clearfix></div>';


if ( $use_search ) {

	$aside .=

	'<form method=get action="' . $url . '">' . $n .
	'<fieldset>' . $n .
	'<div class="input-group input-group-lg search">' . $n .
	'<input placeholder=Search type=search id=search name=query required class=form-control tabindex=1 accesskey=i>' . $n .
	'<span class=input-group-btn>' . $n .
	'<button class="btn btn-default btn-lg" type=submit tabindex=2 accesskey=q>' . $n .
	'<span class="glyphicon glyphicon-search"></span>' . $n .
	'<span class=sr-only>&#9906;</span>' . $n .
	'</button>' . $n .
	'</span>' . $n .
	'</div>' . $n .
	'</fieldset>' . $n .
	'</form>' . $n;

}


if ( $use_recents && ! empty( $contents ) ) {

	$recent_dirs = glob( $glob_dir, GLOB_ONLYDIR + GLOB_NOSORT );

	if ( $recent_dirs ) {

		$aside .=

		'<div class=panel-default>' . $n .
		'<div class="list-group-item active">' . $n .
		'<h2 class=panel-title><span class="glyphicon glyphicon-plus-sign"></span> ' . $recents . '</h2>' . $n .
		'</div>' . $n;

		foreach( $recent_dirs as $recents_name ) {

			if ( is_file( $recents_index = $recents_name . $s . 'index.html' ) && ! is_link( $recents_index ) && ! is_link( dirname( $recents_index ) ) && ! is_link( dirname( $recents_name ) ) )

			$recents_sort[] = filemtime( $recents_index ) . '–' . $recents_name;

		}

		if ( isset( $recents_sort ) ) {

			rsort( $recents_sort );

			for( $i = 0, $c = count( $recents_sort ); $i < $c && $i < $number_of_recents; ++$i ) {

				$recent_name = explode( '–', $recents_sort[$i] );

				$recent_basename = basename( $recent_name[1] );

				$aside .=

				'<a class="list-group-item' . ( $get_categ . $get_title === basename( dirname( $recent_name[1] ) ) . $recent_basename ? ' list-group-item-info' : '' ) . '" href="' . $url . r( title( $recent_name[1] ) . $s . $recent_basename ) . '">' . h( $recent_basename ) . '</a>' . $n;

			}

		}

		$aside .=

		'</div>' . $n;

	}

}


$glob_info_files = array_diff( glob( 'contents' . $s . '*.html', GLOB_NOSORT ), array( 'contents' . $s . 'index.html' ) );

if ( $glob_info_files || $dl || $use_contact ) {

	$aside .=

	'<div class=panel-default>' . $n .
	'<div class="list-group-item active">' . $n .
	'<h2 class=panel-title><span class="glyphicon glyphicon-info-sign"></span> ' . $informations . '</h2>' . $n .
	'</div>' . $n;

	if ( $glob_info_files ) {

		foreach( $glob_info_files as $info_files ) {

			$infos_sort[] = ! is_link( $info_files ) ? filemtime( $info_files ) . '–' . basename( $info_files, '.html' ) : '';

		}

		$infos_sort = array_filter( $infos_sort );

		rsort( $infos_sort );

		for( $i = 0, $c = count( $infos_sort ); $i < $c; ++$i ) {

			$infos_uri = explode( '–', $infos_sort[$i] );

			$aside .=

			'<a class="list-group-item' . ( $get_page === $infos_uri[1] ? ' list-group-item-info' : '' ) . '" href="' . $url . r( $infos_uri[1] ) . '">' . h( $infos_uri[1] ) . '</a>' . $n;

		}

	}

	if ( $dl ) $aside .=

	'<a class="list-group-item' . ( $get_page === $download_contents ? ' list-group-item-info' : '' ) . '" href="' . $url . r( $download_contents ) . '">' . $download_contents . '</a>' . $n;

	if ( $use_contact && $contact ) $aside .=

	'<a class="list-group-item' . ( $get_page === $contact_us ? ' list-group-item-info' : '' ) . '" href="' . $url . r( $contact_us ) . '">' . $contact_us . '</a>' . $n;

	$aside .=

	'</div>' . $n;

}


if ( $address ) {

	$aside .=

	'<div class=panel-default>' . $n .
	'<div class="list-group-item active">' . $n .
	'<h2 class=panel-title><span class="glyphicon glyphicon-ok-sign"></span> ' . $site_name . '</h2>' . $n .
	'</div>' . $n .
	'<div class=list-group-item>' . $n .
	'<div class=wrap>' . $address . '</div>' . $n .
	'</div>' . $n .
	'</div>' . $n;

}


if ( $use_popular_articles && $number_of_popular_articles > 0 && ! empty( $contents ) ) {

	$glob_all_counter_files = glob( $glob_dir . 'counter.txt', GLOB_NOSORT );

	if ( $glob_all_counter_files ) {

		$aside .=

		'<div class="panel panel-default">' . $n .
		'<div class=panel-heading>' . $n .
		'<h2 class=panel-title><span class="glyphicon glyphicon-circle-arrow-right"></span> ' . $popular_articles . '</h2>' . $n .
		'</div>' . $n .
		'<div class=list-group>' . $n;

		foreach( $glob_all_counter_files as $all_counter_files ) {

			$counter_sort[] = ! is_link( dirname( $all_counter_files ) . $s . 'index.html' ) && ! is_link( dirname( $all_counter_files ) ) && ! is_link( dirname( dirname( $all_counter_files ) ) ) ?

			( int )trim( strip_tags( file_get_contents( $all_counter_files ) ) ) . $all_counter_files : '';

		}

		$counter_sort = array_filter( $counter_sort );

		rsort( $counter_sort, SORT_NUMERIC );

		for( $i = 0, $c = count( $counter_sort ); $i < $c && $i < $number_of_popular_articles; ++$i ) {

			$popular_titles = explode( $s, $counter_sort[$i] );

			$aside .=

			'<a class="list-group-item' . ( $get_categ . $get_title === $popular_titles[1] . $popular_titles[2] ? ' list-group-item-info' : '' ) . '" href="' . $url . r( $popular_titles[1] ) . $s . r( $popular_titles[2] ) . '">' . h( $popular_titles[2] ) . '</a>' . $n;

		}

		$aside .=

		'</div>' . $n .
		'</div>' . $n;

	}

}


if ( $use_comment && $number_of_new_comments > 0 && ! empty( $contents ) ) {

	$glob_all_comment_files = glob( $glob_dir . 'comments' . $s . '*–*.txt', GLOB_NOSORT );

	if ( $glob_all_comment_files ) {

		$aside .=

		'<div class="panel panel-default">' . $n .
		'<div class=panel-heading>' . $n .
		'<h2 class=panel-title><span class="glyphicon glyphicon-circle-arrow-right"></span> ' . $recent_comments . '</h2>' . $n .
		'</div>' . $n .
		'<div class=list-group>' . $n;

		foreach( $glob_all_comment_files as $all_comment_files ) {

			$new_comments_sort[] = ! is_link( $all_comment_files ) && ! is_link( dirname( $all_comment_files ) ) && ! is_link( dirname( dirname( $all_comment_files ) ) ) && ! is_link( dirname( dirname( dirname( $all_comment_files ) ) ) ) && ! is_link( dirname( dirname( $all_comment_files ) ) . $s . 'index.html' ) ? $all_comment_files : '';

		}

		$new_comments_sort = array_filter( $new_comments_sort );

		rsort( $new_comments_sort );

		for( $i = 0, $c = count( $new_comments_sort ); $i < $c && $i < $number_of_new_comments; ++$i ) {

			$comments_content = trim( strip_tags( file_get_contents( $new_comments_sort[$i] ) ) );

			$comments_content = str_replace( $line_breaks, ' ', $comments_content );

			$new_comments = explode( '–', $new_comments_sort[$i] );

			$comment_link = explode( $s, $new_comments[0] );

			$aside .=

			'<a class="list-group-item list-comment" href="' . $url . r( $comment_link[1] ) . $s . r( $comment_link[2] ) . '#cid-' . basename( $new_comments[0] ) . '">' . $n .
			'<p class="comment-text wrap list-group-item-text">' . mb_strimwidth( $comments_content, 0, $comment_length, $ellipsis, $encoding ) . '</p>' . $n .
			'<small class=list-group-item-text>' . h( basename( $new_comments[1], '.txt' ) ) . '(' . convert_to_fuzzy_time( basename( $new_comments[0] ) ) . ')</small>' . $n .
			'</a> ' . $n;

		}

		$aside .=

		'</div>' . $n .
		'</div>' . $n;

	}

}

$footer.=

'<small>' . $n .
'<span class=center-block>Copyright <span class="glyphicon glyphicon-copyright-mark"></span>
' . date( 'Y' ) . ' ' . $site_name . '. <br>Powered by Kinaga.</span>' . $n .
'<a href="#TOP" class="pull-right top"><span class="glyphicon glyphicon-chevron-up"></span></a>' . $n .
'</small>' . $n;

$header .=

'<meta name=application-name content=kinaga>' . $n .
'<link rel=alternate type="application/atom+xml" href="' . $url . 'atom.php">' . $n .
( ! is_file( 'favicon.ico' ) ? '<link href="' . $url . 'images' . $s . 'icon.php" rel=icon type="image/svg+xml" sizes=any>' : '<link rel="shortcut icon" href="' . $url . 'favicon.ico">' ) . $n;


/* https://gist.github.com/wgkoro/4985763 */

function convert_to_fuzzy_time( $time_db ) {

	global $now, $seconds_ago, $minutes_ago, $hours_ago, $days_ago, $present_format, $time_format;

	$unix = $time_db;

	$diff_sec = $now - $unix;

	if ( $diff_sec < 60 ) {

		$time = $diff_sec;

		$unit = $seconds_ago;

	} elseif ( $diff_sec < 3600 ) {

		$time = $diff_sec / 60;

		$unit = $minutes_ago;

	} elseif ( $diff_sec < 86400 ) {

		$time = $diff_sec / 3600;

		$unit = $hours_ago;

	} elseif ( $diff_sec < 2764800 ) {

		$time = $diff_sec / 86400;

		$unit = $days_ago;

	} else {

		if ( date( "Y" ) != date( "Y", $unix ) ) {

			$time = date( $time_format, $unix );

		} else {

			$time = date( $present_format, $unix );

		}

		return $time;

	}

	return ( int )$time . $unit;

}


/* Author: Ingo Smeritschnig
 * Contact: ingo@lucidlab.cc
 * Feel free to use, alter, ditribute, redistribute, ... this script as you want.
 * Website: http://blog.polymorph.at/105/finished-scripts/prev-next-1-2-3-page-link-php-function
 */

function numlinks( $pagenum, $maxpage, $pages_visible, $scriptname = '' ) {

	global $article, $nav_laquo, $nav_raquo, $s, $n;

	$article .=

	'<div class=text-center id=pager>' . $n .
	'<ul class="pagination pagination-lg">' . $n;

	if ( $pagenum > 1 ) {

		$article .=

		'<li><a href="' . page_name( ( $pagenum - 1 ), $scriptname ) . '">' . $nav_laquo . '</a></li>' . $n;

	} else {

		$article .=

		'<li class=disabled><a>' . $nav_laquo . '</a></li>' . $n;

	}

	$i = 1;

	while ( $i <= $pages_visible ) {

	if ( $pagenum - ceil( $pages_visible / 2 ) < 0 ) {

		if ( $i == $pagenum ) $article .=

		'<li class=active><a>' . $pagenum . '</a></li>' . $n;

		else $article .=

		'<li><a href="' . page_name( $i, $scriptname ) . '">' . ( $i ) . '</a></li>' . $n;

	} elseif ( $pagenum + floor( $pages_visible / 2 ) > $maxpage ) {

		if ( $maxpage > $pages_visible ) $j = $maxpage - $pages_visible + $i;

		else $j = $i;

		if ( $j == $pagenum ) $article .=

		'<li class=active><a>' . $pagenum . '</a></li>' . $n;

		else $article .=

		'<li><a href="' . page_name( $j, $scriptname ) . '">' . $j . '</a></li>' . $n;

	} else {

		if ( $i == ceil( $pages_visible / 2 ) ) $article .=

		'<li class="disable active"><a>' . $pagenum . '</a></li>' . $n;

		else {

			$j = $pagenum - ceil( $pages_visible / 2 ) +$i;

			$article .=

			'<li><a href="' . page_name( $j, $scriptname ) . '">' . $j . '</a></li>' . $n;

		}

	}

	if ( $i == $maxpage ) break;

	++$i;

	}

	$article .= $pagenum < $maxpage ?

	'<li><a href="' . page_name( ( $pagenum + 1 ), $scriptname ) . '">' . $nav_raquo . '</a></li>' . $n : '<li class=disabled><a>' . $nav_raquo . '</a></li>' . $n;

	$article .=

	'</ul>' . $n .
	'</div>' . $n;

}


function page_name( $nr, $scriptname ) {

	global $url, $categ_link, $current_url, $word, $s, $download_contents;

	if ( filter_has_var( INPUT_GET, 'categ' ) && filter_has_var( INPUT_GET, 'title' ) ) {

		$scriptname = $current_url . '&amp;pages=' . $nr;

	} elseif ( filter_has_var( INPUT_GET, 'categ' ) && ! filter_has_var( INPUT_GET, 'title' ) ) {

		$scriptname = $url . $categ_link. $s . $nr . $s;

	} elseif ( filter_has_var( INPUT_GET, 'query' ) ) {

		$scriptname = $url . '?query=' . $word . '&amp;pages=' . $nr;

	} elseif ( filter_has_var( INPUT_GET, 'page' ) == $download_contents ) {

		$scriptname = $url . r( $download_contents ) . '&amp;pages=' . $nr;

	} else {

		$scriptname = $url . '?pages=' . $nr;

	}

	return $scriptname;

}

