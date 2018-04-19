<?php
include_once 'includes/config.php';
header('Content-Type: application/atom+xml; charset=' . $encoding);
include_once 'includes/functions.php';
$xml = new DOMDocument('1.0', $encoding);
echo $xml -> saveXML() .
'<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="' . $lang . '">' . $n .
'<title type="text">' . $site_name . '</title>' . $n .
'<updated>' . date(DATE_ATOM, getlastmod()) . '</updated>' . $n .
'<id>' . $url . '</id>' . $n .
'<link rel="alternate" type="text/html" hreflang="' . $lang . '" href="' . $url . '" />' . $n .
'<link rel="self" type="application/atom+xml" href="' . $url . 'atom.php" />' . $n .
'<rights>Copyright (c) ' . date('Y') . ', ' . $site_name . '</rights>' . $n .
'<generator>kinaga</generator>' . $n;

$atom_files = glob('{' . $glob_dir . 'index.html,contents/*.html}', GLOB_BRACE + GLOB_NOSORT);

if ($atom_files)
{
	foreach($atom_files as $atoms)
		$sort[] = filemtime($atoms) . '-~-' . $atoms;

	$sort = array_filter($sort);

	rsort($sort);
	for($i = 0, $c = count($sort); $i < $c && $i < $number_of_feeds; ++$i)
	{
		$atom_articles = explode('-~-', $sort[$i]);
		$atom_title = get_title($atom_articles[1]);
		$atom_article_dir = dirname($atom_articles[1]);
		$atom_section = file_get_contents($atom_articles[1]);

		if ($atom_title == 'contents')
		{
			$pagename = basename($atom_articles[1], '.html');
			if ($pagename == 'index')
			{
				$atom_article_link_title = $home;
				$id = $url;
			}
			else
			{
				$atom_article_link_title = h($pagename);
				$id = $url . rawurlencode($pagename);
			}
		}
		else
		{
			$atom_article_link_title = h($atom_title);
			$id = $url . rawurlencode(get_categ($atom_articles[1])) . '/' . rawurlencode($atom_title);
		}
		if (file_exists($atom_imgs_dir = $atom_article_dir . '/' . 'images') && is_dir($atom_imgs_dir))
		{
			$glob_atom_imgs = glob($atom_imgs_dir . '/*', GLOB_NOSORT);

			if ($glob_atom_imgs)
			{
				sort($glob_atom_imgs);
				$atom_image = ($size = @getimagesize($glob_atom_imgs[0])) ?
				'<a href="' . $id . '"><img src="' . $url . r($glob_atom_imgs[0]) . '" width="' . ($size[0] > 500 ? 500 : $size[0]) . '" alt="' . $atom_article_link_title . '" /></a>' : '';
			}
			else
				$atom_image = '';
		}
		else
			$atom_image = '';

		if (file_exists($atom_background_imgs_dir = $atom_article_dir . '/background-images') && is_dir($atom_background_imgs_dir))
		{
			$glob_atom_background_imgs = glob($atom_background_imgs_dir . '/*', GLOB_NOSORT);

			if ($glob_atom_background_imgs)
			{
				sort($glob_atom_background_imgs);
				$atom_background_image = ($size = @getimagesize($glob_atom_background_imgs[0])) ?
				'<a href="' . $id . '"><img src="' . $url . r($glob_atom_background_imgs[0]) . '" width="' . ($size[0] > 500 ? 500 : $size[0]) . '" alt="' . $atom_article_link_title . '" /></a>' : '';
			}
			else
				$atom_background_image = '';
		}
		else
			$atom_background_image = '';

		echo
		'<entry>' . $n .
		'<title>' . $atom_article_link_title . '</title>' . $n .
		'<link rel="alternate" type="text/html" href="' . $id . '" />' . $n .
		'<id>' . $id . '</id>' . $n .
		'<updated>' . date(DATE_ATOM, $atom_articles[0]) . '</updated>' . $n .
		'<content type="xhtml" xml:lang="' . $lang . '">' . $n .
		'<div xmlns="http://www.w3.org/1999/xhtml">' . $n .
		'<p>' . get_description($atom_section) . ' <a href="' . $id . '">' . $more_link_text . '</a></p>' . $n . $atom_image . $atom_background_image .
		'</div>' . $n .
		'</content>' . $n .
		'<author><name>' . $site_name . '</name></author>' . $n .
		'</entry>' . $n;
	}
}
echo '</feed>';