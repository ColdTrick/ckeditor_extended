<?php

namespace ColdTrick\CKEditorExtended\Controllers;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Http\OkResponse;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Elgg\Traits\TimeUsing;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Elgg\Project\Paths;

/**
 * Controller to handle /mod/ckeditor_extended/pages/thumbnail.php requests
 *
 * @note only maintained for BC purpose
 */
class Thumbnail {
	
	/**
	 * Respond to a request
	 *
	 * @param Request $request the HTTP request
	 *
	 * @return ResponseBuilder
	 * @throws EntityNotFoundException
	 */
	public function __invoke(Request $request) {
		$guid = (int) $request->getParam('guid');
		$name = $request->getParam('name');
			
		if (empty($guid) || empty($name)) {
			throw new EntityNotFoundException();
		}
		
		$name = Paths::sanitize($name, false);

		$fh = ckeditor_extended_get_file_handler($guid);
		$fh->setFilename($name);
		
		if (!$fh->exists()) {
			throw new EntityNotFoundException();
		}
		
		// forward to new handler
		return elgg_redirect_response(elgg_get_inline_url($fh), ELGG_HTTP_MOVED_PERMANENTLY);
	}
}
