<?php

class Jetpack_Omnisearch_Posts {
	static $instance;
	var $post_type = 'post';

	function __construct() {
		self::$instance = $this;
		add_filter( 'omnisearch_results', array( $this, 'search'), 10, 2 );
	}

	function search( $results, $search_term ) {
		$post_type_obj = get_post_type_object( $this->post_type );
		$html = '<h2>' . $post_type_obj->labels->name . '</h2>';

		$query = new WP_Query( array( 's' => $search_term, 'post_type' => $this->post_type, 'posts_per_page' => 10 ) );
		if( $query->have_posts() ) {
			$html .= '<ul>';
			while( $query->have_posts() ) {
				$query->the_post();
				$html .= '<li>' . get_the_title() . ' <a href="' . get_edit_post_link() . '">' . __('edit') . '</a></li>';
			}
			$html .= '</ul>';
		} else {
			$html .= '<p>' . __('No results found.') . '</p>';
		}

		$results[ __CLASS__ . "_{$this->post_type}" ] = $html;
		return $results;
	}

}

new Jetpack_Omnisearch_Posts;
