<?php
if (__FILE__ === implode(get_included_files())) exit;
$header .=
'<title>'. $site_name. ($subtitle ? ' - '. $subtitle : ''). ($pages > 1 ? ' - '. sprintf($page_prefix, $pages) : ''). '</title>'.
'<meta name=description content="'. $meta_description. '">';
if ($subtitle) $meta_description = $subtitle;
if (is_admin() || is_subadmin())
{
	$assist_error = false;
	$article .= $create_with_kisou. '<div class="modal fade" id=kisou tabindex=-1 aria-hidden=true><div class="modal-dialog modal-fullscreen"><div class=modal-content><div class=modal-header><h5 class=modal-title>'. $admin_menus[0]. ' / '. $admin_menus[10]. '</h5><button type=button class=btn-close data-bs-dismiss=modal></button></div><div class=modal-body><fieldset class=admin>';
	if (filter_has_var(INPUT_POST, 'create-categ'))
	{
		$create_categ = filter_input(INPUT_POST, 'create-categ', FILTER_CALLBACK, ['options' => 'trim_str_replace_basename']);
		$create_categ_dir = 'contents/'. $create_categ;
		$current_categ = !filter_has_var(INPUT_POST, 'current-categ-name') ? '' : filter_input(INPUT_POST, 'current-categ-name', FILTER_CALLBACK, ['options' => 'trim_str_replace_basename']);
		$current_categ_dir = 'contents/'. $current_categ;
		$current_categ_author = !is_file($current_categ_author_txt = $current_categ_dir. '/author.txt') ? '' : file_get_contents($current_categ_author_txt);
		$create_categ_check = false;
		$create_categ_login_txt = $create_categ_dir. '/login.txt';
		if (!$current_categ && !is_dir($create_categ_dir) && !is_dir('contents/!'. $create_categ))
		{
			$create_categ_check = true;
			mkdir($create_categ_dir, 0757);
			counter($userdir. '/create-categ.txt', 1);
		}
		elseif (is_dir($current_categ_dir) && !is_dir($create_categ_dir) && !is_dir('contents/!'. $create_categ) && !is_dir('contents/'. ltrim($create_categ, '!')))
		{
			$create_categ_check = true;
			rename($current_categ_dir, $create_categ_dir);
		}
		elseif ($create_categ === $current_categ)
		{
			 $create_categ_check = true;
		 }
		if ($create_categ_check)
		{
			if (filter_has_var(INPUT_POST, 'require-login') && filter_has_var(INPUT_POST, 'login-textarea'))
				file_put_contents($create_categ_login_txt, filter_input(INPUT_POST, 'login-textarea', FILTER_CALLBACK, ['options' => 'scriptentities']));
			elseif (!filter_has_var(INPUT_POST, 'require-login') && is_file($create_categ_login_txt)) unlink($create_categ_login_txt);
			$categ_subtitle = '';
			if (!filter_has_var(INPUT_POST, 'autowrap')) $categ_subtitle .= '<?php nowrap()?>';
			if (filter_has_var(INPUT_POST, 'create-categ-subtitle'))
			{
				$categ_subtitle .= filter_input(INPUT_POST, 'create-categ-subtitle', FILTER_CALLBACK, ['options' => 'scriptentities']);
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
				touch($tpl_dir. 'css/index.php', $now);
			}
			exit (header('Location: '. $url. r($create_categ ?? $current_categ). '/'));
		}
		else
		{
			$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
			$assist_error = true;
		}
	}
	$edit_categ_name = !filter_has_var(INPUT_GET, 'edit') ? '' : filter_input(INPUT_GET, 'edit', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
	$edit_categ_dir = 'contents/'. $edit_categ_name;
	$edit_categ_html = $edit_categ_dir. '/index.html';
	$edit_categ_login_txt = 'contents/'. $edit_categ_name. '/login.txt';
	if ($edit_categ_name && is_dir($edit_categ_dir))
	{
		if (is_admin() || is_author($edit_categ_dir))
		{
			$edit_categ_content = !is_file($edit_categ_html) ? '' : file_get_contents($edit_categ_html);
			$edit_categ_login_txt_content = !is_file($edit_categ_login_txt) ? '' : file_get_contents($edit_categ_login_txt);
			if (str_contains($edit_categ_content, $nowrap_txt = '<?php nowrap()?>'))
			{
				$edit_categ_content = str_replace($nowrap_txt, '', $edit_categ_content);
				$edit_categ_nowrap = 1;
			}
			if (str_contains($edit_categ_content, '&lt;') && str_contains($edit_categ_content, '&gt;'))
				$edit_categ_content = str_replace(['&lt', '&gt'], ['&amp;lt', '&amp;gt'], $edit_categ_content);
		}
	}
	if (filter_has_var(INPUT_POST, 'create-sidepage'))
	{
		$create_sidepage_content = '';
		if (!filter_has_var(INPUT_POST, 'author') && !filter_has_var(INPUT_POST, 'editor')) $create_sidepage_content .= '<?php $author="'. $_SESSION['l']. '"?>';
		if (filter_has_var(INPUT_POST, 'author')) $create_sidepage_content .= '<?php $author="'. filter_input(INPUT_POST, 'author', FILTER_CALLBACK, ['options' => 'strip_tags_basename']). '"?>';
		if (filter_has_var(INPUT_POST, 'editor')) $create_sidepage_content .= '<?php $editor="'. filter_input(INPUT_POST, 'editor', FILTER_CALLBACK, ['options' => 'strip_tags_basename']). '"?>';
		if (!filter_has_var(INPUT_POST, 'autowrap')) $create_sidepage_content .= '<?php nowrap()?>';
		if (filter_has_var(INPUT_POST, 'create-sidepage-content')) $create_sidepage_content .= filter_input(INPUT_POST, 'create-sidepage-content', FILTER_CALLBACK, ['options' => 'scriptentities']);
		$create_sidepage = !filter_input(INPUT_POST, 'create-sidepage') ? '!index' : '!'. filter_input(INPUT_POST, 'create-sidepage', FILTER_CALLBACK, ['options' => 'trim_str_replace_basename']);		$create_sidepage_html = 'contents/'. $create_sidepage. '.html';
		$current_sidepage = !filter_has_var(INPUT_POST, 'current-sidepage') ? 'index' : filter_input(INPUT_POST, 'current-sidepage', FILTER_CALLBACK, ['options' => 'trim_str_replace_basename']);
		$current_sidepage_html = 'contents/'. $current_sidepage. '.html';
		if (!is_file($create_sidepage_html) && !is_file($current_sidepage_html) && !is_file('contents/'. substr($create_sidepage, 1). '.html') && !is_file('contents/'. substr($current_sidepage, 1). '.html'))
		{
			counter($userdir. '/create-sidepage.txt', 1);
			file_put_contents($create_sidepage_html, $create_sidepage_content, LOCK_EX);
			exit (header('Location: '. $url. r($create_sidepage ?? '')));
		}
		elseif (is_file($current_sidepage_html))
		{
			ob_start();
			include $current_sidepage_html;
			$current_sidepage_content = ob_get_clean();
			if (isset($author))
			{
				if ($author !== $_SESSION['l'] && !is_admin())
				{
					$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
					$assist_error = true;
				}
				elseif ($current_sidepage_html === $create_sidepage_html)
				{
					file_put_contents($create_sidepage_html, $create_sidepage_content, LOCK_EX);
					exit (header('Location: '. $url. r($create_sidepage ?? $current_sidepage ?? '')));
				}
				elseif ($current_sidepage_html !== $create_sidepage_html && !is_file($create_sidepage_html) && !is_file('contents/'. substr($create_sidepage, 1). '.html'))
				{
					rename($current_sidepage_html, $create_sidepage_html);
					file_put_contents($create_sidepage_html, $create_sidepage_content, LOCK_EX);
					exit (header('Location: '. $url. r($create_sidepage ?? $current_sidepage ?? '')));
				}
				else
				{
					$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
					$assist_error = true;
				}
			}
			else
			{
				$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
				$assist_error = true;
			}
		}
		else
		{
			$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
			$assist_error = true;
		}
	}
	$del = !filter_has_var(INPUT_GET, 'delete') ? '' : filter_input(INPUT_GET, 'delete', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
	$rep = !filter_has_var(INPUT_GET, 'post') ? '' : filter_input(INPUT_GET, 'post', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
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
					if (!is_admin()) mail($mail_address, $btn[6]. ' - '. h($post_sidepage. ' - '. $site_name), a($url. r($post_sidepage), $post_sidepage. ' - '. $site_name));
					exit (header('Location: '. $url. ('index' !== $post_sidepage ? '' : '?page='). r($post_sidepage)));
				}
			}
			else exit (header('Location: '. $url));
		}
	}
	$edit_sidepage_name = !filter_has_var(INPUT_GET, 'sedit') ? '' : filter_input(INPUT_GET, 'sedit', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
	if ($edit_sidepage_name && is_file($edit_sidepage_html = 'contents/'. $edit_sidepage_name. '.html') && isset($edit_sidepage_name[0]) && '!' === $edit_sidepage_name[0])
	{
		$edit_sidepage_content = file_get_contents($edit_sidepage_html);
		if (str_contains($edit_sidepage_content, $nowrap_txt = '<?php nowrap()?>'))
		{
			$edit_sidepage_content = str_replace($nowrap_txt, '', $edit_sidepage_content);
			$edit_sidepage_nowrap = 1;
		}
		if (str_contains($edit_sidepage_content, '&lt;') && str_contains($edit_sidepage_content, '&gt;'))
			$edit_sidepage_content = str_replace(['&lt', '&gt'], ['&amp;lt', '&amp;gt'], $edit_sidepage_content);
		if (preg_match('/<\?php \$author="(.*?)"\?>/', $edit_sidepage_content, $author))
			$edit_sidepage_content = preg_replace('/<\?php \$author.*?\?>/', '', $edit_sidepage_content);
		if (preg_match('/<\?php \$editor="(.*?)"\?>/', $edit_sidepage_content, $editor))
			$edit_sidepage_content = preg_replace('/<\?php \$editor.*?\?>/', '', $edit_sidepage_content);
	}
	$article .=
	'<ul class="nav nav-tabs" id=admin-menu role=tablist>'.
	'<li class=nav-item role=presentation>'.
	'<a class=nav-link id=categ-form-tab data-bs-toggle=tab data-bs-target=#categ-form href=#categ-form role=tab aria-controls=categ-form aria-selected='. ($edit_categ_name ? 'true' : 'false'). '>'. $admin_menus[0]. '</a>'.
	'</li>'.
	'<li class=nav-item role=presentation>'.
	'<a class=nav-link id=sidepage-form-tab data-bs-toggle=tab data-bs-target=#sidepage-form href=#sidepage-form role=tab aria-controls=sidepage-form aria-selected='. ($edit_sidepage_name ? 'true' : 'false'). '>'. $admin_menus[10]. '</a>'.
	'</li>'.
	'</ul>'.
	'<div class="tab-content p-3">'.
	'<form id=categ-form class="tab-pane'. ($edit_categ_name ? ' show active' : ''). '" aria-labelledby=categ-form-tab role=tabpanel method=post enctype="multipart/form-data">'.
	'<div class="d-block text-muted small max"></div>'.
	'<input class="form-control creates" type=text name=create-categ id=create-categ maxlength='. $title_length. ' required placeholder="'. $admin_menus[1]. '"'. (isset($edit_categ_name) ? ' value="'. $edit_categ_name. '"' : ''). '>'.
	(isset($edit_categ_name) ? '<input type=hidden name=current-categ-name value="'. $edit_categ_name. '">' : '').
	'<div class="d-flex align-items-end justify-content-between my-2">'.
	'<div class="form-check me-4 assist">'.
	'<input class=form-check-input type=checkbox id=c-autowrap name=autowrap value=true'. (isset($edit_categ_nowrap) && $edit_categ_nowrap ? '' : ' checked'). '>'.
	'<label class=form-check-label for=c-autowrap>'. $html_assist[1]. '</label>'.
	'</div>'.
	'<div class=form-check>'.
	'<input class=form-check-input type=checkbox id=require-login name=require-login value=true'. (is_file($edit_categ_login_txt) ? ' checked' : ''). '>'.
	'<label class=form-check-label for=require-login>'. $form_label[6]. '</label>'.
	'</div>'.
	'</div>'.
	'<textarea class="form-control mb-4" name=create-categ-subtitle placeholder="'. $admin_menus[9]. '" rows=10>'. ($edit_categ_content ?? ''). '</textarea>'.
	'<div class="input-group my-4">'.
	'<select class=form-select name=categ-img-name>';
	foreach (['header', 'background'] as $categ_img)
		$article .= '<option value="'. $categ_img. '">'. $categ_img. '</option>';
	$article .=
	'</select>'.
	'<input class=form-control type=file name=categ-img accept=".jpg,.png">'.
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
	'<textarea class="form-control mb-4" name=login-textarea id=login-textarea placeholder="'. $admin_menus[16]. '" rows=10>'. ($edit_categ_login_txt_content ?? ''). '</textarea>'.
	'<div class=modal-footer><button type=button class="btn btn-secondary" data-bs-dismiss=modal>'. $btn[13]. '</button><input class="btn btn-primary d-block" type=submit value="'. $btn[8]. '"></div>'.
	'</form>'.
	'<form id=sidepage-form class="tab-pane'. ($edit_sidepage_name ? ' show active' : ''). '" aria-labelledby=sidepage-form-tab role=tabpanel method=post>'.
	'<div class="d-block text-muted small max"></div>'.
	'<input class="form-control creates" type=text name=create-sidepage id=create-sidepage maxlength='. $title_length. ' placeholder="'. $admin_menus[11]. '" '. (isset($edit_sidepage_name) ? ' value="'. substr($edit_sidepage_name, 1). '"' : ''). '>'.
	(isset($edit_sidepage_name) ? '<input type=hidden name=current-sidepage value="'. $edit_sidepage_name. '">' : '').
	'<div class="d-flex align-items-end justify-content-between my-2">'.
	'<div class="form-check assist">'.
	'<input class=form-check-input type=checkbox id=s-autowrap name=autowrap value=true'. (isset($edit_sidepage_nowrap) && $edit_sidepage_nowrap ? '' : ' checked'). '>'.
	'<label class=form-check-label for=s-autowrap>'. $html_assist[1]. '</label>'.
	'</div>'.
	'</div>'.
	'<textarea class="form-control mb-4" name=create-sidepage-content placeholder="'. $admin_menus[12]. '" rows=10>'. ($edit_sidepage_content ?? ''). '</textarea>';
	if (!isset($author[1]) && !isset($editor[1])) $article .= '<input type=hidden name=author value='. $_SESSION['l']. '>';
	if (isset($author[1], $editor[1])) $article .= '<input type=hidden name=author value='. $author[1]. '><input type=hidden name=editor value='. $editor[1]. '>';
	if ((isset($author[1]) && !isset($editor[1])) && ($_SESSION['l'] !== $author[1])) $article .= '<input type=hidden name=author value='. $author[1]. '><input type=hidden name=editor value='. $_SESSION['l']. '>';
	$article .=
	'<div class=modal-footer><button type=button class="btn btn-secondary" data-bs-dismiss=modal>'. $btn[13]. '</button><input class="btn btn-primary" type=submit value="'. $btn[8]. '"></div>'.
	'</form>'.
	'</div>'.
	'</fieldset></div></div></div></div>';
	html_assist();
	$javascript .= 'function setAssist(e){document.querySelector(e).getElementsByTagName("textarea")[0].id="textarea";document.querySelector(e).querySelector(".assist").id="i";document.getElementById("i").parentNode.insertBefore(document.getElementById("h"),document.getElementById("i"))}document.querySelector("#admin-menu.nav-tabs").addEventListener("hidden.bs.tab",e=>{document.querySelector(e.target.getAttribute("data-bs-target")).querySelector("textarea").removeAttribute("id");document.querySelector(e.target.getAttribute("data-bs-target")).querySelector(".assist").removeAttribute("id");setAssist(e.relatedTarget.getAttribute("data-bs-target"))});rl=document.getElementById("require-login"),cl=document.getElementById("login-textarea");window.addEventListener("load",loginCheck);rl.addEventListener("change",loginCheck);clone=document.getElementById("h").cloneNode(true);clone.id="ch";clone.classList.add("mb-2");footer.appendChild(clone);cl.parentNode.insertBefore(document.getElementById("ch"),cl);function loginCheck(){if(true!==rl.checked){cl.style.display="none";ch.style.display="none"}else{cl.style.display="block";ch.style.display="block"}}'.
	(filter_has_var(INPUT_GET, 'edit') || filter_has_var(INPUT_GET, 'sedit') ? 'editid="#"+document.querySelector(".tab-pane.show.active").getAttribute("aria-labelledby");document.querySelector(editid).classList.add("active");setAssist(".tab-pane.show.active");new bootstrap.Modal(document.getElementById("kisou")).show();' : 'setAssist(document.getElementById("categ-form-tab").getAttribute("data-bs-target"));new bootstrap.Tab(document.getElementById("categ-form-tab")).show();').
	(!$assist_error ? '' : 'new bootstrap.Modal(document.getElementById("kisou")).show();');
}
if (is_file($index_file = 'contents/index.html'))
{
	$article .= '<article class="'. $article_wrapper_class. ' article clearfix" id=article>';
	ob_start();
	include $index_file;
	$article .= str_replace($line_breaks, '&#10;', ob_get_clean());
	$article .= '</article>';
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
			if (is_dir($default_imgs_dir = $article_dir. '/images') && $glob_default_imgs = glob($default_imgs_dir. $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
			{
				sort($glob_default_imgs);
				$default_image = img($glob_default_imgs[0]);
				$count_images = count($glob_default_imgs);
			}
			else
				$default_image = $count_images = '';
			if (is_dir($default_background_dir = $article_dir. '/background-images') && $glob_default_background_imgs = glob($default_background_dir. $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
			{
				rsort($glob_default_background_imgs);
				$default_background_image = img($glob_default_background_imgs[0]);
				$count_background_images = count($glob_default_background_imgs);
			}
			else
				$default_background_image = $count_background_images = '';
			$total_images = (int)$count_images + (int)$count_background_images;
			preg_match('/width="(\d+)"/', $default_image, $width);
			$article .=
			'<div class='. $index_column_class. '>'.
			'<div class="'. $index_wrapper_class. '">'.
			($default_image ?: $default_background_image).
			'<div class="'. $index_content_class. '">'.
			'<h2 class="'. $index_title_class. '">'.
			(is_admin() || is_author($article_dir) ? '!' !== $t[0] ?
				'<a class="btn btn-sm btn-danger me-2" href="'. $url. $categ_link. '/&amp;delete='. $title_link. '">'. $btn[4]. '</a>'
			:
				'<a class="btn btn-sm btn-success me-2" href="'. $url. $categ_link. '/&amp;post='. $title_link. '">'. $btn[6]. '</a>' : '').
			'<a href="'. $url. $categ_link. '/'. $title_link. '">'. ht($all_link[2]);
			if ($total_images > 0)
				$article .= '<small>'. sprintf($images_count_title, $total_images). '</small>';
			if (is_file($ticket) && (is_file($categ_dir. '/login.txt') || is_file($article_dir. '/login.txt')) && !isset($_SESSION['l']))
				$article .= '<sup class="d-inline-block lock"></sup>';
			$article .=
			'</a></h2>';
			if ($use_summary)
				$article .= '<p class="index-summary wrap">'. get_summary($sections). '</p>';
			$article .=
			'<span class="'. $index_categ_link_class. '"><a href="'. $url. $categ_link. '/" class=card-link>'. h($all_link[1]). '</a></span>'.
			'</div>'.
			'<div class="'. $index_footer_class. '">';
			if (is_file($author_file = $article_dir. '/author.txt') && is_dir($author_prof = 'users/'. basename(file_get_contents($author_file)). '/prof/'))
				$article .= '<a class=card-link href="'. $url. '?user='. str_rot13(basename(dirname($author_prof))). '">'. avatar($author_prof, 20). ' '. handle($author_prof). '</a>';
			$article .= '<time class=card-link datetime="'. date('c', $filemtime). '">'. timeformat($filemtime, $intervals). '</time>';
			if (is_file($counter_txt = $article_dir. '/counter.txt'))
				$article .= '<span class=card-link>'. sprintf($views, size_unit((int)file_get_contents($counter_txt), false)). '</span>';
			if ($use_comment && is_dir($comments_dir = $article_dir. '/comments'))
				$article .=
				'<a class=card-link href="'. $url. $categ_link. '/'. $title_link. '#comment">'. sprintf($comment_counts, count(glob($comments_dir. '/*'. $delimiter. '*.txt', GLOB_NOSORT))). '</a>';
			$article .=
			'</div>'.
			'</div>'.
			'</div>';
		}
		$article .= '</div>';
		if ($count_glob_files > $default_sections_per_page) pager($max_pages, $page_ceil);
	}
	else
	{
		$stylesheet .= '#index>div{margin:.75rem;flex-basis:32%}@media(max-width:1200px){#index>div{flex-basis:49%}}@media(max-width:767px){.index{flex-direction:column}.index div{width:100%!important}#index>div{flex-basis:100%}}';
		if (5 === $index_type)
		{
			$stylesheet .= '.breadcrumb{display:none}';
			$javascript .= 'if(wrapper=document.getElementById("wrapper"))if(wrapper.classList.contains("container-fluid") && wrapper.classList.contains("mb-5"))wrapper.classList.remove("container-fluid","mb-5");';
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
					$a = img($i[0], 'd-block mx-auto rounded-circle', 0, .5). '<span class="d-inline-block">'. h($t). '</span>';
				elseif (3 === $index_type)
					$a = img($i[0], 'rounded-sm rounded-1'). '<br>'. h($t);
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
					'<p class="text-end"><a class="btn btn-secondary" href="'. $url. r($c. '/'. $t). '">'. $btn[9].'</a></p>'.
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
					$a .= '<a class="btn btn-sm btn-danger ms-2" href="'. $url. r($c). '/&amp;delete='. r($t). '">'. $btn[4]. '</a>';
				else
					$a .= '<a class="btn btn-sm btn-success ms-2" href="'. $url. r($c). '/&amp;post='. r($t). '">'. $btn[6]. '</a>';
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
	$header .= '<title>'. $site_name. '</title>';
	if (!$index_file || !$contents) $article .= '<img src="'. $url. 'images/icon.php" class="d-block w-75 p-3 m-auto" style="opacity:.08;max-width:50%">';
}
