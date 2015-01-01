<?php get_header();
$plugins_url = plugin_dir_url( $file );
?>
<!-- Page Section -->
<link rel="stylesheet" href="<?php echo $plugins_url ?>vegashero/templates/css/bootstrap.css" type="text/css" />

<link rel="stylesheet" href="<?php echo $plugins_url ?>vegashero/templates/css/vh-lobby.css" type="text/css" />

<div class="app-content">
	<!-- main -->
	<div class="col wrapper-lg">
		<div class="row row-sm">


				<?php if ( have_posts() ) :
					while(have_posts()): the_post();
					$post_meta = get_post_meta(get_the_ID(), 'game_meta', true);
					?>
				<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2-4">
					<div class="item">
						<div class="pos-rlt">
							<div class="item-overlay">
								<div class="center text-center">
									<a><i class="vh-play-fun">Play for fun</i></a><br>
									<a><i class="vh-play-real">Play for real</i></a>
								</div>
							</div>
							<a ><img src="http://placehold.it/350x250" alt="" class="img-full r r-2x"></a>
						</div>
						<div class="padder-v">
							<a href="<?php the_permalink(); ?>" class="text-ellipsis"><?php the_title(); ?></a>
							<a class="text-ellipsis text-xs text-muted">Category name</a>
						</div>
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
		</div>

<?php get_footer(); ?>
