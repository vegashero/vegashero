FROM php:8-apache

EXPOSE 80

ARG USER_ID
ARG DB_NAME
ARG DB_USER
ARG DB_PASSWORD
ARG DB_HOST

RUN usermod -u $USER_ID www-data
RUN groupmod -g $USER_ID www-data 
RUN a2enmod rewrite

RUN apt -qq update && apt install -y vim unzip libzip-dev default-mysql-client wget subversion git libonig-dev 
RUN docker-php-ext-install mysqli mbstring pcntl zip
WORKDIR /tmp

# install wp-cli
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp

# install composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# install dockerize
RUN curl -sfL $(curl -s https://api.github.com/repos/powerman/dockerize/releases/latest | grep -i /dockerize-$(uname -s)-$(uname -m)\" | cut -d\" -f4) | install /dev/stdin /usr/local/bin/dockerize

RUN chown -R www-data:www-data /var/www

WORKDIR /var/www/html

USER www-data

RUN wp core download 
ADD .htaccess /var/www/html/.htaccess
RUN wp config create --dbname=$DB_NAME --dbuser=$DB_USER --dbpass=$DB_PASSWORD --dbhost=$DB_HOST --force --skip-check
RUN ln -s /usr/local/src /var/www/html/wp-content/plugins/vegashero

USER root

CMD dockerize -timeout 300s -wait tcp://db:3306 apache2-foreground
