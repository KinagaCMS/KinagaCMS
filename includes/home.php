<?php
if (__FILE__ === implode(get_included_files())) exit;
$header .=
'<title>'. $site_name. ($subtitle ? ' - '. $subtitle : ''). ($pages > 1 ? ' - '. sprintf($page_prefix, $pages) : ''). '</title>'. $n.
'<meta name=description content="'. $meta_description. '">'. $n;
if ($subtitle)
	$article .= '<header><h1 class="'. $h1_title[0]. '">'. $site_name. ' <small class="'. $h1_title[1]. '">'. $subtitle. '</small></h1></header>'. $n;
if (is_admin() || is_subadmin())
{
	if (filter_has_var(INPUT_POST, 'create-categ'))
	{
		$create_categ = trim(str_replace($disallow_symbols, $replace_symbols, basename(filter_input(INPUT_POST, 'create-categ'))));
		$create_categ_dir = 'contents/'. $create_categ;
		$current_categ = !filter_has_var(INPUT_POST, 'current-categ-name') ? '' : trim(str_replace($disallow_symbols, $replace_symbols, basename(filter_input(INPUT_POST, 'current-categ-name'))));
		if ($current_categ && is_dir($current_categ_dir = 'contents/'. $current_categ) && $create_categ !== $current_categ && !is_dir($create_categ_dir))
			rename($current_categ_dir, $create_categ_dir);
		elseif (!is_dir($create_categ_dir))
		{
			mkdir($create_categ_dir, 0757);
			counter($userdir. '/create-categ.txt', 1);
		}
		$categ_subtitle = '';
		if (!filter_has_var(INPUT_POST, 'autowrap')) $categ_subtitle .= '<?php nowrap()?>';
		if (filter_has_var(INPUT_POST, 'create-categ-subtitle'))
		{
			$categ_subtitle .= filter_input(INPUT_POST, 'create-categ-subtitle');
			file_put_contents($create_categ_dir. '/index.html', $categ_subtitle, LOCK_EX);
		}
		elseif (!$categ_subtitle && is_file($create_categ_dir. '/index.html')) unlink($create_categ_dir. '/index.html');
		if (!is_file($author_txt = $create_categ_dir. '/author.txt')) file_put_contents($author_txt, $_SESSION['l'], LOCK_EX);
		if (is_dir($create_categ_dir) && (is_admin() || is_author($create_categ_dir)))
		{
			if (filter_has_var(INPUT_POST, 'remove')) array_map('unlink', array_filter(filter_input(INPUT_POST, 'remove', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY), 'is_file'));
		}
		if (isset($_FILES['categ-img']['error'], $_FILES['categ-img']['tmp_name']) && UPLOAD_ERR_OK === $_FILES['categ-img']['error'])
		{
			move_uploaded_file($_FILES['categ-img']['tmp_name'], $create_categ_dir. '/'. ('header' === filter_input(INPUT_POST, 'categ-img-name') ? 'header' : 'background'). ('image/png' === $_FILES['categ-img']['type'] ? '.png' : '.jpg'));
		}
		touch($css. 'index.php', $now);
		exit (header('Location: '. $url. r($create_categ ?? $current_categ). '/'));
	}
	$edit_categ_name = basename(filter_input(INPUT_GET, 'edit', FILTER_SANITIZE_STRING));
	$edit_categ_dir = 'contents/'. $edit_categ_name;
	if ($edit_categ_name && is_dir($edit_categ_dir))
	{
		if (is_admin() || is_author($edit_categ_dir))
		{
			$edit_categ_content = is_file($edit_categ_html = $edit_categ_dir. '/index.html') ? file_get_contents($edit_categ_html) : '';
			if (false !== strpos($edit_categ_content, $nowrap_txt = '<?php nowrap()?>'))
			{
				$edit_categ_content = str_replace($nowrap_txt, '', $edit_categ_content);
				$edit_categ_nowrap = 1;
			}
		}
	}
	if (filter_has_var(INPUT_POST, 'create-sidepage'))
	{
		$create_sidepage_content = '';
		$create_sidepage = !filter_input(INPUT_POST, 'create-sidepage') ? '!index' : '!'. trim(str_replace($disallow_symbols, $replace_symbols, basename(filter_input(INPUT_POST, 'create-sidepage'))));
		if (!filter_has_var(INPUT_POST, 'author') && !filter_has_var(INPUT_POST, 'editor'))
			$create_sidepage_content .= '<?php $author="'. $_SESSION['l']. '"?>';
		if (filter_has_var(INPUT_POST, 'author')) $create_sidepage_content .= '<?php $author="'. basename(filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING)). '"?>';
		if (filter_has_var(INPUT_POST, 'editor')) $create_sidepage_content .= '<?php $editor="'. basename(filter_input(INPUT_POST, 'editor', FILTER_SANITIZE_STRING)). '"?>';

		if (!filter_has_var(INPUT_POST, 'autowrap')) $create_sidepage_content .= '<?php nowrap()?>';
		if (filter_has_var(INPUT_POST, 'create-sidepage-content')) $create_sidepage_content .= filter_input(INPUT_POST, 'create-sidepage-content');
		$create_sidepage_html = 'contents/'. $create_sidepage. '.html';
		$current_sidepage = !filter_has_var(INPUT_POST, 'current-sidepage') ? 'index' : trim(str_replace($disallow_symbols, $replace_symbols, basename(filter_input(INPUT_POST, 'current-sidepage'))));
		if (is_file($current_sidepage_html = 'contents/'. $current_sidepage. '.html') && $create_sidepage !== $current_sidepage && !is_file($create_sidepage_html))
			rename($current_sidepage_html, $create_sidepage_html);
		elseif (!is_file($create_sidepage_html))
		{
			touch($create_sidepage_html);
			counter($userdir. '/create-sidepage.txt', 1);
		}
		if ($create_sidepage_content) file_put_contents($create_sidepage_html, $create_sidepage_content, LOCK_EX);
		exit (header('Location: '. $url. r($create_sidepage ?? $current_sidepage ?? '')));
	}
	$del = !filter_has_var(INPUT_GET, 'delete') ? '' : basename(filter_input(INPUT_GET, 'delete'));
	$rep = !filter_has_var(INPUT_GET, 'post') ? '' : basename(filter_input(INPUT_GET, 'post'));
	if ($del || $rep)
	{
		if (is_file($sidepagefile = 'contents/'. $del. $rep. '.html'))
		{
			ob_start();
			include $sidepagefile;

			if (is_admin() || (isset($_SESSION['l'], $author) && $_SESSION['l'] === $author))
			{
				if ($del && is_file('contents/'. $del. '.html') && rename('contents/'. $del. '.html', 'contents/!'. $del. '.html'))
					exit (header('Location: '. $url. r('!'. $del)));
				if ($rep && is_file('contents/'. $rep. '.html') && rename('contents/'. $rep. '.html', 'contents/'. substr($rep, 1). '.html'))
				{
					$post_sidepage = substr($rep, 1);
					if (!is_admin()) mail($mail_address, $btn[6]. ' - '. h($post_sidepage. ' - '. $site_name), a($url. r($post_sidepage), h($post_sidepage. ' - '. $site_name)));
					exit (header('Location: '. $url. r($post_sidepage)));
				}
			}
			else exit (header('Location: '. $url));
		}
	}

	$edit_sidepage_name = !filter_has_var(INPUT_GET, 'sedit') ? '' : basename(filter_input(INPUT_GET, 'sedit'));
	if ($edit_sidepage_name && is_file($edit_sidepage_html = 'contents/'. $edit_sidepage_name. '.html') && isset($edit_sidepage_name[0]) && '!' === $edit_sidepage_name[0])
	{
		$edit_sidepage_content = file_get_contents($edit_sidepage_html);
		if (false !== strpos($edit_sidepage_content, $nowrap_txt = '<?php nowrap()?>'))
		{
			$edit_sidepage_content = str_replace($nowrap_txt, '', $edit_sidepage_content);
			$edit_sidepage_nowrap = 1;
		}
		if (preg_match('/<\?php \$author="(.*?)"\?>/', $edit_sidepage_content, $author))
			$edit_sidepage_content = preg_replace('/<\?php \$author.*?\?>/', '', $edit_sidepage_content);
		if (preg_match('/<\?php \$editor="(.*?)"\?>/', $edit_sidepage_content, $editor))
			$edit_sidepage_content = preg_replace('/<\?php \$editor.*?\?>/', '', $edit_sidepage_content);
		$javascript .= '$("#sidepage-form")';

		if (!isset($author[1]) && !isset($editor[1]))
			$javascript .= '.append("<input type=hidden name=author value=\"'. $_SESSION['l']. '\">")';

		if (isset($author[1], $editor[1]))
			$javascript .=
			'.append("<input type=hidden name=author value=\"'. $author[1]. '\">").append("<input type=hidden name=editor value=\"'. $editor[1]. '\">")';

		if (isset($author[1]) && !isset($editor[1]) && ($_SESSION['l'] !== $author[1]))
			$javascript .=
			'.append("<input type=hidden name=author value=\"'. $author[1]. '\">").append("<input type=hidden name=editor value=\"'. $_SESSION['l']. '\">");';
	}
	$article .=
	'<div class="bg-light p-3 mb-5"><div class=accordion id=admin-menu>
	<a class="d-inline-block p-3" href=# data-toggle=collapse data-target=#categ-form aria-expanded='. ($edit_categ_name ? 'true' : 'false'). ' aria-controls=categ-form>'. $admin_menus[0]. '</a>
	<a class="d-inline-block p-3" href=# data-toggle=collapse data-target=#sidepage-form aria-expanded='. ($edit_sidepage_name ? 'true' : 'false'). ' aria-controls=sidepage-form>'. $admin_menus[10]. '</a>'.
	'<form id=categ-form class="collapse'. ($edit_categ_name ? ' show' : ''). ' bg-light p-4" data-parent="#admin-menu" method=post enctype="multipart/form-data">'.
	'<div class="d-block text-muted small max"></div>'. $n.
	'<input class="form-control creates" type=text name=create-categ id=create-categ required placeholder="'. $admin_menus[1]. '"'. (isset($edit_categ_name) ? ' value="'. $edit_categ_name. '"' : ''). '>'. $n.
	(isset($edit_categ_name) ? '<input type=hidden name=current-categ-name value="'. $edit_categ_name. '">' : '').
	'<div class="d-flex align-items-end justify-content-between mt-4 mb-2">'.
	'<div class="custom-control custom-checkbox">'.
	'<input class=custom-control-input type=checkbox id=c-autowrap name=autowrap value=true'. (isset($edit_categ_nowrap) && $edit_categ_nowrap ? '' : ' checked'). '>'.
	'<label class=custom-control-label for=c-autowrap>'. $html_assist[1]. '</label>'.
	'</div>'.
	'</div>'.
	'<textarea class="form-control mb-4" name=create-categ-subtitle placeholder="'. $admin_menus[9]. '" rows=10>'. ($edit_categ_content ?? ''). '</textarea>'.
	'<div class="form-row align-items-center my-4">'.
	'<div class="col-auto my-1">'.
	'<select class="form-control mr-sm-2" name=categ-img-name>';
	foreach (['header', 'background'] as $categ_img)
		$article .= '<option value="'. $categ_img. '">'. $categ_img. '</option>';
	$article .=
	'</select>'.
	'</div>'.
	'<div class="col-auto my-1"><input class=form-control-file type=file name=categ-img accept=".jpg,.png"></div>'.
	'</div>';
	if ($categ_imgs = glob($edit_categ_dir. '/*.{jpg,png}', GLOB_BRACE))
	{
		$article .=
		'<fieldset class="form-group bg-light px-4 my-4"><h2 class="h5 my-4">'. $btn[4]. '</h2>';
		foreach ($categ_imgs as $current_img)
		{
			$current_img_name = basename($current_img);
			$article .=
			'<div class="form-check my-3">'.
			'<input class=form-check-input name=remove[] type=checkbox value="'. $current_img. '" id="i-'. $current_img_name. '">'.
			'<label class=form-check-label for="i-'. $current_img_name. '">'. $current_img_name. '</label>'.
			'</div>';
		}
		$article .= '</fieldset>';
	 }
	$article .=
	'<input class="btn btn-primary btn-block" type=submit value="'. $btn[8]. '">'.
	'</form>'.
	'<form id="sidepage-form" class="collapse'. ($edit_sidepage_name ? ' show' : ''). ' bg-light p-4" data-parent="#admin-menu" method=post>'.
	'<div class="d-block text-muted small max"></div>'. $n.
	'<input class="form-control creates" type=text name=create-sidepage id=create-sidepage placeholder="'. $admin_menus[11]. '"
	'. (isset($edit_sidepage_name) ? ' value="'. substr($edit_sidepage_name, 1). '"' : ''). '>'. $n.
	(isset($edit_sidepage_name) ? '<input type=hidden name=current-sidepage value="'. $edit_sidepage_name. '">' : '').
	'<div class="d-flex align-items-end justify-content-between mt-4 mb-2">'.
	'<div class="custom-control custom-checkbox">'.
	'<input class=custom-control-input type=checkbox id=s-autowrap name=autowrap value=true'. (isset($edit_sidepage_nowrap) && $edit_sidepage_nowrap ? '' : ' checked'). '>'.
	'<label class=custom-control-label for=s-autowrap>'. $html_assist[1]. '</label>'.
	'</div>'.
	'</div>'.
	'<textarea class="form-control mb-4" name=create-sidepage-content placeholder="'. $admin_menus[12]. '" rows=10>'. ($edit_sidepage_content ?? ''). '</textarea>'.
	'<input class="btn btn-primary btn-block" type=submit value="'. $btn[8]. '">'.
	'</form>'.
	'</div></div>';
	html_assist();
	$javascript .= '$(".collapse").on("show.bs.collapse",function(){t=$(this).find("textarea");t.attr("id","textarea").prev().children().last().attr("id","i");$("#h").insertBefore($("#i"))});$(".collapse").on("hidden.bs.collapse",function(){$(this).find("textarea").removeAttr("id").prev().children().last().removeAttr("id");$("#h").insertBefore($("#i"))});'. (filter_has_var(INPUT_GET, 'edit') || filter_has_var(INPUT_GET, 'sedit') ? '$("#h").insertBefore($("form[class*=\"show\"]>textarea").attr("id","textarea").prev().children().last().attr("id","i"));' : '$(".collapse").collapse("show");');
}
if (is_file($index_file = 'contents/index.html'))
{
	$article .= '<article class="'. $article_wrapper_class. ' article clearfix">';
	ob_start();
	include $index_file;
	$article .= trim(ob_get_clean());
	$article .= '</article>'. $n;
}
else	if ($glob_files = glob($glob_dir. 'index.html', GLOB_NOSORT))
{
	usort($glob_files, 'sort_time');
	if (1 === $index_type)
	{
		$count_glob_files = count($glob_files);
		$page_ceil = ceil($count_glob_files / $default_sections_per_page);
		$max_pages = min($pages, $page_ceil);
		$sections_in_home = array_slice($glob_files, ($max_pages - 1) * $default_sections_per_page, $default_sections_per_page);
		if ($count_glob_files > $default_sections_per_page) pager($max_pages, $page_ceil);
		$article .= '<div class="'. $index_class. '">';
		foreach($sections_in_home as $sections)
		{
			$all_link = explode('/', $sections);
			$categ_link = r(get_categ($sections));
			$title_link = r($t = get_title($sections));
			$categ_dir = dirname(dirname($sections));
			$article_dir = dirname($sections);
			$filemtime = filemtime($sections);
			$counter = is_file($counter_txt = $article_dir. '/counter.txt') ?
			'<span class=card-link>'. sprintf($views, (int)file_get_contents($counter_txt)). '</span>' : '';
			if (is_dir($default_imgs_dir = $article_dir. '/images') && $glob_default_imgs = glob($default_imgs_dir. $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
			{
				sort($glob_default_imgs);
				$default_image = img($glob_default_imgs[0]);
				$count_images = count($glob_default_imgs);
			}
			else
				$default_image = $count_images = '';
			if (is_dir($default_background_dir = $article_dir. '/background-images'))
				$count_background_images = count(glob($default_background_dir. $glob_imgs, GLOB_NOSORT+GLOB_BRACE));
			else
				$count_background_images = 0;
			$total_images = (int)$count_images + (int)$count_background_images;
			preg_match('/width="(\d+)"/', $default_image, $width);
			$article .=
			'<div class="'. $index_wrapper_class. '">'. $n.
			$default_image.
			'<div class="'. $index_content_class. '">'. $n.
			'<h2 class="'. $index_title_class. '">'.
			(is_admin() || is_author($article_dir) ? '!' !== $t[0] ?
				'<a class="btn btn-sm btn-danger mr-2" href="'. $url. $categ_link. '/&amp;delete='. $title_link. '">'. $btn[4]. '</a>'
			:
				'<a class="btn btn-sm btn-success mr-2" href="'. $url. $categ_link. '/&amp;post='. $title_link. '">'. $btn[6]. '</a>' : '').
			'<a href="'. $url. $categ_link. '/'. $title_link. '">'. ht($all_link[2]);
			if ($total_images > 0)
				$article .= '<small>'. sprintf($images_count_title, $total_images). '</small>';
			if (is_file($ticket) && (is_file($categ_dir. '/login.txt') || is_file($article_dir. '/login.txt')) && !isset($_SESSION['l']))
				$article .= '<sup class="d-inline-block lock"></sup>';
			$article .=
			'</a></h2>';
			if ($use_summary)
				$article .= '<p class="index-summary wrap">'. get_summary($sections). '</p>'. $n;
			$article .=
			'<span class="'. $index_categ_link_class. '"><a href="'. $url. $categ_link. '/" class=card-link>'. h($all_link[1]). '</a></span>'. $n.
			'</div>'. $n.
			'<div class="'. $index_footer_class. '">';
			if (is_file($author_file = $article_dir. '/author.txt') && is_dir($author_prof = 'users/'. basename(file_get_contents($author_file)). '/prof/'))
				$article .= '<a class=card-link href="'. $url. '?user='. str_rot13(basename(dirname($author_prof))). '">'. avatar($author_prof, 20). ' '. handle($author_prof). '</a>';
			$article .= '<time class=card-link datetime="'. date('c', $filemtime). '">'. timeformat($filemtime, $intervals). '</time>';
			if (is_file($counter_txt = $article_dir. '/counter.txt'))
				$article .= '<span class=card-link>'. sprintf($views, (int)file_get_contents($counter_txt)). '</span>';
			if ($use_comment && is_dir($comments_dir = $article_dir. '/comments'))
				$article .=
				'<a class=card-link href="'. $url. $categ_link. '/'. $title_link. '#comment">'. sprintf($comment_counts, count(glob($comments_dir. '/*'. $delimiter. '*.txt', GLOB_NOSORT))). '</a>';
			$article .=
			'</div></div>'. $n;
		}
		$article .= '</div>';
		if ($count_glob_files > $default_sections_per_page) pager($max_pages, $page_ceil);
	}
	else
	{
		$stylesheet .= '#side div[id],#index>div{margin:.75rem;flex-basis:32%}@media(max-width:1200px){#side div[id],#index>div{flex-basis:49%}}@media(max-width:767px){.index{flex-direction:column}.index div{width:100%!important}#side div[id],#index>div{flex-basis:100%}}';
		if (5 === $index_type)
		{
			sideless(1,1);
			nowrap();
			$article .= '<section class="bg-primary p-5 text-white"><div class="container-fluid">';
		}
		$article .= '<div id=index class="d-flex '. (4 === $index_type ? 'flex-column' : 'justify-content-between'). ' flex-wrap p-0">';
		foreach($glob_files as $k => $a)
		{
			$c = get_categ($a);
			$t = get_title($a);

			if ($i = glob('contents/'. $c. '/'. $t. '/images'. $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
			{
				sort($i);
				if (2 === $index_type)
					$a = img($i[0], 'd-block mx-auto rounded-circle', 0, .5). '<span class="d-inline-block mt-2">'. h($t). '</span>';
				elseif (3 === $index_type)
					$a = img($i[0], 'rounded-sm'). '<br>'. h($t);
				elseif (4 === $index_type)
				{
					$a = img($i[0], '', 0, .4). '<br>'. h($t);
				}
				elseif (5 === $index_type)
				{
					$b[] =
					'<div class="order-a flex-fill text-center">'. img($i[0], 'w-100'). '</div>'.
					'<div class="order-b flex-fill mb-5">'.
					'<h2 class="my-3">'. h($t). '</h2>'.
					'<p>'. get_summary('contents/'. $c. '/'. $t. '/index.html'). '</p>'.
					'<p class=text-right><a class="btn btn-secondary" href="'. $url. r($c. '/'. $t). '">'. $btn[9].'</a></p>'.
					'</div>';
				}
			}
			else
				$a = '<a href="'. $url. r($c. '/'. $t). '">'. h($t). '</a>';
			if (is_file($ticket) && (is_file('contents/'. $c. '/login.txt') || is_file('contents/'. $c. '/'. $t. '/login.txt')) && !isset($_SESSION['l']))
				$a .= '<sup class="d-inline-block lock"></sup>';
			if (is_admin() || is_author('contents/'. $c. '/'. $t. '/'))
			{
				if ('!' !== $t[0])
					$a .= '<a class="btn btn-sm btn-danger ml-2" href="'. $url. r($c). '/&amp;delete='. r($t). '">'. $btn[4]. '</a>';
				else
					$a .= '<a class="btn btn-sm btn-success ml-2" href="'. $url. r($c). '/&amp;post='. r($t). '">'. $btn[6]. '</a>';
			}
			if (5 !== $index_type) $b['<a href="'. $url. r($c). '/">'. h($c). '</a>'][] = $a;
		}
		if (isset($b))
		{
			if (5 === $index_type)
			{
				$j = 1;
				$ab = array_filter($b);
				foreach ($ab as $ad)
				{
					$article .= '<div class="col-lg-4 mx-0">'. $ad. '</div>';
					if ($index_items <= $j) break;
					++$j;
				}
				$article .= '</div></section>';
				$ac = ['bg-white text-dark', 'bg-success text-white', 'bg-dark text-white'];
				foreach (array_rand($ab, 3) as $k => $e)
				{
					$f = $b[$e];
					$article .=
					'<section class="'. $ac[$k]. '">'.
					'<div class="index d-flex justify-content-between">';
					$f = str_replace('<div class="', '<div class="w-50 ', $f);
					if (0 === $k) $f = str_replace('<h2 class="', '<h2 class="text-dark ', $f);
					if (1 === $k) $f = str_replace(['order-a', 'order-b'], ['order-md-1', 'order-md-0 px-5 py-3'], $f);
					else $f = str_replace('order-b', 'px-5 py-3', $f);
					$article .= $f.
					'</div>'.
					'</section>';
				}
			}
			else
			{
				foreach($b as $k => $v)
				{
					$s = array_slice($v, 0, $index_items);
					if (2 === $index_type)
						$article .= '<div class="col-md p-4 text-center bg-light"><span class="h5 border-bottom pb-1">'. $k. '</span><ul class="mt-4 list-group list-group-flush"><li class="list-group-item bg-transparent">'. implode('</li><li class="list-group-item bg-transparent">', $s). '</li></ul></div>';
					if (3 === $index_type)
						$article .= '<div class="bg-light p-0 mx-0"><div class="h5 bg-light p-3">'. $k. '</div><div class=m-4>'. implode('</div><div class=m-4>', $s). '</div></div>';
					if (4 === $index_type)
						$article .= '<div class="mb-4"><div class="h3 border-bottom px-2 py-1 my-3">'. $k. '</div><div class=row><div class="m-2 col-md-auto">'. implode('</div><div class="m-2 col-md-auto">', $s). '</div></div></div>';
				}
			}
		}
		$article .= '</div>';
		if (5 === $index_type) $article .= '</div></section>';
	}
}
else
{
	$header .= '<title>'. $site_name. '</title>'. $n;
	if (!$index_file || !$contents) $article .= '<img src="'. $url. 'images/icon.php" class="d-block w-75 p-3 m-auto" style="opacity:.08;max-width:50%">';
}
