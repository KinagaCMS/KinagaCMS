<?php
if (filter_has_var(INPUT_POST, 'preview'))
{
	$previews = [
		'name' => FILTER_SANITIZE_STRIPPED,
		'email' => FILTER_VALIDATE_EMAIL,
		'message' => FILTER_SANITIZE_SPECIAL_CHARS
	];
	$filtered_previews = filter_input_array(INPUT_POST, $previews);
	$filtered_preview_name = str_replace($delimiter, '', trim($filtered_previews['name']));
	$filtered_preview_email = trim($filtered_previews['email']);
	$filtered_preview_message = str_replace($line_breaks, $n, $filtered_previews['message']);

	$article .=
	'<div id=preview class="modal fade">'. $n.
	'<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">'. $n.
	'<div class=modal-content>'. $n.
	'<div class="modal-body p-0">'. $n.
	'<table class="table table-bordered text-secondary m-0">'. $n.
	'<thead class=thead-light><tr><th colspan=2>'. $contact_preview. '</th></tr></thead>'. $n.
	'<tr>'. $n.
	'<th class=w-25>'. $contact_label[0]. '</th>'. $n.
	'<td>'. ($filtered_preview_name ?: '<span class=text-danger>'. $contact_message[0]. '</span>'). '</td>'. $n.
	'</tr>'. $n.
	'<tr>'. $n.
	'<th>'. $contact_label[1]. '</th>'. $n.
	'<td>'. ($filtered_preview_email ?: '<span class=text-danger>'. $contact_message[1]. '</span>'). '</td>'. $n.
	'</tr>'. $n.
	'<tr>'. $n.
	'<th>'. $contact_label[2]. '</th>'. $n.
	'<td class=wrap>'. ($filtered_preview_message ?: '<span class=text-danger>'. $contact_message[2]. '</span>'). '</td>'. $n.
	'</tr>'. $n. ($get_categ && $get_title ?
	'<tr><td colspan=2 class=text-right>'. $comment_note. '</td></tr>' : '');

	if (!isset($_COOKIE[$session_name]))
		$article .=
		'<tr>'. $n.
		'<td colspan=2 class=text-center><strong class=text-danger>'. $contact_message[3]. '</strong></td>'. $n.
		'</tr>'. $n.
		'<tr>'. $n.
		'<td><button data-dismiss=modal class="btn btn-outline-secondary btn-block" accesskey=y tabindex=0>'. $btn[0]. '</button></td>'. $n.
		'<td><button disabled class="btn btn-outline-danger btn-block" accesskey=z tabindex=0>'. $btn[1]. '</button></td>'. $n.
		'</tr>';
	elseif ($filtered_preview_name && $filtered_preview_email && $filtered_preview_message)
	{
		$_SESSION['token'] = $token;
		$article .=
		'<tr>'. $n.
		'<td><button data-dismiss=modal class="btn btn-outline-secondary btn-block" accesskey=y tabindex=0>'. $btn[0]. '</a></td>'. $n.
		'<td>'. $n.
		'<form method=post action="'. $url. ($get_categ && $get_title ? $get_categ. $get_title. '#form_result' : r($contact_us)). '">'. $n.
		'<input type=hidden name=preview_name value="'. base64_encode($filtered_preview_name). '">'. $n.
		'<input type=hidden name=preview_email value="'. base64_encode($filtered_preview_email). '">'. $n.
		'<input type=hidden name=token value="'. $token. '">'. $n.
		'<input type=hidden name=preview_message value="'. base64_encode($filtered_preview_message). '">'. $n.
		'<button name=send type=submit class="btn btn-outline-success btn-block" accesskey=z tabindex=0>'. $btn[1]. '</button>'. $n.
		'</form>'. $n.
		'</td>'. $n.
		'</tr>';
	}
	else
		$article .=
		'<tr>'. $n.
		'<td><button data-dismiss=modal class="btn btn-outline-secondary btn-block" accesskey=y tabindex=0>'. $btn[0]. '</button></td>'. $n.
		'<td><button disabled class="btn btn-outline-danger btn-block" accesskey=z tabindex=0>'. $btn[1]. '</button></td>'. $n.
		'</tr>';
	$article .=
	'</table>'. $n.
	'</div>'. $n.
	'</div>'. $n.
	'</div>'. $n.
	'</div>'. $n;
	$footer .= '<script>$("#preview").modal().on("hidden.bs.modal",function(){$("#form").find("[name=message]").focus()});</script>';
}
elseif (filter_has_var(INPUT_POST, 'send'))
{
	if (isset($_SESSION['token']) && filter_has_var(INPUT_POST, 'token') && $_SESSION['token'] === $_POST['token'])
	{
		$sendings = [
			'preview_name' => FILTER_SANITIZE_STRIPPED,
			'preview_email' => FILTER_SANITIZE_STRIPPED,
			'preview_message' => FILTER_SANITIZE_STRIPPED
		];

		$filtered_sendings = filter_input_array(INPUT_POST, $sendings);
		$filtered_sending_name = filter_var(base64_decode($filtered_sendings['preview_name']), FILTER_SANITIZE_STRIPPED);
		$filtered_sending_email = filter_var(base64_decode($filtered_sendings['preview_email']), FILTER_VALIDATE_EMAIL);
		$filtered_sending_message = filter_var(base64_decode($filtered_sendings['preview_message']), FILTER_SANITIZE_STRIPPED);

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
			if (mail($mail_address, $subject, $body, $headers))
			{
				if (isset($_SESSION['l'], $userdir)) counter($userdir. '/'. ($get_categ && $get_title ? 'comment' : 'contact'). '-success.txt', 1);
				$article .=
				'<div id=form_result class="alert alert-dismissible alert-success">'. $n.
				'<button type=button class=close data-dismiss=alert tabindex=0>&times;</button>'. $n.
				'<strong>'. $contact_message[4]. '</strong>'. $n.
				'</div>';
			}
			else
			{
				if (isset($_SESSION['l'], $userdir)) counter($userdir. '/'. ($get_categ && $get_title ? 'comment' : 'contact'). '-error.txt', 1);
				$article .=
				'<div id=form_result class="alert alert-dismissible alert-danger">'. $n.
				'<button type=button class=close data-dismiss=alert tabindex=0>&times;</button>'. $n.
				'<strong>'. $contact_message[5]. '</strong>'. $n.
				'</div>';
			}

			if (isset($_SESSION['token'])) unset($_SESSION['token']);
		}
		else
		{
			if (isset($_SESSION['l'], $userdir)) counter($userdir. '/'. ($get_categ && $get_title ? 'comment' : 'contact'). '-error.txt', 1);
			if (isset($_SESSION['token'])) unset($_SESSION['token']);
			$article .=
			'<div id=form_result class="alert alert-dismissible alert-danger">'. $n.
			'<button type=button class=close data-dismiss=alert tabindex=0>&times;</button><strong>'. $contact_message[5]. '</strong>'. $n.
			'</div>';
		}
	}
}
if (__FILE__ === implode(get_included_files())) exit;
$article .=
'<form id=form method=post action="'. $url. ($get_categ && $get_title ? $get_categ. $get_title. '#privacy-policy' : r($contact_us)). '">'. $n.
'<div class=form-row>'. $n.
(isset($_SESSION['l'], $_SESSION['h']) ? '<input name=name type=hidden value="'. $_SESSION['h']. '"><input name=email type=hidden value="'. $session_usermail. '">' :
'<div class="form-group col-xl-6">'. $n.
'<label class=input-group-text>'. $contact_label[0]. $n.
'<input required name=name maxlength=60 type=text value="'. ($_SESSION['h'] ?? $filtered_preview_name ?? ''). '" class="form-control ml-md-2" accesskey=n'. ($placeholder[2] ? ' placeholder="'. $placeholder[2]. '"' : ''). '>'. $n.
'</label>'. $n.
'</div>'. $n.
'<div class="form-group col-xl-6">'. $n.
'<label class=input-group-text>'. $contact_label[1]. $n.
'<input required name=email type=email value="'. ($filtered_preview_email ?? ''). '" class="form-control ml-md-2" accesskey=m'. ($placeholder[3] ? ' placeholder="'. $placeholder[3]. '"' : ''). '>'. $n.
'</label>'. $n.
'</div>'). $n.
'<div class="form-group col-md-12">'. $n.
'<label class=input-group-text>'. $contact_label[2]. $n.
'<textarea required name=message class="form-control ml-md-2" accesskey=t rows=10 tabindex=0'. ($placeholder[4] ? ' placeholder="'. $placeholder[4]. '"' : ''). '>'. ($filtered_preview_message ?? ''). '</textarea>'. $n.
'</label>'. $n.
'<button name=preview type=submit class="btn btn-outline-primary float-right mt-3" accesskey=s tabindex=0>'. $contact_preview. '</button>'. $n.
'</div>'. $n.
'</div>'. $n.
'</form>'. $n;
