FROM composer:latest as composer
FROM php:8-fpm

WORKDIR /usr/src/api

# copy composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# # install packages
RUN apt-get update
RUN apt-get install -y libpq-dev
RUN apt-get install -y git

# # install php extensions and libs
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install pdo pgsql pdo_pgsql
RUN pecl install xdebug
RUN pecl install openswoole-22.0.0

# enable php extensions
RUN docker-php-ext-enable openswoole
RUN docker-php-ext-enable xdebug

COPY ./api .

RUN composer install --no-cache --ignore-platform-reqs