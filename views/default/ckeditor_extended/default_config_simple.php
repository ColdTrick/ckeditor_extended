<?php
/**
 * This file contains the simple CKEditor config as used by this plugin
 */
?>
toolbar: [['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat']],
removeButtons: 'Subscript,Superscript', // To have Underline back
allowedContent: true,
baseHref: elgg.get_site_url(),
removePlugins: 'liststyle,contextmenu,tabletools,elementspath,tableselection,image',
extraPlugins: 'blockimagepaste,image2',
defaultLanguage: 'en',
language: elgg.get_language(),
skin: 'moono-lisa',
contentsCss: elgg.get_simplecache_url('elgg/wysiwyg.css'),
disableNativeSpellChecker: false,
disableNativeTableHandles: false,
removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
customConfig: false, //no additional config.js
stylesSet: false, //no additional styles.js
