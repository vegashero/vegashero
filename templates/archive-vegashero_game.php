<?php get_header();
$images = plugins_url('vegasgod/images');
$config = new Vegashero_Config();
?>
<!-- Page Section -->



<style type="text/css">

/* ---- button ---- */

.button {
  display: inline-block;
  padding: 0.5em 1.0em;
  background: #EEE;
  border: none;
  border-radius: 7px;
  background-image: linear-gradient( to bottom, hsla(0, 0%, 0%, 0), hsla(0, 0%, 0%, 0.2) );
  color: #222;
  font-family: sans-serif;
  font-size: 16px;
  text-shadow: 0 1px white;
  cursor: pointer;
}

.button:hover {
  background-color: #8CF;
  text-shadow: 0 1px hsla(0, 0%, 100%, 0.5);
  color: #222;
}

.button:active,
.button.is-checked {
  background-color: #28F;
}

.button.is-checked {
  color: white;
  text-shadow: 0 -1px hsla(0, 0%, 0%, 0.8);
}

.button:active {
  box-shadow: inset 0 1px 10px hsla(0, 0%, 0%, 0.8);
}

/* ---- button-group ---- */

.button-group:after {
  content: '';
  display: block;
  clear: both;
}

.button-group .button {
  float: left;
  border-radius: 0;
  margin-left: 0;
  margin-right: 1px;
}

.button-group .button:first-child { border-radius: 0.5em 0 0 0.5em; }
.button-group .button:last-child { border-radius: 0 0.5em 0.5em 0; }

input[type="text"] {
  font-size: 20px;
  padding: 3px 10px 4px 10px;
  border: #CCC 1px solid;
  margin: 0;
  width: 180px;
  line-height: 27px;
}

/* ---- isotope ---- */

/* clear fix */
.vh-container:after {
  content: '';
  display: block;
  clear: both;
}

/* ---- .game-item ---- */

.vh-item {
  position: relative;
  float: left;
  /*width: 20%;*/
}

@media (min-width: 768px) {
	.app.container {
	width: 920px;
	}
	.vh-item {
	width: 33.33333333%;
	height: 300px;
	}
}
@media (min-width: 992px) {
	.vh-item {
	  width: 20%;
	  height: 350px;
	}
}


</style>


<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="http://isotope.metafizzy.co/isotope.pkgd.min.js"></script>

<script type="text/javascript">


// $('.search-btn').click(function() {
// 	var keywd = $('.search-input').val();
// 	console.log(keywd);
// });

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
  $('#sorts').on( 'click', 'button', function() {
    var sortByValue = $(this).attr('data-sort-by');
    $container.isotope({ sortBy: sortByValue });
  });
  
  // change is-checked class on buttons
  $('.button-group').each( function( i, buttonGroup ) {
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

<br/><br/><br/><br/>

<h2>Filter Game Category</h2>
<div id="filters" class="button-group">
  <button class="button is-checked" data-filter="*">show all</button>
  <button class="button" data-filter=".video-slots">video slots</button>
  <button class="button" data-filter=".super-slots">super slots</button>
  <button class="button" data-filter=".mega-slots">mega slots</button>
  <input type="text" id="quicksearch" placeholder="Search" />
</div>

<h2>Sort</h2>
<div id="sorts" class="button-group">
  <button class="button is-checked" data-sort-by="original-order">original order</button>
  <button class="button" data-sort-by="name">game Name</button>
  <button class="button" data-sort-by="gamecat">category</button>
</div>

<br/><br/>

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
						<div class="vh-item-overlay">
							<a href="<?php the_permalink(); ?>" class="vh-play-fun" >Play Now</a>
                            <img src="<?=$image_url?>cover.jpg" alt="<?php the_title(); ?>" class="img-hover">
							<a href="<?php the_permalink(); ?>" class="vh-game-title"><?php the_title(); ?></a>
                            <p class="vh-game-cat"><?=$categories[0]->name?></p>
						</div>
                        <img src="<?=$image_url?>cover.jpg" alt="<?php the_title(); ?>" class="img-full">
						<a href="<?php the_permalink(); ?>" class="vh-game-title name"><?php the_title(); ?></a>
						<p class="vh-game-cat gamecat"><?=$categories[0]->name?></p>
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
