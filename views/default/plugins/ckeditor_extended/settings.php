<?php
/**
 * Plugin settings for CKEditor Extended
 *
 * @uses $vars['entity'] the plugin entity
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

$noyes_options = [
	'no' => elgg_echo('option:no'),
	'yes' => elgg_echo('option:yes'),
];

$yesno_options = array_reverse($noyes_options, true);

$show_html_toggler_options = [
	'yes' => elgg_echo('option:yes'),
	'admin_only' => elgg_echo('ckeditor_extended:settings:show_html_toggler:option:admin_only'),
	'no' => elgg_echo('option:no'),
];

$editor_config = $plugin->editor_config;
if (empty($editor_config)) {
	$editor_config = elgg_view('ckeditor_extended/default_config');
}

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ckeditor_extended:settings:editor_version'),
	'#help' => elgg_echo('ckeditor_extended:settings:editor_version:help'),
	'name' => 'params[editor_version]',
	'value' => $plugin->editor_version,
	'options_values' => [
		'4.6.2' => elgg_echo('ckeditor_extended:settings:editor_version:4.6.2'),
		'4.7.3' => elgg_echo('ckeditor_extended:settings:editor_version:4.7.3'),
		'4.8.0' => elgg_echo('ckeditor_extended:settings:editor_version:4.8.0'),
		'4.9.2' => elgg_echo('ckeditor_extended:settings:editor_version:4.9.2'),
	],
]);

echo elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('ckeditor_extended:settings:editor_config'),
	'#help' => elgg_view('output/url', [
		'href' => 'http://docs.ckeditor.com/#!/api/CKEDITOR.config',
		'text' => elgg_echo('ckeditor_extended:settings:link'),
		'target' => '_blank',
	]),
	'name' => 'params[editor_config]',
	'value' => $editor_config,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ckeditor_extended:settings:show_html_toggler'),
	'name' => 'params[show_html_toggler]',
	'value' => $plugin->show_html_toggler,
	'options_values' => $show_html_toggler_options,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ckeditor_extended:settings:image_upload_allowed'),
	'#help' => elgg_echo('ckeditor_extended:settings:image_upload_allowed:help'),
	'name' => 'params[image_upload_allowed]',
	'value' => $plugin->image_upload_allowed,
	'options_values' => $noyes_options,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ckeditor_extended:settings:image_upload_browse'),
	'#help' => elgg_echo('ckeditor_extended:settings:image_upload_browse:help'),
	'name' => 'params[image_upload_browse]',
	'value' => $plugin->image_upload_browse,
	'options_values' => $noyes_options,
]);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ckeditor_extended:settings:overwrite_uploaded_images'),
	'#help' => elgg_echo('ckeditor_extended:settings:overwrite_uploaded_images:help'),
	'name' => 'params[overwrite_uploaded_images]',
	'value' => $plugin->overwrite_uploaded_images,
	'options_values' => $yesno_options,
]);

// HTMLAwed settings
$htmlawed_settings = elgg_view('output/longtext', [
	'value' => elgg_echo('ckeditor_extended:settings:htmlawed:info'),
]);

$htmlawed_settings .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('ckeditor_extended:settings:htmlawed:elements'),
	'name' => 'params[htmlawed_elements]',
	'value' => $plugin->htmlawed_elements,
]);

$htmlawed_settings .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('ckeditor_extended:settings:htmlawed:schemes'),
	'name' => 'params[htmlawed_schemes]',
	'value' => $plugin->htmlawed_schemes,
]);

echo elgg_view_module('inline', elgg_echo('ckeditor_extended:settings:htmlawed:title'), $htmlawed_settings);
