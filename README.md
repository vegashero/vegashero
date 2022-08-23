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
VEGASHERO_ENV=development USER_ID=$(id -u) docker-compose up -d wordpress
# install wordpress
docker exec -u www-data vegashero_wordpress_1 wp core install --url="localhost:4360" --title="Vegas Hero" --admin_user=vegashero --admin_email=support@vegashero.co
# update password
docker exec -u www-data vegashero_wordpress_1 wp user update vegashero --user_pass="secret"
# update wordpress
docker exec -u www-data vegashero_wordpress_1 wp core update
# install plugins
docker exec -u www-data vegashero_wordpress_1 wp plugin install wordpress-importer polylang loco-translate --activate
# install languages
docker exec -u www-data vegashero_wordpress_1 wp language core install af
# activate vegashero plugin
docker exec -u www-data vegashero_wordpress_1 wp plugin activate vegashero
# set permalinks
docker exec -u www-data vegashero_wordpress_1 wp rewrite structure --hard '/%postname%/'
# enable debugging
docker exec -u www-data vegashero_wordpress_1 wp config set --raw WP_DEBUG true
docker exec -u www-data vegashero_wordpress_1 wp config set --raw WP_DEBUG_LOG true
# view debug log
docker exec -u www-data vegashero_wordpress_1 tail -f /var/www/html/wp-content/debug.log
```

Now navigate to [http://localhost:4360](http://localhost:4360)

## Theme Development

Create base image containing Wordpress and container

```
docker build --build-arg USER_ID=$(id -u) -t vegashero_theme_base:latest . -f Dockerfile.theme
```

In your theme Dockerfile 

```
FROM vegashero_theme_base:latest
```

See crypto theme for example

## Plugin Development

## Unit Tests

Initial setup

```sh
docker-compose build --build-arg USER_ID=$(id -u) tests
docker-compose up tests
docker exec -ti -u www-data vegashero_tests_1 wp core install --url=localhost:4360 --title=VegasHero --admin_user=vegashero --admin_password=secret --admin_email=support@vegashero.co
docker exec -ti -u www-data vegashero_tests_1 wp rewrite structure '/%postname%/'
#docker exec -ti -u www-data vegashero_tests_1 wp scaffold plugin-tests vegashero
docker exec -ti -u www-data vegashero_tests_1 wp-content/plugins/vegashero/bin/install-wp-tests.sh wordpress_test root '' db latest
```

Running the tests

```sh
docker exec -ti -u www-data vegashero_tests_1 bash
cd wp-content/plugins/vegashero
ln -s /var/www/html/wp-content/plugins/vegashero /tmp/wordpress/wp-content/plugins/vegashero # NB!
./vendor/bin/phpcs --config-set prefixes vegashero
./vendor/bin/phpcs --config-set text_domain vegashero
composer test
```

## Coding Standards Checker

Initial setup

```sh
cd wp-content/plugins/vegashero
./vendor/bin/phpcs --config-set installed_paths /opt/wpcs
```

Running the checks

```sh
composer check
composer fix
```

## Deployment

```sh
# staging
rsync -rhvzpog --chown=www-data:www-data ./ root@206.81.25.235:/var/www/staging.vegashero.co/public_html/wp-content/plugins/vegashero/ --delete --exclude=.git --exclude=tests --exclude=vendor
# production
rsync -rhvzpog --chown=www-data:www-data ./ root@206.81.25.235:/var/www/vegashero.co/public_html/wp-content/plugins/vegashero/ --delete --exclude=.git --exclude=tests --exclude=vendor
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

Since staging.vegashero.co is protected by Basic Auth make sure to add a .htaccess files to the .well-known/acme-challenge directory with the contents:

```
Satisfy any
```

Renewing SSL certificates. 

```sh
certbot renew
```

## i18n

Generate `.pot` files.

```sh
wp i18n make-pot path/to/your-theme-directory
```

Create `.po` file from `.pot` file.

```sh
cp vegashero.pot vegashero-af_ZA.po
```

Translate the `.po` file.

```sh
vim vegashero-af_ZA.po
```

Generate `.mo` files from `.po` files.

```sh
cd languages
msgfmt -cv -o vegashero-af.mo vegashero-af.po
```

Validate `.mo` file

```sh
msgunfmt vegashero-af.mo
```

Update the `.po` 

```sh
wp i18n make-pot path/to/your-theme-directory
msgmerge -vU vegashero-af_ZA.po vegashero.pot
```

Install languages

```sh
wp language core install af
```

List plugin languages

```sh
wp language plugin list vegashero
```

Switch language

```sh
wp site switch-language af
```

## References
- [How To Internationalize Your WordPress Website](https://www.smashingmagazine.com/2018/01/internationalize-your-wordpress-website/)
- [Internationalization in WordPress 5.0](https://pascalbirchler.com/internationalization-in-wordpress-5-0/)
- [How to Internationalize Your Plugin](https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/)
- [PHP DocBlocks](https://phpdoc.org/docs/latest/guides/docblocks.html)
- [wp_mock](https://github.com/10up/wp_mock)
- [Unit Testing PHP](https://phpunit.de/)
- [Cucumber PHP](http://behat.org/en/latest/)
- [Selenium Web Driver PHP](https://github.com/facebook/php-webdriver)
- [Factories for Wordpress unit testing](https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory)
- [Unit Testing WordPress Plugins with PHPUnit](https://premium.wpmudev.org/blog/unit-testing-wordpress-plugins-phpunit/)
- [Writing WordPress Plugin Unit Tests](https://codesymphony.co/writing-wordpress-plugin-unit-tests/)
- [Plugin Unit Tests ](https://make.wordpress.org/cli/handbook/plugin-unit-tests)
- [](https://make.wordpress.org/core/2021/09/27/changes-to-the-wordpress-core-php-test-suite/)

    





