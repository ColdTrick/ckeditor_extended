<?php

use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\InvalidArgumentException;

class CKEditorFilestore extends ElggDiskFilestore {
	
	/**
	 * Number of entries per matrix dir.
	 * You almost certainly don't want to change this.
	 */
	const BUCKET_SIZE = 500;
	
	/**
	 * @var int the site_guid of the filestore
	 */
	protected $site_guid;
	
	/**
	 * {@inheritDoc}
	 * @see ElggDiskFilestore::__construct()
	 */
	function __construct($directory_root = "") {
		
		parent::__construct($directory_root);
		
		$this->site_guid = elgg_get_site_entity()->guid;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggDiskFilestore::getFilenameOnFilestore()
	 */
	public function getFilenameOnFilestore(\ElggFile $file) {
		
		$owner_guid = $file->getOwnerGuid();
		if (!$owner_guid) {
			$owner_guid = _elgg_services()->session->getLoggedInUserGuid();
		}
		
		if (!$owner_guid) {
			$msg = "File {$file->getFilename()} (file guid: {$file->guid}) is missing an owner!";
			throw new InvalidParameterException($msg);
		}
		
		$filename = $file->getFilename();
		if (!$filename) {
			return '';
		}
		
		// Windows has different seperators
		$filename = str_ireplace(DIRECTORY_SEPARATOR, '/', $filename);
		
		$parts = [
			$this->getUploadPath($owner_guid),
			$filename,
		];
		if (in_array(false, $parts)) {
			$msg = "Unable to create a valid folder structure";
			throw new InvalidArgumentException($msg);
		}
		
		$trim = function($value) {
			return rtrim($value, '/\\');
		};
		$parts = array_map($trim, $parts);
		
		return $this->getDirRoot() . implode('/', $parts);
	}
	
	/**
	 * Get the current site_guid
	 *
	 * @return int
	 */
	public function getSiteGUID() {
		return $this->site_guid;
	}
	
	/**
	 * Set the site_guid for the filestore
	 *
	 * @param int $site_guid the new site_guid
	 *
	 * @return bool
	 */
	public function setSiteGUID($site_guid) {
		$site_guid = (int) $site_guid;
		if ($site_guid < 1) {
			return false;
		}
		
		$this->site_guid = $site_guid;
		return true;
	}
	
	/**
	 * Make the correct folder structure for an owner
	 *
	 * @param int $owner_guid the owner to generate for
	 *
	 * @return false|string
	 */
	public function getUploadPath($owner_guid) {
		
		if (empty($owner_guid)) {
			$owner_guid = elgg_get_logged_in_user_guid();
		}
		
		if (empty($owner_guid)) {
			return false;
		}
		
		$lower_bound = (int) max(floor($owner_guid / self::BUCKET_SIZE) * self::BUCKET_SIZE, 1);
		
		return implode('/', [
			'ckeditor_upload',
			$this->getSiteGUID(),
			$lower_bound,
			$owner_guid,
		]);
	}
	
	/**
	 * Get the base dir of the filestore
	 *
	 * @return false|string
	 */
	protected function getDirRoot() {
		$params = $this->getParameters();
		
		return isset($params['dir_root']) ? $params['dir_root'] : false;
	}
}
