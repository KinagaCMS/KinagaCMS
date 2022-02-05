<?php
if (filter_has_var(INPUT_POST, 'preview'))
{
	$filtered_preview_name = str_replace($delimiter, '', trim(filter_input(INPUT_POST,'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
	$filtered_preview_email = filter_input(INPUT_POST, 'email', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
	$filtered_preview_message = str_replace($line_breaks, '&#10;', filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));
	$article .=
	'<div id=preview class="modal fade">'.
	'<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">'.
	'<div class=modal-content>'.
	'<div class="modal-body p-0">'.
	'<table class="table table-bordered text-secondary m-0">'.
	'<thead class=thead-light><tr><th colspan=2>'. $contact_preview. '</th></tr></thead>'.
	'<tr>'.
	'<th class=w-25>'. $contact_label[0]. '</th>'.
	'<td>'. ($filtered_preview_name ?: '<span class=text-danger>'. $contact_message[0]. '</span>'). '</td>'.
	'</tr>'.
	'<tr>'.
	'<th>'. $contact_label[1]. '</th>'.
	'<td>'. ($filtered_preview_email ?: '<span class=text-danger>'. $contact_message[1]. '</span>'). '</td>'.
	'</tr>'.
	'<tr>'.
	'<th>'. $contact_label[2]. '</th>'.
	'<td class=wrap>'. ($filtered_preview_message ?: '<span class=text-danger>'. $contact_message[2]. '</span>'). '</td>'.
	'</tr>'. $n. ($get_categ && $get_title && !isset($userdir) ?
	'<tr><td colspan=2 class=text-end>'. $comment_note[0]. '</td></tr>' : '');
	if (!isset($_COOKIE[$session_name]))
		$article .=
		'<tr>'.
		'<td colspan=2 class=text-center><strong class=text-danger>'. $contact_message[3]. '</strong></td>'.
		'</tr>'.
		'<tr>'.
		'<td><button data-bs-dismiss=modal class="btn btn-outline-secondary d-block" accesskey=y tabindex=0>'. $btn[0]. '</button></td>'.
		'<td><button disabled class="btn btn-outline-danger d-block" accesskey=z tabindex=0>'. $btn[1]. '</button></td>'.
		'</tr>';
	elseif ($filtered_preview_name && $filtered_preview_email && $filtered_preview_message)
	{
		$_SESSION['token'] = $token;
		$article .=
		'<tr>'.
		'<td><button data-bs-dismiss=modal class="btn btn-outline-secondary d-block" accesskey=y tabindex=0>'. $btn[0]. '</button></td>'.
		'<td>'.
		'<form method=post action="'. $url. ($get_categ && $get_title ? $get_categ. $get_title. '#form_result' : r($contact_us)). '">'.
		'<input type=hidden name=preview_name value="'. base64_encode($filtered_preview_name). '">'.
		'<input type=hidden name=preview_email value="'. base64_encode($filtered_preview_email). '">'.
		'<input type=hidden name=token value="'. $token. '">'.
		'<input type=hidden name=preview_message value="'. base64_encode($filtered_preview_message). '">'.
		'<button name=send type=submit class="btn btn-outline-success d-block" accesskey=z tabindex=0>'. $btn[1]. '</button>'.
		'</form>'.
		'</td>'.
		'</tr>';
	}
	else
		$article .=
		'<tr>'.
		'<td><button data-bs-dismiss=modal class="btn btn-outline-secondary d-block" accesskey=y tabindex=0>'. $btn[0]. '</button></td>'.
		'<td><button disabled class="btn btn-outline-danger d-block" accesskey=z tabindex=0>'. $btn[1]. '</button></td>'.
		'</tr>';
	$article .=
	'</table>'.
	'</div>'.
	'</div>'.
	'</div>'.
	'</div>';
	$javascript .= 'if(previd=document.getElementById("preview")){new bootstrap.Modal(previd).show();previd.addEventListener("hidden.bs.modal",()=>document.getElementById("form").querySelector("[name=message]").focus())}';
}
elseif (filter_has_var(INPUT_POST, 'send'))
{
	if (isset($_SESSION['token']) && filter_has_var(INPUT_POST, 'token') && $_SESSION['token'] === $_POST['token'])
	{
		$sendings = [
			'preview_name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'preview_email' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
			'preview_message' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
		];
		$filtered_sendings = filter_input_array(INPUT_POST, $sendings);
		$filtered_sending_name = filter_var(base64_decode($filtered_sendings['preview_name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$filtered_sending_email = filter_var(base64_decode($filtered_sendings['preview_email']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$filtered_sending_message = filter_var(base64_decode($filtered_sendings['preview_message']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if ($filtered_sending_name && $filtered_sending_email && $filtered_sending_message)
		{
			$headers = $mime;
			$headers .= 'From: '. $filtered_sending_name. ' <'. $filtered_sending_email. '>'. $n;
			$body = '';
			if ($get_categ && $get_title)
			{
				$boundary = bin2hex(random_bytes(16));
				$filename = $now. $delimiter. ($_SESSION['l'] ?? $filtered_sending_name). '.txt';
				$headers .= 'Content-Type: multipart/mixed;'. $n. ' boundary="'. $boundary. '"'. $n;
				$headers .= 'Content-Transfer-Encoding: 8bit'. $n;
				$subject = sprintf($comment_subject, $categ_name, $title_name). $site_name;
				$body .= '--'. $boundary. $n;
				$body .= 'Content-Type: text/plain; charset='. $encoding. $n;
				$body .= 'Content-Transfer-Encoding: 8bit'. $n;
				$body .= $n;
				$body .= $contact_label[2]. $n;
				$body .= $separator. $n;
				$body .= $filtered_sending_message. $n. $n;
				$body .= $separator. $n;
				$body .= sprintf($comment_acceptance, $filename, $categ_name, $title_name);
				$body .= $n. $separator. $n;
				$body .= '--'. $boundary. $n;
				$body .= 'Content-Type: application/octet-stream; name="'. $filename. '"'. $n;
				$body .= 'Content-Disposition: attachment; filename="'. $filename. '"'. $n;
				$body .= 'Content-Transfer-Encoding: 8bit'. $n. $n;
				$body .= $filtered_sending_message. $n. $n;
				$body .= '--'. $boundary. '--'. $n;
			}
			else
			{
				$headers .= 'Content-Type: text/plain; charset='. $encoding. $n;
				$headers .= 'Content-Transfer-Encoding: 8bit'. $n. $n;
				$subject = sprintf($contact_subject_suffix, $filtered_sending_name). $site_name;
				$body .= $filtered_sending_message. $n. $n;
			}
			$to = isset($author) && ($author_mail = filter_var(dec($author), FILTER_VALIDATE_EMAIL)) && $mail_address !== $author_mail ? "$mail_address, $author_mail" : $mail_address;
			if (isset($userdir, $comment_dir) && is_dir($userdir) && is_dir($comment_dir))
			{
				file_put_contents($comment_dir. '/'. $filename, $filtered_sending_message);
				counter($userdir. '/comment-success.txt', 1);
				header('Location: '. $current_url. '#cid-'. $now);
			 }
			 elseif (mail($to, $subject, $body, $headers))
			{
				if (isset($userdir) && is_dir($userdir)) counter($userdir. '/contact-success.txt', 1);
				$article .=
				'<div id=form_result class="alert alert-dismissible alert-success">'.
				'<button type=button class=btn-close data-bs-dismiss=alert tabindex=0></button>'.
				'<strong>'. $contact_message[4]. '</strong>'.
				'</div>';
				$javascript .= 'document.getElementById("form_result").addEventListener("closed.bs.alert",()=>location=location.href);';
			}
			else
			{
				if (isset($userdir) && is_dir($userdir)) counter($userdir. '/contact-error.txt', 1);
				$article .=
				'<div id=form_result class="alert alert-dismissible alert-danger">'.
				'<button type=button class=btn-close data-bs-dismiss=alert tabindex=0></button>'.
				'<strong>'. $contact_message[5]. '</strong>'.
				'</div>';
				$javascript .= 'document.getElementById("form_result").addEventListener("closed.bs.alert",()=>location=location.href);';
			}
			if (isset($_SESSION['token'])) unset($_SESSION['token']);
		}
		else
		{
			if (isset($userdir)) counter($userdir. '/'. ($get_categ && $get_title ? 'comment' : 'contact'). '-error.txt', 1);
			if (isset($_SESSION['token'])) unset($_SESSION['token']);
			$article .=
			'<div id=form_result class="alert alert-dismissible alert-danger">'.
			'<button type=button class=btn-close data-bs-dismiss=alert tabindex=0></button><strong>'. $contact_message[5]. '</strong>'.
			'</div>';
			$javascript .= 'document.getElementById("form_result").addEventListener("closed.bs.alert",()=>location=location.href);';
		}
	}
}
if (__FILE__ === implode(get_included_files())) exit;
$article .=
'<form id=form method=post action="'. $url. ($get_categ && $get_title ? $get_categ. $get_title. '#privacy-policy' : r($contact_us)). '">'.
(isset($_SESSION['l'], $_SESSION['h']) ? '<input name=name type=hidden value="'. trim($_SESSION['h']). '"><input name=email type=hidden value="'. $session_usermail. '">' :
'<div class="mb-3 row">'.
'<label class="col-sm-2 col-form-label" for=name>'. $contact_label[0]. '</label>'.
'<div class="col-sm-10"><input required name=name id=name maxlength='. $title_length. ' type=text value="'. ($_SESSION['h'] ?? $filtered_preview_name ?? ''). '" class="form-control" accesskey=n'. ($placeholder[2] ? ' placeholder="'. $placeholder[2]. '"' : ''). '></div>'.
'</div>'.
'<div class="mb-3 row">'.
'<label class="col-sm-2 col-form-label" for=email>'. $contact_label[1]. '</label>'.
'<div class="col-sm-10"><input required name=email id=email type=email value="'. ($filtered_preview_email ?? ''). '" class="form-control" accesskey=m'. ($placeholder[3] ? ' placeholder="'. $placeholder[3]. '"' : ''). '></div>'.
'</div>').
'<div class="mb-3 row">'.
'<label class="col-sm-2 col-form-label" for=message>'. $contact_label[2]. '</label>'.
'<div class="col-sm-10"><textarea required name=message id=message class="form-control" accesskey=t rows=10 tabindex=0'. ($placeholder[4] ? ' placeholder="'. $placeholder[4]. '"' : ''). '>'. ($filtered_preview_message ?? ''). '</textarea></div>'.
'</div>'.
'<button name=preview type=submit class="btn btn-outline-primary" accesskey=s tabindex=0>'. $contact_preview. '</button>'.
'</form>';
