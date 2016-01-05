<?php
/**
 * Append the plugin settings form to show an example of the current config
 */

$plugin = elgg_extract('entity', $vars);

if (!elgg_instanceof($plugin, 'object', 'plugin')) {
	return;
}

if ($plugin->getID() !== 'ckeditor_extended') {
	return;
}

// show an example of your settings
$example_content = elgg_view('input/longtext', ['value' => elgg_echo('ckeditor_extended:settings:example:description')]);

echo elgg_view_module('inline', elgg_echo('ckeditor_extended:settings:example'), $example_content);
