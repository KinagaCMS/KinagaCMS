<?php
if (is_file($pages_file = 'contents/'. $get_page. '.html'))
{
	$basetitle = h($get_page);
	$header .=
	'<title>'. $basetitle. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .=
	'<li class="breadcrumb-item active">'. $basetitle. '</li>';
	$article .=
	'<small class=text-muted>'. sprintf($last_modified, date($time_format, filemtime($pages_file))). '</small>'. $n.
	'<h1 class="h2 mb-4">'. $basetitle. '</h1>';

	ob_start();
	include $pages_file;
	$pages_content = trim(ob_get_clean());
	$header .= '<meta name=description content="'. get_description($pages_content). '">'. $n;
	$article .= '<div class="article px-2 mb-5">'. $pages_content. '</div>'. $n;

	if ($use_social)
		social(rawurlencode($basetitle. ' - '. $site_name), rawurlencode($url. $basetitle));

	if ($use_permalink)
		permalink($basetitle. ' - '. $site_name, $url. rawurlencode($basetitle));
}
elseif ($use_contact && $get_page === $contact_us)
{
	$header .= '<title>'. $contact_us. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .= '<li class="breadcrumb-item active">'. $contact_us. '</li>';
	if ($contact_subtitle)
		$article .= '<h1 class="h2 mb-4">'. $contact_us. ' <small class="wrap text-muted">'. $contact_subtitle. '</small></h1>'. $n;
	if ($contact_notice)
		$article .= '<p class="alert alert-warning wrap">'. $contact_notice. '</p>'. $n;
	ob_start();
	include $form;
	$article .= trim(ob_get_clean());
}
elseif ($dl && $get_page === $download_contents)
{
	if (is_file($dl_file = $downloads_dir. '/'. $get_dl) && pathinfo($dl_file, PATHINFO_EXTENSION))
	{
		header('Content-Length: '. filesize($dl_file));
		header('Content-Type: '. mime_content_type($dl_file));

		if (strpos($user_agent_lang, 'ja') !== false && strpos($user_agent, 'MSIE') !== false && strpos($user_agent, 'rv:11.0') !== false)
		{
			header('X-Download-Options: noopen');
			header('Content-Disposition: attachment; filename="'. mb_convert_encoding($get_dl, $encoding_win, $encoding). '"');
		}
		else
			header('Content-Disposition: attachment; filename="'. $get_dl. '"');

		readfile($dl_file);
		exit;
	}
	$breadcrumb .= '<li class="breadcrumb-item active">'. $download_contents. '</li>';
	$header .= '<title>'. $download_contents. ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;

	if ($download_subtitle)
		$article .= '<h1 class="h2 mb-4">'. $download_contents. ' <small class="wrap text-muted">'. $download_subtitle. '</small></h1>'. $n;
	if ($download_notice)
		$article .= '<p class="alert alert-warning wrap">'. $download_notice. '</p>'. $n;
	$dl_files = glob($downloads_dir. '/*.*', GLOB_NOSORT);

	if ($dl_files)
	{
		for($i = 0, $c = count($dl_files); $i < $c; ++$i)
			$dls_sort[] = ($di_filesize = filesize($dl_files[$i])) > 0 ? filemtime($dl_files[$i]). '-~-'. $dl_files[$i]. '-~-'. size_unit($di_filesize) : '';

		$dls_sort = array_filter($dls_sort);
		rsort($dls_sort);
		$dls_number = count($dls_sort);
		$page_ceil = ceil($dls_number / $number_of_downloads);
		$max_pages = min($pages, $page_ceil);
		$dls_in_page = array_slice($dls_sort, ($max_pages - 1) * $number_of_downloads, $number_of_downloads);

		if ($dls_number > $number_of_downloads)
			pager($max_pages, $page_ceil);

		$article .= '<div class="list-group list-group-flush">';

		for($i = 0, $c = count($dls_in_page); $i < $c; ++$i)
		{
			$dl_uri = explode('-~-', $dls_in_page[$i]);
			$article .=
			'<a class=list-group-item href="'. $url. r($download_contents). '&amp;dl='. rawurlencode(strip_tags(basename($dl_uri[1]))). '" target="_blank">'. $n.
			'<span class=mr-3>'. date($time_format, $dl_uri[0]). '</span>'. $n.
			'<span class=mr-3>'. ht($dl_uri[1]). '</span>'. $n.
			'<span class=mr-3>'. $dl_uri[2]. '</span>'. $n.
			'</a>'. $n;
		}
		$article .= '</div>';

		if ($dls_number > $number_of_downloads)
			pager($max_pages, $page_ceil);
	}
}
else
	not_found();