<?php

gatekeeper();

$funcNum = get_input('CKEditorFuncNum');
$user_guid = elgg_get_logged_in_user_guid();
$site_guid = elgg_get_site_entity()->getGUID();
$path = ckeditor_extended_get_upload_path($user_guid);
$dir = @opendir($path);

$body = "<ul class='ckeditor-extended-browse'>";

if ($dir) {
	while (($file = readdir($dir)) !== false) {
		if (!is_dir($file)) {
			$img = elgg_view("output/img", array("src" => "mod/ckeditor_extended/pages/thumbnail.php?guid=" . $user_guid . "&name=" . $file . "&site_guid=" . $site_guid));
			
			$body .= "<li>";
			$body .= elgg_view_image_block($img, $file);
			$body .= "</li>";
		}
	}
}

$body .= "</ul>";

$body .= <<<JS
<script type="text/javascript">
	$(document).ready(function() {
		$(".ckeditor-extended-browse > li").click(function() {
			var url = elgg.get_site_url() + "mod/ckeditor_extended/pages/thumbnail.php?guid={$user_guid}&site_guid={$site_guid}&name=" + $(this).find(".elgg-body").html();
			window.opener.CKEDITOR.tools.callFunction($funcNum, url, '');
			window.close();
		});
	});
</script>
JS;


echo elgg_view("page/elements/html", array("head" => elgg_view('page/elements/head'), "body" => $body));
