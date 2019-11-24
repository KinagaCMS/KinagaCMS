FROM php:7-apache
RUN apt-get update && a2enmod rewrite && apt-get install -y --no-install-recommends imagemagick locales locales-all libfreetype6-dev libjpeg-dev libpng-dev libc-client-dev libkrb5-dev
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install imap exif gd mbstring
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data && apt-get clean -y && rm -rf /var/lib/apt/lists/*
