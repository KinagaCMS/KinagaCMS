# ![](https://avatars3.githubusercontent.com/u/26238188?v=3&s=45) Kinaga
[![Software License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=plastic)](LICENSE)
[![CII Best Practices](https://bestpractices.coreinfrastructure.org/projects/775/badge)](https://bestpractices.coreinfrastructure.org/projects/775)
![Made in Japan](https://img.shields.io/badge/Made%20in-Japan-red.svg?colorA=ff0000&colorB=ff0000&style=plastic)

Kinaga is **the simplest CMS**. It has NoDB and NoAdmin, so use **NFS** and your favorite **Text Editor**, just like usual desktop work. Easy-to-use and easy-code, just like old school scripts.

![](https://cloud.githubusercontent.com/assets/26238188/23639374/a7f382e4-032a-11e7-81ed-86beb7cdafc0.gif)


# Requirements

- PHP 5.6 or higher
- Apache 2.4 or higher


# Installation

To install kinaga, copy the contents to a web accessible folder.


# Setting Up

1. /includes/config.php

	You can set your language, encoding, and template.

2. /includes/lang/YOUR_LANGUAGE.php

	Edit or create your language file (ex: en.php).

	You can also set Site Name and description.


# Quick Start

1. Create a new folder in the *contents* as a **category**.

2. Create a new folder in the *category* as a **article title**.

3. Create a *index.html* in the **article folder** and publish your article.

4. See the directory tree.



		contents
		│
		├── CATEGORY NAME
		│			│
		│			├── ARTICLE TITLE
		│			│			│
		│			│			├── comments ( Provide a contact form. Not required )
		│			│			│
		│			│			├── counter.txt ( As a popular article. Not required )
		│			│			│
		│			│			├── images ( or background-images. Not required )
		│			│			│
		│			│			└── index.html ( Publish your article here )
		│			│
		│			└── index.html ( Category Subtitle. Not required )
		│
		├── ASIDE PAGE.html ( Belong to "Informations" of the Sidebox. Not required )
		│
		└── index.html ( Not required )


# Contributing

Any forms of contribution are welcome: translation into other languages, design templates, bug fixes, or simple suggestions.



# Licenses

- Kinaga is licensed under GPL v3 or higher. See the LICENSE file for details.

- [Bootstrap](http://getbootstrap.com/), [jQuery](http://jquery.com/) and [Magnific Popup](http://dimsemenov.com/plugins/magnific-popup/) are released under the MIT license.


