<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 */

class WOOCPW_i18n{

	public function woocpw_load_plugin_textdomain() {
		load_plugin_textdomain(WOOCPW_NAME,false,WOOCPW_DIR . 'languages/');
	}
	
}