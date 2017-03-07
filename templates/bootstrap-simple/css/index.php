<?
/*
  * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
  * @license    GPL 3.0 or later; see LICENSE file for details.
  */

if ( is_file( $config = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'config.php' ) && !is_link( $config ) ) include_once $config;

$last_modified = timestamp( 'bootstrap-additional.min.css' );

header( 'Content-Type: text/css; charset=' . $encoding );

header( 'Last-Modified: ' . $last_modified );

if ( filter_input( INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE' ) === $last_modified ) header( 'HTTP/1.1 304 Not Modified' );

echo

file_get_contents( 'bootstrap.min.css' ),

file_get_contents( 'magnific-popup.min.css' );

include_once 'bootstrap-additional.min.css';
