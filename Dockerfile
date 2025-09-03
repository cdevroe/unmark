FROM php:7.4-apache
RUN docker-php-ext-install -j$(nproc) mysqli pdo pdo_mysql && a2enmod rewrite