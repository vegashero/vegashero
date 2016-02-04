<?php

class Vegashero_List_Table extends WP_List_Table {

    function __construct() {
        parent::__construct( 
            array(
                'singular'=> 'wp_list_text_link', //Singular label
                'plural' => 'wp_list_test_links', //plural label, also this well be one of the table css class
                'ajax'   => false //We won't support Ajax for this table
            ) 
        );
    }

    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav( $which ) {
        if ( $which == "top" ){
            //The code that goes before the table is here
            echo"Hello, I'm before the table";
        }
        if ( $which == "bottom" ){
            //The code that goes after the table is there
            echo"Hi, I'm after the table";
        }
    }

}
