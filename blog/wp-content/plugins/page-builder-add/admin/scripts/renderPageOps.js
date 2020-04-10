function renderPageOps(pageOptions, pageStatus) {
	( function( $ ) {
		  var frontPage = pageOptions['frontPage'];
	      var loadWpHead = pageOptions['loadWpHead'];
	      var loadWpFooter = pageOptions['loadWpFooter'];
	      var pageSeoName = pageOptions['pageSeoName'];
	      var pageLink = pageOptions['pageLink'];
	      var pageSeoDescription = pageOptions['pageSeoDescription'];
	      var pageSeoKeywords = pageOptions['pageSeoKeywords'];
	      var pageLogoUrl = pageOptions['pageLogoUrl'];
	      var pageFavIconUrl = pageOptions['pageFavIconUrl'];
	      var VariantB_ID = pageOptions['VariantB_ID'];

	      var pageBgColor = pageOptions['pageBgColor'];
	      var pageBgImage = pageOptions['pageBgImage'];
	      var pagePadding = pageOptions['pagePadding'];
	      var pagePaddingTop = pagePadding['pagePaddingTop'];
	      var pagePaddingBottom = pagePadding['pagePaddingBottom'];
	      var pagePaddingRight = pagePadding['pagePaddingRight'];
	      var pagePaddingLeft = pagePadding['pagePaddingLeft'];

	      var POcustomCSS = pageOptions['POcustomCSS'];
	      var POcustomJS = pageOptions['POcustomJS'];


	      POPBDefaults = '';
	      if (typeof(pageOptions['POPBDefaults'])  != 'undefined') {
	        var POPBDefaults = pageOptions['POPBDefaults'];
	      }
	      
	      POPBDefaultsEnable = '';
	      if (typeof(POPBDefaults['POPBDefaultsEnable'])  != 'undefined') {
	        var POPBDefaultsEnable = POPBDefaults['POPBDefaultsEnable'];
	      }

	      POPB_typefaces = '';
	      if (typeof(POPBDefaults['POPB_typefaces'])  != 'undefined') {
	        var POPB_typefaces = POPBDefaults['POPB_typefaces'];
	      }

	      POPB_typeSizes = '';
	      if (typeof(POPBDefaults['POPB_typeSizes'])  != 'undefined') {
	        var POPB_typeSizes = POPBDefaults['POPB_typeSizes'];
	      }

	      if (typeof(pageOptions['MultiVariantTesting'])  != 'undefined') {
	        var MultiVariantTesting = pageOptions['MultiVariantTesting'];
	        $('.VariantB').val(MultiVariantTesting['VariantB']);
	        $('.VariantC').val(MultiVariantTesting['VariantC']);
	        $('.VariantD').val(MultiVariantTesting['VariantD']);
	      }

	      if (POPB_typefaces != '') {
	        
	        $.each(POPB_typefaces, function(index,val){
	          $('.'+index).val(val);

	          $('.'+index).next('.font-select').children('a').children('.font_select_placeholder').html($('.'+index).val().replace(/\+/g, ' '));

	        });
	      }

	      $.each(POPB_typeSizes, function(index,val){
	        $('.'+index).val(val);
	      });


	      if (typeof(pageOptions['POPBDefaults'])  != 'undefined') {

	        $('.POPBDefaultsEnable').val(POPBDefaultsEnable);

	        if (POPBDefaultsEnable == 'true') {

	          $('#fontLoaderContainer').html('<link rel="stylesheet"href="https://fonts.googleapis.com/css?family='+POPB_typefaces['typefaceHOne']+'|'+POPB_typefaces['typefaceHTwo']+'|'+POPB_typefaces['typefaceParagraph']+'|'+POPB_typefaces['typefaceButton']+'|'+POPB_typefaces['typefaceAnchorLink']+' ">');

	          if (typeof( POPB_typefaces['typefaceH3'] ) == 'undefined') {
	            POPB_typefaces['typefaceH3'] = '';
	          }
	          if (typeof( POPB_typefaces['typefaceH4'] ) == 'undefined') {
	            POPB_typefaces['typefaceH4'] = '';
	          }
	          if (typeof( POPB_typefaces['typefaceH5'] ) == 'undefined') {
	            POPB_typefaces['typefaceH5'] = '';
	          }
	          if (typeof( POPB_typefaces['typefaceH6'] ) == 'undefined') {
	            POPB_typefaces['typefaceH6'] = '';
	          }

	          var POPBGlobalStylesTag = '\n'+

	            '#pbWrapper h1 { font-family:'+POPB_typefaces['typefaceHOne'].replace(/\+/g, ' ')+'; font-size:'+POPB_typeSizes['typeSizeHOne']+'px ; }  \n'+

	            '#pbWrapper h2 { font-family:'+POPB_typefaces['typefaceHTwo'].replace(/\+/g, ' ')+'; font-size:'+POPB_typeSizes['typeSizeHTwo']+'px ; }  \n'+

	            '#pbWrapper h3 { font-family:'+POPB_typefaces['typefaceH3'].replace(/\+/g, ' ')+'; font-size:'+POPB_typeSizes['typeSizeH3']+'px ; }  \n'+

	            '#pbWrapper h4 { font-family:'+POPB_typefaces['typefaceH4'].replace(/\+/g, ' ')+'; font-size:'+POPB_typeSizes['typeSizeH4']+'px ; }  \n'+

	            '#pbWrapper h5 { font-family:'+POPB_typefaces['typefaceH5'].replace(/\+/g, ' ')+'; font-size:'+POPB_typeSizes['typeSizeH5']+'px ; }  \n'+

	            '#pbWrapper h6 { font-family:'+POPB_typefaces['typefaceH6'].replace(/\+/g, ' ')+'; font-size:'+POPB_typeSizes['typeSizeH6']+'px ; }  \n'+

	            '#pbWrapper p { font-family:'+POPB_typefaces['typefaceParagraph'].replace(/\+/g, ' ')+'; font-size:'+POPB_typeSizes['typeSizeParagraph']+'px ; }  \n'+

	            '#pbWrapper p span { font-family:'+POPB_typefaces['typefaceParagraph'].replace(/\+/g, ' ')+'; }  \n'+

	            '#pbWrapper button { font-family:'+POPB_typefaces['typefaceButton'].replace(/\+/g, ' ')+'; font-size:'+POPB_typeSizes['typeSizeButton']+'px; }  \n'+
	            
	            '#pbWrapper a { font-family:'+POPB_typefaces['typefaceAnchorLink'].replace(/\+/g, ' ')+'; font-size:'+POPB_typeSizes['typeSizeAnchorLink']+'px ; } \n';

	          $('#POPBGlobalStylesTag').html(POPBGlobalStylesTag);
	        }else{
	          $('#POPBGlobalStylesTag').html('');
	        }
	      }



	      if (typeof(pageOptions['POcustomCSS']) == 'undefined') { POcustomCSS = '/*Add your custom CSS here.*/'}

	      $('.POcustomCSS').val(POcustomCSS);
	      if (POcustomCSS !== '') {
	      } else {
	      }

	      if (typeof(pageOptions['POcustomJS']) == 'undefined') { POcustomJS = '/*Add your custom CSS here.*/'}

	      $('.POcustomJS').val(POcustomJS);
	      if (POcustomJS !== '') {
	      } else {
	      }

	    getLPTitle = $('#title').val();
	    if (getLPTitle == '' || getLPTitle == ' ' || getLPTitle == 'Auto Draft') {
	    	$('#title').val(pageSeoName);
	    }
	    $('#new-post-slug').val(pageLink);
	    $('#title-prompt-text').html(' ');
	    $('.PbPageStatus').val(pageStatus);
	    $('.pageSeoDescription').val(pageSeoDescription);
	    $('.pageSeoMetaTags').val(pageOptions['pageSeoMetaTags']);
	    $('.pageSeoKeywords').val(pageSeoKeywords);
	    $('.pageBgImage').val(pageBgImage);
	    $('.pageBgColor').val(pageBgColor);
	    $('.pagePaddingTop').val(pagePaddingTop);
	    $('.pagePaddingBottom').val(pagePaddingBottom);
	    $('.pagePaddingLeft').val(pagePaddingLeft);
	    $('.pagePaddingRight').val(pagePaddingRight); 
	    $('.pageLogoUrl').val(pageLogoUrl);
	    $('.pageFavIconUrl').val(pageFavIconUrl);
	    $('.pageSeofbOgImage').val(pageOptions['pageSeofbOgImage']);
	    $('.VariantB_ID').val(VariantB_ID);

	    if (pageStatus == 'publish') {
	      $('.draftBtn').css('display','none');
	      $('#pbbtnRedo').css('margin-right','-4%');
	      $('.publishBtn').text('Update');
	    }else{
	      $('#pbbtnRedo').css('margin-right','-12%');
	    }

	    if (typeof(pageOptions['pagePaddingTablet']) !== 'undefined') {
	      pagePaddingTablet = pageOptions['pagePaddingTablet'];
	      pagePaddingMobile = pageOptions['pagePaddingMobile'];

	      $('.pagePaddingTopTablet').val(pagePaddingTablet['pagePaddingTopTablet']);
	      $('.pagePaddingBottomTablet').val(pagePaddingTablet['pagePaddingBottomTablet']);
	      $('.pagePaddingLeftTablet').val(pagePaddingTablet['pagePaddingLeftTablet']);
	      $('.pagePaddingRightTablet').val(pagePaddingTablet['pagePaddingRightTablet']); 

	      $('.pagePaddingTopMobile').val(pagePaddingMobile['pagePaddingTopMobile']);
	      $('.pagePaddingBottomMobile').val(pagePaddingMobile['pagePaddingBottomMobile']);
	      $('.pagePaddingLeftMobile').val(pagePaddingMobile['pagePaddingLeftMobile']);
	      $('.pagePaddingRightMobile').val(pagePaddingMobile['pagePaddingRightMobile']); 

	    }


	    var bodyID = 'pbWrapper';
	      var bodyBackgroundOptions = 'background-color:' + pageBgColor + ';';
	      if (pageBgImage != '') {
	        bodyBackgroundOptions = 'background: url(' + pageBgImage + '); background-size: cover; ';
	      }
	      if (typeof (pageOptions['bodyBackgroundType']) !== 'undefined') {
	        if (pageOptions['bodyBackgroundType'] == 'gradient') {
	          var bodyGradient = pageOptions['bodyGradient'];
	          if (bodyGradient['bodyGradientType'] == 'linear') {
	            bodyBackgroundOptions = 'background: linear-gradient(' + bodyGradient['bodyGradientAngle'] + 'deg, ' + bodyGradient['bodyGradientColorFirst'] + ' ' + bodyGradient['bodyGradientLocationFirst'] + '%,' + bodyGradient['bodyGradientColorSecond'] + ' ' + bodyGradient['bodyGradientLocationSecond'] + '%);';
	          }
	          if (bodyGradient['bodyGradientType'] == 'radial') {
	            bodyBackgroundOptions = 'background: radial-gradient(at ' + bodyGradient['bodyGradientPosition'] + ', ' + bodyGradient['bodyGradientColorFirst'] + ' ' + bodyGradient['bodyGradientLocationFirst'] + '%,' + bodyGradient['bodyGradientColorSecond'] + ' ' + bodyGradient['bodyGradientLocationSecond'] + '%);';
	          }
	        }
	      }
	      var thisbodyHoverStyleTag = '';
	      var thisbodyHoverOption = '';
	      if (typeof (pageOptions['bodyHoverOptions']) !== 'undefined') {
	        var bodyHoverOptions = pageOptions['bodyHoverOptions'];
	        if (bodyHoverOptions['bodyBackgroundTypeHover'] == 'solid') {
	          var thisbodyHoverOption = ' #' + bodyID + ':hover { background:' + bodyHoverOptions['bodyBgColorHover'] + ' !important; transition: all ' + bodyHoverOptions['bodyHoverTransitionDuration'] + 's; }';
	        }
	        if (bodyHoverOptions['bodyBackgroundTypeHover'] == 'gradient') {
	          var bodyGradientHover = bodyHoverOptions['bodyGradientHover'];
	          if (bodyGradientHover['bodyGradientTypeHover'] == 'linear') {
	            thisbodyHoverOption = ' #' + bodyID + ':hover { background: linear-gradient(' + bodyGradientHover['bodyGradientAngleHover'] + 'deg, ' + bodyGradientHover['bodyGradientColorFirstHover'] + ' ' + bodyGradientHover['bodyGradientLocationFirstHover'] + '%,' + bodyGradientHover['bodyGradientColorSecondHover'] + ' ' + bodyGradientHover['bodyGradientLocationSecondHover'] + '%) !important; transition: all ' + bodyHoverOptions['bodyHoverTransitionDuration'] + 's; }';
	          }
	          if (bodyGradientHover['bodyGradientTypeHover'] == 'radial') {
	            thisbodyHoverOption = ' #' + bodyID + ':hover { background: radial-gradient(at ' + bodyGradientHover['bodyGradientPositionHover'] + ', ' + bodyGradientHover['bodyGradientColorFirstHover'] + ' ' + bodyGradientHover['bodyGradientLocationFirstHover'] + '%,' + bodyGradientHover['bodyGradientColorSecondHover'] + ' ' + bodyGradientHover['bodyGradientLocationSecondHover'] + '%) !important; transition: all ' + bodyHoverOptions['bodyHoverTransitionDuration'] + 's; }';
	          }
	        }
	        jQuery('#POPBBodyHoverStylesTag').html(thisbodyHoverOption);
	      }
	      bodyOverlayBackgroundOptions = '';
	      if (typeof (pageOptions['bodyBgOverlayColor']) !== 'undefined') {
	        var bodyOverlayBackgroundOptions = 'background:' + pageOptions['bodyBgOverlayColor'] + '; background-color:' + pageOptions['bodyBgOverlayColor'] + ';';
	      }
	      if (typeof (pageOptions['bodyOverlayBackgroundType']) !== 'undefined') {
	        if (pageOptions['bodyOverlayBackgroundType'] == 'gradient') {
	          var bodyOverlayGradient = pageOptions['bodyOverlayGradient'];
	          if (bodyOverlayGradient['bodyOverlayGradientType'] == 'linear') {
	            bodyOverlayBackgroundOptions = 'background: linear-gradient(' + bodyOverlayGradient['bodyOverlayGradientAngle'] + 'deg, ' + bodyOverlayGradient['bodyOverlayGradientColorFirst'] + ' ' + bodyOverlayGradient['bodyOverlayGradientLocationFirst'] + '%,' + bodyOverlayGradient['bodyOverlayGradientColorSecond'] + ' ' + bodyOverlayGradient['bodyOverlayGradientLocationSecond'] + '%);';
	          }
	          if (bodyOverlayGradient['bodyOverlayGradientType'] == 'radial') {
	            bodyOverlayBackgroundOptions = 'background: radial-gradient(at ' + bodyOverlayGradient['bodyOverlayGradientPosition'] + ', ' + bodyOverlayGradient['bodyOverlayGradientColorFirst'] + ' ' + bodyOverlayGradient['bodyOverlayGradientLocationFirst'] + '%,' + bodyOverlayGradient['bodyOverlayGradientColorSecond'] + ' ' + bodyOverlayGradient['bodyGradientLocationSecond'] + '%);';
	          }
	        }
	      }

	      jQuery('#pbWrapperContainerOverlay').attr('style',bodyOverlayBackgroundOptions);

	      // Populate body background Options
	      if (typeof (pageOptions['bodyGradient']) !== "undefined") {
	        var bodyGradient = pageOptions['bodyGradient'];
	        jQuery.each(bodyGradient, function (index, val) {
	          jQuery('.' + index).val(val);
	          if (index == 'bodyGradientColorFirst') {
	            jQuery('.bodyGradientColorFirst').parent().parent().siblings('.wp-color-result').children('.color-alpha').css('background', val);
	          }
	          if (index == 'bodyGradientColorSecond') {
	            jQuery('.bodyGradientColorSecond').parent().parent().siblings('.wp-color-result').children('.color-alpha').css('background', val);
	          }
	        });
	        if (bodyGradient['bodyGradientType'] == 'linear') {
	          jQuery('.radialInput').css('display', 'none');
	          jQuery('.linearInput').css('display', 'block');
	        } else if (bodyGradient['bodyGradientType'] == 'radial') {
	          jQuery('.radialInput').css('display', 'block');
	          jQuery('.linearInput').css('display', 'none');
	        }
	      } else {
	        jQuery('.bodyGradientColorFirst').val('');
	        jQuery('.bodyGradientLocationFirst').val('');
	        jQuery('.bodyGradientColorSecond').val('');
	        jQuery('.bodyGradientLocationSecond').val('');
	        jQuery('.bodyGradientType').val('');
	        jQuery('.bodyGradientPosition').val('');
	        jQuery('.bodyGradientAngle').val('');
	      }
	      if (typeof (pageOptions['bodyBackgroundType']) !== "undefined") {
	        if (pageOptions['bodyBackgroundType'] == 'solid') {
	          jQuery(".POPBInputNormalbody .bodyBackgroundTypeSolid").prop("checked", true);
	          jQuery('.POPBInputNormalbody .popbNavItem label').css({
	            'background': '#f1f1f1',
	            'color': '#333'
	          });
	          jQuery('.POPBInputNormalbody .bodyBackgroundTypeSolid').prev('label').css({
	            'background': '#a5a5a5',
	            'color': '#fff'
	          });
	          jQuery('.POPBInputNormalbody .popb_tab_content').css('display', 'none');
	          jQuery('.POPBInputNormalbody .content_popb_tab_1').css('display', 'block');
	        }
	        if (pageOptions['bodyBackgroundType'] == 'gradient') {
	          jQuery(".bodyBackgroundTypeGradient").prop("checked", true);
	          jQuery('.POPBInputNormalbody .popbNavItem label').css({
	            'background': '#f1f1f1',
	            'color': '#333'
	          });
	          jQuery('.bodyBackgroundTypeGradient').prev('label').css({
	            'background': '#a5a5a5',
	            'color': '#fff'
	          });
	          jQuery('.POPBInputNormalbody .popb_tab_content').css('display', 'none');
	          jQuery('.POPBInputNormalbody .content_popb_tab_2').css('display', 'block');
	        }
	      } else {
	        jQuery(".POPBInputNormalbody .bodyBackgroundTypeSolid").prop("checked", true);
	        jQuery('.popbNavItem label').css({
	          'background': '#f1f1f1',
	          'color': '#333'
	        });
	        jQuery('.POPBInputNormalbody .bodyBackgroundTypeSolid').prev('label').css({
	          'background': '#a5a5a5',
	          'color': '#fff'
	        });
	        jQuery('.popb_tab_content').css('display', 'none');
	        jQuery('.content_popb_tab_1').css('display', 'block');
	      }
	      if (typeof (pageOptions['bodyHoverOptions']) !== "undefined") {
	        var bodyHoverOptions = pageOptions['bodyHoverOptions'];
	        if (bodyHoverOptions['bodyBgColorHover'] != '' || typeof (bodyHoverOptions['bodyBgColorHover']) != 'undefined') {
	          jQuery('.bodyBgColorHover').val(bodyHoverOptions['bodyBgColorHover']);
	          jQuery('.bodyBgColorHover').parent().parent().siblings('.wp-color-result').children('.color-alpha').css('background', bodyHoverOptions['bodyBgColorHover']);
	        } else {
	          jQuery('.bodyBgColorHover').val('');
	        }
	        jQuery('.bodyHoverTransitionDuration').val(bodyHoverOptions['bodyHoverTransitionDuration']);
	        if (bodyHoverOptions['bodyBackgroundTypeHover'] == 'solid') {
	          jQuery(".bodyBackgroundTypeSolidHover").prop("checked", true);
	          jQuery('.POPBInputHoverbody .popbNavItem label').css({
	            'background': '#f1f1f1',
	            'color': '#333'
	          });
	          jQuery('.bodyBackgroundTypeSolidHover').prev('label').css({
	            'background': '#a5a5a5',
	            'color': '#fff'
	          });
	          jQuery('.POPBInputHoverbody .popb_tab_content').css('display', 'none');
	          jQuery('.POPBInputHoverbody .content_popb_tab_1').css('display', 'block');
	        }
	        if (bodyHoverOptions['bodyBackgroundTypeHover'] == 'gradient') {
	          jQuery(".bodyBackgroundTypeGradientHover").prop("checked", true);
	          jQuery('.POPBInputHoverbody .popbNavItem label').css({
	            'background': '#f1f1f1',
	            'color': '#333'
	          });
	          jQuery('.bodyBackgroundTypeGradientHover').prev('label').css({
	            'background': '#a5a5a5',
	            'color': '#fff'
	          });
	          jQuery('.POPBInputHoverbody .popb_tab_content').css('display', 'none');
	          jQuery('.POPBInputHoverbody .content_popb_tab_2').css('display', 'block');
	        }
	        if (bodyHoverOptions['bodyBackgroundTypeHover'] == '' || typeof (bodyHoverOptions['bodyBackgroundTypeHover']) == 'undefined') {
	          jQuery(".bodyBackgroundTypeSolidHover").prop("checked", false);
	          jQuery(".bodyBackgroundTypeGradientHover").prop("checked", false);
	          jQuery('.POPBInputHoverbody .popbNavItem label').css({
	            'background': '#f1f1f1',
	            'color': '#333'
	          });
	        }
	        var bodyGradientHover = bodyHoverOptions['bodyGradientHover'];
	        jQuery.each(bodyGradientHover, function (index, val) {
	          jQuery('.' + index).val(val);
	          if (index == 'bodyGradientColorFirstHover') {
	            jQuery('.bodyGradientColorFirstHover').parent().parent().siblings('.wp-color-result').children('.color-alpha').css('background', val);
	          }
	          if (index == 'bodyGradientColorSecondHover') {
	            jQuery('.bodyGradientColorSecondHover').parent().parent().siblings('.wp-color-result').children('.color-alpha').css('background', val);
	          }
	        });
	        if (bodyGradientHover['bodyGradientTypeHover'] == 'linear') {
	          jQuery('.radialInputHover').css('display', 'none');
	          jQuery('.linearInputHover').css('display', 'block');
	        } else if (bodyGradientHover['bodyGradientTypeHover'] == 'radial') {
	          jQuery('.radialInputHover').css('display', 'block');
	          jQuery('.linearInputHover').css('display', 'none');
	        }
	      } else {
	        jQuery('.bodyBgColorHover').val('');
	        jQuery('.bodyGradientColorFirstHover').val('');
	        jQuery('.bodyGradientLocationFirstHover').val('');
	        jQuery('.bodyGradientColorSecondHover').val('');
	        jQuery('.bodyGradientLocationSecondHover').val('');
	        jQuery('.bodyGradientTypeHover').val('');
	        jQuery('.bodyGradientPositionHover').val('');
	        jQuery('.bodyGradientAngleHover').val('');
	        jQuery(".bodyBackgroundTypeSolidHover").prop("checked", false);
	        jQuery(".bodyBackgroundTypeGradientHover").prop("checked", false);
	      }
	      if (typeof (pageOptions['bodyBgOverlayColor']) !== "undefined") {
	        jQuery('.bodyBgOverlayColor').val(pageOptions['bodyBgOverlayColor']);
	        jQuery('.bodyBgOverlayColor').parent().parent().siblings('.wp-color-result').children('.color-alpha').css('background', pageOptions['bodyBgOverlayColor']);
	      } else {
	        jQuery('.bodyBgOverlayColor').val('');
	      }
	      if (typeof (pageOptions['bodyOverlayGradient']) !== "undefined") {
	        var bodyOverlayGradient = pageOptions['bodyOverlayGradient'];
	        jQuery.each(bodyOverlayGradient, function (index, val) {
	          jQuery('.' + index).val(val);
	          if (index == 'bodyOverlayGradientColorFirst') {
	            jQuery('.bodyOverlayGradientColorFirst').parent().parent().siblings('.wp-color-result').children('.color-alpha').css('background', val);
	          }
	          if (index == 'bodyOverlayGradientColorSecond') {
	            jQuery('.bodyOverlayGradientColorSecond').parent().parent().siblings('.wp-color-result').children('.color-alpha').css('background', val);
	          }
	        });
	        if (bodyOverlayGradient['bodyOverlayGradientType'] == 'linear') {
	          jQuery('.radialInput').css('display', 'none');
	          jQuery('.linearInput').css('display', 'block');
	        } else if (bodyOverlayGradient['bodyOverlayGradientType'] == 'radial') {
	          jQuery('.radialInput').css('display', 'block');
	          jQuery('.linearInput').css('display', 'none');
	        }
	      } else {
	        jQuery('.bodyOverlayGradientColorFirst').val('');
	        jQuery('.bodyOverlayGradientLocationFirst').val('');
	        jQuery('.bodyOverlayGradientColorSecond').val('');
	        jQuery('.bodyOverlayGradientLocationSecond').val('');
	        jQuery('.bodyOverlayGradientType').val('');
	        jQuery('.bodyOverlayGradientPosition').val('');
	        jQuery('.bodyOverlayGradientAngle').val('');
	      }
	      if (typeof (pageOptions['bodyOverlayBackgroundType']) !== "undefined") {
	        if (pageOptions['bodyOverlayBackgroundType'] == 'solid') {
	          jQuery(".POPBInputNormalbody .bodyOverlayBackgroundTypeSolid").prop("checked", true);
	          jQuery('.POPBInputNormalbody .popbNavItem label').css({
	            'background': '#f1f1f1',
	            'color': '#333'
	          });
	          jQuery('.POPBInputNormalbody .bodyOverlayBackgroundTypeSolid').prev('label').css({
	            'background': '#a5a5a5',
	            'color': '#fff'
	          });
	          jQuery('.POPBInputNormalbody .popb_tab_content').css('display', 'none');
	          jQuery('.POPBInputNormalbody .content_popb_tab_1').css('display', 'block');
	        }
	        if (pageOptions['bodyOverlayBackgroundType'] == 'gradient') {
	          jQuery(".bodyOverlayBackgroundTypeGradient").prop("checked", true);
	          jQuery('.POPBInputNormalbody .popbNavItem label').css({
	            'background': '#f1f1f1',
	            'color': '#333'
	          });
	          jQuery('.bodyOverlayBackgroundTypeGradient').prev('label').css({
	            'background': '#a5a5a5',
	            'color': '#fff'
	          });
	          jQuery('.POPBInputNormalbody .popb_tab_content').css('display', 'none');
	          jQuery('.POPBInputNormalbody .content_popb_tab_2').css('display', 'block');
	        }
	      } else {
	        jQuery(".POPBInputNormalbody .bodyOverlayBackgroundTypeSolid").prop("checked", false);
	        jQuery(".POPBInputNormalbody .bodyOverlayBackgroundTypeGradient").prop("checked", false);
	        jQuery('.popbNavItem label').css({
	          'background': '#f1f1f1',
	          'color': '#333'
	        });
	        jQuery('.popb_tab_content').css('display', 'none');
	        jQuery('.content_popb_tab_1').css('display', 'block');
	      }

	    $('.pageBgColor').parent().prev().css('background-color',pageBgColor);
	    $('#pbWrapper').attr('style','background-image: url("'+pageBgImage+'"); background-size:cover; background-color:'+pageBgColor+'; padding: '+pagePaddingTop+'% '+pagePaddingRight+'% '+pagePaddingBottom+'% '+pagePaddingLeft+'%  ;  ');
	    /*
	    if (typeof(pageOptions['poCustomFonts']) != 'undefined' ) {
	    	$.each(pageOptions['poCustomFonts'] , function(index,val){
	    		var cfontCountA = 980 + index;
	    		cfontCountA = cfontCountA + index;
                cfontCountB = cfontCountA + 1 + index;
                cfontCountC = cfontCountA + 2 + index;
                cfontCountD = cfontCountA + 3 + index;

	    		$('.customFontsItems').html('');
	    		jQuery('.customFontsItems').append('<li>'+
                	' <h3 class="handleHeader"> '+val['poCfName']+' <span class="dashicons dashicons-trash customfontRemoveBtn" style="float: right;"></span> </h3>'+
                    '<div class="accordContentHolder" style="background: #fff;"> '+
                        '<label>Font Name</label>'+
                        '<input type="text" class="poCfName" value="'+val['poCfName']+'">'+
                        '<br><br><hr><br>'+
                        "<p>Select Font files </p>"+
                                      '<label> Format EOT : </label>'+
                                      '<input id="image_location'+cfontCountA+'" type="text" class="poCfFileUrlEot upload_image_button'+cfontCountA+'"  name="lpp_add_img_'+cfontCountA+'" value="'+val['poCfFileUrlEot']+'"  placeholder="Insert URL here" style="width:40%;" />'+
                                      '<input id="image_location'+cfontCountA+'" type="button" class="upload_bg_btn_imageSlider" data-id="'+cfontCountA+'" value="Select" />'+
                                      "<br><br><br><br><br>"+
                                      '<label> Format OTF : </label>'+
                                      '<input id="image_location'+cfontCountB+'" type="text" class="poCfFileUrlOtf upload_image_button'+cfontCountB+'"  name="lpp_add_img_'+cfontCountB+'" value="'+val['poCfFileUrlOtf']+'"  placeholder="Insert URL here" style="width:40%;" />'+
                                      '<input id="image_location'+cfontCountB+'" type="button" class="upload_bg_btn_imageSlider" data-id="'+cfontCountB+'" value="Select" />'+
                                      "<br><br><br><br><br>"+
                                      '<label> Format WOFF : </label>'+
                                      '<input id="image_location'+cfontCountC+'" type="text" class="poCfFileUrlWoff upload_image_button'+cfontCountC+'"  name="lpp_add_img_'+cfontCountC+'" value="'+val['poCfFileUrlWoff']+'"  placeholder="Insert URL here" style="width:40%;" />'+
                                      '<input id="image_location'+cfontCountC+'" type="button" class="upload_bg_btn_imageSlider" data-id="'+cfontCountC+'" value="Select" />'+
                                      "<br><br><br><br><br>"+
                                      '<label> Format SVG : </label>'+
                                      '<input id="image_location'+cfontCountD+'" type="text" class="poCfFileUrlSvg upload_image_button'+cfontCountD+'"  name="lpp_add_img_'+cfontCountD+'" value="'+val['poCfFileUrlSvg']+'"  placeholder="Insert URL here" style="width:40%;" />'+
                                      '<input id="image_location'+cfontCountD+'" type="button" class="upload_bg_btn_imageSlider" data-id="'+cfontCountD+'" value="Select" />'+
                        "<br><br><br><br><br>"+
					'</div> '+
                '</li>');

                cfontCountA++;
                jQuery( '.customFontsItems' ).accordion( "refresh" );

	    	});
	    }
	    */

	})(jQuery);
}