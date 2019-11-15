<!doctype html>
<html lang=<?=$lang?>>
<head>
<meta charset=<?=$encoding?>>
<meta name=viewport content="width=device-width,initial-scale=1">
<link href="<?=$css, ($get_categ ? '?categ='.$get_categ : '')?>" rel=stylesheet>
<?=$header?>
</head>
<body id=TOP>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary title">
<a class=navbar-brand href="<?=$url?>"><?=get_logo()?></a>
<button class=navbar-toggler type=button data-toggle=collapse data-target="#nav" accesskey=n tabindex=100>
<span class=navbar-toggler-icon></span>
</button>
<div class="collapse navbar-collapse" id=nav>
<ul class="navbar-nav mr-auto mt-2 mt-lg-0"><?=$nav?></ul>
<?=$search?>
</div>
</nav>
<ol class="breadcrumb rounded-0 justify-content-end"><?=$breadcrumb?></ol>
<div class=container-fluid>
<div class=row>
<div id=main class=col-lg-9><?=$article?></div>
<div id=side class="col-lg-3 d-flex flex-column"><?=$aside?></div>
</div>
</div>
<footer class="text-center pb-3">
<a href="#TOP" id=page-top class="btn btn-outline-primary">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="currentColor" d="m2.5872 45.447-2.5872-2.043 24.034-40.851 23.966 40.987-2.996 1.362-21.038-35.745z"/></svg>
</a>
<script src="<?=$js?>"></script>
<?=$footer?>
</footer>
</body>
</html>
