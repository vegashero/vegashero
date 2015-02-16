# Vegas Hero 

This plugin will be installed by users and depends on the remote Vegas God plugin to populate with data

## Install

### After install manually run the cron. 

We make use of cron here so the inital game import can take place in the background. 

    wget -qO- http://vegashero.co/wp-cron.php?doing_wp_cron &> /dev/null

### MySQL Query to see pending cron operations
    
    SELECT * FROM `wp_options` WHERE `option_name` LIKE '%cron%'





