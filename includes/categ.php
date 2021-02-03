<?php
if (!filter_has_var(INPUT_GET, 'categ') || (!is_admin() && !is_subadmin() && '!' === $categ_name[0])) exit;
$sidebox_order[4] = 0;
if (is_dir($current_categ = 'contents/'. $categ_name))
{
	$categ_login_txt = $current_categ. '/login.txt';
	$categ_title = h($categ_name);
	$breadcrumb .= '<li class="breadcrumb-item active">'. $categ_title. '</li>';
	$categ_contents = get_dirs($current_categ);
	$categ_contents_number = $categ_contents ? count($categ_contents) : 0;
	$categ_content = '';
	if (is_file($categ_file = $current_categ. '/index.html'))
	{
		ob_start();
		include $categ_file;
		$categ_content = trim(ob_get_clean());
		$header .= '<meta name=description content="'. get_description($categ_content). '">'. $n;
		$article .= '<header><h1 class="'. $h1_title[0]. '">'. $categ_title. (!$categ_content ? '' : ' <small class="'. $h1_title[1]. '">'. $categ_content. '</small>'). '</h1></header>';
	}

	if (is_admin() || is_subadmin())
	{
		if (is_admin() || is_author($current_categ))
		{
			if ($categ_del = basename(filter_input(INPUT_GET, 'categ-delete', FILTER_SANITIZE_STRING)))
				if ($categ_del === $categ_name && is_dir('contents/'. $categ_del) && rename('contents/'. $categ_del, 'contents/'. '!'. $categ_del)) exit (header('Location: '. $url. r('!'. $categ_del. '/')));
			if ($categ_rep = basename(filter_input(INPUT_GET, 'categ-post', FILTER_SANITIZE_STRING)))
				if ($categ_rep === $categ_name && is_dir('contents/'. $categ_rep) && rename('contents/'. $categ_rep, 'contents/'. substr($categ_rep, 1))) exit (header('Location: '. $url. r(substr($categ_rep, 1). '/')));
			$article .=
			'<div class=text-center>'.
			('!' === $categ_name[0] ?
				'<a class="btn btn-success" href="'. $url. r($categ_name). '/&amp;categ-post='. r($categ_name). '">'. $category. $btn[6]. '</a>'
			:
				'<a class="btn btn-danger" href="'. $url. r($categ_name). '/&amp;categ-delete='. r($categ_name). '">'. $category. $btn[4]. '</a>').
			'<a class="btn btn-info ml-2" href="'. $url. '&amp;edit='. r($categ_name). '#admin-menu">'. $category. $btn[7]. '</a>'.
			'</div>';
		}

		if ($create_article = !filter_has_var(INPUT_POST, 'create-article') ? '' : '!'. trim(str_replace($disallow_symbols, $replace_symbols, filter_input(INPUT_POST, 'create-article'))))
		{
			$create_article_dir = $current_categ. '/'. basename($create_article);
			$create_article_login_txt = $create_article_dir. '/login.txt';
			$create_images_dir = $create_article_dir. '/'. basename(filter_input(INPUT_POST, 'create-images-dir', FILTER_SANITIZE_STRING));
			$current_article = !filter_has_var(INPUT_POST, 'current-article-name') ? '' : trim(str_replace($disallow_symbols, $replace_symbols, filter_input(INPUT_POST, 'current-article-name')));
			$current_images_dir = !filter_has_var(INPUT_POST, 'current-images-dir') ? '' : $create_article_dir. '/'. filter_input(INPUT_POST, 'current-images-dir');
			if ($current_article && is_dir($current_article_dir = $current_categ. '/'. $current_article) && $create_article !== $current_article && !is_dir($create_article_dir))
				rename($current_article_dir, $create_article_dir);
			elseif (!is_dir($create_article_dir))
			{
				mkdir($create_article_dir, 0757);
				counter($userdir. '/create-article.txt', 1);
			}
			if (filter_has_var(INPUT_POST, 'require-login') && !is_file($create_article_login_txt)) touch($create_article_login_txt);
			elseif (!filter_has_var(INPUT_POST, 'require-login') && is_file($create_article_login_txt)) unlink($create_article_login_txt);
			if (filter_has_var(INPUT_POST, 'create-article-content'))
			{
				$create_article_content = filter_input(INPUT_POST, 'create-article-content');
				if (!is_admin()) $create_article_content = str_replace(['<?', '?>'], ['&lt;?', '?&gt;'], $create_article_content);
				if (!filter_has_var(INPUT_POST, 'autowrap')) $create_article_content = '<?php nowrap()?>'. $create_article_content;
				file_put_contents($create_article_dir. '/index.html', $create_article_content, LOCK_EX);
			}
			if (!is_file($author_txt = $create_article_dir. '/author.txt')) file_put_contents($author_txt, $_SESSION['l'], LOCK_EX);
			elseif (is_admin() && is_admin() !== file_get_contents($author_txt)) file_put_contents($create_article_dir. '/editor.txt', $_SESSION['l'], LOCK_EX);
			if (filter_has_var(INPUT_POST, 'create-counter')) counter($create_article_dir. '/counter.txt', 1);
			elseif (is_file($article_counter_txt = $create_article_dir. '/counter.txt')) unlink($article_counter_txt);
			if (filter_has_var(INPUT_POST, 'create-comment'))
			{
				if (is_dir($comment_bk_dir = $create_article_dir. '/comments-bk')) rename($comment_bk_dir, $create_article_dir. '/comments');
				elseif (!is_dir($create_comment_dir = $create_article_dir. '/comments')) mkdir($create_comment_dir);
			}
			elseif (is_dir($comment_dir = $create_article_dir. '/comments')) rename($comment_dir, $comment_dir. '-bk');
			if (is_dir($create_article_dir) && (is_admin() || is_author($create_article_dir)))
			{
				if (filter_has_var(INPUT_POST, 'remove')) array_map('unlink', array_filter(filter_input(INPUT_POST, 'remove', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY), 'is_file'));
			}
			if (is_dir($current_images_dir) && $current_images_dir !== $create_images_dir) rename($current_images_dir, $create_images_dir);
			if (isset($_FILES['create-article-files']['error'][0], $_FILES['create-article-files']['name'][0], $_FILES['create-article-files']['tmp_name'][0]) && UPLOAD_ERR_OK === $_FILES['create-article-files']['error'][0])
			{
				if (!is_dir($create_images_dir)) mkdir($create_images_dir);
				foreach ($_FILES['create-article-files']['error'] as $key => $error)
				{
					if (UPLOAD_ERR_OK === $error)
					{
						move_uploaded_file($_FILES['create-article-files']['tmp_name'][$key], $uploaded_images = $create_images_dir. '/img-'. basename($_FILES['create-article-files']['name'][$key]));
						if (is_file($uploaded_images))
						{
							$extention = get_extension($uploaded_images);
							if ($img_comment = filter_input(INPUT_POST, basename($uploaded_images, $extention), FILTER_SANITIZE_SPECIAL_CHARS))
							{
								if ('.png' !== strtolower($extention))
								{
									$im = new Imagick($uploaded_images);
									$im->stripImage();
									$im->setImageProperty('comment', $img_comment);
									$im->writeImage($uploaded_images);
								}
								else put_png_tEXt($uploaded_images, $pngtext, $img_comment, false);
							}
						}
					}
				}
			}
			exit (header('Location: '. $url. r(basename($current_categ). '/'. basename($create_article))));
		}
		$edit_article_name = basename(filter_input(INPUT_GET, 'edit', FILTER_SANITIZE_STRING));
		if ($edit_article_name && is_dir($edit_article_dir = $current_categ. '/'. $edit_article_name) && isset($edit_article_name[0]) && '!' === $edit_article_name[0])
		{
			if (is_admin() || is_author($edit_article_dir))
			{
				$edit_article_title = basename($edit_article_dir);
				$edit_article_counter = is_file($edit_article_dir. '/counter.txt') ? 1 : 0;
				$edit_article_comment = is_dir($edit_article_dir. '/comments') ? 1 : 0;
				$edit_article_content = is_file($edit_article_html = $edit_article_dir. '/index.html') ? file_get_contents($edit_article_html) : '';
				$edit_article_login_txt =  is_file($edit_article_dir. '/login.txt') ? 1 : 0;
				if (false !== strpos($edit_article_content, $nowrap_txt = '<?php nowrap()?>'))
				{
					$edit_article_content = str_replace($nowrap_txt, '', $edit_article_content);
					$edit_article_nowrap = 1;
				}
				foreach (glob($edit_article_dir. '/*images', GLOB_NOSORT+GLOB_ONLYDIR) as $updir);
			}
		}

		$article .=
		'<form class="bg-light p-4 my-5" id=create-article-form method=post enctype="multipart/form-data">'.
		'<label class="h5" for=create-article>'. $admin_menus[2]. ' <small class="text-muted max"></small></label>'. $n.
		'<div class="input-group my-4">'. $n.
		'<input class="form-control creates" type=text name=create-article id=create-article required placeholder="'. $admin_menus[3]. '"'. (isset($edit_article_title) ? ' value="'. substr($edit_article_title, 1). '"' : ''). '>'. $n.
		(isset($edit_article_name) ? '<input type=hidden name=current-article-name value="'. $edit_article_name. '">' : '').
		'<div class="input-group-append btn-group-toggle" data-toggle=buttons>'.
		'<label class="btn btn-secondary" data-toggle=tooltip for=create-counter title="'. $admin_menus[5]. '">'.
		'<input class="custom-control-input" type=checkbox id=create-counter name=create-counter value=true'. (isset($edit_article_counter) && $edit_article_counter ? ' checked' : ''). '>'. $admin_menus[4].
		'</label>'.
		'<label class="btn btn-secondary" data-toggle=tooltip for=create-comment title="'. $admin_menus[7]. '">'.
		'<input class="custom-control-input" type=checkbox id=create-comment name=create-comment value=true'. (isset($edit_article_comment) && $edit_article_comment ? ' checked' : ''). '>'. $admin_menus[6].
		'</label>'.
		'</div>'.
		'</div>'.
		'<div class="d-flex align-items-end justify-content-between mb-2">'.
		'<div class="custom-control custom-checkbox mr-3" id=i>'.
		'<input class=custom-control-input type=checkbox id=autowrap name=autowrap value=true'. (isset($edit_article_nowrap) && $edit_article_nowrap ? '' : ' checked'). '>'.
		'<label class=custom-control-label for=autowrap>'. $html_assist[1]. '</label>'.
		'</div>'.
		'<div class="custom-control custom-checkbox">'.
		'<input class=custom-control-input type=checkbox id=require-login name=require-login value=true'. (isset($edit_article_login_txt) && $edit_article_login_txt ? ' checked' : ''). '>'.
		'<label class=custom-control-label for=require-login>'. $form_label[6]. '</label>'.
		'</div>'.
		'</div>'.
		'<textarea class="form-control mb-4" id=textarea name=create-article-content placeholder="'. $admin_menus[8]. '" rows=10>'. ($edit_article_content ?? ''). '</textarea>'.
		'<div class="form-row align-items-center my-4" id=uploads>'.
		'<div class="col-auto my-1">'.
		'<select class="form-control mr-sm-2 bg-light" name=create-images-dir id=create-images-dir>';
		foreach (['images', 'background-images', 'tooltip-images', 'slide-images', 'delete-images'] as $img_dir)
			$article .= '<option value="'. $img_dir. '"'. (isset($updir) && basename($updir) === $img_dir ? ' selected' : ''). '>'. $img_dir. '</option>';
		$article .=
		'</select>'.
		'</div>'.
		'<div class="col-auto my-1" id=file><input class="form-control-file" type=file name=create-article-files[] id=create-article-files multiple accept="image/gif,image/jpeg,image/png,image/svg,video/mp4,video/webm,video/ogg,text/vtt"></div>'.
		'</div>';
		if (isset($updir))
		{
			if ((new FilesystemIterator($updir))->valid())
			{
				$article .=
				'<fieldset class="form-group bg-light px-4 my-4"><h2 class="h5 my-4">'. $btn[4]. '</h2>'.
				'<input type=hidden name=current-images-dir value="'. basename($updir). '">';
				foreach (glob($updir. $glob_imgs, GLOB_BRACE) as $current_img)
				{
					$current_img_name = h(basename($current_img));
					$article .=
					'<div class="form-check my-3">'.
					'<input class=form-check-input name=remove[] type=checkbox value="'. $current_img. '" id="'. $current_img_name. '">'.
					'<label class=form-check-label for="'. $current_img_name. '">'. $current_img_name. '</label>'.
					'</div>';
				}
				$article .= '</fieldset>';
			 }
			 else rmdir($updir);
		 }
		$article .=
		'<input class="btn btn-primary btn-block" type=submit value="'. $btn[8]. '">'.
		'</form>';
		html_assist();
		$footer .= '<script defer>$("#h").insertBefore($("#i"));$("#file").after("<div class=\"d-flex align-items-start justify-content-between flex-wrap my-1 w-100\" id=preview></div>");$("#create-images-dir").on("change",function(e){files=$("#create-article-files").prop("files");if("background-images"===$(this).val()){let a="";for(v of files)a+="<div class=\"img-"+v.name.substr(0,v.name.lastIndexOf("."))+"\"></div>\n";$("#textarea").val($("#textarea").val()+a)}if("tooltip-images"===$(this).val()){let b="";for(w of files)b+="<span id=\"img-"+w.name.substr(0,w.name.lastIndexOf("."))+"\"></span>\n";$("#textarea").val($("#textarea").val()+b)}});$("#create-article-files").on("change",function(){let preview=$("#preview"),files=$("input[type=file]").prop("files");$("#create-images-dir").trigger("change");function preView(file){const reader=new FileReader();reader.onload=function(e){const image=new Image(),figure=$("<figure class=\"figure img-thumbnail m-3\">"),video=$("<video id=\"video-"+file.name.substr(0,file.name.lastIndexOf("."))+"\" controls>"),textarea=$("<textarea class=\"form-control\" name=\"img-"+file.name.substr(0,file.name.lastIndexOf("."))+"\" placeholder=\"'. $placeholder[10]. '\">");if(/image/.test(file.type)){image.alt=file.name;image.classList.add("img-fluid");image.src = this.result}if(/\.('. (!extension_loaded('imagick') ? '' : 'jpe?g|'). 'png)$/i.test(file.name))preview.append(figure.append(image).append(textarea));else if(/video/.test(file.type)||/vtt/.test(file.type)){if(/vtt/.test(file.type))preview.append($("<track id=\"track-"+file.name.substr(0,file.name.lastIndexOf("."))+"\" kind=subtitles>").attr("src",this.result));else preview.append(figure.append(video.append($("<source>").attr("src",this.result)).append($("#track-"+file.name.substr(0,file.name.lastIndexOf("."))))))}else	preview.append(figure.append(image))};reader.readAsDataURL(file)}if(files)$.each(files,function(i,v){preView(v)})})</script>';
	}
	if (0 < $categ_contents_number)
	{
		foreach ($categ_contents as $articles_name)
		{
			if (!is_admin() && !is_subadmin() && '!' === $articles_name[0]) continue;
			if (is_admin() || is_author($current_categ. '/'.$articles_name))
			{
				if ($del = basename(filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_STRING)))
					if ($current_categ. '/'. $del === $current_categ. '/'. $articles_name && is_dir($current_categ. '/'. $del) && rename($current_categ. '/'. $del, $current_categ. '/!'. $del))
						exit (header('Location: '. $url. r(basename($current_categ). '/!'. $del)));
				if ($rep = basename(filter_input(INPUT_GET, 'post', FILTER_SANITIZE_STRING)))
					if ($current_categ. '/'. $rep === $current_categ. '/'. $articles_name && is_dir($current_categ. '/'. $rep) && rename($current_categ. '/'. $rep, $current_categ. '/'. substr($rep, 1)))
					{
						$post_article = basename($current_categ). '/'. substr($rep, 1);
						if (!is_admin()) mail($mail_address, $btn[6]. ' - '. h($post_article. ' - '. $site_name), a($url. r($post_article), h($post_article. ' - '. $site_name)));
						exit (header('Location: '. $url. r($post_article)));
					}
			}
			$articles_sort[] = is_file($article_files = $current_categ. '/'. $articles_name. '/index.html') ? filemtime($article_files). $delimiter. $article_files : '';
		}

		$articles_sort = array_filter($articles_sort);
		rsort($articles_sort);
		$page_ceil = ceil($categ_contents_number / $categ_sections_per_page);
		$max_pages = min($pages, $page_ceil);
		$sections_in_categ = array_slice($articles_sort, ($max_pages-1) * $categ_sections_per_page, $categ_sections_per_page);

		$header .= '<title>'. $categ_title. ' - '. (1 < $max_pages ? sprintf($page_prefix, $max_pages). ' - ' : ''). $site_name. '</title>'. $n;

		if ($categ_contents_number > $categ_sections_per_page) pager($max_pages, $page_ceil);

		$article .= '<div class="'. $categ_class. '">';
		foreach ($sections_in_categ as $sections)
		{
			$articles = explode($delimiter, $sections);
			$articles_link = explode('/', $articles[1]);
			$categ_link = r($articles_link[1]);
			$title_link = r($articles_link[2]);
			$article_dir = dirname($articles[1]);
			$article_login_txt = $article_dir. '/login.txt';
			$count_images = '';

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

			$article .=
			'<div class="'. $categ_wrapper_class. '">'. $n.
			$default_image.
			'<div class="'. $categ_content_class. '">'. $n.
			'<h2 class="'. $categ_title_class. '">'.
			(is_admin() || is_author($article_dir) ? '!' !== $articles_link[2][0] ?
				'<a class="btn btn-sm btn-danger mr-2" href="'. $url. $categ_link. '/&amp;delete='. $title_link. '">'. $btn[4]. '</a>'
			:
				'<a class="btn btn-sm btn-success mr-2" href="'. $url. $categ_link. '/&amp;post='. $title_link. '">'. $btn[6]. '</a>' : '').
			'<a href="'. $url. $categ_link. '/'. $title_link. '">'. ht($articles_link[2]);
			if (0 < $total_images)
				$article .= '<small>'. sprintf($images_count_title, $total_images). '</small>';

			if (is_file($ticket) && (is_file($categ_login_txt) || is_file($article_login_txt)) && !isset($_SESSION['l']))
				$article .= '<sup class="d-inline-block lock"></sup>';

			$article .= '</a></h2>'. $n;

			if ($use_summary) $article .= '<p class="categ-summary wrap">'. get_summary($articles[1]). '</p>'. $n;
			$article .=
			'</div>'. $n.
			'<div class="'. $categ_footer_class. '">'. $n;
			if (is_file($author_file = $article_dir. '/author.txt') && is_dir($author_prof = 'users/'. basename($author = file_get_contents($author_file)). '/prof/'))
				$article .= '<a class=card-link href="'. $url. '?user='. str_rot13(basename(dirname($author_prof))). '">'. avatar($author_prof, 20). ' '. handle($author_prof). '</a>';
			$article .=
			'<time class=card-link datetime="'. date('c', $articles[0]). '">'. timeformat($articles[0], $intervals). '</time>';
			if (is_file($counter_txt = $article_dir. '/counter.txt'))
				$article .= '<span class=card-link>'. sprintf($views, size_unit((int)file_get_contents($counter_txt), false)). '</span>';
			if ($use_comment && is_dir($comments_dir = $article_dir. '/comments'))
				$article .=
				'<a class=card-link href="'. $url. $categ_link. '/'. $title_link. '#comment">'. sprintf($comment_counts, count(glob($comments_dir. '/*'. $delimiter. '*.txt', GLOB_NOSORT))). '</a>';
			$article .= '</div></div>'. $n;
		}
		$article .= '</div>';

		if ($categ_contents_number > $categ_sections_per_page) pager($max_pages, $page_ceil);
	}
	elseif (!$categ_file)
		not_found();
	else
		$header .= '<title>'. $categ_title. ' - '. $site_name. '</title>'. $n;
}
else
	not_found();
