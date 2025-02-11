#!/bin/bash
HOSTNAME=`hostname`
if [ ${HOSTNAME:0:3} == 'dev' ]; then
    chown -R woa:woa /var/www/woa*
    chmod -R 777 /var/www/woa*
fi
