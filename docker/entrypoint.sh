#!/bin/sh
set -e

export SMTP_HOST SMTP_PORT SMTP_USER SMTP_PASS SMTP_FROM

envsubst < /etc/msmtprc.template > /etc/msmtprc
chmod 600 /etc/msmtprc
chown www-data:www-data /etc/msmtprc

chown -R www-data:www-data /var/www/html

exec apache2-foreground
