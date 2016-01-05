<?php
/**
 * Show the thumbnail
 */

// won't be able to serve anything if no guid
if (!isset($_GET['guid']) || !isset($_GET['site_guid']) || !isset($_GET['name'])) {
	header("HTTP/1.1 404 Not Found");
	exit;
}

$name = $_GET['name'];
$guid = (int) $_GET['guid'];
$site_guid = (int) $_GET['site_guid'];

// If is the same ETag, content didn't changed.
$etag = md5($name . $site_guid . $guid);
if (isset($_SERVER["HTTP_IF_NONE_MATCH"])) {
	list ($etag_header) = explode("-", trim($_SERVER["HTTP_IF_NONE_MATCH"], "\""));
	if ($etag_header === $etag) {
		header("HTTP/1.1 304 Not Modified");
		exit;
	}
}

$autoload_root = dirname(dirname(dirname(__DIR__)));
if (!is_file("$autoload_root/vendor/autoload.php")) {
	$autoload_root = dirname(dirname(dirname($autoload_root)));
}
require_once "$autoload_root/vendor/autoload.php";

$data_root = \Elgg\Application::getDataPath();

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
