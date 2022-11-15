# Argumentos da Imagem
## Versão da Imagem Docker PHP
ARG PHP_VERSION=8.1-fpm
FROM php:${PHP_VERSION}

## Diretório da aplicação
ARG APP_DIR=/var/www/skeleton

RUN apt-get update
RUN apt-get install -y --no-install-recommends \
    apt-utils
### apt-utils é um extensão de recursos do gerenciador de pacotes APT

# dependências recomendadas de desenvolvido para ambiente linux
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libpng-dev \
    libpq-dev \
    libxml2-dev

# Extensões do php
RUN docker-php-ext-install pdo pdo_pgsql pgsql session xml json mbstring zip iconv simplexml pcntl gd fileinfo

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR $APP_DIR
RUN cd $APP_DIR

RUN chmod 755 -R *
RUN chown -R www-data: *
