<?php
include '../../../includes/functions.php';
include '../../../includes/config.php';
header('Last-Modified: '. $last_modified = timestamp(__FILE__));
if ($last_modified === filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE')) header('HTTP/1.1 304 Not Modified');
header('Content-Type: text/css; charset='. $encoding);
$bootstrap = file_get_contents('bootstrap.min.css');
if ($color)
{
	#primary
	$bootstrap = str_replace('#007bff', hsla($color, 0, -5), $bootstrap);
	#secondary
	$bootstrap = str_replace('#6c757d', hsla($color, -70), $bootstrap);
	#light
	$bootstrap = str_replace('#f8f9fa', $c05 = hsla($color, 0, 0, .08), $bootstrap);
	#dark
	#$bootstrap = str_replace('#343a40', hsla($color, -20, -10), $bootstrap);
	#warning
	$bootstrap = str_replace('#ffc107', 'hsla(50, 100%, 47%)', $bootstrap);
	#*-primary
	$bootstrap = str_replace('#b8daff', hsla($color, -10, 20), $bootstrap);
	#active
	$bootstrap = str_replace('rgba(0,0,0,.075)', hsla($color, 50, 50, .5), $bootstrap);
	#breadcrumb, focus
	$bootstrap = str_replace(['#e9ecef', 'rgba(0,123,255,.5)', 'rgba(0,123,255,.25)', 'rgba(128,189,255,.5)', 'rgba(38,143,255,.5)'], $c05, $bootstrap);
	#hover
	$bootstrap = str_replace('#0056b3', hsla($color, 0, 0, .5), $bootstrap);
	#btn:hover
	$bootstrap = str_replace(['#0069d9', '#0062cc'], hsla($color, 0, 5), $bootstrap);
	#list link
	$bootstrap = str_replace(['#495057', '#004085', '#002752'], hsla($color, -10, -10), $bootstrap);
	#alert
	$bootstrap = str_replace('#cce5ff', hsla($color, 0, 25), $bootstrap);
	#border
	$bootstrap = str_replace(['#7abaff', '#80bdff', '#005cbf'], hsla($color, 0, 20), $bootstrap);
	$c = hsla($color);
	$c2 = hsla($color, 0, 0, .2);
	$bootstrap = str_replace(['-radius:.3rem', '-radius:.25rem', '-radius:.2rem'], '-radius:0', $bootstrap);
}
else
{
	$bootstrap = str_replace('#e9ecef', 'rgba(0, 0, 0, .5)', $bootstrap);
}
$get_categ = !filter_has_var(INPUT_GET, 'categ') ? '' : basename(filter_input(INPUT_GET, 'categ', FILTER_SANITIZE_STRING));
echo $bootstrap, file_get_contents('jquery.fancybox.min.css'), '
#main{min-height:calc(100vh - 722px)}
#side .list-group *:not(input):not(label):not(.btn),#side .bg-primary,.page-item.disabled .page-link{border:0;background-color:inherit!important}
.input-group-text,.form-control{border:0}
#side .list-group-item.bg-primary{font-size:1.25rem}
::placeholder,label,.form-control,.form-control:focus{color:#eeeffd!important}
#page-top{bottom:1em;position:fixed;right:2em;display:none;z-index:10}
#page-top svg{width:1em}
.article{font-size:large;line-height:1.9}
.badge-light{background-color:#f8f9fa}
.banned{filter:grayscale(100%);opacity:.8}
.bg-danger.text-danger{background-color:#f2dede!important;color:#a94442!important}
.bg-info.text-info{background-color:#d9edf7!important;color:#31708f!important}
.bg-success.text-success{background-color:#dff0d8!important;color:#3c763d!important}
.bg-warning.text-warning{background-color:#fcf8e3!important;color:#8a6d3b!important}
.border-black{border-color:#222!important}
#main .card-columns{column-count:2}
.card-img-top{border-top-left-radius:unset;border-top-right-radius:unset}
.h1,.h2,.card-footer{text-align:center}
body{background:linear-gradient(black,#16161d 200px);color:#d4d4e3}
header,footer,.comment:target{text-shadow:0 .05rem 0.1rem rgba(0,0,0,.5)}
select option{background:#16161d}
#footer{line-height:1.8;height:150px;background:linear-gradient(to top,'. ($color ? hsla($color, 0, 0, .05) : 'rgba(36,36,80,.5)'). ',transparent)}
h1,h2,h3:not(.popover-header),h4,h5,h6,.h1,.h2,.h3:not(b),.h4,.h5,.h6{color:white;font-family:serif;text-shadow:0 .05rem .04rem rgba(0,0,0,.3)}
a{color:#eeeffd}
a:hover,.comment:target{color:white}
.page-link,.page-item{border:none;border-radius:0 0!important}
.custom-file-label:after{content:"'. $custom_file_label. '"}
.border-10{border-width:10px!important}
.img-responsive{display:block;max-width:100%;height:auto}
.img-thumbnail,.form-control[readonly],#side .bg-light.current,.form-control:focus{background-color:inherit!important}
.lock{width:.6em;height:.7em;margin-left:.2em;background-image:url(\'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" fill="'. ($color ? $c : 'dimgray'). '" viewBox="0 0 4 5"%3E%3Cpath d="m0.2422 1.7224v0.0608c-0.138 0.0285-0.2422 0.1489-0.2422 0.2931v2.6248c0.000005 0.1658 0.1357 0.2989 0.3047 0.2989h3.3906c0.169 0 0.3047-0.13409 0.3047-0.29889v-2.6248c0-0.1442-0.1042-0.26466-0.2422-0.2931v-0.0608c0-0.95055-0.7888-1.7224-1.7578-1.7224-0.969 0.0000052-1.7578 0.77186-1.7578 1.7224zm1.7578-1.1227c0.631 0 1.1465 0.50276 1.1465 1.1227v0.053149h-2.293v-0.053051c0.00002-0.61995 0.5145-1.1228 1.1465-1.1228zm0 2.0974h0.00195c0.237 0 0.42969 0.18709 0.42969 0.41958 0 0.12066-0.052766 0.23003-0.13477 0.30654l0.11523 0.59584c0.006 0.033352-0.014828 0.059393-0.048828 0.059393h-0.7149c-0.034 0-0.056781-0.026041-0.050781-0.059393l0.11328-0.59201c-0.086-0.076513-0.14062-0.18678-0.14062-0.31038 0-0.23248 0.1927-0.41955 0.4297-0.41955z"%2F%3E%3C%2Fsvg%3E\');background-repeat:no-repeat}
.modal,.popover{color:#555}
.nowrap{white-space:normal}
.page-top:after{content:"'. $pagetop. '";position:absolute;opacity:0;right:-2em}
.page-top:hover:after{opacity:1;right:0;transition:.3s linear}
.page-top:not(:hover):after{opacity:0;transition:.3s}
.page-top{border-bottom:thin dotted;position:relative;text-decoration:none!important}
.permit{left:.5em}
.popover{max-width:70%}
.similar-article{height:2rem}
.sticky-top{top:10px}
.text-light{color:'. ($color ? hsla($color, -5, 20, .9) : 'inherit'). ' !Important}
.user{max-width:400px}
.wrap,.popover-body,.fancybox-caption__body{word-wrap:break-word;white-space:pre-wrap}
a,li,.title{overflow-wrap:break-word}
a:hover,a:hover img,a:hover svg,.social:hover,img[alt=K],.list-group-item-action:hover{opacity:.8}img[alt=K]:hover{opacity:1}
#side a.bg-light:hover,.custom-file-label,.custom-select{background-color:inherit!important}
#side #toc .h2{font-size:1.25rem}
#side div.h5:first-letter,h1.h3:first-letter,h2.h4:first-letter{font-size:125%}
label:not(.active),img[alt=K]{cursor:pointer}
div{min-width:0}
pre{color:#ccc}
feed{background:#16161c;color:aliceblue;line-height:2;margin:2em;text-align:center}
entry{display:block;margin:1em auto;overflow-wrap:break-word;padding:1em 2em;position:relative;max-width:600px}
feed title,entry title{font-size:1.3em;font-family:serif;text-shadow:0 .05rem .04rem rgba(0,0,0,.3);padding:.5em}
entry img{margin:1em auto;margin-bottom:0;max-width:100%}
feed title,entry title,feed id,entry id,feed updated,entry updated,author,rights,generator{display:block;margin-bottom:1em}
author,generator{padding:1em}
urlset{display:flex;flex-wrap:wrap;justify-content:space-between;background:#16161c;color:aliceblue}
urlset:before{width:100%;border-bottom:thin solid aliceblue;text-align:center;font-size:x-large;display:block;content:"'. $site_name. '";padding-top:1em;margin-bottom:1.5em;padding-bottom:1em}
url:hover{background:aliceblue;color:#16161c}
url{margin:1.5em;width:30%;display:block;font-size:small;padding:1em;overflow-wrap:break-word}
lastmod{display:block;text-align:right;margin-top:1em;white-space:nowrap}
.card,.list-group-item:not(.list-group-item-primary):not(.list-group-item-secondary):not(.list-group-item-success):not(.list-group-item-danger):not(.list-group-item-warning):not(.list-group-item-info):not(.list-group-item-light):not(.list-group-item-dark),.page-item,.page-link,.nav-tabs .nav-item.show .nav-link,.nav-link.active,#side div[id]:not(#toctoggle),.input-group-text,.form-control,.article,#article-nav,#main #toc,#side a:hover:not(.btn),#main>header,entry,a[aria-expanded=true]{background-color:'. ($color ? $c05 : 'rgba(0,0,0,.1)'). '}
#main>header{margin-bottom:.75rem;padding:3em;text-align:center}
#side div[id]{margin:.75rem;flex-basis:32%}
::selection,.highlight{background-color:'. ($color ? $c2 : 'white'). ';color:'. ($color ? $c : 'black'). '}
.a{display:inline-block;cursor:pointer;margin:0 0 15px 10px}
.a span{color:#ffffff;position:relative;display:block;line-height:1.4em;border-radius:2em;padding:.2em 1em;box-shadow:inset 0 1px 0 rgba(0,0,0,.2),0 1px 0 rgba(0,0,0,.1);transition:color .3s ease,padding .3s ease-in-out,background .3s ease-in-out;text-shadow:0px 1px 1px rgba(0,0,0,.4)}
.a span:after{position:absolute;content:\'\';border-radius:2em;box-shadow:0px 1px 1px rgba(0,0,0,.4);width:1.3em;height:1.3em;margin-left:-1.45em;top:.25em;background:#ffffff;transition:left .3s cubic-bezier(.2,1,.3,1),background .3s ease-in-out}
.a input[type="checkbox"]{display:none!important}
.a input[type="checkbox"]:not(:checked)+span{background:#de474e;padding-left:1.6em;padding-right:.4em}
.a input[type="checkbox"]:not(:checked)+span:after{left:1.6em}
.a input[type="checkbox"]:checked+span{background:#45ce5b;padding-left:.4em;padding-right:1.6em}
.a input[type="checkbox"]:checked+span:after{left:100%}
';
if ($use_auto_wrap) echo '
.article,.card-text{word-wrap:break-word;white-space:pre-wrap}
.article h1,.article h2,.article h3,.article h4,.article h5,.article h6{margin-bottom:0}
.article #accordion,.article .accordion,.article .btn-group,.article .card,.article .carousel,.article .custom-control,.article .custom-file,.article .form-group,.article .form-horizontal,.article .input-group,.article .list-group,.article .media,.article .modal,.article .navbar,.article .row,.article .tab-content,.article dl,.article fieldset,.article li,.article ol,.article table,.article ul{white-space:normal!important}';
else echo '
.page-top{padding-top:2rem!important;margin-bottom:2rem!important}';

if (is_file($header_img = '../../../contents/'. $get_categ. '/header.jpg')
|| is_file($header_img = '../../../contents/'. $get_categ. '/header.png')
|| is_file($header_img = '../../../images/header.jpg') || is_file($header_img = 'header.jpg')
|| is_file($header_img = '../../../images/header.png') || is_file($header_img = 'header.png'))
{
	if (list (, $height) = getimagesize($header_img)) echo '
#nav{background-color:'. ($color ? $c05 : 'rgba(0,0,0,.1)'). '}
#header{background-color:rgba(0,0,0,.15);height:'. $height. 'px}
#header:before{background:url("'. $header_img. '") center/cover no-repeat;content:"";height:'. $height. 'px;opacity:.9;position:absolute;top:0;right:0;bottom:0;left:0;z-index:-1}
#footer{height:'. $height. 'px;overflow:hidden;position:relative}
#footer:after{bottom:0;background:url("'. $header_img. '") center/cover no-repeat;filter:brightness(.3) blur(5px);content:"";display:block;position:absolute;height:'. $height. 'px;width:100%;transform:scale(1.2);z-index:-1}';
}
else echo '#nav,#breadcrumb{background-color:transparent}';
if (is_file($bg_img = '../../../contents/'. $get_categ. '/background.jpg')|| is_file($bg_img = '../../../contents/'. $get_categ. '/background.png')
|| is_file($bg_img = '../../../images/background.jpg') || is_file($bg_img = 'background.jpg')
|| is_file($bg_img = '../../../images/background.png') || is_file($bg_img = 'background.png'))
echo '
#TOP{position:relative}#TOP::after{background:url("'. $bg_img. '") center/cover no-repeat fixed;content:"";position:absolute;top:0;right:0;bottom:0;left:0;opacity:.35;z-index:-2}';

echo '#copyright{z-index:1}@media(max-width:1200px){#side div[id]{flex-basis:49%}}@media(max-width:767px){.index{flex-direction:column}.index div{width:100%!important}#main .card-columns{column-count:1}#search{text-align:center}#form .input-group-text{display:block}#side div[id]{flex-basis:100%}}';
