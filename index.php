<?php
#exit( 'Down for Maintenance...' );
#error_reporting( -1 );
if ( is_link( 'contents' ) )
{
	exit;
}
if ( is_file( $config = 'includes' . DIRECTORY_SEPARATOR . 'config.php' ) && ! is_link( $config ) )
{
	include_once $config;
}
header( 'Content-Type: text/html; charset=' . $encoding );
include_once 'includes' . $s . 'functions.php';
if ( is_file( $tpl = $tpl_dir . 'index.php' ) && ! is_link( $tpl ) )
{
	include_once $tpl;
}
else
{
	include_once $includes . 'index.php';
}
