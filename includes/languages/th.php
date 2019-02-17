<?php
/*
 * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
 * @license    GPL 3.0 or later; see LICENSE file for details.
 * @author     Translate by Goragod Wiriya http://kotchasan.com
 */

setlocale(LC_ALL, 'th_TH.UTF-8');
date_default_timezone_set('Asia/Bangkok');

// Site Name
$site_name = 'kinaga';

// Your mail address
$mail_address = '';

// Your home or office address
$address = '';
$address_title = '';

// Hue:  'สีแดง', 'สีชมพู', 'สีน้ำตาล', 'สีเขียวเหลือง', 'สีเขียว', 'สีน้ำเงิน', 'สีฟ้า', 'สีม่วง',  'Gray', 'สีดำ', or BLANK
$color = 'สีเขียว';

// Description: Top page
$meta_description = 'คำอธิบายเกี่ยวกับไซต์';

// Subtitle: Top Page H1 and TITLE
$subtitle = '';

// Top Page
$home = 'หน้าหลัก';

// Sideboxes
$recents = 'บทความล่าสุด';

$category = 'ประเภท';

$informations = 'ข้อมูลการติดต่อ';

$recent_comments = 'ความคิดเห็น';

$popular_articles = 'บทความยอดนิยม';

$download_contents = 'ดาวน์โหลด';
$download_subtitle = '';
$download_notice = '';

$contact_us = 'ติดต่อเรา';
$contact_subtitle = '';
$contact_notice = '';

$similar_title = 'บทความคล้ายๆกัน';

//TOC
$toc = 'Table of Contents';

$nav_laquo = '&laquo;';

$nav_raquo = '&raquo;';

$comments_prev = 'ใหม่';

$comments_next = 'เก่า';


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

$views = 'ูเปิดดู %s';

$images_count_title = ' (%s รูปภาพ)';

$source = 'Source: %s';

$result = 'ผลลัพท์การค้นหา <b>%s</b>';

$no_results_found = 'ไม่มีผลลัพท์';

$comments_not_allow = 'ปิดการแสดงความคิดเห็น';

$comments_count_title = ' (%s ความคิดเห็น)';

$comment_title = 'ความคิดเห็น';
$comment_notice = $contact_notice . '';

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

$comment_subject = 'มีความคิดเห็นเกี่ยวกับ %s หมวดหมู่บทความ %s - ';

$contact_success = 'ข้อความถูกส่งเรียบร้อยแล้ว';

$contact_error = 'ไม่สามารถส่งได้';

$time_format = 'F jS, Y h:i';

$present_format = 'F jS';

$seconds_ago = '%s วินาทีที่แล้ว';

$minutes_ago = '%s ไม่กี่นาทีผ่านมา';

$hours_ago = '%s ชั่วโมงที่แล้ว';

$days_ago = '%s วันก่อน';

$benchmark_results = '<span class="d-block text-muted text-center small">Total time: %s sec. Memory: %s</span>';


// /images/index.php
$images_title = 'รูปภาพ - %s';

$images_heading = 'รูปภาพ <small class="text-muted ml-2">สำเนา tag ของรูปภาพไปวางในบทความ</small>';

$images_aligner = 'จัดเรียงรูปภาพ <small class="text-muted ml-2">คุณสามารถใส่ &lt;div class=clearfix&gt;&lt;/div&gt; เพื่อบังคับให้รูปภาพขึ้นบรรทัดใหม่ได้</small>';

$noscript = 'กรุณาเปิดการใช้งาน <strong>Javascript</strong>.';

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


function hsla($colour, $cal_s=0, $cal_l=0, $a=1)
{
	if ($colour === 'สีแดง')
	{
		$h = 355;
		$s = 70;
		$l = 50;
	}
		elseif ($colour === 'สีชมพู')
	{
		$h = 330;
		$s = 70;
		$l = 70;
	}
	elseif ($colour === 'สีน้ำตาล')
	{
		$h = 40;
		$s = 40;
		$l = 35;
	}
	elseif ($colour === 'สีเขียวเหลือง')
	{
		$h = 80;
		$s = 60;
		$l = 50;
	}
	elseif ($colour === 'สีเขียว')
	{
		$h = 120;
		$s = 60;
		$l = 40;
	}
	elseif ($colour === 'สีน้ำเงิน')
	{
		$h = 220;
		$s = 60;
		$l = 60;
	}
		elseif ($colour === 'สีฟ้า')
	{
		$h = 195;
		$s = 60;
		$l = 60;
	}
	elseif ($colour === 'สีม่วง')
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
	elseif ($colour === 'สีดำ')
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
	elseif ($colour === 'สีดำ' )
		return 'dark';
	elseif ($colour === 'สีน้ำตาล')
		return 'muted';
	elseif ($colour === 'สีเขียวเหลือง' || $colour === 'สีเขียว')
		return 'success';
	elseif ($colour === 'สีแดง' || $colour === 'สีชมพู')
		return 'warning';
	elseif ($colour === 'สีม่วง')
		return 'danger';
	elseif ($colour === 'สีน้ำเงิน' || $colour === 'สีฟ้า')
		return 'info';
	else
		return 'primary';
}
