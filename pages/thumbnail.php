<?php
/**
 * Show the thumbnail
 */

// won't be able to serve anything if no guid
if (!isset($_GET['guid']) || !isset($_GET['site_guid']) || !isset($_GET['name'])) {
	header("HTTP/1.1 404 Not Found");
	exit;
}

$name = strtolower($_GET['name']);
$guid = (int) $_GET['guid'];
$site_guid = (int) $_GET['site_guid'];

// If is the same ETag, content didn't changed.
$etag = $name . $site_guid . $guid;
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
	header("HTTP/1.1 304 Not Modified");
	exit;
}

$engine_dir = dirname(dirname(dirname(dirname(__FILE__)))) . '/engine/';

// Get DB settings
require_once $engine_dir . 'settings.php';

global $CONFIG;

if (isset($CONFIG->dataroot)) {
	$data_root = $CONFIG->dataroot;
}

if (!isset($data_root)) {
	$mysql_dblink = @mysql_connect($CONFIG->dbhost, $CONFIG->dbuser, $CONFIG->dbpass, true);
	if ($mysql_dblink) {
		if (@mysql_select_db($CONFIG->dbname, $mysql_dblink)) {
			$q = "SELECT name, value FROM {$CONFIG->dbprefix}datalists WHERE name = 'dataroot'";
			$result = mysql_query($q, $mysql_dblink);
			if ($result) {
				$row = mysql_fetch_object($result);
				while ($row) {
					if ($row->name == 'dataroot') {
						$data_root = $row->value;
					}
	
					$row = mysql_fetch_object($result);
				}
			}
	
			@mysql_close($mysql_dblink);
		}
	}
}

if (isset($data_root)) {
	$bucket_size = 500;
	
	$lower_bound = (int) max(floor($guid / $bucket_size) * $bucket_size, 1);
	
	$user_path = "ckeditor_upload/" . $site_guid . "/" . $lower_bound . "/" . $guid . "/";
	
	$filename = $data_root . $user_path . $name;
	$filecontents = @file_get_contents($filename);

	if ($filecontents) {
		$filesize = strlen($filecontents);
		
		header("Content-type: image/jpeg");
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
		header("Pragma: public");
		header("Cache-Control: public");
		header("Content-Length: $filesize");
		header("ETag: \"$etag\"");
		
		echo $filecontents;
		exit;
	}
}

// something went wrong so 404
header("HTTP/1.1 404 Not Found");
exit;
