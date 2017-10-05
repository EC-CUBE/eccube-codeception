FROM eccube/php7-ext-codeception

MAINTAINER Kentaro Ohkouchi <nanasess@fsm.ne.jp>

### see also https://hub.docker.com/r/codeception/codeception/~/dockerfile/

RUN echo "date.timezone = Asia/Tokyo" >> /usr/local/etc/php/php.ini

## Add mailcatcher-codeception-module
WORKDIR /repo
RUN composer require captbaritone/mailcatcher-codeception-module "1.*"

## Clone EC-CUBE3

ENV ECCUBE_PATH /var/www
ENV BASE_URL="http://eccube3/"

ARG ECCUBE_REPOS
ARG ECCUBE_BRANCH
ARG DBTYPE

RUN cd /var && \
    git clone --depth=50 -b ${ECCUBE_BRANCH} ${ECCUBE_REPOS} ${ECCUBE_PATH}

WORKDIR ${ECCUBE_PATH}
RUN composer install

## create config yaml files
RUN php /var/www/eccube_install.php ${DBTYPE} none --skip-createdb --skip-initdb --verbose
WORKDIR /project

## create dummy directories
RUN mkdir /project/html
RUN mkdir /project/app

COPY codecept /repo/codecept
RUN chmod +x /repo/codecept
