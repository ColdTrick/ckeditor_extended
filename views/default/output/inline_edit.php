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
	require(['elgg', 'elgg/ckeditor'], function (elgg, elggCKEditor) {
 		elggCKEditor.bind('#<?php echo $id;?>');

 		var textArea = $('#<?php echo $id;?>');
		var editor = textArea.ckeditorGet();

		var timeout;
		
		// The "change" event is fired whenever a change is made in the editor.
		editor.on('change', function(evt) {
			// getData() returns CKEditor's HTML content.
			
			clearTimeout(timeout);
			timeout = setTimeout(function() {
				elgg.action('ckeditor_extended/inline_edit', {
					data: {
						'id': '<?php echo $id; ?>',
						'description': evt.editor.getData()
					}
				});
			}, 1000);
		});
	});
</script>