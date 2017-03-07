<!doctype html>
<html lang=<?=$lang?>>
<head>
<meta charset=<?=$encoding?>>
<meta name=viewport content="width=device-width,initial-scale=1">
<link href="<?=$css?>" rel=stylesheet>
<?=$header?>
</head>
<body id=TOP>
<nav class="navbar navbar-inverse">
<div class=container>
<div class=navbar-header>
<button type=button class=navbar-toggle data-toggle=collapse data-target=#navbar tabindex=9 accesskey=t>
<span class=icon-bar></span>
<span class=icon-bar></span>
<span class=icon-bar></span>
<span class=sr-only>&#9776;</span>
</button>
<a class=navbar-brand href="<?=$url?>"><?=$site_name?></a>
</div>
<div id=navbar class="navbar-collapse collapse">
<ul class="nav navbar-nav navbar-right">
<?=$nav?>
</ul>
</div>
</div>
</nav>
<div class=container>
<ol class=breadcrumb>
<?=$breadcrumb?>
</ol>
<div class=row>
<div class=col-md-9><?=$article?></div>
<div class=col-md-3><?=$aside?></div>
</div>
</div>
<div class="navbar navbar-inverse footer">
<div class=container>
<footer class=text-center>
<script src="<?=$js?>"></script>
<?=$footer?>
</footer>
</div>
</div>
</body>
</html>
