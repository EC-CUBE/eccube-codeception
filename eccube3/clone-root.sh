#!/bin/bash

cd /var
rm -r /var/www
git clone --depth=50 -b ${ECCUBE_BRANCH} ${ECCUBE_REPOS} ${ECCUBE_PATH}
