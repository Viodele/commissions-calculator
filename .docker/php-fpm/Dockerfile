FROM php:8.3-fpm-alpine
WORKDIR '/var/www/app'

# --- Copying configs
COPY config/default/php-overrides.ini /usr/local/etc/php/conf.d/99-overrides.ini

# --- Composer Install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 9000

CMD ["php-fpm"]
