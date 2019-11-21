<?php
session($session_name = 'kinaga');

if (filter_has_var(INPUT_GET, 'logout'))
{
	unsession();
	exit(header('Location: '. $url));
}

if (!is_dir($usersdir = './users/')) mkdir($usersdir, 0757, true);

if (!isset($_SESSION['l'], $_SESSION['n']) && isset($ticket) && is_file($ticket))
{
	$_SESSION['m'] = $now;
	if (filter_has_var(INPUT_POST, 'u'))
	{
		if (isset($_FILES['i']['error']) && $_FILES['i']['error'] === UPLOAD_ERR_OK && exif_imagetype($_FILES['i']['tmp_name']) === IMAGETYPE_JPEG)
		{
			if (basename($_FILES['i']['name'], '.jpg')+600 >= $now && filemtime($_FILES['i']['tmp_name'])+600 >= $now)
			{
				$session_exif = @exif_read_data($_FILES['i']['tmp_name'], '', '', true);

				if (isset($session_exif['COMMENT']))
				{
					$session_exifcom = explode('@', $session_exif['COMMENT'][0]);
					$session_rotcom = str_rot13($session_exifcom[1]);
				}
				if ($session_c === $session_exifcom[0] && $session_rotcom && $session_rotcom === $session_f)
				{
					if (is_file($session_tmpfile = $tmpdir. $session_rotcom))
					{
						unlink($session_tmpfile);
						$_SESSION['l'] = strip_tags($session_rotcom);
						$_SESSION['n'] = (int)$_SESSION['m'];

						if (!is_dir($userdir = $usersdir. $_SESSION['l']))
							mkdir($userdir, 0757, true);
						elseif (!is_permitted($userdir))
						{
							unset($_SESSION['l'], $_SESSION['n']);
							exit(header('Location: '. $url. '?user='. $session_rotcom));
						}

						if (!is_dir($logdir = $userdir. '/log/')) mkdir($logdir, 0757, true);
						if (!is_dir($profdir = $userdir. '/prof/')) mkdir($profdir, 0757, true);
						if (!is_file($handle = $profdir. '/handle')) file_put_contents($handle, '');
						counter($userdir. '/logged-in.txt', 1);
						file_put_contents($logdir. (int)$_SESSION['n'], $remote_addr. $delimiter. $user_agent);

						header('Location: '. $scheme. $server. $port. $request_uri);
					}
					else
						sess_err($login_warning[0]);
				}
				else
					sess_err($login_warning[1]);
			}
			else
				sess_err($login_warning[2]);
		}
		else
			sess_err($login_warning[3]);
	}
	elseif (filter_has_var(INPUT_POST, 't') && $session_e)
	{
		$session_tmp = $tmpdir. $session_e;

		file_put_contents($session_tmp, $now, LOCK_EX);

		if (extension_loaded('imagick'))
		{
			if (is_file($session_tmp) && filemtime($session_tmp)+600 >= $now)
			{
				if (isset($_SESSION['t'], $_SESSION['m']) && $_SESSION['m']+600 >= $now && $_SESSION['t'] === $session_t)
				{
					$session_precom = str_shuffle(substr($session_txt, 2, 32));
					$session_filename = $now. '.jpg';
					$session_limit = date($time_format, $now+600);
					$userubject = $ticket_subject. ' - '. $site_name;
					$session_headers = $mime;
					$session_headers .= 'From: noreply@'. $server. $n;
					$session_headers .= 'Content-Type: multipart/mixed; boundary="'. $token. '"'. $n;
					$session_headers .= 'Content-Transfer-Encoding: 8bit'. $n;
					$session_body = '--'. $token. $n;
					$session_body .= 'Content-Type: text/plain; charset='. $encoding. $n;
					$session_body .= 'Content-Transfer-Encoding: 8bit'. $n. $n;
					$session_body .= sprintf($ticket_body, $remote_addr, $session_filename, $session_limit). $n;
					$session_body .= '--'. $token. $n;
					$session_body .= 'Content-Type: application/octet-stream; name="'. $session_filename. '"'. $n;
					$session_body .= 'Content-Disposition: attachment; filename="'. $session_filename. '"'. $n;
					$session_body .= 'Content-Transfer-Encoding: base64'. $n. $n;
					$session_card = new Imagick($ticket);
					$session_card->setImageProperty('comment', $session_precom. '@'. str_rot13($session_e));
					$session_body .= chunk_split(base64_encode($session_card->getImagesBlob()));
					$session_body .= '--'. $token. '--'. $n;

					if (mail(dec($session_e), $userubject, $session_body, $session_headers))
					{
						$article .=
						'<div id=b class="modal fade">'. $n.
						'<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">'. $n.
						'<div class=modal-content>'. $n.
						'<div class="modal-header bg-primary rounded-top">'. $n.
						'<span class="modal-title h5 text-white">'. $ticket_title[0]. '</span>'. $n.
						'<button type=button class=close data-dismiss=modal onmouseup="location.reload()"><span aria-hidden=true>&times;</span></button>'. $n.
						'</div>'. $n.
						'<div class="modal-body p-0">'. $n.
						'<p class="border-bottom m-3">'. sprintf($ticket_process[0], $session_limit). '</p>'. $n.
						'<ol class="mb-5 ml-2">'. $n.
						'<li class=mb-2>'. $ticket_process[1]. '</li>'. $n.
						'<li class=mb-2>'. $ticket_process[2]. '</li>'. $n.
						'<li class=mb-2>'. $ticket_process[3]. '</li>'. $n.
						'<li>'. $ticket_process[4]. '</li>'. $n.
						'</ol>'. $n.
						'<h2 class="h5 p-3 mb-4 bg-info border-0 text-white">'. $ticket_title[1]. '</h2>'. $n.
						'<form enctype="multipart/form-data" method=post class="mb-4 mx-3">'. $n.
						'<input type=hidden name=MAX_FILE_SIZE value=10240>'. $n.
						'<input type=hidden name=c value="'. $session_precom. '">'. $n.
						'<input type=hidden name=f value="'. $session_e. '">'. $n.
						'<input required name=i type=file accesskey=i accept="image/jpeg">'. $n.
						'<input name=u type=submit accesskey=u class="btn btn-info m-2">'. $n.
						'</form>'. $n.
						'</div>'. $n.
						'</div>'. $n.
						'</div>'. $n.
						'</div>';
						$footer .= '<script>$("#b").modal({backdrop:"static"})</script>';
						unset($_SESSION['t']);
					}
					else
						sess_err($ticket_warning[0]);
				}
				else
					sess_err($ticket_warning[1]);
			}
			else
				sess_err($ticket_warning[2]);
		}
		else
			sess_err($ticket_warning[3]);
	}
	elseif (isset($_COOKIE['kinaga']))
	{
		$_SESSION['t'] = $token;
		$aside .=
		'<div id=login class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[0]. '">'. $n.
		'<div class="'. $sidebox_title_class[0]. '">'. $login. '</div>'. $n.
		'<form class="'. $sidebox_content_class[3]. '" method=post>'. $n.
		'<input class="bg-transparent text-reset form-control my-2" required name=e type=email accesskey=e placeholder="'. $placeholder[1]. '" '. (filter_has_var(INPUT_GET, 'login') ? 'autofocus' : ''). '>'. $n.
		'<input type=hidden name=t value="'. $token. '">'. $n.
		'<p>'. $login_message[1]. '</p>'. $n.
		'</form>'. $n.
		'</div>'. $n;
	}
	else
	{
		$aside .=
		'<div id=login class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[0]. '">'. $n.
		'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[4]. '</div>'. $n.
		'<div class="'. $sidebox_content_class[3]. '">'. $n.
		'<a class="btn btn-info btn-lg btn-block my-3" href="'. $scheme. $server. $port. $request_uri. '&amp;login">'. $login. '</a>'. $n.
		'<p>'. $login_message[0]. '</p>'. $n.
		'</div>'. $n.
		'</div>'. $n;
	}
}
if (isset($_SESSION['l'], $_SESSION['m'], $_SESSION['n']) && $_SESSION['n'] === $_SESSION['m'])
{
	if (is_dir($userdir = $usersdir. $_SESSION['l']) && is_permitted($userdir))
	{
		$useraddr = str_rot13(basename($userdir));
		$session_usermail = dec($_SESSION['l']);
		if (filter_var($session_usermail, FILTER_VALIDATE_EMAIL))
		{
			if (!is_dir($profdir = $userdir. '/prof/')) mkdir($profdir, 0757, true);
			if (!is_file($handle = $profdir. '/handle')) file_put_contents($handle, '');

			if (!is_dir($userdir))
				mkdir($userdir, 0757, true);
			else
				$_SESSION['h'] = $handlename = handle($profdir);

			if (!is_file($bgcolor = $profdir. 'bgcolor'))
				file_put_contents($bgcolor, 'hsl('. random_int(1, 360). ',80%,40%)');

			if (!is_dir($logdir = $userdir. '/log/'))
			{
				mkdir($logdir, 0757, true);
				file_put_contents($logdir. $_SESSION['n'], $remote_addr. $delimiter. $user_agent);
			}
			$aside .=
			'<div id=logout class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[0]. '">'. $n.
			'<div class="'. $sidebox_title_class[2]. '">'. sprintf($sidebox_title[5], $handlename). '</div>'. $n.
			'<div class="'. $sidebox_content_class[3]. '">'. $n.
			'<a class="btn btn-info btn-lg btn-block my-3" href="'. $url. '?user='. $useraddr. '">'. $myprof. '</a>'. $n.
			'<a class="btn btn-danger btn-lg btn-block my-3" href="'. $url. '?logout">'. $logout. '</a>'. $n.
			'</div>'. $n.
			'</div>'. $n;
		}
		else
			unsession();
	}
	else
		unsession();
}
