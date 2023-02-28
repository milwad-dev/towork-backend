FROM php:8.2.3-fpm-alpine3.17

# Install required packages
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    build-base \
    libpng-dev \
    libzip-dev \
    unzip \
    linux-headers \
    zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    gd \
    zip

# Set working directory
WORKDIR /srv/app

RUN addgroup -g 1000 -S docker && \
    adduser -u 1000 -S docker -G docker

# Install composer dependencies
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN set -eux; \
    composer clear-cache

COPY ./docker-entrypoint.sh /docker-entrypoint.sh

RUN chmod +x /docker-entrypoint.sh

RUN chown -R www-data:www-data /srv/app

USER docker

# Expose port 9000 for PHP-FPM
EXPOSE 9000

ENTRYPOINT ["/docker-entrypoint.sh"]

# Start PHP-FPM
CMD ["/bin/sh"]
