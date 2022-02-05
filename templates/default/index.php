<!doctype html>
<html lang=<?=$lang?>>
<head>
<meta charset=<?=$encoding?>>
<meta name=viewport content="width=device-width,initial-scale=1">
<link href="<?=$css, ($get_categ ? '?categ='. $get_categ : '')?>" rel=stylesheet>
<?=$header?>
</head>
<body id=TOP class=text-break>
<header id=header class="navbar sticky-md-top navbar-light bg-light shadow">
<nav class="d-table w-100 p-3">
<div class="d-table-cell align-middle text-left w-25">
<a class="btn btn-primary btn-sm text-white" data-bs-toggle=offcanvas href="#side">&#9664;&#9654;</a></div>
<div class="d-table-cell align-middle text-center w-50"><a class="h2 navbar-brand" href="<?=$url?>"><?=get_logo()?></a></div>
<div class="d-table-cell align-middle w-25"><?=$search?></div>
</nav>
</header>
<?php if ($nav): ?><nav id=nav><ul class="nav nav-pills justify-content-center py-4 bg-dark"><?=$nav?></ul></nav><?php endif?>
<main id=main class="bg-light container-sm px-md-5 py-3"><ol id=breadcrumb class="breadcrumb py-3 m-0"><?=$breadcrumb?></ol><?=$article?></main>
<aside id=side class="offcanvas offcanvas-start shadow" data-bs-scroll=true data-bs-backdrop=false style="overflow:auto">
<div class="offcanvas-header bg-primary sticky-top shadow text-white" style="top:0">
<h3 class="offcanvas-title h6"><?=$meta_description?></h3>
<button type=button class="btn-close btn-close-white" data-bs-dismiss=offcanvas tabindex=-1 accesskey=x></button></div>
<div class=offcanvas-body><div class="d-flex flex-column"><?=$aside?></div></div>
</aside>
<footer id=footer class="d-flex flex-wrap justify-content-center justify-content-lg-between align-items-center">
<nav class="nav nav-pills justify-content-center flex-row">
<?php
usort($glob_info_files, 'sort_time');
foreach ($glob_info_files as $info_files)
{
	$info_uri = basename($info_files, '.html');
	echo '<a class="text-sm-center nav-link', ($page_name !== $info_uri ? '' : ' active'), '" href="', $url, ('index' !== $info_uri ? '' : '?page=') , r($info_uri), '">', h($info_uri), '</a>';
}
if ($use_forum)
	echo '<a class="text-sm-center nav-link', ($page_name !== $forum ? '' : ' active'), '" href="', $url, r($forum), '">', $forum, '</a>';
if ($dl)
	echo '<a class="text-sm-center nav-link', ($page_name !== $download_contents ? '' : ' active'), '" href="', $url, r($download_contents), '">', $download_contents, '</a>';
if ($use_contact && $mail_address)
	echo '<a class="text-sm-center nav-link', ($page_name !== $contact_us ? '' : ' active'), '" href="', $url, r($contact_us), '">', $contact_us, '</a>';
?>
</nav>
<a href="#TOP" id=page-top><svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" fill="currentColor" class="bi bi-arrow-up-square-fill" viewBox="0 0 16 16"><path d="M2 16a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2zm6.5-4.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 1 0z"/></svg></a>
<script src="<?=$js?>"></script>
<?=$footer?>
</footer>
</body>
</html>