<?php get_header();
$plugins_url = plugin_dir_url( $file );
?>
<!-- Page Section -->
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="<?php echo $plugins_url ?>vegashero/templates/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="<?php echo $plugins_url ?>vegashero/templates/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $plugins_url ?>vegashero/templates/css/bootstrap-theme.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $plugins_url ?>vegashero/templates/css/dropdown.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $plugins_url ?>vegashero/templates/css/vh-lobby.css" type="text/css" />

<div class="app-content">
	<!-- main -->
	<div class="col wrapper-lg">
		<div class="row row-sm">


			<div class="row vh-cats">
				<div class="col-md-12">
					<nav class="navbar">
						<div class="collapse navbar-collapse js-navbar-collapse">
							<ul class="btn-group">
								<li class="dropdown mega-dropdown">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										Categories <span class="caret"></span>
									</button>


									<ul class="dropdown-menu mega-dropdown-menu row">

										<li class="col-sm-3">
											<ul>
												<li><a href="#">Magicslots</a></li>
												<li><a href="#">Mahjong</a></li>
												<li><a href="#">Mega ball</a></li>
												<li><a href="#">Multi line</a></li>
												<li><a href="#">Multi line 4 spaces</a></li>
											</ul>
										</li>
										<li class="col-sm-3">
											<ul>
												<li><a href="#">Magicslots</a></li>
												<li><a href="#">Mahjong</a></li>
												<li><a href="#">Mega ball</a></li>
												<li><a href="#">Multi line</a></li>
												<li><a href="#">Multi line 4 spaces</a></li>
											</ul>
										</li>
										<li class="col-sm-3">
											<ul>
												<li><a href="#">Magicslots</a></li>
												<li><a href="#">Mahjong</a></li>
												<li><a href="#">Mega ball</a></li>
												<li><a href="#">Multi line</a></li>
												<li><a href="#">Multi line 4 spaces</a></li>
											</ul>
										</li>
										<li class="col-sm-3">
											<ul>
												<li><a href="#">Magicslots</a></li>
												<li><a href="#">Mahjong</a></li>
												<li><a href="#">Mega ball</a></li>
												<li><a href="#">Multi line</a></li>
												<li><a href="#">Multi line 4 spaces</a></li>
											</ul>
										</li>
									</ul>

								</li>
							</ul>

						</div><!-- /.nav-collapse -->
					</nav>
				</div>
			</div>
				<?php if ( have_posts() ) :
					while(have_posts()): the_post();
					$post_meta = get_post_meta(get_the_ID(), 'game_meta', true);
					?>
				<div class="col-xs-6 col-sm-4 col-md-4 col-lg-1-3">
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
