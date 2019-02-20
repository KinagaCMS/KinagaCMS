<?php
setlocale(LC_ALL, 'en_US.UTF-8');
date_default_timezone_set('America/New_York');

#Site Name
$site_name = 'kinaga';

# Your mail address
$mail_address = '';

#Your home or office address
$address = '';
$address_title = '';

#Hue: 'Red', 'Pink', 'Brown', 'YellowGreen', 'Green', 'Blue', 'LightBlue',  'Purple',  'Gray', 'Black', or BLANK
$color = 'Red';


#Description: Top page
$meta_description = 'Description here.';

#Subtitle: Top Page H1 and TITLE
$subtitle = '';


#Top Page
$home = 'Home';


#Sideboxes
$recents = 'Latest Articles';

$category = 'Category';

$informations = 'Informations';

$recent_comments = 'Comments';

$popular_articles = 'Popular Articles';

$download_contents = 'Downloads';
$download_subtitle = '';
$download_notice = '';

$contact_us = 'Contact Us';
$contact_subtitle = '';
$contact_notice = 'We will never collect information about you without your explicit consent.';

$similar_title = 'Similar Articles';

$toc = 'Table of Contents';

$nav_laquo = '&laquo;';

$nav_raquo = '&raquo;';


$comments_prev = 'Newer';

$comments_next = 'Older';

$page_prefix = 'Page %s';

#Social icons
$social = 'Share this';

$permalink = 'Permalink';

$for_html = 'HTML';

$for_wiki = 'Wiki';

$for_forum = 'Forum';


#Separator
$top = '<a class="page-top text-right d-block p-0 small" href="#TOP"> </a>';
$pagetop = 'Go to Top';

$last_modified = 'Last updated: %s';

$no_article = 'Article not found.';

$no_categ = 'Category does not exist.';

$error = 'An error occurred';

$not_found = 'The page you requested was not found.';

$more_link_text = 'Read More...';

$ellipsis = '...';

$views = '%s Views';

$images_count_title = ' (%s images)';

$source = 'Source: %s';

$result = 'Search results for %s.';

$no_results_found = 'No results.';

$comments_not_allow = 'Comments are closed.';

$comments_count_title = ' (%s comments)';

$comment_title = 'Comments';

$comment_notice = $contact_notice . '';

$comment_counts = '%s comments';

$contact_caution = 'Only approved comments will be posted.';

#email separator line
$separator = '_______________________________________________';

$comment_acceptance =

	'To post this comment,' . $n .
	'save the attached file: %s' . $n .
	'and upload it in the following folder' . $n . $n .
	'/contents/%s/%s/comments/';

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

$comment_subject = '%s category %s article has comment - ';

$contact_success = 'Your message has been successfully sent.';

$contact_error = 'Sending failed.';

$time_format = 'F jS, Y h:i';

$present_format = 'F jS';

$seconds_ago = '%s seconds ago';

$minutes_ago = '%s minutes ago';

$hours_ago = '%s hours ago';

$days_ago = '%s days ago';

$benchmark_results = '<span class="d-block text-muted text-center small">Total time: %s sec. Memory: %s</span>';


#/images/index.php
$images_title = 'Images - %s';

$images_heading = 'Images <small class="text-muted ml-2">Copy the tag and paste your article.</small>';

$images_aligner = 'Image Alignment <small class="text-muted ml-2">You might need &lt;div class=clearfix&gt;&lt;/div&gt; to release wrap.</small>';

$noscript = 'Please enable <strong>Javascript</strong>.';

$align_left = 'Left';

$align_center = 'Center';

$align_right = 'Right';

#Number of images per page
$number_of_imgs = 3;

$large_image = 'Large';

$small_image = 'Small';

$imgs_first= 'First';
$imgs_prev = 'Prev';

$imgs_next = 'Next';
$imgs_last = 'Last';

function hsla($colour, $cal_s=0, $cal_l=0, $a=1)
{
	if ($colour === 'Red')
	{
		$h = 355;
		$s = 70;
		$l = 50;
	}
		elseif ($colour === 'Pink')
	{
		$h = 330;
		$s = 70;
		$l = 70;
	}
	elseif ($colour === 'Brown')
	{
		$h = 40;
		$s = 40;
		$l = 35;
	}
	elseif ($colour === 'YellowGreen')
	{
		$h = 80;
		$s = 60;
		$l = 50;
	}
	elseif ($colour === 'Green')
	{
		$h = 120;
		$s = 60;
		$l = 40;
	}
	elseif ($colour === 'Blue')
	{
		$h = 220;
		$s = 60;
		$l = 60;
	}
		elseif ($colour === 'LightBlue')
	{
		$h = 195;
		$s = 60;
		$l = 60;
	}
	elseif ($colour === 'Purple')
	{
		$h = 250;
		$s = 60;
		$l = 70;
	}
	elseif ($colour === 'Gray')
	{
		$h = 200;
		$s = 5;
		$l = 60;
	}
	elseif ($colour === 'Black')
	{
		$h = 0;
		$s = 0;
		$l = 10;
	}
	else list($h, $s, $l) = get_hsl($colour);
	if (isset($h, $s, $l))
		return 'hsla('. $h. ', '. ($s + (int)$cal_s). '%, '. ($l + (int)$cal_l). '%, '. $a. ')';
}

function color2class($colour)
{
	if ($colour === 'Gray')
		return 'secondary';
	elseif ($colour === 'Black' )
		return 'dark';
	elseif ($colour === 'Brown')
		return 'muted';
	elseif ($colour === 'YellowGreen' || $colour === 'Green')
		return 'success';
	elseif ($colour === 'Pink')
		return 'warning';
	elseif ($colour === 'Red' || $colour === 'Purple')
		return 'danger';
	elseif ($colour === 'Blue' || $colour === 'LightBlue')
		return 'info';
	else
		return 'primary';
}
