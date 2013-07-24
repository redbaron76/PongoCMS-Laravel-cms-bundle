//PongoCMS v1.3  jQuery Library
//2012-04-20 - copyright Fabio Fumis - Pongoweb.it

	
$.cms = {
	
	toolTip:
	function() {
		$('*[rel=tooltip]').tooltip();
	},
	
	fancyBox:
	function() {
		$('a.fancy').fancybox();
	},

	getPopOver:
	function(what, id) {
		var content = '';
		$.ajax({
			type: "POST",
			url: BASE+'/cms/'+what+'/popover_details',
			data: ({ id: id }),
			dataType: 'html',
			async: false,
			success: function(data) {
				content = data;
			}
		});
		return content;
	},

	popOver:
	function(what) {
		var options = {
			trigger: 'hover',
			html: true,
			content: function() {
				var id = $(this).attr('rel');
				var content = $.cms.getPopOver(what,id);
				return content;
			}			
		}

		$('.pop-over').popover(options);
	},

	togglePreviewButton:
	function() {
		var $preview_btn = $('a.preview'),
			$checkbox = $('input[name=is_valid]');

		$checkbox.is(':checked') ? $preview_btn.hide() : $preview_btn.show();

		$checkbox.click(function () {
			$preview_btn.toggle();
		});

		$preview_btn.click(function(e) {
			e.preventDefault();
			if($preview_btn.attr('href').length > 0) {
				window.open(
				  $preview_btn.attr('href'),
				  '_blank'
				);
			}
		});
	},

	disableNavTab:
	function() {
		$('.disabled a').click(function() {
			return false;
		});
	},

	//PAGE

	togglePageList:
	function() {

		var $open = $('ol.list.open');

		$open.parents('ol').addClass('open');
		$open.parents('li[rel=0]').find('button.toggle').html('-');

		$('button.toggle').click(function() {
			
			var el = $(this).attr('rel');
			var $list = $('ol[rel='+el+']');

			if ($list.is(':hidden')) {
				$list.show();
				$(this).html('-');     
			} else {
				$list.hide();
				$(this).html('+');     
			}

			return false;
		});
	},

	parseLayout:
	function() {		
		// LAYOUT PARSER
		$('div[rel=preview] span').each(function(index, el) {			
			var content = el;
			$(this).parent().empty().html(content);
		});
	},

	highlightLayout:
	function() {

		var $el = $('#element_zone');

		if($el.length > 0) {
			var el_val = $el.val();
			$('div[data-zone='+el_val+']').addClass('highlight');

			$el.live('change', function() {
				$('div.highlight').removeClass('highlight');
				$('div[data-zone='+ $(this).val() +']').addClass('highlight');
			});
		}

	},

	changeZone:
	function() {

		$.cms.highlightLayout();
		$.cms.parseLayout();

	},

	changeLayout:
	function() {

		$('#page_header').live('change', function() {
			var text = $('#page_header option:selected').text();
			$('#header-preview').html(text);
		});

		$('#page_footer').live('change', function() {
			var text = $('#page_footer option:selected').text();
			$('#footer-preview').html(text);
		});

		$('#page_layout').live('change', function() {
			var layout = $('#page_layout').val();
			
			if(layout.length > 0) {
				$.post(BASE+'/cms/ajax/page/layout',{
					layout: layout
				},function(data) {
					$('#layout-preview').html(data);
					$.cms.parseLayout();
				});
			}
		});

		$.cms.parseLayout();

	},

	changeLang:
	function(where) {
		$('#change_lang').live('change', function() {
			var lang = $(this).val();
			if(lang.length > 0) {
				window.location = BASE+'/cms/'+where+'/'+lang;
			}
		});
	},

	label2Slug:
	function() {
		$('#element_label').stringToSlug({getPut: '#element_name'});
	},

	title2Slug:
	function() {
		$('#page_name').stringToSlug({getPut: '#page_slug'});
		var $page_parent = $('#page_parent');
		var $page_slug = $('#page_slug');
		var $addon_slug = $('span[rel=page_slug]');
		var $ppw = $page_parent.width();
		var $psw = $page_slug.width();
		var $asw = $addon_slug.width();
		$page_slug.css('width', parseInt($ppw - $asw - 10));
		var $tot = $ppw;
		$('#page_parent').live('change', function() {
			var $parent = $(this).val();			
			if($parent) {
				$.post(BASE+'/cms/ajax/get/page/parent/paths',{
					parent_id: $parent
				},function(data) {
					$('span.add-on[rel=page_slug]').html('/').prepend(data);
					$('#page_parent_slug').val(data);
					$diff = $tot - $('span[rel=page_slug]').width();			
					$page_slug.css('width', $diff - 10);
				});
			}
		});
	},

	count20:
	function() {
		$('.count20').charCount({
			allowed: 20,
			warning: 5,
			css: 'help-block counter',
			counterElement: 'p'
		});
	},

	count70:
	function() {
		$('.count70').charCount({
			allowed: 70,
			warning: 5,
			css: 'help-block counter',
			counterElement: 'p'
		});
	},

	count150:
	function() {
		$('.count150').charCount({
			allowed: 150,
			warning: 20,
			css: 'help-block counter',
			counterElement: 'p'
		});
	},

	elastic:
	function() {
		$('.elastic').elastic();
	},

	checkAllClone:
	function() {
		$('#checkall_clone').click(function () {
			$('.to_clone').attr('checked', this.checked);
		});
	},

	//IAS paginator
	paginator:
	function(where, fancy) {

		jQuery.ias({
			container 	: ".listing",
			item		: ".post",
			pagination	: ".navigation",
			next		: ".next_page a",
			loader		: "<img src='"+BASE+"/bundles/cms/img/loader.gif'>",
			history		: false,
			onRenderComplete: function(items) {
				if(where) $.cms.popOver(where);
				if(fancy) $.cms.fancyBox();
			}
		});

	},

	//INSIDER paginator
	insPaginator:
	function(where) {
		
		var $link = $('.navigation a');
		var $span = $('.navigation span.disabled');

		$link.addClass('btn btn-mini');
		$span.css('visibility', 'hidden');
		
		$link.live('click', function(e) {			
			e.preventDefault();
			var btn = this;
			$url = $(this).attr('href');
			$('.loading').append($('<div>').load($url + ' .listing', function() {
				$.cms.fancyBox();
				$('.navigation a').addClass('btn btn-mini');
				$('.navigation span.disabled').css('visibility', 'hidden');
				$(btn).parents('.navigation').remove();
				if(where) $.cms.popOver(where);
			}));
			return false;
		});
	},

	processJson:
	function(data) {
		if(!data.auth && !data.messages && !data.noaccess) {
			window.location = BASE+'/cms';
		} else {

			if(data.messages) {				

				//remove error messages
				$('.control-group').removeClass('error');
				$('span.help-inline.error').remove();

				$.each(data.messages, function(key, value) {
					$('.control-group[rel='+key+']').addClass('error');
					if($('span.help-inline.error[rel='+key+']').length == 0) {
						$('input[name='+key+']').after('<span class="help-inline error" rel="'+key+'">'+value+'</span>');
						$('select[name='+key+']').after('<span class="help-inline error" rel="'+key+'">'+value+'</span>');
						$('textarea[name='+key+']').after('<span class="help-inline error" rel="'+key+'">'+value+'</span>');
					}
				});

			} else if(data.noaccess) {

				noty({
					type : 'error',
					text: data.noaccess,			
					layout: "top",
					theme: "noty_theme_twitter",
					speed: 250,
					timeout: 1500
				});

			} else {

				//remove error messages
				$('.control-group').removeClass('error');
				$('span.help-inline.error').remove();

				//Set all hidden id
				$('.'+data.cls).val(data.id);

				//Remove .disabled from tabs
				if(data.id) {
					$('.nav').find('li.disabled').removeClass('disabled');
					$('.hide.disabled').removeClass('disabled').show();
					$('a[data-toggle="tab"], a[data-toggle="pill"]').click(function(e) {
						e.preventDefault();
						$(this).tab('show');
					});						
				}

				//Set page_id for extra and enable upload
				if(data.pageid) $('.page_id').val(data.pageid);

				if(data.full_slug) $('a.preview').attr('href', data.full_slug + PREVIEW);

				// Set element name
				if(data.element) $('legend > span').html(data.element);

				// Template inject
				if(data.inject && data.template) {

					// Clear content
					if(data.detach) $(data.inject).children().detach();

					// Append content if not already present
					if($('#'+data.pageid+'_'+data.id).length === 0) {

						// Remove <li> with no id
						$(data.inject).find('li:not([id])').remove();

						// Remove <li> not in ZONE
						if(data.zone) $(data.inject).find('li:not([data-zone='+data.zone+'])').remove();

						$(data.inject).append(data.template);
					}					

					//Renew tooltip
					$.cms.toolTip();
				}

				//redirect if exit
				if(data.backurl != '#') {
					
					window.location = data.backurl;

				} else {
					
					noty({
						type : data.response,
						text: data.message,			
						layout: "top",
						theme: "noty_theme_twitter",
						speed: 250,
						timeout: 1500
					});

				}	

			}
		}

	},

	saveContinue:
	function() {

		$('a.save_form').live('click', function() {

			var url = $(this).attr('href');
			var disabled = $(this).hasClass('disabled');

			if(!disabled) {

				var options = {
					dataType: 'json',
					data: { back_url: url },
					success: $.cms.processJson
				};

				var form = $(this).attr('rel');
				$('#'+form).ajaxSubmit(options);

			}
			
			return false;

		});
	},

	uploadMedia:
	function() {

		var $page_id = $('.page_id').val();

		var uploader = new plupload.Uploader({
			runtimes : 'html5,html4',
			browse_button : 'add_media',
			container : 'media-container',
			// max_file_size : MAX_UP,
			url : BASE+'/cms/ajax/upload/media',
			multipart_params : {
				page_id: $page_id,
			}
		});

		$('#upload_media').click(function(e) {
			uploader.start();
			e.preventDefault();
		});

		uploader.bind('Init', function(up, params) {
			$('#filelist').html("<div>Upload engine: " + params.runtime + "</div>");
		});

		uploader.init();

		uploader.bind('FilesAdded', function(up, files) {

			this.settings.multipart_params.page_id = $('.page_id').val();

			$.each(files, function(i, file) {
				$('#filelist').append('<div id="' + file.id + '" class="upload_list">' +
				file.name + '<span class="label">' + plupload.formatSize(file.size) + '</span>' +
				'</div>');
			});
		});

		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + ' span').html(file.percent + '%');
		});

		uploader.bind('FileUploaded', function(up, file, info) {
			
			var obj = jQuery.parseJSON(info.response);

			$('#' + file.id + ' span').html(obj.message).addClass(obj.type);

			//create thumb element if path returned
			if(obj.path) {
				
				$('.none').remove();

				var fancy = (obj.is_img) ? ' fancy' : '';

				var thumb = '<li class="span1 media-box-block">' +
								'<a href="'+BASE+obj.path+'" class="thumbnail'+fancy+'" rel="tooltip" data-original-title="'+obj.name+'">' +
									'<img src="'+BASE+obj.thumb_path+'" width="50" height="50">' +
								'</a>' +
							'</li>';

				$('#media-box.thumbnails').prepend(thumb);

				$.cms.toolTip();
				$.cms.fancyBox();

			}

		});


	},

	openMediaModal:
	function() {
		
		$('.open-media-modal').live('click', function() {

			var $pid = $(this).attr('rel');

				var $wrapper = $('#modal-media-list');
				
				$.post(BASE+'/cms/ajax/media/list', {
					pid: $pid
				}, function(data) {
					$wrapper.empty().html(data, function() {
						//LISTENER as_html
						$.ck.CKasHtml();
						//LISTENER as_html
						$.ck.CKasText();
					});
					$('#modal-media').modal();
				});


			return false;
		});		

	},

	openImageModal:
	function(where) {
		
		$('.open-media-modal').live('click', function() {

				var lid = $(this).attr('rel');
				var $wrapper = $('#modal-image-list');
				
				$.post(BASE+'/cms/ajax/image/list', {
					lid: lid,
					where: where
				}, function(data) {
					$wrapper.empty().html(data);
					$('#modal-media').modal();
				});

			return false;
		});		

	},

	addImageList:
	function() {

		$('.list-insert').live('click', function() {

			var lid = $(this).attr('data-list');
			var fid = $(this).attr('data-file');
			var rel = $(this).attr('data-rel');

			$.post(BASE+'/cms/ajax/image/list/add', {
				lid: lid,
				fid: fid,
				rel: rel
			}, function(data) {
				if(data) {

					var list_tpl = 	'<tr rel="'+ data.file.id +'">' +
										'<td>' +
											'<a href="'+ BASE+data.file.path +'" class="thumbnail fancy" rel="tooltip" data-original-title="'+data.file.name+'">' +
												'<img src="'+BASE+data.file.thumb+'">' +
											'</a>' +
										'</td>' +
										'<td class="v-middle"><small>'+data.file.name+'</small></td>' +
										'<td>' +
											'<a href="#" class="btn btn-mini btn-danger pull-right list-delete" data-file="'+data.file.id+'" data-list="'+data.list_id+'" data-rel="galleries">'+data.del_btn+'</a>' +
										'</td>' +
									'</tr>';

					var order_tpl = '<li class="span1" id="'+data.list_id+'_'+data.file.id+'" rel="'+data.file.id+'">' +
										'<a href="'+ BASE+data.file.path +'" class="thumbnail" data-original-title="'+data.file.name+'" rel="tooltip">' +
											'<img src="'+BASE+data.file.thumb+'" />' +
										'</a>' +
									'</li>';

					$('table.listing > tbody').append(list_tpl);
					$('ul.thumbnails').append(order_tpl);

					$('*[rel=empty]').remove();
					$('#modal-media tr[rel='+fid+']').remove();

				};
				
			}, 'json');

			return false;
		});

	},

	deleteImageList:
	function() {

		$('.list-delete').live('click', function() {

			var lid = $(this).attr('data-list');
			var fid = $(this).attr('data-file');
			var rel = $(this).attr('data-rel');

			if(lid && fid && rel) {

				$.post(BASE+'/cms/ajax/image/list/del', {
					lid: lid,
					fid: fid,
					rel: rel
				}, function(status) {

					if(status) {

						$('tr[rel='+fid+']').remove();
						$('li[rel='+fid+']').remove();
						
					}
				});

			}

			return false;
		});

	},

	//FILES

	fileChangeExtension:
	function() {

		var extensions = $('.ext').attr('rel');

		$('.ext input[type=checkbox]').live('change', function() {

			var location, pid = $('#change_file_path').val();			
			var ext = $(this).attr('name');

			if(extensions.indexOf(ext) > -1) {
				extensions = extensions.replace('-'+ext, '');
				extensions = extensions.replace(ext, '');
			} else {
				extensions = extensions + '-' + ext;
			}

			if(extensions.substr(0,1) == '-') {
				extensions = extensions.substring(1);
			}

			if(extensions.length > 0) {
				location = BASE+'/cms/file/filter/'+pid+'/'+extensions;
			} else {
				location = BASE+'/cms/file';
				if(pid > 0) location = location + '/filter/' + pid;
			}

			window.location = location;

		});
	},

	fileChangePath:
	function() {
		$('#change_file_path').live('change', function() {
			
			var location, id = $(this).val();
			var extensions = $('.ext').attr('rel');

			if(id != 0) {
				location = BASE+'/cms/file/filter/'+id;
				if(extensions.length > 0) location = location + '/' + extensions;
			} else {
				location = BASE+'/cms/file';
				if(extensions.length > 0) location = location + '/filter/0/' + extensions;
			}

			window.location = location;

		});
	},

	fileChangeLang:
	function() {
		$('#file_lang').live('change', function(){
			$lang = $(this).val();
			$fid = $('.file_id').val();
			$.post(BASE+'/cms/ajax/file/text/lang', {
				file_lang: $lang,
				file_id: $fid
			}, function(data) {
				var obj = jQuery.parseJSON(data);
				$('#filetext_alt').val(obj.alt);
				$('#filetext_title').val(obj.title);
				$('#filetext_label').val(obj.label);
			});
		});
	},

	//SORTABLE

	sortableListPage:
	function() {

		$('.list').sortable({'items':'li.sortable','update' : function () {
			$.post(BASE+'/cms/ajax/page/list/order',$(this).serializeTree('id', 'order'));		
		}});

	},

	sortableListSubpage:
	function() {

		$('.sortable').sortable({'items':'li','update' : function () {
			$.post(BASE+'/cms/ajax/page/subpage/order',$(this).serializeTree('id', 'order'));		
		}});

	},

	sortableListElement:
	function() {

		$('.sortable').sortable({'items':'li','update' : function () {
			$.post(BASE+'/cms/ajax/page/element/order',$(this).serializeTree('id', 'order'));		
		}});

	},

	sortableListDownload:
	function() {

		$('.sortable').sortable({'items':'li','update' : function () {
			$.post(BASE+'/cms/ajax/download/file/order',$(this).serializeTree('id', 'order'));		
		}});

	},

	sortableListBanner:
	function() {

		$('.sortable').sortable({'items':'li','update' : function () {
			$.post(BASE+'/cms/ajax/banner/file/order',$(this).serializeTree('id', 'order'));		
		}});

	},

	sortableListGallery:
	function() {

		$('.sortable').sortable({'items':'li','update' : function () {
			$.post(BASE+'/cms/ajax/gallery/file/order',$(this).serializeTree('id', 'order'));		
		}});

	},

	sortableListMenu:
	function() {

		$('.sortable').sortable({'items':'li','update' : function () {
			$.post(BASE+'/cms/ajax/menu/page/order',$(this).serializeTree('id', 'order'));		
		}});

	},

	//TRANSLATIONS

	editTranslation:
	function() {

		$('.edit').live('click', function() {
			var $id = $(this).attr('rel');
			var $word = $('.word[rel='+$id+']').text();
			var $value = $('.value[rel='+$id+']').text();
			$('#word').val($word);
			$('#value').val($value);
			$('#translation_id').val($id);

			return false;
		});

	},

	deleteTranslation:
	function() {

		$('.delete').live('click', function() {
			var id = $(this).attr('rel');
			if(id) {

				$.post(BASE+'/cms/ajax/translation/delete', {
					id: id
				}, function(data) {
					
					var obj = jQuery.parseJSON(data);

					var response = obj.response;
					var message = obj.message;
					var id = obj.id;

					noty({
						type : response,
						text: message,
						layout: "top",
						theme: "noty_theme_twitter",
						speed: 250,
						timeout: 1500,
						onShow: function() {
							$('#translation tr[rel='+id+']').remove();
						}
					});
					
				});
				
			}

			return false;
		});

	},

	processTranslation:
	function(data) {		

		if(data.messages) {

			var msg = '';
			var response = 'error';
			$.each(data.messages, function(key, value) {
				msg = value;
			});
			var id = '';
			var message = msg.toString();

		} else {
			var response = data.response;
			var message = data.message;
			var id = data.id;
		}


		var tpl = 	'<tr rel="'+id+'">' +
						'<td>' +
							'<div class="word" rel="'+id+'"><strong>'+data.word+'</strong></div>' +
							'<div class="value" rel="'+id+'">'+data.value+'</div>' +
						'</td>' +
						'<td>' +
							'<div class="btn-toolbar">' +
								'<div class="btn-group">' +
									'<a href="" class="btn btn-mini edit" rel="'+id+'">'+data.edit+'</a>' +
								'</div>' +
								'<div class="btn-group">' +
									'<a href="" class="btn btn-mini btn-danger delete" rel="'+id+'">'+data.delete+'</a>' +
								'</div>' +
							'</div>' +
						'</td>' +
					'</tr>';

		noty({
			type : response,
			text: message,			
			layout: "top",
			theme: "noty_theme_twitter",
			speed: 250,
			timeout: 1500,
			onShow: function() {
				if(!data.messages) {
					$('#translation tr[rel='+id+']').remove();
					$('#translation').prepend(tpl);
					$('#word').val('');
					$('#value').val('');
				}
			}
		});

	},

	saveTranslation:
	function() {

		$('a.save_form').live('click', function() {

			var url = $(this).attr('href');
			var disabled = $(this).hasClass('disabled');

			if(!disabled) {

				var options = {
					dataType: 'json',
					data: { back_url: url },
					success: $.cms.processTranslation
				};

				var form = $(this).attr('rel');
				$('#'+form).ajaxSubmit(options);

			}
			
			return false;

		});
	},

	changeTransLang:
	function() {

		//var lang = $('#trans_to').val();
		//$('input[name=lang_to]').val(lang);

		$('#trans_to').live('change', function() {
			var lang = $(this).val();
			if(lang.length > 0) {
				window.location = BASE+'/cms/translation/'+lang;
			}
		});
	},

	//BLOG

	dateTimePicker:
	function() {
		//$('.datetimepicker').datetimepicker();
		$('.datetimepicker_on').datetimepicker({
		    onClose: function(dateText, inst) {
		        var endDateTextBox = $('.datetimepicker_off');
		        if (endDateTextBox.val() != '') {
		            var testStartDate = new Date(dateText);
		            var testEndDate = new Date(endDateTextBox.val());
		            /*if (testStartDate > testEndDate)
		                endDateTextBox.val(dateText);*/
		        }
		        else {
		            //endDateTextBox.val(dateText);
		        }
		    },
		    onSelect: function (selectedDateTime){
		        var start = $(this).datetimepicker('getDate');
		        $('.datetimepicker_off').datetimepicker('option', 'minDate', new Date(start.getTime()));
		    }
		});
		$('.datetimepicker_off').datetimepicker({
		    onClose: function(dateText, inst) {
		        var startDateTextBox = $('.datetimepicker_on');
		        if (startDateTextBox.val() != '') {
		            var testStartDate = new Date(startDateTextBox.val());
		            var testEndDate = new Date(dateText);
		            if (testStartDate > testEndDate)
		                startDateTextBox.val(dateText);
		        }
		        else {
		            startDateTextBox.val(dateText);
		        }
		    },
		    onSelect: function (selectedDateTime){
		        var end = $(this).datetimepicker('getDate');
		        $('.datetimepicker_on').datetimepicker('option', 'maxDate', new Date(end.getTime()) );
		    }
		});
	},

	blog2Slug:
	function() {
		$('#blog_name').stringToSlug({getPut: '#blog_slug'});
		var $blog_slug = $('#blog_slug');
		var $addon_slug = $('span[rel=blog_slug]');
		var $psw = $blog_slug.width();
		var $asw = $addon_slug.width();
		var $tot = parseInt($psw + $asw);
		$('#blog_parent').live('change', function() {
			var $parent = $(this).val();			
			if($parent) {

				//Empty select zone
				$('#blog_zone').find('option[value!=0]').remove();
				
				//Get zones for parent layout
				$.post(BASE+'/cms/ajax/get/page/parent/zones', {
					parent_id: $parent
				}, function(data) {
					var sel = $('#blog_zone');
					$.each(data, function(val, text) {
						sel.append(
							$('<option></option>').val(val).html(text)
						);
					});
				});

				//Set media button rel
				$('.open-media-modal').attr('rel', $parent);

				$.post(BASE+'/cms/ajax/get/page/parent/paths',{
					parent_id: $parent
				},function(data) {
					$('span.add-on[rel=blog_slug]').html('/').prepend(data);
					$('#blog_parent_slug').val(data+'/');
					$diff = $tot - $('span[rel=blog_slug]').width();			
					$blog_slug.css('width', $diff);
				});
			}
		});
	},

	//TAGS

	suggestTags:
	function(where) {

		var $lang = $('#'+where+'_lang').val();
		var $id = $('.'+where+'_id').val();
		var $populate = null;

		$.post(BASE+'/cms/ajax/populate/tags/'+where, {
			id: $id
		}, function(data) {
			
			if(data.length>0) $populate = jQuery.parseJSON(data);

			$('#tags_text').autoSuggest(BASE+'/cms/ajax/get/tags', {
				preFill: $populate,
				minChars: 2,
				selectedItemProp: "name",
				searchObjProps: "name",
				neverSubmit: true,
				asHtmlID: 'tags_id',
				startText: 'Tags...',
				emptyText: '...',			
				extraParams: '&lang=' + $lang
			});

		});		

		
	},

	addNewTag:
	function(where) {

		$('#add_tag').live('click', function() {

			var $lang = $('#'+where+'_lang').val();
			var $tag = $('#new_tag').val();

			$.post(BASE+'/cms/ajax/add/tags', {
				tag_lang: $lang,
				tag_name: $tag
			}, function(data) {
				$('#new_tag').val('');
				$data = jQuery.parseJSON(data);
				$.cms.processJson($data);
			})

			return false;
		});

	},

	//BANNER

	bannerDatePicker:
	function() {
		$('.date_off').datepicker();
	},

	//DASHBOARD

	analyticsVisits:
	function() {

		// helper for returning the weekends in a period
		function weekendAreas(axes) {
			var markings = [];
			var d = new Date(axes.xaxis.min);
			// go to the first Saturday
			d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
			d.setUTCSeconds(0);
			d.setUTCMinutes(0);
			d.setUTCHours(0);
			var i = d.getTime();
			do {
			// when we don't set yaxis, the rectangle automatically
			// extends to infinity upwards and downwards
			markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
			i += 7 * 24 * 60 * 60 * 1000;
			} while (i < axes.xaxis.max);
			return markings;
		} 
		
		var placeholder = $("#placeholder");
		
		var options = {
			xaxis: { show: true, mode: "time", timeformat: "%0d/%0m", ticks: 15, tickLength: 5 },
			lines: { show: true, fill: true, fillColor: "rgba(119, 185, 101, 0.5)" },
			points: { show: true, fill: true, fillColor: "rgba(76, 154, 55, 1)" },
			grid: { markings: weekendAreas }
		};
		
		$.getJSON(BASE+'/cms/dashboard/analytics_data', function(data) {		
			
			$.plot(placeholder, [data], options);
			
		});
		
	},


	
}
