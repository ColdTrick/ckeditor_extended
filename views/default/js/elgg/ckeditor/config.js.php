<?php
/**
 * Overrule the default configuration of the CKEditor with those of the plugin settings
 */

$settings = elgg_get_plugin_setting("editor_config", "ckeditor_extended");
if (empty($settings)) {
	// revert to the basic settings
	$settings = <<<JS
toolbar: [['Bold', 'Italic', 'Underline'], ['Strike', 'NumberedList', 'BulletedList', 'Undo', 'Redo', 'Link', 'Unlink', 'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']],
removeButtons: 'Subscript,Superscript', // To have Underline back
allowedContent: true,
baseHref: elgg.config.wwwroot,
removePlugins: 'contextmenu,tabletools,resize',
defaultLanguage: 'en',
language: elgg.config.language,
skin: 'moono',
uiColor: '#EEEEEE',
contentsCss: elgg.get_simplecache_url('css', 'elgg/wysiwyg.css'),
disableNativeSpellChecker: false,
disableNativeTableHandles: false,
removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
autoGrow_maxHeight: $(window).height() - 100,
JS;
}

?>
define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');

	return {
		<?php echo $settings; ?>
	};
});
