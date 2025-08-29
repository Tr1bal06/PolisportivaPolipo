# Immagine base con PHP 8.2 e Apache
FROM php:8.2-apache

# Abilita estensioni necessarie
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Abilita mod_rewrite di Apache (utile per URL friendly)
RUN a2enmod rewrite

# Cartella di lavoro
WORKDIR /var/www/html
