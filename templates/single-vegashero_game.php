<?php get_header();
get_template_part('index', 'banner');
?>



<!-- Blog & Sidebar Section -->
<div class="container">
	<div class="row blog_sidebar_section">
		<!--Blog-->
<div class="col-md-8">
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
					<a href="<?php echo get_month_link(get_post_time('Y'),get_post_time('m')); ?>"><i class="fa fa-calendar"></i> <?php echo get_the_date('M j, Y'); ?> </a>
					<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><i class="fa fa-user"></i> <?php _e('Posted by : &nbsp;', 'corpbiz'); ?> <?php the_author(); ?> </a>
					<a href="<?php comments_link(); ?>"><i class="fa fa-comments"></i> <?php comments_number('No Comments', '1 Comment','% Comments'); ?></a>
					<?php 	$tag_list = get_the_tag_list();
							if(!empty($tag_list)) { ?>
					<div class="post_tags">
						<i class="fa fa-tags"></i><?php the_tags('', ',', '<br />'); ?>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="blog_post_content">
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
