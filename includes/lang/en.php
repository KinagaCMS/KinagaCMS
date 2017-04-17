<?php
/*
  * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
  * @license    GPL 3.0 or later; see LICENSE file for details.
  */

date_default_timezone_set( 'America/New_York' );

#Site Name
$site_name = 'kinaga';

#Your home or office address: Sidebox and Mail footer
$address = '';

#Hue: red, orange, yellow, liteGreen, green, liteBlue, blue, darkBlue, purple, peach, brown or BLANK
$color = 'green';


#Description: Top page
$meta_description = 'description goes here.';

#Subtitle: Top Page H1 and TITLE
$subtitle = 'Your install is compleate';


#Top Page
$home = 'Home';


#Sideboxes
$recents = 'Latest Articles';

$informations = 'Informations';

$recent_comments = 'Comments';

$popular_articles = 'Popular Articles';

$download_contents = 'Downloads';

$contact_us = 'Contact Us';


$similar_title = 'Similar Articles';


$nav_laquo = '&laquo;';

$nav_raquo = '&raquo;';


$comments_prev = 'Newer';

$comments_next = 'Older';


#Contact Us
$contact_subtitle = 'Want to say hello, ask a question. Use the form below.';


$download_subtitle = 'Click the links below.';


$page_prefix = 'Page %s';


$permalink = 'Permalink <small>Copy the link and add to your website.</small>';

$for_html = 'HTML';

$for_wiki = 'Wiki';

$for_forum = 'Forum';


#Separator
$top = '<a class="text-primary page-top" href="#TOP"><span class="glyphicon glyphicon-chevron-up"></span></a>';


$last_modified = 'Last updated: %s';

$no_article = 'Article not found.';

$no_categ = 'Category does not exist.';

$error = 'An error occurred';

$not_found = 'The page you requested was not found.';

$more_link_text = 'Read More...';

$ellipsis = '...';

$display_counts = '%s Views';

$view = '%s Views';

$images_count_title = ' (%s images)';

$source = 'Source: %s';

$result = 'Search results for %s.';

$no_results_found = 'No results.';

$comments_not_allow = 'Comments are closed.';

$comments_count_title = ' (%s comments)';

$comment_title = 'Comments';

$comment_counts = '%s comments';

$contact_caution = 'Only approved comments will be posted.';

#email separator line
$separator = '_______________________________________________';

$comment_acceptance =

	'To post this comment,' . $n .
	'save the attached file: %s' . $n .
	'and upload it in the following folder' . $n . $n .
	$s.'contents'.$s.'%s'.$s.'%s'.$s.'comments'.$s;

$contact_name = 'Name';

$placeholder_name = 'Your name';

$contact_mail = 'email';

$placeholder_mail = 'Your email address';

$contact_message = 'Message';

$placeholder_message = 'Your message';

$contact_preview = 'Confirm';

$contact_cancel = 'Cancel';

$contact_send = 'Send';

$cookie_disabled_error = 'Please enable cookies.';

$contact_subject_suffix = 'Inquiries from %s - ';

$comment_subject = 'Comment on %s of %s - ';

$contact_success = 'Your message has been successfully sent.';

$contact_error = 'Sending failed.';

$time_format = 'F jS, Y h:i';

$present_format = 'F jS';

$seconds_ago = ' seconds ago';

$minutes_ago = ' minutes ago';

$hours_ago = ' hours ago';

$days_ago = ' days ago';


#/images/index.php
$images_title = 'Images - %s';

$images_heading = 'Images <small>Copy the tag and paste your article.</small>';

$images_aligner = 'Image Alignment <small>You might need &lt;div class=clearfix&gt;&lt;/div&gt; to release wrap.</small>';

$noscript = 'Please enable <strong>javascript</strong>.';

$align_left = 'Left';

$align_center = 'Center';

$align_right = 'Right';

#Number of images per page
$number_of_imgs = '3';

$large_image = 'Large';

$small_image = 'Small';

$imgs_prev = 'Prev';

$imgs_next = 'Next';


function hsla( $h, $s = 100, $l = 50, $a = 1 ) {

	$hue = array(
		'red'           => '0',
		'orange'     => '35',
		'yellow'       => '50',
		'liteGreen' => '65',
		'green'       => '85',
		'liteBlue'    => '170',
		'blue'          => '195',
		'darkBlue'  => '220',
		'purple'      => '265',
		'peach'       => '330',
		'brown'       => '25'
	);

	if ( isset( $hue[$h] ) ) return "hsla( $hue[$h], $s%, $l%, $a )";

}


function color2class( $colour ) {

	switch ( true ) {

		case $colour == 'green' || $colour == 'liteGreen': return 'success';

		case $colour == 'orange' || $colour == 'yellow' || $colour == 'brown': return 'warning';

		case $colour == 'red' || $colour == 'purple' || $colour == 'peach': return 'danger';

		default: return 'info';
	}

}

