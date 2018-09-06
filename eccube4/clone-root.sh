#!/bin/bash

cd ${ECCUBE_PATH}
git init
git remote add origin ${ECCUBE_REPOS}
git fetch --depth=50 origin
git checkout origin/${ECCUBE_BRANCH}
composer install
