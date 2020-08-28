<?php

use Elgg\Project\Paths;

$user_guid = (int) get_input('guid');
$name = get_input('name');
$name = Paths::sanitize($name, false);

if (empty($user_guid) || empty($name)) {
	return;
}

if (elgg_get_logged_in_user_guid() !== $user_guid) {
	return;
}

$fh = ckeditor_extended_get_file_handler($user_guid);
if (empty($fh)) {
	return;
}

$fh->setFilename($name);
error_log($fh->getFilenameOnFilestore());
if ($fh->exists()) {
	$fh->delete();
}
