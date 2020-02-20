<?php
if (is_file($pages_file = 'contents/'. $page_name. '.html'))
{
	$basetitle = h($page_name);
	$header .=
	'<title>'. $basetitle. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .=
	'<li class="breadcrumb-item active">'. $basetitle. '</li>';
	$article .=
	'<small class=text-muted>'. sprintf($last_modified, date($time_format, filemtime($pages_file))). '</small>'. $n.
	'<h1 class="h3 mb-4">'. $basetitle. '</h1>';

	ob_start();
	include $pages_file;
	$pages_content = trim(ob_get_clean());
	$header .= '<meta name=description content="'. get_description($pages_content). '">'. $n;
	$article .= '<div class="article px-2 mb-5 clearfix">'. $pages_content. '</div>'. $n;

	if ($use_social)
		social(rawurlencode($basetitle. ' - '. $site_name), rawurlencode($url. $page_name));

	if ($use_permalink)
		permalink($basetitle. ' - '. $site_name, $current_url);
}
elseif ($use_contact && $page_name === $contact_us)
{
	$header .= '<title>'. $contact_us. ' - '. $site_name. '</title>'. $n;
	$breadcrumb .= '<li class="breadcrumb-item active">'. $contact_us. '</li>';
	if ($contact_subtitle)
		$article .= '<h1 class="h3 mb-4">'. $contact_us. ' <small class="ml-3 wrap text-muted">'. $contact_subtitle. '</small></h1>'. $n;
	if ($privacy_policy)
		$article .= '<p class="alert alert-warning wrap">'. $privacy_policy. '</p>'. $n;
	ob_start();
	include $form;
	$article .= trim(ob_get_clean());
}
elseif ($dl && $page_name === $download_contents)
{
	$dl_name = d($get_dl);
	if (is_file($dl_file = $downloads_dir. '/'. $dl_name))
	{
		header('Content-Length: '. filesize($dl_file));
		header('Content-Type: '. mime_content_type($dl_file));

		if (strpos($user_agent_lang, 'ja') !== false && strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'rv:11.0') !== false || strpos($user_agent, 'Edge') !== false)
		{
			header('X-Download-Options: noopen');
			header('Content-Disposition: attachment; filename="'. mb_convert_encoding($dl_name, $encoding_win, $encoding). '"');
		}
		else
			header('Content-Disposition: attachment; filename="'. $dl_name. '"');

		exit(readfile($dl_file));
	}
	$breadcrumb .= ($pages > 1 ? '<li class="breadcrumb-item"><a href="'. $url. r($download_contents). '">'. $download_contents. '</a></li><li class="breadcrumb-item active">'. sprintf($page_prefix, $pages). '</li>' : '<li class="breadcrumb-item active">'. $download_contents. '</li>');
	$header .= '<title>'. $download_contents. ' - '. ($pages > 1 ? sprintf($page_prefix, $pages). ' - ' : ''). $site_name. '</title>'. $n;

	if ($download_subtitle)
		$article .= '<h1 class="h3 mb-4">'. $download_contents. ' <small class="ml-3 wrap text-muted">'. $download_subtitle. '</small></h1>'. $n;
	if ($download_notice)
		$article .= '<p class="alert alert-warning wrap">'. $download_notice. '</p>'. $n;
	$dl_files = glob($downloads_dir. '/*', GLOB_NOSORT);

	if ($dl_files)
	{
		foreach ($dl_files as $dfiles)
			$dls_sort[] = ($di_filesize = filesize($dfiles)) > 0 ? filemtime($dfiles). $delimiter. $dfiles. $delimiter. size_unit($di_filesize) : '';

		$dls_sort = array_filter($dls_sort);
		rsort($dls_sort);
		$dls_number = count($dls_sort);
		$page_ceil = ceil($dls_number / $number_of_downloads);
		$max_pages = min($pages, $page_ceil);
		$dls_in_page = array_slice($dls_sort, ($max_pages - 1) * $number_of_downloads, $number_of_downloads);

		if ($dls_number > $number_of_downloads)
			pager($max_pages, $page_ceil);

		$article .= '<div class=list-group>';

		foreach ($dls_in_page as $dls)
		{
			$dl_uri = explode($delimiter, $dls);
			$article .=
			'<a class="list-group-item bg-transparent d-flex justify-content-between align-items-center" href="'. $url. r($download_contents). '&amp;dl='. rawurlencode(strip_tags(basename($dl_uri[1]))). '" target="_blank">'. $n.
			'<span><span class=mr-3>'. ht($dl_uri[1]). '</span>'. $n.
			'<span class=mr-3>'. date($time_format, $dl_uri[0]). '</span></span>'. $n.
			'<span class="badge badge-primary badge-pill">'. $dl_uri[2]. '</span>'. $n.
			'</a>'. $n;
		}
		$article .= '</div>';

		if ($dls_number > $number_of_downloads)
			pager($max_pages, $page_ceil);
	}
}
elseif ($use_forum && $page_name === $forum)
	include 'forum.php';
else
	not_found();
