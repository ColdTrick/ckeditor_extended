<?php

namespace ColdTrick\CKeditorExtended;

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
		$hooks->registerHandler('action:validate', 'plugins/settings/save', function() {
			if (get_input('plugin_id') === 'ckeditor_extended') {
				elgg_invalidate_simplecache();
			}
		});
		$hooks->registerHandler('config', 'htmlawed', '\ColdTrick\CKeditorExtended\Menus::extendConfig');
		$hooks->registerHandler('register', 'menu:longtext', '\ColdTrick\CKeditorExtended\Menus::registerLongtextMenu');
	}
}
