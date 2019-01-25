FROM php:5.6-apache
RUN docker-php-ext-install -j$(nproc) mysqli && a2enmod rewrite