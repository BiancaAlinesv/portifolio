#!/bin/sh
set -e

envsubst < /etc/msmtprc.template > /etc/msmtprc
chmod 600 /etc/msmtprc
chown www-data:www-data /etc/msmtprc

exec apache2-foreground
