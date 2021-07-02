<?php

$id = elgg_extract('id', $vars);
if (empty($id)) {
	echo 'Missing id';
	return;
}

$entity = ckeditor_extended_get_inline_object($id);
if ($entity) {
	$vars['value'] = $entity->description;
} else {
	$vars['value'] = elgg_echo('ckeditor_extended:output:inline_edit:default');
}

$vars['class'] = 'ckeditor-extended-inline-edit';
if (elgg_is_admin_logged_in()) {
	$vars['contenteditable'] = 'true';
}

echo elgg_view('output/longtext', $vars);

if (!elgg_is_admin_logged_in()) {
	return;
}
?>
<script>
	require(['elgg/Ajax', 'elgg-ckeditor'], function (Ajax, elggCKEditor) {
		
 		var ajax = new Ajax();
		var timeout;
		
		// The "change" event is fired whenever a change is made in the editor.
		CKEDITOR.inline( '<?php echo $id;?>' ).on('change', function(evt) {
			// getData() returns CKEditor's HTML content.
			
			clearTimeout(timeout);
			timeout = setTimeout(function() {
				ajax.action('ckeditor_extended/inline_edit', {
					data: {
						'id': '<?php echo $id; ?>',
						'description': evt.editor.getData()
					}
				});
			}, 1000);
		});
	});
</script>