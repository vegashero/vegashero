<?php get_header();
$images = plugins_url('vegasgod/images');
?>
<!-- Page Section -->
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

<div class="app-content">
	<!-- main -->
	<div class="col wrapper-lg">
		<div class="row row-sm">

				<?php if ( have_posts() ) :
					while(have_posts()): the_post();
					$post_meta = get_post_meta(get_the_ID(), 'game_meta', true);
                    $image_url = sprintf("%s/%s/%s/", $images, $post_meta['provider'], sanitize_title($post->post_title)) ;
					?>
				<div class="col-xs-6 col-sm-4 col-md-4">
					<div class="item">
						<div class="item-overlay">
							<a href="<?php the_permalink(); ?>" class="vh-play-fun" >Play Now</a>
                            <img src="<?=$image_url?>cover.jpg" alt="" class="img-hover">
							<a href="<?php the_permalink(); ?>" class="vh-game-title"><?php the_title(); ?></a>
							<p class="vh-game-cat">Category name</p>
						</div>
                        <img src="<?=$image_url?>cover.jpg" alt="" class="img-full r r-2x">
						<a href="<?php the_permalink(); ?>" class="vh-game-title"><?php the_title(); ?></a>
						<p class="vh-game-cat">Category name</p>
					</div>

				</div>

					<?php endwhile; ?>
					<div class="row">
						<div class="col-md-12">
						<?php if(get_previous_posts_link() ): ?>
							<?php previous_posts_link(); ?>
						<?php endif; ?>
						<?php if ( get_next_posts_link() ): ?>
							<?php next_posts_link(); ?>
						<?php endif; ?>
					</div>
				</div>
					<?php if(wp_link_pages()) { wp_link_pages();  } ?>

				<?php endif; ?>
			</div>
		</div>

<?php get_footer(); ?>
