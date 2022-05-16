define(['jquery', 'elgg/i18n', 'elgg/Ajax'], function($, i18n, Ajax) {
	var ajax = new Ajax(false);
	
	$('.ckeditor-extended-browse > li').click(function(event) {
		if (event.target.nodeName == 'SPAN') return;
		
		var url = $(this).data().embedUrl;
		
		var funcNum = $(this).parent().attr('rel');
		
		window.opener.CKEDITOR.tools.callFunction(funcNum, url, '');
		window.close();
	});
				
	$(document).on('click', '.ckeditor-delete-file', function(event) {
		event.preventDefault();
		event.stopPropagation();
		event.stopImmediatePropagation();
		
		if (confirm(i18n.echo('question:areyousure'))) {
			var href = $(this).attr('href');
			
			$(this).parents('li').hide();
			ajax.path(href);
		
			return false;
		}
	});
});
