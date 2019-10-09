<?php
/**
 * The RocketGeek_jQuery_Tabs class.
 *
 * Provides a class to invoke jQuery UI tabs for WordPress plugins.
 *
 * @link URL
 *
 * @package WordPress
 * @subpackage RocketGeek_jQuery_Tabs
 * @since 1.0.0
 */

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

		wp_register_style( 'jquery-ui-style', plugin_dir_url( __FILE__ ) . 'assets/css/jquery-ui.min.css' );
		wp_enqueue_style ( 'jquery-ui-style' );
		
		//wp_register_style( 'rktgk-ui-tabs', plugin_dir_url( __FILE__ ) . 'assets/css/rktgk-tabs.css' );
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
	public static function tabs( $tabs, $tag = 'rktgk_tabs', $echo = true ) {
		
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
		$tabs = apply_filters( 'rktgk_jquery_tabs', $tabs, $tag ); 

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
		background: #008ec2;
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
				$html .= apply_filters( 'rktgk_jquery_tabs_content', $content, $key, $tag );
				
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