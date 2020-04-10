( function( $ ) {


	$('.templatesInstallDivOne').hide();
	$('.tempPaca input:disabled').prop('disabled',false);
	$('.tempPaca input').next('label').html(' Select ');
	$('.template-card input').next('label').html(' Select ');


$(document).on("click", ".updateTemplate" , function() {
	if ( pageBuilderApp.rowList.length > 0 ) {
		$('.popb_confirm_template_action_popup').css('display','block');

		$('.confirm_template_no').click(function(){
			isConfirmTrue_c = false;
			$('.popb_confirm_template_action_popup').css('display','none');
		});
	}else{
		$('.confirm_template_yes_replace').trigger('click');
	}
	
	$('.runTemplateUpdateFunction').val('true');

});


$('.confirm_template_yes').on('click', function(){

		var isTemplateReplace = $(this).attr('data-tempisreplace');

		$('.popb_confirm_template_action_popup').css('display','none');
		isConfirmTrue_c = true;

	   var popb_selected_template = $('input[name=template_select]:checked').val();
	   var pageOptions = "";
	   if (isConfirmTrue_c == true) {
	   	var pageSeoName = $('#title').val();
	   	var PbPageStatus = $('.PbPageStatus').val();
	   	var pageLink = $('#editable-post-name-full').html();
	   	$('.pb_loader_container').slideDown(200);
	   	
	   	if (pageSeoName == '') {
	   		$('#title').val('PluginOps Page  - '+P_ID);
	   		pageSeoName = $('#title').val();
	   	}
	   	switch (popb_selected_template){
	   		case 'temp0':
	   			var currentAttrValue = jQuery('.templatesTabEditor').children('a').attr('href');
        		jQuery('.pluginops-tabs ' + currentAttrValue).show().siblings().hide();
        		jQuery('.templatesTabEditor').addClass('pluginops-active').siblings().removeClass('pluginops-active');
        		$('.pb_fullScreenEditorButton').trigger('click');
        		$('.pb_loader_container').slideUp(200);
        		return;
	   		break;
	   		case 'temp2':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp3':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp4':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp9':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp13':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp16':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp17':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp20':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp21':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"http://smuzthemes.com/wp-content/uploads/2017/07/stock-0001.jpg","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp22':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp23':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'tempftp1':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'tempftp2':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'tempftp3':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'tempftp4':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp25':
	   		var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp26':
	   			var pageOptions = '{"loadWpHead":"false","loadWpFooter":"true","pageBgImage":"https://images.unsplash.com/photo-1503945438517-f65904a52ce6?dpr=1&auto=compress,format&fit=crop&w=1950&h=&q=80&cs=tinysrgb&crop=","pageBgColor":" ","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"0","pagePaddingBottom":"0","pagePaddingLeft":"0","pagePaddingRight":"0"},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":""}';
	   		break;
	   		case 'temp32':
	   			var pageOptions = '{"setFrontPage":"false","loadWpHead":"false","loadWpFooter":"false","pageBgImage":"","pageBgColor":"transparent","pageLink":"'+pageLink+'","pagePadding":{"pagePaddingTop":"","pagePaddingBottom":"","pagePaddingLeft":"","pagePaddingRight":""},"pagePaddingTablet":{"pagePaddingTopTablet":"","pagePaddingBottomTablet":"","pagePaddingLeftTablet":"","pagePaddingRightTablet":""},"pagePaddingMobile":{"pagePaddingTopMobile":"","pagePaddingBottomMobile":"","pagePaddingLeftMobile":"","pagePaddingRightMobile":""},"pageSeoName":"'+pageSeoName+'","pageSeoDescription":"","pageSeoKeywords":"","pageLogoUrl":"","pageFavIconUrl":"","VariantB_ID":null,"POcustomCSS":".temp-22-sub-form input {\\n    border:none !important;\\n    border-bottom:1px solid #00C2A6 !important;\\n}","POcustomJS":"\\/* Add your custom Javascript here.*\\/","POPBDefaults":{"POPBDefaultsEnable":"false","POPB_typefaces":{"typefaceHOne":"Arial","typefaceHTwo":"Arial","typefaceParagraph":"Arial","typefaceButton":"Arial","typefaceAnchorLink":"Arial"},"POPB_typeSizes":{"typeSizeHOne":"45","typeSizeHTwo":"29","typeSizeParagraph":"15","typeSizeButton":"16","typeSizeAnchorLink":"15","typeSizeHOneTablet":"","typeSizeHOneMobile":"","typeSizeHTwoTablet":"","typeSizeHTwoMobile":"","typeSizeParagraphTablet":"","typeSizeParagraphMobile":"","typeSizeButtonTablet":"","typeSizeButtonMobile":"","typeSizeAnchorLinkTablet":"","typeSizeAnchorLinkMobile":""}}}';
	   		break;
	   		case 'temp34':
	   			var pageOptions = '{"setFrontPage":"false","loadWpHead":"false","loadWpFooter":"false","pageBgImage":"","pageBgColor":"#f5f5f5","pageLink":"template-34","pagePadding":{"pagePaddingTop":"","pagePaddingBottom":"","pagePaddingLeft":"","pagePaddingRight":""},"pagePaddingTablet":{"pagePaddingTopTablet":"","pagePaddingBottomTablet":"","pagePaddingLeftTablet":"","pagePaddingRightTablet":""},"pagePaddingMobile":{"pagePaddingTopMobile":"","pagePaddingBottomMobile":"","pagePaddingLeftMobile":"","pagePaddingRightMobile":""},"pageSeoName":"Template #34","pageSeoDescription":"","pageSeoKeywords":"","pageLogoUrl":"","pageFavIconUrl":"","VariantB_ID":null,"POcustomCSS":"","POcustomJS":"/* Add your custom Javascript here.*/","POPBDefaults":{"POPBDefaultsEnable":"false","POPB_typefaces":{"typefaceHOne":"Arial","typefaceHTwo":"Arial","typefaceParagraph":"Arial","typefaceButton":"Arial","typefaceAnchorLink":"Arial"},"POPB_typeSizes":{"typeSizeHOne":"45","typeSizeHTwo":"29","typeSizeParagraph":"15","typeSizeButton":"16","typeSizeAnchorLink":"15","typeSizeHOneTablet":"","typeSizeHOneMobile":"","typeSizeHTwoTablet":"","typeSizeHTwoMobile":"","typeSizeParagraphTablet":"","typeSizeParagraphMobile":"","typeSizeButtonTablet":"","typeSizeButtonMobile":"","typeSizeAnchorLinkTablet":"","typeSizeAnchorLinkMobile":""}}}';
	   		break;
	   		default: 
	   		
	   		break;
	   	}

	   	pageOptionsNeedToParse = 'true';
	   	var model = '';
		$.ajax({
		  type: 'GET',
		  dataType: "json",
		  url: bbfourLinks.templatesFolder+popb_selected_template+".json",
		  data: { get_param: 'value' },
		  success: function( data ) {
	   		model = data;
	   		if (pageOptions == "") {
	   			if ( typeof(model['pageOptions']) != 'undefined' ) {
	   				pageOptions = model['pageOptions'];
	   				pageOptionsNeedToParse = 'false';
	   			}
	   			if ( typeof(model['Rows']) != 'undefined' ) {
	   				model = model['Rows'];
	   			}
	   		}
		  },
		  error: function( xhr, ajaxOptions, thrownError ) {
	   		alert('Some Error Occurred');
		  },
		  async:false
		});

		if (model == '') {
			$('.pb_loader_container').slideUp(200);
		}else{

			if (isTemplateReplace == 'true') {

				var ex_modelRows;
				while (ex_modelRows = pageBuilderApp.rowList.first()) {
				  ex_modelRows.destroy();
				}

				$.each(model, function(index, val) {
					val['rowID'] = 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100);
					pageBuilderApp.rowList.add(val);
				});

				pageBuilderApp.PageBuilderModel.set( 'Rows', model );
			    pageBuilderApp.PageBuilderModel.set( 'pageStatus', PbPageStatus );
			    if (pageOptions !== "") {
			    	if (pageOptionsNeedToParse == 'true') {
			    		parsedPageOptions = JSON.parse(pageOptions);
			    	}else{
			    		parsedPageOptions = pageOptions;
			    	}

			    	parsedPageOptions['pageLink'] = pageLink;
			    	parsedPageOptions['pageSeoName'] = pageSeoName;

			    	pageBuilderApp.PageBuilderModel.set( 'pageOptions', parsedPageOptions );
			    }
			    var savedPageID = pageBuilderApp.PageBuilderModel.get('pageID');
			    if (P_ID !== savedPageID ) {
			    	pageBuilderApp.PageBuilderModel.set('pageID', P_ID );
			    	var savedPageID = pageBuilderApp.PageBuilderModel.get('pageID');
			    	//console.log(savedPageID);
			    }
			

				pageBuilderApp.PageBuilderModel.save({ wait:true }).success(function(response){

			    	setTimeout(function(){
				      	$('.pb_loader_container').slideUp(200);
					    var pageOptions = pageBuilderApp.PageBuilderModel.get('pageOptions');
					    var pageStatus = pageBuilderApp.PageBuilderModel.get('pageStatus');
					    renderPageOps(pageOptions, pageStatus);
					    pageBuilderApp.PgCollectionView.render();

					    var currentAttrValue = jQuery('.templatesTabEditor .pluginops-tab_link').attr('href');
 
			        	jQuery('.pluginops-tabs ' + currentAttrValue).show().siblings().hide();
			 
			        	jQuery('.templatesTabEditor .pluginops-tab_link').parent('li').addClass('pluginops-active').siblings().removeClass('pluginops-active');
			 		
			 			$('.pb_fullScreenEditorButton').trigger('click');
				        //window.location.href = admURL+'post.php?post='+P_ID+'&action=edit'; 
				    }, 1000);
				    console.log('Saved');

				}).error(function(response){

				    if (response['responseText'] == 'Invalid Nonce') {
				      alert('Nonce Expired  - Changes cannot be saved, Please reload the page.');
				      $('.pb_loader_container').slideUp(200);
				    }else{
				      alert('Page Not Saved - Some Error Occurred! ');
				      $('.pb_loader_container').slideUp(200);
				    }

				});
			} else{
				$.each(model, function(index, val) {
					val['rowID'] = 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100);
					pageBuilderApp.rowList.add(val);
				});
				
				$('.pb_loader_container').slideUp(200);

				var currentAttrValue = jQuery('.templatesTabEditor .pluginops-tab_link').attr('href');
 
		        jQuery('.pluginops-tabs ' + currentAttrValue).show().siblings().hide();
		 
		        jQuery('.templatesTabEditor .pluginops-tab_link').parent('li').addClass('pluginops-active').siblings().removeClass('pluginops-active');
		 		
		 		$('.pb_fullScreenEditorButton').trigger('click');
			}
				

		}
		    

	   }

	   $('.runTemplateUpdateFunction').val('false');
});



$(document).ready(function(){

  $('.rowBlockUpdateBtn').click(function(ev){
    var blockName = $(ev.target).attr('data-rowBlockName');
    var rowBlock = '';
    var modelIndex = $('.insertRowBlockAtIndex').val();
    modelIndex = parseInt(modelIndex) +1;
    $.ajax({
      type: 'GET',
      dataType: "json",
      url: bbfourLinks.pluginsUrl+'/admin/scripts/blocks/rowBlocks/'+blockName+''+".json",
      data: { get_param: 'value' },
      success: function( data ) {
        rowBlock = data;
      },
      error: function(  thrownError ) {
        alert('Some Error Occurred');
        console.log(thrownError);
      },
      async:false
    });
    
    if ( typeof(rowBlock['multiRows']) != 'undefined' ) {
    	console.log(rowBlock['multiRows'])

    	$.each(rowBlock['multiRows'], function(index,val){
    		var addModelAtIndex = parseInt(index) + parseInt(modelIndex);
    		console.log( addModelAtIndex );
    		val['rowID'] = 'ulpb_Row'+Math.floor((Math.random() * 300000) + 100);
    		pageBuilderApp.rowList.add( val , {at: addModelAtIndex} );

    		var duplicatedModel = pageBuilderApp.rowList.at(addModelAtIndex);
		    var rowCID = duplicatedModel.cid;
		    var thisChangeRedoProps = {
		        changeModelType : 'rowSpecialAction',
		        thisModelCId: rowCID,
		        thisModelElId:val['rowID'],
		        specialAction:'duplicate',
		        thisModelVal:duplicatedModel,
		        thisModelIndex:addModelAtIndex
		    }
		    sendDataBackToUndoStack(thisChangeRedoProps);

    	});
    	$('.insertRowBlock').hide(300);


    }else{
    	rowBlock['rowID'] = 'ulpb_Row'+Math.floor((Math.random() * 300000) + 100);
	    if (rowBlock !='' ) {
	      	pageBuilderApp.rowList.add( rowBlock , {at: modelIndex} );

	      	var duplicatedModel = pageBuilderApp.rowList.at(modelIndex);
		    var rowCID = duplicatedModel.cid;
		    var thisChangeRedoProps = {
		        changeModelType : 'rowSpecialAction',
		        thisModelCId: rowCID,
		        thisModelElId:rowBlock['rowID'],
		        specialAction:'duplicate',
		        thisModelVal:duplicatedModel,
		        thisModelIndex:modelIndex
		    }

		    sendDataBackToUndoStack(thisChangeRedoProps);
	      	$('.insertRowBlock').hide(300);
	    }else{
	    }
    }
    
    $('.insertRowBlockAtIndex').val('');
  });
  $('.addNewRowBlockVisible').click(function(ev){
    modelIndex = pageBuilderApp.rowList.length;
    $('.insertRowBlockAtIndex').val(modelIndex  - 1);

    $('.ulpb_column_controls').hide();
    hideWidgetOpsPanel();
    $('.pageops_modal').hide(300);
    $('.edit_column').hide(300);

    $('.insertRowBlock').show(300);
  });
  $('.insertRowBlockClosebutton').click(function(ev){
    $('.insertRowBlock').hide(300);
  });

});

}( jQuery ) );