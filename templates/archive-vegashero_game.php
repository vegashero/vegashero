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

			<nav class="navbar navbar-default">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Categories <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="#">3 reels 1 index</a></li>
									<li><a href="#">3 reels 3 indices</a></li>
									<li><a href="#">5 reels 1 index</a></li>
									<li><a href="#">8 lines</a></li>
									<li><a href="#">9 reels 1 index</a></li>
									<li class="divider"></li>
									<li><a href="#">Arcade</a></li>
									<li><a href="#">Baccarat</a></li>
									<li><a href="#">Binary options</a></li>
									<li><a href="#">Bingo</a></li>
									<li class="divider"></li>
									<li><a href="#">One more separated link</a></li>
								</ul>
							</li>
						</ul>
						<form class="navbar-form navbar-right" role="search">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Search">
							</div>
							<button type="submit" class="btn btn-default">Submit</button>
						</form>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>
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
