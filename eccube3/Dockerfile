FROM eccube/php7-ext-apache

MAINTAINER Kentaro Ohkouchi <nanasess@fsm.ne.jp>

RUN apt-get update && apt-get install --no-install-recommends -y \
        vim \
        && pecl install xdebug  \
        && docker-php-ext-enable xdebug \
        && rm -r /var/lib/apt/lists/*

ENV PGUSER cube3_dev_user
ARG ECCUBE_PATH=/var/www
ARG DBTYPE
ARG ECCUBE_REPOS
ARG ECCUBE_BRANCH
ARG DIRTYPE="root"
ENV ECCUBE_PATH=${ECCUBE_PATH}

RUN useradd -ms /bin/bash ${PGUSER}

RUN ls -lt ${PHP_INI_DIR}/conf.d/
COPY config/php.ini ${PHP_INI_DIR}/
ADD wait-for-${DBTYPE}.sh /wait-for-db.sh
RUN chmod +x /wait-for-db.sh

ENTRYPOINT /wait-for-db.sh

## Clone EC-CUBE3
ADD clone-${DIRTYPE}.sh /clone.sh
RUN /clone.sh

WORKDIR ${ECCUBE_PATH}

RUN sed -i.bak -e 's/5.3.9/5.4.0/g' ${ECCUBE_PATH}/composer.json

RUN composer install
RUN composer require codeception/codeception "2.*"
RUN curl https://raw.githubusercontent.com/Codeception/c3/2.0/c3.php -o ${ECCUBE_PATH}/c3.php

COPY html/index_cover.php ${ECCUBE_PATH}/html/index_cover.php

COPY config/exec_env.sh ${ECCUBE_PATH}/exec_env.sh
COPY config/codeception.yml ${ECCUBE_PATH}/codeception.yml
RUN mkdir -p ${ECCUBE_PATH}/tests/_output
RUN chmod o+w ${ECCUBE_PATH}/tests/_output

RUN chmod +x ${ECCUBE_PATH}/exec_env.sh
RUN ls -lt ${ECCUBE_PATH}/

# Enable SSL
RUN a2enmod ssl
RUN ln -s ${APACHE_CONFDIR}/sites-available/default-ssl.conf ${APACHE_CONFDIR}/sites-enabled/default-ssl.conf

EXPOSE 80 443


