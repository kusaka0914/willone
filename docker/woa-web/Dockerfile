FROM composer:2.5 as composer_stage

RUN wget https://github.com/k0kubun/sqldef/releases/download/v0.16.6/mysqldef_linux_386.tar.gz \
    && tar zxvf mysqldef_linux_386.tar.gz -C /tmp/

FROM php:8.2.1-apache

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV DEBCONF_NOWARNINGS yes

# TimeZoneの設定
RUN cp /usr/share/zoneinfo/Japan /etc/localtime

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    gettext \
    libmcrypt-dev \
    libmemcached-dev \
    libpq-dev \
    libzip-dev \
    lynx \
    mariadb-client \
    unzip \
    zlib1g-dev \
    wget \
  && pecl install memcached mcrypt\
  && docker-php-ext-install zip pdo_mysql mysqli exif bcmath opcache\
  && docker-php-ext-enable memcached mcrypt\
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  && a2enmod rewrite \
  && a2enmod headers

RUN wget https://github.com/DataDog/dd-trace-php/releases/download/1.1.0/datadog-setup.php
RUN php datadog-setup.php --php-bin=all --enable-profiling
RUN rm datadog-setup.php

COPY ./docker/woa-web/usr/local/etc/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/woa-web/etc/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./docker/woa-web/etc/apache2/sites-enabled/001-security.conf /etc/apache2/sites-enabled/001-security.conf
COPY ./docker/woa-web/etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer
COPY --from=composer_stage /tmp/mysqldef /usr/bin/mysqldef
COPY --chown=www-data:www-data . /var/www/html
RUN \cp -pf /var/www/html/public/.htaccess.prd /var/www/html/public/.htaccess

RUN composer install --no-dev

WORKDIR /var/www/html
