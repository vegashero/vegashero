<?php
namespace VegasHero\ShortCodes;

final class GamesGrid
{

    /**
     * @param Vegashero_Config $config
     */
    public function __construct($config) {
        $this->config = $config;
    }

    public function render() {
        return $this->getTemplate();
    }

    /**
     * @param array $attributes 
     * @returns object
     */
    static private function _getAttributes($attributes = array()) {
        return (object)shortcode_atts( 
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

    /**
     * @param array $attributes 
     * @returns array
     */
    public function getGames($attributes = array()) {
        $attributes = self::_getAttributes($attributes);
        $options = array(
            'post_type' => $this->config->customPostType,
            'order' => $attributes->order,
            'orderby' => $attributes->orderby,
            'posts_per_page' => $attributes->gamesperpage,
            'paged' => $attributes->paged,
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => $this->config->gameProviderTaxonomy,
                    'field'    => 'slug',
                    'terms'    => $attributes->provider,
                ),
                array(
                    'taxonomy' => $this->config->gameOperatorTaxonomy,
                    'field'    => 'slug',
                    'terms'    => $attributes->operator,
                ),
                array(
                    'taxonomy' => $this->config->gameCategoryTaxonomy,
                    'field'    => 'slug',
                    'terms'    => $attributes->category,
                ),
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $attributes->tag,
                ),
            ),
            's' => $attributes->keyword
        );
        return get_posts($options);
    }

    public function getThumbnail($post_id) {
        $terms = wp_get_post_terms($post_id, $this->config->gameProviderTaxonomy, array('fields' => 'all'));
        $featured_image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'vegashero-thumb');
        if($featured_image_src) {
            $thumbnail = $featured_image_src[0];
        } else {
            if( ! $thumbnail = get_post_meta($post_id, $this->config->postMetaGameImg, true )) {
                $mypostslug = get_post_meta($post_id, $this->config->postMetaGameTitle, true );
                $thumbnail = sprintf("%s/%s/cover.jpg", $this->config->gameImageUrl, $terms[0]->slug, sanitize_title($mypostslug));
            }
        }
        return $thumbnail;
    }

    static function getPage() {
        return ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    }

    public function getTemplate() {
        $template = '<ul id="vh-lobby-posts-grid" class="vh-row-sm">';
        $template .= $this->_getGameItemsMarkup();
        $template .= '</ul>';
        $template .= '<div class="clear"></div>';
        return $template;
    }

    private function _getGameItemsMarkup() {
    }

    private function getGameItemMarkup($thumbnail) {
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
