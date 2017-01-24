FROM php:5.4-apache
EXPOSE 80
ARG USER_ID
ARG USER_NAME
RUN apt-get update && apt-get install -y vim
RUN a2enmod rewrite
RUN docker-php-ext-install mysqli
WORKDIR /tmp
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp
WORKDIR /var/www/html
RUN chmod g+s /var/www/html
RUN useradd -u $USER_ID $USER_NAME -m
RUN usermod -a -G www-data $USER_NAME
RUN chmod 775 -R /var/www/html
RUN chown -R $USER_NAME:www-data /var/www/html
USER $USER_NAME
RUN wp core download 
ADD .htaccess /var/www/html/.htaccess
ADD wp-config.php /var/www/html/wp-config.php
RUN mkdir -p /var/www/html/wp-content/plugins/vegashero
#ADD . /var/www/html/wp-content/plugins/vegashero/
RUN mkdir -p /var/www/html/wp-content/themes/newspaper
ADD tests/themes/newspaper /var/www/html/wp-content/themes/newspaper
USER root
RUN chown -R $USER_NAME:www-data /var/www/html
VOLUME /var/www/html/wp-content/plugins/vegashero


