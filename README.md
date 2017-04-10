# Vegas Hero 
This plugin will be installed by users and depends on the remote Vegas God plugin to populate with data

Localhost license key

```
adc88446b4e3476a04091835fec15e08
```

## Unit Tests NB!!!
```bash
$ sudo apt install php-xml php-json php-mbstring
$ composer install
$ vendor/bin/phpunit --debug  tests
```

## Quickstart
```bash
$ USER_NAME=$USER USER_ID=$(id -u) docker-compose up --build
```

Now navigate to [http://localhost:8080](http://localhost:8080)

## Snippets

### Manually run Wordpress cron
```bash
$ wget -qO- http://localhost:8080/wp-cron.php?doing_wp_cron &> /dev/null
```

### MySQL Query to see pending cron operations
```sql
> SELECT * FROM `wp_options` WHERE `option_name` LIKE '%cron%'
```

## References
* [wp_mock](https://github.com/10up/wp_mock)
* [Unit Testing PHP](https://phpunit.de/)
* [Cucumber PHP](http://behat.org/en/latest/)
* [Selenium Web Driver PHP](https://github.com/facebook/php-webdriver)
    





