//PAGE

$(function() {
	
	//DISABLE NAV TAB
	$.cms.disableNavTab();

	//TOOLTIP
	$.cms.toolTip();

	//FANCYBOX
	$.cms.fancyBox();

	//CHANGE LAYOUT
	$.cms.changeLayout();

	//COMPILE SLUG
	$.cms.title2Slug();

	//COUNT
	$.cms.count70();
	$.cms.count150();

	//ELASTIC TEXTAREA
	$.cms.elastic();

	//CHECKALL CLONE
	$.cms.checkAllClone();

	//TOGGLE PREVIEW
	$.cms.togglePreviewButton();

	//SAVE AND CONTINUE
	$.cms.saveContinue();

	//UPLOAD MEDIA
	$.cms.uploadMedia();

	//SORTABLE LIST ELEMENT
	$.cms.sortableListSubpage();

	//MODAL MEDIA EVENT
	$.cms.openMediaModal();

	//ENABLE CKEDITOR
	if(WYSIWYG === 'ckeditor')
		$.ck.CKEditor();

	//ENABLE MARKITUP
	if(WYSIWYG === 'markitup')
		$.ck.MarkItUp();

	//LISTENER as_html
	$.ck.CKasHtml();

	//LISTENER as_text
	$.ck.CKasText();

	//LISTENER as_sample
	$.ck.CKasSample();

});
