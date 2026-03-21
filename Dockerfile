FROM php:8.4-apache-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

RUN a2enmod rewrite

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV APP_SECRET=set-me-on-render

RUN mkdir -p var/cache var/log \
    && php bin/console cache:warmup --env=prod --no-debug \
    && chown -R www-data:www-data var

EXPOSE 80
CMD ["apache2-foreground"]
