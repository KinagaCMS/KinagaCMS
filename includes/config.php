<?php

$template = 'bootstrap4';

#See includes/social.php
$social_medias = array('googleplus', 'facebook', 'twitter', 'hatena', 'line');

##########################

$lang = 'ja';

$encoding = 'UTF-8';

#Download Multi-byte filename for IE
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

#Show Summary
$use_summary = true;

##########################

#Sidebox
$number_of_recents = 5;

$number_of_popular_articles = 5;

$number_of_new_comments = 5;

$comment_length = 100;


#Top page
$number_of_default_sections = 5;

#Category
$number_of_categ_sections = 5;

#Search
$number_of_results = 5;

#Atom
$number_of_feeds = 10;


#Article images
$number_of_images = 10;

#Comments
$number_of_comments = 10;


#Category and Search results
$summary_length = 300;


#META description and Atom
$description_length = 150;


#Prev Next link title length
$prev_next_length = 50;


#Download files
$number_of_downloads = 10;


#Page Navigation
$number_of_pager = 5;

#Similar articles
$number_of_similars = 3;


##########################


$n = PHP_EOL;


##########################


function r($path)
{
	if (strpos($path, '%') !== false)
		return $path;
	else
		return str_replace(array('%26', '%2F', '%5C', '%3A'), array('&amp;', '/', '/', ':'), rawurlencode($path));
}

function h($str)
{
	global $encoding;
	return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
}

function size_unit($size)
{
	if ($size > 0)
	{
		$unit = array('B', 'KB', 'MB', 'GB');
		return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $unit[$i];
	}
}

function timestamp($file)
{
	return gmdate('D, d M Y H:i:s T', filemtime($file));
}

function is_ssl()
{
	if (isset($_SERVER['HTTPS']) && isset($_SERVER['SSL']) || isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR']))
		return true;
}

##########################


$now = time();

$port = getenv('SERVER_PORT') !== '80' ? ':' . getenv('SERVER_PORT') : '';

$server = getenv('SERVER_NAME');

$dir = r(dirname(getenv('SCRIPT_NAME')));

$addslash = $dir !== '/' ? '/' : '';

$script = $dir . $addslash;

$scheme = is_ssl() ? 'https://' : 'http://';

$url = $scheme . $server . $port . $script;

$line_breaks = array("\r\n", "\n", "\r", '&#13;&#10;', '&#13;', '&#10;');

$remote_addr = filter_var(getenv('REMOTE_ADDR'), FILTER_VALIDATE_IP);

$user_agent = h(getenv('HTTP_USER_AGENT'));

$user_agent_lang = h(getenv('HTTP_ACCEPT_LANGUAGE'));

$token = bin2hex(openssl_random_pseudo_bytes(16));

$glob_dir = 'contents/*/*/';

$tpl_dir = 'templates/' . $template . '/';

$css = $url . $tpl_dir . 'css/';

$js = $url . $tpl_dir . 'js/';

$glob_imgs ='/*.{[jJ][pP][gG],[pP][nN][gG],[gG][iI][fF],[sS][vV][gG],[jJ][pP][eE][gG],[mM][pP]4,[oO][gG][gG],[wW][eE][bB][mM]}';

##########################


if (is_file($lang_file = __DIR__ . '/lang/' . $lang . '.php'))
	include_once $lang_file;
