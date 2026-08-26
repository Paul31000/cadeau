#debian 11-slim
FROM php:8.2-apache

WORKDIR /var/www/html


RUN apt-get update \
    && apt-get install -y \
    #    libicu-dev \
        libpq-dev \
    #    libzip-dev \
        unzip \
        git \
        cron \
        nano \
        supervisor

# Extensions PHP
RUN docker-php-ext-install \
#    intl \
    pdo \
    pdo_mysql 
#    zip 
#    pdo_mysql \
#    opcache

# Install XDEBUG
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Installer symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Installer Composer CLI
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs


# Alias machine
RUN echo 'alias console="php /var/www/html/bin/console"' >> ~/.bashrc
RUN echo 'alias phpstan="/var/www/html/vendor/phpstan/phpstan/phpstan"' >> ~/.bashrc
RUN echo 'alias dsu="/var/www/html/bin/console d:s:u --force --complete"' >> ~/.bashrc
RUN echo 'alias composer="php ~/composer.phar"' >> ~/.bashrc
RUN echo "umask 0000" >> /root/.bashrc

ARG PHP_XDEBUG_MODE
ARG PHP_XDEBUG_CLIENT_HOST
ARG PHP_XDEBUG_CLIENT_PORT

RUN echo "xdebug.mode=${PHP_XDEBUG_MODE}" >> /usr/local/etc/php/conf.d/xdebug.ini \
 && echo "xdebug.client_host=${PHP_XDEBUG_CLIENT_HOST}" >> /usr/local/etc/php/conf.d/xdebug.ini \
 && echo "xdebug.client_port=${PHP_XDEBUG_CLIENT_PORT}" >> /usr/local/etc/php/conf.d/xdebug.ini \
 && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini

# comme on ne fait pas tourner le serveur de dev de symfony, c'est une config apache classique.
RUN a2enmod rewrite
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80