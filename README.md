# Vegas Hero 

This plugin will be installed by users and depends on the remote Vegas God plugin to populate with data

## Quickstart
```bash
$ docker-compose up --build
```

Now navigate to [http://localhost:8080](http://localhost:8080)

## Snippets


### Manually run Wordpress cron
```bash
$ wget -qO- http://vegashero.co/wp-cron.php?doing_wp_cron &> /dev/null
```

### MySQL Query to see pending cron operations
```sql
> SELECT * FROM `wp_options` WHERE `option_name` LIKE '%cron%'
```

## References
* [Unit Testing PHP](https://phpunit.de/)
* [Cucumber PHP](http://behat.org/en/latest/)
* [Selenium Web Driver PHP](https://github.com/facebook/php-webdriver)
    





