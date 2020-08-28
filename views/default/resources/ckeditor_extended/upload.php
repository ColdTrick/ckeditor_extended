<?php

use Elgg\Project\Paths;

elgg_gatekeeper();

$user_guid = elgg_get_logged_in_user_guid();

$response_params = [
	'response_type' => elgg_extract('responseType', $vars, get_input('responseType')),
	'funcNum' => elgg_extract('CKEditorFuncNum', $vars, get_input('CKEditorFuncNum')),
];

$upload = elgg_get_uploaded_files('upload');
if (empty($upload)) {
	$response_params['error'] = elgg_echo('ckeditor_extended:upload:no_upload');
	
	echo ckeditor_extended_get_file_upload_response($response_params);
	return;
}

/* @var $upload \Symfony\Component\HttpFoundation\File\UploadedFile */
$upload = $upload[0];
if (!$upload->isValid()) {
	$response_params['error'] = $upload->getErrorMessage();
	
	echo ckeditor_extended_get_file_upload_response($response_params);
	return;
}

// check if it's an image
if (elgg()->mimetype->getSimpleType($upload->getMimeType()) !== 'image') {
	$response_params['error'] = elgg_echo('ckeditor_extended:upload:no_image');
	
	echo ckeditor_extended_get_file_upload_response($response_params);
	return;
}

$filename = $upload->getClientOriginalName();
$filename = Paths::sanitize($filename, false);

$prefix = '';
if ($response_params['response_type'] === 'json') {
	// store pasted images in different location
	$prefix = 'paste' . DIRECTORY_SEPARATOR;
	// generate random filename for less naming conflicts
	$filename = md5(microtime(true)) . ".{$upload->guessExtension()}";
}

$fh = ckeditor_extended_get_file_handler($user_guid);
$fh->setFilename($prefix . $filename);

// check for uniqueness
if (elgg_get_plugin_setting('overwrite_uploaded_images', 'ckeditor_extended') === 'no' || !empty($prefix)) {
	// don't override manual uploaded files ans pasted files
	$filename_parts = explode('.', $filename);
	$ext = array_pop($filename_parts);
	$count = 0;
	while ($fh->exists()) {
		$count++;
		
		$filename = implode('.', $filename_parts);
		$filename .= "_{$count}";
		$filename .= ".{$ext}";
		
		$fh->setFilename($prefix . $filename);
	}
}

try {
	// touch file location in order to create the file
	$fh->open('write');
	$fh->close();
	
	// copy first as we can only rotate with a correct image extension
	$temp_file = elgg_get_temp_file();
	$temp_file->setFilename(uniqid() . basename($fh->getFilenameOnFilestore()));
	$temp_file->open('write');
	$temp_file->close();
	
	copy($upload->getPathname(), $temp_file->getFilenameOnFilestore());
	
	_elgg_services()->imageService->fixOrientation($temp_file->getFilenameOnFilestore());
	
	// resize image to save diskspace (2048x2048px)
	$success = elgg_save_resized_image($temp_file->getFilenameOnFilestore(), $fh->getFilenameOnFilestore(), [
		'w' => 2048,
		'h' => 2048,
		'square' => false,
		'upscale' => false,
	]);
	
} catch (Exception $e) {
	$response_params['error'] = $e->getMessage();
	
	if ($fh->exists()) {
		$fh->delete();
	}
	
	echo ckeditor_extended_get_file_upload_response($response_params);
	return;
}

if (empty($success)) {
	// remove new file
	$fh->delete();
	
	// report error
	$response_params['error'] = elgg_echo('ckeditor_extended:upload:resize_failed');
	
	echo ckeditor_extended_get_file_upload_response($response_params);
	return;
}

$response_params['uploaded'] = true;
$response_params['filename'] = $upload->getClientOriginalName();
$response_params['url'] = elgg_get_inline_url($fh);

echo ckeditor_extended_get_file_upload_response($response_params);
