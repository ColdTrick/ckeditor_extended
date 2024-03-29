/**
 * This module can be used to bind CKEditor to a textarea
 * <code>
 *	  require(['elgg-ckeditor'], function(editor) {
 *	      editor.bind('textarea');
 *	  });
 * </code>
 *
 * @warning It's important this module is not renamed so that it ends with "/ckeditor". This can confuse
 *          the CKeditor library when it sniffs its resource directory.
 *
 * @module elgg-ckeditor
 */
define(function (require) {
	var elgg = require('elgg');
	var $ = require('jquery');
	var i18n = require('elgg/i18n');
	require('jquery.ckeditor');

	var CKEDITOR = require('ckeditor/ckeditor');
	var DEFAULT_CONFIG_MODULE = 'elgg/ckeditor/config';

	var elggCKEditor = {
		bind: function (selector, config_module) {
			config_module = config_module || DEFAULT_CONFIG_MODULE;

			require([config_module], function (config) {
				elggCKEditor.registerHandlers();
				CKEDITOR = elgg.trigger_hook('prepare', 'ckeditor', null, CKEDITOR);
				selector = selector || '.elgg-input-longtext';
				if ($(selector).length === 0) {
					return;
				}

				$(selector).not('[data-cke-init]')
					.attr('data-cke-init', true)
					.each(function () {
						var opts = $(this).data('editorOpts') || {};

						if (opts.disabled) {
							// Editor has been disabled
							return;
						}
						delete opts.disabled;

						var visual = opts.state !== 'html';
						delete opts.state;

						var FINAL_CONFIG = $.extend({}, config, opts);
						$(this).data('elggCKEeditorConfig', FINAL_CONFIG);

						if (!visual) {
							elggCKEditor.init(this, visual);
						} else {
							$(this).ckeditor(elggCKEditor.init, FINAL_CONFIG);
						}
					});
			});
		},

		/**
		 * Register event and hook handlers
		 * @return void
		 */
		registerHandlers: function () {
			elgg.register_hook_handler('prepare', 'ckeditor', function (hook, type, params, CKEDITOR) {
				CKEDITOR.plugins.addExternal('blockimagepaste', elgg.get_simplecache_url('elgg/ckeditor/blockimagepaste.js'), '');
				CKEDITOR.on('instanceReady', elggCKEditor.fixImageAttributes);
				CKEDITOR.on('instanceReady', function(event) {
					
					event.editor.on('change', function() {
						var $replacement = $('<textarea></textarea>')
							.attr('id', this.name)
							.attr('required', 'required')
							.addClass('ckeditor-extended-required-textarea')
							.html(this.getData());
						
						$('#' + this.name + '.ckeditor-extended-required-textarea').replaceWith($replacement);
						
						// need to use selecter again otherwise the trigger will not happen on the correct dom element
						$('#' + this.name + '.ckeditor-extended-required-textarea').trigger('input');						
					});
				});
				
				return CKEDITOR;
			});
			elgg.register_hook_handler('embed', 'editor', function (hook, type, params, value) {
				var textArea = $('#' + params.textAreaId);
				var content = params.content;
				if ($.fn.ckeditorGet) {
					try {
						var editor = textArea.ckeditorGet();
						editor.insertHtml(content);
						return false;
					} catch (e) {
						// do nothing.
					}
				}
			});
			elggCKEditor.registerHandlers = elgg.nullFunction;
		},

		/**
		 * Toggles the CKEditor
		 * Callback function for toggler click event
		 *
		 * @param {Object} event
		 * @return void
		 */
		toggleEditor: function (event) {
			event.preventDefault();
			var target = $(this).attr('href');
			elggCKEditor.toggle($(target)[0]);
		},

		/**
		 * Toggles the CKEditor
		 *
		 * @param {Object} textarea DOM element
		 * @returns {void}
		 */
		toggle: function (textarea) {
			$(textarea).each(function() {
				if (!$(this).data('ckeditorInstance')) {
					$(this).ckeditor(elggCKEditor.init, $(this).data('elggCKEeditorConfig'));
					$(this).data('toggler').html(i18n.echo('ckeditor:html'));
				} else {
					$(this).ckeditorGet().destroy();
					$(this).data('toggler').html(i18n.echo('ckeditor:visual'));
					
					elggCKEditor.toggleRequiredHelper($(this));
				}
			});
		},

		/**
		 * Resets the CKEditor
		 * Callback function for the reset event
		 *
		 * @param {Object} event
		 * @return void
		 */
		resetEditor: function (event) {
			event.preventDefault();
			elggCKEditor.reset(this);
		},

		/**
		 * Resets the CKEditor
		 *
		 * @param {Object} textarea DOM element
		 * @returns {void}
		 */
		reset: function (textarea) {
			$(textarea).each(function() {
				if ($(textarea).data('ckeditorInstance')) {
					$(textarea).ckeditorGet().setData('');
				}
			});
		},

		/**
		 * Focuses the CKEditor
		 * Callback function for the focus event
		 *
		 * @param {Object} event
		 * @return void
		 */
		focusEditor: function (event) {
			event.preventDefault();
			elggCKEditor.focus(this);
		},

		/**
		 * Focuses the CKEditor
		 *
		 * @param {Object} textarea DOM element
		 * @returns {void}
		 */
		focus: function (textarea) {
			if ($(textarea).first().data('ckeditorInstance')) {
				$(textarea).ckeditorGet().focus();
			}
		},

		/**
		 * Initializes the ckeditor module
		 *
		 * @param {Object}  textarea DOM element passed by ckeditor on init
		 * @param {Boolean} visual
		 * @return void
		 */
		init: function (textarea, visual) {
			var $toggler = $(textarea).data('toggler');

			if (!$toggler) {
				$toggler = $('.ckeditor-toggle-editor[href="#' + textarea.id + '"]');
				$(textarea).data('toggler', $toggler);
			}

			if (visual === false) {
				$toggler.html(i18n.echo('ckeditor:visual'));
			}

			// show the toggle-editor link which is hidden by default, so it will only show up if the editor is correctly loaded
			$toggler.show();
			
			$(textarea).closest('form').on('elgg-ajax-objectify', function() {
				if ($(textarea).data('ckeditorInstance')) {
					$(textarea).ckeditorGet().updateElement();
				}
			});
			
			elggCKEditor.toggleRequiredHelper($(textarea));
		},
		
		toggleRequiredHelper: function($textarea) {
			$helper_textarea = $textarea.parents('.elgg-field-input').find('.ckeditor-extended-required-textarea').eq(0);
			if ($helper_textarea.prop('disabled') == true) {
				$helper_textarea.prop('disabled', false);

				$helper_textarea.html($textarea.ckeditorGet().getData());
			} else {
				$helper_textarea.prop('disabled', true);
			}
		},

		/**
		 * CKEditor has decided using width and height as attributes on images isn't
		 * kosher and puts that in the style. This adds those back as attributes.
		 * This is from this patch: http://dev.ckeditor.com/attachment/ticket/5024/5024_5.patch
		 *
		 * @param {Object} event
		 * @return void
		 */
		fixImageAttributes: function (event) {
			event.editor.dataProcessor.htmlFilter.addRules({
				elements: {
					img: function (element) {
						var style = element.attributes.style;
						if (style) {
							var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style);
							var width = match && match[1];
							if (width) {
								element.attributes.width = width;
							}
							match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
							var height = match && match[1];
							if (height) {
								element.attributes.height = height;
							}
						}
					}
				}
			});
		}
	};

	$(document).on('click', '.ckeditor-toggle-editor', elggCKEditor.toggleEditor);

	$(document).on('reset', '[data-cke-init]', elggCKEditor.resetEditor);

	$(document).on('focus', '[data-cke-init]', elggCKEditor.focusEditor);

	$(document).on('change', '.elgg-list', function() {
		// triggered by hypeList when new data arrives
		// the list is sorted, so we need to recreate editors (assumed to be destroyed by hypeList)
		$(this).find('[data-cke-init]').each(function(index, item) {
			$(item).ckeditor(elggCKEditor.init, $(item).data('elggCKEeditorConfig'));
		});
	});
	
	$(window).on('beforeunload.ckeditor', function(event) {
		for(editorName in CKEDITOR.instances) {
			var editor = CKEDITOR.instances[editorName];

			if (editor.checkDirty() && $('#' + editor.name).closest('form').is(':visible')) {
				return true;
			}
		}
	});
	
	$(document).on('submit', 'form', function() {
		$(window).off('beforeunload.ckeditor');
	});

	return elggCKEditor;
});
