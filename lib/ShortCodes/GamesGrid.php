<?php
namespace VegasHero\ShortCodes;

final class GamesGrid
{

    /**
     * @param array $attributes 
     * @param Vegashero_Config $config
     */
    public function __construct($attributes, $config) {
        $this->config = $config;
        $this->attributes = (object)shortcode_atts( 
            array(
                'order' => 'ASC',
                'orderby' => 'title',
                'gamesperpage' => -1,
                'pagination' => 'off',
                'provider' => '',
                'operator' => '',
                'category' => '',
                'tag' => '',
                'keyword' => '',
                'paged' => self::getPage() 
            ), $attributes
        );
    }

    public function render() {
        return $this->getTemplate();
    }

    public function getGames() {
        // define query parameters based on attributes
        $options = array(
            'post_type' => $this->config->customPostType,
            'order' => $this->attributes->order,
            'orderby' => $this->attributes->orderBy,
            'posts_per_page' => $this->attributes->gamesPerPage,
            'paged' => $this->attributes->page,
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => $this->config->gameProviderTaxonomy,
                    'field'    => 'slug',
                    'terms'    => $this->attributes->provider,
                ),
                array(
                    'taxonomy' => $this->config->gameOperatorTaxonomy,
                    'field'    => 'slug',
                    'terms'    => $this->attributes->operator,
                ),
                array(
                    'taxonomy' => $this->config->gameCategoryTaxonomy,
                    'field'    => 'slug',
                    'terms'    => $this->attributes->category,
                ),
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $this->attributes->tag,
                ),
            ),
            's' => $this->attributes->keyword
        );
        return get_posts($options);
    }

    public function getThumbnail() {
        $providerz = wp_get_post_terms(get_the_ID(), 'game_provider', array('fields' => 'all'));
        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'vegashero-thumb');
        $mypostslug = get_post_meta( get_the_ID(), 'game_title', true );
        if($thumbnail) {
            $thumbnail_new = $thumbnail[0];
        } else {
            if( ! $thumbnail_new = get_post_meta( get_the_ID(), 'game_img', true )) {
                $thumbnail_new = $this->config->gameImageUrl . '/' . $providerz[0]->slug . '/' . sanitize_title($mypostslug) . '/cover.jpg';
            }
        }
        return $thumbnail_new;
    }

    static function getPage() {
        return ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    }

    public function getTemplate() {
        return <<<MARKUP
        <ul id="vh-lobby-posts-grid" class="vh-row-sm">
        </ul>
        <div class="clear"></div>
MARKUP;
    }

    private function getGameItemMarkup() {
        return <<<MARKUP
            <li class="vh-item" id="post-<?php the_ID(); ?>">
                <a class="vh-thumb-link" href="<?php the_permalink(); ?>">
                    <div class="vh-overlay">
                        <img src="<?php echo $thumbnail_new; ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
                        <!-- <span class="play-now">Play now</span> -->
                    </div>
                </a>
                <div class="vh-game-title"><?php the_title(); ?></div>
            </li>
MARKUP;
    }

    private function getPaginationMarkup() {
        return <<<MARKUP
            <?php if ($pagination == 'on') { ?>
            <?php if ($the_query->max_num_pages > 1) { ?>
              <nav class="vh-pagination">                
                <?php if( is_paged() ) { //check if first page ?>
                <div class="prev page-numbers">
                  <?php echo get_previous_posts_link( '<< Previous' ); ?>
                </div>
                <?php } ?>
                <?php if ( $the_query->max_num_pages > get_query_var('paged') ) { //check if last page ?>
                <div class="next page-numbers">
                  <?php echo get_next_posts_link( 'Next >>', $the_query->max_num_pages ); ?>
                </div>
                <?php } ?>
              </nav>
            <?php } ?>
            <?php } ?>
MARKUP;

    }
}
