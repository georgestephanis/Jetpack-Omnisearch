<?php

if( ! class_exists( 'WP_Comments_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php' );

class Jetpack_Omnisearch_Comments extends WP_Comments_List_Table {
	static $instance;
	var $checkbox = false;

	function __construct() {
		self::$instance = $this;
		add_filter( 'omnisearch_results', array( $this, 'search'), 10, 2 );
	}

	function search( $results, $search_term ) {
		$search_url = esc_url( admin_url( sprintf( 'edit-comments.php?s=%s', urlencode( $search_term ) ) ) );
		$search_link = sprintf( ' <a href="%s" class="add-new-h2">%s</a>', $search_url, __('Search Comments') );
		$html = '<h2>' . __('Comments') . $search_link . '</h2>';
		parent::__construct();

		ob_start();
		$this->prepare_items();
		$this->_column_headers = array( $this->get_columns(), array(), array() );
		$this->display();
		$html .= ob_get_clean();

		$results[ __CLASS__ ] = $html;
		return $results;
	}

	function get_per_page( $comment_status ) {
		return Jetpack_Omnisearch::$num_results;
	}

	function get_sortable_columns() {
		return array();
	}

	function get_bulk_actions() {
		return array();
	}

	function pagination() {
		
	}

	function extra_tablenav( $which ) {
		
	}

}
