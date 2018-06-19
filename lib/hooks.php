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
	
	$plugin_id = get_input('plugin_id');
	if ($plugin_id === 'ckeditor_extended') {
		elgg_invalidate_simplecache();
		elgg_reset_system_cache(); // needed for version switching
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
	if (!is_array($returnvalue)) {
		return $returnvalue;
	}
	
	$show_toggler_setting = elgg_get_plugin_setting('show_html_toggler', 'ckeditor_extended', 'yes');
	
	if ($show_toggler_setting == 'yes' || ($show_toggler_setting == 'admin_only' && elgg_is_admin_logged_in())) {
		return $returnvalue;
	}
		
	foreach ($returnvalue as $key => $item) {
		if ($item->getName() == 'ckeditor_toggler') {
			unset($returnvalue[$key]);
		}
	}
	
	return $returnvalue;
}

/**
 * Extends the current config of htmlawed
 *
 * @param string $hook        hookname
 * @param string $type        hooktype
 * @param array  $returnvalue returnvalue
 * @param array  $params      params
 *
 * @return void
 */
function ckeditor_extended_htmlawed_config($hook_name, $entity_type, $return_value, $params){

	if (!is_array($return_value)) {
		return $return_value;
	}
	
	// Extend valid input elements
	$htmlawed_elements = elgg_get_plugin_setting('htmlawed_elements', 'ckeditor_extended');

	if (!empty($htmlawed_elements)) {
		$ext_tags_array = explode(',', $htmlawed_elements);

		$elements = '*';

		foreach ($ext_tags_array as $fulltag) {
			$fulltag = trim(str_replace(array('[', ']'), ' ', $fulltag));
			$fulltag = explode(' ', $fulltag);

			$tag = $fulltag[0];
			
			$elements .= '+' . $tag;
		}

		$return_value['elements'] = $elements;
	}

	$htmlawed_schemes = elgg_get_plugin_setting('htmlawed_schemes', 'ckeditor_extended');
	if (!empty($htmlawed_schemes)) {

		$current_schemes = elgg_extract('schemes', $return_value, '*:http');

		$htmlawed_schemes = $current_schemes . ',' . ltrim($htmlawed_schemes, ',');
		$return_value['schemes'] = $htmlawed_schemes;
	}

	return $return_value;
}
