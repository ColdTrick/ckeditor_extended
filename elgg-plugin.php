<?php

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

return [
	'settings' => [
// 		'generate_username_from_email' => 'no',
		
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
