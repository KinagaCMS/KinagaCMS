<?php
if (__FILE__ === implode(get_included_files())) exit;
$delete_lines = $upload_time = $upload_user = [];
$sidebox_order[11] = 1;
$blacklist_alert =
'<div class="modal fade" id=blacklist-alert>'.
'<div class="modal-dialog modal-dialog-centered">'.
'<div class=modal-content><div class="modal-header"><h5 class="border-0 text-black-50">'. $user_not_found_title[1]. '</h5>'.
'<button type=button class=btn-close data-bs-dismiss=modal tabindex=-1></button></div>'.
'<div class="modal-body text-center">'. $ask_admin. '</div>'.
'</div>'.
'</div>'.
'</div>';
if ($forum_topic && false === $fpos)
{
	if (is_file($topic_file = './forum/'. $forum_thread. '/'. $forum_topic))
	{
		$topic_lines = array_unique(file($topic_file));
		$topic_header = str_getcsv($topic_lines[0], ',', "\"", "\\");
		$topicer_name = $topic_header[1];


		$forum_limit = $topic_header[3] ?? $forum_limit;


		if (is_dir($topicer_profdir = 'users/'. $topicer_name. '/prof/'))
			$topicer_name = '<a href="'. $url. '?user='. str_rot13($topicer_name). '">'. avatar($topicer_profdir, 20). ' '. handle($topicer_profdir). '</a>';
		else
		{
			$topicer_name = ($topicer_email = filter_var(dec($topicer_name), FILTER_VALIDATE_EMAIL)) ? avatar($topicer_email, 20). ' '. explode('@', $topicer_email)[0] : '';
			if (is_admin()) $topicer_name = '<a href="mailto:'. $topicer_email. '">'. $topicer_name. '</a>';
		}
		if ($get_page || $get_title) $header .= '<title>'. $topic_title. ' - '. $site_name. '</title>';
		$breadcrumb .=
		'<li class=breadcrumb-item><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
		'<li class=breadcrumb-item><a href="'. $thread_url. '">'. $thread_title. '</a></li>'.
		'<li class="breadcrumb-item active">'. $topic_title. '</li>';
		$article .=
		'<header class=mb-3>'.
		$topicer_name. ' <small class="mx-1 text-muted">'. date($time_format, $topic_header[0]). '</small>'.
		'<h1 class="h3 my-2">'. $topic_title. '</h1>'.
		'</header>';
		if (('!' === $forum_thread[0] || '!' === $forum_topic[0]) && !isset($_SESSION['l']))
			$article .= '<div class="alert alert-danger">'. $login_required[0]. '</div>';
		else
		{
			$count_lines = count($topic_lines);
			$stylesheet .= 'div[id]:target{animation:1s target;box-shadow:0 2px 2px rgba(0,0,0,.4)}@keyframes target{from{background:#ccc}to{background:inherit}';
			if ($count_lines <= $forum_limit) $article .= '<form'. (!isset($_SESSION['l']) ? '' : ' enctype="multipart/form-data"'). ' method=post>';
			$javascript .= '[].slice.call(document.querySelectorAll(".re")||[]).map(e=>new bootstrap.Tooltip(e,{trigger:"hover"}));'.
			(isset($_SESSION['l']) ? '' : 'if(resserid=document.getElementById("resser"))new bootstrap.Popover(resserid,{html:true,trigger:"focus",placement:"bottom",content:"'. $forum_guests[5]. '"});');
			if (is_admin() || is_subadmin())
			{
				if (filter_has_var(INPUT_GET, 'resdel') && ($d = (int)filter_input(INPUT_GET, 'resdel', FILTER_SANITIZE_NUMBER_INT)))
				{
					$topic_contents = str_replace($topic_lines[$d], '#'. $topic_lines[$d], file_get_contents($topic_file));
					if (file_put_contents($topic_file, $topic_contents, LOCK_EX)) exit (header('Location: '. $topic_url. '#re'. $d));
				}
				if (filter_has_var(INPUT_GET, 'resrepost') && ($r = (int)filter_input(INPUT_GET, 'resrepost', FILTER_SANITIZE_NUMBER_INT)))
				{
					$topic_contents = str_replace($topic_lines[$r], substr($topic_lines[$r], 1), file_get_contents($topic_file));
					if (file_put_contents($topic_file, $topic_contents, LOCK_EX)) exit (header('Location: '. $topic_url. '#re'. $r));
				}
			}
			if (($reid = (int)filter_input(INPUT_GET, 'r', FILTER_SANITIZE_NUMBER_INT)) && ($unixtime = (int)filter_input(INPUT_GET, 't', FILTER_SANITIZE_NUMBER_INT)))
			{
				$guest_file = $tmpdir. $reid. $delimiter. $unixtime. '.txt';
				if (is_file($guest_file) && $now <= $unixtime + $time_limit * 60)
				{
					if (file_put_contents($topic_file, file_get_contents($guest_file), FILE_APPEND | LOCK_EX))
					{
						touch('./forum/'. $forum_thread, $now);
						unlink($guest_file);
						exit (header('Location: '. $topic_url. '#re'. $reid));
					}
				}
				else
					$article .= '<div class="alert alert-danger my-4">'. $not_found[0]. '</div>';
				if (is_file($guest_file)) unlink($guest_file);
			}
			for ($k=1; $k < $count_lines; ++$k)
			{
				$topic_str = str_getcsv($topic_lines[$k], ',', "\"", "\\");
				$first_letter = $topic_str[0][0] ?? '';
				$upload_time[] = $topic_str[0] ?? '';
				$upload_user[] = $topic_str[1] ?? '';
				if (is_dir($topic_user_profdir = 'users/'. $topic_str[1]. '/prof/'))
				{
					$topic_user = '<a href="'. $url. '?user='. str_rot13($topic_str[1]). '">'. handle($topic_user_profdir). '</a>';
					$topic_user_avatar = avatar($topic_user_profdir);
				}
				else
				{
					$topic_user = ($topic_user_email = filter_var(dec($topic_str[1]), FILTER_VALIDATE_EMAIL)) ? explode('@', $topic_user_email)[0] : '';
					$topic_user_avatar = avatar($topic_user);
					if (is_admin()) $topic_user = '<a href="mailto:'. $topic_user_email. '">'. $topic_user. '</a>';
				}
				$article .=
				'<div class="d-flex p-4 mb-4'. ($k & 1 ? '': ' bg-light'). '" id="re'. $k. '">'.
				'<div class="me-4">'. $topic_user_avatar. '</div>'.
				'<div class=flex-grow-1>';
				if (is_admin() || is_subadmin())
				{
					if ('#' !== $first_letter)
						$article .= '<a class="btn btn-sm btn-danger" href="'. $topic_url. '&amp;resdel='. $k. '">'. $btn[4]. '</a>';
					else
						$article .= '<a class="btn btn-sm btn-success" href="'. $topic_url. '&amp;resrepost='. $k. '">'. $btn[6]. '</a>';
				}
				$article .= '<h6>'. $topic_user. ' <small class=text-muted>'. timeformat(ltrim($topic_str[0], '#'), $intervals). '</small></h6>';
				if (isset($topic_str[3]) && $topic_str[3])
				{
					$topic_ref = explode(',', $topic_str[3]);
					$end = end($topic_ref);
					$article .= '<p>';
					foreach ($topic_ref as $ref) $article .= '<a href="#re'. $ref. '">&gt;&gt;'. $ref. '</a>'. ($ref === $end ? '' : ', ');
					$article .= '</p>';
				}
				$article .= '<p class="lead wrap">';
				if ((is_admin() || is_subadmin()) && '#' === $first_letter)
				{
					$article .= '<del>'. hs($topic_str[2]). '</del>';
					$ltrim_sharp = ltrim($topic_str[0], '#');
					if (isset($topic_str[4]) && is_file($userimg = 'users/'. $topic_str[1]. '/upload/'. $ltrim_sharp))
						$article .= '<br><a class="btn btn-outline-primary" data-fancybox href="?user='. str_rot13($topic_str[1]). '&amp;t='. $ltrim_sharp.'&amp;f='. $topic_str[4]. '">'. $icon_image. ' '. $topic_str[0].'.'. $topic_str[4]. '</a>';
				}
				elseif (!is_admin() && !is_subadmin() && '#' === $first_letter)
				{
					$delete_lines[] = ltrim($topic_str[0], '#');
					$article .= implode($n, array_map(function($str){return str_repeat('*', mb_strlen($str));}, mb_split('&#10;', $topic_str[2])));
				}
				else
				{
					$article .= hs($topic_str[2]);
					if (isset($topic_str[4]) && is_file($userimg = 'users/'. $topic_str[1]. '/upload/'. $topic_str[0]))
						$article .= '<hr><a class="btn btn-outline-primary" data-fancybox href="?user='. str_rot13($topic_str[1]). '&amp;t='. $topic_str[0].'&amp;f='. $topic_str[4]. '">'. $icon_image. ' '. $topic_str[0].'.'. $topic_str[4]. '</a>';
				}
				$article .= '</p>'.
				'</div>';
				if ($count_lines <= $forum_limit)
					$article .=
					'<div class="form-check re" title="'. $form_label[0]. '">'.
					'<input class=form-check-input type=checkbox name=ressid[] id="ressid'. $k. '" value="'. $k. '">'.
					'<label class=form-check-label for="ressid'. $k. '"></label>'.
					'</div>';
				$article .= '</div>';
			}
			if (str_contains($request_uri, '?user=') && str_contains($request_uri, '&t=') && str_contains($request_uri, '&f='))
			{
				if (is_file($userimg = 'users/'. str_rot13($upuser = basename($v[0])). '/upload/'. $filename = basename($v[1])) && isset($v[2]) && !in_array($filename, $delete_lines, true))
				{
					if (!in_array($filename, $upload_time, true) && !in_array($upuser, $upload_user, true)) exit (http_response_code(403));
					match ($extension = basename($v[2]))
					{
						'png', 'gif' => $mime = $extension,
						default => $mime = 'jpeg',
					};
					header('Content-Type: image/'. $mime);
					header('Content-Disposition: filename="'. $filename. '.'. $extension. '"');
					exit (base64_decode(file_get_contents($userimg)));
				}
			}
			if ($count_lines <= $forum_limit)
			{
				if (('@' === $forum_thread[0] || '@' === $forum_topic[0]) && !isset($_SESSION['l']))
					$article .= '<p class="alert alert-warning">'. $login_required[1]. '</p>';
				else
				{
					$article .= ('check' !== filter_input(INPUT_GET, 'guest') ? '' : '<div class="alert alert-success" id=email>'. $forum_guests[2]. '</div>').
					'<fieldset>'.
					(!isset($_SESSION['l']) ?
						'<input class="form-control mb-3" name=resser id=resser placeholder="'. $placeholder[1]. '" type=email required>'
					:
						'<input name=resser type=hidden value="'. $_SESSION['l']. '">'.
						'<input class=form-control-file type=file name=a accesskey=a accept="image/jpeg,image/png,image/gif" title="&lt;='. (int)ini_get('upload_max_filesize') .'MB">'
					).
					'<textarea class="form-control mb-3" name=ress accesskey=q required rows=5 tabindex=0></textarea>'.
					'<input class="btn btn-primary" type=submit accesskey=c>'.
					'<p>'. $forum_title[3]. sprintf($accepting, (int)($forum_limit - $count_lines)).'</p></fieldset>';
				}
				$ress = !filter_has_var(INPUT_POST, 'ress') ? '' : filter_input(INPUT_POST, 'ress');
				$resser = !filter_has_var(INPUT_POST, 'resser') ? '' : filter_input(INPUT_POST, 'resser', FILTER_CALLBACK, ['options' => 'sanitize_mail']);
				$ressid = !filter_has_var(INPUT_POST, 'ressid') ? '' : filter_input_array(INPUT_POST, ['ressid' => ['flags' => FILTER_REQUIRE_ARRAY, 'filter' => FILTER_SANITIZE_NUMBER_INT]]);
				if ($ress && $resser)
				{
					$resstime = $now;
					$ress = str_replace($line_breaks, '&#10;', $ress);
					$ress = trim($ress, "\\");
					if (filter_var($resser, FILTER_VALIDATE_EMAIL))
					{
						if (!filter_var($resser, FILTER_CALLBACK, ['options' => 'blacklist']))
						{
							$article .= $blacklist_alert;
							$javascript .= 'new bootstrap.Modal(document.getElementById("blacklist-alert")).show();';
						}
						else
						{
							if (file_put_contents($tmpdir. $k. $delimiter. $resstime. '.txt', $resstime. ',"'. enc($resser). '","'. $ress. '"'. (!isset($ressid['ressid']) ? '' : ',"'. implode(',', $ressid['ressid']). '"'). $n, LOCK_EX))
							{
								$ress_limit = date($time_format, $resstime + $time_limit * 60);
								$headers = $mime. 'From: '. $from. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n;
								$subject = $forum_guests[0]. ' - '. $site_name;
								$body = sprintf($forum_guests[1], $ress_limit). $n. $topic_url. '&amp;r='. $k. '&amp;t='. $resstime. $n. $n. $separator. $n. $site_name. $n. $url;
								if (mail($resser, $subject, $body, $headers)) header('Location: '. $forum_url. '&guest=check#email');
							}
						}
					}
					elseif (is_dir('users/'. $resser. '/prof/'))
					{
						if (isset($_FILES['a']['error'], $_FILES['a']['name'], $_FILES['a']['tmp_name']) && UPLOAD_ERR_OK === $_FILES['a']['error'])
						{
							if (getimagesize($_FILES['a']['tmp_name']))
							{
								match (exif_imagetype($_FILES['a']['tmp_name']))
								{
									IMAGETYPE_JPEG => $type = 'jpeg',
									IMAGETYPE_PNG => $type = 'png',
									IMAGETYPE_GIF => $type = 'gif',
								};
								if (isset($type)) file_put_contents($userdir. '/upload/'. $resstime, base64_encode(file_get_contents($_FILES['a']['tmp_name'])), LOCK_EX);
							}
						}
						file_put_contents('./forum/'. $forum_thread. '/'. $forum_topic, $resstime. ',"'. $resser. '","'. $ress. '","'. (!isset($ressid['ressid']) ? '' : implode(',', $ressid['ressid'])). '"'. (!isset($type) ? '' : ',"'. $type. '"'). $n, FILE_APPEND | LOCK_EX);
						counter($userdir. '/forum-ress.txt', 1);
						touch('./forum/'. $forum_thread, $resstime);
						exit (header('Location: '. $topic_url. '#re'. $k));
					}
				}
				$article .= '</form>';
			}
		}
	}
	else not_found();
}
elseif ($forum_thread && false === $fpos)
{
	$threader_file = './forum/'. $forum_thread. '/#threader';
	if (is_file($threader_oldfile = './forum/'. $forum_thread. '/threader')) rename($threader_oldfile, $threader_file);
	if (is_file($threader_file))
	{
		$threader_name = file_get_contents($threader_file);
		if (is_dir($threader_profdir = 'users/'. $threader_name. '/prof/'))
			$threader_name = '<a href="'. $url. '?user='. str_rot13($threader_name). '">'. avatar($threader_profdir, 20). ' '. handle($threader_profdir). '</a>';
		else
		{
			$threader_name = ($threader_email = filter_var(dec($threader_name), FILTER_VALIDATE_EMAIL)) ? avatar($threader_email, 20). ' '. explode('@', $threader_email)[0] : '';
			if (is_admin() || is_subadmin())
				$threader_name = '<a href="mailto:'. $threader_email. '">'. $threader_name. '</a>';
		}
		$article .=
		'<header class=my-3>'.
		$threader_name. ' <small class="mx-1 text-muted">'. date($time_format, filemtime($threader_file)). '</small>'.
		'<h1 class="h3 my-2">'. $thread_title. '</h1>'.
		'</header>';
		if ('!' === $forum_thread[0] && !isset($_SESSION['l']))
		{
			if ($get_page || $get_title) $header .= '<title>'. $thread_title. ' - '. $site_name. '</title>';
			$breadcrumb .= '<li class="breadcrumb-item active"><a href="'. $forum_url. '">'. h($forum). '</a></li><li class="breadcrumb-item active">'. $thread_title. '</li>';
			$article .= '<p class="alert alert-danger">'. $login_required[0]. '</p>';
		}
		else
		{
			if ($get_page || $get_title) $header .= '<title>'. $thread_title. ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>';
			$breadcrumb .=
			($pages > 1 ?
				'<li class=breadcrumb-item><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
				'<li class=breadcrumb-item><a href="'. $thread_url. '">'. $thread_title. '</a></li>'.
				'<li class="breadcrumb-item active">'. sprintf($page_prefix, $pages). '</li>'
			:
				'<li class="breadcrumb-item active"><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
				'<li class="breadcrumb-item active">'. $thread_title. '</li>'
			);
			if (is_admin() || is_subadmin())
			{
				if ($d = !filter_has_var(INPUT_GET, 'topicdel') ? '' : filter_input(INPUT_GET, 'topicdel', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
					if (is_file('./forum/'. $forum_thread. '/'. $d)) if (rename('./forum/'. $forum_thread. '/'. $d, './forum/'. $forum_thread. '/#'. $d)) exit (header('Location: '. $thread_url));
				if ($r = !filter_has_var(INPUT_GET, 'topicrepost') ? '' : filter_input(INPUT_GET, 'topicrepost', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
					if (is_file('./forum/'. $forum_thread. '/#'. $r)) if (rename('./forum/'. $forum_thread. '/#'. $r, './forum/'. $forum_thread. '/'. $r)) exit (header('Location: '. $thread_url));
			}
			if (($guest = !filter_has_var(INPUT_GET, 'g') ? '' : filter_input(INPUT_GET, 'g', FILTER_CALLBACK, ['options' => 'strip_tags_basename'])) && ($unixtime = !filter_has_var(INPUT_GET, 't') ? '' : (int)filter_input(INPUT_GET, 't', FILTER_SANITIZE_NUMBER_INT)))
			{
				$guest_file = $tmpdir. $unixtime. $delimiter. str_rot13($guest);
				if (is_file($guest_file) && $now <= $unixtime + $time_limit * 60)
				{
					 $guest_content = str_getcsv($guest_str = file_get_contents($guest_file), ',', "\"", "\\");
					 if (isset($guest_content[2]) && !is_file($guest_topic = './forum/'. $forum_thread. '/'. $guest_content[2]))
					 {
						file_put_contents($guest_topic, $guest_str, LOCK_EX);
						touch('./forum/'. $forum_thread, $now);
						unlink($guest_file);
						exit (header('Location: '. $thread_url. r($guest_content[2])));
					}
				}
				else
					$article .= '<div class="alert alert-danger my-4">'. $not_found[0]. '</div>';
				if (is_file($guest_file)) unlink($guest_file);
			}

			if ($thread_topic_glob = array_filter(glob('./forum/'. $forum_thread. '/'. (is_admin() || is_subadmin() ? '*' : '[!#]*'), GLOB_NOSORT), function($a){if (is_file($a) && '#threader' !== basename($a)) return $a;}))
			{
				usort($thread_topic_glob, 'sort_time');
				$count_topics = count($thread_topic_glob);
				$page_ceil = ceil($count_topics / $forum_contents_per_page);
				$max_page = min($pages, $page_ceil);
				$sliced_topics = array_slice($thread_topic_glob, ($max_page - 1) * $forum_contents_per_page, $forum_contents_per_page);
				if ($count_topics > $forum_contents_per_page) pager($max_page, $page_ceil);
				$article .= '<ul class="list-group list-group-flush mb-5">';
				foreach ($sliced_topics as $key => $thread_topics)
				{
					$thread_topic = basename($thread_topics);
					if (str_contains($thread_topic, '.')) continue;
					$topic_name = '#' !== $thread_topic[0] ? $thread_topic : substr($thread_topic, 1);
					$topic_name = ('!' !== $topic_name[0] && '@' !== $topic_name[0]) ? $topic_name : substr($topic_name, 1);
					$article .= '<li class="list-group-item '. (!($key & 1) ? ' bg-light' : ' bg-body'). ' d-flex align-items-center">';
					if ('#' !== $thread_topic[0])
						$article .=
						(!is_admin() && !is_subadmin() ? '' : '<a class="btn btn-sm btn-danger" href="'. $thread_url. '&amp;topicdel='. r($thread_topic). '">'. $btn[4]. '</a>').
						'<a class="h5 flex-grow-1 ms-2" href="'. $thread_url. r($thread_topic). '">'. h($topic_name). '</a>';
					else
						$article .=
						(!is_admin() && !is_subadmin() ? '' : '<a class="btn btn-sm btn-success" href="'. $thread_url. '&amp;topicrepost='. r(substr($thread_topic, 1)). '">'. $btn[6]. '</a>').
						'<del class="flex-grow-1 ms-2">'. h($topic_name). '</del>';
					$article .=
					'<span class="d-flex flex-column text-center">'.
					'<small class="bg-light px-3 py-2">'. timeformat(filemtime($thread_topics), $intervals). '</small>'.
					'<small class="bg-light px-3 py-2">'. $forum_title[2]. ' '. (count(file($thread_topics))-1). '</small>'.
					'</span>'.
					'</li>';
				}
				$article .= '</ul>';
				if ($count_topics > $forum_contents_per_page) pager($max_page, $page_ceil);
			}
			$topic = !filter_has_var(INPUT_POST, 'topic') ? '' : filter_input(INPUT_POST, 'topic', FILTER_CALLBACK, ['options' => 'trim_str_replace_basename']);
			$topicer = !filter_has_var(INPUT_POST, 'topicer') ? '' : filter_input(INPUT_POST, 'topicer', FILTER_CALLBACK, ['options' => 'sanitize_mail']);
			$topic_limit = !filter_has_var(INPUT_POST, 'topic-limit') ? '' : filter_input(INPUT_POST, 'topic-limit', FILTER_SANITIZE_NUMBER_INT);
			if ($topic && $topicer)
			{
				if (!is_file($topicfile = './forum/'. $forum_thread. '/'. $topic) && !is_file('./forum/'. $forum_thread. '/#'. $topic))
				{
					if (filter_var($topicer, FILTER_VALIDATE_EMAIL))
					{
						if (!filter_var($topicer, FILTER_CALLBACK, ['options' => 'blacklist']))
						{
							$article .= $blacklist_alert;
							$javascript .= 'new bootstrap.Modal(document.getElementById("blacklist-alert")).show();';
						}
						else
						{
							$topictime = $now;
							$enctopicer = enc($topicer);
							if (file_put_contents($tmpdir. $topictime. $delimiter. $enctopicer, $topictime. ',"'. $enctopicer. '","'. $topic. '",'. $topic_limit. $n, LOCK_EX))
							{
								$topic_limit = date($time_format, $topictime + $time_limit * 60);
								$headers = $mime. 'From: '. $from. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n;
								$subject = $forum_guests[0]. ' - '. $site_name;
								$body = sprintf($forum_guests[1], $topic_limit). $n. $thread_url. '&amp;g='. str_rot13($enctopicer). '&amp;t='. $topictime. $n. $n. $separator. $n. $site_name. $n. $url;
								if (mail($topicer, $subject, $body, $headers)) header('Location: '. $thread_url. '&guest=check#email');
							}
						}
					}
					elseif (is_dir('users/'. $topicer. '/prof/'))
					{
						file_put_contents($topicfile, $now. ',"'. $topicer. '","'. $topic. '",'. $topic_limit. $n, FILE_APPEND | LOCK_EX);
						counter($userdir. '/forum-topic.txt', 1);
						touch('./forum/'. $forum_thread, $now);
						exit (header('Location: '. $thread_url. r($topic)));
					}
				}
				else
					$article .= '<div class="alert alert-danger">'. $not_found[0]. '</div>';
			}
			if ($allow_guest_creates || isset($_SESSION['l']))
			{
				$article .=
				('check' !== filter_input(INPUT_GET, 'guest') ? '' : '<div class="alert alert-success" id=email>'. $forum_guests[2]. '</div>').
				'<form class="bg-light p-4" method=post>'.
				'<label class="h5 mb-4" for=topic>'. $form_label[1]. ' <small class=text-muted id=max></small></label>'.
				'<div class=input-group>'.
				'<input class=form-control type=text name=topic id=topic accesskey=t maxlength='. $title_length. ' required placeholder="'. $form_label[2]. '">'.
				(isset($_SESSION['l']) ? '<input type=hidden name=topicer value="'. $_SESSION['l']. '">' : '<input class=form-control name=topicer id=topicer placeholder="'. $placeholder[1]. '" type=email required>').
				'<select class=form-select name=topic-limit id=topic-limit style="flex:0 10%" data-bs-toggle="tooltip" data-bs-placement="top" title="'. $forum_title[3]. '">'.
				'<option selected>'. sprintf($hitcount, $forum_limit). '</option>'.
				'<option value="500">'. sprintf($hitcount, 500). '</option>'.
				'<option value="1000">'. sprintf($hitcount, 1000). '</option>'.
				'</select>'.
				'<input class="btn btn-primary" type=submit accesskey=c>'.
				'</div>'.
				'</form>';
				$javascript .= 'if(topicerid=document.getElementById("topicer"))new bootstrap.Popover(topicerid,{html:true,trigger:"focus",placement:"bottom",content:"'. $forum_guests[5]. '"});if(topicid=document.getElementById("topic")){new bootstrap.Popover(topicid,{html:true,trigger:"focus",placement:"bottom",title:"'. $forum_guests[3]. '",content:"'. $forum_guests[4]. '"});topicid.addEventListener("input",ev=>{l=encodeURIComponent(ev.target.value).replace(/%../g,"x").length,m='. $title_length. ';document.getElementById("max").innerText="'. sprintf($form_label[5], '"+(m-l)+"'). '";if(l>=m){topicid.classList.add("is-invalid");document.querySelector("input[type=submit]").setAttribute("disabled",true)}else{topicid.classList.remove("is-invalid");document.querySelector("input[type=submit]").removeAttribute("disabled")}})}';
			}
			else
				$article .= '<p class="alert alert-warning">'. $forum_guests[6]. '</p>';
		}
	}
	else not_found();
}
elseif (!isset($v[0]))
{
	if ($get_page || $get_title) $header .= '<title>'. h($forum). ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>';
	$breadcrumb .=
	($pages > 1 ?
		'<li class=breadcrumb-item><a href="'. $forum_url. '">'. h($forum). '</a></li><li class="breadcrumb-item active">'. sprintf($page_prefix, $pages). '</li>'
	:
		'<li class="breadcrumb-item active"><a href="'. $forum_url. '">'. h($forum). '</a></li>'
	);
	if ($forum_thread_glob = glob('./forum/'. (is_admin() || is_subadmin() ? '' : '[!#]'). '*', GLOB_NOSORT+GLOB_ONLYDIR))
	{
		if (is_admin() || is_subadmin())
		{
			if ($d = !filter_has_var(INPUT_GET, 'del') ? '' : filter_input(INPUT_GET, 'del', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
				if (is_dir('./forum/'. $d)) if (rename('./forum/'. $d, './forum/#'. $d)) exit (header('Location: '. $forum_url));
			if ($r = !filter_has_var(INPUT_GET, 'repost') ? '' : filter_input(INPUT_GET, 'repost', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
				if (is_dir('./forum/#'. $r)) if (rename('./forum/#'. $r, './forum/'. $r)) exit (header('Location: '. $forum_url));
		}
		usort($forum_thread_glob, 'sort_time');
		$count_threads = count($forum_thread_glob);
		$page_ceil = ceil($count_threads / $forum_contents_per_page);
		$max_page = min($pages, $page_ceil);
		$sliced_threads = array_slice($forum_thread_glob, ($max_page - 1) * $forum_contents_per_page, $forum_contents_per_page);
		$article .=
		'<header class=mb-3>'.
		'<h2 class="h3 my-2">'. $forum.
		'<small class="h5 mx-4">'. $forum_title[1]. ' <span class="badge bg-secondary">'. $count_threads. '</span></small>'.
		'<small class="h5">'. $forum_title[0]. ' <span class="badge bg-secondary">'. count(array_filter(glob('forum/[!#]*/[!#]*', GLOB_NOSORT), 'is_file')). '</span></small>'.
		'</h2>'.
		'</header>'.
		'<div class="'. $forum_wrapper_class. '">';
		if ($count_threads > $forum_contents_per_page) pager($max_page, $page_ceil);
		foreach ($sliced_threads as $key => $threads)
		{
			$thread_name = basename($threads);
			$threader_file = $threads. '/#threader';
			$thread_title = '#' === $thread_name[0] || '!' === $thread_name[0] || '@' === $thread_name[0] ? substr($thread_name, 1) : $thread_name;
			if (is_file($threader_old = $threads. '/threader')) rename($threader_old, $threader_file);
			$threader_name = file_get_contents($threads. '/#threader');
			if (is_dir($threader_profdir = 'users/'. $threader_name. '/prof/'))
				$threader_name = '<a href="'. $url. '?user='. str_rot13($threader_name). '">'. avatar($threader_profdir, 20). ' '. handle($threader_profdir). '</a>';
			else
			{
				$threader_name = ($threader_email = filter_var(dec($threader_name), FILTER_VALIDATE_EMAIL)) ? avatar($threader_email, 20). ' '. explode('@', $threader_email)[0] : '';
				if (is_admin() || is_subadmin())
					$threader_name = '<a href="mailto:'. $threader_email. '">'. $threader_name. '</a>';
			}
			$article .=
			'<div class="d-flex mb-5 p-3'. (!($key & 1) ? ' bg-light' : ' bg-body'). '">'.
			'<div class="col-9">'.
			'<small class="d-block mb-2">'. $threader_name. '</small>';
			if ('#' !== $thread_name[0])
				$article .=
				(!is_admin() && !is_subadmin() ? '' : '<a class="btn btn-sm btn-danger me-2" href="'. $forum_url. '&amp;del='. r($thread_title). '">'. $btn[4]. '</a>').
				'<a class=h4 href="'. $forum_url. '/'. r($thread_name). '/">'. h($thread_title). '</a>';
			else
				$article .=
				(!is_admin() && !is_subadmin() ? '' : ' <a class="btn btn-sm btn-success" href="'. $forum_url. '&amp;repost='. r($thread_title). '">'. $btn[6]. '</a>').
				'<del class="h5 ms-2">'. h($thread_title). '</del>';
			$article .=
			'</div>'.
			'<div class="col-3 bg-light text-center d-flex flex-column text-center">'.
			'<small class="px-3 py-2">'. timeformat(filemtime($threads), $intervals). '</small>'.
			'<small class="px-3 py-2">'.$forum_title[0]. ' '. count(array_filter(glob($threads. '/[!#]*', GLOB_NOSORT), 'is_file')). '</small></div>'.
			'</div>';
		}
		$article .= '</div>';
		if ($count_threads > $forum_contents_per_page) pager($max_page, $page_ceil);
	}
	if (($guest = !filter_has_var(INPUT_GET, 'g') ? '' : filter_input(INPUT_GET, 'g', FILTER_CALLBACK, ['options' => 'strip_tags_basename'])) && ($unixtime = !filter_has_var(INPUT_GET, 't') ? '' : (int)filter_input(INPUT_GET, 't', FILTER_SANITIZE_NUMBER_INT)))
	{
		$guest_file = $tmpdir. $unixtime. $delimiter. ($threader = str_rot13($guest));
		if (is_file($guest_file) && $now <= $unixtime + $time_limit * 60)
		{
			if (!is_dir($threaddir = './forum/'. ($thread_name = file_get_contents($guest_file)). '/'))
			{
				mkdir($threaddir, 0757);
				file_put_contents($threaddir. '#threader', $threader, LOCK_EX);
				unlink($guest_file);
				exit (header('Location: '. $forum_url. '/'. r($thread_name). '/'));
			}
		}
		else
			$article .= '<div class="alert alert-danger mb-4">'. $not_found[0]. '</div>';
		if (is_file($guest_file)) unlink($guest_file);
	}
	$thread = !filter_has_var(INPUT_POST, 'thread') ? '' : filter_input(INPUT_POST, 'thread', FILTER_CALLBACK, ['options' => 'trim_str_replace_basename']);
	$threader = !filter_has_var(INPUT_POST, 'threader') ? '' : filter_input(INPUT_POST, 'threader', FILTER_CALLBACK, ['options' => 'sanitize_mail']);
	if ($thread && $threader)
	{
		if (!is_dir($threaddir = './forum/'. $thread. '/'))
		{
			if (filter_var($threader, FILTER_VALIDATE_EMAIL))
			{
				if (!filter_var($threader, FILTER_CALLBACK, ['options' => 'blacklist']))
				{
					$article .= $blacklist_alert;
					$javascript .= 'new bootstrap.Modal(document.getElementById("blacklist-alert")).show();';
				}
				else
				{
					$threadtime = $now;
					$encthreader = enc($threader);
					if (file_put_contents($tmpdir. $threadtime. $delimiter. $encthreader, $thread, LOCK_EX))
					{
						$thread_limit = date($time_format, $threadtime + $time_limit * 60);
						$headers = $mime. 'From: '. $from. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n;
						$subject = $forum_guests[0]. ' - '. $site_name;
						$body = sprintf($forum_guests[1], $thread_limit). $n. $forum_url. '&amp;g='. str_rot13($encthreader). '&amp;t='. $threadtime. $n. $n. $separator. $n. $site_name. $n. $url;
						if (mail($threader, $subject, $body, $headers)) header('Location: '. $forum_url. '&guest=check#email');
					}
				}
			}
			elseif (is_dir('users/'. $threader. '/prof/'))
			{
				mkdir($threaddir, 0757);
				file_put_contents($threaddir. '#threader', $threader, LOCK_EX);
				counter($userdir. '/forum-thread.txt', 1);
				exit (header('Location: '. $forum_url. '/'. r($thread). '/'));
			}
		}
		else
			$article .= '<div class="alert alert-danger mb-4">'. $not_found[0]. '</div>';
	}
	if ($allow_guest_creates || isset($_SESSION['l']))
	{
		$article .=
		('check' !== filter_input(INPUT_GET, 'guest') ? '' : '<div class="alert alert-success my-4" id=email>'. $forum_guests[2]. '</div>').
		'<form class="bg-light p-4" method=post>'.
		'<label class="h5 mb-4" for=thread>'. $form_label[3]. ' <small class=text-muted id=max></small></label>'.
		'<div class=input-group>'.
		'<input class=form-control type=text name=thread id=thread accesskey=t maxlength='. $title_length. ' required placeholder="'. $form_label[4]. '">'.
		(isset($_SESSION['l']) ? '<input type=hidden name=threader value="'. $_SESSION['l']. '">' : '<input class=form-control name=threader id=threader placeholder="'. $placeholder[1]. '" type=email required>').
		'<input class="btn btn-primary" type=submit accesskey=c>'.
		'</div>'.
		'</form>';
		$javascript .= 'if(threaderid=document.getElementById("threader"))new bootstrap.Popover(threaderid,{html:true,trigger:"focus",placement:"bottom",content:"'. $forum_guests[5]. '"});if(threadid=document.getElementById("thread")){new bootstrap.Popover(threadid,{html:true,trigger:"focus",placement:"bottom",title:"'. $forum_guests[3]. '",content:"'. $forum_guests[4]. '"});threadid.addEventListener("input",ev=>{l=encodeURIComponent(ev.target.value).replace(/%../g,"x").length,m='. $title_length. ';document.getElementById("max").innerText="'. sprintf($form_label[5], '"+(m-l)+"'). '";if(l>=m){threadid.classList.add("is-invalid");document.querySelector("input[type=submit]").setAttribute("disabled",true)}else{threadid.classList.remove("is-invalid");document.querySelector("input[type=submit]").removeAttribute("disabled")}})}';
	}
	else
		$article .= '<p class="alert alert-warning">'. $forum_guests[6]. '</p>';
}
