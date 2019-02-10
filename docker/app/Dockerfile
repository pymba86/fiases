FROM mileschou/phalcon:7.2-fpm-alpine
LABEL Description="fias-es-app"

ENV PS1='\[\033[1;32m\]🐳  \[\033[1;36m\][\u@\h] \[\033[1;34m\]\w\[\033[0;35m\] \[\033[1;36m\]# \[\033[0m\]'

ENV PHP_XDEBUG_VERSION 2.6.0
ENV COMPOSER_VERSION 1.8.3

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

RUN apk add --no-cache --virtual .persistent-deps \
    # for intl extension
    icu-dev \
    libicu-dev \
    # for soap
    libxml2-dev \
    # for bz2 extension
    bzip2-dev \
    # for intl extension
    libintl gettext-dev libxslt \
    # etc
    bash nano

RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
    && docker-php-ext-configure bcmath --enable-bcmath \
    && docker-php-ext-configure intl --enable-intl \
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
        intl \
        pcntl \
        iconv \
        bz2 \
        calendar \
        exif \
        mbstring \
        soap \
        gettext \
        opcache \
    && echo -e "opcache.memory_consumption=128\n\
opcache.interned_strings_buffer=8\n\
opcache.max_accelerated_files=4000\n\
opcache.revalidate_freq=60\n\
opcache.fast_shutdown=1\n\
opcache.enable_cli=1\n\
opcache.enable=1\n" > /usr/local/etc/php/conf.d/opcache.ini \
    # For phpunit coverage
    && pecl install xdebug-${PHP_XDEBUG_VERSION} \
    && pecl install rar \
    && apk del .build-deps \
    && rm -rf /tmp/* \
    && rm -rf /app \
    && mkdir /app \
    && rm -rf /scripts \
    && mkdir /scripts \
    && mkdir -p /scripts/aliases \
    && rm -rf /home/user \
    && mkdir /home/user \
    && chmod 777 /home/user \
    && rm -f /docker-entrypoint.sh \
    && rm -f /usr/local/etc/php-fpm.d/*

COPY ./etc/php/php.ini /usr/local/etc/php/php.ini
COPY ./etc/php/php-fpm.conf /usr/local/etc/php-fpm.conf

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV PATH /scripts:/scripts/aliases:$PATH

RUN set -xe \
    && mkdir -p "$COMPOSER_HOME" \
    # install composer
    && php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" \
    && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/bin --filename=composer --version=$COMPOSER_VERSION \
    && composer --ansi --version --no-interaction \
    && composer --no-interaction global require 'hirak/prestissimo' \
    && composer clear-cache \
    && rm -rf /tmp/composer-setup.php /tmp/.htaccess \
    # show php info
    && php -v \
    && php-fpm -v \
    && php -m

COPY ./keep-alive.sh /scripts/keep-alive.sh
COPY ./fpm-entrypoint.sh /fpm-entrypoint.sh
COPY ./aliases/* /scripts/aliases/

WORKDIR /app
ENTRYPOINT []
CMD []