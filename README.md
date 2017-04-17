<p align="right"><a href="README.ja.md">【日本語版 README はこちら】</a></p>

# ![](https://avatars3.githubusercontent.com/u/26238188?v=3&s=45) Kinaga

Kinaga is a Directory Management CMS that has NoDB and NoAdmin, so use **NFS** and **Text Editor**, just like usual desktop work. 


<small>Not translated yet</small>


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

1.  Connect with NFS or [WinSCP](https://winscp.net/) to your server.

2.  Create a new folder in the *contents* as a **category**.

3.  Create a new folder in the *category* as a **article title**.

4.  Create a *index.html* in the **article folder** and publish your article with text editor ( [ReText](https://github.com/retext-project/retext), [Pluma](https://github.com/mate-desktop/pluma), [Geany](https://github.com/geany/geany/) ).

5. Create folders and texts as follows, each function can be added.

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
		└── index.html ( Top page. Not required )


# Contributing

Any forms of contribution are welcome: translation into other languages, bug fixes.



# Licenses

- Kinaga is licensed under GPL v3 or higher. See the [LICENSE](LICENSE) file for details.

- [Bootstrap](http://getbootstrap.com/), [jQuery](http://jquery.com/) and [Magnific Popup](http://dimsemenov.com/plugins/magnific-popup/) are released under the MIT license.
