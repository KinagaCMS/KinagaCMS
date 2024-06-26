<?php
if (__FILE__ !== implode(get_included_files())) return
[
	'addthis' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#ff6550" href="https://www.addthis.com/bookmark.php?url='. $u. '">AddThis</a>',
	'blogger' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#f57d00" href="https://www.blogger.com/blog-this.g?u='. $u. '&amp;n='. $t.'">Blogger</a>' ,
	'buffer' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#323b43" href="https://buffer.com/add?text='. $t. '&amp;url='. $u. '">Buffer</a>',
	'digg' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#000000" href="https://digg.com/submit?url='. $u. '&amp;title='. $t. '">digg</a>',
	'douban' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#20982d" href="https://www.douban.com/recommend/?url='. $u. '&amp;title='. $t. '">Douban</a>',
	'evernote' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#2dbe60" href="https://www.evernote.com/clip.action?url='. $u. '&amp;title='. $t. '">Evernote</a>',
	'facebook' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#3b5998" href="https://www.facebook.com/sharer.php?u='. $u. '">Facebook</a>',
	'flipboard' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#e12828" href="https://share.flipboard.com/bookmarklet/popout?v=2&amp;title='. $t. '&amp;url='. $u. '">Flipboard</a>',
	'pocket' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#ef4056" href="https://getpocket.com/edit?url='. $u. '">Pocket</a>',
	'hatena' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#00a4de" href="https://b.hatena.ne.jp/entry/'. $u. '">B!</a>',
	'line' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#00c300" href="https://social-plugins.line.me/lineit/share?url='. $u. '">Line</a>',
	'linkedin' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#0077b5" href="https://www.linkedin.com/shareArticle?mini=true&amp;url='. $u. '">LinkedIn</a>',
	'livejournal' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#00b0ea" href="https://www.livejournal.com/update.bml?subject='. $t. '&amp;event='. $u. '">LiveJournal</a>',
	'ok.ru' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#ee8208" href="https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&amp;st.shareUrl='. $u. '">OK</a>',
	'pinterest' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#bd081c" href="https://pinterest.com/pin/create/button/?url='. $u . '">Pinterest</a>',
	'qzone' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#3086f8" href="https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='. $u. '">Qzone</a>',
	'reddit' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#ff4500" href="https://reddit.com/submit?url='. $u. '&amp;title='. $t. '">Reddit</a>',
	'surfingbird' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:#596e7e;background:#f2f3f5" href="https://surfingbird.ru/share?url='. $u. '&amp;title='. $t. '">Surfingbird</a>',
	'tumblr' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#35465c" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl='. $u. '&amp;title='. $t. '">Tumblr</a>',
	'twitter' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#000" href="https://twitter.com/intent/tweet?url='. $u. '&amp;text='. $t. '">X</a>',
	'vk' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#45668e" href="https://vk.com/share.php?url='. $u. '&amp;title='. $t. '">VK</a>',
	'weibo' => '<a target="_blank" rel="noopener noreferrer" class="m-1 social btn btn-lg" style="color:white;background:#ec4039" href="https://service.weibo.com/share/share.php?url='. $u. '&amp;appkey=&amp;title='. $t. '&amp;pic=&amp;ralateUid=">Weibo</a>',
];
