<?php
/**
 * Show the thumbnail
 *
 * @deprecated This page hase been replace and is only maintained for BC purpose
 */

use Elgg\Project\Paths;

// won't be able to serve anything if no guid
if (!isset($_GET['guid']) || !isset($_GET['name'])) {
	header("HTTP/1.1 404 Not Found");
	exit;
}

$autoload_root = dirname(dirname(dirname(__DIR__)));
if (!is_file("$autoload_root/vendor/autoload.php")) {
	$autoload_root = dirname(dirname(dirname($autoload_root)));
}
require_once "$autoload_root/vendor/autoload.php";

// load Elgg
\Elgg\Application::start();

$name = get_input('name');
$name = Paths::sanitize($name, false);

$guid = (int) get_input('guid');

// get file handler for user
$fh = ckeditor_extended_get_file_handler($guid);
$fh->setFilename($name);

// forward to new handler
forward(elgg_get_inline_url($fh), 301);
