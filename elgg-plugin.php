<?php

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

return [
	'settings' => [
		'show_html_toggler' => 'yes',
		'image_upload_allowed' => 'no',
		'image_upload_browse' => 'no',
		'overwrite_uploaded_images' => 'yes',
		
	],
	'routes' => [
		'default:ckeditor_extended:upload' => [
			'path' => '/ckeditor_extended/upload',
			'resource' => 'ckeditor_extended/upload',
		],
		'default:ckeditor_extended:browse' => [
			'path' => '/ckeditor_extended/browse',
			'resource' => 'ckeditor_extended/browse',
		],
		'default:ckeditor_extended:download' => [
			'path' => '/ckeditor_extended/download/{user_guid}/{file_name}',
			'resource' => 'ckeditor_extended/download',
		],
	],
	'views' => [
		'default' => [
			'ckeditor.js' => __DIR__ . '/vendors/ckeditor/ckeditor.js',
			'ckeditor/' => __DIR__ . '/vendors/ckeditor/',
			'jquery.ckeditor.js' => __DIR__ . '/vendors/ckeditor/adapters/jquery.js',
		],
	],
	'actions' => [
		'ckeditor_extended/delete' => [],
		'ckeditor_extended/change_category' => ['access' => 'admin'],
	],
];
