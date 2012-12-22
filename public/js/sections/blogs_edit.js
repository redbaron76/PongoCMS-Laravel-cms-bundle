//BLOGS

$(function() {
	
	//DISABLE NAV TAB
	$.cms.disableNavTab();
	
	//TOOLTIP
	$.cms.toolTip();

	//FANCYBOX
	$.cms.fancyBox();

	//COMPILE SLUG
	$.cms.blog2Slug();

	//COUNT
	$.cms.count70();
	$.cms.count150();

	//ELASTIC TEXTAREA
	$.cms.elastic();

	//TOGGLE PREVIEW
	$.cms.togglePreviewButton();

	//SAVE AND CONTINUE
	$.cms.saveContinue();

	//UPLOAD MEDIA
	$.cms.uploadMedia();

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

	//DATETIMEPICKER
	$.cms.dateTimePicker();

	//INS PAGINATOR
	$.cms.insPaginator('blog');

	//POPOVER
	$.cms.popOver('blog');

	//TAGS
	$.cms.suggestTags('blog');

	$.cms.addNewTag('blog');

});