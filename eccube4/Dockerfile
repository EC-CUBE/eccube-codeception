FROM eccube/php-ext-apache

MAINTAINER Kentaro Ohkouchi <nanasess@fsm.ne.jp>

ENV PGUSER cube4_dev_user
ARG ECCUBE_PATH
ARG DBTYPE
ARG ECCUBE_REPOS
ARG ECCUBE_BRANCH
ARG DIRTYPE="root"

ENV ECCUBE_PATH=${ECCUBE_PATH}
ENV DATABASE_URL=${DATABASE_URL}
ENV DATABASE_SERVER_VERSION=${DATABASE_SERVER_VERSION}

RUN useradd -ms /bin/bash ${PGUSER}

RUN ls -lt ${PHP_INI_DIR}/conf.d/
COPY config/php.ini ${PHP_INI_DIR}/
ADD wait-for-${DBTYPE}.sh /wait-for-db.sh
RUN chmod +x /wait-for-db.sh

ENTRYPOINT /wait-for-db.sh

USER www-data

## Clone EC-CUBE

ADD clone-${DIRTYPE}.sh /clone.sh
RUN /clone.sh
RUN (cd ${ECCUBE_PATH} && echo "Current revision: $(git log -n 1 --format=%H) ($(git rev-parse --abbrev-ref HEAD))")
RUN { \
    echo "APP_ENV=prod"; \
    echo "APP_DEBUG=0"; \
} > ${ECCUBE_PATH}/.env


WORKDIR ${ECCUBE_PATH}

USER root

# Enable SSL
RUN a2enmod ssl
RUN ln -s ${APACHE_CONFDIR}/sites-available/default-ssl.conf ${APACHE_CONFDIR}/sites-enabled/default-ssl.conf

RUN a2enmod rewrite
RUN a2enmod headers

VOLUME ${ECCUBE_PATH}

EXPOSE 80 443


