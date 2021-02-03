<!doctype html>
<html lang=<?=$lang?>>
<head>
<meta charset=<?=$encoding?>>
<meta name=viewport content="width=device-width,initial-scale=1">
<link href="<?=$css, ($get_categ ? '?categ='.$get_categ : '')?>" rel=stylesheet>
<?=$header?>
</head>
<body id=TOP class=text-break>
<header id=header>
<div class="container d-flex flex-wrap justify-content-center align-items-center py-5 h-100">
<a class="col-md-2 text-md-left text-center" href="<?=$url?>atom.php"><img src="<?=$css?>Rss_font_awesome.svg" data-license="Dave Gandy CC BY-SA 3.0" alt=atom width=25 height=25></a>
<h1 class="col-md-8 text-center h2 py-3"><a class="text-white" href="<?=$url?>"><?=get_logo()?></a></h1>
<div class="col-md-2 text-md-right"><?=$search?></div>
</div>
</header>
<?php if ($nav): ?><nav id=nav><ul class="list-unstyled text-center m-0 py-3"><?=$nav?></ul></nav><?php endif?>
<main id=main class="container py-5"><?=$article?></main>
<aside id=side class="container pb-5"><div class="d-flex justify-content-between flex-wrap p-0"><?=$aside?></div></aside>
<ol id=breadcrumb class="breadcrumb clearfix justify-content-center py-3 m-0"><?=$breadcrumb?></ol>
<a href="#TOP" id=page-top class="btn btn-outline-dark"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="currentColor" d="m2.5872 45.447-2.5872-2.043 24.034-40.851 23.966 40.987-2.996 1.362-21.038-35.745z"/></svg></a>
<script src="<?=$js?>"></script>
<footer id=footer class="d-flex justify-content-center align-items-center"><?=$footer?></footer>
</body>
</html>
