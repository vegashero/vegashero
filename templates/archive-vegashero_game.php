<?php get_header();
$images = plugins_url('vegasgod/images');
$config = new Vegashero_Config();
?>
<!-- Page Section -->
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

<div class="vh-app-content">
	<!-- main -->
	<div class="vh-col vh-wrapper-lg">
		<div class="vh-row vh-row-sm">

                <?php if ( have_posts() ) : ?>
                <?php while(have_posts()): ?>
                <?php 
                    the_post();
                    $post_meta = get_post_meta(get_the_ID(), $config->metaKey, true);
                    $categories = wp_get_post_terms(get_the_ID(), $config->gameCategoryTaxonomy);
                    $operators = wp_get_post_terms(get_the_ID(), $config->gameOperatorTaxonomy);
                    $provider = wp_get_post_terms(get_the_ID(), $config->gameProviderTaxonomy)[0];
                    $image_url = sprintf("%s/%s/%s/", $images, $provider->name, sanitize_title($post->post_title));
					?>
				<div class="vh-col-xs-6 vh-col-sm-4 vh-col-md-4">
					<div class="vh-item">
						<div class="vh-item-overlay">
							<a href="<?php the_permalink(); ?>" class="vh-play-fun" >Play Now</a>
                            <img src="<?=$image_url?>cover.jpg" alt="" class="img-hover">
							<a href="<?php the_permalink(); ?>" class="vh-game-title"><?php the_title(); ?></a>
                            <p class="vh-game-cat"><?=$categories[0]->name?></p>
						</div>
                        <img src="<?=$image_url?>cover.jpg" alt="" class="img-full">
						<a href="<?php the_permalink(); ?>" class="vh-game-title"><?php the_title(); ?></a>
						<p class="vh-game-cat"><?=$categories[0]->name?></p>
					</div>

				</div>

					<?php endwhile; ?>
					<div class="vh-row">
						<div class="vh-col-md-12">
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
