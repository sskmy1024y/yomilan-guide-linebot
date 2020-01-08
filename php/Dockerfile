FROM php:7.2-fpm

RUN apt-get update \
  && apt-get install -y wget zlib1g-dev mariadb-client \
  && docker-php-ext-install zip pdo_mysql

COPY php.ini /usr/local/etc/php/
COPY composer-init.sh ./

#Composer install
RUN chmod -R 755 ./composer-init.sh
RUN ./composer-init.sh
RUN mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

ENV COMPOSER_HOME /composer

ENV PATH $PATH:/composer/vendor/bin

WORKDIR /var/www

RUN composer global require "laravel/installer"
