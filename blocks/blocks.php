<?php
/* FUNCTIONS SHARED BY BLOCKS */
function render_review($id){
	$source = get_post_meta( $id, 'source', true );
	$name = get_post_meta( $id, 'name', true );
	$description = get_post_meta( $id, 'description', true );
    $rating = get_post_meta( $id, 'rating', true );
    
    $result = '';
    $result .= '<div class="item-review" data-review-source="' . $source . '">';
	    $result .= '<div class="item-review-name">' . $name . '</div> - ';
	    $result .= '<div class="item-review-rating">' . render_stars($rating) . '</div>';
        $result .= '<blockquote class="item-review-text">' . $description . '</blockquote>';
    $result .= '</div>';
    
    return $result;
}

function render_stars($rating){
	$result = '';
    
    for ( $i = 1; $i <= $rating; $i++ ) { 
        $result .= '<span class="star"></span>';
	}
    
    return $result;
}

require_once plugin_dir_path( __FILE__ ) . 'review-form/review-form.php';
require_once plugin_dir_path( __FILE__ ) . 'one-random-review/one-random-review.php';




