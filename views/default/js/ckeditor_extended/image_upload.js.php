<?php

if (elgg_get_plugin_setting('image_upload_allowed', 'ckeditor_extended') !== 'yes') {
	return;
}
	
?>
//<script>
require(['jquery', 'elgg'], function($, elgg) {
	
	elgg.register_hook_handler('config', 'ckeditor', function(hook, type, params, returnValue) {

		// add uploadimage plugin to loaded plugins
		var extraPlugins = returnValue.extraPlugins.split(',');
		if (typeof extraPlugin === null) {
			extraPlugins = [];
		}
		extraPlugins.push('uploadimage');

		// prevent the blockimagepaste plugin from being loaded
		function removeBlockImagePaste(value) {
			return value !== 'blockimagepaste';
		}
		extraPlugins = extraPlugins.filter(removeBlockImagePaste);
		returnValue.extraPlugins = extraPlugins.join(',');
		
		// make sure uploadimage plugin isn't blocked
		var removePlugins = returnValue.removePlugins.split(',');
		if (Array.isArray(removePlugins)) {
			function filterPlugin(value) {
				return value !== 'uploadimage';
			}
			
			removePlugins = removePlugins.filter(filterPlugin);
			returnValue.removePlugins = removePlugins.join(',');
		}

		// set upload url
		returnValue.filebrowserImageUploadUrl = ((elgg.is_logged_in()) ? elgg.normalize_url('ckeditor/upload') : false);
		
		return returnValue;
	});
});
