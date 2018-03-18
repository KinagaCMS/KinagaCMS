<?php
/*
 * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
 * @license    GPL 3.0 or later; see LICENSE file for details.
 * @author     Translate by Goragod Wiriya http://kotchasan.com
 */

date_default_timezone_set('Asia/Bangkok');

// Site Name
$site_name = 'kinaga';

// Your mail address
$mail_address = '';

// Your home or office address
$address = '';
$address_title = '';

// Hue: red, orange, yellow, liteGreen, green, liteBlue, blue, darkBlue, purple, peach, brown or BLANK
$color = 'green';

// Description: Top page
$meta_description = 'คำอธิบายเกี่ยวกับไซต์';

// Subtitle: Top Page H1 and TITLE
$subtitle = 'ติดตั้งเรียบร้อย';

// Top Page
$home = 'หน้าหลัก';

// Sideboxes
$recents = 'บทความล่าสุด';

$informations = 'ข้อมูลการติดต่อ';

$recent_comments = 'ความคิดเห็น';

$popular_articles = 'บทความยอดนิยม';

$download_contents = 'ดาวน์โหลด';

$contact_us = 'ติดต่อเรา';

$similar_title = 'บทความคล้ายๆกัน';

$nav_laquo = '&laquo;';

$nav_raquo = '&raquo;';

$comments_prev = 'ใหม่';

$comments_next = 'เก่า';

// Contact Us
$contact_subtitle = 'พูดคุย ติดต่อสอบถาม ติดต่อเรา';

$download_subtitle = 'คลิกลิงค์ด้านล่าง';

$page_prefix = 'หน้าที่ %s';

$social = 'หุ้น';

$permalink = 'ลิงค์ถาวร';

$for_html = 'HTML';

$for_wiki = 'Wiki';

$for_forum = 'Forum';

// Separator
$top = '<a class="text-primary page-top" href="#TOP"><span class="glyphicon glyphicon-chevron-up"></span></a>';

$last_modified = 'อัปเดท: %s';

$no_article = 'ไม่พบบทความที่เลือก';

$no_categ = 'ไม่พบหมวดหมู่ที่ต้องการ';

$error = 'มีข้อผิดพลาด';

$not_found = 'ไม่พบหน้าที่ต้องการ';

$more_link_text = 'อ่านต่อ...';

$ellipsis = '...';

$display_counts = 'เปิดดู %s';

$view = 'ดู %s';

$images_count_title = ' (%s รูปภาพ)';

$source = 'Source: %s';

$result = 'ผลลัพท์การค้นหา <b>%s</b>';

$no_results_found = 'ไม่มีผลลัพท์';

$comments_not_allow = 'ปิดการแสดงความคิดเห็น';

$comments_count_title = ' (%s ความคิดเห็น)';

$comment_title = 'ความคิดเห็น';

$comment_counts = '%s ความคิดเห็น';

$contact_caution = 'ส่งข้อความไปตรวจสอบยังผู้ดูแลเรียบร้อย';

// email separator line
$separator = '_______________________________________________';

$comment_acceptance = 'To post this comment,'.$n.
  'save the attached file: %s'.$n.
  'and upload it in the following folder'.$n.$n.
  '/contents/%s/%s/comments/';

$contact_name = 'ชื่อ';

$placeholder_name = 'ชื่อของคุณ';

$contact_mail = 'อีเมล์';

$placeholder_mail = 'ที่อยู่อีเมล์ของคุณ';

$contact_message = 'ข้อความ';

$placeholder_message = 'ข้อความของคุณ';

$contact_preview = 'ตัวอย่างก่อนส่ง';

$contact_cancel = 'กลับไปแก้ไข';

$contact_send = 'ส่ง';

$cookie_disabled_error = 'กรุณาเปิดใช้งานคุกกี้';

$contact_subject_suffix = 'Inquiries from %s - ';

$comment_subject = 'Comment on %s of %s - ';

$contact_success = 'ข้อความถูกส่งเรียบร้อยแล้ว';

$contact_error = 'ไม่สามารถส่งได้';

$time_format = 'F jS, Y h:i';

$present_format = 'F jS';

$seconds_ago = ' วินาทีที่แล้ว';

$minutes_ago = ' ไม่กี่นาทีผ่านมา';

$hours_ago = ' ชั่วโมงที่แล้ว';

$days_ago = ' วันก่อน';


// /images/index.php
$images_title = 'รูปภาพ - %s';

$images_heading = 'รูปภาพ <small class=text-muted>สำเนา tag ของรูปภาพไปวางในบทความ</small>';

$images_aligner = 'จัดเรียงรูปภาพ <small class=text-muted>คุณสามารถใส่ &lt;div class=clearfix&gt;&lt;/div&gt; เพื่อบังคับให้รูปภาพขึ้นบรรทัดใหม่ได้</small>';

$noscript = 'กรุณาเปิดการใช้งาน <strong>javascript</strong>.';

$align_left = 'ซ้าย';

$align_center = 'กึ่งกลาง';

$align_right = 'ขวา';

// Number of images per page
$number_of_imgs = '3';

$large_image = 'ใหญ่';

$small_image = 'เล็ก';

$imgs_first= 'เป็นครั้งแรก';
$imgs_prev = 'ก่อนหน้า';

$imgs_next = 'ถัดไป';
$imgs_last = 'ในที่สุด';


function hsla($h, $cal_s=0, $cal_l=0, $a=1)
{
	if ($h === 'Red')
	{
		$hue = 354;
		$s = 66;
		$l = 44;
	}
	elseif ($h === 'Rose')
	{
		$hue = 351;
		$s = 77;
		$l = 62;
	}
	elseif ($h === 'Pink')
	{
		$hue = 333;
		$s = 77;
		$l = 62;
	}
	elseif ($h === 'DarkPink')
	{
		$hue = 340;
		$s = 52;
		$l = 55;
	}
	if ($h === 'Coral')
	{
		$hue = 9;
		$s = 79;
		$l = 68;
	}
	if ($h === 'Carrot')
	{
		$hue = 26;
		$s = 100;
		$l = 40;
	}
	if ($h === 'Orange')
	{
		$hue = 30;
		$s = 100;
		$l = 47;
	}
	if ($h === 'Topaz')
	{
		$hue = 48;
		$s = 86;
		$l = 40;
	}
	if ($h === 'DarkYellow')
	{
		$hue = 41;
		$s = 60;
		$l = 49;
	}
	if ($h === 'SpringGreen')
	{
		$hue = 85;
		$s = 43;
		$l = 48;
	}
	if ($h === 'MossGreen')
	{
		$hue = 75;
		$s = 66;
		$l = 31;
	}
	if ($h === 'MintGreen')
	{
		$hue = 131;
		$s = 31;
		$l = 55;
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
		$s = 40;
		$l = 45;
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
		$s = 68;
		$l = 19;
	}
	if ($h === 'OrientalBlue')
	{
		$hue = 232;
		$s = 39;
		$l = 49;
	}
	if ($h === 'Violet')
	{
		$hue = 259;
		$s = 30;
		$l = 49;
	}
	elseif ($h === 'Grape')
	{
		$hue = 283;
		$s = 34;
		$l = 28;
	}
	elseif ($h === 'Chocolate')
	{
		$hue = 16;
		$s = 28;
		$l = 34;
	}
	elseif ($h === 'Soil')
	{
		$hue = 39;
		$s = 57;
		$l = 37;
	}
	elseif ($h === 'Khaki')
	{
		$hue = 45;
		$s = 27;
		$l = 52;
	}
	elseif ($h === 'Coffee')
	{
		$hue = 39;
		$s = 56;
		$l = 26;
	}
	elseif ($h === 'White')
	{
		$hue = 0;
		$s = 0;
		$l = 60;
	}
	elseif ($h === 'Moonlight')
	{
		$hue = 206;
		$s = 12;
		$l = 55;
	}
	elseif ($h === 'WistariaWhite')
	{
		$hue = 270;
		$s = 16;
		$l = 55;
	}
	elseif ($h === 'Gold')
	{
		$hue = 53;
		$s = 95;
		$l = 33;
	}
	elseif ($h === 'LimeWhite')
	{
		$hue = 75;
		$s = 69;
		$l = 37;
	}
	elseif ($h === 'Gray')
	{
		$hue = 0;
		$s = 0;
		$l = 45;
	}
	elseif ($h === 'GreenGray')
	{
		$hue = 131;
		$s = 7;
		$l = 45;
	}
	elseif ($h === 'PinkGray')
	{
		$hue = 330;
		$s = 3;
		$l = 52;
	}
	elseif ($h === 'SandGray')
	{
		$hue = 50;
		$s = 13;
		$l = 40;
	}
	elseif ($h === 'DarkGray')
	{
		$hue = 210;
		$s = 4;
		$l = 29;
	}
	elseif ($h === '')
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
	if ($colour === 'White' || $colour === 'Moonlight' || $colour === 'WistariaWhite'|| $colour === 'Gold' || $colour === 'LimeWhite' )
		return 'white';
	elseif ($colour === 'Gray' || $colour === 'GreenGray' || $colour === 'PinkGray' || $colour === 'SandGray')
		return 'secondary';
	elseif ($colour === 'Black' || $colour === 'DarkGray')
		return 'dark';
	elseif ($colour === 'MillennialGreen' || $colour === 'MintGreen' || $colour === 'MossGreen' || $colour === 'SpringGreen')
		return 'success';
	elseif ($colour === 'Carrot' || $colour === 'Orange' || $colour === 'Topaz' || $colour === 'DarkYellow' || $colour === 'Chocolate'|| $colour === 'Soil' || $colour === 'Khaki' || $colour === 'Coffee')
		return 'warning';
	elseif ($colour === 'Red' || $colour === 'Rose' || $colour === 'Pink' || $colour === 'DarkPink' || $colour === 'Coral' || $colour === 'Violet' || $colour === 'Grape')
		return 'danger';
	else
		return'primary';
}
