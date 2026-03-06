FROM php:8.5-apache-bookworm

# Update and install necessary packages
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    locales locales-all \
    libonig-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    libc-client-dev \
    libkrb5-dev \
    msmtp \
    libmagickwand-dev && \
    apt-get clean -y && \
    rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod expires headers rewrite
RUN a2dismod status

# Install PECL extensions
RUN pecl install imap imagick

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install intl exif gd && \
    docker-php-ext-enable imap imagick

# Ensure PHP uses OpenSSL
ENV PHP_OPENSSL=yes

# Update www-data user and group
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Apache basic settings
RUN printf "ServerName localhost\n" >> /etc/apache2/apache2.conf
RUN printf "ErrorLog /tmp/error.log\n" >> /etc/apache2/apache2.conf
RUN printf "DirectoryIndex index.html index.php\n" > /etc/apache2/mods-available/dir.conf
RUN printf "ErrorLog /tmp/error.log\n#CustomLog /tmp/access.log combined\n" > /etc/apache2/sites-available/000-default.conf
RUN printf "ServerTokens Prod\nServerSignature Off\nTraceEnable Off\n" > /etc/apache2/conf-available/security.conf
RUN mkdir -p /etc/ImageMagick-6/policy.d
RUN printf "<policy domain=\"coder\" rights=\"none\" pattern=\"EPHEMERAL\" />\n<policy domain=\"coder\" rights=\"none\" pattern=\"URL\" />\n<policy domain=\"coder\" rights=\"none\" pattern=\"HTTPS\" />\n<policy domain=\"coder\" rights=\"none\" pattern=\"MVG\" />\n" > /etc/ImageMagick-6/policy.d/disable-dangerous-coders.xml

# Clean up source files
RUN docker-php-source delete

# Remove unnecessary packages
RUN apt-get clean -y && \
    rm -rf /var/lib/apt/lists/*

