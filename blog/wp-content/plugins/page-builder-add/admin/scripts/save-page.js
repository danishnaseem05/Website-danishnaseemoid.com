( function( $ ) { 

$('.SavePage').click(function() {

  var setFrontPage = '';
  var loadWpHead = $('.loadWpHead').attr('isChecked');
  var loadWpFooter = $('.loadWpFooter').attr('isChecked');
  var pageSeoName = $('#title').val();
  var pageLink = $('#editable-post-name-full').html();
  var pageSeoDescription = $('.pageSeoDescription').val();
  var pageSeoMetaTags = $('.pageSeoMetaTags').val();
  var pageSeoKeywords = $('.pageSeoKeywords').val();
  var pageBgImage = $('.pageBgImage').val();
  var pageBgColor = $('.pageBgColor').val();
  var pagePaddingTop = $('.pagePaddingTop').val();
  var pagePaddingBottom = $('.pagePaddingBottom').val();
  var pagePaddingLeft = $('.pagePaddingLeft').val();
  var pagePaddingRight = $('.pagePaddingRight').val(); 
  var pageLogoUrl = $('.pageLogoUrl').val();
  var pageFavIconUrl = $('.pageFavIconUrl').val();
  var VariantB_ID = $('.VariantB_ID').val();

  if (pageSeoName == '' || pageSeoName == 'Auto Draft') {
    $('#title').val('PluginOps Page  - '+P_ID);
    pageSeoName = $('#title').val();
  }

  var PbPageStatus = $('.PbPageStatus').val();
  var checkBtnClickedTypePublish = $(this).hasClass('publishBtn');
  if (checkBtnClickedTypePublish == true) {
    PbPageStatus = 'publish';
  }
  var checkBtnClickedTypeDraft = $(this).hasClass('draftBtn');
  if (checkBtnClickedTypeDraft == true) {
    PbPageStatus = 'draft';
  }

  var POcustomCSS = $('.POcustomCSS').val();
  var POcustomJS = $('.POcustomJS').val();

  setFrontPage = "false";
  isChecked = $('.setFrontPage').attr('isChecked');
  if (isChecked == 'true') {
    setFrontPage = "true";
  } else{
    setFrontPage = "false"; 
  }

  if (loadWpHead == 'true') {
    loadWpHead = "true";
  } else{
    loadWpHead = "false"; 
  }

  if (loadWpFooter == 'true') {
    loadWpFooter = "true";
  } else{
    loadWpFooter = "false"; 
  }

  var POPBDefaultsEnable = $('.POPBDefaultsEnable').val();

  var POPB_typefaces =  {
    typefaceHOne:$('.typefaceHOne').val(),
    typefaceHTwo:$('.typefaceHTwo').val(),
    typefaceH3:$('.typefaceH3').val(),
    typefaceH4:$('.typefaceH4').val(),
    typefaceH5:$('.typefaceH5').val(),
    typefaceH6:$('.typefaceH6').val(),
    typefaceParagraph:$('.typefaceParagraph').val(),
    typefaceButton:$('.typefaceButton').val(),
    typefaceAnchorLink:$('.typefaceAnchorLink').val()
  };

  var POPB_typeSizes = {
    typeSizeHOne:$('.typeSizeHOne').val(),
    typeSizeHTwo:$('.typeSizeHTwo').val(),
    typeSizeParagraph:$('.typeSizeParagraph').val(),
    typeSizeButton:$('.typeSizeButton').val(),
    typeSizeAnchorLink:$('.typeSizeAnchorLink').val(),
    typeSizeHOneTablet:$('.typeSizeHOneTablet').val(),
    typeSizeHOneMobile:$('.typeSizeHOneMobile').val(),
    typeSizeHTwoTablet:$('.typeSizeHTwoTablet').val(),
    typeSizeHTwoMobile:$('.typeSizeHTwoMobile').val(),
    typeSizeH3: $('.typeSizeH3').val(),
    typeSizeH3Tablet: $('.typeSizeH3Tablet').val(),
    typeSizeH3Mobile: $('.typeSizeH3Mobile').val(),
    typeSizeH4: $('.typeSizeH4').val(),
    typeSizeH4Tablet: $('.typeSizeH4Tablet').val(),
    typeSizeH4Mobile: $('.typeSizeH4Mobile').val(),
    typeSizeH5: $('.typeSizeH5').val(),
    typeSizeH5Tablet: $('.typeSizeH5Tablet').val(),
    typeSizeH5Mobile: $('.typeSizeH5Mobile').val(),
    typeSizeH6: $('.typeSizeH6').val(),
    typeSizeH6Tablet: $('.typeSizeH6Tablet').val(),
    typeSizeH6Mobile: $('.typeSizeH6Mobile').val(),
    typeSizeParagraphTablet:$('.typeSizeParagraphTablet').val(),
    typeSizeParagraphMobile:$('.typeSizeParagraphMobile').val(),
    typeSizeButtonTablet:$('.typeSizeButtonTablet').val(),
    typeSizeButtonMobile:$('.typeSizeButtonMobile').val(),
    typeSizeAnchorLinkTablet:$('.typeSizeAnchorLinkTablet').val(),
    typeSizeAnchorLinkMobile:$('.typeSizeAnchorLinkMobile').val(),
  };

  var poCustomFonts = [];
  /*
  $('.customFontsItems li').each(function(index){
    var poCfName =  $( this ).children('.accordContentHolder').children('.poCfName').val();
    var thisListValues = {
      poCfName: $.trim( poCfName ),
      poCfFileUrlEot: $( this ).children('.accordContentHolder').children('.poCfFileUrlEot').val(),
      poCfFileUrlOtf: $( this ).children('.accordContentHolder').children('.poCfFileUrlOtf').val(),
      poCfFileUrlWoff: $( this ).children('.accordContentHolder').children('.poCfFileUrlWoff').val(),
      poCfFileUrlSvg: $( this ).children('.accordContentHolder').children('.poCfFileUrlSvg').val(),
    }
    poCustomFonts.push( thisListValues );
  });
  */


  var pageOps = {
    setFrontPage: setFrontPage,
    loadWpHead:loadWpHead,
    loadWpFooter: loadWpFooter,
    pageBgImage: pageBgImage,
    pageBgColor: pageBgColor,
    pageLink: pageLink,
    pagePadding: {
      pagePaddingTop : pagePaddingTop,
      pagePaddingBottom : pagePaddingBottom,
      pagePaddingLeft : pagePaddingLeft,
      pagePaddingRight : pagePaddingRight,
    },
    pagePaddingTablet: {
      pagePaddingTopTablet : $('.pagePaddingTopTablet').val(),
      pagePaddingBottomTablet : $('.pagePaddingBottomTablet').val(),
      pagePaddingLeftTablet : $('.pagePaddingLeftTablet').val(),
      pagePaddingRightTablet : $('.pagePaddingRightTablet').val(),
    },
    pagePaddingMobile: {
      pagePaddingTopMobile : $('.pagePaddingTopMobile').val(),
      pagePaddingBottomMobile : $('.pagePaddingBottomMobile').val(),
      pagePaddingLeftMobile : $('.pagePaddingLeftMobile').val(),
      pagePaddingRightMobile : $('.pagePaddingRightMobile').val(),
    },
    pageSeoName: pageSeoName,
    pageSeoDescription: pageSeoDescription,
    pageSeoMetaTags:pageSeoMetaTags,
    pageSeoKeywords: pageSeoKeywords,
    pageLogoUrl: pageLogoUrl,
    pageFavIconUrl: pageFavIconUrl,
    pageSeofbOgImage: $('.pageSeofbOgImage').val(),
    MultiVariantTesting: {
      VariantB: $('.VariantB').val(),
      VariantC:$('.VariantC').val(),
      VariantD:$('.VariantD').val(),
    },
    POcustomCSS:POcustomCSS,
    POcustomJS:POcustomJS,
    POPBDefaults: {
      POPBDefaultsEnable : POPBDefaultsEnable,
      POPB_typefaces:POPB_typefaces ,
      POPB_typeSizes: POPB_typeSizes
    },
    bodyBackgroundType:$('.bodyBackgroundType:checked').val(),
    bodyGradient:{
      bodyGradientColorFirst: $('.bodyGradientColorFirst').val(),
      bodyGradientLocationFirst:$('.bodyGradientLocationFirst').val(),
      bodyGradientColorSecond:$('.bodyGradientColorSecond').val(),
      bodyGradientLocationSecond:$('.bodyGradientLocationSecond').val(),
      bodyGradientType:$('.bodyGradientType').val(),
      bodyGradientPosition:$('.bodyGradientPosition').val(),
      bodyGradientAngle:$('.bodyGradientAngle').val(),
    },
    bodyHoverOptions: {
      bodyBgColorHover:$('.bodyBgColorHover').val(),
      bodyBackgroundTypeHover:$('.bodyBackgroundTypeHover:checked').val(),
      bodyHoverTransitionDuration:$('.bodyHoverTransitionDuration').val(),
      bodyGradientHover:{
        bodyGradientColorFirstHover: $('.bodyGradientColorFirstHover').val(),
        bodyGradientLocationFirstHover:$('.bodyGradientLocationFirstHover').val(),
        bodyGradientColorSecondHover:$('.bodyGradientColorSecondHover').val(),
        bodyGradientLocationSecondHover:$('.bodyGradientLocationSecondHover').val(),
        bodyGradientTypeHover:$('.bodyGradientTypeHover').val(),
        bodyGradientPositionHover:$('.bodyGradientPositionHover').val(),
        bodyGradientAngleHover:$('.bodyGradientAngleHover').val(),
      }
    },
    bodyOverlayBackgroundType: $('.bodyOverlayBackgroundType:checked').val(),
    bodyOverlayGradient:{
      bodyOverlayGradientColorFirst: $('.bodyOverlayGradientColorFirst').val(),
      bodyOverlayGradientLocationFirst:$('.bodyOverlayGradientLocationFirst').val(),
      bodyOverlayGradientColorSecond:$('.bodyOverlayGradientColorSecond').val(),
      bodyOverlayGradientLocationSecond:$('.bodyOverlayGradientLocationSecond').val(),
      bodyOverlayGradientType:$('.bodyOverlayGradientType').val(),
      bodyOverlayGradientPosition:$('.bodyOverlayGradientPosition').val(),
      bodyOverlayGradientAngle:$('.bodyOverlayGradientAngle').val(),
    },
    bodyBgOverlayColor:$('.bodyBgOverlayColor').val(),
    bodyBorderType:$('.bodyBorderType').val(),
    bodyBorderWidth:$('.bodyBorderWidth').val(),
    bodyBorderColor:$('.bodyBorderColor').val(),
    bodyBorderRadius:{
      bbrt:$('.bbrt').val(),
      bbrb:$('.bbrb').val(),
      bbrl:$('.bbrl').val(),
      bbrr:$('.bbrr').val(),
    },
    poCustomFonts:poCustomFonts,
  };

  var newPermalinkUrl = siteURLpb+'/'+pageLink;
  $('#sample-permalink a').attr('href',newPermalinkUrl);

  var isEditorEnabled = $('.pb_fullScreenEditorButton');
  if (isEditorEnabled.hasClass('EditorActive')) {
    displayEditor = 'block';
  } else{
    displayEditor = 'none';
  }




  renderPageOps(pageOps, PbPageStatus);

  $('#pbWrapper').css( 'display' , displayEditor );


  //console.log($('#container').parent('#tab1'));
  $('.pb_loader_container').slideDown(200);
  var PageStatus = pageBuilderApp.PageBuilderModel.get('pageStatus');
  pageBuilderApp.PageBuilderModel.set( 'pageID', P_ID);
  pageBuilderApp.PageBuilderModel.set( 'pageOptions', pageOps);
  pageBuilderApp.PageBuilderModel.set('pageStatus',PbPageStatus);
  pageBuilderApp.PageBuilderModel.set( 'Rows', pageBuilderApp.rowList.models );

  if (PbPageStatus == 'publish') {
    if (typeof(pageBuilderApp.PageBuilderModel.get('shareOpShown')) != 'undefined') {
      shareOpShownTimes = pageBuilderApp.PageBuilderModel.get('shareOpShown');
      shareOpShownTimes++;
    }else{
      shareOpShownTimes = 1;
    }
    pageBuilderApp.PageBuilderModel.set('shareOpShown',shareOpShownTimes);
  }else{
    pageBuilderApp.PageBuilderModel.set('shareOpShown',0);
  }
  pageBuilderApp.PageBuilderModel.save({ wait:true }).success(function(response){

    setTimeout(function(){
      $('.pb_loader_container').slideUp(200);
      if (PbPageStatus == 'publish' && pageBuilderApp.PageBuilderModel.get('shareOpShown') < 2) {
        $('.popb_post_publish_share').slideDown('slow');
        $('.popb_post_publish_share').css('display','block');
      }
      
      if (PageStatus === 'publish' || PageStatus === 'draft' || PageStatus === 'private') {

      }else{
        //window.location.href = admURL+'post.php?post='+P_ID+'&action=edit'; 
        var pageOptions = pageBuilderApp.PageBuilderModel.get('pageOptions');
        var pageStatus = pageBuilderApp.PageBuilderModel.get('pageStatus');

      
        renderPageOps(pageOptions, pageStatus);
        pageBuilderApp.PgCollectionView.render();

        var currentAttrValue = jQuery('.templatesTabEditor .pluginops-tab_link').attr('href');
 
        jQuery('.pluginops-tabs ' + currentAttrValue).show().siblings().hide();
       
        jQuery('.templatesTabEditor .pluginops-tab_link').parent('li').addClass('pluginops-active').siblings().removeClass('pluginops-active');
          
        $('.pb_fullScreenEditorButton').trigger('click');
      }

      

    }, 1000);
    console.log('Saved');

  }).error(function(response){

    console.log(response);
    
    var result = response;

    if (response['responseText'] != '') {

      jQuery('.popb_safemode_popup').css('display','block');

      jQuery('.confirm_safemode_no').on('click',function(){
        jQuery('.popb_safemode_popup').css('display','none');
        location.reload();
      });

      popb_errorLog.errorMsg = response['responseText'];
      popb_errorLog.errorURL = 'na';


      jQuery('.confirm_safemode_yes').on('click',function(){

        var result = " ";
        var form = jQuery('.insertTemplateForm');
        var errordata = 

        jQuery.ajax({
                  url: admURL+'/admin-ajax.php?action=popb_enable_safe_mode&POPB_nonce='+shortCodeRenderWidgetNO,
                  method: 'post',
                  data:{
                    errorMsg : popb_errorLog.errorMsg,
                    errorURL : popb_errorLog.errorURL,
                  },
                  success: function(result){
                      location.reload();
                  }
        });

      });

    }

    if (response['responseText'] == 'Invalid Nonce') {
      alert('Nonce Expired  - Changes cannot be saved, Please reload the page.');
      $('.pb_loader_container').slideUp(200);
    }else{
      alert('Page Not Saved - Some Error Occurred! ');
      $('.pb_loader_container').slideUp(200);
    }

    

  });

  

});

/*
$(document).ready(function(){

  setInterval(function(){

    var isChagesMade = $('.isChagesMade').val();

    if (isChagesMade == 'true') {
      
      pageBuilderApp.PageBuilderModel.set( 'Rows', pageBuilderApp.rowList.models );
      pageBuilderApp.PageBuilderModel.save({ wait:true }).success(function(response){
        console.log('Saved');
      }).error(function(response){
        console.log('Page Not Saved - Some Error Occurred! ');
      });
    } 

     $('.isChagesMade').val('false');
  }, 15000);

});
*/

}( jQuery ) );