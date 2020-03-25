<?php
$categ_nav_class = ' class="list-inline-item"';
$article_nav_wrapper_class = 'p-4';
$article_nav_next_href_class = '';
$article_nav_prev_href_class = '';
$article_nav_xaquo_class = 'd-none';
$article_nav_title_class = 'h6';
$article_nav_content_class = 'd-block mb-4 mx-3';

$sidebox_wrapper_class[0] = 'p-5';
$sidebox_wrapper_class[1] = 'border-0';
$sidebox_wrapper_class[2] = 'mb-5';
foreach (range(0, 3) as $i) $sidebox_title_class[$i] = 'h5';
$sidebox_title_class[3] .= ' bg-danger p-3 text-white';
$sidebox_title_class[4] = 'h2 navbar navbar-dark';
$sidebox_content_class[0] = $sidebox_content_class[2] = $sidebox_content_class[3] = $sidebox_content_class[4] = 'd-block p-2 border-0';
$sidebox_content_class[2] .= ' wrap';
