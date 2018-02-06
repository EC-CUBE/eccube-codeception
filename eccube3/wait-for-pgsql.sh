#!/bin/bash

set -e

cmd="$@"

until psql -h db -U "${ECCUBE_DB_USERNAME}" -d "template1" -c '\l'; do
  >&2 echo "Postgres is unavailable - sleeping"
  sleep 1
done

>&2 echo "Postgres is up - executing command"
${ECCUBE_PATH}/exec_env.sh

bin/console doctrine:schema:create
bin/console eccube:fixtures:load

bin/console cache:warmup --env=prod

chown -R www-data:www-data ${ECCUBE_PATH}/app
apache2-foreground
