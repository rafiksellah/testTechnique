FROM php:8.3-fpm-alpine

# Installer les extensions PHP nécessaires pour MariaDB
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Installer OpCache
RUN docker-php-ext-install opcache

# Ajouter le fichier de configuration OpCache
ADD opcache.ini $PHP_INI_DIR/conf.d/