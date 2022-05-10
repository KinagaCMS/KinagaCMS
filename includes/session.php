<?php
session($session_name = 'kinaga');
if (filter_has_var(INPUT_GET, $logout) && isset($_SESSION['l']))
{
	unsession();
	$footer .=
	'<div class="toast bg-dark position-fixed bottom-0 end-0 m-2" id=logout-toast role=alert aria-live=assertive aria-atomic=true>'.
	'<div class=toast-header>'.
	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-patch-check-fill me-2" viewBox="0 0 16 16"><path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708z"/></svg>'.
	'<strong class=me-auto>'. $logout.'</strong>'.
	'<button type=button class=btn-close data-bs-dismiss=toast aria-label=Close></button>'.
	'</div>'.
	'<div class="toast-body text-white">'. $login_message[3]. '</div>'.
	'</div>';
	$javascript .= 'let logoutToast=document.getElementById("logout-toast");window.addEventListener("load",()=>new bootstrap.Toast(logoutToast).show());logoutToast.addEventListener("hidden.bs.toast",()=>location=location.href);';
}
if (!isset($_SESSION['l'], $_SESSION['n']) && isset($ticket) && is_file($ticket))
{
	$_SESSION['m'] = $now;
	if (filter_has_var(INPUT_POST, 'u'))
	{
		if (isset($_FILES['i']['error']) && UPLOAD_ERR_OK === $_FILES['i']['error'] && IMAGETYPE_PNG === exif_imagetype($_FILES['i']['tmp_name']))
		{
			if ($_SESSION['m'] <= basename($_FILES['i']['name'], '.png')+$time_limit*60 && $_SESSION['m'] <= filemtime($_FILES['i']['tmp_name'])+$time_limit*60)
			{
				if ($session_gtext = get_png_tEXt($_FILES['i']['tmp_name']))
				{
					$session_excom = explode('@', $session_gtext);
					$session_rotcom = str_rot13($session_excom[1]);
				}
				if ($session_c === $session_excom[0] && $session_rotcom && $session_rotcom === $session_f)
				{
					if (is_file($session_tmpfile = $tmpdir. $session_rotcom))
					{
						unlink($session_tmpfile);
						$_SESSION['l'] = strip_tags($session_rotcom);
						$_SESSION['n'] = (int)$_SESSION['m'];
						if (!is_dir($userdir = 'users/'. $_SESSION['l']))
							mkdir($userdir, 0757, true);
						elseif (!is_permitted($userdir))
						{
							unset($_SESSION['l'], $_SESSION['n']);
							exit(header('Location: '. $url. '?user='. $session_rotcom));
						}
						if (!is_dir($logdir = $userdir. '/log/')) mkdir($logdir);
						if (!is_dir($profdir = $userdir. '/prof/')) mkdir($profdir);
						if (!is_file($handle = $profdir. '/handle')) file_put_contents($handle, '');
						if (!is_dir($upload = $userdir. '/upload/')) mkdir($upload);
						counter($userdir. '/logged-in.txt', 1);
						file_put_contents($logdir. (int)$_SESSION['n'], $remote_addr. $delimiter. $user_agent, LOCK_EX);
						header('Location: '. $scheme. $server. $port. $request_uri. '&'. r($login). '=1');
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
		file_put_contents($session_tmp, $_SESSION['m'], LOCK_EX);
		if (is_file($session_tmp) && filemtime($session_tmp)+$time_limit*60 >= $_SESSION['m'])
		{
			if (isset($_SESSION['t'], $_SESSION['m']) && $_SESSION['m']+$time_limit*60 >= $_SESSION['m'] && $_SESSION['t'] === $session_t)
			{
				$session_precom = str_shuffle(substr($session_txt, 2, 32));
				$session_filename = $_SESSION['m']. '.png';
				$session_limit = date($time_format, $_SESSION['m']+$time_limit*60);
				$userubject = $ticket_subject. ' - '. $site_name;
				$session_headers = $mime. 'From: '. $from. $n. 'Content-Type: multipart/mixed; boundary="'. $token. '"'. $n. 'Content-Transfer-Encoding: 8bit'. $n;
				$session_body =
				'--'. $token. $n.
				'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n. $n.
				sprintf($ticket_body, $remote_addr, $session_filename, $session_limit). $n. $n.
				$separator. $n. $site_name. $n. $url. $n.
				'--'. $token. $n.
				'Content-Type: application/octet-stream; name="'. $session_filename. '"'. $n.
				'Content-Disposition: attachment; filename="'. $session_filename. '"'. $n.
				'Content-Transfer-Encoding: base64'. $n. $n;
				$session_body .= chunk_split(put_png_tEXt($ticket, $pngtext, $session_precom. '@'. str_rot13($session_e))). '--'. $token. '--'. $n;
				if (mail(dec($session_e), $userubject, $session_body, $session_headers))
				{
					$article .=
					'<div id=b class="modal fade" data-bs-backdrop=static>'.
					'<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">'.
					'<div class=modal-content>'.
					'<div class="modal-header bg-primary rounded-top">'.
					'<span class="modal-title h5 text-white">'. $ticket_title[0]. '</span>'.
					'<button type=button class=btn-close data-bs-dismiss=modal onmouseup="location.reload()"></button>'.
					'</div>'.
					'<div class="modal-body p-0">'.
					'<p class="border-bottom m-3">'. sprintf($ticket_process[0], $session_limit). '</p>'.
					'<ol class="mb-5 ms-2">'.
					'<li class=mb-2>'. $ticket_process[1]. '</li>'.
					'<li class=mb-2>'. $ticket_process[2]. '</li>'.
					'<li class=mb-2>'. $ticket_process[3]. '</li>'.
					'<li>'. $ticket_process[4]. '</li>'.
					'</ol>'.
					'<h2 class="h5 p-3 mb-4 bg-info border-0 text-white">'. $ticket_title[1]. '</h2>'.
					'<form enctype="multipart/form-data" method=post class="mb-4 mx-3">'.
					'<input type=hidden name=MAX_FILE_SIZE value=10240>'.
					'<input type=hidden name=c value="'. $session_precom. '">'.
					'<input type=hidden name=f value="'. $session_e. '">'.
					'<input required name=i type=file accesskey=i accept="image/png">'.
					'<input name=u type=submit accesskey=u class="btn btn-info m-2">'.
					'</form>'.
					'</div>'.
					'</div>'.
					'</div>'.
					'</div>';
					$javascript .= 'new bootstrap.Modal(document.getElementById("b")).show();';
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
	elseif (isset($_COOKIE['kinaga']))
	{
		$_SESSION['t'] = $token;
		$aside .=
		'<div id=login class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[0]. '">'.
		'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[4]. '</div>'.
		'<form class="'. $sidebox_content_class[3]. '" name=login method=post>'.
		'<input class="form-control mb-3" required name=e id=e type=email accesskey=e placeholder="'. $placeholder[1]. '"'. (filter_has_var(INPUT_GET, $login) ? ' autofocus' : ''). '>'.
		'<input type=hidden name=t value="'. $token. '">'.
		'<p>'. $login_message[1]. '</p>'.
		'</form>'.
		'</div>';
	}
	else
	{
		$aside .=
		'<div id=login class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[0]. '">'.
		'<div class="'. $sidebox_title_class[0]. '">'. $sidebox_title[4]. '</div>'.
		'<div class="'. $sidebox_content_class[3]. '">'.
		'<a class="btn btn-info btn-lg d-block my-3 text-white" href="'. $current_url. '&amp;'. r($login). '='. $now. '#login">'. $login. '</a>'.
		'<p>'. $login_message[0]. '</p>'.
		'</div>'.
		'</div>';
	}
}
if (isset($_SESSION['l'], $_SESSION['m'], $_SESSION['n']) && $_SESSION['n'] === $_SESSION['m'])
{
	if (is_dir($userdir = 'users/'. $_SESSION['l']) && is_permitted($userdir))
	{
		$useraddr = str_rot13(basename($userdir));
		if ($session_usermail = filter_var(dec($_SESSION['l']), FILTER_VALIDATE_EMAIL))
		{
			$userid = md5($_SESSION['l']);
			if (!is_dir($profdir = $userdir. '/prof/')) mkdir($profdir);
			if (!is_file($handle = $profdir. '/handle')) file_put_contents($handle, '');
			if (!is_dir($userdir))
				mkdir($userdir, 0757, true);
			else
				$_SESSION['h'] = $handlename = handle($profdir);
			if (!is_file($bgcolor = $profdir. 'bgcolor'))
				file_put_contents($bgcolor, 'hsl('. random_int(1, 360). ',80%,40%)', LOCK_EX);
			if (!is_dir($logdir = $userdir. '/log/'))
			{
				mkdir($logdir);
				file_put_contents($logdir. $_SESSION['n'], $remote_addr. $delimiter. $user_agent, LOCK_EX);
			}
			if (is_file($subscribe = $userdir. '/'. $userid))
			{
				$subscribe_expiry = (int)file_get_contents($subscribe);
				if ($now >= $subscribe_expiry)
				{
					unlink($subscribe);
					$subscribe_expired = true;
				}
			}
			$aside .=
			'<div id=logout class="'. $sidebox_wrapper_class[0]. ' order-'. $sidebox_order[0]. '">'.
			'<div class="'. $sidebox_title_class[2]. '">'. sprintf($sidebox_title[5], $handlename). '</div>'.
			(!isset($subscribe_expiry) ? '' : '<div class="alert alert-info mx-3 mb-0">'. sprintf($sidebox_title[12], expiry($subscribe_expiry, 1)). '</div>').
			'<div class="'. $sidebox_content_class[3]. '">'.
			'<a class="btn btn-info btn-lg d-block my-3 text-white" href="'. $url. '?user='. $useraddr. '">'. $prof_title[0]. '</a>'.
			'<a class="btn btn-danger btn-lg d-block my-3" href="'. $url. '?'. r($logout). '=1">'. $logout. '</a>'.
			'</div>'.
			'</div>';
			if (filter_has_var(INPUT_GET, $login))
			{
				$footer .=
				'<div class="toast bg-dark position-fixed bottom-0 end-0 m-2" id=login-toast role=alert aria-live=assertive aria-atomic=true>'.
				'<div class="toast-header">'.
				'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-patch-check-fill me-2" viewBox="0 0 16 16"><path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708z"/></svg>'.
				'<strong class=me-auto>'. $login_message[2].'</strong>'.
				'<button type=button class=btn-close data-bs-dismiss=toast aria-label=Close></button>'.
				'</div>'.
				'<div class="toast-body text-white">'. sprintf($sidebox_title[5], $handlename). (!isset($subscribe_expired) ? '' : $login_message[4]). '</div>'.
				'</div>';
				$javascript .= 'window.addEventListener("load",()=>new bootstrap.Toast(document.getElementById("login-toast")).show());';
			}
		}
		else
			unsession();
	}
	else
		unsession();
}
if (isset($_SESSION['l']) && !filter_var(dec($_SESSION['l']), FILTER_VALIDATE_EMAIL)) unsession();
