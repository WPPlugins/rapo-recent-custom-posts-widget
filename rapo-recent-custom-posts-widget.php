<?php
/**
 * Plugin Name:  Rapo Recent Custom Posts Widget
 * Plugin URI:   http://smenaka.rapo.in/plugins/rapo-recent-custom-posts-widget/
 * Description:  Recent posts by post type/custom post type with only thumbnails option
 * Version:      1.1.0
 * Author:       Menaka S.
 * Author URI:   http://smenaka.rapo.in/
 * Author Email: menakas@yahoo.com
 * Text Domain:  rapo-recent-custom-posts-widget
 * Domain Path:  /lang
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    recent-custom-posts-widget
 * @since      0.1
 * @author     menakas
 * @copyright  Copyright (c) 2016, Menaka S
 * @license    http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

register_activation_hook( __FILE__, 'rapo_rcpw_activate' );
register_deactivation_hook( __FILE__, 'rapo_rcpw_deactivate' );
register_uninstall_hook( __FILE__, 'rapo_rcpw_uninstall' );

new Rapo_Recent_Custom_Posts;

function rapo_rcpw_activate(){
	
}

function rapo_rcpw_deactivate(){
	
}

function rapo_rcpw_uninstall(){
unregister_widget( 'Rapo_Recent_Custom_Posts_Widget' );

}

class Rapo_Recent_Custom_Posts {

	/**
	 * PHP5 constructor method.
	 *
	 * @since  0.1
	 */
	public function __construct() {

		// Set the constants
		add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );

		// Internationalize the text strings used.
		add_action( 'plugins_loaded', array( &$this, 'i18n' ), 2 );

		// Load the admin style
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );

		// Load the style
		add_action( 'wp_enqueue_scripts', array( &$this, 'rapo_rcpw_style' ) );

		// Register widget
		add_action( 'widgets_init', array( &$this, 'register_widget' ) );

	}

	/**
	 * Define constants (optional)
	 *
	 * @since  0.1
	 */
	public function constants() {

		// Set constant path to the plugin directory.
		define( 'rapo_recent_custom_posts_widget_DIR', plugin_dir_path( __FILE__ ) ) ;

		// Set the constant path to the plugin directory URI.
		define( 'rapo_recent_custom_posts_widget_URL', plugin_dir_url( __FILE__ ) ) ;

		// Set the constant path to the widgets directory.
		define( 'rapo_recent_custom_posts_widget_WIDGETS', rapo_recent_custom_posts_widget_DIR . trailingslashit( 'widgets' ) );

		// Set the constant path to the assets directory.
		define( 'rapo_recent_custom_posts_widget_ASSETS', rapo_recent_custom_posts_widget_URL . trailingslashit( 'assets' ) );

	}

	/**
	 * Loads the translation files.
	 *
	 * @since  0.1
	 */
	public function i18n() {
		load_plugin_textdomain( 'about-us-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}


	/**
	 * Register custom style for the frontend settings.
	 *
	 * @since  0.1
	 */
	public function rapo_rcpw_style() {
		// Loads the widget style.
		wp_enqueue_style( 'rapo_recent_custom_posts_widget-style', trailingslashit( rapo_recent_custom_posts_widget_ASSETS ) . 'css/style.css', null, null );
	}


	/**
	 * Register custom style for the widget settings.
	 *
	 * @since  0.1
	 */
	public function admin_scripts() {
		// Loads the widget style.
		wp_enqueue_style( 'rapo_recent_custom_posts_widget-admin-style', trailingslashit( rapo_recent_custom_posts_widget_ASSETS ) . 'css/rapo_recent_custom_posts_widget-admin.css', null, null );

		// Loads the widget scripts.
		wp_enqueue_script( 'rapo_recent_custom_posts_widget-admin-script', trailingslashit( rapo_recent_custom_posts_widget_ASSETS ) . 'js/rapo_recent_custom_posts_widget-admin.js', array(), false, true );
	}

	/**
	 * Register the widget.
	 *
	 * @since  0.1
	 */
	public function register_widget() {
		require_once( rapo_recent_custom_posts_widget_WIDGETS . '/rapo-recent-custom-posts-widget-core.php' );
		register_widget( 'Rapo_Recent_Custom_Posts_Widget' );
	}

}

