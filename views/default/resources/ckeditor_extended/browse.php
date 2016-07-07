<?php

gatekeeper();

$funcNum = elgg_extract('CKEditorFuncNum', $vars);
$user_guid = elgg_get_logged_in_user_guid();
$site_guid = elgg_get_site_entity()->getGUID();
$path = ckeditor_extended_get_upload_path($user_guid);
$dir = @opendir($path);

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
if ($dir) {
	while (($file = readdir($dir)) !== false) {
		if (!is_dir($file)) {
			$src = 'mod/ckeditor_extended/pages/thumbnail.php?guid=' . $user_guid . '&name=' . $file . '&site_guid=' . $site_guid;
			$img = elgg_view('output/img', [
				'src' => $src,
				'alt' => $file,
			]);
			
			$text = elgg_view('output/url',[
				'text' => elgg_view_icon('delete-alt-hover'),
				'href' => 'action/ckeditor_extended/delete?guid=' . $user_guid . '&name=' . $file . '&site_guid=' . $site_guid,
				'class' => 'float-alt elgg-discoverable ckeditor-delete-file',
				'title' => elgg_echo('delete'),
			]);
			$text .= $file;
			
			$user_files .= elgg_format_element('li', [
				'class' => 'elgg-discover elgg-divide-bottom',
				'data-embed-url' => elgg_get_site_url() . 'mod/ckeditor_extended/pages/thumbnail.php?guid=' . $user_guid . '&site_guid=' . $site_guid . '&name=' . $file,
			], elgg_view_image_block($img, $text, ['class' => 'pam']));
		}
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
	'head' => elgg_view('page/elements/head'),
	'body' => $body,
]);
