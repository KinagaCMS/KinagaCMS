<?php
$forum_url = $url. r($forum);
if ($forum_thread = !filter_has_var(INPUT_GET, 'thread') ? '' : basename(filter_input(INPUT_GET, 'thread', FILTER_SANITIZE_STRING)))
{
	$thread_title = $forum_thread[0] === '!' || $forum_thread[0] === '@' ? h(substr($forum_thread, 1)) : h($forum_thread);
	$thread_url = $forum_url. '/'. r($forum_thread). '/';
}
if ($forum_topic = !filter_has_var(INPUT_GET, 'topic') ? '' : basename(filter_input(INPUT_GET, 'topic', FILTER_SANITIZE_STRING)))
{
	$topic_title = $forum_topic[0] === '!' || $forum_topic[0] === '@' ? h(substr($forum_topic, 1)) : h($forum_topic);
	$topic_url = $thread_url. r($forum_topic);
}
if ($forum_topic)
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
			if (isset($session_usermail) && $mail_address === $session_usermail)
				$topicer_name = '<a href="mailto:'. $topicer_email. '">'. $topicer_name. '</a>';
		}
		$header .= '<title>'. $topic_title. ' - '. $site_name. '</title>';
		$breadcrumb .=
		'<li class=breadcrumb-item><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
		'<li class=breadcrumb-item><a href="'. $thread_url. '">'. $thread_title. '</a></li>'.
		'<li class="breadcrumb-item active">'. $topic_title. '</li>';
		$article .=
		'<small class=text-muted>'. date($time_format, $topic_header[0]). ' '. $topicer_name. '</small>'.
		'<h2 class=h3>'. $topic_title. '</h2>';
		if (($forum_thread[0] === '!' || $forum_topic[0] === '!') && !isset($_SESSION['l']))
			$article .= '<p class="alert alert-danger mt-3">'. $login_required[0]. '</p>';
		else
		{
			$header .= '<style>.media:target{animation:3s target}@keyframes target{from{background:#ccc}to{background:inherit}</style>'. $n;
			$article .= '<form method=post>';
			$footer .= '<script>$(".re").tooltip({trigger:"hover"})'. (isset($_SESSION['l']) ? '' : ';$("#resser").popover({html:true,trigger:"focus",placement:"bottom",content:"'. $forum_guests[5]. '"})'). '</script>';
			if (isset($session_usermail) && $mail_address === $session_usermail)
			{
				if (filter_has_var(INPUT_GET, 'del') && ($d = (int)filter_input(INPUT_GET, 'del', FILTER_SANITIZE_NUMBER_INT)))
				{
					$topic_contents = str_replace($topic_lines[$d], '#'. $topic_lines[$d], file_get_contents($topic_file));
					file_put_contents($topic_file, $topic_contents, LOCK_EX);
					exit(header('Location: '. $topic_url));
				}
			}
			if (($reid = (int)filter_input(INPUT_GET, 'r', FILTER_SANITIZE_NUMBER_INT)) && ($unixtime = (int)filter_input(INPUT_GET, 't', FILTER_SANITIZE_NUMBER_INT)))
			{
				$guest_file = $tmpdir. $reid. $delimiter. $unixtime. '.txt';
				if (is_file($guest_file) && $unixtime + $time_limit * 60 >= $now)
				{
					if (file_put_contents($topic_file, file_get_contents($guest_file), FILE_APPEND | LOCK_EX))
					{
						touch('./forum/'. $forum_thread, $now);
						unlink($guest_file);
						exit(header('Location: '. $topic_url. '#re'. $reid));
					}
				}
				else
					$article .= '<div class="alert alert-danger my-4">'. $not_found[0]. '</div>';
				if (is_file($guest_file)) unlink($guest_file);
			}
			for ($i=1, $c=count($topic_lines); $i < $c; ++$i)
			{
				$topic_str = str_getcsv($topic_lines[$i]);
				if (is_dir($topic_user_profdir = $usersdir. $topic_str[1]. '/prof/'))
				{
					$topic_user = '<a href="'. $url. '?user='. str_rot13($topic_str[1]). '">'. handle($topic_user_profdir). '</a>';
					$topic_user_avatar = avatar($topic_user_profdir);
				}
				else
				{
					$topic_user = filter_var($topic_user_email = dec($topic_str[1]), FILTER_VALIDATE_EMAIL) ? explode('@', $topic_user_email)[0] : '';
					$topic_user_avatar =
					'<span class="avatar align-items-center bg-primary d-flex justify-content-center font-weight-bold display-3 mx-auto rounded text-center text-white">'. mb_substr($topic_user, 0, 1). '</span>';
					if (isset($session_usermail) && $mail_address === $session_usermail)
						$topic_user = '<a href="mailto:'. $topic_user_email. '">'. $topic_user. '</a>';
				}
				$article .=
				'<div class="media border-bottom py-3" id="re'. $i. '">'.
				'<div class="text-center px-4 small">'. date($time_format, ltrim($topic_str[0], '#')). $topic_user_avatar. $topic_user. '</div>'.
				'<div class=media-body>';
				if (isset($session_usermail, $_SESSION['l']) && $mail_address === $session_usermail && $topic_str[1] !== $_SESSION['l'])
					$article .= '<a class="d-block text-danger" href="'. $topic_url. '&amp;del='. $i. '">'. $prof_btn[2]. '</a>';
				if (isset($topic_str[3])) foreach (explode(',', $topic_str[3]) as $ref) $article .= '<a href="#re'. $ref. '">&gt;&gt;'. $ref. '</a> ';
				$article .=
				'<p class="wrap text-break">'. (substr($topic_lines[$i], 0, 1) !== '#' ? hs($topic_str[2]) : str_repeat('*', mb_strlen($topic_str[2], 'UTF8'))). '</p>'.
				'</div>'.
				'<div class="custom-control custom-checkbox re" data-toggle=tooltip data-placement=left title="'. $forum_form[0]. '">'.
				'<input class="custom-control-input" type=checkbox name=ressid[] id="ressid'. $i. '" value="'. $i. '">'.
				'<label class="custom-control-label" for="ressid'. $i. '"></label>'.
				'</div>'.
				'</div>';
			}
			if ($c <= $forum_limit)
			{
				if (($forum_thread[0] === '@' || $forum_topic[0] === '@') && !isset($_SESSION['l']))
					$article .= '<p class="alert alert-warning mt-3">'. $login_required[1]. '</p>';
				else
				{
					$article .= (filter_input(INPUT_GET, 'guest') === 'check' ? '<div class="alert alert-success mt-4" id=email>'. $forum_guests[2]. '</div>' : '').
					'<fieldset class=mt-4>'. $n.
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
							$article .= '<div class="alert alert-danger my-4">'. $not_found[0]. '</div>';
						else
						{
							$resstime = $now;
							if (file_put_contents($tmpdir. $i. $delimiter. $resstime. '.txt', $resstime. ',"'. enc($resser). '","'. $ress. '"'. (!isset($ressid['ressid']) ? '' : ',"'. implode(',', $ressid['ressid']). '"'). $n, LOCK_EX))
							{
								$ress_limit = date($time_format, $resstime+$time_limit*60);
								$headers = $mime. 'From: noreply@'. $server. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;
								$subject = $forum_guests[0]. ' - '. $site_name;
								$body = sprintf($forum_guests[1], $ress_limit). $n. $topic_url. '&amp;r='. $i. '&amp;t='. $resstime. $n. $n. $separator. $n. $site_name. $n. $url;
								if (mail($resser, $subject, $body, $headers)) header('Location: '. $topic_url. '&guest=check#email');
							}
						}
					}
					elseif (is_dir($usersdir. $resser. '/prof/'))
					{
						file_put_contents('./forum/'. $forum_thread. '/'. $forum_topic, $now. ',"'. $resser. '","'. $ress. '"'. (!isset($ressid['ressid']) ? '' : ',"'. implode(',', $ressid['ressid']). '"'). $n, FILE_APPEND | LOCK_EX);
						counter($userdir. '/forum-ress.txt', 1);
						touch('./forum/'. $forum_thread, $now);
						exit(header('Location: '. $topic_url. '#re'. $i));
					}
				}
			}
			$article .= '</form>';
		}
	}
	else
		not_found();
}
elseif ($forum_thread)
{
	if (is_file($threader_file = './forum/'. $forum_thread. '/threader'))
	{
		$threader_name = file_get_contents($threader_file);
		if (is_dir($threader_profdir = $usersdir. $threader_name. '/prof/'))
			$threader_name = '<a href="'. $url. '?user='. str_rot13($threader_name). '">'. handle($threader_profdir). '</a>';
		else
		{
			$threader_name = filter_var($threader_email = dec($threader_name), FILTER_VALIDATE_EMAIL) ? explode('@', $threader_email)[0] : '';
			if (isset($session_usermail) && $mail_address === $session_usermail)
				$threader_name = '<a href="mailto:'. $threader_email. '">'. $threader_name. '</a>';
		}
		$article .=
		'<small class=text-muted>'. date($time_format, filemtime($threader_file)). ' '. $threader_name. '</small>'.
		'<h2 class=h3>'. $thread_title. '</h2>';
		if ($forum_thread[0] === '!' && !isset($_SESSION['l']))
		{
			$header .= '<title>'. $thread_title. ' - '. $site_name. '</title>'. $n;
			$breadcrumb .= '<li class="breadcrumb-item active"><a href="'. $forum_url. '">'. h($forum). '</a></li><li class="breadcrumb-item active">'. $thread_title. '</li>';
			$article .= '<p class="alert alert-danger mt-3">'. $login_required[0]. '</p>';
		}
		else
		{
			$header .= '<title>'. $thread_title. ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;
			$breadcrumb .=
			($pages > 1 ?
				'<li class=breadcrumb-item><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
				'<li class=breadcrumb-item><a href="'. $thread_url. '">'. $thread_title. '</a></li>'.
				'<li class="breadcrumb-item active">'. sprintf($page_prefix, $pages). '</li>'
			:
				'<li class="breadcrumb-item active"><a href="'. $forum_url. '">'. h($forum). '</a></li>'.
				'<li class="breadcrumb-item active">'. $thread_title. '</li>'
			);
			if (isset($session_usermail) && $mail_address === $session_usermail)
			{
				if ($d = filter_input(INPUT_GET, 'del', FILTER_SANITIZE_STRING))
				{
					if (is_file('./forum/'. $forum_thread. '/'. $d)) rename('./forum/'. $forum_thread. '/'. $d, './forum/'. $forum_thread. '/#'. $d);
					exit(header('Location: '. $thread_url));
				}
			}
			if (($guest = filter_input(INPUT_GET, 'g', FILTER_SANITIZE_STRING)) && ($unixtime = (int)filter_input(INPUT_GET, 't', FILTER_SANITIZE_NUMBER_INT)))
			{
				$guest_file = $tmpdir. $unixtime. $delimiter. str_rot13($guest);
				if (is_file($guest_file) && $unixtime + $time_limit * 60 >= $now)
				{
					 $guest_content = str_getcsv($guest_str = file_get_contents($guest_file));
					 if (isset($guest_content[2]) && !is_file($guest_topic = './forum/'. $forum_thread. '/'. $guest_content[2]))
					 {
						file_put_contents($guest_topic, $guest_str, LOCK_EX);
						touch('./forum/'. $forum_thread, $now);
						unlink($guest_file);
						exit(header('Location: '. $thread_url. r($guest_content[2])));
					}
				}
				else
					$article .= '<div class="alert alert-danger my-4">'. $not_found[0]. '</div>';
				if (is_file($guest_file)) unlink($guest_file);
			}
			if ($thread_topic_glob = glob('./forum/'. $forum_thread. '/[!#]*[!threader]*', GLOB_NOSORT))
			{
				usort($thread_topic_glob, 'sort_time');
				$count_topics = count($thread_topic_glob);
				$page_ceil = ceil($count_topics / $forum_contents_per_page);
				$max_page = min($pages, $page_ceil);
				$sliced_topics = array_slice($thread_topic_glob, ($max_page - 1) * $forum_contents_per_page, $forum_contents_per_page);
				$article .= '<ul class="list-group list-group-flush mb-5">';
				foreach ($sliced_topics as $thread_topics)
				{
					$thread_topic = basename($thread_topics);
					$topic_name = $thread_topic[0] === '!' || $thread_topic[0] === '@' ? h(substr($thread_topic, 1)) : h($thread_topic);

					$thread_url = $forum_url. '/'. r(basename(dirname($thread_topics))). '/';
					$article .= '<li class="bg-transparent list-group-item">';
					if (isset($session_usermail) && $mail_address === $session_usermail)
						$article .= '<a class="d-block text-danger" href="'. $thread_url. '&amp;del='. r($thread_topic). '">'. $prof_btn[2]. '</a>';
					$article .= '<a class="d-flex justify-content-between align-items-center text-break" href="'. $thread_url. r($thread_topic). '">'.
					$topic_name.
					'<small class="badge badge-success badge-pill">'.
					sprintf($hitcount, (count(file($thread_topics))-1)).
					'</small></a></li>';
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
							$article .= '<div class="alert alert-danger my-4">'. $not_found[0]. '</div>';
						else
						{
							$topictime = $now;
							$enctopicer = enc($topicer);
							if (file_put_contents($tmpdir. $topictime. $delimiter. $enctopicer, $topictime. ',"'. $enctopicer. '","'. $topic. '"'. $n, LOCK_EX))
							{
								$topic_limit = date($time_format, $topictime + $time_limit * 60);
								$headers = $mime. 'From: noreply@'. $server. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;
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
						exit(header('Location: '. $thread_url. r($topic)));
					}
				}
				else
					$article .= '<div class="alert alert-danger">'. $not_found[0]. '</div>';
			}
			if ($allow_guest_creates || isset($_SESSION['l']))
			{
				$article .=
				(filter_input(INPUT_GET, 'guest') === 'check' ? '<div class="alert alert-success mt-4" id=email>'. $forum_guests[2]. '</div>' : '').
				'<form method=post class=mt-4>'.
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
	else
		not_found();
}
else
{
	$header .= '<title>'. h($forum). ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;
	$breadcrumb .=
	($pages > 1 ?
		'<li class=breadcrumb-item><a href="'. $forum_url. '">'. h($forum). '</a></li><li class="breadcrumb-item active">'. sprintf($page_prefix, $pages). '</li>'
	:
		'<li class="breadcrumb-item active"><a href="'. $forum_url. '">'. h($forum). '</a></li>'
	);
	if ($forum_thread_glob = glob('./forum/[!#]*', GLOB_NOSORT+GLOB_ONLYDIR))
	{
		usort($forum_thread_glob, 'sort_time');
		$count_threads = count($forum_thread_glob);
		$page_ceil = ceil($count_threads / $forum_contents_per_page);
		$max_page = min($pages, $page_ceil);
		$sliced_threads = array_slice($forum_thread_glob, ($max_page - 1) * $forum_contents_per_page, $forum_contents_per_page);
		$article .= '<div class="card-columns mb-5">';
		if (isset($session_usermail) && $mail_address === $session_usermail)
		{
			if ($d = filter_input(INPUT_GET, 'del', FILTER_SANITIZE_STRING))
			{
				if (is_dir('./forum/'. $d)) rename('./forum/'. $d, './forum/#'. $d);
				exit(header('Location: '. $forum_url));
			}
		}
		foreach ($sliced_threads as $threads)
		{
			$thread_name = basename($threads);
			$thread_title = $thread_name[0] === '!' || $thread_name[0] === '@' ? h(substr($thread_name, 1)) : h($thread_name);
			$threader_name = file_get_contents($threads. '/threader');
			if (is_dir($threader_profdir = $usersdir. $threader_name. '/prof/'))
				$threader_name = '<a href="'. $url. '?user='. str_rot13($threader_name). '">'. handle($threader_profdir). '</a>';
			else
			{
				$threader_name = filter_var($threader_email = dec($threader_name), FILTER_VALIDATE_EMAIL) ? explode('@', $threader_email)[0] : '';
				if (isset($session_usermail) && $mail_address === $session_usermail)
					$threader_name = '<a href="mailto:'. $threader_email. '">'. $threader_name. '</a>';
			}
			$article .=
			'<div class=card>'.
			'<div class="card-header">'.
			'<h2 class="card-title border-0 h5">'.
			'<a class=text-secondary href="'. $forum_url. '/'. r($thread_name). '/">'. $thread_title. '</a>'.
			(isset($session_usermail, $_SESSION['l']) && $mail_address === $session_usermail ?
				'<a class="float-right text-danger" href="'. $forum_url. '&amp;del='. r($thread_name). '">'. $prof_btn[2]. '</a>' : '').
			'</h2>'. $n.
			'<small class="card-subtitle d-block text-right">'. date($time_format, filemtime($threads)). ' '. $threader_name. '</small>'.
			'</div>';
			if ($glob_topics = glob($threads. '/[!#]*[!threader]*'))
			{
				usort($glob_topics, 'sort_time');
				$article .= '<ul class="list-group list-group-flush">';
				for ($i=0, $c=count($glob_topics); $i < $c; ++$i)
				{
					if ($i === $number_of_topics) break;
					$topic_basename = basename($glob_topics[$i]);
					$topic_basetitle = $topic_basename[0] === '!' || $topic_basename[0] === '@' ? h(substr($topic_basename, 1)) : h($topic_basename);
					$article .=
					'<li class="bg-transparent list-group-item">'.
					'<a class="d-flex justify-content-between align-items-center text-break" href="'. $forum_url. '/'. r($thread_name). '/'. r($topic_basename). '">'.
					$topic_basetitle.
					'<small class="badge badge-dark badge-pill">'. timeformat(filemtime($glob_topics[$i]), $intervals). '</small>'.
					'</a></li>';
				}
				$article .= '</ul>';
			}
			$article .= '</div>'. $n;
		}
		$article .= '</div>';
		if ($count_threads > $forum_contents_per_page) pager($max_page, $page_ceil);
	}
	if (($guest = filter_input(INPUT_GET, 'g', FILTER_SANITIZE_STRING)) && ($unixtime = (int)filter_input(INPUT_GET, 't', FILTER_SANITIZE_NUMBER_INT)))
	{
		$guest_file = $tmpdir. $unixtime. $delimiter. ($threader = str_rot13($guest));
		if (is_file($guest_file) && $unixtime + $time_limit * 60 >= $now)
		{
			if (!is_dir($threaddir = './forum/'. ($thread_name = file_get_contents($guest_file)). '/'))
			{
				mkdir($threaddir, 0757);
				file_put_contents($threaddir. 'threader', $threader);
				unlink($guest_file);
				exit(header('Location: '. $forum_url. '/'. r($thread_name). '/'));
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
					$article .= '<div class="alert alert-danger my-4">'. $not_found[0]. '</div>';
				else
				{
					$threadtime = $now;
					$encthreader = enc($threader);
					if (file_put_contents($tmpdir. $threadtime. $delimiter. $encthreader, $thread, LOCK_EX))
					{
						$thread_limit = date($time_format, $threadtime + $time_limit * 60);
						$headers = $mime. 'From: noreply@'. $server. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;
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
				exit(header('Location: '. $forum_url. '/'. r($thread). '/'));
			}
		}
		else
			$article .= '<div class="alert alert-danger mb-4">'. $not_found[0]. '</div>';
	}
	if ($allow_guest_creates || isset($_SESSION['l']))
	{
		$article .=
		(filter_input(INPUT_GET, 'guest') === 'check' ? '<div class="alert alert-success my-4" id=email>'. $forum_guests[2]. '</div>' : '').
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
