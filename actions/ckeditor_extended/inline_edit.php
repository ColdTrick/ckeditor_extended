<?php

$id = get_input('id', '');
$description = get_input('description');

$object = ckeditor_extended_get_inline_object($id, true);
if (empty($object)) {
	register_error(elgg_echo('error:missing_data'));
	return;
}

$object->description = $description;
$object->save();

return elgg_ok_response('', elgg_echo('save:success'));
