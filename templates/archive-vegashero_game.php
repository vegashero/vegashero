<?php get_header();
$plugins_url = plugin_dir_url( $file );
?>
<!-- Page Section -->
<link rel="stylesheet" href="<?php echo $plugins_url ?>vegashero/templates/css/bootstrap.css" type="text/css" />

<link rel="stylesheet" href="<?php echo $plugins_url ?>vegashero/templates/css/vh-lobby.css" type="text/css" />
		<?php if ( have_posts() ) :
			while(have_posts()): the_post(); ?>



<?php
    // post meta data
    $post_meta = get_post_meta(get_the_ID(), 'game_meta', true);
    // echo '<pre>';
    // print_r($post_meta);
    // echo '</pre>';
		// echo $post_meta['ref'];
?>
<div class="app-content">
	<!-- main -->
	<div class="col wrapper-lg">
		<h3 class="font-thin m-t-n-xs m-b">Lobby Games</h3>
		<div class="row row-sm">
			<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2-4">
				<div class="item">
					<div class="pos-rlt">
						<div class="top">
							<span class="badge bg-info">04:10</span>
						</div>
						<div class="item-overlay">
							<div class="center text-center">
								<a><i class="vh-play-fun">Play for fun</i></a><br>
								<a><i class="vh-play-real">Play for real</i></a>
							</div>
						</div>
						<a ><img src=""<?=$post_meta['thumb_image']?> alt="" class="img-full r r-2x"></a>
					</div>
					<div class="padder-v">
						<a href="<?php the_permalink(); ?>" class="text-ellipsis"><?php the_title(); ?></a>
						<a class="text-ellipsis text-xs text-muted">Category name</a>
					</div>
				</div>
			</div>
		</div>



			<div id="post-<?php the_ID(); ?>" <?php post_class('blog_section'); ?>>
				<?php if(has_post_thumbnail()): ?>
				<?php $defalt_arg =array('class' => "img-responsive"); ?>
				<div class="blog_post_img">
					<?php the_post_thumbnail('webriti_blog_thumb', $defalt_arg); ?>

				</div>
				<?php endif; ?>

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
