<?php

namespace ColdTrick\CKEditorExtended;

/**
 * HTMLawed
 *
 * @package    ColdTrick
 * @subpackage CKEditorExtended
 */
class HTMLawed {
	
	/**
	 * Extends the HTMLawed config
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:longtext'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function extendConfig(\Elgg\Hook $hook) {
		
		$result = $hook->getValue();
		
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
	
			$result['elements'] = $elements;
		}
	
		$htmlawed_schemes = elgg_get_plugin_setting('htmlawed_schemes', 'ckeditor_extended');
		if (!empty($htmlawed_schemes)) {
	
			$current_schemes = elgg_extract('schemes', $result, '*:http');
	
			$htmlawed_schemes = $current_schemes . ',' . ltrim($htmlawed_schemes, ',');
			$result['schemes'] = $htmlawed_schemes;
		}
	
		return $result;
	}
}
