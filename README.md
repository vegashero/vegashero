# Vegas Hero 
![Test Status](https://github.com/vegashero/vegashero/actions/workflows/ci.yaml/badge.svg?branch=master)

Our Demo Games Import Plugin is a one-click solution for any Casino Affiliate that wants reliable demo slots games. Save yourself hundreds of hours of time and effort sourcing and adding demo games for your WordPress site.

## Quickstart

```bash
# run the container
VEGASHERO_ENV=development USER_ID=$(id -u) docker-compose up -d wordpress
# install wordpress
docker exec -u www-data vegashero-web-1 wp core install --url="localhost:4360" --title="Vegas Hero" --admin_user=vegashero --admin_email=support@vegashero.co
# update password
docker exec -u www-data vegashero-web-1 wp user update vegashero --user_pass="secret"
# update wordpress
docker exec -u www-data vegashero-web-1 wp core update
# install plugins
docker exec -u www-data vegashero-web-1 wp plugin install wordpress-importer polylang loco-translate --activate
# install languages
docker exec -u www-data vegashero-web-1 wp language core install af
# activate vegashero plugin
docker exec -u www-data vegashero-web-1 wp plugin activate vegashero
# set permalinks
docker exec -u www-data vegashero-web-1 wp rewrite structure --hard '/%postname%/'
# enable debugging
docker exec -u www-data vegashero-web-1 wp config set --raw WP_DEBUG true
docker exec -u www-data vegashero-web-1 wp config set --raw WP_DEBUG_LOG true
# view debug log
docker exec -u www-data vegashero-web-1 tail -f /var/www/html/wp-content/debug.log
```

Now navigate to [http://localhost:4360](http://localhost:4360)

## Plugin Development

### Unit Tests

Initial setup

```sh
docker-compose build --build-arg USER_ID=$(id -u) web
docker-compose up -d
docker exec -ti -u www-data vegashero-web-1 wp core install --url=localhost:4360 --title=VegasHero --admin_user=vegashero --admin_password=secret --admin_email=support@vegashero.co
docker exec -ti -u www-data vegashero-web-1 wp rewrite structure '/%postname%/'
#docker exec -ti -u www-data vegashero-web-1 wp scaffold plugin-tests vegashero
docker exec -ti -u www-data vegashero-web-1 wp-content/plugins/vegashero/bin/install-wp-tests.sh wordpress_test root '' db latest
```

Run the tests

```sh
docker exec -ti -u www-data vegashero-web-1 bash
cd wp-content/plugins/vegashero
#ln -s /var/www/html/wp-content/plugins/vegashero /tmp/wordpress/wp-content/plugins/vegashero # NB!
composer test
```

### WordPress Coding Standard checks

Initial setup

```sh
cd wp-content/plugins/vegashero
composer install
```

Run the checks

```sh
composer check
composer fix
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

## Release a new version

Update version in `composer.json` and `vegashero.php` files.

```sh
docker compose up -d 
docker exec -ti -u www-data:www-data -w /usr/local/src vegashero-web-1 bash
composer update --no-dev 
composer dumpautoload --optimize
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

