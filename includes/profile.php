<?php
if (!filter_has_var(INPUT_GET, 'user')) exit;
if (is_dir($user_profdir = 'users/'. ($userstr = str_rot13($user)). '/prof/') && is_permitted('users/'. $userstr))
{
	$myintrod = $user_profdir. 'myintrod';
	$username = handle($user_profdir);
	$avatar = $user_profdir. 'avatar';
	$donotapproach = $user_profdir. 'donotapproach';
	$user_prof_title = sprintf($prof_title[2], $username);

	$header .= '<title>'. $user_prof_title. ' - '. $site_name. '</title>';
	$breadcrumb .= '<li class="breadcrumb-item active"><a href="'. $url. '?user='. $user. '">'. $user_prof_title. '</a></li>';

	if (isset($_SESSION['l']) && $userstr === $_SESSION['l'])
	{
		$article .= '<h2 class=h4>'. $prof_title[0]. '</h2>';

		if (filter_has_var(INPUT_POST, 'p'))
		{
			if (filter_has_var(INPUT_POST, 'h'))
				file_put_contents($handle, filter_input(INPUT_POST, 'h', FILTER_CALLBACK, ['options' => 'strip_tags']), LOCK_EX);
			if (filter_has_var(INPUT_POST, 'j'))
				file_put_contents($myintrod, filter_input(INPUT_POST, 'j', FILTER_CALLBACK, ['options' => 'strip_tags']), LOCK_EX);
			if (filter_has_var(INPUT_POST, 'd'))
			{
				if (is_file($avatar)) unlink($avatar);
				if (is_file($bgcolor)) unlink($bgcolor);
			}

			if (false === filter_input(INPUT_POST, 'o', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))
			{
				if (!is_file($donotapproach)) file_put_contents($donotapproach, '');
			}
			else
			{
				if (is_file($donotapproach)) unlink($donotapproach);
			}

			if (isset($_FILES['a']['error'], $_FILES['a']['name'], $_FILES['a']['tmp_name']) && UPLOAD_ERR_OK === $_FILES['a']['error'])
			{
				if (list($width, $height) = getimagesize($_FILES['a']['tmp_name']))
				{
					$min_width = $min_height = 100;
					if ($width === $height)
					{
						$new_width = $min_width;
						$new_height = $min_height;
					}
					else if ($width > $height)
					{
						$new_width = $min_width;
						$new_height = $height * ($min_width/$width);
					}
					else if ($width < $height)
					{
						$new_width = $width * ($min_height/$height);
						$new_height = $min_height;
					}
					$thumb = imagecreatetruecolor($new_width, $new_height);

					if (IMAGETYPE_JPEG === exif_imagetype($_FILES['a']['tmp_name']))
					{
						$source = imagecreatefromjpeg($_FILES['a']['tmp_name']);
						imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
						ob_start();
						imagejpeg($thumb);
						$content = ob_get_contents();
						ob_end_clean();
						imagedestroy($thumb);
						file_put_contents($avatar, 'data:image/jpeg;base64,'. ltrim(base64_encode($content), '='), LOCK_EX);
					}
					elseif (IMAGETYPE_PNG === exif_imagetype($_FILES['a']['tmp_name']))
					{
						$source = imagecreatefrompng($_FILES['a']['tmp_name']);
						imagealphablending($thumb, false);
						imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
						imagesavealpha($thumb, true);
						ob_start();
						imagepng($thumb);
						$content = ob_get_contents();
						ob_end_clean();
						imagedestroy($thumb);
						file_put_contents($avatar, 'data:image/png;base64,'. ltrim(base64_encode($content), '='), LOCK_EX);
					}
				}
			}
			header('Location: '. $url. '?user='. $user);
		}
		$article .=
		'<form enctype="multipart/form-data" method=post class=mb-5>'.
		'<fieldset class="form-group my-4">'.
		'<label for=h class=h5>'. $prof_label[0]. '</label>'.
		'<input class=form-control type=text id=h name=h accesskey=h maxlength='. $title_length. ' title="'. $prof_note[0]. '" value="'. $username. '">'.
		'<small class="form-text text-muted">'. $prof_note[1]. '</small>'.
		'</fieldset>'.
		'<fieldset class="form-group mb-4">'.
		'<label for=a class=h5>'. $prof_label[1]. '</label>';

		if (is_file($avatar) || is_file($bgcolor))
		{
			$article .=
			'<div class="form-check mb-2 me-sm-2">'.
			'<input type=checkbox name=d class=form-check-input id=d>'.
			'<label class=form-check-label for=d>'. $prof_label[2]. '</label>'.
			'</div>';
		}
		$article .=
		'<div class=d-flex>'.
		'<div class="d-table text-center">'. avatar($user_profdir). '</div>'.
		'<div class="ms-3">'.
		'<input type=hidden name=MAX_FILE_SIZE value=100000>'.
		'<input id=a name=a type=file accesskey=a accept="image/jpeg,image/png">'.
		'<small class="form-text text-muted">'. $prof_note[2]. '</small>'.
		'</div>'.
		'</div>'.
		'</fieldset>'.
		'<fieldset class="form-group mb-4">'.
		'<label for=j class=h5>'. $prof_label[3]. '</label>'.
		'<textarea class=form-control id=j name=j accesskey=j>'.
		(is_file($myintrod) && filesize($myintrod) ? h(trim(file_get_contents($myintrod))) : '').
		'</textarea>'.
		'<small class="form-text text-muted">'. $prof_note[3]. '</small>'.
		'</fieldset>'.
		'<fieldset class="form-group mb-4">'.
		'<label for=o class=h5>'. $prof_label[4]. '</label>'.
		'<div class=form-check>'.
		'<input type=checkbox class=form-check-input id=o name=o'. (is_file($donotapproach) ? '' : ' checked'). '>'.
		'<label class=form-check-label for=o>'. $prof_label[5]. '</label>'.
		'</div>'.
		'</fieldset>'.
		'<div class=text-center>'.
		'<input class="btn btn-primary btn-lg mb-2" id=p name=p type=submit accesskey=p value="'. $btn[2]. '">'.
		'</div>'.
		'</form>';

		if (!is_file($donotapproach) && is_dir($approach = 'users/'. $userstr. '/approach/') && $glob_approach = glob($approach. '[!!]*', GLOB_NOSORT))
		{
			if ($acceptable_arr = filter_input(INPUT_POST, 'acceptable', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY))
			{
				foreach ($acceptable_arr as $key => $val)
				{
					$skey = substr($key, 0, 1);
					$lkey = ltrim($key, '@#');
					$to = dec($lkey);
					$headers = $mime. 'From: '. $from. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;

					if ('on' === $val && ('@' === $skey || '#' === $skey))
					{
						if (!is_file($approach. $lkey))
						{
							rename($approach. $key, $approach. $lkey);
							$subject = sprintf($approach_subject[0], $username). ' - '. $site_name;
							$body = sprintf($approach_body[0], $username). $n. $url. '?user='. $user. $n. $n.
							$separator. $n. $site_name. $n. $url;
							mail($to, $subject, $body, $headers);
						}
					}
					if ('off' === $val && '#' !== $skey)
					{
						rename($approach. $key, $approach. '#'. $lkey);
						$subject = sprintf($approach_subject[1], $username). ' - '. $site_name;
						$body = sprintf($approach_body[1], $username). $n. $url. '?user='. $useraddr. $n. $n.
						$separator. $n. $site_name. $n. $url;
						mail($to, $subject, $body, $headers);
					}
					if ('del' === $val && '!' !== $skey)
					{
						rename($approach. $key, $approach. '!'. $lkey);
						$subject = sprintf($approach_subject[2], $username). ' - '. $site_name;
						$body = sprintf($approach_body[2], $username). $n. $n.
						$separator. $n. $site_name. $n. $url;
						mail($to, $subject, $body, $headers);
					}
				}
				header('Location: '. $url. '?user='. $user. '#approval');
			}
			$q = !filter_has_var(INPUT_GET, 'q') ? 1 : (int)filter_input(INPUT_GET, 'q', FILTER_SANITIZE_NUMBER_INT);
			$article .=
			'<h3 id=approval>'. $prof_title[3]. ' <small>'. ($q > 1 ? sprintf($page_prefix, $q) : ''). '</small></h2>'.
			'<p class=my-4>'. sprintf($approach_info[0], $username). '</p>';

			$count_glob_approach = count($glob_approach);
			$mx = ceil($count_glob_approach/$users_per_page);
			if ($q > $mx) $p = $mx;

			$sliced_glob_approachers = array_slice($glob_approach, ($q-1) * $users_per_page, $users_per_page);

			if ($count_glob_approach >= $users_per_page)
			{
				$article .=
				'<ul class="pagination justify-content-center">'.
				'<li class="page-item'. ($q >= 2 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;q=1#approval">'. $first. '</a></li>'.
				'<li class="page-item'. ($q > 1 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;q='. ($q - 1). '#approval">'. $prev. '</a></li>'.
				'<li class="page-item'. ($q < $mx ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;q='. ($q + 1). '#approval">'. $next. '</a></li>'.
				'<li class="page-item'. ($q <= $mx-1 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;q='. $mx. '#approval">'. $last. '</a></li>'.
				'</ul>';
			}
			$article .=
			'<form method=post action="'. $url. '?user='. $user. '" class=my-5 id=approachers>'.
			'<div class=row>';
			foreach ($sliced_glob_approachers as $ga)
			{
				$approacher_message = !filesize($ga) ? '' : h(trim(file_get_contents($ga)));
				$bga = basename($ga);
				$lbga = ltrim(basename($ga), '@#');
				$sbga = str_rot13($lbga);

				if (is_dir($approacher_profdir = 'users/'. $lbga. '/prof/'))
				{
					$approacher_handle =handle($approacher_profdir);
					$approacher_avatar = avatar($approacher_profdir);
					$acceptable = substr($bga, 0, 1);
					$article .=
					'<div class="col mb-5">'.
					'<div class="d-flex position-relative"'. (!$approacher_message ? '' : ' onmouseover="n=new bootstrap.Tooltip(this.childNodes[0]);n.show()" onmouseout="n.hide()"'). '>'.
					'<div class="text-center me-4"'. (!$approacher_message ? '' : ' title="'. $approacher_message. '"'). '>'. $approacher_avatar. '</div>'.
					'<div class=card-arrow>'.
					'<div class="card p-3">'.
					'<a class=card-title href="'. $url. '?user='. $sbga. '">'. $approacher_handle. '</a>'.
					'<div class=btn-group role=group>'.
					'<input type=radio class=btn-check id="b['. $bga. ']" name="acceptable['. $bga. ']"'. ($acceptable === '#' && $acceptable !== '@' ? ' checked' : ''). ' value=off>'.
					'<label class="btn btn-danger'. ('#' === $acceptable && '@' !== $acceptable ? ' active' : ''). '" for="b['. $bga. ']">'. $btn[3]. '</label>'.
					'<input type=radio class=btn-check id="d['. $bga. ']" name="acceptable['. $bga. ']" value=del>'.
					'<label class="btn btn-secondary" for="d['. $bga. ']">'. $btn[4]. '</label>'.
					'<input type=radio class=btn-check id="a['. $bga. ']" name="acceptable['. $bga. ']"'. ($acceptable === '@' || $acceptable === '#' ? '' : ' checked'). ' value=on>'.
					'<label class="btn btn-'. ('@' === $acceptable || '#' === $acceptable ? 'info' : 'success active'). '" for="a['. $bga. ']">'. $btn[5]. '</label>'.
					'</div>'.
					'</div>'.
					'</div>'.
					'</div>'.
					'</div>';
				}
			}
			$article .=
			'</div>'.
			'<div class=text-center><input type=submit value="'. $btn[2]. '" class="btn btn-primary btn-lg" id=approachers-submit disabled></div>'.
			'</form>';
			$javascript .= 'document.getElementById("approachers").addEventListener("change",()=>document.getElementById("approachers-submit").removeAttribute("disabled"));';
		}
		if (is_admin() || is_subadmin())
		{
			if ($glob_userdir = glob('users/*/log/', GLOB_NOSORT+GLOB_ONLYDIR))
			{
				$p = !filter_has_var(INPUT_GET, 'p') ? 1 : (int)filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
				$article .= '<h3 id=users>'. $prof_title[4]. (1 < $p ? ' <small>'. sprintf($page_prefix, $p) .'</small>': ''). '</h2>';
				usort($glob_userdir, 'sort_time');
				$count_glob_userdir = count($glob_userdir);
				$mx = ceil($count_glob_userdir/$users_per_page);
				if ($p > $mx) $p = $mx;

				$sliced_glob_users = array_slice($glob_userdir, ($p-1) * $users_per_page, $users_per_page);

				if (filter_has_var(INPUT_POST, 'user') && is_dir($permit_user = 'users/'. filter_input(INPUT_POST, 'user', FILTER_CALLBACK, ['options' => 'strip_tags_basename'])))
				{
					if (filter_has_var(INPUT_POST, 'val'))
					{
						if (filter_input(INPUT_POST, 'val', FILTER_VALIDATE_BOOLEAN))
							chmod($permit_user, 0755);
						else
							chmod($permit_user, 0700);
					}
					if (is_admin() && filter_has_var(INPUT_POST, 'add'))
					{
						if (filter_var(dec($post_userstr = !filter_has_var(INPUT_POST, 'user') ? '' : filter_input(INPUT_POST, 'user', FILTER_CALLBACK, ['options' => 'strip_tags_basename'])), FILTER_VALIDATE_EMAIL))
						{
							$subadmin_txt = 'users/'. $post_userstr. '/prof/subadmin';
							if (filter_input(INPUT_POST, 'add', FILTER_VALIDATE_BOOLEAN))
								touch($subadmin_txt);
							else
								unlink($subadmin_txt);
						}
					}
				}
				$article .=
				'<p>'. $users_info[0]. '</p>'.
				'<div class=row>';
				foreach ($sliced_glob_users as $current_user)
				{
					if ($current_user === $userdir. '/log/' || enc($mail_address) === basename(dirname($current_user))) continue;

					$current_userdir = dirname($current_user);
					$users_lastlogin = filemtime($current_user);
					$user_handle = handle($current_userdir. '/prof/');
					$user_avatar = avatar($current_userdir. '/prof/');
					$user_mail = dec($base_current_userdir = basename($current_userdir));
					$user_addr = str_rot13($base_current_userdir);
					$permitted = is_permitted($current_userdir);

					$article .=
					'<div class="col mb-5">'.
					'<div class="user list-group'. ($permitted ? '' : ' banned'). '" id="'. $base_current_userdir. '">'.
					'<div class="list-group-item text-center">'.
					$user_avatar.
					'<h5><a class=text-dark href="?user='. $user_addr. '" title="'. $base_current_userdir. '">'. $user_handle. '</a></h5>'.
					(!is_admin() ? '' : '<a class=text-dark href="mailto:'. $user_mail. '">'. $user_mail. '</a>').
					'</div>'.
					'<div class="list-group-item d-flex justify-content-between align-items-center">'.
					'<h6>'. $users_info[1]. '</h6>'.
					'<div class=form-switch>'.
					'<input type=checkbox class=form-check-input id="l'. $base_current_userdir. '" onchange="permit(this)"'. ($permitted ? ' checked' : ''). '>'.
					'<label class=form-check-label for="l'. $base_current_userdir. '"></label>'.
					'</div>'.
					'</div>'.
					(!is_admin() ? '' :
					'<div class="list-group-item d-flex justify-content-between align-items-center">'.
					'<h6>'. $users_info[2]. '</h6>'.
					'<div class=form-switch>'.
					'<input type=checkbox class=form-check-input id="s'. $base_current_userdir. '" onchange="subadmin(this)"'. (2 === is_subadmin($base_current_userdir) ? ' checked' : ''). '>'.
					'<label class="form-check-label" for="s'. $base_current_userdir. '"></label>'.
					'</div>'.
					'</div>').
					'<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[0]. '</h6>'. date($time_format, $users_lastlogin). '</div>'.
					(is_file($logged_in = $current_userdir. '/logged-in.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[1]. '</h6>'. (int)file_get_contents($logged_in). '</div>' : '').
					(is_file($approached = $current_userdir. '/approached.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[2]. '</h6>'. (int)file_get_contents($approached). '</div>' : '').
					(is_file($disapproached = $current_userdir. '/disapproached.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[3]. '</h6>'. (int)file_get_contents($disapproached). '</div>' : '').
					(is_file($message_success = $current_userdir. '/message-success.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[4]. '</h6>'. (int)file_get_contents($message_success). '</div>' : '').
					(is_file($message_error = $current_userdir. '/message-error.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[5]. '</h6>'. (int)file_get_contents($message_error). '</div>' : '').
					(is_file($created_categ = $current_userdir. '/create-categ.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[14]. '</h6>'. (int)file_get_contents($created_categ). '</div>' : '').
					(is_file($created_article = $current_userdir. '/create-article.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[15]. '</h6>'. (int)file_get_contents($created_article). '</div>' : '').
					(is_file($created_sidepage = $current_userdir. '/create-sidepage.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[16]. '</h6>'. (int)file_get_contents($created_sidepage). '</div>' : '').
					(is_file($comment_success = $current_userdir. '/comment-success.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[6]. '</h6>'. (int)file_get_contents($comment_success). '</div>' : '').
					(is_file($comment_error = $current_userdir. '/comment-error.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[7]. '</h6>'. (int)file_get_contents($comment_error). '</div>' : '').
					(is_file($contact_success = $current_userdir. '/contact-success.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[8]. '</h6>'. (int)file_get_contents($contact_success). '</div>' : '').
					(is_file($contact_error = $current_userdir. '/contact-error.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[9]. '</h6>'. (int)file_get_contents($contact_error). '</div>' : '').
					(is_file($forum_thread_count = $current_userdir. '/forum-thread.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[10]. '</h6>'. (int)file_get_contents($forum_thread_count). '</div>' : '').
					(is_file($forum_topic_count = $current_userdir. '/forum-topic.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[11]. '</h6>'. (int)file_get_contents($forum_topic_count). '</div>' : '').
					(is_file($forum_ress_count = $current_userdir. '/forum-ress.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[12]. '</h6>'. (int)file_get_contents($forum_ress_count). '</div>' : '').
					(is_dir($forum_upload_dir = $current_userdir. '/upload/') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[13]. '</h6>'. (int)count(glob($forum_upload_dir, GLOB_NOSORT)). '</div>' : '').
					'</div>'.
					'</div>';
				}
				$article .= '</div>';
				$javascript .= 'function permit(e){let f=e.closest(".user");f.classList.toggle("banned");const fd=new FormData();fd.append("user",f.id);fd.append("val",(e.checked?true:false));fetch("'. $current_url. '",{method:"POST",cache:"no-cache",body:fd})}function subadmin(e){const fd=new FormData();fd.append("user",e.closest(".user").id);fd.append("add",(e.checked?true:false));fetch("'. $current_url. '",{method:"POST",cache:"no-cache",body:fd})}';

				if ($count_glob_userdir >= $users_per_page)
				{
					$article .=
					'<ul class="pagination justify-content-center mb-5">'.
					'<li class="page-item'. ($p >= 2 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;p=1#users">'. $first. '</a></li>'.
					'<li class="page-item'. ($p > 1 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;p='. ($p - 1). '#users">'. $prev. '</a></li>'.
					'<li class="page-item'. ($p < $mx ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;p='. ($p + 1). '#users">'. $next. '</a></li>'.
					'<li class="page-item'. ($p <= $mx-1 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;p='. $mx. '#users">'. $last. '</a></li>'.
					'</ul>';
				}
			}
		}
		if (isset($logdir) && $glogs = glob($logdir. '*', GLOB_NOSORT))
		{
			$article .= '<h2 class=h4>'. $prof_title[5]. '</h2>';
			usort($glogs, 'sort_time');
			$article .= '<ul class="list-group my-4">';
			foreach ($glogs as $key => $glog)
			{
				if (10 < $key)
					unlink($glog);
				else
				{
					$log = explode($delimiter, file_get_contents($glog));
					$article .= '<li class="list-group-item">'. date('Y-m-d H:i:s', basename($glog)). ' '. h($log[0]). ' '. h($log[1]).'</li>';
				}
			}
			$article .= '</ul>';
		}
	}
	else
	{
		$article .=
		'<section class=mb-5><h2 class=h4>'. $user_prof_title. '</h2>'.
		'<div class="d-flex my-5 position-relative">'.
		'<div class="d-table me-4 rounded-circle text-center">'. avatar($user_profdir). '</div>'.
		'<div class=card-arrow></div>'.
		'<div class="card w-100">'.
		'<div class="card-body wrap">';

		if (file_exists($myintrod) && filesize($myintrod))
			$article .= h(trim(file_get_contents($myintrod)));
		else
			$article .= $prof_title[1];

		$article .=
		'</div>'.
		'</div>'.
		'</div>'.
		'</section>';

		if ($use_user_achievements)
		{
			$achievements = false;
			$current_userdir = dirname($user_profdir);
			$article .= '<section class=my-5><h2 class=h4 id=achievements>'. sprintf($prof_title[7], $username). '</h2>';
			if ($glob_authors = glob($glob_dir. 'author.txt', GLOB_NOSORT))
			{
				usort($glob_authors, 'sort_time');
				foreach ($glob_authors as $authors) if ($userstr === file_get_contents($authors)) $author[] = $authors;
				if (isset($author))
				{
					$article .=
					'<div class=px-4>'.
					'<h3 class="h4 my-4">'. $prof_title[8]. '</h3>'.
					'<div class="list-group list-group-flush">';
					foreach ($author as $key => $author_article)
						if (5 > $key) $article .= '<a class=list-group-item href="'. r(get_categ($author_article). '/'. get_title($author_article)). '">'. h(get_title($author_article)). '</a>';
					$article .=
					'</div>'.
					'</div>';
					$achievements = true;
				}
			}
			$comment_success = !is_file($cs = $current_userdir. '/comment-success.txt') ? '' : $cs;
			$forum_thread_count = !is_file($fh = $current_userdir. '/forum-thread.txt') ? '' : $fh;
			$forum_topic_count = !is_file($ft = $current_userdir. '/forum-topic.txt') ? '' : $ft;
			$forum_ress_count = !is_file($fr = $current_userdir. '/forum-ress.txt') ? '' : $fr;
			if ($comment_success || $forum_thread_count || $forum_topic_count || $forum_ress_count)
			{
				$article .=
				'<div class=px-4>'.
				'<h3 class="h4 my-4">'. $prof_title[9]. '</h3>'.
				'<ul class="list-group list-group-flush">'.
				(!$comment_success ? '' :
				'<li class="list-group-item d-flex justify-content-between align-items-start"><div class="ms-2 me-auto">'. $comment. '</div><span class="badge bg-primary rounded-pill">'. size_unit((int)file_get_contents($comment_success), false). '</span></li>').
				(!$forum_thread_count ? '' :
				'<li class="list-group-item d-flex justify-content-between align-items-start"><div class="ms-2 me-auto">'. $forum_title[1]. '</div><span class="badge bg-primary rounded-pill">'. size_unit((int)file_get_contents($forum_thread_count), false). '</span></li>').
				(!$forum_topic_count ? '' :
				'<li class="list-group-item d-flex justify-content-between align-items-start"><div class="ms-2 me-auto">'. $forum_title[0]. '</div><span class="badge bg-primary rounded-pill">'. size_unit((int)file_get_contents($forum_topic_count), false). '</span></li>').
				(!$forum_ress_count ? '' :
				'<li class="list-group-item d-flex justify-content-between align-items-start"><div class="ms-2 me-auto">'. $forum_title[2]. '</div><span class="badge bg-primary rounded-pill">'. size_unit((int)file_get_contents($forum_ress_count), false). '</span></li>').
				'</ul></div>';
				$achievements = true;
			}
			if (!$achievements) $article .= '<p class=my-4>'. $prof_title[10]. '</p>';
			$article .= '</section>';
		}

		if ($use_user_approach && !is_file($donotapproach))
		{
			$article .= '<section class=my-5 id=approach>';
			if (isset($profdir))
			{
				if (!is_dir($approach = 'users/'. $userstr. '/approach/')) mkdir($approach, 0757, true);
				if (is_file($userdir. '/approach/@'. $userstr) || is_file($userdir. '/approach/'. $userstr))
					$article .='<p class="alert alert-success my-5">'. sprintf($approach_info[1], $username, $useraddr). '</p>';
				elseif (is_dir($approach))
				{
					if (is_file($approacher = $approach. $_SESSION['l']))
					{
						if (!is_dir($contacted = 'users/'. $userstr. '/contacted/')) mkdir($contacted, 0757, true);
						$approacher_name = handle($profdir);
						if (filter_has_var(INPUT_POST, 'contact'))
						{
							$headers = $mime. 'From: '. $approacher_name. '<'. $session_usermail. '>'. $n.
							'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;
							$to = dec(str_rot13($user));
							$subject = sprintf($approach_subject[3], $approacher_name). ' - '. $site_name;
							$body =
								sprintf($approach_body[3], $approacher_name). $n. $separator. $n.
								filter_input(INPUT_POST, 'contact-text', FILTER_SANITIZE_FULL_SPECIAL_CHARS). $n. $separator. $n.
								sprintf($approach_body[4], $approacher_name). $n. $site_name. $n. $url;

							if (mail($to, $subject, $body, $headers))
							{
								file_put_contents($contacted. $_SESSION['l'], '');
								counter($userdir. '/message-success.txt', 1);
								header('Location: '. $url. '?user='. $user. '&success=1#approach');
							}
							else
							{
								counter($userdir. '/message-error.txt', 1);
								header('Location: '. $url. '?user='. $user. '&error=1#approach');
							}
						}
						elseif (filter_has_var(INPUT_GET, 'success'))
						{
							$breadcrumb .= '<li class="breadcrumb-item active">'. $approach_form[0]. '</li>';
							$article .=
							'<p class="alert alert-success">'. sprintf($approach_form_message[0], $username). '</p>'.
							flow($approach_flow, $handlename, $username, 4);
						}
						elseif (filter_has_var(INPUT_GET, 'error'))
						{
							$breadcrumb .= '<li class="breadcrumb-item active">'. $approach_form[1]. '</li>';
							$article .= '<p class="alert alert-danger">'. $approach_form_message[6]. '</p>';
						}
						else
						{
							if (!is_file($contacted. $_SESSION['l']))
								$article .=
								'<h2 class=h4>'. sprintf($approach_form_title[0], $username). '</h2>'.
								'<p class=my-4>'. sprintf($approach_form_message[1], $handlename, $username). '</p>'.
								'<form class=mb-5 method=post action="'. $url. '?user='. $user. '">'.
								'<textarea class="form-control mb-3" rows=5 id=contact name=contact-text required></textarea>'.
								'<input type=submit name=contact value="'. $approach_form[3]. '" class="btn btn-danger">'.
								'</form>'.
								flow($approach_flow, $handlename, $username, 3);
							else
								$article .= flow($approach_flow, $handlename, $username, 4);
						}
					}
					else
					{
						if (is_file($disapproacher = $approach. '#'. $_SESSION['l']))
						{
							$article .=
							'<h2 class=h4>'. sprintf($approach_form_title[1], $username). '</h2>'.
							'<p class=my-4>'. $approach_form_message[2]. '</p>'.
							'<form method=post action="'. $url. '?user='. $user. '" class="text-center mb-5">'.
							'<input type=submit name=disapproach value="'. $approach_form[2]. '" class="btn btn-danger">'.
							'</form>';
						}
						elseif (is_file($disapproacher = $approach. '!'. $_SESSION['l']))
						{
							$article .=
							'<h2 class=h4>'. sprintf($approach_form_title[2], $username). '</h2>'.
							'<p class=my-4>'. sprintf($approach_form_message[3], $username). '</p>';
						}
						elseif (!is_file($approacher = $approach. '@'. $_SESSION['l']))
						{
							$article .=
							'<h2 class=h4>'. sprintf($approach_form_title[3], $username). '</h2>'.
							'<p class=my-4>'. sprintf($approach_form_message[4], $username, $handlename). '</p>'.
							'<form method=post action="'. $url. '?user='. $user. '" class="text-center mb-5">'.
							'<input name=approach-message type=text class="form-control mb-3" accesskey=a placeholder="'. $placeholder[5]. '">'.
							'<input type=submit name=approach value="'. $approach_form[3]. '" class="btn btn-danger">'.
							'</form>'.
							flow($approach_flow, $handlename, $username, 1);
						}
						else
						{
							$article .=
							'<h2 class=h4>'. sprintf($approach_form_title[4], $username). '</h2>'.
							'<p>'. sprintf($approach_form_message[5], $username). '</p>'.
							'<form method=post action="'. $url. '?user='. $user. '" class="text-center mb-5">'.
							'<input type=submit name=disapproach value="'. $approach_form[2]. '" class="btn btn-danger">'.
							'</form>'.
							flow($approach_flow, $handlename, $username, 2);
						}
						if (filter_has_var(INPUT_POST, 'approach'))
						{
							if (!is_file($approacher))
							{
								file_put_contents($approacher, filter_input(INPUT_POST, 'approach-message', FILTER_CALLBACK, ['options' => 'strip_tags_basename']), LOCK_EX);

								$headers = $mime. 'From: '. $from. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;
								$to = dec(str_rot13($user));
								$subject = sprintf($approach_subject[4], $handlename). ' - '. $site_name;
								$body = sprintf($approach_body[5], $handlename, $prof_title[0]). $n. $url. '?user='. $useraddr. $n. $n. $separator. $n. $site_name. $n. $url;
								if (mail($to, $subject, $body, $headers))
								{
									counter($userdir. '/approached.txt', 1);
									header('Location: '. $url. '?user='. $user. '#approach');
								}
							}
						}
						if (filter_has_var(INPUT_POST, 'disapproach'))
						{
							if (is_file($approacher)) unlink($approacher);
							if (is_file($disapproacher)) unlink($disapproacher);
							counter($userdir. '/disapproached.txt', 1);
							header('Location: '. $url. '?user='. $user. '#approach');
						}
					}
				}
			}
			else
			{
				$article .= '<h2 class=h4>'. $prof_title[6]. '</h2>';
				if ($mail_address !== dec(basename(dirname($user_profdir))))
					$article .= '<p class=px-4>'. sprintf($prof_note[4], $username). '</p>';
				else
					$article .= '<p class=px-4>'. sprintf($prof_note[5], $username). '</p>';
			}
			$article .= '</section>';
		}
	}
}
else
{
	http_response_code(404);
	$header .= '<title>'. $user_not_found. ' - '. $site_name. '</title>';
	$breadcrumb .= '<li class="breadcrumb-item active">'. $user_not_found. '</li>';
	$article .= '<h2 class=h4>'. $user_not_found_title[0]. '</h2>';
	if ($use_contact) $article .= '<p>'. $​ask_admin. '</p>';
}
