FROM php:8.4-apache

RUN apt-get update && apt-get install -y msmtp msmtp-mta gettext-base && rm -rf /var/lib/apt/lists/*

COPY docker/apache/custom.conf /etc/apache2/sites-available/000-default.conf
COPY docker/msmtp/msmtprc.template /etc/msmtprc.template
COPY docker/entrypoint.sh /entrypoint.sh

RUN a2enmod rewrite \
    && a2dissite 000-default && a2ensite 000-default \
    && chmod +x /entrypoint.sh \
    && echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini

RUN mkdir -p /var/www/html \
    && chown -R www-data:www-data /var/www/html

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
