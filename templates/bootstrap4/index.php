<!doctype html>
<html lang=<?=$lang?>>
<head>
<meta charset=<?=$encoding?>>
<meta name=viewport content="width=device-width,initial-scale=1">
<link href="<?=$css?>" rel=stylesheet>
<?=$header?>
</head>
<body id=TOP>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
<a class=navbar-brand href="<?=$url?>"><?=get_logo()?></a>
<button class=navbar-toggler type=button data-toggle=collapse data-target="#nav" accesskey=n tabindex=100>
<span class=navbar-toggler-icon></span>
</button>
<div class="collapse navbar-collapse" id=nav>
<div class="navbar-nav mr-auto mt-2 mt-lg-0"><?=$nav?></div>
<?=$search?>
</div>
</nav>

<ol class="breadcrumb rounded-0"><?=$breadcrumb?></ol>

<div class=container-fluid>
<div class=row>
<div class=col-lg-9><?=$article?></div>
<div class=col-lg-3><?=$aside?></div>
</div>
</div>

<footer class=text-center>
<a href="#TOP" id=page-top class="btn btn-outline-primary">&uarr;</a>
<script src="<?=$js?>"></script>
<?=$footer?>
</footer>
</body>
</html>
