<?php
if (__FILE__ === implode(get_included_files())) exit;
if (false !== strpos($request_uri, '?'))
{
	$re = explode('?', $request_uri);
	if (isset($re[1])) $re = explode('&', $re[1]);
	foreach ($re as $req) if ($req) list ($k[], $v[]) = explode('=', $req);
	$pages = $v[1] ?? $pages;
}
$forum_url = $url. r($forum);
if ($forum_thread = !filter_has_var(INPUT_GET, 'thread') ? '' : basename(filter_input(INPUT_GET, 'thread', FILTER_SANITIZE_STRING)))
{
	$thread_title = '!' === $forum_thread[0] || '@' === $forum_thread[0] ? h(substr($forum_thread, 1)) : h($forum_thread);
	$thread_url = $forum_url. '/'. r($forum_thread). '/';
}
if ($forum_topic = !filter_has_var(INPUT_GET, 'topic') ? '' : basename(filter_input(INPUT_GET, 'topic', FILTER_SANITIZE_STRING)))
{
	$topic_title = '!' === $forum_topic[0] || '@' === $forum_topic[0] ? h(substr($forum_topic, 1)) : h($forum_topic);
	$topic_url = $thread_url. r($forum_topic);
}
$blacklist_alert =
'<div class="modal fade" id=blacklist-alert>'. $n.
'<div class="modal-dialog modal-dialog-centered">'. $n.
'<div class=modal-content><div class="modal-header"><h5 class="border-0 text-black-50">'. $user_not_found_title[1]. '</h5>'. $n.
'<button type=button class=close data-dismiss=modal tabindex=-1><span aria-hidden=true>&times;</span></button></div>'. $n.
'<div class="modal-body text-center">'. $​ask_admin. '</div>'. $n.
'</div>'. $n.
'</div>'. $n.
'</div>';
if ($use_search)
{
	if (isset($v[0]))
	{
		$no_results = '';
		$fquery = urldecode($v[0]);
		$result_title = sprintf($result, h($fquery));
		$breadcrumb .= '<li class="breadcrumb-item"><a href="'. $forum_url. '">'. h($forum). '</a></li><li class="breadcrumb-item active">'. $result_title. '</li>';
		$header .= '<title>'. $result_title. ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;
		$article .= '<h1 class="h3 mb-4">'. $result_title. '</h1>'. $n;
		$forum_search_area = 'forum/'. (filter_has_var(INPUT_GET, 'thread') ? $forum_thread. '/'. (is_admin() ? '*' : '[!#]*[!threader]*') : (is_admin() ? '*' : '[!#]*/[!#]*[!threader]*'));
		$forum_search_glob = glob($forum_search_area, GLOB_NOSORT);
		if ($forum_search_glob)
		{
			usort($forum_search_glob, 'sort_time');
			foreach($forum_search_glob as $topics)
			{
				$topic_lines = file($topics);
				foreach ($topic_lines as $topic_line)
				{
					$topic_str = str_getcsv($topic_line);
					if ('#' === $topic_str[0][0]) continue;
					$topic_contents = html_entity_decode($topic_str[2]);
					$timestamp = date($time_format, $topic_str[0]);
					$first_pos = strpos($topic_contents. $timestamp, $fquery);
					if (false !== $first_pos)
					{
						$start = max(0, $first_pos - 150);
						$length = $summary_length + mb_strlen($fquery, $encoding);
						$str = mb_substr($topic_contents, $start, $length, $encoding);
						$str = !$str ? mb_strimwidth($topic_contents, 0, $summary_length, $ellipsis, $encoding) : mb_strimwidth($str, 0, $summary_length, $ellipsis, $encoding);
						$str = str_replace($fquery, '<strong class=highlight>'. $fquery. '</strong>', $str);
						$outputs[] = [$timestamp, $topics, $str];
						break;
					}
				}
			}
			if (isset($outputs))
			{
				$results_number = count($outputs);
				$page_ceil = ceil($results_number / $results_per_page);
				$max_pages = min($pages, $page_ceil);
				$results_in_page = array_slice($outputs, ($max_pages - 1) * $results_per_page, $results_per_page);
				if ($results_number > $results_per_page) pager($max_pages, $page_ceil);
				foreach ($results_in_page as $output)
				{
					$base_title = basename($output[1]);
					$topic_title = '!' === $base_title[0] || '@' === $base_title[0] ? substr($base_title, 1) : $base_title;
					$dir_title = get_title($output[1]);
					$thread_title = '!' === $dir_title[0] || '@' === $dir_title[0] ? substr($dir_title, 1) : $dir_title;
					$article .=
					'<section class="p-2 mb-5 border-bottom position-relative">'. $n.
					'<h2 class=h4><a class=stretched-link href="'. $url. r($forum. '/'. $dir_title. '/'. $base_title). '">'. h($topic_title). '</a></h2>'. $n.
					'<div class="wrap p-2">'. strip_tags($output[2], '<strong>'). '</div>'. $n.
					'<small class="blockquote-footer text-right">'. h($thread_title). ' - '. $output[0]. '</small>'. $n.
					'</section>'. $n;
				}
				if ($results_number > $results_per_page) pager($max_pages, $page_ceil);
			}
			else
				$no_results = true;
		}
		else
			$no_results = true;
		if ($no_results) $article .= '<h2 class=h4>'. $no_results_found. '</h2>'. $n;
	}
	$aside .=
	'<div id=fsearch class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[11]. '">'. $n.
	'<div class="'. $sidebox_title_class[0]. '">'. sprintf($sidebox_title[12], filter_has_var(INPUT_GET, 'thread') ? $forum_title[1] : $forum). '</div>'. $n.
	'<form class="'. $sidebox_content_class[3]. '" method=get>'. $n.
	'<input class="text-reset form-control my-2" placeholder="'. $placeholder[0]. '" type=search name=fquery required accesskey=f>'. $n.
	'</form>'. $n.
	'</div>'. $n;
}
if (!isset($v[0]) && $forum_topic)
{
	if (is_file($topic_file = './forum/'. $forum_thread. '/'. $forum_topic))
	{
		$topic_lines = file($topic_file);
		$topic_header = str_getcsv($topic_lines[0]);
		$topicer_name = $topic_header[1];
		if (is_dir($topicer_profdir = $usersdir. $topicer_name. '/prof/'))
			$topicer_name = '<a href="'. $url. '?user='. str_rot13($topicer_name). '">'. handle($topicer_profdir). '</a>';
		else
		{
			$topicer_name = filter_var($topicer_email = dec($topicer_name), FILTER_VALIDATE_EMAIL) ? explode('@', $topicer_email)[0] : '';
			if (is_admin()) $topicer_name = '<a href="mailto:'. $topicer_email. '">'. $topicer_name. '</a>';
		}
		if ($get_page || $get_title) $header .= '<title>'. $topic_title. ' - '. $site_name. '</title>';
		$breadcrumb .=
		'<li class=breadcrumb-item><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
		'<li class=breadcrumb-item><a href="'. $thread_url. '">'. $thread_title. '</a></li>'.
		'<li class="breadcrumb-item active">'. $topic_title. '</li>';
		$article .=
		'<header class=mb-5>'.
		'<small class=text-muted>'. date($time_format, $topic_header[0]). ' '. $topicer_name. '</small>'.
		'<h2 class=h3>'. $topic_title. '</h2>'.
		'</header>';
		if (('!' === $forum_thread[0] || '!' === $forum_topic[0]) && !isset($_SESSION['l']))
			$article .= '<p class="alert alert-danger mt-3">'. $login_required[0]. '</p>';
		else
		{
			$count_lines = count($topic_lines);
			$header .= '<style>.media:target{animation:1s target}@keyframes target{from{background:#ccc}to{background:inherit}</style>'. $n;
			if ($count_lines <= $forum_limit) $article .= '<form method=post>';
			$footer .= '<script>$(".re").tooltip({trigger:"hover"})'. (isset($_SESSION['l']) ? '' : ';$("#resser").popover({html:true,trigger:"focus",placement:"bottom",content:"'. $forum_guests[5]. '"})'). '</script>';
			if (is_admin())
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
				$topic_str = str_getcsv($topic_lines[$k]);
				$first_letter = substr($topic_lines[$k], 0, 1);
				if (is_dir($topic_user_profdir = $usersdir. $topic_str[1]. '/prof/'))
				{
					$topic_user = '<a href="'. $url. '?user='. str_rot13($topic_str[1]). '">'. handle($topic_user_profdir). '</a>';
					$topic_user_avatar = avatar($topic_user_profdir);
				}
				else
				{
					$topic_user = filter_var($topic_user_email = dec($topic_str[1]), FILTER_VALIDATE_EMAIL) ? explode('@', $topic_user_email)[0] : '';
					$topic_user_avatar =
					'<span class="avatar align-items-center bg-primary d-flex justify-content-center font-weight-bold display-3 mx-auto rounded-circle text-center text-white">'. mb_substr($topic_user, 0, 1). '</span>';
					if (is_admin())
						$topic_user = '<a href="mailto:'. $topic_user_email. '">'. $topic_user. '</a>';
				}
				$article .=
				'<div class="media p-4 mb-4'. ($k & 1 ? '': ' bg-inherit'). '" id="re'. $k. '">'.
				'<div class="avatar text-center mr-4 small">'. $topic_user_avatar. '</div>'.
				'<div class=media-body>';
				if (is_admin() && $topic_str[1] !== $_SESSION['l'])
				{
					if ('#' !== $first_letter)
						$article .= '<a class="btn btn-sm btn-danger" href="'. $topic_url. '&amp;resdel='. $k. '">'. $btn[4]. '</a>';
					else
						$article .= '<a class="btn btn-sm btn-success" href="'. $topic_url. '&amp;resrepost='. $k. '">'. $btn[6]. '</a>';
				}
				$article .= '<small class="d-block mt-2">'. timeformat(ltrim($topic_str[0], '#'), $intervals). ' '. $topic_user. '</small>';
				if (isset($topic_str[3]))
				{
					$topic_ref = explode(',', $topic_str[3]);
					$end = end($topic_ref);
					$article .= '<p class=mt-3>';
					foreach ($topic_ref as $ref) $article .= '<a href="#re'. $ref. '">&gt;&gt;'. $ref. '</a>'. ($ref === $end ? '' : ', ');
					$article .= '</p>';
				}
				$article .= '<p class="wrap text-break mt-3">';
				if (is_admin() && '#' === $first_letter) $article .= '<del>'. hs($topic_str[2]). '</del>';
				elseif (!is_admin() && '#' === $first_letter) $article .=  str_repeat('*', mb_strlen($topic_str[2]));
				else $article .=  hs($topic_str[2]);
				$article .= '</p>'.
				'</div>';
				if ($count_lines <= $forum_limit)
					$article .=
					'<div class="custom-control custom-checkbox re" data-toggle=tooltip data-placement=left title="'. $forum_form[0]. '">'.
					'<input class="custom-control-input" type=checkbox name=ressid[] id="ressid'. $k. '" value="'. $k. '">'.
					'<label class="custom-control-label" for="ressid'. $k. '"></label>'.
					'</div>';
				$article .= '</div>';
			}
			if ($count_lines <= $forum_limit)
			{
				if (('@' === $forum_thread[0] || '@' === $forum_topic[0]) && !isset($_SESSION['l']))
					$article .= '<p class="alert alert-warning mt-5">'. $login_required[1]. '</p>';
				else
				{
					$article .= ('check' === filter_input(INPUT_GET, 'guest') ? '<div class="alert alert-success mt-5" id=email>'. $forum_guests[2]. '</div>' : '').
					'<fieldset class=mt-5>'. $n.
					(isset($_SESSION['l']) ?
						'<input name=resser type=hidden value="'. $_SESSION['l']. '">'
					:
						'<input class="form-control mb-3" name=resser id=resser placeholder="'. $placeholder[1]. '" type=email required>'
					). $n.
					'<textarea class="form-control mb-3" name=ress accesskey=q required rows=5 tabindex=0></textarea>'. $n.
					'<input class="btn btn-primary btn-lg btn-block" type=submit accesskey=c>'. $n.
					'</fieldset>'. $n;
				}
				$ress = !filter_has_var(INPUT_POST, 'ress') ? '' : trim(filter_input(INPUT_POST, 'ress', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
				$resser = !filter_has_var(INPUT_POST, 'resser') ? '' : filter_input(INPUT_POST, 'resser', FILTER_SANITIZE_EMAIL) ?? filter_input(INPUT_POST, 'resser', FILTER_SANITIZE_STRING);
				$ressid = !filter_has_var(INPUT_POST, 'ressid') ? '' : filter_input_array(INPUT_POST, ['ressid' => ['flags' => FILTER_REQUIRE_ARRAY, 'filter' => FILTER_SANITIZE_NUMBER_INT]]);
				if ($ress && $resser)
				{
					$ress = str_replace($line_breaks, '&#10;', $ress);
					$ress = trim($ress, "\\");
					if (filter_var($resser, FILTER_VALIDATE_EMAIL))
					{
						if (!filter_var($resser, FILTER_CALLBACK, ['options' => 'blacklist']))
						{
							$article .= $blacklist_alert;
							$footer .= '<script>$("#blacklist-alert").modal();</script>';
						}
						else
						{
							$resstime = $now;
							if (file_put_contents($tmpdir. $i. $delimiter. $resstime. '.txt', $resstime. ',"'. enc($resser). '","'. $ress. '"'. (!isset($ressid['ressid']) ? '' : ',"'. implode(',', $ressid['ressid']). '"'). $n, LOCK_EX))
							{
								$ress_limit = date($time_format, $resstime+$time_limit*60);
								$headers = $mime. 'From: '. $from. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;
								$subject = $forum_guests[0]. ' - '. $site_name;
								$body = sprintf($forum_guests[1], $ress_limit). $n. $topic_url. '&amp;r='. $i. '&amp;t='. $resstime. $n. $n. $separator. $n. $site_name. $n. $url;
								if (mail($resser, $subject, $body, $headers)) header('Location: '. $forum_url. '&guest=check#email');
							}
						}
					}
					elseif (is_dir($usersdir. $resser. '/prof/'))
					{
						file_put_contents('./forum/'. $forum_thread. '/'. $forum_topic, $now. ',"'. $resser. '","'. $ress. '"'. (!isset($ressid['ressid']) ? '' : ',"'. implode(',', $ressid['ressid']). '"'). $n, FILE_APPEND | LOCK_EX);
						counter($userdir. '/forum-ress.txt', 1);
						touch('./forum/'. $forum_thread, $now);
						exit (header('Location: '. $topic_url. '#re'. $i));
					}
				}
				$article .= '</form>';
			}
		}
	}
	else not_found();
}
elseif (!isset($v[0]) && $forum_thread)
{
	if (is_file($threader_file = './forum/'. $forum_thread. '/threader'))
	{
		$threader_name = file_get_contents($threader_file);
		if (is_dir($threader_profdir = $usersdir. $threader_name. '/prof/'))
			$threader_name = '<a href="'. $url. '?user='. str_rot13($threader_name). '">'. handle($threader_profdir). '</a>';
		else
		{
			$threader_name = filter_var($threader_email = dec($threader_name), FILTER_VALIDATE_EMAIL) ? explode('@', $threader_email)[0] : '';
			if (is_admin())
				$threader_name = '<a href="mailto:'. $threader_email. '">'. $threader_name. '</a>';
		}
		$article .=
		'<header class=mb-5>'.
		'<small class=text-muted>'. date($time_format, filemtime($threader_file)). ' '. $threader_name. '</small>'.
		'<h2 class=h3>'. $thread_title. '</h2>'.
		'</header>';
		if ('!' === $forum_thread[0] && !isset($_SESSION['l']))
		{
			if ($get_page || $get_title) $header .= '<title>'. $thread_title. ' - '. $site_name. '</title>'. $n;
			$breadcrumb .= '<li class="breadcrumb-item active"><a href="'. $forum_url. '">'. h($forum). '</a></li><li class="breadcrumb-item active">'. $thread_title. '</li>';
			$article .= '<p class="alert alert-danger mt-3">'. $login_required[0]. '</p>';
		}
		else
		{
			if ($get_page || $get_title) $header .= '<title>'. $thread_title. ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;
			$breadcrumb .=
			($pages > 1 ?
				'<li class=breadcrumb-item><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
				'<li class=breadcrumb-item><a href="'. $thread_url. '">'. $thread_title. '</a></li>'.
				'<li class="breadcrumb-item active">'. sprintf($page_prefix, $pages). '</li>'
			:
				'<li class="breadcrumb-item active"><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
				'<li class="breadcrumb-item active">'. $thread_title. '</li>'
			);
			if (is_admin())
			{
				if ($d = filter_input(INPUT_GET, 'topicdel', FILTER_SANITIZE_STRING))
					if (is_file('./forum/'. $forum_thread. '/'. $d)) if (rename('./forum/'. $forum_thread. '/'. $d, './forum/'. $forum_thread. '/#'. $d)) exit (header('Location: '. $thread_url));
				if ($r = filter_input(INPUT_GET, 'topicrepost', FILTER_SANITIZE_STRING))
					if (is_file('./forum/'. $forum_thread. '/#'. $r)) if (rename('./forum/'. $forum_thread. '/#'. $r, './forum/'. $forum_thread. '/'. $r)) exit (header('Location: '. $thread_url));
			}
			if (($guest = filter_input(INPUT_GET, 'g', FILTER_SANITIZE_STRING)) && ($unixtime = (int)filter_input(INPUT_GET, 't', FILTER_SANITIZE_NUMBER_INT)))
			{
				$guest_file = $tmpdir. $unixtime. $delimiter. str_rot13($guest);
				if (is_file($guest_file) && $now <= $unixtime + $time_limit * 60)
				{
					 $guest_content = str_getcsv($guest_str = file_get_contents($guest_file));
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
			if ($thread_topic_glob = array_filter(glob('./forum/'. $forum_thread. '/'. (is_admin() ? '*' : '[!#]*'). '[!threader]*', GLOB_NOSORT), 'is_file'))
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
					if (false !== strpos($thread_topic, '.')) continue;
					$topic_name = '#' !== $thread_topic[0] ? $thread_topic : substr($thread_topic, 1);
					$topic_name = ('!' !== $topic_name[0] && '@' !== $topic_name[0]) ? $topic_name : substr($topic_name, 1);
					$article .= '<li class="list-group-item '. (!($key & 1) ? ' bg-transparent' : ''). ' d-flex align-items-center">';
					if ('#' !== $thread_topic[0])
						$article .=
						(!is_admin() ? '' : '<a class="btn btn-sm btn-danger" href="'. $thread_url. '&amp;topicdel='. r($thread_topic). '">'. $btn[4]. '</a>').
						'<a class="flex-grow-1 ml-2 text-break" href="'. $thread_url. r($thread_topic). '">'. h($topic_name). '</a>';
					else
						$article .=
						(!is_admin() ? '' : '<a class="btn btn-sm btn-success" href="'. $thread_url. '&amp;topicrepost='. r(substr($thread_topic, 1)). '">'. $btn[6]. '</a>').
						'<del class="flex-grow-1 ml-2 text-break">'. h($topic_name). '</del>';
					$article .=
					'<span class="d-flex flex-column text-center">'.
					'<small class="bg-inherit px-3 py-2">'. timeformat(filemtime($thread_topics), $intervals). '</small>'.
					'<small class="bg-inherit px-3 py-2">'. $forum_title[2]. ' '. (count(file($thread_topics))-1). '</small>'.
					'</span>'.
					'</li>';
				}
				$article .= '</ul>';
				if ($count_topics > $forum_contents_per_page) pager($max_page, $page_ceil);
			}
			$topic = !filter_has_var(INPUT_POST, 'topic') ? '' : trim(str_replace($forum_disallow_symbols, $forum_replace_symbols, filter_input(INPUT_POST, 'topic')));
			$topicer = !filter_has_var(INPUT_POST, 'topicer') ? '' : filter_input(INPUT_POST, 'topicer', FILTER_SANITIZE_EMAIL) ?? trim(filter_input(INPUT_POST, 'topicer', FILTER_SANITIZE_STRING));
			if ($topic && $topicer)
			{
				if (!is_file($topicfile = './forum/'. $forum_thread. '/'. $topic) && !is_file('./forum/'. $forum_thread. '/#'. $topic))
				{
					if (filter_var($topicer, FILTER_VALIDATE_EMAIL))
					{
						if (!filter_var($topicer, FILTER_CALLBACK, ['options' => 'blacklist']))
						{
							$article .= $blacklist_alert;
							$footer .= '<script>$("#blacklist-alert").modal();</script>';
						}
						else
						{
							$topictime = $now;
							$enctopicer = enc($topicer);
							if (file_put_contents($tmpdir. $topictime. $delimiter. $enctopicer, $topictime. ',"'. $enctopicer. '","'. $topic. '"'. $n, LOCK_EX))
							{
								$topic_limit = date($time_format, $topictime + $time_limit * 60);
								$headers = $mime. 'From: '. $from. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;
								$subject = $forum_guests[0]. ' - '. $site_name;
								$body = sprintf($forum_guests[1], $topic_limit). $n. $thread_url. '&amp;g='. str_rot13($enctopicer). '&amp;t='. $topictime. $n. $n. $separator. $n. $site_name. $n. $url;
								if (mail($topicer, $subject, $body, $headers)) header('Location: '. $thread_url. '&guest=check#email');
							}
						}
					}
					elseif (is_dir($usersdir. $topicer. '/prof/'))
					{
						file_put_contents($topicfile, $now. ',"'. $topicer. '","'. $topic. '"'. $n, FILE_APPEND | LOCK_EX);
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
				('check' === filter_input(INPUT_GET, 'guest') ? '<div class="alert alert-success mt-5" id=email>'. $forum_guests[2]. '</div>' : '').
				'<form method=post class=mt-5>'.
				'<label for=topic>'. $forum_form[1]. ' <small class=text-muted id=max></small></label>'. $n.
				'<div class=input-group>'. $n.
				'<input class=form-control type=text name=topic id=topic accesskey=t required placeholder="'. $forum_form[2]. '">'. $n.
				(isset($_SESSION['l']) ?
					'<input type=hidden name=topicer value="'. $_SESSION['l']. '">'. $n
				:
					'<input class=form-control name=topicer id=topicer placeholder="'. $placeholder[1]. '" type=email required>'
				).
				'<div class=input-group-append><input class="btn btn-primary" type=submit accesskey=c></div>'. $n.
				'</div>'. $n.
				'</form>';
				$footer .= '<script>$("#topic").popover({html:true,trigger:"focus",placement:"bottom",title:"'. $forum_guests[3]. '",content:"'. $forum_guests[4]. '"});$("#topicer").popover({html:true,trigger:"focus",placement:"bottom",content:"'. $forum_guests[5]. '"});$("#topic").on("change keyup mouseup paste",function(){l=encodeURIComponent($(this).val()).replace(/%../g,"x").length,m=200;$("#max").text("'. sprintf($forum_form[5], '"+(m-l)+"'). '");if(l>m){$("#topic").addClass("is-invalid");$(":submit").prop("disabled",true)}else{$("#topic").removeClass("is-invalid");$(":submit").prop("disabled",false)}})</script>';
			}
			else
				$article .= '<p class="alert alert-warning mt-3">'. $forum_guests[6]. '</p>';
		}
	}
	else not_found();
}
elseif (!isset($v[0]))
{
	if ($get_page || $get_title) $header .= '<title>'. h($forum). ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;
	$breadcrumb .=
	($pages > 1 ?
		'<li class=breadcrumb-item><a href="'. $forum_url. '">'. h($forum). '</a></li><li class="breadcrumb-item active">'. sprintf($page_prefix, $pages). '</li>'
	:
		'<li class="breadcrumb-item active"><a href="'. $forum_url. '">'. h($forum). '</a></li>'
	);
	if ($forum_thread_glob = glob('./forum/'. (is_admin() ? '' : '[!#]'). '*', GLOB_NOSORT+GLOB_ONLYDIR))
	{
		if (is_admin())
		{
			if ($d = filter_input(INPUT_GET, 'del', FILTER_SANITIZE_STRING))
				if (is_dir('./forum/'. $d)) if (rename('./forum/'. $d, './forum/#'. $d)) exit (header('Location: '. $forum_url));
			if ($r = filter_input(INPUT_GET, 'repost', FILTER_SANITIZE_STRING))
				if (is_dir('./forum/#'. $r)) if (rename('./forum/#'. $r, './forum/'. $r)) exit (header('Location: '. $forum_url));
		}
		usort($forum_thread_glob, 'sort_time');
		$count_threads = count($forum_thread_glob);
		$page_ceil = ceil($count_threads / $forum_contents_per_page);
		$max_page = min($pages, $page_ceil);
		$sliced_threads = array_slice($forum_thread_glob, ($max_page - 1) * $forum_contents_per_page, $forum_contents_per_page);
		$article .=
		'<header class=mb-5>'.
		'<h2 class=h3>'. $forum. '</h2>'.
		'<small class="px-3 py-2 bg-inherit m-2">'. $forum_title[1]. ' <span class="badge badge-light">'. $count_threads. '</span></small>'.
		'<small class="px-3 py-2 bg-inherit m-2">'. $forum_title[0]. ' <span class="badge badge-light">'. count(array_filter(glob('forum/[!#]*/[!#]*[!threader]*', GLOB_NOSORT), 'is_file')). '</span></small>'.
		'</header>'.
		'<div class="'. $forum_wrapper_class. '">';
		if ($count_threads > $forum_contents_per_page) pager($max_page, $page_ceil);
		foreach ($sliced_threads as $key => $threads)
		{
			$thread_name = basename($threads);
			$thread_title = '#' === $thread_name[0] || '!' === $thread_name[0] || '@' === $thread_name[0] ?substr($thread_name, 1) : $thread_name;
			$threader_name = file_get_contents($threads. '/threader');
			if (is_dir($threader_profdir = $usersdir. $threader_name. '/prof/'))
				$threader_name = '<a href="'. $url. '?user='. str_rot13($threader_name). '">'. handle($threader_profdir). '</a>';
			else
			{
				$threader_name = filter_var($threader_email = dec($threader_name), FILTER_VALIDATE_EMAIL) ? explode('@', $threader_email)[0] : '';
				if (is_admin())
					$threader_name = '<a href="mailto:'. $threader_email. '">'. $threader_name. '</a>';
			}
			$article .=
			'<div class="row mb-5 p-3'. (!($key & 1) ? '' : ' bg-inherit'). '">'.
			'<div class="col-10">'.
			'<small class="d-block mb-3">'. timeformat(filemtime($threads), $intervals). ' '. $threader_name. '</small>';
			if ('#' !== $thread_name[0])
				$article .=
				(!is_admin() ? '' : '<a class="btn btn-sm btn-danger" href="'. $forum_url. '&amp;del='. r($thread_title). '">'. $btn[4]. '</a>').
				'<a class="h5 ml-2" href="'. $forum_url. '/'. r($thread_name). '/">'. h($thread_title). '</a>';
			else
				$article .=
				(!is_admin() ? '' : ' <a class="btn btn-sm btn-success" href="'. $forum_url. '&amp;repost='. r($thread_title). '">'. $btn[6]. '</a>').
				'<del class="h5 ml-2">'. h($thread_title). '</del>';
			$article .=
			'</div>'.
			'<div class="col-2 bg-inherit text-center text-break">'.
			'<div class="h6 mt-2">'.$forum_title[0]. '</div>'.
			'<span>'. count(array_filter(glob($threads. '/*[!threader]*', GLOB_NOSORT), 'is_file')). '</span>'.
			'</div>'.
			'</div>'. $n;
		}
		$article .= '</div>';
		if ($count_threads > $forum_contents_per_page) pager($max_page, $page_ceil);
	}
	if (($guest = filter_input(INPUT_GET, 'g', FILTER_SANITIZE_STRING)) && ($unixtime = (int)filter_input(INPUT_GET, 't', FILTER_SANITIZE_NUMBER_INT)))
	{
		$guest_file = $tmpdir. $unixtime. $delimiter. ($threader = str_rot13($guest));
		if (is_file($guest_file) && $now <= $unixtime + $time_limit * 60)
		{
			if (!is_dir($threaddir = './forum/'. ($thread_name = file_get_contents($guest_file)). '/'))
			{
				mkdir($threaddir, 0757);
				file_put_contents($threaddir. 'threader', $threader);
				unlink($guest_file);
				exit (header('Location: '. $forum_url. '/'. r($thread_name). '/'));
			}
		}
		else
			$article .= '<div class="alert alert-danger mb-4">'. $not_found[0]. '</div>';
		if (is_file($guest_file)) unlink($guest_file);
	}
	$thread = !filter_has_var(INPUT_POST, 'thread') ? '' : trim(str_replace($forum_disallow_symbols, $forum_replace_symbols, filter_input(INPUT_POST, 'thread')));
	$threader = !filter_has_var(INPUT_POST, 'threader') ? '' : filter_input(INPUT_POST, 'threader', FILTER_SANITIZE_EMAIL) ?? trim(filter_input(INPUT_POST, 'threader', FILTER_SANITIZE_STRING));
	if ($thread && $threader)
	{
		if (!is_dir($threaddir = './forum/'. $thread. '/'))
		{
			if (filter_var($threader, FILTER_VALIDATE_EMAIL))
			{
				if (!filter_var($threader, FILTER_CALLBACK, ['options' => 'blacklist']))
				{
					$article .= $blacklist_alert;
					$footer .= '<script>$("#blacklist-alert").modal();</script>';
				}
				else
				{
					$threadtime = $now;
					$encthreader = enc($threader);
					if (file_put_contents($tmpdir. $threadtime. $delimiter. $encthreader, $thread, LOCK_EX))
					{
						$thread_limit = date($time_format, $threadtime + $time_limit * 60);
						$headers = $mime. 'From: '. $from. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;
						$subject = $forum_guests[0]. ' - '. $site_name;
						$body = sprintf($forum_guests[1], $thread_limit). $n. $forum_url. '&amp;g='. str_rot13($encthreader). '&amp;t='. $threadtime. $n. $n. $separator. $n. $site_name. $n. $url;
						if (mail($threader, $subject, $body, $headers)) header('Location: '. $forum_url. '&guest=check#email');
					}
				}
			}
			elseif (is_dir($usersdir. $threader. '/prof/'))
			{
				mkdir($threaddir, 0757);
				file_put_contents($threaddir. 'threader', $threader);
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
		('check' === filter_input(INPUT_GET, 'guest') ? '<div class="alert alert-success my-4" id=email>'. $forum_guests[2]. '</div>' : '').
		'<form method=post>'.
		'<label for=thread>'. $forum_form[3]. ' <small class=text-muted id=max></small></label>'. $n.
		'<div class=input-group>'. $n.
		'<input class=form-control type=text name=thread id=thread accesskey=t required placeholder="'. $forum_form[4]. '">'. $n.
		(isset($_SESSION['l']) ?
			'<input type=hidden name=threader value="'. $_SESSION['l']. '">'. $n
		:
			'<input class=form-control name=threader id=threader placeholder="'. $placeholder[1]. '" type=email required>'
		).
		'<div class=input-group-append><input class="btn btn-primary" type=submit accesskey=c></div>'. $n.
		'</div>'. $n.
		'</form>';
		$footer .= '<script>$("#thread").popover({html:true,trigger:"focus",placement:"bottom",title:"'. $forum_guests[3]. '",content:"'. $forum_guests[4]. '"});$("#threader").popover({html:true,trigger:"focus",placement:"bottom",content:"'. $forum_guests[5]. '"});$("#thread").on("change keyup mouseup paste",function(){l=encodeURIComponent($(this).val()).replace(/%../g,"x").length,m=200;$("#max").text("'. sprintf($forum_form[5], '"+(m-l)+"'). '");if(l>m){$("#thread").addClass("is-invalid");$(":submit").prop("disabled",true)}else{$("#thread").removeClass("is-invalid");$(":submit").prop("disabled",false)}})</script>';
	}
	else
		$article .= '<p class="alert alert-warning mt-3">'. $forum_guests[6]. '</p>';
}
