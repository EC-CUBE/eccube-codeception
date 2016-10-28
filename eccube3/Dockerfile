FROM php:7-apache

MAINTAINER Kentaro Ohkouchi <nanasess@fsm.ne.jp>

ENV PGUSER cube3_dev_user
ENV ECCUBE_PATH /var/www

ARG DBTYPE
ARG ECCUBE_REPOS
ARG ECCUBE_BRANCH

RUN apt-get update && apt-get install --no-install-recommends -y \
        git curl wget sudo libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libmcrypt-dev libxml2-dev libpq-dev libpq5 postgresql-client mysql-client \
        && docker-php-ext-configure \
        gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-install -j$(nproc) \
        mbstring zip gd xml pdo pdo_pgsql pdo_mysql soap mcrypt \
        && rm -r /var/lib/apt/lists/*

RUN useradd -ms /bin/bash ${PGUSER}

RUN ls -lt ${PHP_INI_DIR}/conf.d/
COPY config/php.ini ${PHP_INI_DIR}/
ADD wait-for-${DBTYPE}.sh /wait-for-db.sh
RUN chmod +x /wait-for-db.sh

ENTRYPOINT /wait-for-db.sh

## Clone EC-CUBE3
RUN cd /var && \
    rm -r /var/www && \
    git clone --depth=50 -b ${ECCUBE_BRANCH} ${ECCUBE_REPOS} ${ECCUBE_PATH}

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin
RUN composer global require --optimize-autoloader \
        "hirak/prestissimo"

WORKDIR ${ECCUBE_PATH}
RUN composer install
COPY config/exec_env.sh ${ECCUBE_PATH}/exec_env.sh
RUN chmod +x ${ECCUBE_PATH}/exec_env.sh
RUN ls -lt ${ECCUBE_PATH}/

EXPOSE 80

