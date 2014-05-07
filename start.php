<?php
/**
 * Main file for this plugin
 */

require_once(dirname(__FILE__) . "/lib/hooks.php");

// register default Elgg events
elgg_register_event_handler("init", "system", "ckeditor_extended_init");

/**
 * Gets called when the system initializes
 *
 * @return void
 */
function ckeditor_extended_init() {
	// extend CSS
	elgg_extend_view("css/admin", "css/ckeditor_extended/admin");
	
	// this way a simplecache JS file can be loaded from a .php file
	elgg_register_simplecache_view("js/elgg/ckeditor/config.js");
	
	// extend view
	elgg_extend_view("forms/plugins/settings/save", "ckeditor_extended/example");
	
	// plugin hooks
	elgg_register_plugin_hook_handler("action", "plugins/settings/save", "ckeditor_extended_plugins_settings_save_action_hook");
	
	elgg_register_plugin_hook_handler("register", "menu:longtext", "ckeditor_extended_longtext_menu");
}