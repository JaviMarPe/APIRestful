FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql zip

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar la configuración de Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copy existing application directory contents
COPY src/ /var/www/html

# Cambiar el propietario del directorio
RUN chown -R www-data:www-data /var/www/html

# Asegúrate de que el usuario www-data tenga el UID y GID correctos
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Establece los permisos correctos para los directorios de Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache