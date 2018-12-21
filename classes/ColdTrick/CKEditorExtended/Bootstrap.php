<?php

namespace ColdTrick\CKEditorExtended;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function init() {
		
		elgg_extend_view('css/elgg', 'css/ckeditor_extended.css');
		elgg_extend_view('css/admin', 'css/ckeditor_extended.css');
		
		elgg_extend_view('elgg.js', 'js/ckeditor_extended/image_upload.js');
		elgg_extend_view('elgg.js', 'js/ckeditor_extended/image_browse.js');
		elgg_extend_view('input/longtext', 'ckeditor_extended/textarea');
		
		// this way a simplecache JS file can be loaded from a .php file
		elgg_register_simplecache_view('js/elgg/ckeditor/config.js');
		
		// register plugin hook handlers
		$hooks = $this->elgg()->hooks;
		
		$hooks->registerHandler('config', 'htmlawed', '\ColdTrick\CKEditorExtended\HTMLawed::extendConfig');
		$hooks->registerHandler('register', 'menu:longtext', '\ColdTrick\CKEditorExtended\Menus::registerLongtextMenu', 999);
		
		$this->extendCKEditorViews();
	}
	
	/**
	 * Register view extends for ckeditor js code
	 */
	protected function extendCKEditorViews() {
		
		elgg_extend_view('ckeditor/ckeditor.js', 'ckeditor/plugins/filetools/plugin.js');
		elgg_extend_view('ckeditor/ckeditor.js', 'ckeditor/plugins/lineutils/plugin.js');
		elgg_extend_view('ckeditor/ckeditor.js', 'ckeditor/plugins/notificationaggregator/plugin.js');
		elgg_extend_view('ckeditor/ckeditor.js', 'ckeditor/plugins/uploadimage/plugin.js');
		elgg_extend_view('ckeditor/ckeditor.js', 'ckeditor/plugins/uploadwidget/plugin.js');
		elgg_extend_view('ckeditor/ckeditor.js', 'ckeditor/plugins/widget/plugin.js');
		elgg_extend_view('ckeditor/ckeditor.js', 'ckeditor/plugins/widgetselection/plugin.js');
		
		$languages = ['en'];
		$languages[] = get_current_language();
		$languages = array_unique($languages);
		
		$plugins = [
			'filetools',
			'uploadwidget',
			'widget',
		];
		
		foreach ($languages as $lang) {
			foreach ($plugins as $plugin) {
				elgg_extend_view("ckeditor/lang/{$lang}.js", "ckeditor/plugins/{$plugin}/lang/{$lang}.js");
			}
		}
	}
}
