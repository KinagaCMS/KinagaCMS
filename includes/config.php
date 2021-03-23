<?php

$template = 'default';

#See includes/social.php
$social_medias = ['facebook', 'twitter', 'hatena', 'line'];

#if (!is_file(contents/index.html)), set 1 to 5
$index_type = 1;
#$index_type = 2 - 5, set the number of links in each category
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

#Show Recent articles
$use_recents = true;

#Show Prev Next link in articles
$use_prevnext = true;

#Show Popular articles
$use_popular_articles = true;

#Use Lazy Load
$use_datasrc = true;

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
$categ_nav_class = ' class="list-inline-item"';

#Article
$article_wrapper_class = 'my-3 p-5';
$article_nav_wrapper_class = 'my-3 p-4';
$article_nav_next_href_class = '';
$article_nav_prev_href_class = '';
$article_nav_xaquo_class = 'd-none';
$article_nav_title_class = 'h6';
$article_nav_content_class = 'd-block mb-4 mx-3';
$article_images_wrapper_class = 'bg-light text-center py-3 my-3';

$pager_wrapper = 'bg-light justify-content-center p-5 my-3';

#Forum
$forum_wrapper_class = '';

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
	10 #forum
	];

$sidebox_wrapper_class = [
	'px-4 py-3 px-lg-5 py-lg-4 mx-0',
	'border-0', #inner toc
	'mb-3' #toc
	];
$sidebox_title_class = [
	'h5 mb-3 text-center', #sidebox 1
	'h5 mb-3 text-center', #sidebox 2
	'h5 mb-3 text-center', # login success
	'h5 mb-3 text-center bg-danger p-3 text-white', #login error
	'h2 navbar navbar-dark', #toc
	];
$sidebox_content_class = [
	'd-block p-3 border-0',
	'list-group-item-text wrap',
	'd-block p-3 border-0 wrap',
	'd-block p-3 border-0',
	'd-block p-2 border-0'
	];

$number_of_recents = 5;

$number_of_popular_articles = 5;

#Comments
$number_of_new_comments = 5;
$comment_length = 100;
$comment_wrapper_class = ['my-5', 'card card-body comment mb-3'];
$comment_class = '';
$comment_content_class = 'media';
$comment_body_class = 'media-body';
$comment_user_class = 'd-flex justify-content-between mb-2';

#h1 title, subtitle
$h1_title = ['h3 my-3', 'd-block p-3 wrap text-muted'];

#Top page: if $index_type = 1;
$default_sections_per_page = 6;
$index_class = 'card-columns index';
$index_wrapper_class = 'card';
$index_content_class = 'card-body';
$index_title_class = 'h4 card-title mb-3';
$index_categ_link_class = 'blockquote-footer text-right';
$index_footer_class = 'card-footer bg-transparent';

#Category
$categ_sections_per_page = 6;
$categ_class = 'card-columns categ';
$categ_wrapper_class = 'card';
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
$line_breaks = ["\r\n", "\n", "\r", '&#13;&#10;', '&#13;', '&#10;'];
$remote_addr = filter_var(getenv('REMOTE_ADDR'), FILTER_VALIDATE_IP);
$user_agent = h(getenv('HTTP_USER_AGENT'));
$user_agent_lang = h(getenv('HTTP_ACCEPT_LANGUAGE'));
$token = bin2hex(openssl_random_pseudo_bytes(16));
$tpl_dir = 'templates/'. $template. '/';
$css = $url. $tpl_dir. 'css/';
$js = $url. $tpl_dir. 'js/';
$glob_imgs ='/*.{[jJ][pP][gG],[pP][nN][gG],[gG][iI][fF],[sS][vV][gG],[jJ][pP][eE][gG],[mM][pP]4,[oO][gG][gG],[wW][eE][bB][mM]}';
$tmpdir = ini_get('upload_tmp_dir') ?? sys_get_temp_dir();
$mime = 'MIME-Version: 1.0'. $n. 'X-Date: '. date('c'). $n. 'X-Host: '. gethostbyaddr($remote_addr). $n. 'X-IP: '. $remote_addr. $n. 'X-Mailer: kinaga'. $n. 'X-UA: '. $user_agent. $n;
$pngtext = 'comment';
$icon_image = '<svg class="align-top" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9c0 .013 0 .027.002.04V12l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094L15 9.499V3.5a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm4.502 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/></svg>';
if (is_file($lang_file = __DIR__. '/languages/'. $lang. '.php')) include $lang_file;
$from = $site_name. ' <'. ($use_noreply || !$mail_address ? 'noreply@'. $server : $mail_address). '>';
$blacklist_alert =
'<div class="modal fade" id=blacklist-alert>'. $n.
'<div class="modal-dialog modal-dialog-centered">'. $n.
'<div class=modal-content><div class="modal-header"><h5 class="border-0 text-black-50">'. $user_not_found_title[1]. '</h5>'. $n.
'<button type=button class=close data-dismiss=modal tabindex=-1><span aria-hidden=true>&times;</span></button></div>'. $n.
'<div class="modal-body text-center">'. $​ask_admin. '</div>'. $n.
'</div>'. $n.
'</div>'. $n.
'</div>';
