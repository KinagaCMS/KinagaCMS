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

#Hue: Red, Rose, Orange, Topaz, SpringGreen, MossGreen, MintGreen, MillennialGreen, Turquoise, LapisLazuli, MidnightBlue, OrientalBlue, Violet, Grape, Chocolate, Coffee, White, Moonlight, WistariaWhite, Gold, LimeWhite, Gray, GreenGray, PinkGray, SandGray, Black or BLANK
$color = 'Moonlight';


#Description: Top page
$meta_description = 'description goes here.';

#Subtitle: Top Page H1 and TITLE
$subtitle = '';


#Top Page
$home = 'Home';


#Sideboxes
$recents = 'Latest Articles';

$informations = 'Informations';

$recent_comments = 'Comments';

$popular_articles = 'Popular Articles';

$download_contents = 'Downloads';
$download_subtitle = '';
$download_notice = '';

$contact_us = 'Contact Us';
$contact_subtitle = '';
$contact_notice = '';

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
$pagetop = 'go to top';

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

$comment_subject = 'Comment on %s of %s - ';

$contact_success = 'Your message has been successfully sent.';

$contact_error = 'Sending failed.';

$time_format = 'F jS, Y h:i';

$present_format = 'F jS';

$seconds_ago = ' seconds ago';

$minutes_ago = ' minutes ago';

$hours_ago = ' hours ago';

$days_ago = ' days ago';

$benchmark_results = '<span class="d-block text-muted text-center small">Total time: %s sec. Memory: %s</span>';


#/images/index.php
$images_title = 'Images - %s';

$images_heading = 'Images <small class=text-muted>Copy the tag and paste your article.</small>';

$images_aligner = 'Image Alignment <small class=text-muted>You might need &lt;div class=clearfix&gt;&lt;/div&gt; to release wrap.</small>';

$noscript = 'Please enable <strong>javascript</strong>.';

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

function hsla($h, $cal_s=0, $cal_l=0, $a=1)
{
	if ($h === 'Red')
	{
		$hue = 355;
		$s = 65;
		$l = 40;
	}
	elseif ($h === 'Rose')
	{
		$hue = 330;
		$s = 75;
		$l = 40;
	}
	elseif ($h === 'Orange')
	{
		$hue = 30;
		$s = 98;
		$l = 42;
	}
	elseif ($h === 'Topaz')
	{
		$hue = 48;
		$s = 86;
		$l = 40;
	}
	elseif ($h === 'SpringGreen')
	{
		$hue = 80;
		$s = 75;
		$l = 40;
	}
	elseif ($h === 'MossGreen')
	{
		$hue = 70;
		$s = 65;
		$l = 30;
	}
	elseif ($h === 'MintGreen')
	{
		$hue = 131;
		$s = 45;
		$l = 40;
	}
	elseif ($h === 'MillennialGreen')
	{
		$hue = 142;
		$s = 36;
		$l = 30;
	}
	elseif ($h === 'Turquoise')
	{
		$hue = 190;
		$s = 60;
		$l = 37;
	}
	elseif ($h === 'LapisLazuli')
	{
		$hue = 214;
		$s = 69;
		$l = 38;
	}
	elseif ($h === 'MidnightBlue')
	{
		$hue = 222;
		$s = 65;
		$l = 10;
	}
	elseif ($h === 'OrientalBlue')
	{
		$hue = 232;
		$s = 39;
		$l = 49;
	}
	elseif ($h === 'Violet')
	{
		$hue = 259;
		$s = 40;
		$l = 40;
	}
	elseif ($h === 'Grape')
	{
		$hue = 290;
		$s = 40;
		$l = 38;
	}
	elseif ($h === 'Chocolate')
	{
		$hue = 16;
		$s = 28;
		$l = 34;
	}
	elseif ($h === 'Coffee')
	{
		$hue = 39;
		$s = 56;
		$l = 30;
	}
	elseif ($h === 'White')
	{
		$hue = 0;
		$s = 0;
		$l = 42;
	}
	elseif ($h === 'Moonlight')
	{
		$hue = 200;
		$s = 18;
		$l = 42;
	}
	elseif ($h === 'WistariaWhite')
	{
		$hue = 270;
		$s = 18;
		$l = 42;
	}
	elseif ($h === 'Gold')
	{
		$hue = 53;
		$s = 45;
		$l = 42;
	}
	elseif ($h === 'LimeWhite')
	{
		$hue = 75;
		$s = 45;
		$l = 42;
	}
	elseif ($h === 'Gray')
	{
		$hue = 0;
		$s = 0;
		$l = 40;
	}
	elseif ($h === 'GreenGray')
	{
		$hue = 131;
		$s = 10;
		$l = 40;
	}
	elseif ($h === 'PinkGray')
	{
		$hue = 320;
		$s = 10;
		$l = 40;
	}
	elseif ($h === 'SandGray')
	{
		$hue = 50;
		$s = 10;
		$l = 40;
	}
	elseif ($h === 'Black')
	{
		$hue = 0;
		$s = 0;
		$l = 0;
	}
	if (isset($hue, $s, $l))
		return 'hsla(' . $hue . ', ' . ($s + (int)$cal_s) . '%, ' . ($l + (int)$cal_l) . '%, ' . $a . ')';
}

function color2class($colour)
{
	if ($colour === 'White' || $colour === 'Moonlight' || $colour === 'WistariaWhite' || $colour === 'Gold' || $colour === 'LimeWhite' )
		return 'white';
	elseif ($colour === 'Gray' || $colour === 'GreenGray' || $colour === 'PinkGray' || $colour === 'SandGray')
		return 'secondary';
	elseif ($colour === 'Black' || $colour === 'MidnightBlue')
		return 'dark';
	elseif ($colour === 'Chocolate' || $colour === 'Coffee' || $colour === 'MillennialGreen')
		return 'muted';
	elseif ($colour === 'MintGreen' || $colour === 'MossGreen' || $colour === 'SpringGreen')
		return 'success';
	elseif ($colour === 'Orange' || $colour === 'Topaz' )
		return 'warning';
	elseif ($colour === 'Red' || $colour === 'Rose' || $colour === 'Grape' || $colour === 'Violet')
		return 'danger';
	elseif ($colour === 'Turquoise' || $colour === 'LapisLazuli' || $colour === 'OrientalBlue')
		return 'info';
	else
		return 'primary';
}
