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
USER_NAME=$USER USER_ID=$(id -u) docker-compose up php5.4-apache 
```

## Update wordpress

```sh
docker exec -u $USER vegashero_wordpress_1 wp core update
```

## Installing plugins
```sh
$ docker exec --user $USER yogahomecapetown_wordpress_1 wp plugin install mailchimp-for-wp jetpack imsanity wp-instagram-widget wordpress-importer --activate

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

### Setup

* [Factories for Wordpress unit testing](https://core.trac.wordpress.org/browser/trunk/tests/phpunit/includes/factory)
* [Unit Testing WordPress Plugins with PHPUnit](https://premium.wpmudev.org/blog/unit-testing-wordpress-plugins-phpunit/)
* [Writing WordPress Plugin Unit Tests](https://codesymphony.co/writing-wordpress-plugin-unit-tests/)
* [Plugin Unit Tests ](https://make.wordpress.org/cli/handbook/plugin-unit-tests)

```sh
docker exec -ti -u $USER vegashero_tests_1 wp scaffold plugin-tests vegashero
docker exec -ti -u $USER vegashero_tests_1 wp-content/plugins/vegashero/bin/install-wp-tests.sh wordpress_test root '' mysql latest
```

### Run

```sh
docker exec -ti -u $USER vegashero_tests_1 bash
stty rows 41 columns 141
cd wp-content/plugins/vegashero
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

###

Download games via CURL call
```sh
curl -lv -X GET "https://vegasgod.com/wp-json/vegasgod/games/provider/elk?license=adc88446b4e3476a04091835fec15e08&referer=http://localhost"
```

Adding SSL certificates

```sh
certbot certonly --cert-name vegashero.co -d vegashero.co,demo.vegashero.co,slot.vegashero.co,staging.vegashero.co,www.vegashero.co,sports.vegashero.co
```

Renewing SSL certificates. *NB* Cloudlfare root and subdomains should be set to DNS only. 

```sh
certbot renew
```

### Manually run Wordpress cron
```bash
$ firefox http://localhost:8080/wp-cron.php?doing_wp_cron
```

### MySQL Query to see pending cron operations
```sql
> SELECT * FROM `wp_options` WHERE `option_name` LIKE '%cron%'
```

## References
* [PHP DocBlocks](https://phpdoc.org/docs/latest/guides/docblocks.html)
* [wp_mock](https://github.com/10up/wp_mock)
* [Unit Testing PHP](https://phpunit.de/)
* [Cucumber PHP](http://behat.org/en/latest/)
* [Selenium Web Driver PHP](https://github.com/facebook/php-webdriver)
    





