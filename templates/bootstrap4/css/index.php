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
#page-top{bottom:1em;position:fixed;right:2em;display:none;z-index:10}
.article{font-size:large;line-height:1.8}
.bg-danger.text-danger{background-color:#f2dede!important;color:#a94442!important}
.bg-info.text-info{background-color:#d9edf7!important;color:#31708f!important}
.bg-success.text-success{background-color:#dff0d8!important;color:#3c763d!important}
.bg-warning.text-warning{background-color:#fcf8e3!important;color:#8a6d3b!important}
.card-columns{column-count:2}
.col-lg-9{min-width:0}
.custom-file-label:after{content:"\0053c2\007167"}
.img-responsive{display:block;max-width:100%;height:auto}
.mfp-title{padding-bottom:3em}
.nowrap{white-space:normal}
.page-top:after{content:"'.$pagetop.'";position:absolute;opacity:0;right:-2em}
.page-top:hover:after{opacity:1;right:0;transition:.3s linear}
.page-top:not(:hover):after{opacity:0;transition:.3s}
.page-top{border-bottom:thin dotted;position:relative;text-decoration:none!important}
.popover{max-width:70%}
.similar-article{height:2rem}
.sticky-top{top:10px}
.title{color:white}
.wrap,.mfp-title,.popover-body{word-wrap:break-word;white-space:pre-wrap}
::-moz-selection,.highlight,.comment:target{background-color:#d9edf7;color:#31708f}
::selection,.highlight,.comment:target{background-color:#d9edf7;color:#31708f}
a:hover img,a:hover svg{opacity:0.8}
body{font-family:"Droid Sans","Yu Gothic",YuGothic,"Hiragino Sans",sans-serif;color:#555}
time:before{background:url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 7 7\'%3E%3Cpath fill=\'gray\' d=\'m3.5 0c-1.9283 0-3.5 1.5717-3.5 3.5s1.5717 3.5 3.5 3.5 3.5-1.5717 3.5-3.5-1.5717-3.5-3.5-3.5zm0 0.78125c1.5002 0 2.7188 1.2186 2.7188 2.7188s-1.2186 2.6875-2.7188 2.6875-2.6875-1.1873-2.6875-2.6875 1.1873-2.7188 2.6875-2.7188zm-0.375 0.31255v0.4062 2 0.375h0.375 1.625 0.4062v-0.7812h-0.4062-1.2188v-1.5938-0.4062h-0.7812z\'/%3E%3C/svg%3E") no-repeat;content:"";display:inline-block;height:1em;margin-right:.3em;vertical-align:middle;width:1em}
@media(max-width:767px){.card-columns{column-count:1}}
.f{background-color:rgb(59, 89, 152);border-color:rgb(59, 89, 152)}.f:hover{background-color:rgba(59, 89, 152, .9);border-color:rgba(59, 89, 152, .1)}
.g{background-color:rgb(221, 75, 57);border-color:rgb(221, 75, 57)}.g:hover{background-color:rgba(221, 75, 57, .9);border-color:rgba(221, 75, 57, .1)}
.t{background-color:rgb(85, 172, 238);border-color:rgb(85, 172, 238)}.t:hover{background-color:rgba(85, 172, 238, .9);border-color:rgba(85, 172, 238, .1)}
';

if ($use_auto_wrap === true)
	echo '
.article,.card-text
{word-wrap:break-word;white-space:pre-wrap}

.article #accordion,
.article .accordion,
.article .btn-group,
.article .card,
.article .carousel,
.article .custom-control,
.article .custom-file,
.article .form-group,
.article .form-horizontal,
.article .input-group,
.article .list-group,
.article .media,
.article .modal,
.article .navbar,
.article .row,
.article .tab-content,
.article dl,
.article fieldset,
.article li,
.article ol,
.article table,
.article ul
{white-space:normal!important}

';
else
	echo '.page-top{padding-top:2rem!important;margin-bottom:2rem!important}';

if ($color)
{
	echo '
.badge-light,
.btn-link,
.btn-outline-primary,
.custom-file-label,
.custom-select,
.form-control:focus,
.page-link,
a
{color:'.hsla($color).'}

.custom-file-label:after,
.form-control,
.input-group-text,
.list-group-item-action,
.list-group-item-primary,
.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link.active,
body
{color:'.hsla($color, -10, -10, .9).'}

.breadcrumb-item+.breadcrumb-item:before,
.text-muted
{color:'.hsla($color, -10, 3, .8).'!important}

time:before{background:url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 7 7\'%3E%3Cpath fill=\''.hsla($color, -10, 3, .8).'\' d=\'m3.5 0c-1.9283 0-3.5 1.5717-3.5 3.5s1.5717 3.5 3.5 3.5 3.5-1.5717 3.5-3.5-1.5717-3.5-3.5-3.5zm0 0.78125c1.5002 0 2.7188 1.2186 2.7188 2.7188s-1.2186 2.6875-2.7188 2.6875-2.6875-1.1873-2.6875-2.6875 1.1873-2.7188 2.6875-2.7188zm-0.375 0.31255v0.4062 2 0.375h0.375 1.625 0.4062v-0.7812h-0.4062-1.2188v-1.5938-0.4062h-0.7812z\'/%3E%3C/svg%3E") no-repeat;content:"";display:inline-block;height:1em;margin-right:.3em;vertical-align:middle;width:1em}

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
{color:'.hsla($color, 0, 0, .6).'}

.nav-link.disabled
{color:'.hsla($color, -5, -5, .5).'}

.page-item.active .page-link,
.btn-outline-primary:hover
{color:white!important}

.badge-primary,
.bg-primary,
.btn-outline-primary:active:not(:disabled):not(.disabled),
.btn-outline-primary:hover,
.btn-primary,
.dropdown-item:active,
.nav-pills .nav-link.active,
.nav-pills .show > .nav-link,
.page-item.active .page-link,
.progress-bar.bg-primary,
.title
{background-color:'.hsla($color).'!important}

.btn-primary:hover,
.list-group-item.active
{background-color:'.hsla($color, -5, -5).'!important}

.border-primary,
.btn-outline-primary,
.btn-outline-primary:hover,
.btn-primary,
.btn-primary:active:not(:disabled):not(.disabled),
.list-group-item-primary,
.page-item.active .page-link,
.page-link
{border-color:'.hsla($color).'!important}

.btn-outline-primary,
.btn-primary:hover,
.card,
.card-footer,
.card-header,
.custom-file-label,
.custom-file-label:after,
.custom-select,
.form-control,
.img-thumbnail,
.input-group-text,
.list-group-item,
.list-group-item-primary,
.nav-tabs .nav-item.show .nav-link,
.nav-tabs,
.page-link,
.table td,
.table th
{border-color:'.hsla($color, -10, -15, .3).'!important}

.nav-tabs
{border-bottom:1px solid '.hsla($color, 0, 0, .3).'!important}

.nav-tabs .nav-link.active
{border-color:'.hsla($color, -5, 5, .2).' '.hsla($color, 0, 0, .2).' white!important}

.nav-tabs .nav-link:hover
{border-color:'.hsla($color, 0, 0, .1).'}

.btn-outline-primary:active:focus:not(:disabled):not(.disabled),
.btn-outline-primary:focus,
.btn-primary.dropdown-toggle:focus,
.btn-primary:active:focus:not(:disabled):not(.disabled),
.btn-primary:active:not(:disabled):not(.disabled), .show > .btn-primary.dropdown-toggle,
.btn-primary:focus,
.custom-select:focus,
.form-control:focus,
.page-link:focus
{box-shadow: 0 0 0 .2rem '.hsla($color, 5, 5, .2).'!important}

::selection, .highlight, .comment:target{background-color:'.hsla($color, 0, 0, .2).';color:'.hsla($color).'}
::-moz-selection, .highlight, .comment:target{background-color:'.hsla($color, 0, 0, .2).';color:'.hsla($color).'}
';

if (color2class($color) === 'dark')
	echo '
.breadcrumb,
.bg-light,
.card-footer,
.list-group-item-action:focus,
.list-group-item-primary,
.progress
{background-color:'.hsla($color, -10, 88).'!important}

.card-header,
.custom-file-label:after,
.dropdown-item:hover,
.input-group-text,
.jumbotron,
.list-group-item-action:hover,
.page-link:hover,
.table-primary,
a.bg-light:focus,
a.bg-light:hover,
a.bg-primary:focus,
a.bg-primary:hover
{background-color:'.hsla($color, -40, 80).'!important}

';
else
	echo '
.breadcrumb,
.card-footer,
.list-group-item-action:focus,
.list-group-item-primary,
.progress
{background-color:'.hsla($color, -5, 55).'!important}

.bg-light
{background-color:'.hsla($color, 0, 0, .03).'!important}

.card-header,
.custom-file-label:after,
.dropdown-item:hover,
.input-group-text,
.jumbotron,
.table-primary, .table-primary > td, .table-primary > th,
.list-group-item-action:hover,
.page-link:hover,
a.bg-primary:focus,
a.bg-primary:hover
{background-color:'.hsla($color, -15, 55).'!important}

a.bg-light:focus,
a.bg-light:hover
{background-color:'.hsla($color, 0, 0, .05).'!important}
';

if (color2class($color) === 'white')
	echo '
.list-group-item-primary,
.navbar-dark .navbar-brand,
.navbar-dark .navbar-nav .active > .nav-link,
.navbar-dark .navbar-nav .nav-link.active,
.navbar-dark .navbar-nav .nav-link.show,
.navbar-dark .navbar-nav .show > .nav-link,
.title
{color:'.hsla($color).'!important}

.navbar-dark .navbar-nav .nav-link
{color:'.hsla($color, -2, -3, .8).'!important}

.navbar-dark .navbar-nav .nav-link:hover
{color:'.hsla($color, 3, 3, .5).'!important}

.navbar-dark .navbar-toggler
{border-color:'.hsla($color).'}

.navbar-dark .navbar-toggler:hover
{opacity:0.5}

.navbar-toggler-icon
{background-image:url("data:image/svg+xml;charset=utf8,%3Csvg viewBox=\'0 0 30 30\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath stroke=\''.hsla($color).'\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-miterlimit=\'10\' d=\'M4 7h22M4 15h22M4 23h22\'/%3E%3C/svg%3E")!important}

.breadcrumb,
.card-header,
.list-group-item-primary,
.navbar-dark.bg-primary,
.table-primary, .table-primary > td, .table-primary > th,
.title
{background-color:white!important}

.col-lg-3 .card,
.col-lg-3 .list-group-item
{border-left:0;border-right:0;border-radius:0}

.col-lg-3 .card,
.col-lg-3 .list-group-item:first-child
{border-top:0}

.custom-file-label:after,
.form-control,
.input-group-text,
.list-group-item-action,
.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link.active,
body
{color:'.hsla($color, 0, -10, .9).'}

';
}
