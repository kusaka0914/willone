FROM composer:2.5 as composer_stage

WORKDIR /app
RUN docker-php-ext-install exif
COPY composer.json composer.lock ./

RUN composer install --no-scripts --no-autoloader --no-dev

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
  && pecl install memcached mcrypt\
  && docker-php-ext-install zip pdo_mysql mysqli exif bcmath\
  && docker-php-ext-enable memcached mcrypt\
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  && a2enmod rewrite \
  && a2enmod headers

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer
COPY --from=composer_stage /app/vendor/ ./vendor/
