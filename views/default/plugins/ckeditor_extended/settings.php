<?php
/**
 * Plugin settings for CKEditor Extended
 *
 * @uses $vars['entity'] the plugin entity
 */

/* @var $plugin ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'flush_cache',
	'value' => 1,
]);

$editor_config = $plugin->editor_config;
if (empty($editor_config)) {
	$editor_config = elgg_view('ckeditor_extended/default_config');
}

$editor_config_simple = $plugin->editor_config_simple;
if (empty($editor_config_simple)) {
	$editor_config_simple = elgg_view('ckeditor_extended/default_config_simple');
}

$editor_settings = '';

$editor_settings .= elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('ckeditor_extended:settings:editor_config') . ' [default]',
	'#help' => elgg_view('output/url', [
		'href' => 'http://docs.ckeditor.com/#!/api/CKEDITOR.config',
		'text' => elgg_echo('ckeditor_extended:settings:link'),
		'target' => '_blank',
	]),
	'name' => 'params[editor_config]',
	'value' => $editor_config,
]);

$editor_settings .= elgg_view_field([
	'#type' => 'plaintext',
	'#label' => elgg_echo('ckeditor_extended:settings:editor_config') . ' [simple]',
	'name' => 'params[editor_config_simple]',
	'value' => $editor_config_simple,
]);

echo elgg_view_module('info', elgg_echo('ckeditor_extended:settings:editors'), $editor_settings);

// Global Editor settings
$global_settings = '';

$global_settings .= elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('ckeditor_extended:settings:show_html_toggler'),
	'name' => 'params[show_html_toggler]',
	'value' => $plugin->show_html_toggler,
	'options_values' => [
		'yes' => elgg_echo('option:yes'),
		'admin_only' => elgg_echo('ckeditor_extended:settings:show_html_toggler:option:admin_only'),
		'no' => elgg_echo('option:no'),
	],
]);

$global_settings .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('ckeditor_extended:settings:image_upload_allowed'),
	'#help' => elgg_echo('ckeditor_extended:settings:image_upload_allowed:help'),
	'name' => 'params[image_upload_allowed]',
	'checked' => $plugin->image_upload_allowed === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

$global_settings .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('ckeditor_extended:settings:image_upload_browse'),
	'#help' => elgg_echo('ckeditor_extended:settings:image_upload_browse:help'),
	'name' => 'params[image_upload_browse]',
	'checked' => $plugin->image_upload_browse === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

$global_settings .= elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('ckeditor_extended:settings:overwrite_uploaded_images'),
	'#help' => elgg_echo('ckeditor_extended:settings:overwrite_uploaded_images:help'),
	'name' => 'params[overwrite_uploaded_images]',
	'checked' => $plugin->overwrite_uploaded_images === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);

echo elgg_view_module('info', elgg_echo('ckeditor_extended:settings:global'), $global_settings);

// HTMLawed settings
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

echo elgg_view_module('info', elgg_echo('ckeditor_extended:settings:htmlawed:title'), $htmlawed_settings);

// show an example of your settings
$example_content = elgg_view_field([
	'#type' => 'longtext',
	'#label' => 'default',
	'value' => elgg_echo('ckeditor_extended:settings:example:description'),
]);

$example_content .= elgg_view_field([
	'#type' => 'longtext',
	'#label' => 'simple',
	'editor_type' => 'simple',
	'value' => elgg_echo('ckeditor_extended:settings:example:description'),
]);

echo elgg_view_module('info', elgg_echo('ckeditor_extended:settings:example'), $example_content);
