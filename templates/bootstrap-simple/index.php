<!doctype html>
<html lang=<?=$lang?>>
<head>
<meta charset=<?=$encoding?>>
<meta name=viewport content="width=device-width,initial-scale=1">
<link href="<?=$css?>" rel=stylesheet>
<?=$header?>
</head>
<body id=TOP>
<nav class=navbar>
<div class=container>
<div class=navbar-header>
<button type=button class=navbar-toggle data-toggle=collapse data-target=#navbar tabindex=9 accesskey=t>
<span class=icon-bar></span>
<span class=icon-bar></span>
<span class=icon-bar></span>
<span class=sr-only>&#9776;</span>
</button>
<strong><a class=navbar-brand href="<?=$url?>"><?=$site_name?></a></strong>
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
<footer>
<script src="<?=$js?>"></script>
<?=$footer?>
</footer>
</div>
</body>
</html>
