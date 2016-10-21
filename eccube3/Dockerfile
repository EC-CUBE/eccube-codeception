FROM php:7-apache

MAINTAINER Kentaro Ohkouchi <nanasess@fsm.ne.jp>

ENV PGUSER cube3_dev_user
ENV ECCUBE_PATH /var/www

ARG ECCUBE_REPOS=https://github.com/nanasess/ec-cube.git
ARG ECCUBE_BRANCHE=improve/php_installer

RUN apt-get update && apt-get install --no-install-recommends -y \
        git curl wget sudo libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libmcrypt-dev libxml2-dev libpq-dev libpq5 postgresql-client \
        && docker-php-ext-configure \
        gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-install -j$(nproc) \
        mbstring zip gd xml pdo pdo_pgsql soap mcrypt \
        && rm -r /var/lib/apt/lists/*

RUN useradd -ms /bin/bash ${PGUSER}

RUN ls -lt ${PHP_INI_DIR}/conf.d/
COPY config/php.ini ${PHP_INI_DIR}/
ADD wait-for-postgres.sh /wait-for-postgres.sh
RUN ["chmod", "+x", "/wait-for-postgres.sh"]

ENTRYPOINT ["/wait-for-postgres.sh", "postgres"]

## Clone EC-CUBE3
RUN cd /var && \
    rm -r /var/www && \
    git clone --depth=50 -b ${ECCUBE_BRANCHE} ${ECCUBE_REPOS} ${ECCUBE_PATH}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer global require hirak/prestissimo
WORKDIR ${ECCUBE_PATH}
RUN composer install
COPY config/exec_env.sh ${ECCUBE_PATH}/exec_env.sh
RUN chmod +x ${ECCUBE_PATH}/exec_env.sh
RUN ls -lt ${ECCUBE_PATH}/

EXPOSE 80

