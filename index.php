<?php
/*
 * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
 * @license    GPL 3.0 or later; see LICENSE file for details.
 */

#exit( 'Down for Maintenance...' );

ini_set( 'display_errors', 1 );

ini_set( 'display_startup_errors', 1 );

#error_reporting( -1 );

if ( is_link( 'contents' ) )

    exit;

if ( is_file( $config = 'includes' . DIRECTORY_SEPARATOR . 'config.php' ) && ! is_link( $config ) )

    include_once $config;

header( 'Content-Type: text/html; charset=' . $encoding );

include_once 'includes' . $s . 'functions.php';

if ( is_file( $tpl = $tpl_dir . 'index.php' ) && ! is_link( $tpl ) ) {

    include_once $tpl;

} else {

    include_once $includes . 'index.php';

}
