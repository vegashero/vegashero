<?php get_header();
$images = plugins_url('vegashero/templates/img/');
$config = new Vegashero_Config();
?>
<?php if(have_posts()): ?>
<?php while(have_posts()) : ?>
<?php 
the_post();
$game_id = get_post_meta(get_the_ID(), 'vegasgod_unique_game_id')[0];
$categories = wp_get_post_terms(get_the_ID(), $config->gameCategoryTaxonomy);
$operators = wp_get_post_terms(get_the_ID(), $config->gameOperatorTaxonomy);
$provider = wp_get_post_terms(get_the_ID(), $config->gameProviderTaxonomy)[0];
?>

    <h1><?= the_title(); ?></h1>

    <b>Game id:</b> <?=$game_id?><br>

    <b>Game operators:</b><br>
    <?php if(count($operators)): ?>
    <ul>
    <?php foreach ($operators as $operator): ?>
    <li><?= $operator->name ?></li>
    <?php endforeach ?>
    </ul>
    <?php endif ?>

    <b>Game categories:</b><br>
    <?php if(count($categories)): ?>
    <ul>
    <?php foreach ($categories as $category): ?>
    <li><?= $category->name ?></li>
    <?php endforeach ?>
    </ul>
    <?php endif ?>

    <b>Game provider: </b><?=$provider->name?><br><br>

    <iframe width="100%" height="600" frameborder="no" scrolling="no" align="center" src="http://bannercasino.winner.com/flash/55/casino_winner/launchcasino.html?advertisercode=petimi&banner=nasdwc&profile=nasdwc&creferer=admap:600AA043BC3217C42B64D302FEEADC05%3bchannel:SlotsMarvel%3bvar1:%3bvar10:%3bvar2:%3bvar3:%3bvar4:%3bvar5:%3bvar6:%3bvar7:%3bvar8:%3btab:%3bgclid:%3blp_id:52283939&game=avng&nolobby=1&mode=offline&language=en&"></iframe>

            this is the content
            <?php the_content(); ?>
            <table class="vh-casino-providers" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th width="30%">Casino</th>
                    <th width="40%">Bonus</th>
                    <th width="30%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="vh-casino"><img src="<?=$images?>green.png" width="180px"></td>
                    <td class="vh-bonus">500</td>
                    <td><a href="http://mrgreen.com" class="vh-playnow">Sign me up for <?=$operator?></a></td>
                </tr>
            </tbody>

<?php endwhile ?>
<?php endif ?>
