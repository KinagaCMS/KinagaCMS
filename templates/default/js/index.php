<?php
header('Last-Modified: '. $last_modified = gmdate('D, d M Y H:i:s T', getlastmod()));
if ($last_modified === filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE')) header('HTTP/1.1 304 Not Modified');
header('Content-Type: text/javascript');
echo file_get_contents('jquery.min.js'), file_get_contents('popper.min.js'), file_get_contents('bootstrap.min.js'), file_get_contents('jquery.fancybox.min.js')?>
$('a[href="#TOP"]').click(function(){$('body, html').animate({scrollTop:0},100);return false});let scroll_delay=0;$(window).on('scroll',function(){clearTimeout(scroll_delay);scroll_delay=setTimeout(function(){if($(this).scrollTop()>200){$('#page-top').slideDown()}else{$('#page-top').slideUp()}},400)});$('.nav-tabs a').click(function(e){e.preventDefault();$(this).tab('show')});$('[data-toggle="tooltip"]').tooltip();$('[data-toggle="popover"]').popover();$('img').onerror=null;