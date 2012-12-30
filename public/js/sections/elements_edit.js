//ELEMENT

$(function() {

	//DISABLE NAV TAB
	$.cms.disableNavTab();

	//TOOLTIP
	$.cms.toolTip();

	//FANCYBOX
	$.cms.fancyBox();

	//LABEL ID SLUG
	$.cms.label2Slug();

	//CHANGE ZONE LAYOUT
	$.cms.changeZone();
	
	//ENABLE CKEDITOR
	if(WYSIWYG === 'ckeditor')
		$.ck.CKEditor();

	//ENABLE MARKITUP
	if(WYSIWYG === 'markitup')
		$.ck.MarkItUp();

	//SAVE AND CONTINUE
	$.cms.saveContinue();

	//MODAL MEDIA EVENT
	$.cms.openMediaModal();

	//UPLOAD MEDIA
	$.cms.uploadMedia();

	//LISTENER as_html
	$.ck.CKasHtml();

	//LISTENER as_text
	$.ck.CKasText();

	//LISTENER as_sample
	$.ck.CKasSample();

	//SORTABLE LIST ELEMENT
	$.cms.sortableListElement();

});