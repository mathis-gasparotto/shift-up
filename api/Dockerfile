FROM php:8.2-alpine3.18 AS api_php
LABEL maintainer="Mathis Gasparotto <mathis.gasparotto@hotmail.com>"

ENV APP_ENV=dev XDEBUG_MODE=off

WORKDIR /srv/api

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer --link /usr/bin/install-php-extensions /usr/local/bin/

# Installer Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"
ENV TZ=Europe/Paris
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer Imagick et ses dépendances
RUN apk update && apk add --no-cache autoconf g++ imagemagick-dev libtool make pcre-dev && \
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    apk del autoconf g++ libtool make pcre-dev

# Installer wkhtmltopdf
RUN apk add --no-cache \
    libstdc++ \
    libx11 \
    libxrender \
    libxext \
    libssl1.1 \
    libcrypto1.1 \
    ca-certificates \
    font-freefont \
    fontconfig \
    freetype \
    ttf-dejavu \
    ttf-droid \
    ttf-freefont \
    ttf-liberation \
    && apk add --no-cache --virtual .build-deps \
    msttcorefonts-installer \
    # Install microsoft fonts
    && update-ms-fonts \
    && fc-cache -f \
    # Clean up when done
    && rm -rf /tmp/* \
    && apk del .build-deps \
    ;

COPY --from=surnet/alpine-wkhtmltopdf:3.10-0.12.5-full --link /bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf
COPY --from=surnet/alpine-wkhtmltopdf:3.10-0.12.5-full --link /bin/wkhtmltoimage /usr/local/bin/wkhtmltoimage
COPY --from=surnet/alpine-wkhtmltopdf:3.10-0.12.5-full --link /bin/libwkhtmltox* /usr/local/bin/

# wkhtmltopdf 0.12.6 (BREAKING CHANGE: block local filesystem access by default => fail on generating with images)
# COPY --from=surnet/alpine-wkhtmltopdf:3.21.2-0.12.6-full --link /bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf
# COPY --from=surnet/alpine-wkhtmltopdf:3.21.2-0.12.6-full --link /bin/wkhtmltoimage /usr/local/bin/wkhtmltoimage
# COPY --from=surnet/alpine-wkhtmltopdf:3.21.2-0.12.6-full --link /bin/libwkhtmltox* /usr/local/bin/

RUN chmod +x /usr/local/bin/wkhtmltopdf \
    && chmod +x /usr/local/bin/wkhtmltoimage

# Installer les extensions PDO MySQL et PostgreSQL
# RUN apk add --no-cache \
#     mariadb-client \
#     postgresql-dev && \
#     docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Installer les extensions PDO MySQL
#RUN apk add --no-cache mariadb-client && \
#    docker-php-ext-install pdo pdo_mysql \

# Installer les extensions PDO PostgreSQL
RUN apk add --no-cache postgresql-dev && \
    docker-php-ext-install pdo pdo_pgsql

# # Installer l'extension AMQP
# RUN apk add --no-cache rabbitmq-c-dev && \
#     docker-php-ext-install amqp

# Définir le fuseau horaire
RUN apk add --no-cache tzdata

# Copier le fichier composer.json (assurez-vous qu'il est dans le même répertoire que votre Dockerfile)
COPY composer.* symfony.* ./

# Installer les dépendances du projet
RUN set -eux; \
    install-php-extensions \
    amqp-1.11.0 \
    ;


##
# DEV LAYER
##
FROM api_php AS development

RUN composer install --prefer-dist --no-scripts --no-progress; \
    composer clear-cache

# copy sources
COPY --link . .
RUN rm -Rf docker/

# Exposer le port 80
EXPOSE 80

# Démarrer le serveur Symfony
CMD ["php", "-S", "0.0.0.0:80", "-t", "/srv/api/public/"]

##
# PROD LAYER
##
FROM api_php AS production

ENV APP_ENV=prod

RUN composer install --prefer-dist --no-scripts --no-progress --no-dev \
    && composer clear-cache

# copy sources
COPY --link . .
RUN rm -Rf docker/

# Exposer le port 80
EXPOSE 80

# Démarrer le serveur Symfony
CMD ["php", "-S", "0.0.0.0:80", "-t", "/srv/api/public/"]
