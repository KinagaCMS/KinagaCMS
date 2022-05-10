<?php
#exit('Down for Maintenance...');

$time_start = hrtime(true);
$base_mem = memory_get_usage();

#error_reporting(0);
#error_reporting(E_ALL);

ob_implicit_flush(1);

/*
$session_txt = './d5c0a6126819d5d63b9c6d3361eabba1ea7a5d9c.txt';
*/
if (!isset($session_txt) && !is_file($session_txt = './'. sha1(__DIR__). '.txt'))
{
	file_put_contents($session_txt, str_shuffle(base64_encode(openssl_random_pseudo_bytes(256))), LOCK_EX);
	chmod($session_txt, 0600);
}

include 'includes/functions.php';
include 'includes/config.php';
include 'includes/core.php';

header('Content-Type: text/html; charset='. $encoding);

if (is_file($tpl = $tpl_dir. 'index.php'))
	include $tpl;
else
	include 'includes/index.php';
