#!/bin/bash

set -e

cmd="$@"

export DBSERVER=db
export AUTH_MAGIC=XjosAXOzO1B3mE0egwQA
export MAIL_HOST=mailcatcher
export MAIL_PORT=1025

echo "Waiting for mysql"
until mysql -h db --password=password -uroot &> /dev/null
do
  printf "."
  sleep 1
done


>&2 echo "MySQL Ready"
/var/www/exec_env.sh
php /var/www/eccube_install.php mysql none --skip-createdb --verbose
chown -R www-data:www-data ${ECCUBE_PATH}/app
apache2-foreground
