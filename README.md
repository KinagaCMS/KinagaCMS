<p align="right"><a href="README.ja.md">日本語版 README はこちら</a></p>

# ![](https://avatars3.githubusercontent.com/u/26238188?v=3&s=45) Kinaga

Kinaga is **the simplest CMS** that has NoDB and NoAdmin, so use **NFS** and your favorite **Text Editor**, just like usual desktop work. Easy-to-use and easy-code, just like old school scripts.

![](https://cloud.githubusercontent.com/assets/26238188/23820102/835ae602-0654-11e7-9d54-fbdf9f3f369f.png)


# Requirements

- PHP 5.5 or higher
- Apache 2.2 or higher
- .htaccess support


# Installation

	git clone https://github.com/KinagaCMS/KinagaCMS.git


# Setting Up

1.  /includes/config.php

	You can set your language, encoding, and template.

2.  /includes/lang/YOUR_LANGUAGE.php

	Edit or create your language file (ex: en.php).

	You can also set Site Name and description.


# Quick Start

![](https://cloud.githubusercontent.com/assets/26238188/23639374/a7f382e4-032a-11e7-81ed-86beb7cdafc0.gif)

1.  Connect with NFS or [WinSCP](https://winscp.net/) to your server.

2.  Create a new folder in the *contents* as a **category**.

3.  Create a new folder in the *category* as a **article title**.

4.  Create a *index.html* in the **article folder** and publish your article.


		contents
		│
		├── CATEGORY NAME
		│	│
		│	├── ARTICLE TITLE
		│	│	│
		│	│	├── comments ( Provide a contact form. Not required )
		│	│	│
		│	│	├── counter.txt ( As a popular article. Not required )
		│	│	│
		│	│	├── images ( or background-images. Not required )
		│	│	│
		│	│	└── index.html ( Publish your article here )
		│	│
		│	└── index.html ( Category Subtitle. Not required )
		│
		├── ASIDE PAGE.html ( Belong to "Informations" of the Sidebox. Not required )
		│
		└── index.html ( Not required )


# Contributing

Any forms of contribution are welcome: translation into other languages, design templates, bug fixes, or simple suggestions.



# Licenses

- Kinaga is licensed under GPL v3 or higher. See the LICENSE file for details.

- [Bootstrap](http://getbootstrap.com/), [jQuery](http://jquery.com/) and [Magnific Popup](http://dimsemenov.com/plugins/magnific-popup/) are released under the MIT license.


