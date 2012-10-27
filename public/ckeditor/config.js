/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.baseHref = BASE + '/';

	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_P;

	config.fillEmptyBlocks = false;
	
	config.basicEntities = true;
	config.entities = false;
	config.entities_greek = false;
	config.entities_latin = false;
	config.htmlEncodeOutput = false;
	config.entities_processNumerical = false;

	config.height = '40em';
	config.toolbarCanCollapse = false;
	config.resize_enabled = false;

	config.toolbar = 'MyToolbar';
 
	config.toolbar_MyToolbar =
	[
		{ name: 'document', items : [ 'Source' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
                '/',
		{ name: 'styles', items : [ 'Styles','Format','TextColor','BGColor' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'tools', items : [ 'Maximize','-','About' ] }
	];

	config.contentsCss = [
		BASE + '/bundles/cms/css/bootstrap.min.css',
		BASE + '/' + SITE_CSS,
		BASE + '/bundles/cms/css/editor.css',
	];

	CKEDITOR.config.bodyId = 'editor';
	CKEDITOR.config.bodyClass = ELE_CLASS;

};
