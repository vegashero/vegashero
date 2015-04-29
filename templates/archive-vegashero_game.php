<?php get_header();
//$images = plugins_url('vegasgod/images');
$images = "http://cdn.vegasgod.com";
$config = new Vegashero_Config();
?>
<!-- Page Section -->


<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="http://isotope.metafizzy.co/isotope.pkgd.min.js"></script>

<script type="text/javascript">

$( function() {
	// quick search regex
	var qsRegex;
  	// init Isotope
  	var $container = $('.vh-container').isotope({
    itemSelector: '.vh-item',
    layoutMode: 'fitRows',
    filter: function() {
      return qsRegex ? $(this).text().match( qsRegex ) : true;
    },
    getSortData: {
      name: '.name',
      gamecat: '.gamecat',
      category: '[data-category]'
    }
  });

  // use value of search field to filter
  var $quicksearch = $('#quicksearch').keyup( debounce( function() {
    qsRegex = new RegExp( $quicksearch.val(), 'gi' );
    $container.isotope();
  }, 200 ) );

  // bind filter button click
  $('#filters').on( 'click', 'button', function() {
    var filterValue = $( this ).attr('data-filter');
    // use filterFn if matches value
    $container.isotope({ filter: filterValue });
  });

  // bind sort button click
  //$('#sorts').on( 'click', 'button', function() {
    //var sortByValue = $(this).attr('data-sort-by');
    //$container.isotope({ sortBy: sortByValue });
  //});

  $('#sorts-dropdwn').on( 'change', function() {
    var sortByValue = this.value;
    $container.isotope({ sortBy: sortByValue });
  });

  // change is-checked class on buttons
  $('.vh-filters').each( function( i, buttonGroup ) {
    var $buttonGroup = $( buttonGroup );
    $buttonGroup.on( 'click', 'button', function() {
      $buttonGroup.find('.is-checked').removeClass('is-checked');
      $( this ).addClass('is-checked');
    });
  });

  // reveal all items after init animated
  var iso = $container.data('isotope');
  $container.isotope( 'reveal', iso.items );

});

// debounce so filtering doesn't happen every millisecond
function debounce( fn, threshold ) {
  var timeout;
  return function debounced() {
    if ( timeout ) {
      clearTimeout( timeout );
    }
    function delayed() {
      fn();
      timeout = null;
    }
    timeout = setTimeout( delayed, threshold || 100 );
  }
}

</script>



<div class="vh-lobby-header">

  <h2>Vegas Hero Lobby</h2>
  
  <div id="filters" class="vh-filters">
    <button class="vh-filter is-checked" data-filter="*">Show all</button>
    <button class="vh-filter" data-filter=".video-slots">Video slots</button>
    <button class="vh-filter" data-filter=".super-slots">Super slots</button>
    <button class="vh-filter" data-filter=".mega-slots">Mega slots</button>
    <button class="vh-filter" data-filter=".crazy-slots">Crazy slots</button>
    <div id="form-ui" class="vh-sorting">
      <label>Sort by:</label>
      <select id="sorts-dropdwn">
        <option value="">Original Order</option>
        <option value="name">Game Name</option>
        <option value="gamecat">Game Category</option>
      </select>
    </div>
  </div>

  <input type="text" id="quicksearch" class="vh-search" placeholder="Search" />
</div>

<div class="vh-app-content">
	<!-- main -->
	<div class="vh-col vh-wrapper-lg">
		<div class="vh-row vh-row-sm vh-container">

                <?php if ( have_posts() ) : ?>
                <?php while(have_posts()): ?>
                <?php
                    the_post();
                    $post_meta = get_post_meta(get_the_ID(), $config->metaKey, true);
                    $categories = wp_get_post_terms(get_the_ID(), $config->gameCategoryTaxonomy);
                    $operators = wp_get_post_terms(get_the_ID(), $config->gameOperatorTaxonomy);
                    $provider = wp_get_post_terms(get_the_ID(), $config->gameProviderTaxonomy)[0];
                    $image_url = sprintf("%s/%s/%s/", $images, $provider->name, sanitize_title($post->post_title));
                    $cat_slug = $categories;
                    $post_slug = sprintf(sanitize_title($post->post_title));
					?>
					<div class="vh-col-xs-6 vh-col-sm-4 vh-col-md-4 vh-item <?=sprintf(sanitize_title($cat_slug[0]->name))?>" data-category="<?=$categories[0]->name?>">
						
            <!-- hover overlay -->
            <div class="vh-item-overlay">
							<a href="<?php the_permalink(); ?>" class="vh-play-fun" >Play Now</a>
              <a href="<?php the_permalink(); ?>"><img src="<?=$image_url?>cover.jpg" alt="<?php the_title(); ?>" class="img-hover"></a>
							<a href="<?php the_permalink(); ?>" class="vh-game-title"><?php the_title(); ?></a>
                <span class="vh-game-cat"><?=$categories[0]->name?></span>
						</div>
            <!-- #hover overlay -->

                <img src="<?=$image_url?>cover.jpg" alt="<?php the_title(); ?>" class="img-full">
						<a href="<?php the_permalink(); ?>" class="vh-game-title name"><?php the_title(); ?></a>
						<span class="vh-game-cat gamecat"><?=$categories[0]->name?></span>
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
