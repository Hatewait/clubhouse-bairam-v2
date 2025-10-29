# Базовый PHP-FPM (ARM/AMD дружелюбный)
FROM php:8.3-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk add --no-cache \
    bash git zip unzip curl oniguruma-dev icu-dev libpng-dev libjpeg-turbo-dev libzip-dev \
    sqlite sqlite-dev

# Ставим нужные расширения
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    intl \
    gd \
    zip \
    bcmath

# Установим Composer в этот же образ
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Пользователь без рута (как было)
RUN addgroup -g 1000 app && adduser -D -G app -u 1000 app \
 && chown -R app:app /var/www/html
USER app

CMD ["php-fpm", "-F"]