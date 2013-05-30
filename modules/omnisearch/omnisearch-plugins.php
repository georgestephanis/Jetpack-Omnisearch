<?php

if( ! class_exists( 'WP_Plugin_Install_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-plugin-install-list-table.php' );

class Jetpack_Omnisearch_Plugins extends WP_Plugin_Install_List_Table {
	static $instance;

	function __construct() {
		self::$instance = $this;
		add_filter( 'omnisearch_results', array( $this, 'search'), 10, 2 );
		add_action( 'wp_ajax_omnisearch_plugins', array( $this, 'wp_ajax_omnisearch_plugins' ) );
	}

	function search( $results, $search_term ) {
		wp_enqueue_script( 'plugin-install' );
		add_thickbox();

		$search_link = ' <a href="' . admin_url( "plugin-install.php?tab=search&s={$search_term}" ) . '" class="add-new-h2">' . __('Search Plugins') . '</a>';
		$html = '<h2>' . __('Plugins') . $search_link . '</h2>';

		$html .= '<div id="' . __CLASS__ . '_results">' . __('Loading &hellip;') . '</div>';
		$html .= '<script>jQuery("#' . __CLASS__ . '_results").load(ajaxurl,{action:"omnisearch_plugins",search_term:search_term});</script>';

		$results[ __CLASS__ ] = $html;
		return $results;
	}

	function results_html( $search_term ) {
		$_GET['tab'] = 'search';
		$GLOBALS['hook_suffix'] = 'foo';
		$_REQUEST['s'] = $search_term;
		parent::__construct();

		ob_start();
		$this->prepare_items();
		remove_action( 'install_plugins_table_header', 'install_search_form' );
		$this->display();
		$html = ob_get_clean();

		return $html;
	}

	function wp_ajax_omnisearch_plugins() {
		$search_term = $_REQUEST['search_term'];
		wp_die( $this->results_html( $search_term ) );
	}

	function get_bulk_actions() {
		return array();
	}

	function pagination() {
		
	}

}

new Jetpack_Omnisearch_Plugins;
