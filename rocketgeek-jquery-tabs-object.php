<?php
/**
 * RocketGeek jQuery UI Tabs Object
 *
 * Provides an object class to invoke jQuery UI tabs for use in WordPress
 * plugins and themes.  See the readme.md for initial instructions.
 *
 * This library is open source and Apache-2.0 licensed. I hope you find it 
 * useful for your project(s). Attribution is appreciated ;-)
 *
 * @package    RocketGeek_jQuery_Tabs
 * @version    1.1.0
 *
 * @link       https://github.com/rocketgeek/jquery_tabs/
 * @author     Chad Butler <https://butlerblog.com>
 * @author     RocketGeek <https://rocketgeek.com>
 * @copyright  Copyright (c) 2019-2025 Chad Butler
 * @license    Apache-2.0
 *
 * Copyright [2025] Chad Butler, RocketGeek
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
 * A class to invoke jQuery UI tabs for WordPress plugins.
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
	public $stem = "rktgk_";
	
	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->load_hooks();
	}
	
	/**
	 * Load hooks.
	 *
	 * @since 1.0.0
	 */
	private function load_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}
	
	/**
	 * Determine whether to load minified scripts and styles.
	 * 
	 * @since 1.2.0
	 */
	private function get_suffix() {
		return ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
	}

	/**
	 * Enqueue jQuery
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
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
		$jquery_ui_style = apply_filters( $this->stem . 'jquery_ui_style', plugin_dir_url( __FILE__ ) . 'assets/css/jquery-ui' . $this->get_suffix() . '.css' );
		wp_enqueue_style ( 'jquery-ui-style' );
		
		//wp_register_style( 'rktgk-ui-tabs', plugin_dir_url( __FILE__ ) . 'assets/css/rktgk-tabs' . $this->get_suffix() . '.css' );
		//wp_enqueue_style ( 'rktgk-ui-tabs' );
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
	 * @param  string  $tag (default:default)
	 */
	public function tabs( $tabs, $tag = 'rktgk_tabs', $echo = true ) {
		
		$tag = ( '' == $tag ) ? 'rktgk_tabs' : $tag;
		
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
		$tabs = apply_filters( $this->stem . 'jquery_tabs', $tabs, $tag ); 

		if ( ! empty( $tabs ) ) { 
			
			$html = '
<style>
	#' . $tag . '{ 
		padding: 0px; 
		background: none; 
		border-width: 0px; 
	} 
	#' . $tag . ' .ui-tabs-nav { 
		padding-left: 0px; 
		background: transparent; 
		border-width: 0px 0px 1px 0px; 
		-moz-border-radius: 0px; 
		-webkit-border-radius: 0px; 
		border-radius: 0px; 
	} 
	#' . $tag . ' .ui-tabs-panel { 
		background: #fff; 
		border-width: 0px 1px 1px 1px; 
	}
	#' . $tag . ' .ui-state-active {
		border: 1px solid #006799; 
		background: #0085ba;
	}
	#' . $tag . ' .ui-state-active a {
		color: #fff;
	}
</style>
<script>
	jQuery(document).ready(function($){
		$("#' . $tag . '").tabs();
	});
</script>';
			$html .= '<div id="' . esc_attr( $tag ) . '">';
			$html .= '<ul>';
			foreach ( $tabs as $key => $value ) {
				$id = "#" . $tag . "-" . $key;
				$class = ( isset( $_GET['ui-tabs-active'] ) && $key == $_GET['ui-tabs-active'] ) ? ' class="ui-tabs-active ui-state-active"' : '';
				$html .= '<li' . $class . '><a href="' . esc_attr( $id ) . '">' . esc_html( $value['tab'] ) . '</a></li>';
			}
			$html .= '</ul>';
			foreach ( $tabs as $key => $value ) {
				$id = $tag . "-" . $key;
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
				$html .= apply_filters( $this->stem . 'jquery_tabs_content', $content, $key, $tag );
				
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
endif;