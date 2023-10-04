FROM composer:latest as composer
FROM node:14
FROM php:8-fpm

WORKDIR /usr/src/api

# install node and npm
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

# copy composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# install packages
RUN apt-get update
RUN apt-get install -y libpq-dev
RUN apt-get install -y git

# install php extensions and libs
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install pdo pgsql pdo_pgsql
RUN pecl install xdebug
RUN pecl install openswoole-22.0.0

# enable php extensions
RUN docker-php-ext-enable openswoole
RUN docker-php-ext-enable xdebug

# install yarn
RUN npm install -g yarn

COPY ./api .

# install dependencies
RUN composer install --no-dev --no-cache --ignore-platform-reqs