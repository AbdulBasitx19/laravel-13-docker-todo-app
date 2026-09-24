# 1. php or apache wala linux OS pull kiya / pehli line base OS/environment set krti hy
FROM php:8.3-apache

# system packages: container mai packages install or update kiay/ curl : internet sy file download krnay k liay /clean downloads list ko delte krna
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# laravel ko mysql sy connect krnay k liay pdo extension do
RUN docker-php-ext-install pdo_mysql gd

#apache settings laravel k urls (.htaccess) readable bnanay k liay mod-rewrite enable kiya
RUN a2enmod rewrite

# composer install krna :php packages download k liay  / COPY : kisi or image sy file contaner main lanay k liay
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# container k andar hmara default folder
WORKDIR /var/www/html/

# documentroot change : apache ko btaya k website entry public/index.php sy hy
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf
