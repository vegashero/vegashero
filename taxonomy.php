<?php

class Vegashero_Taxonomy
{

    public function __construct() {
        add_action( 'generate_rewrite_rules', array($this, 'addRewriteRules') );
    }

    public function addRewriteRules() {
        global $wp_rewrite;
        $new_rules = $this->getRewriteRules();
        $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
    }

    public function getRewriteRules( $post_type, $query_vars = array() ) {
        global $wp_rewrite;

        if( ! is_object( $post_type ) )
            $post_type = get_post_type_object( $post_type );

        $new_rewrite_rules = array();

        $taxonomies = get_object_taxonomies( $post_type->name, 'objects' );

        // Add taxonomy filters to the query vars array
        foreach( $taxonomies as $taxonomy )
            $query_vars[] = $taxonomy->query_var;

        // Loop over all the possible combinations of the query vars
        for( $i = 1; $i <= count( $query_vars );  $i++ ) {

            $new_rewrite_rule =  $post_type->rewrite['slug'] . '/';
            $new_query_string = 'index.php?post_type=' . $post_type->name;

            // Prepend the rewrites & queries
            for( $n = 1; $n <= $i; $n++ ) {
                $new_rewrite_rule .= '(' . implode( '|', $query_vars ) . ')/(.+?)/';
                $new_query_string .= '&' . $wp_rewrite->preg_index( $n * 2 - 1 ) . '=' . $wp_rewrite->preg_index( $n * 2 );
            }

            // Allow paging of filtered post type - WordPress expects 'page' in the URL but uses 'paged' in the query string so paging doesn't fit into our regex
            $new_paged_rewrite_rule = $new_rewrite_rule . 'page/([0-9]{1,})/';
            $new_paged_query_string = $new_query_string . '&paged=' . $wp_rewrite->preg_index( $i * 2 + 1 );

            // Make the trailing backslash optional
            $new_paged_rewrite_rule = $new_paged_rewrite_rule . '?$';
            $new_rewrite_rule = $new_rewrite_rule . '?$';

            // Add the new rewrites
            $new_rewrite_rules = array( $new_paged_rewrite_rule => $new_paged_query_string,
                $new_rewrite_rule       => $new_query_string )
                + $new_rewrite_rules;
        }

        return $new_rewrite_rules;
    }
}
