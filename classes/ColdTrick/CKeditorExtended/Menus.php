<?php

namespace ColdTrick\CKeditorExtended;

/**
 * Menus
 *
 * @package    ColdTrick
 * @subpackage CKeditorExtended
 */
class Menus {
	
	/**
	 * Removes editor toggler if wanted
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:longtext'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerLongtextMenu(\Elgg\Hook $hook) {
		
		$show_toggler_setting = elgg_get_plugin_setting('show_html_toggler', 'ckeditor_extended');
	
		if ($show_toggler_setting == 'yes' || ($show_toggler_setting == 'admin_only' && elgg_is_admin_logged_in())) {
			return;
		}
		
		$result = $hook->getValue();
		$result->remove('ckeditor_toggler');
		return $result;
	}
}
