<?php

$template = 'default';

#See includes/social.php
$social_medias = ['facebook', 'twitter', 'hatena', 'line'];

#if !contents/index.html, set 1 to 4
$index_type = 1;
#$index_type is 2 to 4, set the number of links in each category
$index_items = 4;

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

#Show Prev Next link in articles
$use_prevnext = true;

#Show Popular articles
$use_popular_articles = true;

#Show Thumbnails
$use_thumbnails = true;

#Show Thumbnails in categ
$use_categ_thumbnails = true;

#Show Similar articles
$use_similars = true;

#Show Summary
$use_summary = true;

#Show approach menu in user profile
$use_user_approach = true;

#Forum
$use_forum = true;
#Guests can create threads and topics
$allow_guest_creates = 0;

#Benchmark in footer
$use_benchmark = true;

##########################

#Sidebox

$sidebox_order = [
	1, #login
	7, #similar
	2, #social
	3, #permalink
	5, #recent
	1, #information
	4, #popular
	6, #comments
	1, #toc
	9, #address
	1, #category
	12 #forum
	];

$sidebox_wrapper_class = [
	'list-group mb-5',
	'list-group-item collapse show pl-0 pr-3',
	'list-group mb-5 w-100' #toc
	];
$sidebox_title_class = [
	'list-group-item bg-primary title', #sidebox 1
	'list-group-item list-group-item-primary title', #sidebox 2
	'list-group-item bg-success title', # login success
	'list-group-item bg-danger title', #login error
	'list-group-item bg-info navbar-dark d-flex align-items-center justify-content-between py-2 title', #toc
	];
$sidebox_content_class = [
	'list-group-item list-group-item-action',
	'list-group-item-text wrap',
	'list-group-item wrap',
	'list-group-item',
	'p-1 border-0 d-block list-group-item list-group-item-action'
	];

$number_of_recents = 5;

$number_of_popular_articles = 5;

$number_of_new_comments = 5;

$comment_length = 100;


#Top page: if $index_type = 1;
$default_sections_per_page = 6;

#Category
$categ_sections_per_page = 6;

#Search
$results_per_page = 6;

#Atom
$number_of_feeds = 10;


#Images in Article
$images_per_page = 10;

#Comments
$comments_per_page = 10;


#Category and Search results
$summary_length = 300;


#META description and Atom
$description_length = 150;


#Prev Next link title length
$prev_next_length = 100;


#Download files
$number_of_downloads = 10;


#Page Navigation
$number_of_pager = 5;

#Similar articles
$number_of_similars = 3;


#Approached users per page
$users_per_page = 4;


#Forum home & sidebox
$number_of_topics = 5;

#threads/topics per page
$forum_contents_per_page = 10;

#Limit the maximum number of posts in a topic
$forum_limit = 1000;

#Time limit for responding to email (in minutes)
$time_limit = 10;

##########################

$n = PHP_EOL;
$now = time();
$server_port = getenv('SERVER_PORT');
$port = $server_port === '80' || $server_port === '443' ? '' : ':'. $server_port;
$request_uri = getenv('REQUEST_URI');
$server = getenv('SERVER_NAME');
$dir = r(dirname(getenv('SCRIPT_NAME')));
$addslash = $dir !== '/' ? '/' : '';
$script = $dir. $addslash;
$scheme = is_ssl() ? 'https://' : 'http://';
$url = $scheme. $server. $port. $script;
$line_breaks = ["\r\n", "\n", "\r", '&#13;&#10;', '&#13;', '&#10;'];
$remote_addr = filter_var(getenv('REMOTE_ADDR'), FILTER_VALIDATE_IP);
$user_agent = h(getenv('HTTP_USER_AGENT'));
$user_agent_lang = h(getenv('HTTP_ACCEPT_LANGUAGE'));
$token = bin2hex(openssl_random_pseudo_bytes(16));
$glob_dir = 'contents/*/*/';
$tpl_dir = 'templates/'. $template. '/';
$css = $url. $tpl_dir. 'css/';
$js = $url. $tpl_dir. 'js/';
$glob_imgs ='/*.{[jJ][pP][gG],[pP][nN][gG],[gG][iI][fF],[sS][vV][gG],[jJ][pP][eE][gG],[mM][pP]4,[oO][gG][gG],[wW][eE][bB][mM]}';
$tmpdir = ini_get('upload_tmp_dir') ?? sys_get_temp_dir();
$mime = 'MIME-Version: 1.0'. $n. 'X-Date: '. date('c'). $n. 'X-Host: '. gethostbyaddr($remote_addr). $n. 'X-IP: '. $remote_addr. $n. 'X-Mailer: kinaga'. $n. 'X-UA: '. $user_agent. $n;
$delimiter = '-_-';

##########################

if (is_file($lang_file = __DIR__. '/languages/'. $lang. '.php')) include $lang_file;
