<?php
include_once 'includes/config.php';
header('Content-Type: text/plain; charset=' . $encoding);
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
				echo $url . $n;
			else
				echo $url . rawurlencode($page) . $n;
		}
		else
			echo $url . rawurlencode($categ) . '/' . rawurlencode($title) . $n;
	}
}
if ($use_contact === true)
	echo $url . rawurlencode($contact_us);