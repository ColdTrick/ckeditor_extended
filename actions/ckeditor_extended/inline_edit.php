<?php

$id = get_input('id', '');
$description = get_input('description');

$object = ckeditor_extended_get_inline_object($id, true);
if (empty($object)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$object->description = $description;
$object->save();

return elgg_ok_response('', elgg_echo('save:success'));
