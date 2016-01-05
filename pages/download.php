<?php
// Used for deprecated purposes
$user_guid = (int) get_input('user_guid');
$file_name = get_input('file_name');
$site_guid = (int) elgg_get_site_entity()->getGUID();

$etag = $name . $site_guid . $guid;
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
	header('HTTP/1.1 304 Not Modified');
	exit;
}
header('HTTP/1.1 301 Moved Permanently');
header('Content-type: image/jpeg');
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime('+6 months')), true);
header('Pragma: public');
header('Cache-Control: public');
header("ETag: '{$etag}'");

forward('mod/ckeditor_extended/pages/thumbnail.php?guid=' . $user_guid . '&name=' . $file_name . '&site_guid=' . $site_guid);
