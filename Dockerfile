FROM php:8.2-cli-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    curl \
    libzip-dev \
    oniguruma-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

COPY . .

RUN chmod -R 775 storage bootstrap/cache
RUN chmod +x docker/start.sh

EXPOSE 10000

CMD ["sh", "docker/start.sh"]
