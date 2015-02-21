<?php get_header();
$images = plugins_url('vegashero/templates/img/');
?>
<?php
		if(have_posts())
		{
		while(have_posts()) { the_post();
	?>

	<h1><?= the_title(); ?></h1>

    <iframe width="100%" height="600" frameborder="no" scrolling="no" align="center" src="http://bannercasino.winner.com/flash/55/casino_winner/launchcasino.html?advertisercode=petimi&banner=nasdwc&profile=nasdwc&creferer=admap:600AA043BC3217C42B64D302FEEADC05%3bchannel:SlotsMarvel%3bvar1:%3bvar10:%3bvar2:%3bvar3:%3bvar4:%3bvar5:%3bvar6:%3bvar7:%3bvar8:%3btab:%3bgclid:%3blp_id:52283939&game=avng&nolobby=1&mode=offline&language=en&"></iframe>

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
					<td class="vh-casino"><img src="<?=$images?>mrgreen.png" width="180px"></td>
					<td class="vh-bonus">500</td>
					<td><a href="http://mrgreen.com" class="vh-playnow">Sign me up</a></td>
				</tr>
			</tbody>



	<?php
		}
	}
	?>
