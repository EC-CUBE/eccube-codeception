#!/bin/sh

### edit apache2.conf
# set Directory Directive
cmd="sed -i -e '38c\<Directory /home/"${APACHEUSER}"/cube3-"${CUBEID}"/>' /etc/apache2/apache2.conf"
eval $cmd

# set DocumentRoot
cmd="sed -i -e '43c\DocumentRoot /home/"${APACHEUSER}"/cube3-"${CUBEID}"/html' /etc/apache2/apache2.conf"
eval $cmd

### change owner document root directory
chown -R ${APACHEUSER}:${APACHEGROUP} /home/${APACHEUSER}/cube3-${CUBEID}

### start apache2
apache2-foreground
