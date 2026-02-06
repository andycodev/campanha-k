FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Instalar extensiones de PHP necesarias para Laravel y PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www

# Copiar el proyecto
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# --- NUEVAS LÍNEAS DE OPTIMIZACIÓN Y LIMPIEZA ---
# Limpiamos cualquier caché previo que se haya copiado del local
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan cache:clear
# -------------------------------------

# Generamos el caché fresco dentro del contenedor
RUN php artisan config:cache
RUN php artisan route:cache

# Dar permisos (Asegúrate de incluir chmod para escritura)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Puerto que usa Render internamente
EXPOSE 10000

# Comando para iniciar el servidor
CMD php artisan serve --host=0.0.0.0 --port=10000