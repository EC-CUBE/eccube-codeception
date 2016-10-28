#!/bin/bash

set -e

cmd="$@"

export PGPASSWORD=password
export DBSERVER=db
export AUTH_MAGIC=XjosAXOzO1B3mE0egwQA

until psql -h db -U "cube3_dev_user" -d "template1" -c '\l'; do
  >&2 echo "Postgres is unavailable - sleeping"
  sleep 1
done

>&2 echo "Postgres is up - executing command"
/var/www/exec_env.sh
php /var/www/eccube_install.php pgsql none --skip-createdb --verbose
chown -R www-data:www-data /var/www
apache2-foreground
