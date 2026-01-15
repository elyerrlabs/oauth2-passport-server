FROM alpine:3.21
LABEL maintainer="Elvis Yerel Roman C. <yerel9212@yahoo.es>"
RUN apk add --no-cache \
    php84 \
    php84-fpm \
    php84-opcache \
    php84-cli \
    php84-mbstring \
    php84-xml \
    php84-curl \
    php84-pecl-redis \
    php84-pecl-memcached \
    php84-pcntl \
    php84-posix \
    php84-pdo \
    php84-pdo_pgsql \
    php84-zip \
    php84-tokenizer \
    php84-json \
    php84-phar \
    php84-fileinfo \
    php84-dom \
    php84-session \
    php84-simplexml \
    php84-xmlwriter \
    php84-soap \
    php84-openssl \
    php84-bcmath \
    php84-gd \
    php84-intl \
    php84-sodium \
    php84-pecl-redis \
    nginx \
    curl \
    supervisor \
    icu-data-full

# Add user and group www-data
RUN getent passwd www-data || adduser -S -G www-data -s /usr/sbin/nologin www-data

# Symlink php
RUN ln -sf /usr/bin/php84 /usr/bin/php
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir -p /etc/supervisor.d

WORKDIR /var/www/

COPY . /var/www/ 
COPY docker/production/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/production/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/production/php/www.conf /etc/php84/php-fpm.d/www.conf
COPY docker/production/php/php.ini /etc/php84/conf.d/99-custom.ini
COPY docker/production/supervisor/laravel.ini /etc/supervisor.d/laravel.ini
COPY docker/production/entrypoint.sh /usr/local/bin/entrypoint.sh 

# supervisor config to manage services
RUN chmod 440 /etc/nginx/http.d/default.conf \
    && chmod 550 /usr/local/bin/entrypoint.sh

# Install js dependencies and remove after build
RUN apk add --no-cache --virtual .build-deps npm unzip \
    && composer install --no-dev --optimize-autoloader \
    && composer clear-cache \
    && npm install \
    && npm run production \
    && rm -rf node_modules \
    && npm cache clean --force \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* \
    && rm -rf /var/www/docker