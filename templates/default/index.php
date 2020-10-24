<!doctype html>
<html lang=<?=$lang?>>
<head>
<meta charset=<?=$encoding?>>
<meta name=viewport content="width=device-width,initial-scale=1">
<link href="<?=$css, (!$get_categ ? '' : '?categ='.$get_categ)?>" rel=stylesheet>
<?=$header?>
</head>
<body id=TOP>
<header id=header>
<div class="container d-flex flex-wrap justify-content-center align-items-center py-4 h-100">
<a class="col-md-2 text-md-left text-center" href="<?=$url?>atom.php"><img src="<?=$css?>Rss_font_awesome.svg" data-license="Dave Gandy CC BY-SA 3.0" alt=atom width=25 height=25></a>
<h1 class="col-md-8 text-center h2 py-3"><a class="text-white" href="<?=$url?>"><?=get_logo()?></a></h1>
<div class="col-md-2 text-md-right"><?=$search?></div>
</div>
</header>
<nav><ul class="list-unstyled text-center py-3"><?=$nav?></ul></nav>
<main id=main class=container ><?=$article?></main>
<aside id=side class="card-deck justify-content-around align-items-start m-5"><?=$aside?></aside>
<ol class="breadcrumb bg-transparent  justify-content-center mt-5"><?=$breadcrumb?></ol>
<a href="#TOP" id=page-top class="btn btn-outline-dark"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="currentColor" d="m2.5872 45.447-2.5872-2.043 24.034-40.851 23.966 40.987-2.996 1.362-21.038-35.745z"/></svg></a>
<script src="<?=$js?>"></script>
<footer id=footer class="d-flex  justify-content-center align-items-center mt-5"><?=$footer?></footer>
</body>
</html>
