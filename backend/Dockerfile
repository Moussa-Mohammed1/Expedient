FROM node:20-alpine AS frontend_builder

WORKDIR /app

COPY . .

RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi
RUN npm run build

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

RUN php artisan storage:link || true

COPY --from=frontend_builder /app/public/build ./public/build

CMD php artisan serve --host=0.0.0.0 --port=$PORT