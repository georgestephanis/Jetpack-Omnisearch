<?php

class Jetpack_Omnisearch_Comments {
	static $instance;

	function __construct() {
		self::$instance = $this;
		add_filter( 'omnisearch_results', array( $this, 'search'), 10, 2 );
	}

	function search( $results, $search_term ) {
		$search_link = ' <a href="' . admin_url( "edit-comments.php?s={$search_term}" ) . '" class="add-new-h2">' . __('Search Comments') . '</a>';
		$html = '<h2>' . __('Comments') . $search_link . '</h2>';

		$comments = get_comments( array(
			'status' => 'approve',
			'search' => $search_term,
			'number' => 10,
		) );

		if( ! empty( $comments ) ) {
			$html .= '<ul>';
			foreach( $comments as $comment ) {
				$html .= '<li>'
						.'<p>On <strong><a href="' . get_permalink( $comment->comment_post_ID ) . '">'
							.get_the_title( $comment->comment_post_ID ) . '</a>, '
							.'<a href="' . get_comment_link( $comment ) . '">' . $comment->comment_author . ' said:</a></strong></p>'
						.wpautop( $comment->comment_content )
						.'</li>';
			}
			$html .= '</ul>';
		} else {
			$html .= '<p>' . __('No results found.') . '</p>';
		}

		$results[ __CLASS__ ] = $html;
		return $results;
	}

}

new Jetpack_Omnisearch_Comments;
