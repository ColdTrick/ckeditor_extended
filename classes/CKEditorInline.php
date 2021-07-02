<?php
/**
 * Inline Editor object to hold the content
 */
class CKEditorInline extends \ElggObject {
	
	/**
	 * @var string
	 */
	const SUBTYPE = 'ckeditor_inline';
	
	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['access_id'] = ACCESS_PUBLIC;
		$this->attributes['owner_guid'] = elgg_get_site_entity()->guid;
		$this->attributes['container_guid'] = elgg_get_site_entity()->guid;
	}
}
