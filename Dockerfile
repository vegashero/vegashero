FROM php:5.4-apache
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli
WORKDIR /tmp
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp
WORKDIR /var/www/html
RUN wp core download --allow-root
RUN mkdir /var/www/html/wp-content/plugins/vegashero
ADD .htaccess /var/www/html/.htaccess
ADD wp-config.php /var/www/html/wp-config.php
ADD tests/themes/made /var/www/html/wp-content/themes/made
ADD . /var/www/html/wp-content/plugins/vegashero/


