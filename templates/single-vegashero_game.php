<?php get_header();
get_template_part('index', 'banner');
?>



<!-- Blog & Sidebar Section -->
<div class="container">
	<div class="row blog_sidebar_section">
		<!--Blog-->
<div class="col-md-12">
	<?php
		if(have_posts())
		{
		while(have_posts()) { the_post();
	?>
	<div class="blog_detail_section">
			<?php if(has_post_thumbnail()): ?>
			<?php $defalt_arg =array('class' => "img-responsive"); ?>
			<div class="blog_post_img">
				<?php the_post_thumbnail('webriti_blog_thumb', $defalt_arg); ?>
			</div>
			<?php endif; ?>
			<div class="post_title_wrapper">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="post_detail">
						<?php 	$tag_list = get_the_tag_list();
							if(!empty($tag_list)) { ?>
					<div class="post_tags">
						<i class="fa fa-tags"></i><?php the_tags('', ',', '<br />'); ?>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="blog_post_content">
                        <iframe width="100%" height="600" frameborder="no" scrolling="no" align="center" src="http://bannercasino.winner.com/flash/55/casino_winner/launchcasino.html?advertisercode=petimi&banner=nasdwc&profile=nasdwc&creferer=admap:600AA043BC3217C42B64D302FEEADC05%3bchannel:SlotsMarvel%3bvar1:%3bvar10:%3bvar2:%3bvar3:%3bvar4:%3bvar5:%3bvar6:%3bvar7:%3bvar8:%3btab:%3bgclid:%3blp_id:52283939&game=avng&nolobby=1&mode=offline&language=en&"></iframe>

                <?php the_content(); ?>
				<div class="row">
				<div class="small-12 columns">
					<div class="game-cta"><a href="#" class="gamecta-btn">Play this game</a></div>
				</div>
			</div>
		</div>
	</div>
		<?php if( the_author_meta( 'description' )) :?>

	</div>
	<?php endif; ?>
	<?php } ?>
		<?php comments_template('',true); ?>
	<?php } ?>
	</div>
</div>
</div><!--Blog-->
<?php get_footer(); ?>
