<?php
/**
 * All plugin hooks are bundled here
 */

/**
 * Listen to the saving of plugin settings, if the plugin is this plugin invalidate simplecache
 * 
 * @param string $hook        'action'
 * @param string $type        'plugins/settings/save'
 * @param bool   $returnvalue false to stop the action
 * @param null   $params      null
 * 
 * @return void
 */
function ckeditor_extended_plugins_settings_save_action_hook($hook, $type, $returnvalue, $params) {
	
	$plugin_id = get_input("plugin_id");
	if ($plugin_id === "ckeditor_extended") {
		elgg_invalidate_simplecache();
	}
}

/**
 * Listen to the saving of plugin settings, if the plugin is this plugin invalidate simplecache
 * 
 * @param string $hook        hookname
 * @param string $type        hooktype
 * @param array  $returnvalue returnvalue
 * @param array  $params      params
 * 
 * @return void
 */
function ckeditor_extended_longtext_menu($hook, $type, $returnvalue, $params) {
	$show_toggler_setting = elgg_get_plugin_setting("show_html_toggler", "ckeditor_extended");
	if (!is_array($returnvalue) || empty($show_toggler_setting)) {
		return $returnvalue;
	}
	
	if ($show_toggler_setting == "no" || ($show_toggler_setting == "admin_only" && !elgg_is_admin_logged_in())) {
		foreach ($returnvalue as $key => $item) {
			if ($item->getName() == "ckeditor_toggler") {
				unset($returnvalue[$key]);
			}
		}
		
		return $returnvalue;
	}
}