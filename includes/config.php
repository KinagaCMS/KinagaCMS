<?php
/*
  * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
  * @license    GPL 3.0 or later; see LICENSE file for details.
  */

$lang = 'en';

$mail_address = '';

# bootstrap-header, bootstrap-narrow, bootstrap-navbar, bootstrap-simple, bootstrap-harlequin
$template = 'bootstrap-navbar';


##########################


$encoding = 'UTF-8';

setlocale( LC_ALL, 'C.' . $encoding );

#Download Multi-byte filename for Windows Internet Explorer Japanese language
$encoding_win = 'SJIS-win';


##########################


#Use Auto wrap
$use_auto_wrap = true;

#Allow Comments
$use_comment = true;

#Use Contact
$use_contact = true;

#Use Search
$use_search = true;

#Show Social buttons
$use_social = true;

#Use Permalink
$use_permalink = true;

#Show Recent articles
$use_recents = true;

#Show Popular articles
$use_popular_articles = true;

#Show Thumbnails
$use_thumbnails = true;

#Show Similar articles
$use_similars = true;


##########################


#Sidebox
$number_of_recents = '5';

$number_of_popular_articles = '5';

$number_of_new_comments = '5';

$comment_length = '100';


#Top page
$number_of_default_sections = '5';


#Category
$number_of_categ_sections = '5';

#Search
$number_of_results = '5';

#Atom
$number_of_feeds = '10';


#Article images
$number_of_images = '10';

#Comments
$number_of_comments = '5';


#Category and Search results
$summary_length = '300';


#META description and Atom
$description_length = '150';


#Prev Next link title length
$prev_next_length = '50';


#Download files
$number_of_downloads = '10';


#Page Navigation
$number_of_pager = '5';

#Similar articles
$number_of_similars = '3';


##########################


$s = DIRECTORY_SEPARATOR;

$n = PHP_EOL;


##########################


if ( ! function_exists( 'r' ) ) {

	function r( $path ) {

		global $s;

		$entities = array( '%26', '%2F', '%5C' );

		$replaces = array( '&amp;', $s, $s );

		return str_replace( $entities, $replaces, rawurlencode( $path ) );

	}

}


if ( ! function_exists( 'h' ) ) {

	function h( $str ) {

		global $encoding;

		return htmlspecialchars( $str, ENT_QUOTES | ENT_SUBSTITUTE, $encoding );

	}

}


if ( ! function_exists( 'size_unit' ) ) {

	function size_unit( $size ) {

		$unit = array( 'B', 'KB', 'MB', 'GB' );

		if ( $size > 0 ) return round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . ' ' . $unit[$i];

	}

}


if ( ! function_exists( 'timestamp' ) ) {

	function timestamp( $file ) {

		return gmdate( 'D, d M Y H:i:s T', filemtime( $file ) );

	}

}


if ( ! function_exists( 'sideless' ) ) {

	function sideless() {

		global $header;

		return $header .= '<style>.col-md-9{width:100%}.col-md-3{display:none}</style>';

	}

}


if ( ! function_exists( 'nowrap' ) ) {

	function nowrap() {

		global $header;

		return $header .= '<style>.article{white-space:normal}</style>';

	}

}


##########################


$now = time();

$port = getenv( 'SERVER_PORT' );

$server = getenv( 'SERVER_NAME' );

$dir = dirname( getenv( 'SCRIPT_NAME' ) );

$addslash = $dir != $s ? $s : '';

$script = r( $dir ) . $addslash;

$scheme = empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://';

$url = ( $port == '80' ) ? $scheme . $server . $script : $scheme . $server . ':' . $port . $script;

$line_breaks = array( "\n", "\r\n", "\r", '&#13;&#10;', '&#13;', '&#10;' );

$user_agent = getenv( 'HTTP_USER_AGENT' );

$user_agent_lang = getenv( 'HTTP_ACCEPT_LANGUAGE' );

$glob_dir = 'contents' . $s . '*' . $s . '*' . $s;

$tpl_dir = 'templates' . $s . $template . $s;

$css = $url . $tpl_dir . 'css' . $s;

$js = $url . $tpl_dir . 'js' . $s;


##########################


if ( is_file( $lang_file = __DIR__ . $s . 'lang' . $s . $lang . '.php' ) && !is_link( $lang_file ) ) include_once $lang_file;

