<?php
if (!filter_has_var(INPUT_GET, 'categ') || (!is_admin() && !is_subadmin() && '!' === $categ_name[0])) exit;
if (is_dir($current_categ = 'contents/'. $categ_name))
{
	$create_article_check = $assist_error = false;
	$categ_login_txt = $current_categ. '/login.txt';
	$categ_title = h($categ_name);
	$breadcrumb .= '<li class="breadcrumb-item active">'. $categ_title. '</li>';
	$categ_contents = get_dirs($current_categ);
	$categ_contents_number = !$categ_contents ? 0 : count($categ_contents);
	$categ_content = '';
	if (is_file($categ_file = $current_categ. '/index.html'))
	{
		ob_start();
		include $categ_file;
		$categ_content = str_replace($line_breaks, '&#10;', ob_get_clean());
		$header .= '<meta name=description content="'. get_description($categ_content). '">';
		$meta_description = $categ_content;
	}
	else $meta_description = $categ_title;
	if (is_admin() || is_subadmin())
	{
		$article .= $create_with_kisou;
		if (is_admin() || is_author($current_categ))
		{
			if ($categ_del = !filter_has_var(INPUT_GET, 'categ-delete') ? '' : filter_input(INPUT_GET, 'categ-delete', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
			{
				if ($categ_del === $categ_name && is_dir('contents/'. $categ_del) && !is_dir('contents/!'. $categ_del) && rename('contents/'. $categ_del, 'contents/!'. $categ_del))
					exit (header('Location: '. $url. r('!'. $categ_del. '/')));
				else
				{
					$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
					$assist_error = true;
				}
			}
			if ($categ_rep = !filter_has_var(INPUT_GET, 'categ-post') ? '' : filter_input(INPUT_GET, 'categ-post', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
			{
				if ($categ_rep === $categ_name && is_dir('contents/'. $categ_rep) && !is_dir('contents/'. substr($categ_rep, 1)) && rename('contents/'. $categ_rep, 'contents/'. substr($categ_rep, 1)))
					exit (header('Location: '. $url. r(substr($categ_rep, 1). '/')));
				else
				{
					$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
					$assist_error = true;
				}
			}
			$article .=
			('!' === $categ_name[0] ?
				'<a class="btn btn-success mb-3 ms-2" href="'. $url. r($categ_name). '/&amp;categ-post='. r($categ_name). '">'. $category. $btn[6]. '</a>'
			:
				'<a class="btn btn-danger mb-3 ms-2" href="'. $url. r($categ_name). '/&amp;categ-delete='. r($categ_name). '">'. $category. $btn[4]. '</a>').
			'<a class="btn btn-info text-white mb-3 ms-2" href="'. $url. '&amp;edit='. r($categ_name). '#admin">'. $category. $btn[7]. '</a>';
		}
		if ($create_article = !filter_has_var(INPUT_POST, 'create-article') ? '' : '!'. filter_input(INPUT_POST, 'create-article', FILTER_CALLBACK, ['options' => 'trim_str_replace_basename']))
		{
			$create_article_dir = $current_categ. '/'. $create_article;
			$create_article_login_txt = $create_article_dir. '/login.txt';
			$create_images_dir = $create_article_dir. '/'. filter_input(INPUT_POST, 'create-images-dir', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
			$current_article = !filter_has_var(INPUT_POST, 'current-article-name') ? '' : filter_input(INPUT_POST, 'current-article-name', FILTER_CALLBACK, ['options' => 'trim_str_replace_basename']);
			$current_article_dir = $current_categ. '/'. $current_article;
			$current_images_dir = !filter_has_var(INPUT_POST, 'current-images-dir') ? '' : $create_article_dir. '/'. filter_input(INPUT_POST, 'current-images-dir', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
			if ($current_article && is_dir($current_article_dir) && $create_article === $current_article && is_dir($create_article_dir))
				$create_article_check = true;
			elseif ($current_article && is_dir($current_article_dir) && $create_article !== $current_article && !is_dir($create_article_dir))
			{
				rename($current_article_dir, $create_article_dir);
				$create_article_check = true;
			}
			elseif (!is_dir($create_article_dir))
			{
				mkdir($create_article_dir, 0757);
				counter($userdir. '/create-article.txt', 1);
				$create_article_check = true;
			}
			if ($create_article_check)
			{
				if (filter_has_var(INPUT_POST, 'require-login') && filter_has_var(INPUT_POST, 'login-textarea'))
					file_put_contents($create_article_login_txt, filter_input(INPUT_POST, 'login-textarea', FILTER_CALLBACK, ['options' => 'scriptentities']));
				elseif (!filter_has_var(INPUT_POST, 'require-login') && is_file($create_article_login_txt)) unlink($create_article_login_txt);
				if (filter_has_var(INPUT_POST, 'create-article-content'))
				{
					$create_article_content = filter_input(INPUT_POST, 'create-article-content', FILTER_CALLBACK, ['options' => 'scriptentities']);
					if (!filter_has_var(INPUT_POST, 'autowrap')) $create_article_content = '<?php nowrap()?>'. $create_article_content;
					file_put_contents($create_article_dir. '/index.html', $create_article_content, LOCK_EX);
				}
				else
				{
					$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
					$assist_error = true;
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
							array_push($disallow_symbols, ' ', '@');
							$create_article_files_name = basename($_FILES['create-article-files']['name'][$key]);
							$create_article_file_names = substr($create_article_files_name, 0, strripos($create_article_files_name, '.'));
							$create_article_file_names = ('item-images' === basename($create_images_dir) || !is_numeric($create_article_file_names) ? '' : 'img-'). $create_article_file_names;
							$create_article_file_names = str_replace($disallow_symbols, '-', $create_article_file_names). '.'. pathinfo($create_article_files_name, PATHINFO_EXTENSION);
							move_uploaded_file($_FILES['create-article-files']['tmp_name'][$key], $uploaded_images = $create_images_dir. '/'. $create_article_file_names);
							if (is_file($uploaded_images))
							{
								$extention = get_extension($uploaded_images);
								if ($img_comment = filter_input(INPUT_POST, basename($uploaded_images, $extention)))
								{
									if ('.png' !== strtolower($extention))
									{
										$im = new Imagick($uploaded_images);
										$im->stripImage();
										$im->setImageProperty('comment', $img_comment);
										$im->writeImage($uploaded_images);
										$im->clear();
									}
									else put_png_tEXt($uploaded_images, $pngtext, $img_comment, false);
								}
							}
						}
					}
				}
				if (filter_has_var(INPUT_POST, 'move-categ') && is_dir($move_article = 'contents/'. filter_input(INPUT_POST, 'move-categ', FILTER_CALLBACK, ['options' => 'strip_tags_basename'])))
				{
					if (is_admin() || is_author($current_categ. '/'. $create_article))
					{
						if (!is_dir($move_article. '/'. $create_article) && !is_dir($move_article. '/'. substr($create_article, 1)))
						{
							rename($current_categ. '/'. $create_article, $move_article. '/'. $create_article);
							exit (header('Location: '. $url. r(basename($move_article). '/'. $create_article)));
						}
						else
						{
							$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
							$assist_error = true;
						}
					}
				}
				exit (header('Location: '. $url. r(basename($current_categ). '/'. $create_article)));
			}
		}
		$edit_article_name = !filter_has_var(INPUT_GET, 'edit') ? '' : filter_input(INPUT_GET, 'edit', FILTER_CALLBACK, ['options' => 'strip_tags_basename']);
		if ($edit_article_name && is_dir($edit_article_dir = $current_categ. '/'. $edit_article_name) && isset($edit_article_name[0]) && '!' === $edit_article_name[0])
		{
			if (is_admin() || is_author($edit_article_dir))
			{
				$edit_article_title = basename($edit_article_dir);
				$edit_article_counter = is_file($edit_article_dir. '/counter.txt') ? 1 : 0;
				$edit_article_comment = is_dir($edit_article_dir. '/comments') ? 1 : 0;
				$edit_article_content = is_file($edit_article_html = $edit_article_dir. '/index.html') ? file_get_contents($edit_article_html) : '';
				$edit_article_login_txt = $edit_article_dir. '/login.txt';
				$edit_article_login_txt_content = !is_file($edit_article_login_txt) ? '' : file_get_contents($edit_article_login_txt);

				if (false !== strpos($edit_article_content, $nowrap_txt = '<?php nowrap()?>'))
				{
					$edit_article_content = str_replace($nowrap_txt, '', $edit_article_content);
					$edit_article_nowrap = 1;
				}
				if (false !== strpos($edit_article_content, '&lt;') && false !== strpos($edit_article_content, '&gt;'))
					$edit_article_content = str_replace(['&lt', '&gt'], ['&amp;lt', '&amp;gt'], $edit_article_content);
				foreach (glob($edit_article_dir. '/*images', GLOB_NOSORT+GLOB_ONLYDIR) as $updir);
			}
		}
		$article .=
		'<div class="modal fade" id=kisou tabindex=-1 aria-hidden=true><div class="modal-dialog modal-fullscreen"><div class=modal-content><div class=modal-header><h5 class=modal-title>'.
		(filter_has_var(INPUT_GET, 'edit') ? $admin_menus[13] : $admin_menus[2]). '</h5><button type=button class=btn-close data-bs-dismiss=modal></button></div><div class=modal-body><form class="" id=create-article-form method=post enctype="multipart/form-data">';
		if (isset($edit_article_content))
		{
			$article .=
			'<div class="input-group my-3">'.
			'<label class=input-group-text for=move-categ>'. $admin_menus[14]. '</label>'.
			'<select class=form-select id=move-categ name=move-categ>'.
			'<option selected>'. $admin_menus[1]. '</option>';
			foreach ($contents as $move_categ)
			{
				if ($categ_name !== $move_categ)
					$article .= '<option value="'. $move_categ. '">'. $move_categ. '</option>';
			}
			$article .=
			'</select>'.
			'</div>';
		}
		$article .=
		'<label class="h5" for=create-article>'. $admin_menus[3]. ' <small class="text-muted max"></small></label>'.
		'<div class="input-group mb-4">'.
		'<input class="form-control creates" type=text name=create-article id=create-article maxlength='. $title_length. ' required placeholder="'. $admin_menus[3]. '"'. (isset($edit_article_title) ? ' value="'. substr($edit_article_title, 1). '"' : ''). '>'.
		($edit_article_name ? '<input type=hidden name=current-article-name value="'. $edit_article_name. '">' : '').
		'<div class="input-group-text">'.
		'<label class="form-check me-4" data-bs-toggle=tooltip for=create-counter title="'. $admin_menus[5]. '">'.
		'<input class=form-check-input type=checkbox id=create-counter name=create-counter value=true'. (isset($edit_article_counter) && $edit_article_counter ? ' checked' : ''). '>'. $admin_menus[4].
		'</label>'.
		'<label class="form-check" data-bs-toggle=tooltip for=create-comment title="'. $admin_menus[7]. '">'.
		'<input class=form-check-input type=checkbox id=create-comment name=create-comment value=true'. (isset($edit_article_comment) && $edit_article_comment ? ' checked' : ''). '>'. $admin_menus[6].
		'</label>'.
		'</div>'.
		'</div>'.
		'<div class="d-flex align-items-end justify-content-between my-2">'.
		'<div class="form-check me-4" id=i>'.
		'<input class=form-check-input type=checkbox id=autowrap name=autowrap value=true'. (isset($edit_article_nowrap) && $edit_article_nowrap ? '' : ' checked'). '>'.
		'<label class=form-check-label for=autowrap>'. $html_assist[1]. '</label>'.
		'</div>'.
		'<div class=form-check>'.
		'<input class=form-check-input type=checkbox id=require-login name=require-login value=true'. (isset($edit_article_login_txt) && is_file($edit_article_login_txt) ? ' checked' : ''). '>'.
		'<label class=form-check-label for=require-login>'. $form_label[6]. '</label>'.
		'</div>'.
		'</div>'.
		'<textarea class="form-control mb-4" id=textarea name=create-article-content placeholder="'. $admin_menus[8]. '" rows=10>'. ($edit_article_content ?? ''). '</textarea>'.
		'<div class="input-group my-4" id=uploads>'.
		'<select class=form-select name=create-images-dir id=create-images-dir>';
		foreach (['images', 'background-images', 'tooltip-images', 'slide-images', 'delete-images', 'item-images'] as $img_dir)
			$article .= '<option value="'. $img_dir. '"'. (isset($updir) && basename($updir) === $img_dir ? ' selected' : ''). '>'. $img_dir. '</option>';
		$article .=
		'</select>'.
		'<input class=form-control type=file name=create-article-files[] id=create-article-files multiple accept="image/gif,image/jpeg,image/png,image/svg,video/mp4,video/webm,video/ogg,text/vtt">'.
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
		'<textarea class="form-control mb-4" name=login-textarea id=login-textarea placeholder="'. $admin_menus[16]. '" rows=10>'. ($edit_article_login_txt_content ?? ''). '</textarea>'.
		'<div class=modal-footer><button type=button class="btn btn-secondary" data-bs-dismiss=modal>'. $btn[13]. '</button><input class="btn btn-primary" type=submit value="'. $btn[8]. '"></div>'.
		'</form></div></div></div></div>';
		html_assist();
		$javascript .= 'function replaceChar(str,char="-"){return str.substr(0,str.lastIndexOf(".")).replace(/[@\"#$%&\'()*+.,\/:;><=?\\\[\\\\\]^_`{|}~ ]/g,char)}document.getElementById("i").parentNode.insertBefore(document.getElementById("h"),document.getElementById("i"));previewDiv=document.createElement("div");previewDiv.id="preview";document.getElementById("uploads").parentNode.insertBefore(previewDiv,document.getElementById("uploads").nextElementSibling);document.getElementById("create-images-dir").addEventListener("change",e=>{files=document.getElementById("create-article-files").files;if("background-images"===e.target.value){let a="";for(let v of files)a+="<div class=\"img-"+replaceChar(v.name)+"\"><\/div>\n";document.getElementById("textarea").value=document.getElementById("textarea").value+a}if("tooltip-images"===e.target.value){let b="";for(w of files)b+="<span id=\"img-"+replaceChar(w.name)+"\"><\/span>\n";document.getElementById("textarea").value=document.getElementById("textarea").value+b}});document.getElementById("create-article-files").addEventListener("change",e=>{let preview=document.getElementById("preview"),files=e.target.files;document.getElementById("create-images-dir").dispatchEvent(new Event("change"));function preView(file){const reader=new FileReader();reader.onload=e=>{const image=new Image(),figure=document.createElement("figure");figure.className="figure img-thumbnail mb-3";if(/image/.test(file.type)){image.alt=replaceChar(file.name);image.classList.add("img-fluid");image.src=e.target.result}if(/\.('. (!extension_loaded('imagick') ? '' : 'jpe?g|'). 'png)$/i.test(file.name)){textarea=document.createElement("textarea");textarea.className="form-control";textarea.name="img-"+replaceChar(file.name);textarea.placeholder="'. $placeholder[10]. '";figure.appendChild(image);figure.appendChild(textarea);preview.appendChild(figure)}else if(/video/.test(file.type)||/vtt/.test(file.type)){video=document.createElement("video");video.id="video-"+replaceChar(file.name);video.controls=true;if(/vtt/.test(file.type)){track=document.createElement("track");track.id="track-"+replaceChar(file.name);track.kind="subtitles";track.src=e.target.resultpreview.appendChild(track)}else{source=document.createElement("source");source.src=e.target.result;source.id="#track-"+replaceChar(file.name);video.appendChild(source);figure.appendChild(video);preview.appendChild(figure)}}else{figure.appendChild(image);preview.appendChild(figure)}};reader.readAsDataURL(file)}if(files)[].slice.call(files).sort().forEach(v=>preView(v))});rl=document.getElementById("require-login"),cl=document.getElementById("login-textarea");window.addEventListener("load",loginCheck);rl.addEventListener("change",loginCheck);clone=document.getElementById("h").cloneNode(true);clone.id="ch";clone.classList.add("mb-2");footer.appendChild(clone);cl.parentNode.insertBefore(document.getElementById("ch"),cl);function loginCheck(){if(true!==rl.checked){cl.style.display="none";ch.style.display="none"}else{cl.style.display="block";ch.style.display="block"}}'. (!$edit_article_name ? '' : 'new bootstrap.Modal(document.getElementById("kisou")).show();').
		(!$assist_error ? '' : 'new bootstrap.Modal(document.getElementById("kisou")).show();');
	}
	if (0 < $categ_contents_number)
	{
		$post_article_check = true;
		foreach ($categ_contents as $articles_name)
		{
			if (!is_admin() && !is_subadmin() && '!' === $articles_name[0]) continue;
			if (is_admin() || is_author($current_categ. '/'.$articles_name))
			{
				if ($del = !filter_has_var(INPUT_GET, 'delete') ? '' : filter_input(INPUT_GET, 'delete', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
				{
					if ($current_categ. '/'. $del === $current_categ. '/'. $articles_name && is_dir($current_categ. '/'. $del) && !is_dir($current_categ. '/!'. $del) && rename($current_categ. '/'. $del, $current_categ. '/!'. $del))
						exit (header('Location: '. $url. r(basename($current_categ). '/!'. $del)));
					else $post_article_check = false;
				}
				if ($rep = !filter_has_var(INPUT_GET, 'post') ? '' : filter_input(INPUT_GET, 'post', FILTER_CALLBACK, ['options' => 'strip_tags_basename']))
				{
					if ($current_categ. '/'. $rep === $current_categ. '/'. $articles_name && is_dir($current_categ. '/'. $rep) && !is_dir($current_categ. '/'. substr($rep, 1)) && rename($current_categ. '/'. $rep, $current_categ. '/'. substr($rep, 1)))
					{
						$post_article = basename($current_categ). '/'. substr($rep, 1);
						if (!is_admin()) mail($mail_address, $btn[6]. ' - '. h($post_article. ' - '. $site_name), a($url. r($post_article), $post_article. ' - '. $site_name));
						exit (header('Location: '. $url. r($post_article)));
					}
					else $post_article_check = false;
				}
			}
			$articles_sort[] = is_file($article_files = $current_categ. '/'. $articles_name. '/index.html') ? filemtime($article_files). $delimiter. $article_files : '';
		}
		if (false === $post_article_check)
			$article .= '<div class="alert alert-danger">'. $admin_menus[15]. '</div>';
		$articles_sort = array_filter($articles_sort);
		rsort($articles_sort);
		$page_ceil = ceil($categ_contents_number / $categ_sections_per_page);
		$max_pages = min($pages, $page_ceil);
		$sections_in_categ = array_slice($articles_sort, ($max_pages-1) * $categ_sections_per_page, $categ_sections_per_page);
		$header .= '<title>'. $categ_title. ' - '. (1 < $max_pages ? sprintf($page_prefix, $max_pages). ' - ' : ''). $site_name. '</title>';
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
			if (is_dir($default_background_dir = $article_dir. '/background-images') && $glob_default_background_imgs = glob($default_background_dir. $glob_imgs, GLOB_NOSORT+GLOB_BRACE))
			{
				rsort($glob_default_background_imgs);
				$default_background_image = img($glob_default_background_imgs[0]);
				$count_background_images = count($glob_default_background_imgs);
			}
			else
				$default_background_image = $count_background_images = '';
			$total_images = (int)$count_images + (int)$count_background_images;
			$article .=
			'<div class='. $categ_column_class. '>'.
			'<div class="'. $categ_wrapper_class. '">'.
			($default_image ?: $default_background_image).
			'<div class="'. $categ_content_class. '">'.
			'<h2 class="'. $categ_title_class. '">'.
			(is_admin() || is_author($article_dir) ? '!' !== $articles_link[2][0] ?
				'<a class="btn btn-sm btn-danger me-2" href="'. $url. $categ_link. '/&amp;delete='. $title_link. '">'. $btn[4]. '</a>'
			:
				'<a class="btn btn-sm btn-success me-2" href="'. $url. $categ_link. '/&amp;post='. $title_link. '">'. $btn[6]. '</a>' : '').
			'<a href="'. $url. $categ_link. '/'. $title_link. '">'. ht($articles_link[2]);
			if (0 < $total_images)
				$article .= '<small>'. sprintf($images_count_title, $total_images). '</small>';
			if (is_file($ticket) && (is_file($categ_login_txt) || is_file($article_login_txt)) && !isset($_SESSION['l']))
				$article .= '<sup class="d-inline-block lock"></sup>';
			$article .= '</a></h2>';
			if ($use_summary) $article .= '<p class="categ-summary wrap">'. get_summary($articles[1]). '</p>';
			$article .=
			'</div>'.
			'<div class="'. $categ_footer_class. '">';
			if (is_file($author_file = $article_dir. '/author.txt') && is_dir($author_prof = 'users/'. basename($author = file_get_contents($author_file)). '/prof/'))
				$article .= '<a class=card-link href="'. $url. '?user='. str_rot13(basename(dirname($author_prof))). '">'. avatar($author_prof, 20). ' '. handle($author_prof). '</a>';
			$article .=
			'<time class=card-link datetime="'. date('c', $articles[0]). '">'. timeformat($articles[0], $intervals). '</time>';
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
		if ($categ_contents_number > $categ_sections_per_page) pager($max_pages, $page_ceil);
	}
	elseif (!$categ_file)
		not_found();
	else
		$header .= '<title>'. $categ_title. ' - '. $site_name. '</title>';
}
else
	not_found();
