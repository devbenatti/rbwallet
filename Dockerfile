FROM php

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install bcmath
