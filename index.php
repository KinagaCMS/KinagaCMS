<?php
#exit('Down for Maintenance...');

$time_start = microtime(true);
$base_mem = memory_get_usage();

error_reporting(-1);
ob_implicit_flush(true);

if (is_file($config = 'includes/config.php'))
	include_once $config;

header('Content-Type: text/html; charset=' . $encoding);

include_once 'includes/functions.php';

if (is_file($tpl = $tpl_dir . 'index.php'))
	include_once $tpl;
else
	include_once 'includes/index.php';
