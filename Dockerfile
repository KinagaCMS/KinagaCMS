FROM php:7-apache
RUN apt-get update
RUN a2enmod rewrite
RUN apt install -y locales locales-all libpng-dev
RUN docker-php-ext-install exif gd mbstring
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
