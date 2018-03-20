<?php
if (is_file($config = '../../../includes/config.php'))
	include_once $config;

$last_modified = timestamp(__FILE__);

header('Content-Type: text/css; charset=' . $encoding);
header('Last-Modified: ' . $last_modified);

if (filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE') === $last_modified)
	header('HTTP/1.1 304 Not Modified');

echo
file_get_contents('bootstrap.min.css'),
file_get_contents('magnific-popup.min.css'),

'
body{font-family:"Droid Sans","Yu Gothic",YuGothic,"Hiragino Sans",sans-serif;color:#555}

.article{font-size:large;line-height:1.8}

.similar-article{height:2rem}

.card-columns{column-count:2}

a:hover img{opacity:0.8}

::selection,.highlight,.comment:target{background-color:#d9edf7;color:#31708f}
::-moz-selection,.highlight,.comment:target{background-color:#d9edf7;color:#31708f}

.mfp-title{padding-bottom:3em}
.wrap,.mfp-title{word-wrap:break-word;white-space:pre-wrap}

#top{bottom:1em;position:fixed;right:2em;display:none;z-index:10}
.page-top{border-bottom:thin dotted;position:relative;text-decoration:none!important}
.page-top:hover:after{opacity:1;right:0;transition:.3s linear}
.page-top:not(:hover):after{opacity:0;transition:.3s}
.page-top:after{content:"'.$pagetop.'";position:absolute;opacity:0;right:-2em}

@media(max-width:767px){.card-columns{column-count:1}}

.t{background-color:rgb(85, 172, 238);border-color:rgb(85, 172, 238)}.t:hover{background-color:rgba(85, 172, 238, .9);border-color:rgba(85, 172, 238, .1)}
.g{background-color:rgb(221, 75, 57);border-color:rgb(221, 75, 57)}.g:hover{background-color:rgba(221, 75, 57, .9);border-color:rgba(221, 75, 57, .1)}
.f{background-color:rgb(59, 89, 152);border-color:rgb(59, 89, 152)}.f:hover{background-color:rgba(59, 89, 152, .9);border-color:rgba(59, 89, 152, .1)}
';

if ($use_auto_wrap === true)
	echo '
.article{word-wrap:break-word;white-space:pre-wrap}

.article .navbar,
.article .media,
.article .input-group,
.article table,
.article .row,
.article ul,
.article li,
.article ol,
.article dl,
.article .card,
.article .alert,
.article .btn-group,
.article fieldset,
.article .list-group,
.article .form-group,
.article .form-horizontal,
.article .modal,
.article .tab-content,
.article .custom-control,
.article .custom-file,
.article .carousel,
.article .accordion,
.article #accordion
{white-space:normal!important}

';
else
	echo '.page-top{padding-top:2rem!important;margin-bottom:2rem!important}';

if ($color)
	echo '
a,
.btn-link,
.page-link,
.badge-light,
.custom-select,
.custom-file-label,
.form-control:focus,
.btn-outline-primary
{color:'.hsla($color).'}

body,
.form-control,
.input-group-text,
.custom-file-label:after,
.list-group-item-action,
.list-group-item.active,
.nav-tabs .nav-link.active,
.nav-tabs .nav-item.show .nav-link
{color:'.hsla($color, -10, -10, .9).'}

.text-muted,
.breadcrumb-item+.breadcrumb-item:before
{color:'.hsla($color, -10, 3, .8).'!important}

.blockquote-footer,
.breadcrumb-item.active,
.btn-link:hover,
.list-group-item-action:focus,
.list-group-item-action:hover,
.page-link:hover,
a:hover,
input.color
{color:'.hsla($color, 5, 10, .8).'}

.form-control::placeholder
{color:'.hsla($color, '', '', .6).'}

.nav-link.disabled
{color:'.hsla($color, -5, -5, .5).'}

.page-item.active .page-link,
.btn-outline-primary:hover
{color:white!important}

.btn-outline-primary:active:not(:disabled):not(.disabled),
.btn-primary:hover,
.dropdown-item:active,
.nav-pills .show > .nav-link,
a.bg-primary:focus
{background-color:'.hsla($color).'!important}

.badge-primary,
.btn-outline-primary:hover,
.btn-primary,
.nav-pills .nav-link.active,
.navbar-dark.bg-primary,
.page-item.active .page-link,
.progress-bar.bg-primary,
.title
{background-color:'.hsla($color, 0, 0, .9).'!important}

.breadcrumb,
.card-header,
.custom-file-label:after,
.dropdown-item:hover,
.input-group-text,
.jumbotron,
.list-group-item-action:focus,
.list-group-item-action:focus,
.list-group-item-action:hover,
.list-group-item.active,
.page-link:hover,
.progress,
.table-active, .table-active > td, .table-active > th,
a.bg-light:focus,
a.bg-light:hover,
a.bg-primary:hover,
button.bg-light:focus,
button.bg-light:hover
{background-color:'.hsla($color, -20, -15, .1).'!important}

.bg-light,
.card-footer
{background-color:'.hsla($color, 0, 30, .1).'!important}

.border-primary,
.list-group-item.active,
.page-link,
.page-item.active .page-link,
.btn-outline-primary,
.btn-outline-primary:hover,
.btn-primary,
.btn-primary:active:not(:disabled):not(.disabled)
{border-color:'.hsla($color).'!important}

.list-group-item,
.card,
.form-control,
.card-header,
.table td,
.table th,
.nav-tabs,
.nav-tabs .nav-item.show .nav-link,
.img-thumbnail,
.card-footer,
.list-group-item.active,
.input-group-text,
.page-link,
.btn-outline-primary,
.btn-primary:hover,
.page-link,
.custom-select,
.custom-file-label,
.custom-file-label:after
{border-color:'.hsla($color, -10, -15, .3).'!important}

.nav-tabs
{border-bottom:1px solid '.hsla($color, '', '', .3).'!important}

.nav-tabs .nav-link.active
{border-color:'.hsla($color, -5, 5, .2).' '.hsla($color, '', '', .2).' white!important}

.nav-tabs .nav-link:hover
{border-color:'.hsla($color, '', '', .1).'}

.page-link:focus,
.form-control:focus,
.custom-select:focus,
.btn-primary:focus,
.btn-primary:active:focus:not(:disabled):not(.disabled),
.btn-outline-primary:focus,
.btn-outline-primary:active:focus:not(:disabled):not(.disabled),
.btn-primary.dropdown-toggle:focus,
.btn-primary:active:not(:disabled):not(.disabled), .show > .btn-primary.dropdown-toggle
{box-shadow: 0 0 0 .2rem '.hsla($color, 5, 5, .2).'!important}


::selection, .highlight, .comment:target{background-color:'.hsla($color, '', '', .2).';color:'.hsla($color).'}
::-moz-selection, .highlight, .comment:target{background-color:'.hsla($color, '', '', .2).';color:'.hsla($color).'}

';
if (color2class($color) === 'white')
	echo '
.navbar-dark .navbar-brand,
.navbar-dark .navbar-nav .active > .nav-link,
.navbar-dark .navbar-nav .nav-link.active,
.navbar-dark .navbar-nav .nav-link.show,
.navbar-dark .navbar-nav .show > .nav-link,
.list-group-item.active
{color:'.hsla($color).'!important}

.navbar-dark .navbar-nav .nav-link
{color:'.hsla($color, -2, -3, .8).'!important}

.navbar-dark .navbar-nav .nav-link:hover
{color:'.hsla($color, 3, 3, .5).'!important}

.navbar-dark .navbar-toggler
{border-color:'.hsla($color).';background-color:'.hsla($color).'}

.navbar-dark .navbar-toggler:hover
{opacity:0.8}

.breadcrumb,
.col-lg-3 .card-header.bg-light,
.col-lg-3 .list-group-item.active,
.col-lg-3 .list-group-item.bg-light,
.navbar-dark.bg-primary,
.title
{background-color:white!important}

.col-lg-3 .list-group-item,
.col-lg-3 .card
{border-left:0;border-right:0;border-radius:0}

.col-lg-3 .list-group-item:first-child,
.col-lg-3 .card
{border-top:0}

body,
.form-control,
.input-group-text,
.custom-file-label:after,
.list-group-item-action,
.list-group-item.active,
.nav-tabs .nav-link.active,
.nav-tabs .nav-item.show .nav-link
{color:'.hsla($color, 0, -10, .9).'}

';
else
	echo '
.title{color:white}

';
