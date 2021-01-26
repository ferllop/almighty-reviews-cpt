<?php
/**
 * Plugin Name:     One Random Review
 * Description:     Example block written with ESNext standard and JSX support – build step required.
 * Version:         0.1.0
 * Author:          The WordPress Contributors
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     one-random-review
 *
 * @package         create-block
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
add_action( 'init', 'create_block_one_random_review_block_init' );
function create_block_one_random_review_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "create-block/one-random-review" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'create-block-one-random-review-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_set_script_translations( 'create-block-one-random-review-block-editor', 'one-random-review' );

	$editor_css = 'build/index.css';
	wp_register_style(
		'create-block-one-random-review-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'create-block-one-random-review-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'almighty-reviews-cpt/one-random-review', array(
		'editor_script' => 'create-block-one-random-review-block-editor',
		'editor_style'  => 'create-block-one-random-review-block-editor',
		'style'         => 'create-block-one-random-review-block',
		'render_callback' => 'render_a_random_review'
	) );
}

function render_a_random_review(){
	$query = new WP_Query( array ( 'post_type' => 'reviews', 'orderby' => 'rand', 'posts_per_page' => '1' ) );

	if(! $query->have_posts()) {
		return 'Aún no hay reviews publicadas';	
	}

	$result = '<div>';

	while ( $query->have_posts() ) : $query->the_post();
		$result .= render_review(get_the_id());
	endwhile;

	$result .= '</div>';

	wp_reset_postdata();

	return $result;
}

