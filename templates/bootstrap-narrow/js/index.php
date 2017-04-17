<?php
/*
  * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
  * @license    GPL 3.0 or later; see LICENSE file for details.
  */

if ( is_file( $config = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'config.php' ) && !is_link( $config ) ) include_once $config;

$last_modified = timestamp( 'jquery.min.js' );

header( 'Content-Type: application/javascript; charset=' . $encoding );

header( 'Last-Modified: ' . $last_modified );

if ( filter_input( INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE' ) === $last_modified ) header( 'HTTP/1.1 304 Not Modified' );

echo

file_get_contents( 'jquery.min.js' ),

file_get_contents( 'bootstrap.min.js' ),

file_get_contents( 'jquery.magnific-popup.min.js' ),

'/* kinaga */$( document ).ready( function(){ $( "a[href=\"#TOP\"]" ).click( function(){ $( "body, html" ).animate( { scrollTop: 0 },100 ); return false } ); } ); $( ".gallery" ).each( function(){ $( this ).magnificPopup( { delegate: "a", type: "image", gallery: { enabled: true, preload: [1,1] } } ) } ); $( ".expand" ).magnificPopup( { type: "image" } ); $( ".discrete" ).magnificPopup( { type: "image" } ); $( ".nav-tabs a" ).click( function(e){ e.preventDefault(); $( this ).tab( "show" ) } )';
