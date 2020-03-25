<!doctype html>
<html lang=<?=$lang?>>
<head>
<meta charset=<?=$encoding?>>
<meta name=viewport content="width=device-width,initial-scale=1">
<link href="<?=$css?>" rel=stylesheet>
<?=$header?>
</head>
<body id=TOP>
<div class=container>
<header class="py-3 row justify-content-between align-items-center">
<a class="col-md-2 text-md-left text-center" href="<?=$url?>atom.php"><img src="<?=$css?>Rss_font_awesome.svg" data-license="Dave Gandy CC BY-SA 3.0" alt=atom width=25 height=25></a>
<h1 class="col-md-8 text-center h2"><a class="text-white" href="<?=$url?>"><?=get_logo()?></a></h1>
<div class="col-md-2 text-md-right"><?=$search?></div>
</header>
<nav><ul class="list-unstyled py-2"><?=$nav?></ul></nav>
<div class="row">
<main id=main class="col-lg-8 mt-4"><?=$article?></main>
<aside id=side class="col-lg-4 d-flex flex-column"><?=$aside?></aside>
</div>
</div>
<footer class="text-center pb-2 pt-4">
<ol class="breadcrumb bg-transparent justify-content-center"><?=$breadcrumb?></ol>
<a href="#TOP" id=page-top class="btn btn-outline-dark"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="currentColor" d="m2.5872 45.447-2.5872-2.043 24.034-40.851 23.966 40.987-2.996 1.362-21.038-35.745z"/></svg></a>
<script src="<?=$js?>"></script>
<?=$footer?>
</footer>
</body>
</html>
