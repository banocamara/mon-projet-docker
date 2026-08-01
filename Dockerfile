FROM php:8.2-apache

# Installer l'extension pdo_mysql pour que PHP puisse parler à MySQL
RUN docker-php-ext-install pdo pdo_mysql