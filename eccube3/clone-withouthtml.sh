#!/bin/bash

git clone --depth=50 -b ${ECCUBE_BRANCH} ${ECCUBE_REPOS} ${ECCUBE_PATH}

mv ${ECCUBE_PATH}/html/index.php \
    ${ECCUBE_PATH}/html/index_dev.php \
    ${ECCUBE_PATH}/html/install.php \
    ${ECCUBE_PATH}/html/robots.txt \
    ${ECCUBE_PATH}/html/.htaccess \
    ${ECCUBE_PATH}/html/web.config \
    ${ECCUBE_PATH}
mv ${ECCUBE_PATH}/.htaccess.sample ${ECCUBE_PATH}/.htaccess
mv ${ECCUBE_PATH}/web.config.sample ${ECCUBE_PATH}/web.config
sed -i -e 's_^require_//require_' -e "s-//require __DIR__.'/autoload.php';-require __DIR__.'/autoload.php';-" ${ECCUBE_PATH}/index.php
sed -i -e 's_^require_//require_' -e "s-//require __DIR__.'/autoload.php';-require __DIR__.'/autoload.php';-" ${ECCUBE_PATH}/index_dev.php
sed -i -e 's_^require_//require_' -e "s-//require __DIR__ . '/autoload.php';-require __DIR__ . '/autoload.php';-" ${ECCUBE_PATH}/install.php
sed -i -e 's_^define_//define_' -e "s-//define(\"RELATIVE_PUBLIC_DIR_PATH\", '/html');-define(\"RELATIVE_PUBLIC_DIR_PATH\", '/html');-" ${ECCUBE_PATH}/autoload.php
