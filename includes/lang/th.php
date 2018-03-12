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


function hsla($h, $s=100, $l=50, $a=1)
{
	$hue = array(
		'white' => 0,
		'black' => 0,
		'gray' => 0,
		'red' => 0,
		'orange' => 20,
		'yellow' => 53,
		'liteGreen' => 70,
		'green' => 85,
		'liteBlue' => 170,
		'blue' => 195,
		'darkBlue' => 220,
		'purple' => 265,
		'peach' => 330,
		'brown' => 25
	);

	if (isset($hue[$h]))
	{
		if ($h === 'green' || $h === 'liteGreen')
			$s = 45;
		if ($h === 'yellow')
		{
			$s = 95;
			$l = 43;
		}
		if ($h === 'liteBlue')
			$s = 65;
		if ($h === 'darkBlue')
		{
			$s = 65;
			$l = 40;
		}
		if ($h === 'brown')
			$s = $l = 40;
		if ($h === 'black')
			$s = $l = 0;
		if ($h === 'gray')
		{
			$s = 0;
			$l = 55;
		}
		if ($h === 'white')
		{
			$s = 0;
			$l = 65;
		}
		return "hsla($hue[$h], $s%, $l%, $a)";
	}
}


function color2class($colour)
{
	if ($colour === 'white')
		return 'white';
	elseif ($colour === 'gray')
		return 'secondary';
	elseif ($colour === 'black')
		return 'dark';
	elseif ($colour === 'green' || $colour === 'liteGreen')
		return 'success';
	elseif ($colour === 'orange' || $colour === 'yellow' || $colour === 'brown')
		return 'warning';
	elseif ($colour === 'red' || $colour === 'purple' || $colour === 'peach')
		return 'danger';
	else
		return'primary';
}
