<?php

$id = elgg_extract('id', $vars);

if (empty($id)) {
	return;
}

if (!elgg_extract('required', $vars)) {
	return;
}

echo elgg_format_element('textarea', [
	'required' => true,
	'class' => 'ckeditor-extended-required-textarea',
	'id' => "{$id}-required",
	'disabled' => true,
], elgg_extract('value', $vars));
