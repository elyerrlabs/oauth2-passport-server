FROM alpine:3.21
LABEL maintainer="Elvis Yerel Roman C. <yerel9212@yahoo.es>"

ARG UID
ARG GID

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
    git \ 
    vim \
    npm \
    shadow

# Create dev user and group
RUN \
    if ! getent group dev >/dev/null; then \
        addgroup -g ${GID} dev; \
    fi && \
    if ! id dev >/dev/null 2>&1; then \
        adduser -D -u ${UID} -G dev -s /bin/bash dev; \
    fi

# Symlink php
RUN ln -sf /usr/bin/php84 /usr/bin/php
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir -p /etc/supervisor.d 

WORKDIR /var/www/

COPY php/www.conf /etc/php84/php-fpm.d/www.conf
COPY php/php.ini /etc/php84/conf.d/99-custom.ini
COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/default.conf /etc/nginx/http.d/default.conf
COPY supervisor/laravel.ini /etc/supervisor.d/laravel.ini
COPY entrypoint.sh /usr/local/bin/entrypoint.sh 

RUN chmod 440 /etc/nginx/http.d/default.conf 
RUN chmod 550 /usr/local/bin/entrypoint.sh

# Replace UID and GID in configuration files
RUN \
    find \
        /etc/php84 \
        /etc/nginx \
        /etc/supervisor.d \
        /usr/local/bin/entrypoint.sh \
        -type f \
        -exec sed -i \
            -e "s|\${UID}|dev|g" \
            -e "s|\${GID}|dev|g" {} + \
    2>/dev/null


CMD [ "sh" ]