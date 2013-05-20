<?php

class Jetpack_Omnisearch_Plugins {
	static $instance;

	function __construct() {
		self::$instance = $this;
		add_filter( 'omnisearch_results', array( $this, 'search'), 10, 2 );
	}

	function search( $results, $search_term ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
		$search_link = ' <a href="' . admin_url( "plugin-install.php?tab=search&s={$search_term}" ) . '" class="add-new-h2">' . __('Search Plugins') . '</a>';
		$html = '<h2>' . __('Plugins') . $search_link . '</h2>';

		# http://dd32.id.au/projects/wordpressorg-plugin-information-api-docs/
		$api = plugins_api( 'query_plugins', array( 'search' => $search_term, 'per_page' => 10 ) );

		if ( is_wp_error( $api ) ) {
			$html .= '<p>' . $api->get_error_message() . '</p>';
		} elseif( empty( $api->plugins ) ) {
			$html .= '<p>' . __('No results found.') . '</p>';
		} else {
			$html .= '<ul>';
			foreach( $api->plugins as $plugin ) {
				$html .= '<li>'
						.'<p><strong><a href="' . $plugin->homepage . '">' . $plugin->name . '</a></strong> '
						.'<small>Rating: ' . $plugin->rating . '/100</small></p>'
						.wpautop( $plugin->short_description )
						.'</li>';
			}
			$html .= '</ul>';
		}

		$results[ __CLASS__ ] = $html;
		return $results;
	}

}

new Jetpack_Omnisearch_Plugins;
