[![CircleCI](https://circleci.com/bb/vegashero/vegashero/tree/master.svg?style=svg&circle-token=83f342ba2f820a1398cf694200e1405456e527c7)](https://circleci.com/bb/vegashero/vegashero/tree/master)

# Vegas Hero 
This plugin will be installed by users and depends on the remote Vegas God plugin to populate with data

Localhost license key

```
adc88446b4e3476a04091835fec15e08
```

## Deps

```sh
composer install
composer dump-autoload
```

## Quickstart

```bash
# run the container
VEGASHERO_ENV=production USER_NAME=$USER USER_ID=$(id -u) docker-compose up php-apache
# install wordpress
docker exec -u $USER vegashero_php-apache_1 wp core install --url="localhost:8080" --title="Vegas Hero" --admin_user=vegashero --admin_email=support@vegashero.co
# update password
docker exec -u $USER vegashero_php-apache_1 wp user update vegashero --user_pass="secret"
# update wordpress
docker exec -u $USER vegashero_php-apache_1 wp core update
# install plugins
docker exec -u $USER vegashero_php-apache_1 wp plugin install wordpress-importer --activate
```

Now navigate to [http://localhost:8080](http://localhost:8080)

## Theme Development

Create base image containing Wordpress and container

```
docker build --build-arg USER_NAME=$USER --build-arg USER_ID=$(id -u) -t vegashero_theme_base:latest . -f Dockerfile.theme
```

In your theme Dockerfile 

```
FROM vegashero_theme_base:latest
```

See crypto theme for example

## Plugin Development

### Tab1

Runs the required Docker containers

```sh
USER_NAME=$USER USER_ID=$(id -u) docker-compose up tests
```

### Tab2

Edit code on your local machine

## Testing

Can't install Wordpress in the Dockerfile as the db container isn't ready yet. For now I'm running the commands manually, but could also put them in a shell script file and add it to ENTRYPOINT.

### Setup

```sh
USER_NAME=$USER USER_ID=$(id -u) docker-compose up tests
docker exec -ti -u $USER vegashero_tests_1 wp core install --url=localhost:8080 --title=VegasHero --admin_user=vegashero --admin_password=secret --admin_email=support@vegashero.co
docker exec -ti -u $USER vegashero_tests_1 wp rewrite structure '/%postname%/'
#docker exec -ti -u $USER vegashero_tests_1 wp scaffold plugin-tests vegashero
docker exec -ti -u $USER vegashero_tests_1 wp-content/plugins/vegashero/bin/install-wp-tests.sh wordpress_test root '' mysql latest
```

### Run

```sh
docker exec -ti -u $USER vegashero_tests_1 bash
stty rows 41 columns 141
cd wp-content/plugins/vegashero
ln -s /var/www/html/wp-content/plugins/vegashero /tmp/wordpress/wp-content/plugins/vegashero # NB!
composer test
```

## EDD Plugin Updater

### Clearing the cache
To clear the cache add the following to the EDD_SL_Plugin_Updater.php file.

At the top add
```php
set_site_transient( 'update_plugins', null ); //added this
```

Towards the end at line 446 look for a function called *get_cached_verion_info()* and add *return false*
```php
public function get_cached_version_info( $cache_key = '' ) {

    return false; //added this

    if( empty( $cache_key ) ) {
        $cache_key = $this->cache_key;
    }

    $cache = get_option( $cache_key );

    if( empty( $cache['timeout'] ) || current_time( 'timestamp' ) > $cache['timeout'] ) {
        return false; // Cache is expired
    }

    return json_decode( $cache['value'] );

}
```

Remember to remove before commiting.

### Updating the class
When updating the class remember to *sslverify* to *true* on lines 354 and 416.
Also make sure the class name is renamed from the default *EDD_SL_Plugin_Updater* to *VH_EDD_SL_Plugin_Updater*

```php
$request = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => true, 'body' => $api_params ) );
```

## Snippets

### Optimize MySQL

```bash
apt install mysqltuner
mysqltuner
```

#### Optimize Tables

```bash
mysqlcheck -o --all-databases
```

###

Download games via CURL call
```sh
curl -lv -X GET "https://vegasgod.com/wp-json/vegasgod/games/provider/elk?license=adc88446b4e3476a04091835fec15e08&referer=http://localhost"
```

Adding SSL certificates

```sh
# production
certbot certonly --webroot --webroot-path /var/www/vegashero.co/public_html --renew-by-default --email support@vegashero.co --text --agree-tos --cert-name vegashero.co -d vegashero.co,demo.vegashero.co,slot.vegashero.co,www.vegashero.co,sports.vegashero.co,crypto.vegashero.co 
# staging
certbot certonly --webroot --webroot-path /var/www/staging.vegashero.co/public_html --renew-by-default --email support@vegashero.co --text --agree-tos --cert-name staging.vegashero.co -d staging.vegashero.co
```

Renewing SSL certificates. 

```sh
certbot renew
```

## References
* [How to Internationalize Your Plugin](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/)
* [PHP DocBlocks](https://phpdoc.org/docs/latest/guides/docblocks.html)
* [wp_mock](https://github.com/10up/wp_mock)
* [Unit Testing PHP](https://phpunit.de/)
* [Cucumber PHP](http://behat.org/en/latest/)
* [Selenium Web Driver PHP](https://github.com/facebook/php-webdriver)
* [Factories for Wordpress unit testing](https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory)
* [Unit Testing WordPress Plugins with PHPUnit](https://premium.wpmudev.org/blog/unit-testing-wordpress-plugins-phpunit/)
* [Writing WordPress Plugin Unit Tests](https://codesymphony.co/writing-wordpress-plugin-unit-tests/)
* [Plugin Unit Tests ](https://make.wordpress.org/cli/handbook/plugin-unit-tests)

    





