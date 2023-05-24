FROM debian:latest

RUN apt-get update && \
    apt-get install -y \
        apache2 \
        mysql-server \
        php \
        php-mysql \
        php-gd \
        php-xml \
        php-mbstring \
        php-zip \
        && \
    rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
COPY apache.conf /etc/apache2/sites-enabled/000-default.conf
COPY php.ini /etc/php/7.4/apache2/php.ini

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
