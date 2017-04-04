<?php

// Used for deprecated purposes
$user_guid = (int) get_input('user_guid');
$file_name = get_input('file_name');

$fh = ckeditor_extended_get_file_handler($user_guid);
$fh->setFilename($file_name);

forward(elgg_get_inline_url($fh), 301);
