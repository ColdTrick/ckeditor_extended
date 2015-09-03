<?php

gatekeeper();

$funcNum = get_input('CKEditorFuncNum');
$user_guid = elgg_get_logged_in_user_guid();
$site_guid = elgg_get_site_entity()->getGUID();
$path = ckeditor_extended_get_upload_path($user_guid);
$dir = @opendir($path);

$files = '';
if ($dir) {
	while (($file = readdir($dir)) !== false) {
		if (!is_dir($file)) {
			$src = 'mod/ckeditor_extended/pages/thumbnail.php?guid=' . $user_guid . '&name=' . $file . '&site_guid=' . $site_guid;
			$img = elgg_view('output/img', [
				'src' => $src,
				'alt' => $file
			]);
			
			$text = elgg_view('output/url',[
				'text' => elgg_view_icon('delete-alt-hover'),
				'href' => 'action/ckeditor_extended/delete?guid=' . $user_guid . '&name=' . $file . '&site_guid=' . $site_guid,
				'class' => 'float-alt elgg-discoverable ckeditor-delete-file',
				'title' => elgg_echo('delete')
			]);
			$text .= $file;
			
			$files .= elgg_format_element('li', [
				'class' => 'elgg-discover elgg-divide-bottom',
				'data-user-guid' => $user_guid,
				'data-site-guid' => $site_guid,
				'data-file-name' => $file
			], elgg_view_image_block($img, $text, ['class' => 'pam']));
		}
	}
}

$body = elgg_format_element('ul', [
	'class' => 'ckeditor-extended-browse elgg-divide-top elgg-divide-left elgg-divide-right mam',
	'rel' => $funcNum
], $files);

$body .= elgg_format_element('script', ['type' => 'text/javascript'], 'require(["ckeditor_extended/browse_files"]);');

echo elgg_view('page/elements/html', [
	'head' => elgg_view('page/elements/head'),
	'body' => $body
]);
