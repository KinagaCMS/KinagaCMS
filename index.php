<?php
#exit('Down for Maintenance...');

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

$time_start = microtime(true);
$base_mem = memory_get_usage();

ob_implicit_flush(true);

if (!is_file($session_txt = './'. sha1(__DIR__). '.txt'))
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
