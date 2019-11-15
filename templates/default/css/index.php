<?php
include '../../../includes/functions.php';
include '../../../includes/config.php';

$bootstrap = file_get_contents('bootstrap.min.css');

if ($color)
{
	#primary
	$bootstrap = str_replace('#007bff', hsla($color, 5.5, -7, .9), $bootstrap);
	#secondary
	$bootstrap = str_replace('#6c757d', hsla($color, -30, -10), $bootstrap);
	#light
	$bootstrap = str_replace('#f8f9fa', hsla($color, 5, 5, .1), $bootstrap);
	#dark
	$bootstrap = str_replace('#343a40', hsla($color, -20, -10), $bootstrap);
	#warning
	$bootstrap = str_replace('#ffc107', 'hsla(50, 100%, 47%)', $bootstrap);
	#*-primary
	$bootstrap = str_replace('#b8daff', hsla($color, -5, -5), $bootstrap);
	#breadcrumb, focus
	$bootstrap = str_replace(['#dae0e5', '#e9ecef', 'rgba(0,123,255,.5)', 'rgba(0,123,255,.25)', 'rgba(128,189,255,.5)', 'rgba(38,143,255,.5)'], hsla($color, 0, 0, .05), $bootstrap);
	#hover
	$bootstrap = str_replace('#0056b3', hsla($color, -10, -10), $bootstrap);
	#btnhover
	$bootstrap = str_replace(['#0069d9', '#0062cc'], hsla($color, -10, -15), $bootstrap);
	#list link
	$bootstrap = str_replace(['#495057', '#004085', '#002752'], hsla($color, -15, -15), $bootstrap);
	#alert
	$bootstrap = str_replace('#cce5ff', hsla($color, 0, 10), $bootstrap);
	#border
	$bootstrap = str_replace(['#7abaff', '#80bdff', '#005cbf'], hsla($color, 0, 20), $bootstrap);
}

header('Content-Type: text/css; charset='. $encoding);
header('Last-Modified: '. $last_modified = timestamp(__FILE__));

if (filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE') === $last_modified) header('HTTP/1.1 304 Not Modified');

echo
$bootstrap,
file_get_contents('jquery.fancybox.min.css'), '
#page-top{bottom:.8em;position:fixed;right:.8em;z-index:10}
#page-top svg{width:1em}
#page-top,#toc{display:none}
.article{font-size:large;line-height:1.9}
.categ-summary,.index-summary{line-height:1.7}
.avatar{min-height:100px;min-width:100px;width:100px}
.badge-light{background-color:#f8f9fa}
.banned{filter:grayscale(100%);opacity:.8}
.bg-danger.text-danger{background-color:#f2dede!important;color:#a94442!important}
.bg-info.text-info{background-color:#d9edf7!important;color:#31708f!important}
.bg-success.text-success{background-color:#dff0d8!important;color:#3c763d!important}
.bg-warning.text-warning{background-color:#fcf8e3!important;color:#8a6d3b!important}
.card-columns{column-count:2}
.col-lg-9{min-width:0}
.comment:target{box-shadow:5px 5px 5px 3px rgba(0,0,0,.2)}
.comment-icon{background-color:'. ($color ? hsla($color, 5.5, -7,.9) : 'inherit'). '}
.card-arrow:before{background-color:#fff;border-right:solid 1px rgba(0,0,0,.125);border-top:solid 1px rgba(0,0,0,.125);height:15px;transform:rotate(225deg);width:15px;content:"";display:block;left:117px;position:absolute;top:16px;z-index:1}
.custom-file-label:after{content:"'. $custom_file_label. '"}
.flow{color:dimgray;font-size:small;padding:0;list-style:none;counter-reset:num;margin-bottom:2em}
.flow li{overflow-wrap:break-word;width:25%;float:left;position:relative;text-align:center;padding:0 1em}
.flow li:before{counter-increment:num;content:counter(num,upper-roman);height:30px;width:30px;line-height:30px;border:thin solid lightgray;display:block;margin:0 auto 10px auto;border-radius:50%;background-color:white}
.flow li:after{content:"";position:absolute;width:100%;height:1px;background-color:lightgray;top:15px;left:-55%;z-index:-1}
.flow li:first-child:after{content:none}
.flow li.active{color:limegreen}
.flow li.active:before{border-color:limegreen}
.img-responsive{display:block;max-width:100%;height:auto}
.lock{width:.6em;height:.7em;margin-left:.2em;background-image:url(\'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" fill="'. ($color ? hsla($color) : 'dimgray'). '" viewBox="0 0 4 5"%3E%3Cpath d="m0.2422 1.7224v0.0608c-0.138 0.0285-0.2422 0.1489-0.2422 0.2931v2.6248c0.000005 0.1658 0.1357 0.2989 0.3047 0.2989h3.3906c0.169 0 0.3047-0.13409 0.3047-0.29889v-2.6248c0-0.1442-0.1042-0.26466-0.2422-0.2931v-0.0608c0-0.95055-0.7888-1.7224-1.7578-1.7224-0.969 0.0000052-1.7578 0.77186-1.7578 1.7224zm1.7578-1.1227c0.631 0 1.1465 0.50276 1.1465 1.1227v0.053149h-2.293v-0.053051c0.00002-0.61995 0.5145-1.1228 1.1465-1.1228zm0 2.0974h0.00195c0.237 0 0.42969 0.18709 0.42969 0.41958 0 0.12066-0.052766 0.23003-0.13477 0.30654l0.11523 0.59584c0.006 0.033352-0.014828 0.059393-0.048828 0.059393h-0.7149c-0.034 0-0.056781-0.026041-0.050781-0.059393l0.11328-0.59201c-0.086-0.076513-0.14062-0.18678-0.14062-0.31038 0-0.23248 0.1927-0.41955 0.4297-0.41955z"%2F%3E%3C%2Fsvg%3E\');background-repeat:no-repeat}
.nowrap{white-space:normal}
.page-top:after{content:"'. $pagetop. '";position:absolute;opacity:0;right:-2em}
.page-top:hover:after{opacity:1;right:0;transition:.3s linear}
.page-top:not(:hover):after{opacity:0;transition:.3s}
.page-top{clear:both;border-bottom:thin dotted;position:relative;text-decoration:none!important}
.permit{left:.5em}
.popover{max-width:70%}
.similar-article{height:2rem}
.sticky-top{top:10px}
.text-light{color:'. ($color ? hsla($color, -5, +20, .9) : 'inherit'). ' !Important}
.title{color:white}
.wrap,.popover-body{word-wrap:break-word;white-space:pre-wrap}
::-moz-selection,.highlight{background-color:#d9edf7;color:#31708f}
::selection,.highlight{background-color:#d9edf7;color:#31708f}
a:hover img,a:hover svg,.social:hover,img[alt=K]{opacity:.8}img[alt=K]:hover{opacity:1}
body{color:#555555;letter-spacing:.4px}
h1,h2,h3,h4,h5,h6{border-bottom:thin dotted;padding:.1em;overflow-wrap:break-word;margin-bottom:.5em}
a,li,.title{overflow-wrap:break-word}
label:not(.active),img[alt=K]{cursor:pointer}
urlset{background-repeat: no-repeat; background:linear-gradient(to left,white,whitesmoke);color:dimgray}
urlset:before{border-bottom:medium solid dimgray;margin:1em;text-align:center;font-size:x-large;display:block;content:"Sitemap of '. $server. '";padding-bottom:1em}
url:hover{background-color:whitesmoke;color:darkgray}
url{margin:1em auto;width:95%;display:block;font-size:small;padding:1em;overflow-wrap:break-word}
lastmod{display:block;text-align:right;margin-top:1em;white-space:nowrap}
@media(max-width:991px){input[type="search"]{margin-top:1em}.card-columns{column-count:1}#form .input-group-text{display:block}}';
if ($use_auto_wrap) echo '
.article,.card-text{word-wrap:break-word;white-space:pre-wrap}
.article h1,.article h2,.article h3,.article h4,.article h5,.article h6{margin-bottom:0}
.article #accordion,.article .accordion,.article .btn-group,.article .card,.article .carousel,.article .custom-control,.article .custom-file,.article .form-group,.article .form-horizontal,.article .input-group,.article .list-group,.article .media,.article .modal,.article .navbar,.article .row,.article .tab-content,.article dl,.article fieldset,.article li,.article ol,.article table,.article ul{white-space:normal!important}';

else echo '.page-top{padding-top:2rem!important;margin-bottom:2rem!important}';

if ($color) echo '
.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{background-color:'. hsla($color, 0, 0, .05). '}
::selection, .highlight{background-color:'.hsla($color, 0, 0, .2).';color:'.hsla($color).'}
::-moz-selection, .highlight{background-color:'.hsla($color, 0, 0, .2).';color:'.hsla($color).'}
.title,.btn:not([class*="btn-outline"]),[class*="btn-outline"]:hover{background-image:linear-gradient(to bottom,rgba(0,0,0,.05),rgba(0,0,0,.2))}
.col-md-6.mb-5 ul.list-group.list-group-flush li.list-group-item a{color:'. ($color ? hsla($color, -50, -50, .75) : 'inherit'). '}
.navbar-dark .navbar-nav .nav-link{color:rgba(255, 255, 255, .7)}
.navbar-dark .navbar-nav .nav-link:hover, .navbar-dark .navbar-nav .nav-link:focus{color:rgba(255, 255, 255, .9)}';
if (is_file($header_jpg = '../../../contents/'. basename(filter_input(INPUT_GET, 'categ', FILTER_SANITIZE_STRING)). '/header.jpg') || is_file($header_jpg = '../../../images/header.jpg')) echo'
body:before{background-image:url('. $header_jpg. ');background-position:bottom;background-repeat:no-repeat;background-size:cover;content:"";display:block;height:100px;width:100%}
body:after{align-items:center;color:'. ($color ? hsla($color, 0, -30, .9) : 'inherit'). ';display:flex;font-size:large;background-color:'. ($color ? hsla($color, 0, -30, .1) : 'rgba(0,0,0,.1)'). ';content:"'. $meta_description. '";justify-content:center;text-shadow:0px 0px 5px white;letter-spacing:.15em;position:relative;height:100px;left:0;position:absolute;top:0;width:100%}
div.container-fluid{color:'. ($color ? hsla($color, -50, -50, .7) : 'inherit'). '}';
