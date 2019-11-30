FROM php:apache
# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# CUSTOM CONFIG
RUN a2enmod rewrite