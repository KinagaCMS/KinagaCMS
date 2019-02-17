<?php
$last_modified = gmdate('D, d M Y H:i:s T', filemtime(__FILE__));

header('Content-Type: application/javascript');
header('Last-Modified: '. $last_modified);

if (filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE') === $last_modified) header('HTTP/1.1 304 Not Modified');

echo
file_get_contents('jquery.min.js'),
file_get_contents('popper.min.js'),PHP_EOL,
file_get_contents('bootstrap.min.js'),PHP_EOL,
file_get_contents('jquery.magnific-popup.min.js');?>
$('a[href="#TOP"]').click(function(){$('body, html').animate({scrollTop:0},100);return false});var scroll_delay=0;$(window).on('scroll',function(){clearTimeout(scroll_delay);scroll_delay=setTimeout(function(){if($(this).scrollTop()>200){$('#page-top').slideDown()}else{$('#page-top').slideUp()}},400)});$('.gallery').each(function(){$(this).magnificPopup({delegate:'a',type:'image',gallery:{enabled:true,preload:[1,1]}})});$('.expand').magnificPopup({type:'image'});$('.nav-tabs a').click(function(e){e.preventDefault();$(this).tab('show')});$('[data-toggle="tooltip"]').tooltip();$('[data-toggle="popover"]').popover();function toc(){var num=1,toc='',toclv=lv=0;$('.article :header').each(function(){this.id='toc'+num;tag=this.nodeName.toLowerCase();num++;if(tag==='h1')lv=1;else if(tag==='h2')lv=2;else if(tag==='h3')lv=3;else if(tag==='h4')lv=4;else if(tag==='h5')lv=5;else if(tag==='h6')lv=6;while(toclv<lv){toc+='<ul class="list-unstyled ml-2 mb-0">';toclv++}while(toclv>lv){toc+='<\/ul>';toclv--}toc+='<li><a class="p-1 border-0 d-block list-group-item list-group-item-action" href="#'+this.id+'">'+$(this).text()+'<\/a><\/li>'});while(toclv>0){toc+='<\/ul>';toclv--}$('#toc').fadeIn('slow');$('#toctoggle').html(toc);$("body").scrollspy({target:"#toc"})}