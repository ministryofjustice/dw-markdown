<?php

/*
 * Plugin Name: DW Markdown
 * Description: Write posts or pages in plain-text Markdown syntax.
 * Author: Ryan Jarrett
 * Version: 0.3
 * Author URI: http://sparkdevelopment.co.uk
 * Text Domain: jetpack
 * Domain Path: /languages/
 */

/**
 * Module Name: Markdown
 * Module Description: Write posts or pages in plain-text Markdown syntax.
 * Sort Order: 31
 * First Introduced: 2.8
 * Requires Connection: No
 * Auto Activate: No
 * Module Tags: Writing
 */

include_once dirname( __FILE__ ) . '/require-lib.php';
include_once dirname( __FILE__ ) . '/markdown/easy-markdown.php';

/**
 * Force plugin to load early
 *
 * @since 1.0
 */

function DWMDP_load_last(){
  $path = str_replace( WP_PLUGIN_DIR . '/', '', __FILE__ );
  if ( $plugins = get_option( 'active_plugins' ) ) {
    if ( $key = array_search( $path, $plugins ) ) {
      array_splice( $plugins, $key, 1 );
      array_push( $plugins, $path );
      update_option( 'active_plugins', $plugins );
    }
  }
}
add_action( 'activated_plugin', 'DWMDP_load_last',1 );

// If the module is active, let's make this active for posting, period.
// Comments will still be optional.
add_filter( 'pre_option_' . WPCom_Markdown::POST_OPTION, '__return_true' );
function jetpack_markdown_posting_always_on() {
	// why oh why isn't there a remove_settings_field?
	global $wp_settings_fields;
	if ( isset( $wp_settings_fields['writing']['default'][ WPCom_Markdown::POST_OPTION ] ) ) {
		unset( $wp_settings_fields['writing']['default'][ WPCom_Markdown::POST_OPTION ] );
	}
}
add_action( 'admin_init', 'jetpack_markdown_posting_always_on', 11 );

function jetpack_markdown_load_textdomain() {
	load_plugin_textdomain( 'jetpack', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'jetpack_markdown_load_textdomain' );

function jetpack_markdown_settings_link($actions) {
	return array_merge(
		array( 'settings' => sprintf( '<a href="%s">%s</a>', 'options-discussion.php#' . WPCom_Markdown::COMMENT_OPTION, __( 'Settings', 'jetpack' ) ) ),
		$actions
	);
	return $actions;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'jetpack_markdown_settings_link' );
