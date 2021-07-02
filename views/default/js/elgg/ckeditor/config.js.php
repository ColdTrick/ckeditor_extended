<?php
/**
 * Overrule the default configuration of the CKEditor with those of the plugin settings
 */

$settings = elgg_get_plugin_setting('editor_config', 'ckeditor_extended');
if (empty($settings)) {
	// revert to the basic settings
	$settings = elgg_view('ckeditor_extended/default_config');
}

?>
define(['jquery', 'elgg'], function($, elgg) {
	return elgg.trigger_hook('config', 'ckeditor', {'editor': 'default'}, {
		<?php echo $settings ?>
	});
});
