<?php
if (is_dir($user_profdir = $usersdir. ($userstr = str_rot13($user)). '/prof/') && is_permitted($usersdir. $userstr))
{
	$myintrod = $user_profdir. 'myintrod';
	$username = $username_bk = handle($user_profdir);
	$avatar = $user_profdir. 'avatar';
	$donotapproach = $user_profdir. 'donotapproach';
	$user_prof_title = sprintf($prof_title[0], $username);

	$header .= '<title>'. $user_prof_title. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .= '<li class="breadcrumb-item active"><a href="'. $url. '?user='. $user. '" class=text-break>'. $user_prof_title. '</a></li>';

	if (isset($_SESSION['l']) && $userstr === $_SESSION['l'])
	{
		$article .= '<h2>'. $myprof. '</h2>';

		if (filter_has_var(INPUT_POST, 'p'))
		{
			if (filter_has_var(INPUT_POST, 'h'))
				file_put_contents($handle, trim(filter_input(INPUT_POST, 'h', FILTER_SANITIZE_STRING)));
			if (filter_has_var(INPUT_POST, 'j'))
				file_put_contents($myintrod, trim(filter_input(INPUT_POST, 'j', FILTER_SANITIZE_STRING)));
			if (filter_has_var(INPUT_POST, 'd'))
			{
				if (is_file($avatar)) unlink($avatar);
				if (is_file($bgcolor)) unlink($bgcolor);
			}

			if (filter_input(INPUT_POST, 'o', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false)
			{
				if (!is_file($donotapproach))
					file_put_contents($donotapproach, '');
			}
			else
			{
				if (is_file($donotapproach))
					unlink($donotapproach);
			}

			if (isset($_FILES['a']['error'], $_FILES['a']['name'], $_FILES['a']['tmp_name']) && $_FILES['a']['error'] === UPLOAD_ERR_OK)
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

					if (exif_imagetype($_FILES['a']['tmp_name']) === IMAGETYPE_JPEG)
					{
						$source = imagecreatefromjpeg($_FILES['a']['tmp_name']);
						imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
						ob_start();
						imagejpeg($thumb);
						$content = ob_get_contents();
						ob_end_clean();
						imagedestroy($thumb);
						file_put_contents($avatar, 'data:image/jpeg;base64,'. ltrim(base64_encode($content), '='));
					}
					elseif (exif_imagetype($_FILES['a']['tmp_name']) === IMAGETYPE_PNG)
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
						file_put_contents($avatar, 'data:image/png;base64,'. ltrim(base64_encode($content), '='));
					}
				}
			}
			header('Location: '. $url. '?user='. $user);
		}
		$article .=
		'<form enctype="multipart/form-data" method=post class=mb-5>'. $n.
		'<fieldset class="form-group mb-4">'. $n.
		'<label for=h>'. $prof_label[0]. '</label>'. $n.
		'<input class=form-control type=text id=h name=h accesskey=h title="'. $prof_note[0]. '" value="'. str_replace($admin_suffix, '', $username_bk). '">'. $n.
		'<small class="form-text text-muted">'. $prof_note[1]. '</small>'. $n.
		'</fieldset>'. $n.
		'<fieldset class="form-group mb-4">'. $n.
		'<label for=a>'. $prof_label[1]. '</label>';

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
		'<label for=j>'. $prof_label[3]. '</label>'. $n.
		'<textarea class=form-control id=j name=j accesskey=j>'.
		(is_file($myintrod) && filesize($myintrod) ? h(trim(file_get_contents($myintrod))) : '').
		'</textarea>'. $n.
		'<small class="form-text text-muted">'. $prof_note[3]. '</small>'. $n.
		'</fieldset>'. $n.
		'<fieldset class="form-group mb-4">'. $n.
		'<label for=o>'. $prof_label[4]. '</label>'. $n.
		'<div class="custom-control custom-checkbox">'. $n.
		'<input type=checkbox class=custom-control-input id=o name=o'. (is_file($donotapproach) ? '' : ' checked'). '>'. $n.
		'<label class=custom-control-label for=o>'. $prof_label[5]. '</label>'. $n.
		'</div>'. $n.
		'</fieldset>'. $n.
		'<div class=text-center>'. $n.
		'<input class="btn btn-primary btn-lg mb-2" id=p name=p type=submit accesskey=p value="'. $prof_btn[0]. '">'. $n.
		'</div>'. $n.
		'</form>';

		if (!is_file($donotapproach) && is_dir($approach = $usersdir. $userstr. '/approach/') && $glob_approach = glob($approach. '[!!]*', GLOB_NOSORT))
		{
			if ($acceptable_arr = filter_input(INPUT_POST, 'acceptable', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY))
			{
				foreach ($acceptable_arr as $key => $val)
				{
					$skey = substr($key, 0, 1);
					$lkey = ltrim($key, '@#');
					$to = dec($lkey);
					$headers = $mime. 'From: noreply@'. $server. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;

					if ($val === 'on' && ($skey === '@' || $skey === '#'))
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
					if ($val === 'off' && $skey !== '#')
					{
						rename($approach. $key, $approach. '#'. $lkey);
						$subject = sprintf($approach_subject[1], $username). ' - '. $site_name;
						$body = sprintf($approach_body[1], $username). $n. $url. '?user='. $useraddr. $n. $n.
						$separator. $n. $site_name. $n. $url;
						mail($to, $subject, $body, $headers);
					}
					if ($val === 'del' && $skey !== '!')
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
			'<h3 id=approval>'. $prof_title[1]. ' <small>'. ($q > 1 ? sprintf($page_prefix, $q) : ''). '</small></h3>'. $n.
			'<p class=px-1>'. sprintf($approach_info[0], $username). '</p>'. $n;

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

				if (is_dir($approacher_profdir = $usersdir. $lbga. '/prof/'))
				{
					$approacher_handle =handle($approacher_profdir);
					$approacher_avatar = avatar($approacher_profdir);
					$acceptable = substr($bga, 0, 1);
					$article .=
					'<div class="col mb-5">'. $n.
					'<div class="d-flex position-relative">'. $n.
					'<div class="avatar text-center mr-4"'. ($approacher_message ? ' data-toggle=tooltip data-placement=top title="'. $approacher_message. '"' : ''). '>'. $n.
					$approacher_avatar. $n.
					'</div>'. $n.
					'<div class=card-arrow>'. $n.
					'<div class="card p-3">'. $n.
					'<a class="card-title text-break" href="'. $url. '?user='. $sbga. '">'. $approacher_handle. '</a>'. $n.
					'<div class="btn-group btn-group-toggle" data-toggle=buttons>'. $n.
					'<label class="btn btn-danger'. ($acceptable === '#' && $acceptable !== '@' ? ' active' : ''). '" for="b['. $bga. ']">'. $n.
					'<input type=radio id="b['. $bga. ']" name="acceptable['. $bga. ']"'. ($acceptable === '#' && $acceptable !== '@' ? ' checked' : ''). ' value=off>'. $prof_btn[1]. '</label>'. $n.
					'<label class="btn btn-secondary" for="d['. $bga. ']"><input type=radio id="d['. $bga. ']" name="acceptable['. $bga. ']" value=del>'. $prof_btn[2]. '</label>'. $n.
					'<label class="btn btn-'. ($acceptable === '@' || $acceptable === '#' ? 'info' : 'success active'). '" for="a['. $bga. ']">'. $n.
					'<input type=radio id="a['. $bga. ']" name="acceptable['. $bga. ']"'. ($acceptable === '@' || $acceptable === '#' ? '' : ' checked'). ' value=on>'. $prof_btn[3]. '</label>'. $n.
					'</div>'. $n.
					'</div>'. $n.
					'</div>'. $n.
					'</div>'. $n.
					'</div>';
				}
			}
			$article .=
			'</div>'. $n.
			'<div class=text-center><input type=submit value="'. $prof_btn[0]. '" class="btn btn-primary btn-lg" id=approachers-submit disabled></div>'. $n.
			'</form>';
			$footer .= '<script> $("#approachers").change(function(){$("#approachers-submit").prop("disabled",false)})</script>';
		}

		if ($mail_address === $session_usermail)
		{
			if ($glob_userdir = glob($usersdir. '*/log/', GLOB_NOSORT+GLOB_ONLYDIR))
			{
				$p = !filter_has_var(INPUT_GET, 'p') ? 1 : (int)filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
				$article .= '<h3 id=users>'. $prof_title[2]. ($p > 1 ? ' <small>'. sprintf($page_prefix, $p) .'</small>': ''). '</h3>'. $n;
				usort($glob_userdir, function($a, $b){return filemtime($a) < filemtime($b);});
				$count_glob_userdir = count($glob_userdir);
				$mx = ceil($count_glob_userdir/$users_per_page);
				if ($p > $mx) $p = $mx;

				$sliced_glob_users = array_slice($glob_userdir, ($p-1) * $users_per_page, $users_per_page);

				if (filter_has_var(INPUT_POST, 'user') && filter_has_var(INPUT_POST, 'val'))
				{
					if (is_dir($permit_user = $usersdir. filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING)))
					{
						if (filter_input(INPUT_POST, 'val', FILTER_VALIDATE_BOOLEAN))
							chmod($permit_user, 0755);
						else
							chmod($permit_user, 0700);
					}
				}
				$article .=
				'<p>'. $users_info. '</p>'. $n.
				'<div class=row>'. $n;

				foreach ($sliced_glob_users as $current_user)
				{
					if ($current_user === $userdir. '/log/') continue;

					$current_userdir = dirname($current_user);
					$users_lastlogin = filemtime($current_user);
					$user_handle = handle($current_userdir. '/prof/');
					$user_avatar = avatar($current_userdir. '/prof/');
					$user_mail = dec($base_current_userdir = basename($current_userdir));
					$user_addr = str_rot13($base_current_userdir);
					$permitted = is_permitted($current_userdir);

					$article .=
					'<div class="col mb-5">'. $n.
					'<div class="list-group'. ($permitted ? '' : ' banned'). '">'. $n.
					'<div class="list-group-item text-center"><div class="d-flex justify-content-center mx-auto mb-3">'. $user_avatar. '</div>'. $n.
					'<div class="custom-control custom-switch">'. $n.
					'<input type=checkbox class=custom-control-input id="'. $base_current_userdir. '"'. ($permitted ? ' checked' : ''). ' onchange="permit(this)">'. $n.
					'<label class="custom-control-label permit" for="'. $base_current_userdir. '">'. ($permitted ? '' : ''). '</label>'. $n.
					'</div>'. $n.
					'</div>'. $n.
					'<div class="list-group-item text-break text-center"><a href="?user='. $user_addr. '">'. $user_handle. '</a></div>'. $n.
					'<div class="list-group-item text-break text-center"><a href="mailto:'. $user_mail. '">'. $user_mail. '</a></div>'. $n.
					'<div class="list-group-item text-break">'. $base_current_userdir. '</div>'. $n.
					'<div class="list-group-item">'. $status[0]. date($time_format, $users_lastlogin). '</div>'. $n.
					(is_file($logged_in = $current_userdir. '/logged-in.txt') ? '<div class="list-group-item">'. $status[1]. (int)file_get_contents($logged_in). '</div>'. $n : '').
					(is_file($approached = $current_userdir. '/approached.txt') ? '<div class="list-group-item">'. $status[2]. (int)file_get_contents($approached). '</div>'. $n : '').
					(is_file($disapproached = $current_userdir. '/disapproached.txt') ? '<div class="list-group-item">'. $status[3]. (int)file_get_contents($disapproached). '</div>'. $n : '').
					(is_file($message_success = $current_userdir. '/message-success.txt') ? '<div class="list-group-item">'. $status[4]. (int)file_get_contents($message_success). '</div>'. $n : '').
					(is_file($message_error = $current_userdir. '/message-error.txt') ? '<div class="list-group-item">'. $status[5]. (int)file_get_contents($message_error). '</div>'. $n : '').
					(is_file($comment_success = $current_userdir. '/comment-success.txt') ? '<div class="list-group-item">'. $status[6]. (int)file_get_contents($comment_success). '</div>'. $n : '').
					(is_file($comment_error = $current_userdir. '/comment-error.txt') ? '<div class="list-group-item">'. $status[7]. (int)file_get_contents($comment_error). '</div>'. $n : '').
					(is_file($contact_success = $current_userdir. '/contact-success.txt') ? '<div class="list-group-item">'. $status[8]. (int)file_get_contents($contact_success). '</div>'. $n : '').
					(is_file($contact_error = $current_userdir. '/contact-error.txt') ? '<div class="list-group-item">'. $status[9]. (int)file_get_contents($contact_error). '</div>'. $n : '').
					'</div>'. $n.
					'</div>'. $n;
				}
				$article .= '</div>'. $n;
				$footer .= '<script>function permit(e){let f=e.parentElement.parentElement.parentElement;if(e.checked){e.value=1;f.classList.remove("banned")}else{e.value=0;f.classList.add("banned")}$.post("'. $scheme. $server. $port. $request_uri. '","user="+e.id+"&val="+e.value)}</script>';

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
			$article .=
			'<h2>'. $prof_title[3]. '</h2>'. $n.
			'';
			usort($glogs, function($a, $b){return filemtime($a) < filemtime($b);});

			$i = 0;
			foreach ($glogs as $glog)
			{
				if (++$i > 10)
					unlink($glog);
				else
				{
					$log = explode($delimiter, file_get_contents($glog));
					$article .=
					'<ul class="list-group mb-4">'. $n.
					'<li class="list-group-item list-group-item-action">'. date($time_format, h(basename($glog))). '</li>'. $n.
					'<li class="list-group-item list-group-item-action">'. h($log[0]). '</li>'. $n.
					'<li class="list-group-item list-group-item-action">'. h($log[1]). '</li>'. $n.
					'</ul>';
				}
			}
			$article .= '';
		}
	}
	else
	{
		$article .=
		'<h2>'. $user_prof_title. '</h2>'. $n.
		'<div class="d-flex mb-5 position-relative">'. $n.
		'<div class="avatar d-table mr-4 rounded text-center">'. avatar($user_profdir). '</div>'. $n.
		'<div class=card-arrow></div>'. $n.
		'<div class="card w-100">'. $n.
		'<div class="card-body wrap">';

		if (file_exists($myintrod) && filesize($myintrod))
			$article .= h(trim(file_get_contents($myintrod)));
		else
			$article .= $nointrod;

		$article .=
		'</div>'. $n.
		'</div>'. $n.
		'</div>';

		if ($use_user_approach && !is_file($donotapproach))
		{
			if (isset($profdir))
			{
				if (!is_dir($approach = $usersdir. $userstr. '/approach/')) mkdir($approach, 0757, true);

				if (is_file($userdir. '/approach/@'. $userstr) || is_file($userdir. '/approach/'. $userstr))
					$article .='<p class="alert alert-success my-5">'. sprintf($approach_info[1], $username, $useraddr). '</p>';
				elseif (is_dir($approach))
				{
					if (is_file($approacher = $approach. $_SESSION['l']))
					{
						if (!is_dir($contacted = $usersdir. $userstr. '/contacted/')) mkdir($contacted, 0757, true);
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
								header('Location: '. $url. '?user='. $user. '&success');
							}
							else
							{
								counter($userdir. '/message-error.txt', 1);
								header('Location: '. $url. '?user='. $user. '&error');
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
								'<h3>'. sprintf($approach_form_title[0], $username). '</h3>'. $n.
								'<p>'. sprintf($approach_form_message[1], $handlename, $username). '</p>'. $n.
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
							'<h3>'. sprintf($approach_form_title[1], $username). '</h3>'. $n.
							'<p>'. $approach_form_message[2]. '</p>'. $n.
							'<form method=post action="'. $url. '?user='. $user. '" class="text-center mb-5">'. $n.
							'<input type=submit name=disapproach value="'. $approach_form[2]. '" class="btn btn-danger">'. $n.
							'</form>';
						}
						elseif (is_file($disapproacher = $approach. '!'. $_SESSION['l']))
						{
							$article .=
							'<h3>'. sprintf($approach_form_title[2], $username). '</h3>'. $n.
							'<p>'. sprintf($approach_form_message[3], $username). '</p>';
						}
						elseif (!is_file($approacher = $approach. '@'. $_SESSION['l']))
						{
							$article .=
							'<h3>'. sprintf($approach_form_title[3], $username). '</h3>'. $n.
							'<p>'. sprintf($approach_form_message[4], $username, $handlename). '</p>'. $n.
							'<form method=post action="'. $url. '?user='. $user. '" class="text-center mb-5">'. $n.
							'<input name=approach-message type=text class="form-control mb-3" accesskey=a placeholder="'. $placeholder[5]. '">'. $n.
							'<input type=submit name=approach value="'. $approach_form[3]. '" class="btn btn-danger">'. $n.
							'</form>'. $n.
							flow($approach_flow, $handlename, $username, 1);
						}
						else
						{
							$article .=
							'<h3>'. sprintf($approach_form_title[4], $username). '</h3>'. $n.
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
								file_put_contents($approacher, trim(filter_input(INPUT_POST, 'approach-message', FILTER_SANITIZE_STRING)));

								$headers = $mime. 'From: noreply@'. $server. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n;
								$to = dec(str_rot13($user));
								$subject = sprintf($approach_subject[4], $handlename). ' - '. $site_name;
								$body = sprintf($approach_body[5], $handlename, $myprof). $n. $url. '?user='. $useraddr. $n. $n. $separator. $n. $site_name. $n. $url;
								if (mail($to, $subject, $body, $headers))
								{
									counter($userdir. '/approached.txt', 1);
									header('Location: '. $url. '?user='. $user);
								}
							}
						}
						if (filter_has_var(INPUT_POST, 'disapproach'))
						{
							if (is_file($approacher)) unlink($approacher);
							if (is_file($disapproacher)) unlink($disapproacher);
							counter($userdir. '/disapproached.txt', 1);
							header('Location: '. $url. '?user='. $user);
						}
					}
				}
			}
			else
			{
				$article .=
				'<h3>'. $prof_title[4]. '</h3>'. $n.
				'<p>'. sprintf($prof_note[4], $username). '</p>';
			}
		}
	}
}
else
{
	http_response_code(404);
	$header .= '<title>'. $user_not_found. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .= '<li class="breadcrumb-item active">'. $user_not_found. '</li>';
	$article .= '<h2>'. $user_not_found_title. '</h2>';
	if ($use_contact) $article .= '<p>'. $​ask_admin. '</p>';
}
