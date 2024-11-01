<?php

/**
 * The file that defines the core plugin class
 */

class woocpw{
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */	
	protected $loader;

	public function __construct(){

		$this->woocpw_load_dependencies();
		$this->woocpw_set_locale();
		$this->woocpw_load();
		$this->woocpw_load_ajax_hooks();

	}

	/**
	 *	Load all dependencies for localization, admin and public classes
	 */
	private function woocpw_load_dependencies(){

		require_once( WOOCPW_CLASSES_DIR . 'class-woocpw-loader.php' );
		require_once( WOOCPW_CLASSES_DIR . 'class-woocpw-i18n.php');
		require_once( WOOCPW_CLASSES_DIR . 'class-widget.php');
		$this->loader = new WOOCPW_Loader();
	}

	/**
	 *	Set internationalization for plugin
	 */

	private function woocpw_set_locale() {
		$plugin_i18n = new WOOCPW_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'woocpw_load_plugin_textdomain' );
	}


	/**
	 * Load widget hooks
	 */
	public function woocpw_load(){
		$this->loader->add_action( 'widgets_init',$this,'woocpw_widget_init',10);
		$woocp_widget= new WOOCP_Widget();

	}


	/**
	 * Initialize widget
	 */
	public function woocpw_widget_init(){
		register_widget( 'WOOCP_Widget' );
	}
	
	/**
	 *	Load ajax hooks
	 */

	public function woocpw_load_ajax_hooks(){
            
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class the hooks with the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}