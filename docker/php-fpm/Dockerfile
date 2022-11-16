# Argumentos da Imagem
## Versão da Imagem Docker PHP
ARG PHP_VERSION=8.1-fpm
FROM php:${PHP_VERSION}

## Diretório da aplicação
ARG APP_DIR=/var/www

RUN apt-get update
RUN apt-get install -y --no-install-recommends \
    apt-utils
### apt-utils é um extensão de recursos do gerenciador de pacotes APT

# dependências recomendadas de desenvolvido para ambiente linux
RUN apt-get update && apt-get install -y \
    build-essential \
    zlib1g-dev \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    wget

# Extensões do php
RUN docker-php-ext-install pgsql pdo_pgsql zip pcntl bcmath intl opcache

# Mais extensões apcu, xdebug
RUN pecl install \
        xdebug \
    && docker-php-ext-enable \
        xdebug

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR $APP_DIR
RUN cd $APP_DIR
