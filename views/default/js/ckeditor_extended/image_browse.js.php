<?php

if (elgg_get_plugin_setting('image_upload_browse', 'ckeditor_extended') !== 'yes') {
	return;
}

?>
//<script>
require(['jquery', 'elgg'], function($, elgg) {
	
	elgg.register_hook_handler('config', 'ckeditor', function(hook, type, params, returnValue) {

		returnValue.filebrowserImageBrowseUrl = ((elgg.is_logged_in()) ? elgg.normalize_url('ckeditor/browse') : false);
		returnValue.filebrowserImageWindowWidth = '640';
		returnValue.filebrowserImageWindowHeight = '480';

		return returnValue;
	});
});
