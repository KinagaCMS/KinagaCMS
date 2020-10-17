<?php if (__FILE__ === implode(get_included_files())) exit?>
<!doctype html>
<head>
<meta charset=<?=$encoding?>>
<?=$header?>
<style>
	body{background-color:#2a2a2a;color:#cdc8c0;line-height:1.9;margin:2em 10em}
	a{color:#e2deda}a:hover{color:#ffffff}
	a+a,span+span,ul li{margin-left:.5em;margin-right:.5em}
	footer,div{margin-top:2em}
	footer img{display:block;margin-bottom:1em;margin-top:1em}
	input,textarea,button{background-color:inherit;border:thin solid;color:inherit;display:block;padding:.5em}
	ul{padding-left:0}ul li{display:inline-block}
	.categ{display:flex;flex-wrap:wrap;justify-content:space-between}
	.card{margin:1em;flex:1 0 25em;position:relative;background-color:#343434;padding:1em 2em}
	.card-footer{display:flex;justify-content:space-between}
	.modal.fade{display:none}
	.social{padding:.5em}
	.wrap,.popover-body{word-wrap:break-word;white-space:pre-wrap}
</style>
</head>
<header>
	<h1><a href="<?=$url?>"><?=get_logo()?></a></h1>
	<ul><?=$nav?></ul>
</header>
<article><?=$article?></article>
<aside><?=$aside?></aside>
<footer><?=$footer?></footer>