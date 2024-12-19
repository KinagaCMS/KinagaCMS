FROM php:8-apache
RUN apt-get update
RUN a2enmod expires headers rewrite
RUN apt-get install -y --no-install-recommends locales locales-all libonig-dev libfreetype6-dev libjpeg-dev libpng-dev libc-client-dev libkrb5-dev msmtp libmagickwand-dev git
RUN pecl install imap imagick
RUN docker-php-ext-install intl exif gd
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-enable imap imagick
RUN PHP_OPENSSL=yes
RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data
RUN cd /var/www/html && git clone https://github.com/KinagaCMS/KinagaCMS.git .
RUN docker-php-source delete
RUN apt-get clean -y
RUN rm -rf /var/lib/apt/lists/*
