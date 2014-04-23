<?php
/**
 * Append the plugin settings form to show an example of the current config
 */

$plugin = elgg_extract("entity", $vars);
$type = elgg_extract("type", $vars);

if (!empty($plugin) && empty($type)) {
	if (elgg_instanceof($plugin, "object", "plugin")) {
		if ($plugin->getID() === "ckeditor_extended") {
			// show an example of your settings
			$example_content = elgg_view("input/longtext", array("value" => elgg_echo("ckeditor:settings:example:description")));
			
			echo elgg_view_module("inline", elgg_echo("ckeditor:settings:example"), $example_content);
		}
	}
}