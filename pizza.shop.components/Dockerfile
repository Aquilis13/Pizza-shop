FROM api.pizza-shop

WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update
RUN apt-get install zip unzip
RUN apt-get install gzip tar

RUN composer update
RUN composer install