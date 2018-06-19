<?php
/**
 * Main file for this plugin
 */

require_once(dirname(__FILE__) . '/lib/hooks.php');
require_once(dirname(__FILE__) . '/lib/functions.php');

// register default Elgg events
elgg_register_event_handler('plugins_boot', 'system', 'ckeditor_extended_boot');
elgg_register_event_handler('init', 'system', 'ckeditor_extended_init');

/**
 * Called during plugins_boot
 *
 * @return void
 */
function ckeditor_extended_boot() {
	
	if (elgg_get_config('system_cache_loaded')) {
		// view location is cached, no need to register it
		return;
	}
	
	$editor_version = elgg_get_plugin_setting('editor_version', 'ckeditor_extended');
	$supported_versions = [
		// '4.6.2', // technically this is supported but it's also the default in views.php
		'4.7.3',
		'4.8.0',
		'4.9.2',
	];
	if (!in_array($editor_version, $supported_versions)) {
		return;
	}
	
	$view_specs = [
		'default' => [
			'ckeditor.js' => __DIR__ . "/vendors/ckeditor/{$editor_version}/ckeditor.js",
			'ckeditor/' => __DIR__ . "/vendors/ckeditor/{$editor_version}/",
			'jquery.ckeditor.js' => __DIR__ . "/vendors/ckeditor/{$editor_version}/adapters/jquery.js",
		],
	];
	_elgg_services()->views->mergeViewsSpec($view_specs);
}

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
	
	// extend JS
	elgg_extend_view('elgg.js', 'js/ckeditor_extended/image_upload.js');
	elgg_extend_view('elgg.js', 'js/ckeditor_extended/image_browse.js');
	
	// this way a simplecache JS file can be loaded from a .php file
	elgg_register_simplecache_view('js/elgg/ckeditor/config.js');
	
	// extend view
	elgg_extend_view('forms/plugins/settings/save', 'ckeditor_extended/example');
	elgg_extend_view('input/longtext', 'ckeditor_extended/textarea');
	
	// plugin hooks
	elgg_register_plugin_hook_handler('action', 'plugins/settings/save', 'ckeditor_extended_plugins_settings_save_action_hook');
	elgg_register_plugin_hook_handler('config', 'htmlawed', 'ckeditor_extended_htmlawed_config');
	
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'ckeditor_extended_longtext_menu');
	
	elgg_register_action('ckeditor_extended/delete', dirname(__FILE__) . '/actions/delete.php');
	elgg_register_action('ckeditor_extended/inline_edit', dirname(__FILE__) . '/actions/inline_edit.php', 'admin');
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
			echo elgg_view_resource('ckeditor_extended/upload', [
				'CKEditorFuncNum' => get_input('CKEditorFuncNum'),
				'responseType' => get_input('responseType'),
			]);
			return true;
		case 'browse':
			echo elgg_view_resource('ckeditor_extended/browse', [
				'CKEditorFuncNum' => get_input('CKEditorFuncNum'),
			]);
			return true;
		case 'download':
			set_input('user_guid', $page[1]);
			set_input('file_name', $page[2]);
			include(dirname(__FILE__) . '/pages/download.php');
			return true;
	}
}
