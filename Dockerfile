FROM php:8.4-apache

RUN apt-get update && apt-get install -y msmtp msmtp-mta && rm -rf /var/lib/apt/lists/*

COPY docker/apache/custom.conf /etc/apache2/sites-available/000-default.conf
COPY docker/msmtp/msmtprc /etc/msmtprc

RUN a2enmod rewrite \
    && a2dissite 000-default && a2ensite 000-default \
    && chmod 600 /etc/msmtprc \
    && chown www-data:www-data /etc/msmtprc \
    && echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80

CMD ["apache2-foreground"]
