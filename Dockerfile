# syntax=docker/dockerfile:1

############################################
# Base: PHP-FPM with the extensions we need
############################################
FROM php:8.4-fpm-alpine AS base

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql redis intl zip pcntl bcmath opcache \
    && apk add --no-cache fcgi \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/php.ini $PHP_INI_DIR/conf.d/zz-towork.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/zz-towork.conf

WORKDIR /var/www/html

############################################
# Composer dependencies (cached layer)
############################################
FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --no-progress

############################################
# App: PHP-FPM / queue worker / scheduler
############################################
FROM base AS app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=vendor /var/www/html/vendor ./vendor
COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative --no-dev \
    && rm -f /usr/bin/composer \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs storage/app/private bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod +x docker/entrypoint.sh

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr

USER www-data
EXPOSE 9000

HEALTHCHECK --interval=10s --timeout=3s --start-period=30s --retries=5 \
    CMD SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000 | grep -q pong || exit 1

ENTRYPOINT ["docker/entrypoint.sh"]
CMD ["php-fpm"]

############################################
# Web: nginx serving /public and proxying PHP
############################################
FROM nginx:1.29-alpine AS web

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=app /var/www/html/public /var/www/html/public

EXPOSE 80
HEALTHCHECK --interval=10s --timeout=3s --retries=5 CMD wget -qO- http://127.0.0.1/up >/dev/null || exit 1
