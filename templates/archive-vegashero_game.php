<?php get_header(); ?>
<!-- Page Section -->
<div class="page_mycarousel">
	<div class="container page_title_col">
		<div class="row">
			<div class="hc_page_header_area">						
				<h1><?php if ( is_day() ) : ?>
					<?php  _e( "Daily Archives:", 'corpbiz' ); echo (get_the_date()); ?>
					<?php elseif ( is_month() ) : ?>
					<?php  _e( "Monthly Archives:", 'corpbiz' ); echo (get_the_date( 'F Y' )); ?>
					<?php elseif ( is_year() ) : ?>
					<?php  _e( "Yearly Archives:", 'corpbiz' );  echo (get_the_date( 'Y' )); ?>
					<?php else : ?>
					<?php _e( "Blog Archives", 'corpbiz' ); ?>
					<?php endif; ?>
				</h1>
			</div>
		</div>
	</div>
</div>
<!-- /Page Section -->
<!-- Blog & Sidebar Section -->
<div class="container">
	<div class="row blog_sidebar_section">
		<!--Blog-->
		<div class="col-md-8">			
		<?php if ( have_posts() ) : 
			while(have_posts()): the_post(); ?>			



<?php
    // post meta data
    $post_meta = get_post_meta(get_the_ID(), 'game_meta', true);
    echo '<pre>';
    print_r($post_meta);
    echo '</pre>';
?>


			<div id="post-<?php the_ID(); ?>" <?php post_class('blog_section'); ?>>
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
				</div>	
			</div>
			<?php endwhile; ?>
			<div class="blog_pagination">					
				<?php if(get_previous_posts_link() ): ?>
				<?php previous_posts_link(); ?>
				<?php endif; ?>					
				<?php if ( get_next_posts_link() ): ?>
				<?php next_posts_link(); ?>
				<?php endif; ?>
			</div>
			<?php if(wp_link_pages()) { wp_link_pages();  } ?>
			
		<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</div>	
<?php get_footer(); ?>
