<?php
/*
  * @copyright  Copyright (C) 2017 Gari-Hari LLC. All rights reserved.
  * @license    GPL 3.0 or later; see LICENSE file for details.
  */

$filtered_preview_name = '';

$filtered_preview_email = '';

$filtered_preview_message = '';

session_name( $session_name );

session_set_cookie_params( '3600' );

session_start();

session_regenerate_id();

$token = rtrim( base64_encode( openssl_random_pseudo_bytes( 32 ) ), '=' );

if ( filter_has_var( INPUT_POST, 'preview' ) ) {

	$previews = array(
		'name' => FILTER_SANITIZE_STRIPPED,
		'email' => FILTER_VALIDATE_EMAIL,
		'message' => FILTER_SANITIZE_SPECIAL_CHARS
	);

	$filtered_previews = filter_input_array( INPUT_POST, $previews );

	$filtered_preview_name = $filtered_previews['name'];

	$filtered_preview_email = $filtered_previews['email'];

	$filtered_preview_message = str_replace( $line_breaks, $n, $filtered_previews['message'] );

	echo

	'<div id=form-preview></div>' . $n .
	'<div id=preview class="panel panel-primary">' . $n .
	'<div class=panel-heading><h2 class=panel-title><span class="glyphicon glyphicon-check"></span> ' . $contact_preview . '</h2></div>' . $n .
	'<div class=panel-body>' . $n .
	'<table class="table table-striped table-hover">' . $n .
	'<tr>' . $n .
	'<th style="width:20%">' . $contact_name . '</th>' . $n .
	'<td>' . ( $filtered_preview_name ? $filtered_preview_name : $placeholder_name ) . '</td>' . $n .
	'</tr>' . $n .
	'<tr>' . $n .
	'<th>' . $contact_mail . '</th>' . $n .
	'<td>' . ( $filtered_preview_email ? $filtered_preview_email : $placeholder_mail ) . '</td>' . $n .
	'</tr>' . $n .
	'<tr>' . $n .
	'<th>' . $contact_message . '</th>' . $n .
	'<td class=wrap>' . ( $filtered_preview_message ? $filtered_preview_message : $placeholder_message ). '</td>' . $n .
	'</tr>' . $n . ( filter_has_var( INPUT_GET, 'categ' ) && filter_has_var( INPUT_GET, 'title' ) ?
	'<tr><td colspan=2 class=text-right>' . $contact_caution . '</td></tr>' : '' );

if ( !isset( $_COOKIE[$session_name] ) ) { echo

	'<tr>' . $n .
	'<td colspan=2 class=text-center><strong class=text-danger>' . $cookie_disabled_error . '</strong></td>' . $n .
	'</tr>' . $n .
	'<tr>' . $n .
	'<td><a onclick="$(\'#preview\').hide()" href=#post-form class="btn btn-warning btn-lg btn-block">' . $contact_cancel . '</a></td>' . $n .
	'<td><button disabled class="btn btn-danger btn-lg btn-block disabled" tabindex=7 accesskey=z>' . $contact_send . '</button></td>' . $n .
	'</tr>';

} elseif ( $filtered_preview_name !== '' && $filtered_preview_email !== false && $filtered_preview_message !== '' ) {

	$_SESSION['token'] = $token; echo

	'<tr>' . $n .
	'<td><a onclick="$(\'#preview\').hide()" href=#post-form class="btn btn-warning btn-lg btn-block">' . $contact_cancel . '</a></td>' . $n .
	'<td>' . $n .
	'<form method=post action="' . h( getenv( 'REQUEST_URI' ) ) . ( filter_has_var( INPUT_GET, 'categ' ) && filter_has_var(INPUT_GET, 'title' ) ? '#form_result' : '' ) . '">' . $n .
	'<input type=hidden name=preview_name value="' . $filtered_preview_name . '">' . $n .
	'<input type=hidden name=preview_email value="' . $filtered_preview_email . '">' . $n .
	'<input type=hidden name=token value="' . $token . '">' . $n .
	'<input type=hidden name=preview_message value="' . str_replace( $n, '&#10;', $filtered_preview_message ) . '">' . $n .
	'<button name=send type=submit class="btn btn-success btn-lg btn-block" tabindex=7 accesskey=z>' . $contact_send . '</button>' . $n .
	'</form>' . $n .
	'</td>' . $n .
	'</tr>';

} else { echo

	'<tr>' . $n .
	'<td><a onclick="$(\'#preview\').hide()" href=#post-form class="btn btn-warning btn-lg btn-block">' . $contact_cancel . '</a></td>' . $n .
	'<td><button disabled class="btn btn-danger btn-lg btn-block disabled" tabindex=7 accesskey=z>' . $contact_send . '</button></td>' . $n .
	'</tr>';

} echo

	'</table>' . $n .
	'</div>' . $n .
	'</div>';


} elseif ( filter_has_var( INPUT_POST, 'send' ) ) {


	if ( isset( $_SESSION['token'] ) && filter_has_var( INPUT_POST, 'token' ) && $_SESSION['token'] == $_POST['token']) {

		$sendings = array(
			'preview_name' => FILTER_SANITIZE_STRIPPED,
			'preview_email' => FILTER_VALIDATE_EMAIL,
			'preview_message' => FILTER_SANITIZE_SPECIAL_CHARS
		);

		$filtered_sendings = filter_input_array( INPUT_POST, $sendings );

		$filtered_sending_name = $filtered_sendings['preview_name'];

		$filtered_sending_email = $filtered_sendings['preview_email'];

		$filtered_sending_message = str_replace( $line_breaks, $n, $filtered_sendings['preview_message'] );

	if ( $filtered_sending_name !== '' && $filtered_sending_email !== false && $filtered_sending_message !== '' ) {

		$headers = 'MIME-Version: 1.0' . $n;

		$body = '';

		$headers .= 'From: ' . $filtered_sending_name . ' <' . $filtered_sending_email . '>' . $n;

		$headers .= 'X-Mailer: kinaga' . $n;

		$headers .= 'X-Date: ' . date( 'c' ) . $n;

		$headers .= 'X-Host: ' . h( gethostbyaddr( getenv( 'REMOTE_ADDR' ) ) ) . $n;

		$headers .= 'X-IP: ' . h( getenv( 'REMOTE_ADDR' ) ) . $n;

		$headers .= 'X-UA: ' . h( getenv( 'HTTP_USER_AGENT' ) ) . $n;

		$to = $site_name . ' <' . $mail_address . '>';

	if ( filter_has_var( INPUT_GET, 'categ' ) && filter_has_var( INPUT_GET, 'title' ) ) {

		$boundary = md5( uniqid( rand() ) );

		$filename = $now . '-~-' . $filtered_sending_name . '.txt';

		$headers .= 'Content-Type: multipart/mixed;' . $n . ' boundary="' . $boundary . '"' . $n;

		$headers .= 'Content-Transfer-Encoding: 8bit' . $n;

		$subject = sprintf( $comment_subject, $get_categ, $get_title ) . $site_name;

		$body .= '--' . $boundary . $n;

		$body .= 'Content-Type: text/plain; charset=' . $encoding . $n;

		$body .= 'Content-Transfer-Encoding: 8bit' . $n;

		$body .= $n;

		$body .= $separator . $n;

		$body .= sprintf( $comment_acceptance, $filename, $get_categ, $get_title );

		$body .= $n . $separator . $n;

		$body .= '--' . $boundary . $n;

		$body .= 'Content-Type: application/octet-stream; name="' . $filename . '"' . $n;

		$body .= 'Content-Disposition: attachment; filename="' . $filename . '"' . $n;

		$body .= 'Content-Transfer-Encoding: base64' . $n . $n;

		$body .= chunk_split( base64_encode( $filtered_sending_message) ) . $n . $n;

		$body .= '--' . $boundary . '--' . $n;

	} else {

		$headers .= 'Content-Type: text/plain; charset=' . $encoding . $n;

		$subject = sprintf( $contact_subject_suffix, $filtered_sending_name ) . $site_name . $n;

		$body .= $n . $filtered_sending_message . $n;

		$body .= $n . $separator . $n;

		$body .= $site_name . $n;

		$body .= $address . $n . $n;

	}


	if ( isset( $_COOKIE[$session_name] ) ) {

		setcookie( session_name(), '', $now - 36000, $s );

	}


	if ( mail( $mail_address, $subject, $body, $headers ) ) { echo

		'<div id=form_result></div>' . $n .
		'<div class="alert alert-dismissible alert-success">' . $n .
		'<button type=button class=close data-dismiss=alert>&times;</button>' . $n .
		'<strong><span class="glyphicon glyphicon-ok"></span> ' . $contact_success . '</strong>' . $n .
		'</div>';

	} else { echo

		'<div id=form_result></div>' . $n .
		'<div class="alert alert-dismissible alert-danger">' . $n .
		'<button type=button class=close data-dismiss=alert>&times;</button>' . $n .
		'<strong><span class="glyphicon glyphicon-remove"></span> ' . $contact_error . '</strong>' . $n .
		'</div>';

	}

	session_unset();

	session_destroy();

	unset( $_SESSION['token'], $subject, $body, $headers );

	} else {

	if ( isset( $_COOKIE[$session_name] ) ) {

		setcookie( session_name(), '', $now - 36000, $s );

	}

	session_unset();

	session_destroy();

	unset( $_SESSION['token'], $subject, $body, $headers ); echo

	'<div id=form_result></div>' . $n .
	'<div class="alert alert-dismissible alert-danger">' . $n .
	'<button type=button class=close data-dismiss=alert>&times;</button><strong>' . $contact_error . '</strong>' . $n .
	'</div>';

	}

	}

}

	echo

	'<div id=post-form></div>' . $n .
	'<form method=post action="' . h( getenv( 'REQUEST_URI' ) ) . ( filter_has_var( INPUT_GET, 'categ' ) && filter_has_var( INPUT_GET, 'title' ) ? '#form-preview' : '' ) . '">' . $n .
	'<div class=row>' . $n .
	'<div class="col-md-6 form-group">' . $n .
	'<label for=e1 class="sr-only control-label">' . $contact_name . '</label>' . $n .
	'<input required id=e1 name=name type=text value="' . $filtered_preview_name . '" class="form-control input-lg" tabindex=3 accesskey=n placeholder="' . $placeholder_name . '">' . $n .
	'</div>' . $n .
	'<div class="col-md-6 form-group">' . $n .
	'<label for=e2 class="sr-only control-label">' . $contact_mail . '</label>' . $n .
	'<input required id=e2 name=email type=email value="' . $filtered_preview_email . '" class="form-control input-lg" tabindex=4 accesskey=m placeholder="' . $placeholder_mail . '">' . $n .
	'</div>' . $n .
	'<div class="col-md-12 form-group">' . $n .
	'<label for=e4 class="sr-only control-label">' . $contact_message . '</label>' . $n .
	'<textarea required id=e4 name=message class="form-control input-lg" tabindex=5 accesskey=t rows=10 placeholder="' . $placeholder_message . '">' . $filtered_preview_message . '</textarea>' . $n .
	'</div>' . $n .
	'<div class="col-md-12 form-group">' . $n .
	'<button name=preview type=submit class="btn btn-default btn-lg pull-right" tabindex=6 accesskey=s id=contact-preview>' . $contact_preview . '</button>' . $n .
	'</div>' . $n .
	'</div>' . $n .
	'</form>' . $n;

