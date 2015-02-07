single custom post type
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
			<div class="row">
		<div class="small-12 columns">
			<div class="game-cta"><a href="#" class="gamecta-btn">Play this game</a></div>
		</div>
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
			</div>
	</div>
		<?php if( the_author_meta( 'description' )) :?>
	<div class="blog_author">
	<div class="media">
		<div class="pull-left">
		<?php echo get_avatar( get_the_author_meta( 'ID') , 94); ?>

		</div>

		<div class="media-body">
			<h6> <?php the_author(); ?> <span> <?php $user = new WP_User( get_the_author_meta( 'ID' ) ); echo $user->roles[0];?> </span></h6>
			<p><?php the_author_meta( 'description' ); //the_author_description(); ?> </p>

			<ul class="blog_author_social">
				<?php
				$google_profile = get_the_author_meta( 'google_profile' );
				if ( $google_profile && $google_profile != '' ) {
					echo '<li class="googleplus"><a href="' . esc_url($google_profile) . '" rel="author"><i class="fa fa-google-plus"></i></a></li>';
				}

				$twitter_profile = get_the_author_meta( 'twitter_profile' );
				if ( $twitter_profile && $twitter_profile != '' ) {
					echo '<li class="twitter"><a href="' . esc_url($twitter_profile) . '"><i class="fa fa-twitter"></i></a></li>';
				}

				$facebook_profile = get_the_author_meta( 'facebook_profile' );
				if ( $facebook_profile && $facebook_profile != '' ) {
					echo '<li class="facebook"><a href="' . esc_url($facebook_profile) . '"><i class="fa fa-facebook"></i></a></li>';
				}

				$linkedin_profile = get_the_author_meta( 'linkedin_profile' );
				if ( $linkedin_profile && $linkedin_profile != '' ) {
					   echo '<li class="linkedin"><a href="' . esc_url($linkedin_profile) . '"><i class="fa fa-linkedin"></i></a></li>';
				}
				$youtube_profile = get_the_author_meta( 'youtube_profile' );
				if ( $youtube_profile && $youtube_profile != '' ) {
					   echo '<li class="youtube"><a href="' . esc_url($youtube_profile) . '"><i class="fa fa-youtube-play"></i></a></li>';
				}
				?>
			</ul>

		</div>

	</div>
	</div>
	<?php endif; ?>
	<?php } ?>
		<?php comments_template('',true); ?>
	<?php } ?>
	</div>
	<?php get_sidebar(); ?>
</div>
</div><!--Blog-->
<?php get_footer(); ?>
