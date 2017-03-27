FROM eccube/php-ext-apache

MAINTAINER Kentaro Ohkouchi <nanasess@fsm.ne.jp>

ENV PGUSER cube3_dev_user
ARG ECCUBE_PATH=/var/www/html
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

USER www-data

## Clone EC-CUBE3
ADD clone-${DIRTYPE}.sh /clone.sh
RUN /clone.sh
RUN (cd ${ECCUBE_PATH} && echo "Current revision: $(git log -n 1 --format=%H) ($(git rev-parse --abbrev-ref HEAD))")

WORKDIR ${ECCUBE_PATH}
RUN composer install

USER root

COPY config/exec_env.sh ${ECCUBE_PATH}/exec_env.sh
RUN chmod +x ${ECCUBE_PATH}/exec_env.sh
RUN ls -lt ${ECCUBE_PATH}/

# Enable SSL
RUN a2enmod ssl
RUN ln -s ${APACHE_CONFDIR}/sites-available/default-ssl.conf ${APACHE_CONFDIR}/sites-enabled/default-ssl.conf

EXPOSE 80 443


