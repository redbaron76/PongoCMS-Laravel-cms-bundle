//GALLERY

$(function() {

	//DISABLE NAV TAB
	$.cms.disableNavTab();
	
	//TOOLTIP
	$.cms.toolTip();
	
	//FANCYBOX
	$.cms.fancyBox();

	//OPEN MEDIA
	$.cms.openImageModal('galleries');

	//DELETE MEDIA
	$.cms.addImageList();

	//DELETE MEDIA
	$.cms.deleteImageList();
	
	//SORTABLE
	$.cms.sortableListGallery();

	//SAVE AND CONTINUE
	$.cms.saveContinue();

	//INS PAGINATOR
	$.cms.insPaginator();

});