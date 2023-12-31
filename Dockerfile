FROM php:8.1.9-fpm-alpine3.16

COPY docker/development/php/php.ini /usr/local/etc/php/php.ini

# зависимости
RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql && \
    apk update && apk add bash unzip git

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev \
    libzip-dev

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN chmod -R 777 storage/

CMD php-fpm;
