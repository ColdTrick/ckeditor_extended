<?php

namespace ColdTrick\CKEditorExtended;

class Views {

	/**
	 * Cleanup empty paragraphs (<p>&nbsp;</p>) from longtexts
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'output/longtext'
	 *
	 * @return void|array
	 */
	public static function stripEmptyClosingParagraph(\Elgg\Hook $hook) {
		
		$vars = $hook->getValue();
		if (empty($vars['value'])) {
			return;
		}
		
		$vars['value'] = preg_replace('/((\r\n|\r|\n)*<p>(&nbsp;)*<\/p>)+$/', '', trim($vars['value']));
		
		return $vars;
	}
}
