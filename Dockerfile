# Immagine base con PHP 8.2 e Apache
FROM php:8.2-apache

# Abilita estensioni necessarie
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Abilita mod_rewrite di Apache
RUN a2enmod rewrite

# Installa unzip e git (necessari a Composer)
RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*

# Copia Composer dall'immagine ufficiale
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cartella di lavoro
WORKDIR /var/www/html

# Copia composer.json e composer.lock da src2
COPY ./src2/composer.json ./src2/composer.lock* /var/www/html/

# Installa dipendenze (PHPMailer, OAuth2, Google API, dotenv)
RUN composer install --no-dev --optimize-autoloader || true

# Copia tutto il codice PHP da src2
COPY ./src2 /var/www/html
