<?php
/**
 * RocketGeek jQuery UI Tabs Library
 *
 * Provides a class to invoke jQuery UI tabs for use in WordPress
 * plugins and themes.  See the readme.md for initial instructions.
 *
 * This library is open source and Apache-2.0 licensed. I hope you find it useful
 * for your project(s). Attribution is appreciated ;-)
 *
 * @package    RocketGeek_jQuery_Tabs
 * @version    1.1.0
 *
 * @link       https://github.com/rocketgeek/jquery_tabs/
 * @author     Chad Butler <https://butlerblog.com>
 * @author     RocketGeek <https://rocketgeek.com>
 * @copyright  Copyright (c) 2019-2021 Chad Butler
 * @license    Apache-2.0
 *
 * Copyright [2021] Chad Butler, RocketGeek
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     https://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// Make sure the class is only loaded once.
if ( ! class_exists( 'RocketGeek_jQuery_Tabs' ) ):
/**
 * The RocketGeek_jQuery_Tabs class.
 *
 * Provides a class to invoke jQuery UI tabs for WordPress plugins.
 *
 * @since 1.0.0
 */
class RocketGeek_jQuery_Tabs {
	
	/**
	 * Filter stem.
	 *
	 * @since 1.0.0
	 * @param string
	 */
	public static $stem = "rktgk_";
	
	/**
	 * Default ID tag.
	 *
	 * @since 1.1.0
	 * @apram string
	 */
	public static $tag = 'rktgk-tabs';
	
	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		self::loader();
	}
	
	/**
	 * Load hooks.
	 *
	 * @since 1.0.0
	 */
	private static function loader() {
		add_action( 'admin_enqueue_scripts', 'RocketGeek_jQuery_Tabs::enqueue' );
	}
	
	/**
	 * Enqueue jQuery
	 *
	 * @since 1.0.0
	 */
	public static function enqueue() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' ); // enqueue jQuery UI Core
		wp_enqueue_script( 'jquery-ui-tabs' ); // enqueue jQuery UI Tabs

		/**
		 * Filter the CSS file loaded.
		 *
		 * Allows you to load the non-minified CSS for debugging
		 * and other purposes. Load the non-minified CSS OR a 
		 * custom stylesheet.
		 *
		 * @since 1.0.0
		 *
		 * @param  string  $jquery_ui_style
		 */
		$jquery_ui_style = apply_filters( self::$stem . 'jquery_ui_style', plugin_dir_url( __FILE__ ) . 'assets/css/jquery-ui.min.css' );
		wp_enqueue_style ( 'jquery-ui-style' );
		
		wp_register_style( self::$tag . '-ui-tabs', plugin_dir_url( __FILE__ ) . 'assets/css/rktgk-tabs.css' );
		wp_enqueue_style ( self::$tag . '-ui-tabs' );
	}
	
	/**
	 * Invoke jQuery Tabs.
	 *
	 * @since 1.0.0
	 *
	 * @param  array   $tabs {
	 *    @type string $tab     (required)
	 *    @type string $content (optional)
	 * }
	 * @param  boolean $echo True echos the result, false returns as string (default:true)
	 */
	public static function tabs( $tabs, $echo = true ) {
		
		/**
		 * Load a set of tabs.
		 *
		 * @since 1.0.0
		 *
		 * @param array {
		 *    @type string $tab     (required)
		 *    @type string $content (optional)
		 * }
		 * @param string $tag
		 */
		$tabs = apply_filters( self::$stem . 'jquery_tabs', $tabs, self::$tag ); 

		if ( ! empty( $tabs ) ) { 
			
			$html = '
<script>
	jQuery(document).ready(function($){
		$("#' . self::$tag . '").tabs();
	});
</script>';
			$html .= '<div id="' . esc_attr( self::$tag ) . '">';
			$html .= '<ul>';
			foreach ( $tabs as $key => $value ) {
				$id = "#" . self::$tag . "-" . $key;
				$class = ( isset( $_GET['ui-tabs-active'] ) && $key == $_GET['ui-tabs-active'] ) ? ' class="ui-tabs-active ui-state-active"' : '';
				$html .= '<li' . $class . '><a href="' . esc_attr( $id ) . '">' . esc_html( $value['tab'] ) . '</a></li>';
			}
			$html .= '</ul>';
			foreach ( $tabs as $key => $value ) {
				$id = self::$tag . "-" . $key;
				$html .= '<div id="' . esc_attr( $id ) . '">';
				
				$content = ( isset( $value['content'] ) ) ? $value['content'] : '';
				
				/**
				 * Fires after a tab's content.
				 *
				 * @since 1.0.0
				 *
				 * @param string $content
				 * @param string $key
				 * @param string $tag
				 */
				$html .= apply_filters( self::$stem . 'jquery_tabs_content', $content, $key, self::$tag );
				
				$html .= '</div>';
			}
			$html .= '</div>';
			
			if ( $echo ) {
				echo $html;
			} else {
				return $html;
			}
		}
	}
}

// Initialize the tabs library.
RocketGeek_jQuery_Tabs::init();

endif;