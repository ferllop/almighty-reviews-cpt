<?php
/**
 * Plugin Name:     Review form
 * Description:     Block to deal with de reviews of reviews custom post type
 * Version:         0.1.0
 * Author:          Ferran Llop
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     review-form
 *
 * @package         almighty-reviews-cpt
 */

add_action( 'init', 'register_almighty_reviews_cpt_review_form_block' );
function register_almighty_reviews_cpt_review_form_block() {
	$dir = dirname( __FILE__ );

	$index_js = 'index.js';
	wp_register_script(
		'almighty-reviews-cpt-review-form-block-editor',
		plugins_url( $index_js, __FILE__ ),
		array( 'wp-i18n', 'wp-blocks', 'wp-element', 'wp-components', 'wp-data', 'wp-core-data' ),
		filemtime( "$dir/$index_js" )
	);
	wp_set_script_translations( 'almighty-reviews-cpt-review-form-block-editor', 'review-form' );

	$editor_css = 'editor.css';
	wp_register_style(
		'almighty-reviews-cpt-review-form-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'style.css';
	wp_register_style(
		'almighty-reviews-cpt-review-form-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'almighty-reviews-cpt/review-form', array(
		'editor_script' => 'almighty-reviews-cpt-review-form-block-editor',
		'editor_style'  => 'almighty-reviews-cpt-review-form-block-editor',
		'style'         => 'almighty-reviews-cpt-review-form-block',
		'render_callback' => 'almighty_reviews_cpt_render_review'
	) );	
}

function almighty_reviews_cpt_render_review(){
	return render_review(get_the_id());
}