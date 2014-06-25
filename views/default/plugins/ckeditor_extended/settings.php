<?php
/**
 * Plugin settings for CKEditor Extended
 *
 * @uses $vars['entity'] the plugin entity
 */

$plugin = elgg_extract("entity", $vars);

$noyes_options = array(
	"no" => elgg_echo("option:no"),
	"yes" => elgg_echo("option:yes"),
);

$show_html_toggler_options = array(
	"yes" => elgg_echo("option:yes"),
	"admin_only" => elgg_echo("ckeditor_extended:settings:show_html_toggler:option:admin_only"),
	"no" => elgg_echo("option:no"),
);

$editor_config = $plugin->editor_config;
if (empty($editor_config)) {
	$editor_config = <<<JS
toolbar: [['Bold', 'Italic', 'Underline'], ['Strike', 'NumberedList', 'BulletedList', 'Undo', 'Redo', 'Link', 'Unlink', 'Image', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']],
removeButtons: 'Subscript,Superscript', // To have Underline back
allowedContent: true,
baseHref: elgg.config.wwwroot,
removePlugins: 'contextmenu,tabletools,resize',
defaultLanguage: 'en',
language: elgg.config.language,
skin: 'moono',
uiColor: '#EEEEEE',
contentsCss: elgg.get_simplecache_url('css', 'elgg/wysiwyg.css'),
disableNativeSpellChecker: false,
disableNativeTableHandles: false,
removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
autoGrow_maxHeight: $(window).height() - 100,
JS;
}

echo "<div>";
echo elgg_echo("ckeditor_extended:settings:editor_config");
echo elgg_view("input/plaintext", array("name" => "params[editor_config]", "value" => $editor_config));
echo "<div class='elgg-subtext'>" . elgg_view("output/url", array("href" => "http://docs.ckeditor.com/#!/api/CKEDITOR.config", "text" => elgg_echo("ckeditor_extended:settings:link"), "target" => "_blank"));
echo "</div>";

echo "<div>";
echo elgg_echo("ckeditor_extended:settings:show_html_toggler");
echo elgg_view("input/select", array("name" => "params[show_html_toggler]", "value" => $plugin->show_html_toggler, "options_values" => $show_html_toggler_options, "class" => "mlm"));
echo "</div>";

echo "<div>";
echo elgg_echo("ckeditor_extended:settings:image_upload_allowed");
echo elgg_view("input/select", array("name" => "params[image_upload_allowed]", "value" => $plugin->image_upload_allowed, "options_values" => $noyes_options, "class" => "mlm"));
echo "</div>";


$htmlawed_settings = "<div class='pbm'>" . elgg_echo("ckeditor_extended:settings:htmlawed:info") . "</div>";

$htmlawed_settings .= "<div>";
$htmlawed_settings .= elgg_echo("ckeditor_extended:settings:htmlawed:elements");
$htmlawed_settings .= elgg_view("input/text", array(
	"name" => "params[htmlawed_elements]",
	"value" => $plugin->htmlawed_elements
));
$htmlawed_settings .= "</div>";

$htmlawed_settings .= "<div>";
$htmlawed_settings .= elgg_echo("ckeditor_extended:settings:htmlawed:schemes");
$htmlawed_settings .= elgg_view("input/text", array(
		"name" => "params[htmlawed_schemes]",
		"value" => $plugin->htmlawed_schemes
));
$htmlawed_settings .= "</div>";

echo elgg_view_module("inline", elgg_echo("ckeditor_extended:settings:htmlawed:title"), $htmlawed_settings);