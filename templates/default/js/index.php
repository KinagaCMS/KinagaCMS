<?php
header('Content-Type: application/javascript');
header('Last-Modified: '. $last_modified = gmdate('D, d M Y H:i:s T', filemtime(__FILE__)));

if (filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE') === $last_modified) header('HTTP/1.1 304 Not Modified');

echo
file_get_contents('jquery.min.js'),
file_get_contents('popper.min.js'),PHP_EOL,
file_get_contents('bootstrap.min.js'),PHP_EOL,
file_get_contents('jquery.fancybox.min.js');?>
$('a[href="#TOP"]').click(function(){$('body, html').animate({scrollTop:0},100);return false});let scroll_delay=0;$(window).on('scroll',function(){clearTimeout(scroll_delay);scroll_delay=setTimeout(function(){if($(this).scrollTop()>200){$('#page-top').slideDown()}else{$('#page-top').slideUp()}},400)});$('.nav-tabs a').click(function(e){e.preventDefault();$(this).tab('show')});$('[data-toggle="tooltip"]').tooltip();$('[data-toggle="popover"]').popover();