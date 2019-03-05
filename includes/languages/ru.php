<?php
setlocale(LC_ALL, 'ru_RU.UTF-8');
date_default_timezone_set('Europe/Moscow');

#Site Name
$site_name = 'kinaga';

# Your mail address
$mail_address = '';

#Your home or office address
$address = '';
$address_title = '';

#Hue: 'Red', 'Pink', 'Brown', 'YellowGreen', 'Green', 'Blue', 'LightBlue', 'Purple',  'MediumGray',  'DimGray', 'Black', or BLANK
$color = 'Black';


#Description: Top page
$meta_description = 'Описание здесь.';

#Subtitle: Top Page H1 and TITLE
$subtitle = '';


#Top Page
$home = 'Главная';


#Sideboxes
$recents = 'Последние статьи';

$category = 'Категория';

$informations = 'Информация';

$recent_comments = 'Комментарии';

$popular_articles = 'Популярные статьи';

$download_contents = 'Загрузки';
$download_subtitle = '';
$download_notice = '';

$contact_us = 'Связаться с нами';
$contact_subtitle = '';
$contact_notice = 'Мы никогда не будем собирать информацию о вас без вашего явного согласия.';

$similar_title = 'Похожие статьи';

$toc = 'Оглавление';

$nav_laquo = '&laquo;';

$nav_raquo = '&raquo;';


$comments_prev = 'Новее';

$comments_next = 'Старшая';

$page_prefix = 'Страница %s';

#Social icons
$social = 'Поделись этим';

$permalink = 'Постоянная ссылка';

$for_html = 'HTML';

$for_wiki = 'Wiki';

$for_forum = 'Forum';


#Separator
$top = '<a class="page-top text-right d-block p-0 small" href="#TOP"> </a>';
$pagetop = 'Перейти наверх';

$last_modified = 'Последнее обновление: %s';

$no_article = 'Статья не найдена.';

$no_categ = 'Категория не существует.';

$error = 'Произошла ошибка';

$not_found = 'Запрошенная вами страница не найдена.';

$more_link_text = 'Прочитайте больше...';

$ellipsis = '...';

$views = '%s Просмотров';

$images_count_title = ' (%s фото)';

$source = 'Источник: %s';

$result = 'Результаты поиска по %s.';

$no_results_found = 'Нет результатов.';

$comments_not_allow = 'Комментарии закрыты.';

$comments_count_title = ' (%s Комментариев)';

$comment_title = 'Комментарии';

$comment_notice = $contact_notice . '';

$comment_counts = '%s Комментариев';

$contact_caution = 'Будут опубликованы только одобренные комментарии.';

#email separator line
$separator = '_______________________________________________';

$comment_acceptance =

	'Чтобы оставить этот комментарий,' . $n .
	'сохранить прикрепленный файл: %s' . $n .
	'и загрузите его в следующую папку' . $n . $n .
	'/contents/%s/%s/comments/';

$contact_name = 'Название';

$placeholder_name = 'Ваше имя';

$contact_mail = 'е-мейл';

$placeholder_mail = 'Ваш адрес электронной почты';

$contact_message = 'Сообщение';

$placeholder_message = 'Твое сообщение';

$contact_preview = 'Конфирмовать';

$contact_cancel = 'Отменить';

$contact_send = 'Послать';

$cookie_disabled_error = 'Пожалуйста, включите куки, чтобы продолжить.';

$contact_subject_suffix = 'Запросы от %s - ';

$comment_subject = '%s категория %s статья имеет комментарий - ';

$contact_success = 'Ваше сообщение было успешно отправлено.';

$contact_error = 'Отправка не удалась.';

$time_format = 'F jS, Y h:i';

$present_format = 'F jS';

$seconds_ago = '%s секунд назад';

$minutes_ago = '%s минут назад';

$hours_ago = '%s часов назад';

$days_ago = '%s дней назад';

$benchmark_results = '<span class="d-block text-muted text-center small">Total time: %s sec. Memory: %s</span>';


#/images/index.php
$images_title = 'Images - %s';

$images_heading = 'Images <small class="text-muted ml-2">Скопируйте тег и вставьте свою статью.</small>';

$images_aligner = 'Выравнивание изображения <small class="text-muted ml-2">Вам может понадобиться &lt;div class=clearfix&gt;&lt;/div&gt; чтобы снять обтекание</small>';

$noscript = 'Пожалуйста, включите <strong>Javascript</strong>.';

$align_left = 'Лево';

$align_center = 'Центр';

$align_right = 'Право';

#Number of images per page
$number_of_imgs = 3;

$large_image = 'Большой';

$small_image = 'Маленький';

$imgs_first= 'Первый';
$imgs_prev = 'Предыдущая';

$imgs_next = 'Следующий';
$imgs_last = 'Прошлой';

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
