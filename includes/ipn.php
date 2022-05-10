<?php
if (!filter_has_var(INPUT_GET, 'i') && !filter_has_var(INPUT_GET, 'c') && !filter_has_var(INPUT_GET, 't')) exit;
$categ = !filter_has_var(INPUT_GET, 'c') ? ' ' : d(filter_input(INPUT_GET, 'c'));
$title = !filter_has_var(INPUT_GET, 't') ? ' ' : d(filter_input(INPUT_GET, 't'));
$req = 'cmd=_notify-validate';
foreach (filter_input_array(INPUT_POST) ?? [] as $k => $v) $req .= '&'. $k. '='. urlencode(stripslashes($v));
[$price, $buyer] = str_getcsv(dec(filter_input(INPUT_POST, 'custom')));
$buyer_dir = 'users/'. basename(trim($buyer));
if ('Completed' !== filter_input(INPUT_POST, 'payment_status') && (int)$price !== filter_input(INPUT_POST, 'mc_gross') && !is_dir($buyer_dir)) exit;
$item_name = !filter_has_var(INPUT_POST, 'item_name') ? ' ' : trim(dec(filter_input(INPUT_POST, 'item_name')));
$item_number = !filter_has_var(INPUT_POST, 'item_number') ? '' : $delivery_times[filter_input(INPUT_POST, 'item_number', FILTER_SANITIZE_NUMBER_INT)];
$address_name = !filter_has_var(INPUT_POST, 'address_name') ? '' : strip_tags(filter_input(INPUT_POST, 'address_name'));
$payer_id = !filter_has_var(INPUT_POST, 'payer_id') ? '' : strip_tags(filter_input(INPUT_POST, 'payer_id'));
$mc_gross = !filter_has_var(INPUT_POST, 'mc_gross') ? '' : filter_input(INPUT_POST, 'mc_gross', FILTER_SANITIZE_NUMBER_INT);
$quantity = !filter_has_var(INPUT_POST, 'quantity') ? '' : filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
$payment_date = !filter_has_var(INPUT_POST, 'payment_date') ? '' : strip_tags(filter_input(INPUT_POST, 'payment_date'));
$address_zip = !filter_has_var(INPUT_POST, 'address_zip') ? '' : filter_input(INPUT_POST, 'address_zip', FILTER_SANITIZE_NUMBER_INT);
$address_country = !filter_has_var(INPUT_POST, 'address_country') ? '' : strip_tags(filter_input(INPUT_POST, 'address_country'));
$address_state = !filter_has_var(INPUT_POST, 'address_state') ? '' : strip_tags(filter_input(INPUT_POST, 'address_state'));
$address_city = !filter_has_var(INPUT_POST, 'address_city') ? '' : strip_tags(filter_input(INPUT_POST, 'address_city'));
$address_street = !filter_has_var(INPUT_POST, 'address_street') ? '' : strip_tags(filter_input(INPUT_POST, 'address_street'));
$pphost = 'www.'. (!$sandbox_mail_address ? '' : 'sandbox.'). 'paypal.com';
$headers = 'POST /cgi-bin/webscr HTTP/1.1'. $n;
$headers .= 'Content-Type: application/x-www-form-urlencoded'. $n;
$headers .= 'Connection: close'. $n;
$headers .= 'Content-Length: '. strlen($req). $n;
$headers .= 'Host: '. $pphost. $n. $n;
$fp = fsockopen('ssl://'. $pphost, 443, $errno, $errstr);
fwrite($fp, $headers. $req);
while (!feof($fp))
{
	$res = fgets($fp, 1024);
	if (0 === strcmp($res, 'VERIFIED'))
	{
		if (is_dir($article_dir = 'contents/'. $categ. '/'. $title) && !is_dir($item_name_dir = $article_dir. '/purchased/'. basename($item_name))) mkdir($item_name_dir, 0757);
		if (!is_file($buyer_txt = $item_name_dir. '/'. $now)) file_put_contents($buyer_txt, basename($buyer));
		counter($buyer_dir. '/payment-success.txt', 1);
		$headers = $mime. 'From: '. $site_name. '<'. $mail_address. '>'. $n. 'Content-Type: text/plain; charset='. $encoding. $n. 'Content-Transfer-Encoding: 8bit'. $n;
		if (is_numeric($item_name) && (10 === strlen($item_name)) && is_dir($buyer_dir))
		{
			file_put_contents($buyer_dir. '/'. md5($buyer), $item_name);
			$payer_subject = sprintf($shop_mail_subject[0], $site_name);
			$payer_body = sprintf($shop_mail_body[0], handle($buyer_dir. '/prof/'), $site_name, $mc_gross, expiry($item_name), date($time_format, $item_name)). $n. $n.
			$separator. $n. $site_name. $n. $url;
			$seller_subject = sprintf($shop_mail_subject[1], $site_name);
			$seller_body = sprintf($shop_mail_body[1], $payment_date, dec($buyer), expiry($item_name, 1), $mc_gross).
			$n. $n;
		}
		else
		{
			$payer_subject = sprintf($shop_mail_subject[2], $site_name);
			$payer_body = sprintf($shop_mail_body[2], $address_name, $site_name, $item_name, $mc_gross, $item_number). $n. $n.
			$separator. $n. $site_name. $n. $url;
			$seller_subject = sprintf($shop_mail_subject[3], $site_name);
			$seller_body = sprintf($shop_mail_body[3], $payment_date, $address_name, $item_name, $quantity, $mc_gross, $address_street, $address_city, $address_state, $address_country, $address_zip,  $item_number).
			$n. $n;
		}
		if (mail(dec($buyer), $payer_subject, $payer_body, $headers))
			mail($mail_address, $seller_subject, $seller_body, $headers);
	}
}
fclose($fp);
