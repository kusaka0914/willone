#!/bin/bash
HOSTNAME=`hostname`
if [ ${HOSTNAME:0:3} == 'prd' ]; then
    cp /var/www/woa/public/.htaccess.prd /var/www/woa/public/.htaccess
    if [ ${HOSTNAME:0:13} == 'prd-woa-batch' ]; then
        aws s3 cp s3://prd-jb-conf/woa/.env.batch /var/www/woa/.env
        aws s3 cp s3://prd-jb-conf/woa/jinzaisystem-tool-woa-web.json /var/www/woa/jinzaisystem-tool-woa-web.json
    else
        aws s3 cp s3://prd-jb-conf/woa/.env /var/www/woa/.env
    fi
else
    cp /var/www/woa/public/.htaccess.dev /var/www/woa/public/.htaccess
    if [ ${HOSTNAME:0:13} == 'dev-woa-batch' ]; then
        aws s3 cp s3://dev-jb-conf/woa/.env.batch /var/www/woa/.env
        aws s3 cp s3://dev-jb-conf/woa/dev-jinzaisystem-tool-woa-web.json /var/www/woa/dev-jinzaisystem-tool-woa-web.json
    else
        aws s3 cp s3://dev-jb-conf/woa/.env /var/www/woa/.env
    fi
fi

LOG_OUT=/tmp/afterinstall-stdout-$(date +"%Y%m%d")
LOG_ERR=/tmp/afterinstall-stderr-$(date +"%Y%m%d")

exec 1> >(awk '{print strftime("[%Y-%m-%d %H:%M:%S] "),$0 } { fflush() } ' >> "$LOG_OUT")
exec 2> >(awk '{print strftime("[%Y-%m-%d %H:%M:%S] "),$0 } { fflush() } ' >> "$LOG_ERR")

if [ ${HOSTNAME:0:3} == 'dev' ]; then
    composer install -d /var/www/woa
else
    composer install --no-dev -d /var/www/woa
    /usr/bin/php /var/www/woa/artisan opcache:clear
fi
/usr/bin/php /var/www/woa/artisan view:clear
