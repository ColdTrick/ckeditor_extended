<?php

gatekeeper();

$user_guid = elgg_get_logged_in_user_guid();
$path = ckeditor_extended_get_upload_path($user_guid);
if (!file_exists($path)) {
	if (!@mkdir($path, 0700, true)) {
		exit();
	}
}
$upload = elgg_extract("upload", $_FILES);

if ($upload["name"]) {
 	move_uploaded_file($upload["tmp_name"], $path . $upload["name"]);
 	
 	$funcNum = get_input('CKEditorFuncNum');
 	
 	$url = '/ckeditor/download/' . $user_guid . '/' . $upload["name"];
 	
 	echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '');</script>";
}
