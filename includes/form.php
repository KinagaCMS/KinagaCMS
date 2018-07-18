<?php
$filtered_preview_name = $filtered_preview_email = $filtered_preview_message = '';
session_name($session_name = 'kinaga_session');
session_set_cookie_params('3600', $dir === '/' ? '/' : $dir. '/', $server,  is_ssl(), true);

if (!isset($_SESSION[$session_name]))
{
	session_start();
	session_regenerate_id(true);
}
if (filter_has_var(INPUT_POST, 'preview'))
{
	$previews = array(
		'name' => FILTER_SANITIZE_STRIPPED,
		'email' => FILTER_VALIDATE_EMAIL,
		'message' => FILTER_SANITIZE_SPECIAL_CHARS
	);
	$filtered_previews = filter_input_array(INPUT_POST, $previews);
	$filtered_preview_name = trim($filtered_previews['name']);
	$filtered_preview_email = trim($filtered_previews['email']);
	$filtered_preview_message = str_replace($line_breaks, $n, $filtered_previews['message']);

	echo
	'<table id=preview class="table mb-5">' . $n .
	'<tr class="table-active text-center"><th colspan=2>' . $contact_preview . '</th></tr>' . $n .
	'<tr>' . $n .
	'<th class=w-25>' . $contact_name . '</th>' . $n .
	'<td>' . ($filtered_preview_name ? $filtered_preview_name : $placeholder_name) . '</td>' . $n .
	'</tr>' . $n .
	'<tr>' . $n .
	'<th>' . $contact_mail . '</th>' . $n .
	'<td>' . ($filtered_preview_email ? $filtered_preview_email : $placeholder_mail) . '</td>' . $n .
	'</tr>' . $n .
	'<tr>' . $n .
	'<th>' . $contact_message . '</th>' . $n .
	'<td class=wrap>' . ($filtered_preview_message ? $filtered_preview_message : $placeholder_message). '</td>' . $n .
	'</tr>' . $n . (filter_has_var(INPUT_GET, 'categ') && filter_has_var(INPUT_GET, 'title') ?
	'<tr><td colspan=2 class=text-right>' . $contact_caution . '</td></tr>' : '');

	if (!isset($_COOKIE[$session_name]))
		echo
		'<tr>' . $n .
		'<td colspan=2 class=text-center><strong class=text-danger>' . $cookie_disabled_error . '</strong></td>' . $n .
		'</tr>' . $n .
		'<tr>' . $n .
		'<td><a onclick="$(\'#preview\').hide()" href=#comment class="btn btn-warning btn-lg btn-block">' . $contact_cancel . '</a></td>' . $n .
		'<td><button disabled class="btn btn-danger btn-lg btn-block disabled" tabindex=7 accesskey=z>' . $contact_send . '</button></td>' . $n .
		'</tr>';
	elseif ($filtered_preview_name && $filtered_preview_email && $filtered_preview_message)
	{
		$_SESSION['token'] = $token;
		echo
		'<tr>' . $n .
		'<td><a onclick="$(\'#preview\').hide()" href=#comment class="btn btn-warning btn-lg btn-block">' . $contact_cancel . '</a></td>' . $n .
		'<td>' . $n .
		'<form method=post action="' . h(getenv('REQUEST_URI')) . (filter_has_var(INPUT_GET, 'categ') && filter_has_var(INPUT_GET, 'title') ? '#form_result' : '') . '">' . $n .
		'<input type=hidden name=preview_name value="' . base64_encode($filtered_preview_name) . '">' . $n .
		'<input type=hidden name=preview_email value="' . base64_encode($filtered_preview_email) . '">' . $n .
		'<input type=hidden name=token value="' . $token . '">' . $n .
		'<input type=hidden name=preview_message value="' . base64_encode($filtered_preview_message) . '">' . $n .
		'<button name=send type=submit class="btn btn-success btn-lg btn-block" tabindex=7 accesskey=z>' . $contact_send . '</button>' . $n .
		'</form>' . $n .
		'</td>' . $n .
		'</tr>';
	}
	else
		echo
		'<tr>' . $n .
		'<td><a onclick="$(\'#preview\').hide()" href=#comment class="btn btn-warning btn-lg btn-block">' . $contact_cancel . '</a></td>' . $n .
		'<td><button disabled class="btn btn-danger btn-lg btn-block disabled" tabindex=7 accesskey=z>' . $contact_send . '</button></td>' . $n .
		'</tr>';
	echo
	'</table>' . $n;
}
elseif (filter_has_var(INPUT_POST, 'send'))
{
	if (isset($_SESSION['token']) && filter_has_var(INPUT_POST, 'token') && $_SESSION['token'] === $_POST['token'])
	{
		$sendings = array(
			'preview_name' => FILTER_SANITIZE_STRIPPED,
			'preview_email' => FILTER_SANITIZE_STRIPPED,
			'preview_message' => FILTER_SANITIZE_STRIPPED
		);

		$filtered_sendings = filter_input_array(INPUT_POST, $sendings);
		$filtered_sending_name = $filtered_sendings['preview_name'];
		$filtered_sending_email = base64_decode($filtered_sendings['preview_email']);
		$filtered_sending_message = $filtered_sendings['preview_message'];

		if ($filtered_sending_name && $filtered_sending_email && $filtered_sending_message)
		{
			$headers = 'MIME-Version: 1.0' . $n;
			$headers .= 'From: =?' . $encoding . '?B?' . $filtered_sending_name . '?= <' . $filtered_sending_email . '>' . $n;
			$headers .= 'X-Mailer: kinaga' . $n;
			$headers .= 'X-Date: ' . date('c') . $n;
			$headers .= 'X-Host: ' . gethostbyaddr($remote_addr) . $n;
			$headers .= 'X-IP: ' . $remote_addr . $n;
			$headers .= 'X-UA: ' . h(getenv('HTTP_USER_AGENT')) . $n;
			$to = ($mail_address && isset($site_name) ? '=?' . $encoding . '?B?' . base64_encode($site_name) . '?= <' . $mail_address . '>' : $mail_address);
			$body = '';

			if (filter_has_var(INPUT_GET, 'categ') && filter_has_var(INPUT_GET, 'title'))
			{
				$boundary = md5(uniqid(rand()));
				$filename = $now . '-~-' . base64_decode($filtered_sending_name) . '.txt';
				$headers .= 'Content-Type: multipart/mixed;' . $n . ' boundary="' . $boundary . '"' . $n;
				$headers .= 'Content-Transfer-Encoding: 8bit' . $n;
				$subject = sprintf($comment_subject, $get_categ, $get_title) . $site_name;
				$body .= '--' . $boundary . $n;
				$body .= 'Content-Type: text/plain; charset=' . $encoding . $n;
				$body .= 'Content-Transfer-Encoding: 8bit' . $n;
				$body .= $n;
				$body .= $separator . $n;
				$body .= sprintf($comment_acceptance, $filename, $get_categ, $get_title);
				$body .= $n . $separator . $n;
				$body .= '--' . $boundary . $n;
				$body .= 'Content-Type: application/octet-stream; name="' . $filename . '"' . $n;
				$body .= 'Content-Disposition: attachment; filename="' . $filename . '"' . $n;
				$body .= 'Content-Transfer-Encoding: base64' . $n . $n;
				$body .= chunk_split($filtered_sending_message) . $n . $n;
				$body .= '--' . $boundary . '--' . $n;
			}
			else
			{
				$headers .= 'Content-Type: text/plain; charset=' . $encoding . $n;
				$headers .= 'Content-Transfer-Encoding: base64' . $n . $n;
				$subject = sprintf($contact_subject_suffix, base64_decode($filtered_sending_name)) . $site_name;
				$body .= chunk_split($filtered_sending_message) . $n . $n;
			}

#echo'<pre id=form_result>sitename:'.$site_name.'<br>to:'.h($to).'<br>subject:',$subject.'<br>body:',$body.'<br>header:',h($headers).'</pre>';

			if (isset($_SESSION[$session_name]))
				setcookie(session_name(), '', 1);

			if (mail($to, '=?' . $encoding . '?B?' . base64_encode($subject) . '?=', $body, $headers))
			{
				echo
				'<div id=form_result></div>' . $n .
				'<div class="alert alert-dismissible alert-success">' . $n .
				'<button type=button class=close data-dismiss=alert>&times;</button>' . $n .
				'<strong><span class="glyphicon glyphicon-ok"></span> ' . $contact_success . '</strong>' . $n .
				'</div>';
			}
			else
				echo
				'<div id=form_result></div>' . $n .
				'<div class="alert alert-dismissible alert-danger">' . $n .
				'<button type=button class=close data-dismiss=alert>&times;</button>' . $n .
				'<strong><span class="glyphicon glyphicon-remove"></span> ' . $contact_error . '</strong>' . $n .
				'</div>';

			if (isset($_SESSION[$session_name]))
				session_destroy();
			unset($_SESSION['token'], $subject, $body, $headers);
		}
		else
		{
			if (isset($_SESSION[$session_name]))
			{
				setcookie(session_name(), '', 1);
				session_destroy();
			}
			unset($_SESSION['token'], $subject, $body, $headers);

			echo
			'<div id=form_result></div>' . $n .
			'<div class="alert alert-dismissible alert-danger">' . $n .
			'<button type=button class=close data-dismiss=alert>&times;</button><strong>' . $contact_error . '</strong>' . $n .
			'</div>';
		}
	}
}
echo
'<form id=form method=post action="' . h(getenv('REQUEST_URI')) . (filter_has_var(INPUT_GET, 'categ') && filter_has_var(INPUT_GET, 'title') ? '#preview' : '') . '">' . $n .
'<div class=form-row>' . $n .
'<div class="form-group col-md-6">' . $n .
'<label for=e1 class="sr-only control-label">' . $contact_name . '</label>' . $n .
'<input required id=e1 name=name type=text value="' . $filtered_preview_name . '" class=form-control tabindex=3 accesskey=n placeholder="' . $contact_name . '">' . $n .
'</div>' . $n .
'<div class="form-group col-md-6">' . $n .
'<label for=e2 class="sr-only control-label">' . $contact_mail . '</label>' . $n .
'<input required id=e2 name=email type=email value="' . $filtered_preview_email . '" class=form-control tabindex=4 accesskey=m placeholder="' . $contact_mail . '">' . $n .
'</div>' . $n .
'<div class="form-group col-md-12">' . $n .
'<label for=e4 class="sr-only control-label">' . $contact_message . '</label>' . $n .
'<textarea required id=e4 name=message class=form-control tabindex=5 accesskey=t rows=10 placeholder="' . $contact_message . '">' . $filtered_preview_message . '</textarea>' . $n .
'<button name=preview type=submit class="btn btn-outline-primary float-right mt-3" tabindex=6 accesskey=s id=contact-preview>' . $contact_preview . '</button>' . $n .
'</div>' . $n .
'</div>' . $n .
'</form>' . $n;
