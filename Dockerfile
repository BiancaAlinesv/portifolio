FROM php:8.4-apache

COPY docker/apache/custom.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite && a2dissite 000-default && a2ensite 000-default

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html && find /var/www/html -type d -exec chmod 755 {} \; && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80

CMD ["apache2-foreground"]
