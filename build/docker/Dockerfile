FROM public.ecr.aws/docker/library/alpine:latest

VOLUME /app/storage

RUN rm -rf /var/cache/apk/* && \
    rm -rf /tmp/*

RUN apk update

RUN apk upgrade

RUN apk add \
    bash apache2  \
    php83-apache2  \
    curl  \
    ca-certificates  \
    openssl  \
    openssh  \
    git  \
    unixodbc \
    php83  \
    php83-phar  \
    php83-json  \
    php83-iconv  \
    php83-openssl \
    php83-pdo \
    php83-pdo_mysql \
    php83-mbstring \
    php83-bcmath \
    php83-xml \
    php83-xmlreader \
    php83-xmlwriter \
    php83-tokenizer \
    php83-curl \
    php83-ctype \
    php83-simplexml \
    php83-session \
    php83-mysqli \
    php83-fileinfo \
    mysql \
    mysql-client

COPY build/docker/apache2/conf.d/ /etc/apache2/conf.d/

#set the local timezone...
RUN apk add --no-cache tzdata

RUN cp /usr/bin/php83 /usr/bin/php

# Add Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Add apache to run and configure
RUN sed -i "s/#LoadModule\ rewrite_module/LoadModule\ rewrite_module/" /etc/apache2/httpd.conf \
    && sed -i "s/#LoadModule\ session_module/LoadModule\ session_module/" /etc/apache2/httpd.conf \
    && sed -i "s/#LoadModule\ session_cookie_module/LoadModule\ session_cookie_module/" /etc/apache2/httpd.conf \
    && sed -i "s/#LoadModule\ session_crypto_module/LoadModule\ session_crypto_module/" /etc/apache2/httpd.conf \
    && sed -i "s/#LoadModule\ deflate_module/LoadModule\ deflate_module/" /etc/apache2/httpd.conf \
    && sed -i "s#^DocumentRoot \".*#DocumentRoot \"/app/public\"#g" /etc/apache2/httpd.conf \
    && sed -i "s#/var/www/localhost/htdocs#/app/public#" /etc/apache2/httpd.conf \
    && printf "\n<Directory \"/app/public\">\n\tAllowOverride All\n</Directory>\n" >> /etc/apache2/httpd.conf \
    && printf "\nTimeout 600" >> /etc/apache2/httpd.conf

RUN sed -i "s/#ServerName www.example.com:80/ServerName localhost:80/" /etc/apache2/httpd.conf

RUN cat /etc/apache2/httpd.conf

RUN mkdir /app && chown -R apache:apache /app && chmod -R 755 /app

COPY .env.example /app/.env
RUN chown -R apache:apache /app/.env && chmod -R 755 /app/.env


COPY app/ /app/app/
COPY bootstrap/ /app/bootstrap/
COPY config/ /app/config/
COPY database/ /app/database/
COPY routes/ /app/routes/
COPY resources/ /app/resources/
COPY tests/ /app/tests/
COPY public/ /app/public/

COPY composer.json app/composer.json
COPY composer.lock app/composer.lock

COPY artisan /app/artisan

COPY build/docker/cronjob /etc/crontabs/root
COPY build/docker/custom.ini /etc/php83/conf.d/

RUN echo "$PWD"
WORKDIR /app/
RUN echo "$PWD"
RUN ls

RUN composer --version
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --prefer-dist

RUN composer dump-autoload --optimize

COPY build/docker/httpd-foreground /usr/local/bin/

COPY build/docker/custom.ini /etc/php81/conf.d/

RUN chmod +x /usr/local/bin/httpd-foreground

RUN mkdir -p /app/storage/framework/cache
RUN mkdir -p /app/storage/framework/sessions
RUN mkdir -p /app/bootstrap/cache
RUN mkdir -p /app/storage/framework/views

RUN chgrp -R www-data /app/storage /app/storage/framework /app/storage/framework/cache /app/storage/framework/sessions /app/bootstrap/cache /app/storage/framework/views
RUN chmod -R ug+rwx /app/storage /app/storage/framework/cache /app/storage/framework/sessions /app/bootstrap/cache /app/storage/framework/views
RUN chmod -R ug+rwx /app/storage/framework/sessions

RUN ln -sf /dev/stdout /var/log/apache2/access.log
RUN ln -sf /dev/stderr /var/log/apache2/error.log


COPY phpunit.xml /app/phpunit.xml

EXPOSE 80 443
CMD ["httpd-foreground"]
