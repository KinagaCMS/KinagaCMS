<?php
include '../../../includes/functions.php';
include '../../../includes/config.php';
header('Last-Modified: '. $last_modified = timestamp(__FILE__));
if ($last_modified === filter_input(INPUT_SERVER, 'HTTP_IF_MODIFIED_SINCE')) header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL'). ' 304 Not Modified');
header('Content-Type: text/javascript');
$fancybox = file_get_contents('fancybox.umd.js');
$fancybox = str_replace('.window', '._window', $fancybox);
echo file_get_contents('popper.min.js'), file_get_contents('bootstrap.min.js'), $fancybox, 'function focusLogin(){if(side.classList.contains(\'offcanvas\')){new bootstrap.Offcanvas(side).show();side.addEventListener(\'shown.bs.offcanvas\',()=>document.getElementById(\'e\').focus())}else window.onscroll=()=>{if(0===document.getElementById(\'login\').getBoundingClientRect().top)document.getElementById(\'e\').focus()}}let side=document.getElementById("side");if("#login"===location.hash)new bootstrap.Offcanvas(side).show();window.addEventListener("hashchange",()=>{if(window.location.hash.substring(1)){window.scrollTo(0,document.getElementById(window.location.hash.substring(1)).offsetTop-document.getElementById("header").offsetHeight)}});let pageTop=document.getElementById("page-top").firstChild;window.addEventListener("scroll",e=>{if(window.pageYOffset>=200){pageTop.setAttribute("height","36px");pageTop.setAttribute("width","36px")}else{pageTop.setAttribute("height","0px");pageTop.setAttribute("width","0px")}});pageTop.addEventListener("click",e=>{e.preventDefault();window.scroll({top:0,behavior:"smooth"})});[].slice.call(document.querySelectorAll("[data-bs-toggle=tooltip]")||[]).map(t=>{return new bootstrap.Tooltip(t)});[].slice.call(document.querySelectorAll("[data-bs-toggle=popover]")||[]).map(el=>{return new bootstrap.Popover(el)});[].slice.call(document.querySelectorAll("img")||[]).onerror=null;'.
(!$use_datasrc ? '' : 'const x=new IntersectionObserver(entries=>{entries.forEach(entry=>{if(entry.isIntersecting){const y=entry.target;if(y.dataset.src){y.src=y.dataset.src}}})}),z=document.querySelectorAll("img[data-src]");z.forEach(z=>x.observe(z));').
(!$use_wikipedia_popover ? '' : '[].slice.call(document.querySelectorAll("dfn")||[]).forEach(e=>{const l="https://"+(e.getAttribute("lang")||"'. $lang. '")+".wikipedia.org",m="/w/api.php?action=query&format=json&origin=*&prop=extracts&exintro&explaintext&redirects=1&titles="+e.textContent;document.querySelector("head").innerHTML+="<link href=\""+l+m+"\" rel=prefetch as=fetch crossorigin>";e.style.borderBottom="thin dotted";e.addEventListener("mouseover",ev=>{e.style.cursor="progress";if(navigator.onLine){fetch(l+m).then(response=>response.json()).then(data=>{let allow=bootstrap.Tooltip.Default.allowList;allow.button=["onclick"];for(let i in data.query.pages){let wp=new bootstrap.Popover(ev.target,{placement:"auto",html:true,trigger:"manual",title:(data.query.pages[i].title||"")+"<button class=btn-close aria-label=Close onclick=this.closest(\".popover\").remove()><\/button>",content:data.query.pages[i].extract||"",template:"<div class=popover><div class=\"arrow popover-arrow\"><\/div><h3 class=\"popover-header d-flex justify-content-between align-items-center\"><\/h3><div class=popover-body><\/div><small class=\"d-flex justify-content-between bg-info rounded-bottom px-2 py-1\"><a href=\"http://www.gnu.org/licenses/fdl-1.3.html\" class=link-light target=_blank title=\"GNU Free Documentation License\">GFDL<\/a><a href=\""+l+"/wiki/"+data.query.pages[i].title+"\" class=link-light target=_blank>"+l+"/wiki/"+data.query.pages[i].title+"<\/a><\/small><\/div>"});wp.show()}e.style.cursor="pointer";e.style.borderBottom="thin solid"})}});e.addEventListener("mouseout",()=>{e.style.borderBottom="thin dotted"})});');
