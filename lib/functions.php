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
	
	return elgg_get_data_path() . "ckeditor_upload/" . $site_guid . "/" . $lower_bound . "/" . $user_guid . "/";
}
