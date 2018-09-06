#!/bin/bash

git clone --depth=50 -b ${ECCUBE_BRANCH} ${ECCUBE_REPOS} ${ECCUBE_PATH}
cd ${ECCUBE_PATH}
composer install
