FROM php:5.4-apache
EXPOSE 80
ARG USER_ID
ARG USER_NAME
RUN useradd -u $USER_ID $USER_NAME -m
RUN usermod -a -G www-data $USER_NAME
RUN a2enmod rewrite
#RUN apt -qq update && apt install -y vim git php-xml php-json php-mbstring
RUN apt -qq update && apt install -y vim 
RUN docker-php-ext-install mysqli
WORKDIR /tmp
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
#RUN chmod g+s /var/www/html
RUN chmod 2775 -R /var/www/html
#RUN chown -R $USER_NAME:www-data /var/www/html

USER $USER_NAME
RUN newgrp www-data
RUN wp core download 
ADD .htaccess /var/www/html/.htaccess
ADD wp-config.php /var/www/html/wp-config.php
#ADD composer.json /var/www/html/composer.json
#RUN composer install 

#USER root
#RUN chown $USER_NAME:www-data .htaccess wp-config.php composer.json


