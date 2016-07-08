<?php
/**
 * All functions bundled here
 */

/**
 * Returns upload path for a given user
 *
 * @param int $user_guid guid of the user
 *
 * @return string
 */
function ckeditor_extended_get_upload_path($user_guid) {
	$bucket_size = 500;
	
	if (empty($user_guid)) {
		$user_guid = elgg_get_logged_in_user_guid();
	}
	
	if (empty($user_guid)) {
		return false;
	}
	
	$site_guid = elgg_get_site_entity()->getGUID();
	$lower_bound = (int) max(floor($user_guid / $bucket_size) * $bucket_size, 1);
	
	return elgg_get_data_path() . 'ckeditor_upload/' . $site_guid . '/' . $lower_bound . '/' . $user_guid . '/';
}

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