<?php

// Used for deprecated purposes
$user_guid = (int) elgg_extract('user_guid', $vars);
$file_name = elgg_extract('file_name', $vars);

$fh = ckeditor_extended_get_file_handler($user_guid);
$fh->setFilename($file_name);

forward(elgg_get_inline_url($fh), 301);
