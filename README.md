# Vegas Hero Wordpress Plugin
Install into /wp-content/plugins/vegashero

## load csv into mysql from tmp dir
LOAD DATA INFILE '/tmp/vegashero_games.csv' INTO TABLE games FIELDS TERMINDATED BY ',' LINES TERMINATED BY '\n' (name, provider, category, ref);

gameId, pid, bid and languageCode will be stored in settings on user side

## US

## Users
SETTINGS
Vegas Hero
    MrGreen
        bid
        pid
        active
    William Hill
        affiliate_id








