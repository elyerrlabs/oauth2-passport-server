FROM alpine:3.21 AS bnode
LABEL maintainer="Elvis Yerel Roman C. <yerel9212@yahoo.es>"

WORKDIR /var/www/

COPY app app
COPY bootstrap bootstrap
COPY config config
COPY core core
COPY database database
COPY lang lang
COPY public public
COPY resources resources
COPY routes routes
COPY secrets secrets
COPY storage storage
COPY third-party third-party
COPY artisan artisan
COPY package.json package.json
COPY package-lock.json package-lock.json
COPY webpack.mix.js webpack.mix.js

RUN apk add --no-cache --virtual .build-deps npm unzip \
    && npm install \
    && npm run production \
    && npm cache clean --force


########################END NODE BUILDER##############################

FROM alpine:3.21 AS bphp
LABEL maintainer="Elvis Yerel Roman C. <yerel9212@yahoo.es>"

WORKDIR /var/www/

COPY app app
COPY bootstrap bootstrap
COPY config config
COPY core core
COPY database database
COPY lang lang
COPY public public
COPY resources resources
COPY routes routes
COPY secrets secrets
COPY storage storage
COPY third-party third-party
COPY artisan artisan 
COPY composer.json composer.json
COPY composer.lock composer.lock

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
    curl \
    php84-iconv \
    icu-data-full \
    unzip

RUN ln -sf /usr/bin/php84 /usr/bin/php
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk add --no-cache --virtual unzip \
    && composer install --no-dev --optimize-autoloader \
    && composer clear-cache 

###########################END PHP BUILDER################################

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
    icu-data-full \
    php84-iconv \
    wireguard-tools \
    php84-pecl-grpc \
    grpc \
    protobuf 

# Add user and group www-data
RUN getent passwd www-data || adduser -S -G www-data -s /usr/sbin/nologin www-data

# Symlink php
RUN ln -sf /usr/bin/php84 /usr/bin/php
RUN mkdir -p /etc/supervisor.d 

WORKDIR /var/www/

COPY app app
COPY bootstrap bootstrap
COPY config config
COPY core core
COPY database database
COPY lang lang
COPY resources resources
COPY routes routes
COPY secrets secrets
COPY storage storage
COPY third-party third-party
COPY artisan artisan
COPY LICENSE.md LICENSE.md

COPY docker/staging/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/staging/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/staging/php/www.conf /etc/php84/php-fpm.d/www.conf
COPY docker/staging/php/php.ini /etc/php84/conf.d/99-custom.ini
COPY docker/staging/supervisor/laravel.ini /etc/supervisor.d/laravel.ini
COPY docker/staging/entrypoint.sh /usr/local/bin/entrypoint.sh 

# supervisor config to manage services
RUN chmod 440 /etc/nginx/http.d/default.conf \
    && chmod 550 /usr/local/bin/entrypoint.sh \
    && rm -rf docker \
    && rm -rf public

COPY --from=bnode /var/www/public public
COPY --from=bphp  /var/www/vendor vendor
COPY --from=bphp  /var/www/bootstrap/cache bootstrap/cache 
COPY --from=bphp  /var/www/composer.json composer.json
COPY --from=bphp  /var/www/composer.lock composer.lock
COPY --from=bphp  /usr/local/bin/composer /usr/local/bin/composer