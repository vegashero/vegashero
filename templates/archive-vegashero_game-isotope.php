<?php get_header();
$images = plugins_url('vegasgod/images');
$config = new Vegashero_Config();
?>
<!-- Page Section -->



<style type="text/css">
* {
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}

body {
  font-family: sans-serif;
}

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

/* ---- isotope ---- */

.isotope {
  border: 1px solid #333;
}

/* clear fix */
.isotope:after {
  content: '';
  display: block;
  clear: both;
}

/* ---- .game-item ---- */

.game-item {
  position: relative;
  float: left;
  width: 20%;
  height: 100px;
  margin: 5px;
  padding: 10px;
  background: #888;
  color: #262524;
}

.game-item > * {
  margin: 0;
  padding: 0;
}

.game-item.alkali          { background: #F00; background: hsl(   0, 100%, 50%); }
.game-item.alkaline-earth  { background: #F80; background: hsl(  36, 100%, 50%); }
.game-item.lanthanoid      { background: #FF0; background: hsl(  72, 100%, 50%); }
.game-item.actinoid        { background: #0F0; background: hsl( 108, 100%, 50%); }
.game-item.transition      { background: #0F8; background: hsl( 144, 100%, 50%); }
.game-item.post-transition { background: #0FF; background: hsl( 180, 100%, 50%); }
.game-item.metalloid       { background: #08F; background: hsl( 216, 100%, 50%); }
.game-item.diatomic        { background: #00F; background: hsl( 252, 100%, 50%); }
.game-item.halogen         { background: #F0F; background: hsl( 288, 100%, 50%); }
.game-item.noble-gas       { background: #F08; background: hsl( 324, 100%, 50%); }
</style>


<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="http://isotope.metafizzy.co/isotope.pkgd.min.js"></script>

<script type="text/javascript">
$( function() {
  // init Isotope
  var $container = $('.isotope').isotope({
    itemSelector: '.game-item',
    layoutMode: 'fitRows',
    getSortData: {
      name: '.name',
      symbol: '.symbol',
      number: '.number parseInt',
      category: '[data-category]',
      weight: function( itemElem ) {
        var weight = $( itemElem ).find('.weight').text();
        return parseFloat( weight.replace( /[\(\)]/g, '') );
      }
    }
  });

  // filter functions
  var filterFns = {
    // show if number is greater than 50
    numberGreaterThan50: function() {
      var number = $(this).find('.number').text();
      return parseInt( number, 10 ) > 50;
    },
    // show if name ends with -ium
    ium: function() {
      var name = $(this).find('.name').text();
      return name.match( /ium$/ );
    }
  };

  // bind filter button click
  $('#filters').on( 'click', 'button', function() {
    var filterValue = $( this ).attr('data-filter');
    // use filterFn if matches value
    filterValue = filterFns[ filterValue ] || filterValue;
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
  
});

</script>


<h2>Filter Game Category</h2>
<div id="filters" class="button-group">
  <button class="button is-checked" data-filter="*">show all</button>
  <button class="button" data-filter=".metal">metal</button>
  <button class="button" data-filter=".transition">transition</button>
  <button class="button" data-filter=".alkali, .alkaline-earth">alkali and alkaline-earth</button>
  <button class="button" data-filter=":not(.transition)">not transition</button>
  <button class="button" data-filter=".metal:not(.transition)">metal but not transition</button>
  <button class="button" data-filter="numberGreaterThan50">number > 50</button>
  <button class="button" data-filter="ium">name ends with &ndash;ium</button>
</div>

<h2>Filter Game Provider</h2>
<div id="filters" class="button-group">
  <button class="button is-checked" data-filter="*">show all</button>
  <button class="button" data-filter=".metal">metal</button>
  <button class="button" data-filter=".transition">transition</button>
  <button class="button" data-filter=".alkali, .alkaline-earth">alkali and alkaline-earth</button>
  <button class="button" data-filter=":not(.transition)">not transition</button>
  <button class="button" data-filter=".metal:not(.transition)">metal but not transition</button>
  <button class="button" data-filter="numberGreaterThan50">number > 50</button>
  <button class="button" data-filter="ium">name ends with &ndash;ium</button>
</div>

<h2>Sort</h2>
<div id="sorts" class="button-group">
  <button class="button is-checked" data-sort-by="original-order">original order</button>
  <button class="button" data-sort-by="name">name</button>
  <button class="button" data-sort-by="category">category</button>
</div>



<div class="isotope">
  <div class="game-item transition metal" data-category="transition"><h3 class="name">Bismuth</h3></div>
  <div class="game-item metalloid" data-category="metalloid"></div>
  <div class="game-item post-transition metal" data-category="post-transition"></div>
  <div class="game-item post-transition metal" data-category="post-transition"></div>
  <div class="game-item transition metal" data-category="transition"></div>
  <div class="game-item alkali metal" data-category="alkali"></div>
  <div class="game-item alkali metal" data-category="alkali"></div>
  <div class="game-item transition metal" data-category="transition"></div>
  <div class="game-item alkaline-earth metal" data-category="alkaline-earth"></div>
  <div class="game-item transition metal" data-category="transition"></div>
  <div class="game-item post-transition metal" data-category="post-transition"></div>
  <div class="game-item metalloid" data-category="metalloid"></div>
  <div class="game-item transition metal" data-category="transition"></div>
  <div class="game-item lanthanoid metal inner-transition" data-category="lanthanoid"></div>
  <div class="game-item noble-gas nonmetal" data-category="noble-gas"></div>
  <div class="game-item diatomic nonmetal" data-category="diatomic"></div>
  <div class="game-item actinoid metal inner-transition" data-category="actinoid"></div>
  <div class="game-item actinoid metal inner-transition" data-category="actinoid"></div>
</div>


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
