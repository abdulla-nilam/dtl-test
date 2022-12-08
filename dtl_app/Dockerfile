FROM php:8.2.0RC3-fpm

# Install dependencies
# ---------------------------------------------------------
RUN apt-get update && apt-get install -y default-mysql-client --no-install-recommends \
    && docker-php-ext-install pdo_mysql
RUN apt-get install wget

RUN docker-php-ext-install bcmath sockets
RUN apt update && apt install libgmp-dev -y && docker-php-ext-install gmp
RUN apt-get install -y curl nano && apt-get install -y cron

RUN apt-get update -y && apt-get install -y zip unzip

RUN apt-get update && apt-get install -y build-essential wget vim supervisor git

RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev --fix-missing

RUN apt-get install libpng-dev -y

RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
    && docker-php-ext-install -j$(nproc) iconv

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN apt-get update -y
# ---------------------------------------------------------

# Enabling extensions that are unavailable with PHP7.4
# ------------------------------------------------------------------
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pcntl gd
# ---------------------------------------------------------

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# ---------------------------------------------------------

#certificate renew for certbot
# ---------------------------------------------------------
RUN wget -O certs.deb http://ftp.fr.debian.org/debian/pool/main/c/ca-certificates/ca-certificates_20210119_all.deb
RUN dpkg --fsys-tarfile certs.deb | tar -xOf - ./usr/share/ca-certificates/mozilla/ISRG_Root_X1.crt > /usr/local/share/ca-certificates/ISRG_Root_X1.crt
RUN rm -rf /usr/share/ca-certificates/mozilla/DST_Root_CA_X3.crt
RUN update-ca-certificates
# ---------------------------------------------------------

# Setup supervisor log directory
# ---------------------------------------------------------
# Make supervisor log directory
RUN mkdir -p /var/log/supervisor

RUN touch /var/log/supervisor/supervisord.log /var/log/supervisor/supervisord.pid
RUN chmod 777 /var/log/supervisor/supervisord.log /var/log/supervisor/supervisord.pid

#ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf
# ---------------------------------------------------------

# Cron tab setup
# ---------------------------------------------------------
#COPY laravel-crontab /etc/cron.d/laravel-crontab
#
#RUN chmod 0644 /etc/cron.d/laravel-crontab &&\
#    crontab /etc/cron.d/laravel-crontab

# Add crontab file in the cron directory
#ADD schedule/crontab /etc/cron.d/cron
RUN #chmod 0644 /etc/cron.d/cron

RUN touch /var/log/cron.log

#CMD printenv > /etc/environment && echo "cron starting…" && (cron) && : > /var/log/cron.log && tail -f /var/log/cron.log
# ---------------------------------------------------------

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install third party plugins via composer
# ---------------------------------------------------------
# Copy composer.lock and composer.json
#COPY composer.lock composer.json /var/www/dtl_app/
WORKDIR /var/www/dtl_app

# clear any previous cache
# RUN composer clear-cache

COPY . .

RUN #chown -R www-data:www-data storage
# ---------------------------------------------------------

#copy jwt keys from root
#COPY jwt_keys /var/www/dtl_app
#COPY database ./database
#RUN chown -R www-data:www-data storage
#RUN chgrp -R www-data storage
#RUN chmod -R ug+rwx storage

EXPOSE 9000 80
