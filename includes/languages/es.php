<?php
setlocale(LC_ALL, 'es_ES.UTF-8');
date_default_timezone_set('Europe/Madrid');

#Site Name
$site_name = 'kinaga';

# Your mail address
$mail_address = '';

#Your home or office address
$address = '';
$address_title = '';

#Hue: 'Red', 'Pink', 'Brown', 'YellowGreen', 'Green', 'Blue', 'LightBlue',  'Purple',  'Gray', 'Black', or BLANK
$color = 'LightBlue';


#Description: Top page
$meta_description = 'Descripción aquí.';

#Subtitle: Top Page H1 and TITLE
$subtitle = '';


#Top Page
$home = 'Home';


#Sideboxes
$recents = 'últimos artículos';

$category = 'Categoría';

$informations = 'Informaciones';

$recent_comments = 'Comentarios';

$popular_articles = 'articulos populares';

$download_contents = 'Descargas';
$download_subtitle = '';
$download_notice = '';

$contact_us = 'Contáctenos';
$contact_subtitle = '';
$contact_notice = 'Nunca recopilaremos información sobre usted sin su consentimiento explícito.';

$similar_title = 'Artículos similares';

$toc = 'Tabla de contenido';

$nav_laquo = '&laquo;';

$nav_raquo = '&raquo;';


$comments_prev = 'Más nuevo';

$comments_next = 'Más viejo';

$page_prefix = 'Página %s';

#Social icons
$social = 'Compartir este';

$permalink = 'Enlace permanente';

$for_html = 'HTML';

$for_wiki = 'Wiki';

$for_forum = 'Forum';


#Separator
$top = '<a class="page-top text-right d-block p-0 small" href="#TOP"> </a>';
$pagetop = 'Ve arriba';

$last_modified = 'Última actualización: %s';

$no_article = 'Artículo no encontrado.';

$no_categ = 'La categoría no existe.';

$error = 'Ocurrió un error';

$not_found = 'No se encontró la página que solicitó.';

$more_link_text = 'Lee mas...';

$ellipsis = '...';

$views = '%s vistas';

$images_count_title = ' (%s imagenes)';

$source = 'Fuente: %s';

$result = 'Resultados de búsqueda de %s.';

$no_results_found = 'No hay resultados.';

$comments_not_allow = 'Los comentarios están cerrados.';

$comments_count_title = ' (%s comentarios)';

$comment_title = 'Comentarios';

$comment_notice = $contact_notice . '';

$comment_counts = '%s comentarios';

$contact_caution = 'Sólo se publicarán los comentarios aprobados.';

#email separator line
$separator = '_______________________________________________';

$comment_acceptance =

	'Para publicar este comentario,' . $n .
	'Guarda el archivo adjunto: %s' . $n .
	'y subirlo a la siguiente carpeta' . $n . $n .
	'/contents/%s/%s/comments/';

$contact_name = 'Nombre';

$placeholder_name = 'Tu nombre';

$contact_mail = 'email';

$placeholder_mail = 'Tu correo electrónico';

$contact_message = 'Mensaje';

$placeholder_message = 'Tu mensaje';

$contact_preview = 'Confirmar';

$contact_cancel = 'Cancelar';

$contact_send = 'Enviar';

$cookie_disabled_error = 'Por favor habilite las cookies.';

$contact_subject_suffix = 'Consultas de %s - ';

$comment_subject = '%s categoría %s artículo tiene comentario - ';

$contact_success = 'Su mensaje ha sido enviado con éxito.';

$contact_error = 'Envío fallido.';

$time_format = 'F jS, Y h:i';

$present_format = 'F jS';

$seconds_ago = 'Hace %s segundos';

$minutes_ago = 'Hace %s minutos';

$hours_ago = 'Hace %s horas';

$days_ago = 'Hace %s días';

$benchmark_results = '<span class="d-block text-muted text-center small">Total time: %s sec. Memory: %s</span>';


#/images/index.php
$images_title = 'Imágenes - %s';

$images_heading = 'Imágenes <small class="text-muted ml-2">Copia la etiqueta y pega tu artículo.</small>';

$images_aligner = 'Alineación de imagen <small class="text-muted ml-2">Es posible que necesite &lt;div class=clearfix&gt;&lt;/div&gt; para liberar envoltura.</small>';

$noscript = 'Por favor habilite las <strong>Javascript</strong>.';

$align_left = 'Lzquierda';

$align_center = 'Centrar';

$align_right = 'Derecha';

#Number of images per page
$number_of_imgs = 3;

$large_image = 'Grande';

$small_image = 'Pequeño';

$imgs_first= 'Primero';
$imgs_prev = 'Anterior';

$imgs_next = 'Siguiente';
$imgs_last = 'Último';

function hsla($colour, $cal_s=0, $cal_l=0, $a=1)
{
	if ($colour === 'Red')
	{
		$h = 355;
		$s = 70;
		$l = 50;
	}
	elseif ($colour === 'Pink')
	{
		$h = 330;
		$s = 70;
		$l = 70;
	}
	elseif ($colour === 'Brown')
	{
		$h = 10;
		$s = 70;
		$l = 50;
	}
	elseif ($colour === 'YellowGreen')
	{
		$h = 80;
		$s = 60;
		$l = 50;
	}
	elseif ($colour === 'Green')
	{
		$h = 120;
		$s = 60;
		$l = 40;
	}
	elseif ($colour === 'Blue')
	{
		$h = 220;
		$s = 60;
		$l = 60;
	}
	elseif ($colour === 'LightBlue')
	{
		$h = 195;
		$s = 60;
		$l = 60;
	}
	elseif ($colour === 'Purple')
	{
		$h = 250;
		$s = 60;
		$l = 70;
	}
	elseif ($colour === 'Gray')
	{
		$h = 200;
		$s = 5;
		$l = 60;
	}
	elseif ($colour === 'Black')
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
	if ($colour === 'Gray')
		return 'secondary';
	elseif ($colour === 'Black' )
		return 'dark';
	elseif ($colour === 'Brown')
		return 'muted';
	elseif ($colour === 'YellowGreen' || $colour === 'Green')
		return 'success';
	elseif ($colour === 'Pink')
		return 'warning';
	elseif ($colour === 'Red' || $colour === 'Purple')
		return 'danger';
	elseif ($colour === 'Blue' || $colour === 'LightBlue')
		return 'info';
	else
		return 'primary';
}
