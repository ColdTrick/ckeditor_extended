<?php
/**
 * All plugin hooks are bundled here
 */

/**
 * Listen to the saving of plugin settings, if the plugin is this plugin invalidate simplecache
 * 
 * @param string $hook        'action'
 * @param stirng $typae       'plugins/settings/save'
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