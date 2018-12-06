<?php
/**
 * All functions bundled here
 */

/**
 * Returns the object based on the id
 *
 * @param string  $id     id of the object to find
 * @param boolean $create should the object be created if id is missing
 *
 * @return /ElggObject|false
 */
function ckeditor_extended_get_inline_object($id, $create = false) {
	static $cached = [];
	if (empty($id)) {
		return false;
	}
	
	$prefix = elgg_extract(0, explode('_', $id));
	
	if (!array_key_exists($prefix, $cached)) {
		$cached[$prefix] = [];
		
		// preload entities
		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'ckeditor_inline',
			'limit' => false,
			'joins' => 'JOIN ' . elgg_get_config('dbprefix') . "objects_entity oe ON oe.guid = e.guid",
			'wheres' => "oe.title LIKE '{$prefix}%'",
		]);
		foreach ($entities as $entity) {
			$cached[$prefix][$entity->title] = $entity;
		}
	}
	
	if (array_key_exists($id, $cached[$prefix])) {
		return $cached[$prefix][$id];
	}
	
	if (!$create) {
		return false;
	}
	
	$object = new \ElggObject();
	$object->subtype = 'ckeditor_inline';
	$object->title = $id;
	$object->owner_guid = elgg_get_site_entity()->guid;
	$object->container_guid = elgg_get_site_entity()->guid;
	$object->access_id = ACCESS_PUBLIC;
	
	$cached[$prefix][$id] = $object;
	
	return $object;
}

/**
 * Build a reponse for the CKEditor upload file handler
 *
 * @param array $params reponse params
 *
 * @return string
 */
function ckeditor_extended_get_file_upload_response($params = []) {
	
	$response = '';
	
	if (elgg_extract('response_type', $params) === 'json') {
		$response = json_encode([
			'uploaded' => (int) elgg_extract('uploaded', $params),
			'filename' => elgg_extract('filename', $params),
			'url' => elgg_extract('url', $params),
			'error' => [
				'message' => elgg_extract('error', $params),
			]
		]);
	} else {
		// non json formatted response
		$funcNum = elgg_extract('funcNum', $params);
		$url = elgg_extract('url', $params);
		$error = elgg_extract('error', $params);
		
		$response = elgg_format_element('script',
			['type' => 'text/javascript'],
			"window.parent.CKEDITOR.tools.callFunction({$funcNum}, '{$url}', '{$error}');"
		);
	}
	
	return elgg_trigger_plugin_hook('upload_response', 'ckeditor_extended', $params, $response);
}

/**
 * Create a file handler for use with uploaded images
 *
 * @param int $user_guid the user to get file handler for
 *
 * @return false|CKEditorFile
 */
function ckeditor_extended_get_file_handler($user_guid = 0) {
	
	$user_guid = (int) $user_guid;
	if (empty($user_guid)) {
		$user_guid = elgg_get_logged_in_user_guid();
	}
	
	if ($user_guid < 1) {
		return false;
	}
	
	$fh = new CKEditorFile();
	$fh->owner_guid = $user_guid;
	
	return $fh;
}
