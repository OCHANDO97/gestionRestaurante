# Dockerfile en la raíz de tu proyecto

FROM php:8.2-fpm-alpine

# 1) Instala extensiones PHP necesarias
RUN apk add --no-cache \
    libzip-dev oniguruma-dev icu-dev libxml2-dev curl-dev \
  && docker-php-ext-install \
       pdo_mysql zip bcmath intl xml mbstring curl \
  && docker-php-ext-enable intl

# 2) Directorio de trabajo
WORKDIR /var/www

# 3) Copia el código de la aplicación (sin vendor ni node_modules)
COPY . .

# 4) Ajusta permisos
RUN chown -R www-data:www-data /var/www

# 5) Expone el puerto de PHP-FPM
EXPOSE 9000

# 6) Arranca PHP-FPM (el Nginx o 'artisan serve' lo pondremos en docker-compose)
CMD ["php-fpm"]
