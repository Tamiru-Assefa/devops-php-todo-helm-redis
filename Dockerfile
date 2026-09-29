# Base PHP + Apache image
FROM php:8.2-apache

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install PHP Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Copy PHP application
COPY ./Apps /var/www/html

# Expose HTTP
EXPOSE 80