<?php get_header();
get_template_part('index', 'banner');
?>

<div id="slot-modal" class="reveal-modal layout-slot-review">
	<?php
		if(have_posts())
		{
		while(have_posts()) { the_post();
	?>
	<div class="row">
		<div class="small-12 columns"><h1><?php the_title(); ?></h1></div>
	</div>
	<div class="row">
		<div class="small-12 columns">
			<div id="modal-embed" class="has-top-overlay" style="display: block;">
			<iframe width="100%" height="600" frameborder="no" scrolling="no" align="center" src="http://bannercasino.winner.com/flash/55/casino_winner/launchcasino.html?advertisercode=petimi&banner=nasdwc&profile=nasdwc&creferer=admap:600AA043BC3217C42B64D302FEEADC05%3bchannel:SlotsMarvel%3bvar1:%3bvar10:%3bvar2:%3bvar3:%3bvar4:%3bvar5:%3bvar6:%3bvar7:%3bvar8:%3btab:%3bgclid:%3blp_id:52283939&game=avng&nolobby=1&mode=offline&language=en&"></iframe>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<div class="game-cta"><a href="#" class="gamecta-btn">Play this game</a></div>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<h2>Top #10 casinos where you can find this slot game</h2>

			<strong class="block margin-bottom">All casinos are sorted by their user rating scores</strong>

			<table id="casino_results" border="0" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th class="casino-logo">Casino</th>
					<th class="casino-bonus">Bonus</th>
					<th class="casino-devices show-for-large-up">Compatible Devices</th>
					<th class="casino-rating text-center"><div class="casino-doughnut-rating">Rating (max 100)</div></th>
					<th class="casino-visit show-for-medium-up text-center"></th>
				</tr>
			</thead>
				<tbody>


						<tr class="result-nr-1">
							<td class="casino-logo" style="background:#1873b9;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;50</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div><div class="results-tablet">Tablet</div><div class="results-mobile">Mobile</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">82</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>
						<tr class="result-nr-2">
							<td class="casino-logo" style="background:#196959;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;300</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div><div class="results-tablet">Tablet</div><div class="results-mobile">Mobile</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">81</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>
						<tr class="result-nr-3">
							<td class="casino-logo" style="background:#d30012;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;500</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div><div class="results-tablet">Tablet</div><div class="results-mobile">Mobile</div><div class="results-tv">TV</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">81</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>
						<tr class="result-nr-4">
							<td class="casino-logo" style="background:#a70e11;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;500</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div><div class="results-tablet">Tablet</div><div class="results-mobile">Mobile</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">79</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>
						<tr class="result-nr-5">
							<td class="casino-logo" style="background:#004890;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;500</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div><div class="results-tablet">Tablet</div><div class="results-mobile">Mobile</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">75</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>
						<tr class="result-nr-6">
							<td class="casino-logo" style="background:#003555;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;1000</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">73</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>
						<tr class="result-nr-7">
							<td class="casino-logo" style="background:#ed8b00;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;400</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div><div class="results-tablet">Tablet</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">71</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>
						<tr class="result-nr-8">
							<td class="casino-logo" style="background:#aa001f;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;100</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div><div class="results-tablet">Tablet</div><div class="results-mobile">Mobile</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">71</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>
						<tr class="result-nr-9">
							<td class="casino-logo" style="background:#ff993a;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;1000</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div><div class="results-tablet">Tablet</div><div class="results-mobile">Mobile</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">70</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>
						<tr class="result-nr-10">
							<td class="casino-logo" style="background:#15286a;width: auto;">
								<a href="#">
									<div class="result-position">
										<img src="img/winnerlogo.png" alt="" align="middle">
									</div>
								</a>
							</td>
							<td class="casino-bonus">&pound;100</td>
							<td class="casino-devices show-for-large-up">
								<div class="results-desktop">Desktop</div><div class="results-tablet">Tablet</div></td>
							<td class="casino-rating text-center">
								<div class="casino-doughnut-rating">68</div>
							</td>
							<td class="casino-visit show-for-medium-up text-center">
								<a href="#" class="button results-go" target="_blank" >Sign Me Up <i class="fa fa-external-link"></i></a>
								<a href="#" class="results-read">Review / Read more</a>
							</td>
						</tr>

					</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Blog & Sidebar Section -->
<div class="container">
	<div class="row blog_sidebar_section">
		<!--Blog-->
<div class="col-md-8">

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
