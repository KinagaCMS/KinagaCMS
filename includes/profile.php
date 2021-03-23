<?php
if (!filter_has_var(INPUT_GET, 'user')) exit;
if (is_dir($user_profdir = 'users/'. ($userstr = str_rot13($user)). '/prof/') && is_permitted('users/'. $userstr))
{
	$myintrod = $user_profdir. 'myintrod';
	$username = handle($user_profdir);
	$avatar = $user_profdir. 'avatar';
	$donotapproach = $user_profdir. 'donotapproach';
	$user_prof_title = sprintf($prof_title[2], $username);

	$header .= '<title>'. $user_prof_title. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .= '<li class="breadcrumb-item active"><a href="'. $url. '?user='. $user. '">'. $user_prof_title. '</a></li>';

	if (isset($_SESSION['l']) && $userstr === $_SESSION['l'])
	{
		$article .= '<h2 class=h4>'. $prof_title[0]. '</h2>';

		if (filter_has_var(INPUT_POST, 'p'))
		{
			if (filter_has_var(INPUT_POST, 'h'))
				file_put_contents($handle, trim(filter_input(INPUT_POST, 'h', FILTER_SANITIZE_STRING)), LOCK_EX);
			if (filter_has_var(INPUT_POST, 'j'))
				file_put_contents($myintrod, trim(filter_input(INPUT_POST, 'j', FILTER_SANITIZE_STRING)), LOCK_EX);
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
		'<form enctype="multipart/form-data" method=post class=mb-5>'. $n.
		'<fieldset class="form-group my-4">'. $n.
		'<label for=h class=h5>'. $prof_label[0]. '</label>'. $n.
		'<input class=form-control type=text id=h name=h accesskey=h title="'. $prof_note[0]. '" value="'. $username. '">'. $n.
		'<small class="form-text text-muted">'. $prof_note[1]. '</small>'. $n.
		'</fieldset>'. $n.
		'<fieldset class="form-group mb-4">'. $n.
		'<label for=a class=h5>'. $prof_label[1]. '</label>';

		if (is_file($avatar) || is_file($bgcolor))
		{
			$article .=
			'<div class="custom-control custom-checkbox mb-2 mr-sm-2">'. $n.
			'<input type=checkbox name=d class=custom-control-input id=d>'. $n.
			'<label class=custom-control-label for=d>'. $prof_label[2]. '</label>'. $n.
			'</div>';
		}
		$article .=
		'<div class=d-flex>'. $n.
		'<div class="d-table text-center">'. avatar($user_profdir). '</div>'. $n.
		'<div class=ml-3>'. $n.
		'<input type=hidden name=MAX_FILE_SIZE value=100000>'. $n.
		'<input id=a name=a type=file accesskey=a accept="image/jpeg,image/png">'. $n.
		'<small class="form-text text-muted">'. $prof_note[2]. '</small>'. $n.
		'</div>'. $n.
		'</div>'. $n.
		'</fieldset>'. $n.
		'<fieldset class="form-group mb-4">'. $n.
		'<label for=j class=h5>'. $prof_label[3]. '</label>'. $n.
		'<textarea class=form-control id=j name=j accesskey=j>'.
		(is_file($myintrod) && filesize($myintrod) ? h(trim(file_get_contents($myintrod))) : '').
		'</textarea>'. $n.
		'<small class="form-text text-muted">'. $prof_note[3]. '</small>'. $n.
		'</fieldset>'. $n.
		'<fieldset class="form-group mb-4">'. $n.
		'<label for=o class=h5>'. $prof_label[4]. '</label>'. $n.
		'<div class="custom-control custom-checkbox">'. $n.
		'<input type=checkbox class=custom-control-input id=o name=o'. (is_file($donotapproach) ? '' : ' checked'). '>'. $n.
		'<label class=custom-control-label for=o>'. $prof_label[5]. '</label>'. $n.
		'</div>'. $n.
		'</fieldset>'. $n.
		'<div class=text-center>'. $n.
		'<input class="btn btn-primary btn-lg mb-2" id=p name=p type=submit accesskey=p value="'. $btn[2]. '">'. $n.
		'</div>'. $n.
		'</form>';

		if (!is_file($donotapproach) && is_dir($approach = 'users/'. $userstr. '/approach/') && $glob_approach = glob($approach. '[!!]*', GLOB_NOSORT))
		{
			if ($acceptable_arr = filter_input(INPUT_POST, 'acceptable', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY))
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
			'<h3 id=approval>'. $prof_title[3]. ' <small>'. ($q > 1 ? sprintf($page_prefix, $q) : ''). '</small></h2>'. $n.
			'<p class=my-4>'. sprintf($approach_info[0], $username). '</p>'. $n;

			$count_glob_approach = count($glob_approach);
			$mx = ceil($count_glob_approach/$users_per_page);
			if ($q > $mx) $p = $mx;

			$sliced_glob_approachers = array_slice($glob_approach, ($q-1) * $users_per_page, $users_per_page);

			if ($count_glob_approach >= $users_per_page)
			{
				$article .=
				'<ul class="pagination justify-content-center mt-5">'. $n.
				'<li class="page-item'. ($q >= 2 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;q=1#approval">'. $first. '</a></li>'. $n.
				'<li class="page-item'. ($q > 1 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;q='. ($q - 1). '#approval">'. $prev. '</a></li>'. $n.
				'<li class="page-item'. ($q < $mx ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;q='. ($q + 1). '#approval">'. $next. '</a></li>'. $n.
				'<li class="page-item'. ($q <= $mx-1 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;q='. $mx. '#approval">'. $last. '</a></li>'. $n.
				'</ul>'. $n;
			}
			$article .=
			'<form method=post action="'. $url. '?user='. $user. '" class=my-5 id=approachers>'. $n.
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
					'<div class="col mb-5">'. $n.
					'<div class="d-flex position-relative">'. $n.
					'<div class="text-center mr-4"'. ($approacher_message ? ' data-toggle=tooltip data-placement=top title="'. $approacher_message. '"' : ''). '>'. $n.
					$approacher_avatar. $n.
					'</div>'. $n.
					'<div class=card-arrow>'. $n.
					'<div class="card p-3">'. $n.
					'<a class="card-title" href="'. $url. '?user='. $sbga. '">'. $approacher_handle. '</a>'. $n.
					'<div class="btn-group btn-group-toggle" data-toggle=buttons>'. $n.
					'<label class="btn btn-danger'. ($acceptable === '#' && $acceptable !== '@' ? ' active' : ''). '" for="b['. $bga. ']">'. $n.
					'<input type=radio id="b['. $bga. ']" name="acceptable['. $bga. ']"'. ($acceptable === '#' && $acceptable !== '@' ? ' checked' : ''). ' value=off>'. $btn[3]. '</label>'. $n.
					'<label class="btn btn-secondary" for="d['. $bga. ']"><input type=radio id="d['. $bga. ']" name="acceptable['. $bga. ']" value=del>'. $btn[4]. '</label>'. $n.
					'<label class="btn btn-'. ($acceptable === '@' || $acceptable === '#' ? 'info' : 'success active'). '" for="a['. $bga. ']">'. $n.
					'<input type=radio id="a['. $bga. ']" name="acceptable['. $bga. ']"'. ($acceptable === '@' || $acceptable === '#' ? '' : ' checked'). ' value=on>'. $btn[5]. '</label>'. $n.
					'</div>'. $n.
					'</div>'. $n.
					'</div>'. $n.
					'</div>'. $n.
					'</div>';
				}
			}
			$article .=
			'</div>'. $n.
			'<div class=text-center><input type=submit value="'. $btn[2]. '" class="btn btn-primary btn-lg" id=approachers-submit disabled></div>'. $n.
			'</form>';
			$javascript .= '$("#approachers").change(function(){$("#approachers-submit").prop("disabled",false)});';
		}
		if (is_admin() || is_subadmin())
		{
			#is_file($tpl_conf = $tpl_dir. 'config.php') ? '' : file_put_contents($tpl_conf, '<?php'. $n, LOCK_EX);

			if ($glob_userdir = glob('users/*/log/', GLOB_NOSORT+GLOB_ONLYDIR))
			{
				$p = !filter_has_var(INPUT_GET, 'p') ? 1 : (int)filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
				$article .= '<h3 id=users>'. $prof_title[4]. (1 < $p ? ' <small>'. sprintf($page_prefix, $p) .'</small>': ''). '</h2>'. $n;
				usort($glob_userdir, 'sort_time');
				$count_glob_userdir = count($glob_userdir);
				$mx = ceil($count_glob_userdir/$users_per_page);
				if ($p > $mx) $p = $mx;

				$sliced_glob_users = array_slice($glob_userdir, ($p-1) * $users_per_page, $users_per_page);

				if (filter_has_var(INPUT_POST, 'user') && is_dir($permit_user = 'users/'. basename(filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING))))
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
						if (filter_var(dec($post_userstr = basename(filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING))), FILTER_VALIDATE_EMAIL))
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
				'<p>'. $users_info[0]. '</p>'. $n.
				'<div class=row>'. $n;
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
					'<div class="col mb-5">'. $n.
					'<div class="user list-group'. ($permitted ? '' : ' banned'). '" id="'. $base_current_userdir. '">'. $n.
					'<div class="list-group-item text-center">'.
					$user_avatar.
					'<h5><a href="?user='. $user_addr. '" title="'. $base_current_userdir. '">'. $user_handle. '</a></h5>'.
					(!is_admin() ? '' : '<a href="mailto:'. $user_mail. '">'. $user_mail. '</a>').
					'</div>'. $n.
					'<div class="list-group-item d-flex justify-content-between align-items-center">'.
					'<h6>'. $users_info[1]. '</h6>'.
					'<div class="custom-control custom-switch">'. $n.
					'<input type=checkbox class=custom-control-input id="l'. $base_current_userdir. '" onchange="permit(this)"'. ($permitted ? ' checked' : ''). '>'. $n.
					'<label class="custom-control-label" for="l'. $base_current_userdir. '"></label>'. $n.
					'</div>'. $n.
					'</div>'. $n.
					(!is_admin() ? '' :
					'<div class="list-group-item d-flex justify-content-between align-items-center">'.
					'<h6>'. $users_info[2]. '</h6>'.
					'<div class="custom-control custom-switch">'. $n.
					'<input type=checkbox class=custom-control-input id="s'. $base_current_userdir. '" onchange="subadmin(this)"'. (2 === is_subadmin($base_current_userdir) ? ' checked' : ''). '>'. $n.
					'<label class="custom-control-label" for="s'. $base_current_userdir. '"></label>'. $n.
					'</div>'. $n.
					'</div>'). $n.
					'<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[0]. '</h6>'. date($time_format, $users_lastlogin). '</div>'. $n.
					(is_file($logged_in = $current_userdir. '/logged-in.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[1]. '</h6>'. (int)file_get_contents($logged_in). '</div>'. $n : '').
					(is_file($approached = $current_userdir. '/approached.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[2]. '</h6>'. (int)file_get_contents($approached). '</div>'. $n : '').
					(is_file($disapproached = $current_userdir. '/disapproached.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[3]. '</h6>'. (int)file_get_contents($disapproached). '</div>'. $n : '').
					(is_file($message_success = $current_userdir. '/message-success.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[4]. '</h6>'. (int)file_get_contents($message_success). '</div>'. $n : '').
					(is_file($message_error = $current_userdir. '/message-error.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[5]. '</h6>'. (int)file_get_contents($message_error). '</div>'. $n : '').
					(is_file($created_categ = $current_userdir. '/create-categ.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[14]. '</h6>'. (int)file_get_contents($created_categ). '</div>'. $n : '').
					(is_file($created_article = $current_userdir. '/create-article.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[15]. '</h6>'. (int)file_get_contents($created_article). '</div>'. $n : '').
					(is_file($created_sidepage = $current_userdir. '/create-sidepage.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[16]. '</h6>'. (int)file_get_contents($created_sidepage). '</div>'. $n : '').
					(is_file($comment_success = $current_userdir. '/comment-success.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[6]. '</h6>'. (int)file_get_contents($comment_success). '</div>'. $n : '').
					(is_file($comment_error = $current_userdir. '/comment-error.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[7]. '</h6>'. (int)file_get_contents($comment_error). '</div>'. $n : '').
					(is_file($contact_success = $current_userdir. '/contact-success.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[8]. '</h6>'. (int)file_get_contents($contact_success). '</div>'. $n : '').
					(is_file($contact_error = $current_userdir. '/contact-error.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[9]. '</h6>'. (int)file_get_contents($contact_error). '</div>'. $n : '').
					(is_file($forum_thread_count = $current_userdir. '/forum-thread.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[10]. '</h6>'. (int)file_get_contents($forum_thread_count). '</div>'. $n : '').
					(is_file($forum_topic_count = $current_userdir. '/forum-topic.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[11]. '</h6>'. (int)file_get_contents($forum_topic_count). '</div>'. $n : '').
					(is_file($forum_ress_count = $current_userdir. '/forum-ress.txt') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[12]. '</h6>'. (int)file_get_contents($forum_ress_count). '</div>'. $n : '').
					(is_dir($forum_upload_dir = $current_userdir. '/upload/') ? '<div class="list-group-item d-flex justify-content-between align-items-center"><h6>'. $status[13]. '</h6>'. (int)count(glob($forum_upload_dir, GLOB_NOSORT)). '</div>'. $n : '').
					'</div>'. $n.
					'</div>'. $n;
				}
				$article .= '</div>'. $n;
				$javascript .= 'function permit(e){let f=$(e).parents(".user");if(e.checked)f.removeClass("banned");else f.addClass("banned");$.post("'. $scheme. $server. $port. $request_uri. '","user="+$(e).parents(".user").attr("id")+"&val="+(e.checked?1:0))}function subadmin(e){$.post("'. $scheme. $server. $port. $request_uri. '","user="+$(e).parents(".user").attr("id")+"&add="+(e.checked?1:0))}';

				if ($count_glob_userdir >= $users_per_page)
				{
					$article .=
					'<ul class="pagination justify-content-center mb-5">'. $n.
					'<li class="page-item'. ($p >= 2 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;p=1#users">'. $first. '</a></li>'. $n.
					'<li class="page-item'. ($p > 1 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;p='. ($p - 1). '#users">'. $prev. '</a></li>'. $n.
					'<li class="page-item'. ($p < $mx ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;p='. ($p + 1). '#users">'. $next. '</a></li>'. $n.
					'<li class="page-item'. ($p <= $mx-1 ? '' : ' disabled'). '"><a class=page-link href="'. $url. '?user='. $user. '&amp;p='. $mx. '#users">'. $last. '</a></li>'. $n.
					'</ul>'. $n;
				}
			}
		}
		if (isset($logdir) && $glogs = glob($logdir. '*', GLOB_NOSORT))
		{
			$article .= '<h2 class=h4>'. $prof_title[5]. '</h2>'. $n;
			usort($glogs, 'sort_time');
			$article .= '<ul class="list-group my-4">'. $n;
			foreach ($glogs as $key => $glog)
			{
				if (10 < $key)
					unlink($glog);
				else
				{
					$log = explode($delimiter, file_get_contents($glog));
					$article .= '<li class="list-group-item">'. date('Y-m-d H:i:s', basename($glog)). ' '. h($log[0]). ' '. h($log[1]).'</li>'. $n;
				}
			}
			$article .= '</ul>';
		}
	}
	else
	{
		$article .=
		'<section class=my-5><h2 class=h4>'. $user_prof_title. '</h2>'. $n.
		'<div class="d-flex my-5 position-relative">'. $n.
		'<div class="d-table mr-4 rounded-circle text-center">'. avatar($user_profdir). '</div>'. $n.
		'<div class=card-arrow></div>'. $n.
		'<div class="card w-100">'. $n.
		'<div class="card-body wrap">';

		if (file_exists($myintrod) && filesize($myintrod))
			$article .= h(trim(file_get_contents($myintrod)));
		else
			$article .= $prof_title[1];

		$article .=
		'</div>'. $n.
		'</div>'. $n.
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
						if (5 > $key) $article .= '<a class=list-group-item href="'. r(get_categ($author_article). '/'. get_title($author_article)). '">'. h(get_title($author_article)). '</a>'. $n;
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
				'<div class=px-4><h3 class="h4 my-4">'. $prof_title[9]. '</h3><div class="d-flex my-4">'.
				(!$comment_success ? '' :
				'<div class="d-flex flex-column mr-3 mb-3 bg-primary text-white text-center"><span class="border-bottom py-2">'. $comment. '</span><span class="display-4 p-3">'. size_unit((int)file_get_contents($comment_success), false). '</span></div>').
				(!$forum_thread_count ? '' :
				'<div class="d-flex flex-column mr-3 mb-3 bg-primary text-white text-center"><span class="border-bottom py-2">'. $forum_title[1]. '</span><span class="display-4 p-3">'. size_unit((int)file_get_contents($forum_thread_count), false). '</span></div>').
				(!$forum_topic_count ? '' :
				'<div class="d-flex flex-column mr-3 mb-3 bg-primary text-white text-center"><span class="border-bottom py-2">'. $forum_title[0]. '</span><span class="display-4 p-3">'. size_unit((int)file_get_contents($forum_topic_count), false). '</span></div>').
				(!$forum_ress_count ? '' :
				'<div class="d-flex flex-column mr-3 mb-3 bg-primary text-white text-center"><span class="border-bottom py-2">'. $forum_title[2]. '</span><span class="display-4 p-3">'. size_unit((int)file_get_contents($forum_ress_count), false). '</span></div>').
				'</div></div>';
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
								trim(filter_input(INPUT_POST, 'contact-text', FILTER_SANITIZE_STRING)). $n. $separator. $n.
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
							'<p class="alert alert-success">'. sprintf($approach_form_message[0], $username). '</p>'. $n.
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
								'<h2 class=h4>'. sprintf($approach_form_title[0], $username). '</h2>'. $n.
								'<p class=my-4>'. sprintf($approach_form_message[1], $handlename, $username). '</p>'. $n.
								'<form method=post action="'. $url. '?user='. $user. '">'. $n.
								'<textarea class="form-control" rows=5 id=contact name=contact-text required></textarea>'. $n.
								'<div class=text-right><input type=submit name=contact value="'. $approach_form[3]. '" class="btn btn-danger mt-3 mb-5"></div>'. $n.
								'</form>'. $n.
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
							'<h2 class=h4>'. sprintf($approach_form_title[1], $username). '</h2>'. $n.
							'<p class=my-4>'. $approach_form_message[2]. '</p>'. $n.
							'<form method=post action="'. $url. '?user='. $user. '" class="text-center mb-5">'. $n.
							'<input type=submit name=disapproach value="'. $approach_form[2]. '" class="btn btn-danger">'. $n.
							'</form>';
						}
						elseif (is_file($disapproacher = $approach. '!'. $_SESSION['l']))
						{
							$article .=
							'<h2 class=h4>'. sprintf($approach_form_title[2], $username). '</h2>'. $n.
							'<p class=my-4>'. sprintf($approach_form_message[3], $username). '</p>';
						}
						elseif (!is_file($approacher = $approach. '@'. $_SESSION['l']))
						{
							$article .=
							'<h2 class=h4>'. sprintf($approach_form_title[3], $username). '</h2>'. $n.
							'<p class=my-4>'. sprintf($approach_form_message[4], $username, $handlename). '</p>'. $n.
							'<form method=post action="'. $url. '?user='. $user. '" class="text-center mb-5">'. $n.
							'<input name=approach-message type=text class="form-control mb-3" accesskey=a placeholder="'. $placeholder[5]. '">'. $n.
							'<input type=submit name=approach value="'. $approach_form[3]. '" class="btn btn-danger">'. $n.
							'</form>'. $n.
							flow($approach_flow, $handlename, $username, 1);
						}
						else
						{
							$article .=
							'<h2 class=h4>'. sprintf($approach_form_title[4], $username). '</h2>'. $n.
							'<p>'. sprintf($approach_form_message[5], $username). '</p>'. $n.
							'<form method=post action="'. $url. '?user='. $user. '" class="text-center mb-5">'. $n.
							'<input type=submit name=disapproach value="'. $approach_form[2]. '" class="btn btn-danger">'. $n.
							'</form>'. $n.
							flow($approach_flow, $handlename, $username, 2);
						}
						if (filter_has_var(INPUT_POST, 'approach'))
						{
							if (!is_file($approacher))
							{
								file_put_contents($approacher, trim(filter_input(INPUT_POST, 'approach-message', FILTER_SANITIZE_STRING)), LOCK_EX);

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
				$article .=
				'<h2 class=h4>'. $prof_title[6]. '</h2>'. $n.
				'<p>'. sprintf($prof_note[4], $username). '</p>';
			}
			$article .= '</section>';
		}
	}
}
else
{
	http_response_code(404);
	$header .= '<title>'. $user_not_found. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .= '<li class="breadcrumb-item active">'. $user_not_found. '</li>';
	$article .= '<h2 class=h4>'. $user_not_found_title[0]. '</h2>';
	if ($use_contact) $article .= '<p>'. $​ask_admin. '</p>';
}
