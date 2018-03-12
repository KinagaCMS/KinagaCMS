<?php
date_default_timezone_set( 'America/New_York' );

#Site Name
$site_name = 'kinaga';

# Your mail address
$mail_address = '';

#Your home or office address
$address = '';
$address_title = '';

#Hue: Red, Rose, Pink, DarkPink, Coral, Carrot, Orange, Topaz, DarkYellow, SpringGreen, MossGreen, MintGreen, DarkGreen, Turquoise, LapisLazuli, MidnightBlue, OrientalBlue, Violet, Grape, Sepia, RawUmber, Khaki, Coffee, White, MoonWhite, WistariaWhite, Gold, LimeWhite, Gray, GrayGreen, PinkGray, SandGray, Onyx, Black or BLANK
$color = 'SandGray';


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


function hsla($h, $s=100, $l=50, $a=1)
{
	if (is_numeric($h))
		return "hsla($h, $s%, $l%, $a)";
	else
	{
		$hue = array(
			'Red' => 354,
			'Rose' => 351,
			'Pink' => 333,
			'DarkPink' => 340,
			'Coral' => 9,
			'Carrot' => 26,
			'Orange' => 30,
			'Topaz' => 48,
			'DarkYellow' => 41,
			'SpringGreen' => 85,
			'MossGreen' => 75,
			'MintGreen' => 131,
			'DarkGreen' => 142,
			'Turquoise' => 190,
			'LapisLazuli' => 217,
			'MidnightBlue' => 222,
			'OrientalBlue' => 232,
			'Violet' => 259,
			'Grape' => 283,
			'Sepia' => 16,
			'RawUmber' => 39,
			'Khaki' => 45,
			'Coffee' => 39,
			'White' => 0,
			'MoonWhite' => 206,
			'WistariaWhite' => 270,
			'Gold' => 53,
			'LimeWhite' => 75,
			'Gray' => 0,
			'GrayGreen' => 131,
			'PinkGray' => 330,
			'SandGray' => 50,
			'Onyx' => 210,
			'Black' => 0
		);
		if ($h === 'Red')
		{
			$s = 66;
			$l = 44;
		}
		elseif ($h === 'Rose')
		{
			$s = 77;
			$l = 62;
		}
		elseif ($h === 'Pink')
		{
			$s = 77;
			$l = 62;
		}
		elseif ($h === 'DarkPink')
		{
			$s = 52;
			$l = 55;
		}
		if ($h === 'Coral')
		{
			$s = 79;
			$l = 68;
		}
		if ($h === 'Carrot')
		{
			$s = 100;
			$l = 40;
		}
		if ($h === 'Orange')
		{
			$s = 100;
			$l = 47;
		}
		if ($h === 'Topaz')
		{
			$s = 86;
			$l = 43;
		}
		if ($h === 'DarkYellow')
		{
			$s = 60;
			$l = 49;
		}
		if ($h === 'SpringGreen')
		{
			$s = 43;
			$l = 48;
		}
		if ($h === 'MossGreen')
		{
			$s = 66;
			$l = 31;
		}
		if ($h === 'MintGreen')
		{
			$s = 31;
			$l = 55;
		}
		elseif ($h === 'DarkGreen')
		{
			$s = 36;
			$l = 30;
		}
		elseif ($h === 'Turquoise')
		{
			$s = 40;
			$l = 45;
		}
		elseif ($h === 'LapisLazuli')
		{
			$s = 69;
			$l = 38;
		}
		elseif ($h === 'MidnightBlue')
		{
			$s = 68;
			$l = 19;
		}
		if ($h === 'OrientalBlue')
		{
			$s = 39;
			$l = 49;
		}
		if ($h === 'Violet')
		{
			$s = 30;
			$l = 49;
		}
		elseif ($h === 'Grape')
		{
			$s = 34;
			$l = 28;
		}
		elseif ($h === 'Sepia')
		{
			$s = 28;
			$l = 34;
		}
		elseif ($h === 'RawUmber')
		{
			$s = 57;
			$l = 37;
		}
		elseif ($h === 'Khaki')
		{
			$s = 27;
			$l = 52;
		}
		elseif ($h === 'Coffee')
		{
			$s = 56;
			$l = 26;
		}
		elseif ($h === 'White')
		{
			$s = 0;
			$l = 60;
		}
		elseif ($h === 'MoonWhite')
		{
			$s = 12;
			$l = 55;
		}
		elseif ($h === 'WistariaWhite')
		{
			$s = 16;
			$l = 55;
		}
		elseif ($h === 'Gold')
		{
			$s = 95;
			$l = 33;
		}
		elseif ($h === 'LimeWhite')
		{
			$s = 69;
			$l = 30;
		}
		elseif ($h === 'Gray')
		{
			$s = 0;
			$l = 55;
		}
		elseif ($h === 'GrayGreen')
		{
			$s = 7;
			$l = 45;
		}
		elseif ($h === 'PinkGray')
		{
			$s = 3;
			$l = 52;
		}
		elseif ($h === 'SandGray')
		{
			$s = 13;
			$l = 40;
		}
		elseif ($h === 'Onyx')
		{
			$s = 4;
			$l = 29;
		}
		elseif ($h === 'Black')
		{
			$s = 0;
			$l = 0;
		}
		if (isset($hue[$h], $s, $l))
			return "hsla($hue[$h], $s%, $l%, $a)";
	}
}


function color2class($colour)
{
	if ($colour === 'White' || $colour === 'MoonWhite' || $colour === 'WistariaWhite' || $colour === 'Gold' || $colour === 'LimeWhite' )
		return 'white';
	elseif ($colour === 'Gray' || $colour === 'GrayGreen' || $colour === 'PinkGray' || $colour === 'SandGray')
		return 'secondary';
	elseif ($colour === 'Black' || $colour === 'Onyx')
		return 'dark';
	elseif ($colour === 'DarkGreen' || $colour === 'MintGreen' || $colour === 'MossGreen' || $colour === 'SpringGreen')
		return 'success';
	elseif ($colour === 'Carrot' || $colour === 'Orange' || $colour === 'Topaz' || $colour === 'DarkYellow' || $colour === 'Sepia'|| $colour === 'RawUmber' || $colour === 'Khaki' || $colour === 'Coffee')
		return 'warning';
	elseif ($colour === 'Red' || $colour === 'Rose' || $colour === 'Pink' || $colour === 'DarkPink' || $colour === 'Coral' || $colour === 'Violet' || $colour === 'Grape')
		return 'danger';
	else
		return'primary';
}
