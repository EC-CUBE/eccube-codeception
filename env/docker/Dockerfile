FROM php:5.6-apache

MAINTAINER Keisuke Mutoh <kmutoh@office-ora.com>

ENV APACHEUSER webuser
ENV APACHEUSERID 501
ENV APACHEGROUP docker
ENV APACHEGROUPID 497

RUN apt-get update && apt-get install --no-install-recommends -y \
        git vim curl wget sudo libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libmcrypt-dev libxml2-dev libpq-dev libpq5 postgresql-client \
        && docker-php-ext-configure \
        gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-install \
        mbstring zip gd xml pdo pdo_pgsql pdo_mysql soap \
        && rm -r /var/lib/apt/lists/*

RUN groupadd -g ${APACHEGROUPID} ${APACHEGROUP} 
RUN useradd -ms /bin/bash -u ${APACHEUSERID} -g ${APACHEGROUP} ${APACHEUSER}

RUN ls -lt ${PHP_INI_DIR}/conf.d/
COPY config/php.ini ${PHP_INI_DIR}/
COPY config/apache2.conf /etc/apache2/
COPY config/start.sh /root/start.sh

## Edit Configs
#ENV ECCUBE_PATH /home/${APACHEUSER}/cube3-1
#RUN mkdir ${ECCUBE_PATH}
#RUN mkdir ${ECCUBE_PATH}/html
#RUN chown -R ${APACHEUSER}:${APACHEGROUP} ${ECCUBE_PATH}
#RUN sed -i -e "s|/var/www/html|${ECCUBE_PATH}/html|g" /etc/apache2/apache2.conf

EXPOSE 80

RUN a2enmod rewrite
CMD /root/start.sh 

