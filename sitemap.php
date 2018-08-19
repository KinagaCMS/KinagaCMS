<?php
include_once 'includes/config.php';
header('Content-Type: application/xml; charset=' . $encoding);
$xml = new DOMDocument('1.0', $encoding);
$insert = $xml->firstChild;
$style = $xml->createProcessingInstruction('xml-stylesheet', 'type="text/css" href="'. $css. '"');
$xml->insertBefore($style, $insert);
echo $xml->saveXML(),
'<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9/">', $n;
$glob = glob('{' . $glob_dir . 'index.html,contents/*.html}', GLOB_BRACE + GLOB_NOSORT);

if ($glob)
{
	foreach($glob as $files)
		$sort[] = filemtime($files) . '-~-' . $files;

	$sort = array_filter($sort);
	rsort($sort);
	for($i = 0, $c = count($sort); $i < $c; ++$i)
	{
		$part = explode('-~-', $sort[$i]);
		$categ = basename(dirname(dirname($part[1])));
		$title = basename(dirname($part[1]));

		if ($title === 'contents')
		{
			$page = basename($part[1], '.html');

			if ($page == 'index')
				echo '<url><loc>', $url, '</loc></url>', $n;
			else
				echo '<url><loc>', $url, rawurlencode($page), '</loc><lastmod>', date('Y-m-d\TH:i:s', $part[0]), '</lastmod></url>', $n;
		}
		else
			echo '<url><loc>', $url, rawurlencode($categ), '/', rawurlencode($title), '</loc><lastmod>', date('Y-m-d\TH:i:s', $part[0]), '</lastmod></url>', $n;
	}
}
if ($use_contact === true)
	echo '<url><loc>', $url, rawurlencode($contact_us), '</loc></url>', $n;

echo '</urlset>';
