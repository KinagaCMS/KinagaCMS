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
<div class="row rear">
<header>
<div class=container>
<div class=row>
<div class=col-xs-4><a href="<?=$url?>"><?=$site_name?></a></div>
<div class="col-xs-8 text-right"><?=$meta_description?></div>
</div>
</div>
</header>
<nav class="navbar navbar-inverse">
<div class=navbar-header>
<button type=button class=navbar-toggle data-toggle=collapse data-target=#navbar tabindex=9 accesskey=t>
<span class=icon-bar></span>
<span class=icon-bar></span>
<span class=icon-bar></span>
<span class=sr-only>&#9776;</span>
</button>
</div>
<div id=navbar class="navbar-collapse collapse">
<ul class="nav navbar-nav">
<li<?=$current?>><a href="<?=$url?>" class=home><span class="glyphicon glyphicon-home"></span> <?=$home?></a></li>
<?=$nav?>
</ul>
</div>
</nav>
<ol class=breadcrumb>
<?=$breadcrumb?>
</ol>
<div class=col-md-2>
<div class="alert alert-info" style="height:800px;"><strong>Sponsor</strong></div></div>
<div class=col-md-7><?=$article?></div>
<div class=col-md-3><?=$aside?></div>
<div class=clearfix></div>
<div class="navbar navbar-inverse footer">
<footer class=text-center>
<script src="<?=$js?>"></script>
<?=$footer?>
</footer>
</div>
</div>
</div>
</body>
</html>
