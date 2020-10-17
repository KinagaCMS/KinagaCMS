<?php
include 'includes/functions.php';
include 'includes/config.php';

header('Content-Type: application/xml; charset='. $encoding);

$xml = new DOMDocument('1.0', $encoding);
$insert = $xml->firstChild;
$style = $xml->createProcessingInstruction('xml-stylesheet', 'type="text/css" href="'. $css. '"');
$xml->insertBefore($style, $insert);

echo $xml->saveXML(),
'<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9/">', $n;

if ($glob = glob('{'. $glob_dir. 'index.html,contents/*.html}', GLOB_BRACE + GLOB_NOSORT))
{
	usort($glob, 'sort_time');

	foreach ($glob as $files)
	{
		$categ = get_categ($files);
		$title = get_title($files);
		$page = basename($files, '.html');
		$filetime = date('Y-m-d\TH:i:s', filemtime($files));

		if ('.' !== $categ)
			echo '<url><loc>', $url, r($categ. '/'. $title), '</loc><lastmod>', $filetime, '</lastmod></url>', $n;
		elseif ('contents/index.html' === $files)
			echo '<url><loc>', $url, '</loc><lastmod>', $filetime, '</lastmod></url>', $n;
		elseif (is_file('contents/'. $page. '.html'))
			echo '<url><loc>', $url, r($page), '</loc><lastmod>', $filetime, '</lastmod></url>', $n;
	}
}
if (is_dir('downloads'))
	echo '<url><loc>', $url, r($download_contents), '</loc></url>', $n;
if ($use_contact && $mail_address)
	echo '<url><loc>', $url, r($contact_us), '</loc></url>', $n;
echo '</urlset>';
