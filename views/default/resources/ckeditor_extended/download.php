<?php

use Elgg\Project\Paths;

// Used for deprecated purposes
$user_guid = (int) elgg_extract('user_guid', $vars);
$file_name = elgg_extract('file_name', $vars);
$file_name = Paths::sanitize($file_name, false);

$fh = ckeditor_extended_get_file_handler($user_guid);
$fh->setFilename($file_name);

forward(elgg_get_inline_url($fh), 301);
