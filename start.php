<?php
/**
 * Main file for this plugin
 */

require_once(dirname(__FILE__) . '/lib/hooks.php');
require_once(dirname(__FILE__) . '/lib/functions.php');

// register default Elgg events
elgg_register_event_handler('init', 'system', 'ckeditor_extended_init');

/**
 * Gets called when the system initializes
 *
 * @return void
 */
function ckeditor_extended_init() {
	elgg_register_page_handler('ckeditor', 'ckeditor_extended_page_handler');
	
	// extend CSS
	elgg_extend_view('css/elgg', 'css/ckeditor_extended.css');
	elgg_extend_view('css/admin', 'css/ckeditor_extended.css');
	
	// this way a simplecache JS file can be loaded from a .php file
	elgg_register_simplecache_view('js/elgg/ckeditor/config.js');
	
	// extend view
	elgg_extend_view('forms/plugins/settings/save', 'ckeditor_extended/example');
	
	// plugin hooks
	elgg_register_plugin_hook_handler('action', 'plugins/settings/save', 'ckeditor_extended_plugins_settings_save_action_hook');
	elgg_register_plugin_hook_handler('config', 'htmlawed', 'ckeditor_extended_htmlawed_config');
	
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'ckeditor_extended_longtext_menu');
	
	elgg_register_action('ckeditor_extended/delete', dirname(__FILE__) . '/actions/delete.php');
}

/**
 * Handles the ckeditor pages
 *
 * @param array $page requested page
 *
 * @return boolean
 */
function ckeditor_extended_page_handler($page) {
	switch($page[0]){
		case 'upload':
			include(dirname(__FILE__) . '/pages/upload.php');
			return true;
		case 'browse':
			include(dirname(__FILE__) . '/pages/browse.php');
			return true;
		case 'download':
			set_input('user_guid', $page[1]);
			set_input('file_name', $page[2]);
			include(dirname(__FILE__) . '/pages/download.php');
			return true;
	}
}
