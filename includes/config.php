<?php

$template = 'default';

#.badge-*, .float-left/right, .border-left/right, .ml/r-*, .pl/r-*, .text-left/right
$bootstrap4 = false;

#See includes/social.php
$social_medias = ['facebook', 'twitter', 'hatena', 'line'];

#1 - 5
$index_type = 1;

#$index_type = 2 - 5
$index_items = 5;

##########################

$lang = 'ja';

$encoding = 'UTF-8';

##########################


#Use Auto wrap
$use_auto_wrap = true;

#Allow Comments
$use_comment = true;

#Use Contact
$use_contact = true;

#From: noreply@example.com
$use_noreply = false;

#Use Search
$use_search = true;

#Show Social buttons
$use_social = true;

#Use Permalink
$use_permalink = true;

#Show Informations in sidebox
$use_info = false;

#Show Recent articles in sidebox
$use_recents = true;

#Show Prev Next link in articles
$use_prevnext = true;

#Show Popular articles in sidebox
$use_popular_articles = true;

#Use Lazy Load
$use_datasrc = true;

#Show Thumbnails
$use_thumbnails = true;

#Show Thumbnails in categ
$use_categ_thumbnails = true;

#Show Similar articles in sidebox
$use_similars = true;

#Show Summary
$use_summary = true;

#Show approach menu in user profile
$use_user_approach = true;

#Show achievements in user profile
$use_user_achievements = true;

#Show Wikipedia summary from <dfn> tags
$use_wikipedia_popover = true;

#Forum
$use_forum = true;
#Guests can create threads and topics
$allow_guest_creates = true;

#Benchmark in footer
$use_benchmark = true;

##########################

#Category
$categ_nav_class = ' class="nav-item"';
$categ_nav_a_class = 'nav-link';
$categ_nav_active_class = ' active';

#Article
$article_wrapper_class = 'm-md-3';
$article_nav_wrapper_class = 'my-5';
$article_nav_next_href_class = 'float-md-end border-end border-5 pe-2';
$article_nav_prev_href_class = 'float-md-start border-start border-5 ps-2';
$article_nav_xaquo_class = 'd-none';
$article_nav_title_class = 'h6';
$article_nav_content_class = 'd-block mb-4 mx-3';
$article_images_wrapper_class = 'text-center py-3 my-3';

$pager_wrapper = 'justify-content-center my-3';

#Forum
$forum_wrapper_class = '';

#Sidebox
$sidebox_order = [
	0, #login
	3, #similar
	2, #social
	2, #permalink
	2, #recent
	2, #information
	3, #popular
	5, #comments
	1, #toc
	4, #address
	1, #category
	5 #forum
	];

$sidebox_wrapper_class = [
	'card',
	'border-0', #toc
	'border mb-3' #in article toc
	];
$sidebox_title_class = [
	'h5 m-3', #sidebox 1
	'h5 m-3', #sidebox 2
	'h5 m-3', #login success
	'h5 m-3 bg-danger p-3 text-white', #login error
	'h5 m-3', #toc
	'h4 m-3', #in article toc
	];
$sidebox_content_class = [
	'd-block p-3',
	'list-group-item-text wrap',
	'd-block p-3 wrap',
	'p-3 lead',
	'd-block p-2'
	];

$number_of_recents = 5;

$number_of_popular_articles = 5;

#Comments
$number_of_new_comments = 5;
$comment_length = 100;
$comment_wrapper_class = ['my-5', 'card card-body comment mb-3'];
$comment_class = '';
$comment_content_class = 'd-flex';
$comment_body_class = 'flex-grow-1';
$comment_user_class = 'd-flex justify-content-between mb-2';

#h1 title, subtitle
$h1_title = ['h3 my-3', 'd-block p-3 wrap text-muted'];

#categ/article title length
$title_length = 200;

#Top page: if $index_type = 1;
$default_sections_per_page = 6;
$index_class = 'card-columns index';
$index_wrapper_class = 'card mb-3 shadow-sm';
$index_content_class = 'card-body';
$index_title_class = 'h4 card-title mb-3';
$index_categ_link_class = 'blockquote-footer text-end';
$index_footer_class = 'card-footer bg-transparent';

#Category
$categ_sections_per_page = 6;
$categ_class = 'card-columns categ';
$categ_wrapper_class = 'card mb-3 shadow-sm';
$categ_content_class = 'card-body';
$categ_title_class = 'h4 card-title mb-3';
$categ_footer_class = 'card-footer bg-transparent';

#Search
$results_per_page = 6;
$results_wrapper_class = 'bg-light p-5 my-3';

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
$number_of_similars = 5;

#Approached users per page
$users_per_page = 4;

#Forum sidebox
$number_of_topics = 5;

#threads/topics per page
$forum_contents_per_page = 10;

#Limit the maximum number of posts in a topic
$forum_limit = 100;

#Time limit for responding to email (in minutes)
$time_limit = 10;

$article_separator = '---';
$delimiter = '-_-';

##########################

if ($file = __FILE__ === implode(get_included_files())) exit;
$n = PHP_EOL;
$now = time();
$server_port = getenv('SERVER_PORT');
$port = '80' === $server_port || '443' === $server_port ? '' : ':'. $server_port;
$request_uri = getenv('REQUEST_URI');
$server = getenv('SERVER_NAME');
$dir = r(dirname(getenv('SCRIPT_NAME')));
$script = $dir. ('/' !== $dir ? '/' : '');
$scheme = is_ssl() ? 'https://' : 'http://';
$url = $scheme. $server. $port. $script;
$current_url = explode('&', $scheme. $server. $port. $request_uri)[0];
$line_breaks = ["\r\n", "\n", "\r", '&#13;&#10;', '&#13;', '&#10;', '

'];
$remote_addr = filter_var(getenv('REMOTE_ADDR'), FILTER_VALIDATE_IP);
$user_agent = h(getenv('HTTP_USER_AGENT'));
$user_agent_lang = h(getenv('HTTP_ACCEPT_LANGUAGE'));
$token = bin2hex(openssl_random_pseudo_bytes(16));
$tpl_dir = 'templates/'. $template. '/';
$css = $url. $tpl_dir. 'css/';
$js = $url. $tpl_dir. 'js/';
$glob_imgs ='/*.{[jJ][pP][gG],[pP][nN][gG],[gG][iI][fF],[sS][vV][gG],[jJ][pP][eE][gG],[mM][pP]4,[oO][gG][gG],[wW][eE][bB][mM],[vV][tT][tT]}';
$tmpdir = ini_get('upload_tmp_dir') ?? sys_get_temp_dir();
$mime = 'MIME-Version: 1.0'. $n. 'X-Date: '. date('c'). $n. 'X-Host: '. gethostbyaddr($remote_addr). $n. 'X-IP: '. $remote_addr. $n. 'X-Mailer: kinaga'. $n. 'X-UA: '. $user_agent. $n;
$pngtext = 'comment';
$icon_image = '<svg class="align-top" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9c0 .013 0 .027.002.04V12l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094L15 9.499V3.5a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm4.502 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/></svg>';
if (is_file($lang_file = __DIR__. '/languages/'. $lang. '.php')) include $lang_file;
$from = $site_name. ' <'. ($use_noreply || !$mail_address ? 'noreply@'. $server : $mail_address). '>';
