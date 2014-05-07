<?php

$user_guid = get_input("user_guid");
$file_name = get_input("file_name");

$path = ckeditor_extended_get_upload_path($user_guid) . $file_name;
readfile($path);
exit();