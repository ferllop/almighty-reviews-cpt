<?php
/*
Plugin Name: Reviews Custom Post Type
Plugin URI: https://ferranllop.com/
Description: Reviews Custom Post Type with some custom meta boxes
Version: 1.0
Author: Ferran Llop
Author URI: https://ferranllop.com/
 */

add_action( 'init', 'almighty_reviews_post_type', 0 );
if ( ! function_exists('almighty_reviews_post_type') ) {
    function almighty_reviews_post_type() {

        $labels = array(
            'name'                  => _x( 'Reviews from my customers', 'Post Type General Name', 'almighty-reviews-cpt' ),
            'singular_name'         => _x( 'Review', 'Post Type Singular Name', 'almighty-reviews-cpt' ),
            'menu_name'             => __( 'Reviews', 'almighty-reviews-cpt' ),
            'name_admin_bar'        => __( 'Reviews', 'almighty-reviews-cpt' ),
            'archives'              => __( 'Review Archives', 'almighty-reviews-cpt' ),
            'attributes'            => __( 'Review Attributes', 'almighty-reviews-cpt' ),
            'parent_item_colon'     => __( 'Parent Review:', 'almighty-reviews-cpt' ),
            'all_items'             => __( 'All Reviews', 'almighty-reviews-cpt' ),
            'add_new_item'          => __( 'Add New Review', 'almighty-reviews-cpt' ),
            'add_new'               => __( 'Add New', 'almighty-reviews-cpt' ),
            'new_item'              => __( 'New Review', 'almighty-reviews-cpt' ),
            'edit_item'             => __( 'Edit Review', 'almighty-reviews-cpt' ),
            'update_item'           => __( 'Update Review', 'almighty-reviews-cpt' ),
            'view_item'             => __( 'View Review', 'almighty-reviews-cpt' ),
            'view_items'            => __( 'View Review', 'almighty-reviews-cpt' ),
            'search_items'          => __( 'Search Review', 'almighty-reviews-cpt' ),
            'not_found'             => __( 'Not found', 'almighty-reviews-cpt' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'almighty-reviews-cpt' ),
            'featured_image'        => __( 'Featured Image', 'almighty-reviews-cpt' ),
            'set_featured_image'    => __( 'Set featured image', 'almighty-reviews-cpt' ),
            'remove_featured_image' => __( 'Remove featured image', 'almighty-reviews-cpt' ),
            'use_featured_image'    => __( 'Use as featured image', 'almighty-reviews-cpt' ),
            'insert_into_item'      => __( 'Insert into Review', 'almighty-reviews-cpt' ),
            'uploaded_to_this_item' => __( 'Uploaded to this Review', 'almighty-reviews-cpt' ),
            'items_list'            => __( 'Reviews list', 'almighty-reviews-cpt' ),
            'items_list_navigation' => __( 'Reviews list navigation', 'almighty-reviews-cpt' ),
            'filter_items_list'     => __( 'Filter reviews list', 'almighty-reviews-cpt' ),
        );

        $rewrite = array(
            'slug'                  => 'opiniones',
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => true,
        );

        $args = array(
            'label'                 => __( 'Review', 'almighty-reviews-cpt' ),
            'description'           => __( 'Post type for reviews', 'almighty-reviews-cpt' ),
            'labels'                => $labels,
            'supports'              => array( 'editor', 'custom-fields' ),
            'taxonomies'            => [],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-star-filled',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
            'template'              => array(
                array( 'almighty-reviews-cpt/review-form', array() )
            )
            // 'template_lock' => 'insert'
        );
        register_post_type( 'reviews', $args );
        remove_rewrite_tag( '%opiniones%' );
    }
}

add_action( 'init', 'register_reviews_cpt_metas' );
function register_reviews_cpt_metas() {
    register_post_meta( 'reviews', 'name', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ) );
    register_post_meta( 'reviews', 'rating', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'integer',
    ) );
    register_post_meta( 'reviews', 'description', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ) );
    register_post_meta( 'reviews', 'source', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
    ) );
}

add_action( 'rest_after_insert_reviews', 'set_data_from_metaboxes' );
function set_data_from_metaboxes( $post ) {
    if ( 'reviews' != get_post_type($post->ID) ){
        return;
    }

    if ( wp_is_post_revision( $post->ID ) 
        && (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) ) {
        return;
    }
    
    $args = [
        'ID' => $post->ID,
        'post_name' => $post->ID,
        'post_title' => get_post_meta( $post->ID, 'name', true )
    ];

    remove_action( 'save_post', 'set_data_from_metaboxes' );
    wp_update_post($args);
    add_action( 'save_post', 'set_data_from_metaboxes' );
}


/*
 * Register custom blocks
 */
require_once plugin_dir_path( __FILE__ ) . 'blocks/blocks.php';
