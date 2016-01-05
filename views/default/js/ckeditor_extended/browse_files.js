define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	

	init = function() {
	
		$('.ckeditor-extended-browse > li').click(function(event) {
			if (event.target.nodeName == 'SPAN') return;
			
			var user_guid = $(this).data().userGuid;
			var site_guid = $(this).data().siteGuid;
			var file_name = $(this).data().fileName;
	
			var url = elgg.get_site_url() + 'mod/ckeditor_extended/pages/thumbnail.php?guid=' + user_guid + '&site_guid=' + site_guid + '&name=' + file_name;
			var funcNum = $(this).parent().attr('rel');
			
			window.opener.CKEDITOR.tools.callFunction(funcNum, url, '');
			window.close();
		});
					
		$(document).on('click', '.ckeditor-delete-file', function(event) {
			event.preventDefault();
			event.stopPropagation();
			event.stopImmediatePropagation();
			
			if (confirm(elgg.echo('question:areyousure'))) {
				var href = $(this).attr('href');
				
				$(this).parents('li').hide();
				elgg.action(href);
			
				return false;
			}
		});
	};
	
	init();
});