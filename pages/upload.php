<?php

gatekeeper();

$user_guid = elgg_get_logged_in_user_guid();
$site_guid = elgg_get_site_entity()->getGUID();
$path = ckeditor_extended_get_upload_path($user_guid);
if (!file_exists($path)) {
	if (!@mkdir($path, 0700, true)) {
		exit();
	}
}

$upload = elgg_extract("upload", $_FILES);

if (get_resized_image_from_uploaded_file("upload", 200, 200)) {
	
	$filename = $upload["name"];
	
	// check for uniqueness
	if (elgg_get_plugin_setting('overwrite_uploaded_images', 'ckeditor_extended') === 'no') {
		while (file_exists($path . $filename)) {
			$filename .= '1';
		}
	}
	
 	move_uploaded_file($upload["tmp_name"], $path . $filename);
 	
 	$funcNum = get_input('CKEditorFuncNum');
 		
 	$url = elgg_normalize_url('/mod/ckeditor_extended/pages/thumbnail.php?guid=' . $user_guid . '&name=' . $filename . '&site_guid=' . $site_guid);
 	
 	echo elgg_format_element('script', ['type' => 'text/javascript'], "window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '');");
} else {
	echo elgg_echo("ckeditor_extended:upload:no_image");
}
