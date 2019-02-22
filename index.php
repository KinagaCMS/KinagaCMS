<?php
#exit('Down for Maintenance...');

$time_start = microtime(true);
$base_mem = memory_get_usage();

error_reporting(-1);
ob_implicit_flush(true);

include 'includes/functions.php';
include 'includes/config.php';
include 'includes/core.php';

header('Content-Type: text/html; charset='. $encoding);

if (is_file($tpl = $tpl_dir. 'index.php'))
{	
	include $tpl;
}
else
{
	include 'includes/index.php';
}
