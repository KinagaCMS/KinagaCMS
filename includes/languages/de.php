<?php
setlocale(LC_ALL, 'de_DE.UTF-8');
date_default_timezone_set('Europe/Berlin');

#Site Name
$site_name = 'kinaga';

# Your mail address
$mail_address = '';

#Your home or office address
$address = '';
$address_title = '';

#Hue: 'Rot', 'Rosa', 'Braun', 'Gelbgrün', 'Grün', 'Blau', 'Hellblau', 'Purpur', 'Grau',  'Schwarz' or BLANK
$color = 'Rot';


#Description: Top page
$meta_description = 'Beschreibung hier';

#Subtitle: Top Page H1 and TITLE
$subtitle = '';


#Top Page
$home = 'Home';


#Sideboxes
$recents = 'Neueste Artikel';

$informations = 'Informationen';

$category = 'Kategorie';

$recent_comments = 'Bemerkungen';

$popular_articles = 'Beliebte Artikel';

$download_contents = 'Downloads';
$download_subtitle = '';
$download_notice = '';

$contact_us = 'Kontakt uns';
$contact_subtitle = '';
$contact_notice = 'Ohne Ihre ausdrückliche Zustimmung werden wir niemals Informationen über Sie sammeln.';

$similar_title = 'Ähnliche Artikel';

$toc = 'Inhaltsverzeichnis';

$nav_laquo = '&laquo;';

$nav_raquo = '&raquo;';


$comments_prev = 'Neueren';

$comments_next = 'Ältere';

$page_prefix = 'Page %s';

#Social icons
$social = 'Share this';

$permalink = 'Permalink';

$for_html = 'HTML';

$for_wiki = 'Wiki';

$for_forum = 'Forum';


#Separator
$top = '<a class="page-top text-right d-block p-0 small" href="#TOP"> </a>';
$pagetop = 'zum Anfang';

$last_modified = 'zuletzt aktualisiert: %s';

$no_article = 'Artikel nicht gefunden.';

$no_categ = 'Kategorie existiert nicht.';

$error = 'Fehler';

$not_found = 'die angeforderte Seite wurde nicht gefunden.';

$more_link_text = 'Weiterlesen...';

$ellipsis = '...';

$views = '%s Ansichten';

$images_count_title = ' (%s Bilder)';

$source = 'Quelle: %s';

$result = 'Suchergebnisse für %s.';

$no_results_found = 'Keine Ergebnisse.';

$comments_not_allow = 'Kommentarfunktion ist geschlossen';

$comments_count_title = ' (%s Kommentare)';

$comment_title = 'Kommentar';

$comment_notice = $contact_notice . '';

$comment_counts = '%s Kommentare';

$contact_caution = 'Nur genehmigte Kommentare werden veröffentlicht.';

#email separator line
$separator = '_______________________________________________';

$comment_acceptance =

	'Um diesen Kommentar zu posten, '. $n.
	'Speichern Sie die angehängte Datei:% s'. $n.
	'und laden Sie es in den folgenden Ordner hoch'. $n . $n .
	'/contents/%s/%s/comments/';

$contact_name = 'Name';

$placeholder_name = 'Ihr Name';

$contact_mail = 'email';

$placeholder_mail = 'Ihr email address';

$contact_message = 'Nachricht';

$placeholder_message = 'Ihr Nachricht';

$contact_preview = 'Confirm';

$contact_cancel = 'Abbrechen';

$contact_send = 'Senden';

$cookie_disabled_error = 'Bitte aktivieren Sie Cookies.';

$contact_subject_suffix = 'Anfragen von %s - ';

$comment_subject = '%s Kategorie %s Artikel hat einen Kommentar - ';

$contact_success = 'Ihre Nachricht wurde erfolgreich gesendet.';

$contact_error = 'Senden fehlgeschlagen.';

$time_format = 'F jS, Y h:i';

$present_format = 'F jS';

$seconds_ago = '%s Sekunden zuvor';

$minutes_ago = '%s Minuten zuvor';

$hours_ago = '%s Stunden zuvor';

$days_ago = 'Vor %s Tagen';

$benchmark_results = '<span class="d-block text-muted text-center small">Gesamtzeit: %s sek. Erinnerung: %s</span>';


#/images/index.php
$images_title = 'Images - %s';

$images_heading = 'Images <small class="text-muted ml-2">Kopieren Sie den Tag und fügen Sie Ihren Artikel ein.</small>';

$images_aligner = 'Image Angleichung <small class="text-muted ml-2">Möglicherweise benötigen Sie &lt;div class=clearfix&gt;&lt;/div&gt; um den Wrap freizugeben.</small>';

$noscript = 'Bitte aktivieren Sie <strong>Javascript</strong>.';

$align_left = 'Links';

$align_center = 'Center';

$align_right = 'Recht';

#Anzahl der Bilder pro Seite
$number_of_imgs = 3;

$large_image = 'Groß';

$small_image = 'Klein';

$imgs_first= 'Zuerst';
$imgs_prev = 'Vorige';

$imgs_next = 'Nächster';
$imgs_last = 'Zuletzt';

function hsla($colour, $cal_s=0, $cal_l=0, $a=1)
{
	if ($colour === 'Rot')
	{
		$h = 355;
		$s = 70;
		$l = 50;
	}
		elseif ($colour === 'Rosa')
	{
		$h = 330;
		$s = 70;
		$l = 70;
	}
	elseif ($colour === 'Braun')
	{
		$h = 40;
		$s = 40;
		$l = 35;
	}
	elseif ($colour === 'Gelbgrün')
	{
		$h = 80;
		$s = 60;
		$l = 50;
	}
	elseif ($colour === 'Grün')
	{
		$h = 120;
		$s = 60;
		$l = 40;
	}
	elseif ($colour === 'Blau')
	{
		$h = 220;
		$s = 60;
		$l = 60;
	}
		elseif ($colour === 'Hellblau')
	{
		$h = 195;
		$s = 60;
		$l = 60;
	}
	elseif ($colour === 'Purpur')
	{
		$h = 250;
		$s = 60;
		$l = 70;
	}
	elseif ($colour === 'Grau')
	{
		$h = 200;
		$s = 5;
		$l = 60;
	}
	elseif ($colour === 'Schwarz')
	{
		$h = 0;
		$s = 0;
		$l = 10;
	}
	else list($h, $s, $l) = get_hsl($colour);
	if (isset($h, $s, $l))
		return 'hsla('. $h. ', '. ($s + (int)$cal_s). '%, '. ($l + (int)$cal_l). '%, '. $a. ')';
}

function color2class($colour)
{
	if ($colour === 'Grau')
		return 'secondary';
	elseif ($colour === 'Schwarz' )
		return 'dark';
	elseif ($colour === 'Braun')
		return 'muted';
	elseif ($colour === 'Gelbgrün' || $colour === 'Grün')
		return 'success';
	elseif ($colour === 'Rot' || $colour === 'Rosa')
		return 'warning';
	elseif ($colour === 'Purpur')
		return 'danger';
	elseif ($colour === 'Blau' || $colour === 'Hellblau')
		return 'info';
	else
		return 'primary';
}
