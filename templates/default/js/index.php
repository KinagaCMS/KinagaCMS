<?php
header('Last-Modified: '. $last_modified = gmdate('D, d M Y H:i:s T', getlastmod()));
if ($last_modified === filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE')) header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL'). ' 304 Not Modified');
header('Content-Type: text/javascript');
$fancybox = file_get_contents('fancybox.umd.js');
$fancybox = str_replace('.window', '._window', $fancybox);
echo file_get_contents('popper.min.js'), file_get_contents('bootstrap.min.js'), $fancybox, 'let side=document.getElementById("side");if("#login"===location.hash)new bootstrap.Offcanvas(side).show();window.addEventListener("hashchange",()=>{if(window.location.hash.substring(1)){window.scrollTo(0,document.getElementById(window.location.hash.substring(1)).offsetTop-document.getElementById("header").offsetHeight)}})';