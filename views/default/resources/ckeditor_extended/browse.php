<?php

elgg_gatekeeper();

$funcNum = elgg_extract('CKEditorFuncNum', $vars);

$site_files = '';
$user_files = '';

// asset library files
if (elgg_is_active_plugin('asset_library')) {
	$assets = elgg_get_entities_from_metadata([
		'type' => 'object',
		'subtype' => 'asset_file',
		'metadata_name_value_pairs' => ['simpletype' => 'image'],
		'limit' => false,
	]);
	
	foreach ($assets as $asset) {
		$img = elgg_view('output/img', [
			'src' => $asset->getInlineURL(),
			'alt' => $asset->getFileName(),
		]);
				
		$site_files .= elgg_format_element('li', [
			'class' => 'elgg-divide-bottom',
			'data-embed-url' => $asset->getInlineURL(),
		], elgg_view_image_block($img, $asset->getFileName(), ['class' => 'pam']));
	}
}

// user files
$user_guid = elgg_get_logged_in_user_guid();

$fs = new CKEditorFilestore();
$path = elgg_get_data_path() . $fs->getUploadPath($user_guid) . DIRECTORY_SEPARATOR;
$dir = @opendir($path);

$fh = ckeditor_extended_get_file_handler($user_guid);
if (!empty($fh) && !empty($dir)) {
	
	while (($file = readdir($dir)) !== false) {
		
		if (!is_file($path . $file)) {
			continue;
		}
		
		// set filename on handler
		$fh->setFilename($file);
		
		// generate inline URL
		$src = elgg_get_inline_url($fh);
		$img = elgg_view('output/img', [
			'src' => $src,
			'alt' => $file,
		]);
		
		$text = elgg_view('output/url',[
			'text' => elgg_view_icon('delete-alt-hover'),
			'href' => elgg_http_add_url_query_elements('action/ckeditor_extended/delete', [
				'guid' => $user_guid,
				'name' => $file,
			]),
			'is_action' => true,
			'class' => 'float-alt elgg-discoverable ckeditor-delete-file',
			'title' => elgg_echo('delete'),
		]);
		$text .= $file;
		
		$user_files .= elgg_format_element('li', [
			'class' => 'elgg-discover elgg-divide-bottom',
			'data-embed-url' => $src,
		], elgg_view_image_block($img, $text, ['class' => 'pam']));
	}
}

$body = '';
if (!empty($site_files)) {
	$list_title = elgg_echo('ckeditor_extended:browse:files:site');
	$list = elgg_format_element('ul', [
		'class' => 'ckeditor-extended-browse elgg-divide-top elgg-divide-left elgg-divide-right mam',
		'rel' => $funcNum,
	], $site_files);
	$body .= elgg_view_module('info', $list_title, $list);
}

if (!empty($user_files)) {
	$list_title = '';
	if (!empty($site_files)) {
		$list_title = elgg_echo('ckeditor_extended:browse:files:user');
	}
	$list = elgg_format_element('ul', [
		'class' => 'ckeditor-extended-browse elgg-divide-top elgg-divide-left elgg-divide-right mam',
		'rel' => $funcNum,
	], $user_files);
	$body .= elgg_view_module('info', $list_title, $list);
}

if (empty($body)) {
	$body .= elgg_echo('notfound');
}

$body .= elgg_format_element('script', [], 'require(["ckeditor_extended/browse_files"]);');
$body .= elgg_view('page/elements/foot');

echo elgg_view('page/elements/html', [
	'head' => elgg_view('page/elements/head', ['title' => '']),
	'body' => $body,
]);
