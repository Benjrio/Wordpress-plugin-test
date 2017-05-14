<?php
/**
 * Plugin Name: BooksPlugin
 * Description:Tester plugin.
 * Plugin URI: http://example.org/
 * Author: Bojidar
 * Author URI: 
 * Version: 0.1
 * Text Domain: 
 * License: GPL2
*/


/* Registering custom post type.*/


add_action( 'init', 'br_post_type' );
function br_post_type() {
	$labels = array(
		'name'               => __( 'Book Reviews', 'post type general name', 'book-reviews' ),
		'singular_name'      => __( 'Book Review', 'post type singular name', 'book-reviews' ),
		'menu_name'          => __( 'Book Reviews', 'admin menu', 'book-reviews' ),
		'name_admin_bar'     => __( 'Book Review', 'add new on admin bar', 'book-reviews' ),
		'add_new'            => __( 'Add New', 'Book Review', 'book-reviews' ),
		'add_new_item'       => __( 'Add New Book Review', 'book-reviews' ),
		'new_item'           => __( 'New Book Review', 'book-reviews' ),
		'edit_item'          => __( 'Edit Book Review', 'book-reviews' ),
		'view_item'          => __( 'View Book Review', 'book-reviews' ),
		'all_items'          => __( 'All Book Reviews', 'book-reviews' ),
		'search_items'       => __( 'Search Book Reviews', 'book-reviews' ),
		'parent_item_colon'  => __( 'Parent Book Reviews:', 'book-reviews' ),
		'not_found'          => __( 'No Book Reviews found.', 'book-reviews' ),
		'not_found_in_trash' => __( 'No Book Reviews found in Trash.', 'book-reviews' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Test Book reviews.', 'book-reviews' ),
		'public'             => true,
		'rewrite'            => array( 'slug' => 'book_review' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' , 'custom-fields'),
		'menu_icon'			 => 'dashicons-book'
	);

	register_post_type( 'book_review', $args );

	
}
/* Return author and score on the end of the post. */
add_filter( 'the_content', 'prepend_book_data' );
function prepend_book_data( $content ) {

 if( is_singular( 'book_review' ) ) {

 		$author = get_post_meta( get_the_ID(), 'author', true );
 		$score = get_post_meta( get_the_ID(), 'score' , true);

 	$html = '
<div class = "book-meta">
	<strong>Author: </strong> '.$author.'<br>
	<strong>Score: </strong> '.$score.'<br>
	</div>
 	';
 	return $content. $html;
}
return $content;
}
/* Hooking custom posts to main page */
add_action ( 'pre_get_posts', 'add_book_review_to_query' );
function add_book_review_to_query( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$query->set( 'post_type', array( 'post', 'book_review') );
	}
	
}
/* Return Title of the top of the post. */
add_filter( 'the title' , 'prepend_post_type', 10, 2 );
function prepend_post_type( $title, $id) {
	if( is_home() ) { 
		$post_type = get_post_type( $id );
		$types = array(
			'post' => 'Blog ',
			'book_review' => 'Review',
	);

		return '<small>' . $types[$post_type] . ': </small><br>' . $title;
	}
	return $title;

}



