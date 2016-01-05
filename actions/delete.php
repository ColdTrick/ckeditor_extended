<?php

$user_guid = (int) get_input('guid');
$site_guid = (int) elgg_get_site_entity()->getGUID();
$name = get_input('name');

if (empty($user_guid) || empty($site_guid) || empty($name)) {
	return;
}

if (elgg_get_logged_in_user_guid() !== $user_guid) {
	return;
}

$user_path = ckeditor_extended_get_upload_path($user_guid);

$filename = $user_path . $name;

unlink($filename);
