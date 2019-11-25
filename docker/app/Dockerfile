FROM composer:1.8.6 AS composer
FROM 512k/roadrunner:1.4.6 AS roadrunner

FROM mileschou/phalcon:7.2-alpine

ENV \
    COMPOSER_ALLOW_SUPERUSER="1" \
    COMPOSER_HOME="/tmp/composer" \
    OWN_SSL_CERT_DIR="/ssl-cert" \
    OWN_SSL_CERT_LIFETIME=1095 \
    PS1='\[\033[1;32m\]\[\033[1;36m\][\u@\h] \[\033[1;34m\]\w\[\033[0;35m\] \[\033[1;36m\]# \[\033[0m\]'

# persistent / runtime deps
ENV PHPIZE_DEPS \
    autoconf \
    cmake \
    file \
    g++ \
    gcc \
    libc-dev \
    pcre-dev \
    make \
    git \
    pkgconf \
    re2c \
    libxslt-dev

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=roadrunner /usr/bin/rr /usr/bin/rr

RUN apk add --no-cache --virtual .persistent-deps \
    # etc
    bash nano

RUN set -xe \
    && apk add --no-cache ${PERMANENT_DEPS} \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
    && docker-php-ext-configure bcmath --enable-bcmath \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-configure mbstring --enable-mbstring \
    && docker-php-ext-configure soap --enable-soap \
    && docker-php-ext-configure xml --enable-xml \
    && docker-php-ext-configure json --enable-json \
    && docker-php-ext-configure zip --enable-zip \
    && docker-php-ext-configure fileinfo --enable-fileinfo \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        fileinfo \
        json \
        xml \
        zip \
        pcntl \
        mbstring \
        soap \
        opcache \
    # For phpunit coverage
    && pecl install rar \
    && pecl install runkit \
    && apk del .build-deps \
    && rm -rf /app /home/user ${COMPOSER_HOME} /var/cache/apk/* \
    && mkdir /app /home/user ${COMPOSER_HOME} \
    && composer global require 'hirak/prestissimo' --no-interaction --no-suggest --prefer-dist \
    && ln -s /usr/bin/composer /usr/bin/c

COPY ./docker/app/etc/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/app/etc/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./docker/app/app-entrypoint.sh /app-entrypoint.sh

WORKDIR /app

COPY ./composer.* /app/

RUN set -xe \
    && composer install --no-interaction --no-ansi --no-suggest --prefer-dist  --no-autoloader --no-scripts \
    && composer install --no-dev --no-interaction --no-ansi --no-suggest --prefer-dist  --no-autoloader --no-scripts \
    && chmod -R 777 /home/user ${COMPOSER_HOME}

COPY . /app

RUN set -xe \
    && chmod +x /app-entrypoint.sh \
    && composer --version \
    && php -v \
    && php -m \
    && rr -h \
    && composer dump

EXPOSE 80
EXPOSE 443
VOLUME ["/app"]

ENTRYPOINT ["/app-entrypoint.sh"]
CMD ["rr", "serve", "-c", "/app/.rr.yml"]
