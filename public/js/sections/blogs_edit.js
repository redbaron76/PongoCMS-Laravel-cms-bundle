//BLOGS

$(function() {
	
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

	//SAVE AND CONTINUE
	$.cms.saveContinue();

	//UPLOAD MEDIA
	$.cms.uploadMedia();

	//MODAL MEDIA EVENT
	$.cms.openMediaModal();

	//ENABLE CKEDITOR
	$.ck.CKEditor();

	//LISTENER as_html
	$.ck.CKasHtml();

	//LISTENER as_html
	$.ck.CKasText();

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