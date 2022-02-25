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
 * Controller to handle /ckeditor_extended/download requests
 *
 * @note only maintained for BC purpose
 */
class Download {
	
	/**
	 * Respond to a request
	 *
	 * @param Request $request the HTTP request
	 *
	 * @return ResponseBuilder
	 * @throws EntityNotFoundException
	 */
	public function __invoke(Request $request) {
		$user_guid = (int) $request->getParam('user_guid');
		$file_name = $request->getParam('file_name');
			
		if (empty($user_guid) || empty($file_name)) {
			throw new EntityNotFoundException();
		}
		
		$file_name = Paths::sanitize($file_name, false);

		$fh = ckeditor_extended_get_file_handler($user_guid);
		$fh->setFilename($file_name);
		
		if (!$fh->exists()) {
			throw new EntityNotFoundException();
		}
		
		// forward to new handler
		return elgg_redirect_response(elgg_get_inline_url($fh), ELGG_HTTP_MOVED_PERMANENTLY);
	}
}
