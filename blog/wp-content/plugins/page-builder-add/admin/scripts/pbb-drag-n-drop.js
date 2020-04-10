( function( $ ) {

// Column Widgets drag/drop
$(function(){

  jQuery(document).ready(function($) {
    
    try {
      // statements
    

    $('.wdt-draggable').draggable({revert: true,cursor: "move",appendTo: "#container",
      helper: function(){
        return $("<div class='widgetDragHelper' style='padding: 15px; background: #95CDF8; border-radius: 100px; z-index:9998;'> <span class='dashicons dashicons-move' style='color:#fff;' title='Move'></span> </div>");
      },
      stop: function(){
        $('.droppableBelowWidget').css('display','none');
        resizeWindowClose();
        $('.closePageOpsBtn').trigger('click');
      },
      start: function(event,ui){
        pageBuilderApp.isDefaultWidget = true;
        currentWidgetType = $(event.target).attr('data-type');
        
        switch(currentWidgetType){
          case 'wigt-WYSIWYG': 
              thisWidgetAttr = 'widgetWYSIWYG';
              thisWidgetAttrValues = {    
                widgetContent: '<h2>Add some text, Image or anything you like :)</h2>'
              };
          break;
          case 'wigt-img': 
              thisWidgetAttr = 'widgetImg';
              var thisWidgetAttrValues = {
                    imgUrl: pluginURL+'/images/dashboard/placeholder.jpg',
                    imgSize: 'medium',
                    imgAlignment: 'center',
                    imgSizeCustomWidth: '',
                    imgSizeCustomHeight: '',
                    imgLightBox: 'false',
                  };
          break;
          case 'wigt-menu':
              thisWidgetAttr = 'widgetMenu';
              var thisWidgetAttrValues = {
                    menuName: 'Select',
                    menuStyle: 'menu-style-1',
                    menuColor: '#333',
                    pbMenuFontFamily: 'Select',
                    pbMenuFontHoverColor: '#eee',
                    pbMenuFontHoverBgColor:'#e3e3e3',
                    pbMenuFontSize: '16',
                  };
          break;
          case 'wigt-btn-gen': 
            thisWidgetAttr = 'widgetButton';
              var thisWidgetAttrValues = {
                btnText: 'Click Now',
                btnLink: '#',
                btnTextColor: '#fff',
                btnFontSize: '14px',
                btnFontSizeTablet:'',
                btnFontSizeMobile:'',
                btnBgColor: '#d9534f',
                btnWidth: '50',
                btnWidthPercent:'20',
                btnWidthUnit: '%',
                btnWidthUnitTablet: '%',
                btnWidthUnitMobile: '%',
                btnWidthPercentTablet:'',
                btnWidthPercentMobile:'',
                btnHeight: '20',
                btnHeightTablet:'',
                btnHeightMobile:'',
                btnHoverBgColor: '#d9534f',
                btnHoverColor: '#fff',
                btnBlankAttr: '_self',
                btnBorderColor: '#d9534f',
                btnBorderWidth: '0',
                btnBorderRadius: '5',
                btnButtonAlignment: 'center',
                btnButtonFontFamily: 'sans',
              };
          break;

          case 'wigt-pb-form':
            thisWidgetAttr = 'widgetSubscribeForm';
              thisWidgetAttrValues = {
                pbFormID: 'ulpb_form'+Math.floor((Math.random() * 20000) + 100),
                formLayout: 'stacked',
                showNameField: 'block',
                successAction:'showMessage',
                successURL:'',
                successMessage:'Thanks for subscribing! Please check your email for further instructions.',
                formBtnText:'Subscribe',
                formBtnHeight:'20',
                formBtnWidth:'40',
                formBtnBgColor:'#d9534f',
                formBtnColor:'#fff',
                formBtnHoverBgColor:'#d9534f',
                formBtnHoverTextColor:'',
                formBtnFontSize:'16',
                formBtnBorderWidth:'0',
                formBtnBorderColor:'#d9534f',
                formBtnBorderRadius:'5',
                formBtnFontFamily:'select',
                formSuccessMessageColor: '#333',
                formDataSaveType: 'database',
                formDataMailChimpApi:'',
                formDataMailChimpListId:'',
              };
          break;

          case 'wigt-video': 
            thisWidgetAttr = 'widgetVideo';
              thisWidgetAttrValues = {    
                videoWebM: '',
                videoMpfour: '',
                videoThumb: '',
                vidAutoPlay: 'no',
                vidLoop: 'no',
                vidControls: 'controls'
              };
          break;
          case 'wigt-pb-postSlider': 
            thisWidgetAttr = 'widgetPBPostsSlider';
              thisWidgetAttrValues = {    
                psAutoplay: 'false',
                psSlideDelay: '1',
                psSlideLoop: 'true',
                psSlideTransition: 'fade',
                psPostsNumber: '10',
                psDots: 'true',
                psArrows: 'true',
                psFtrImage: 'initial',
                psFtrImageSize: 'medium',
                psExcerpt: 'initial',
                psReadMore: 'none',
                psMoreLinkText: 'Read More',
                psHeadingSize: '24',
                psTextAlignment: 'center',
                psBgColor: '#55acef',
                psTxtColor: '#fff',
                psHeadingTxtColor: '#fff',
                psPostType: 'post',
                psPostsOrderBy: 'date',
                psPostsOrder: 'Descending',
                psPostsFilterBy: 'none',
                psFilterValue: ' '
              };
          break;
          case 'wigt-pb-icons': 
            thisWidgetAttr = 'widgetIcons';
              thisWidgetAttrValues = {    
                pbSelectedIcon: 'fa fa-fonticons',
                pbIcStyle: 'none',
                pbIconSize: '85',
                pbIconRotation: '0',
                pbIconColor: '#f0ad4e',
                pbIconLink:'',
                pbIcBgC:'',
                pbIcBC:'',
                pbIcBW:'',
                pbIcBR:'',
                pbIcSHP:'',
                pbIcSVP:'',
                pbIcSDB:'',
                pbIcSC:'rgba(60, 60, 60, 0.44)',
                pbIcHC:'',
                pbIcHBgC:'',
                pbIcHAn:'',
              };
          break;
          case 'wigt-pb-counter':
            thisWidgetAttr = 'widgetCounter';
              thisWidgetAttrValues = {
                pbCounterID: 'ulpb_counter'+Math.floor((Math.random() * 20000) + 100),
                counterStartingNumber: '0',
                counterEndingNumber: '100',
                counterNumberPrefix: '',
                counterNumberSuffix: '',
                counterAnimationTime: '1000',
                counterTitle: 'Cool Counter',
                counterTextColor: '#333',
                counterTitleColor: '#333',
                counterNumberFontSize: '36',
                counterTitleFontSize: '34',
              };
          break;
          case 'wigt-pb-audio': 
            thisWidgetAttr = 'widgetAudio';
              thisWidgetAttrValues = {    
                audioOgg: '',
                audioMpThree: '',
                audioAutoPlay: 'no',
                audioLoop: 'no',
                audioControls: 'controls'
              };
          break;
          case 'wigt-pb-cards': 
            thisWidgetAttr = 'widgetCard';
              thisWidgetAttrValues = {    
                pbSelectedCardIcon: 'fa-archive',
                pbCardIconSize: '55',
                pbCardIconRotation: '0',
                pbCardIconColor: '#545e77',
                pbCardTitleColor: '#545e77',
                pbCardTitleSize: '24',
                pbCardTitleSizeTablet: '',
                pbCardTitleSizeMobile: '',
                pbCardDescColor: '#545e77',
                pbCardDescSize: '16',
                pbCardDescSizeTablet:'',
                pbCardDescSizeMobile:'',
                pbCardTitle: 'This is the heading',
                pbCardDesc: 'This is the description',
              };
          break;
          case 'wigt-pb-testimonial': 
            thisWidgetAttr = 'widgetTestimonial';
              thisWidgetAttrValues = {    
                tsAuthorName: 'John Doe',
                tsJob: 'CEO',
                tsCompanyName: 'Example Company',
                tsTestimonial: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                tsUserImg: '',
                tsImageShape: '0',
                tsIconColor: '#333',
                tsIconSize: '45',
                tsTextColor: '#333',
                tsTextSize: '14',
                tsTestimonialColor: '#333',
                tsTestimonialSize:'18',
                tsTextAlignment: 'center',
              };
          break;
          case 'wigt-pb-shortcode': 
            thisWidgetAttr = 'widgetShortCode';
              thisWidgetAttrValues = {    
                shortCodeInput: '<h2> Add your shortcode for widget to render it. <h2>',
              };
          break;
          case 'wigt-pb-countdown': 
            thisWidgetAttr = 'widgetCowntdown';
              thisWidgetAttrValues = {
                pbCountDownDate: '',
                pbCountDownHour: '',
                pbCountDownMinute: '',
                pbCountDownLabel: '',
                pbCountDownColor: '#333',
                pbCountDownLabelColor: '#333',
                pbCountDownTextSize: '21',
                pbCountDownTextSizeTablet:'',
                pbCountDownTextSizeMobile:'',
                pbCountDownLabelTextSize: '18',
                pbCountDownLabelTextSizeTablet:'',
                pbCountDownLabelTextSizeMobile:'',
                pbCountDownLabelFontFamily:'',
                pbCountDownNumberFontFamily:'',
              };
          break;
          case 'wigt-pb-imageSlider':
            thisWidgetAttr = 'widgetImageSlider';
              thisWidgetAttrValues = {
                pbSliderImagesURL: {
                  0: pluginURL+'/images/dashboard/placeholder.jpg',
                  1: pluginURL+'/images/dashboard/placeholder.jpg'
                },
                pbSliderContent:{
                  0: {
                    imageSlideHeading: 'Enter Headline',
                    imageSlideDesc: 'Enter Some Text Here.',
                    imageSlideButtonText: 'A Button',
                    imageSlideButtonURL: '#'
                  },
                  1: {
                    imageSlideHeading: 'Enter Headline',
                    imageSlideDesc: 'Enter Some Text Here.',
                    imageSlideButtonText: 'A Button',
                    imageSlideButtonURL: '#'
                  }
                },
                slideHeadingStyles: {
                  slideHeadingColor: '#333',
                  slideHeadingSize: '45',
                  slideHeadingSizeTablet: '',
                  slideHeadingSizeMobile: '',
                  slideHeadingLetterSpacing: '',
                  slideHeadingLetterSpacingTablet:'',
                  slideHeadingLetterSpacingMobile:'',
                  slideHeadingLineHeight:'',
                  slideHeadingLineHeightTablet:'',
                  slideHeadingLineHeightMobile:'',
                  slideHeadingFontFamily: 'Arial',
                  slideHeadingBold: 'false',
                  slideHeadingItalic: 'false',
                  slideHeadingUnderlined: 'false'
                },
                slideDescStyles:{
                  slideDescColor: '#333',
                  slideDescSize: '18',
                  slideDescSizeTablet:'',
                  slideDescSizeMobile:'',
                  slideDescLetterSpacing: '',
                  slideDescLetterSpacingTablet:'',
                  slideDescLetterSpacingMobile:'',
                  slideDescLineHeight:'',
                  slideDescLineHeightTablet:'',
                  slideDescLineHeightMobile:'',
                  slideDescFontFamily: 'Arial',
                  slideDescBold: 'false',
                  slideDescItalic: 'false',
                  slideDescUnderlined: 'false'
                },
                slideButtonStyles: {
                  slideButtonBtnHeight: '15',
                  slideButtonBtnHeightTablet:'',
                  slideButtonBtnHeightMobile:'',
                  slideButtonBtnWidth: '200',
                  slideButtonBtnWidthTablet:'',
                  slideButtonBtnWidthMobile:'',
                  slideButtonBtnBgColor:'rgba(255,255,255,0)',
                  slideButtonBtnColor:'#333',
                  slideButtonBtnHoverBgColor:'#d9534f',
                  slideButtonBtnHoverTextColor:'#d9534f',
                  slideButtonBtnFontSize:'21',
                  slideButtonBtnFontSizeTablet:'',
                  slideButtonBtnFontSizeMobile:'',
                  slideButtonBtnFontFamily: 'Arial',
                  slideButtonBtnFontLetterSpacing: '',
                  slideButtonBtnFontLetterSpacingTablet:'',
                  slideButtonBtnFontLetterSpacingMobile:'',
                  slideButtonBtnBorderWidth:'2',
                  slideButtonBtnBorderColor:'#333',
                  slideButtonBtnBorderRadius:'5',
                },
                pbSliderHeight: '400',
                pbSliderHeightTablet:'',
                pbSliderHeightMobile:'',
                pbSliderHeightUnit: 'px',
                pbSliderHeightUnitTablet:'',
                pbSliderHeightUnitMobile:'',
                pbSliderContentBgColor: 'transparent',
                pbSliderAuto: 'true',
                pbSliderDelay: '5000',
                pbSliderPager: 'true',
                pbSliderNav: 'true',
                pbSliderRandom: 'false',
                pbSliderPause: 'false',
              };
          break;
          case 'wigt-pb-progressBar': 
            thisWidgetAttr = 'widgetProgressBar';
              thisWidgetAttrValues = {    
                pbProgressBarTitle: 'Progress Bar',
                pbProgressBarPrecentage: '65',
                pbProgressBarDisplayPrecentage: '',
                pbProgressBarText: 'Complete',
                pbProgressBarTitleColor: '#333',
                pbProgressBarTextColor: '#fff',
                pbProgressBarColor: '#434264',
                pbProgressBarBgColor: '#e3e3e3',
                pbProgressBarTitleSize: '18',
                pbProgressBarHeight: '25',
                pbProgressBarTextSize: '15',
                pbProgressBarTextFontFamily:'Select'
              };
          break;
          case 'wigt-pb-pricing': 
            thisWidgetAttr = 'widgetPricing';
              thisWidgetAttrValues = {    
                pbPricingHeaderText: 'Standard License',
                pbPricingContent: '<p style="text-align: center;"><span style="font-family: helvetica, arial, sans-serif; font-size: 36pt;"><span style="color: #808080;">$50</span> </span></p> <p style="text-align: center;"><span style="color: #808080; font-size: 14pt;">Conference Kit</span></p> <p style="text-align: center;"><span style="color: #808080; font-size: 14pt;">Conference Break</span></p> <p style="text-align: center;"><span style="color: #808080; font-size: 14pt;">Lunch</span></p> <p style="text-align: center;"><span style="color: #808080; font-size: 14pt;">All Seasons</span></p>',
                pbPricingHeaderTextColor: '#333',
                pbPricingHeaderBgColor: '#fff',
                pbPricingHeaderTextSize:'28',
                pbPricingBorderWidth: '2',
                pbPricingBorderColor: '#e3e3e3',
                pbPricingButtonSectionBgColor: '#fff',
                pricingbtnText: 'Purchase',
                pricingbtnLink: '#',
                pricingbtnTextColor: '#fff',
                pricingbtnFontSize: '18',
                pricingbtnBgColor: '#d9534f',
                pricingbtnWidth: '40',
                pricingbtnHeight: '15',
                pricingbtnHoverBgColor: '#222',
                pricingbtnBlankAttr: '_blank',
                pricingbtnBorderColor: '',
                pricingbtnBorderWidth: '0',
                pricingbtnBorderRadius: '5',
                pricingbtnButtonAlignment: 'center',
              };
          break;

          case 'wigt-pb-imgCarousel': 
            thisWidgetAttr = 'widgetImgCarousel';
              thisWidgetAttrValues = {    
                pbImgCarouselAutoplay: 'false',
                pbImgCarouselDelay: '1',
                imgCarouselSlideLoop: 'true',
                imgCarouselSlideTransition: 'fade',
                imgCarouselPagination: 'false',
                pbImgCarouselNav: 'true',
                imgCarouselSlidesURL: {
                  0: pluginURL+'/images/dashboard/placeholder.jpg',
                  1: pluginURL+'/images/dashboard/placeholder.jpg'
                },
              };
          break;

          case 'wigt-pb-wooCommerceProducts': 
            thisWidgetAttr = 'widgetWooPorducts';
              thisWidgetAttrValues = {    
                wooProductsColumn:'2',
                wooProductsCount: '6',
                wooProductsCategories: '',
                wooProductsTags:'',
                wooProductsOrderBy:'date',
                wooProductsOrder:'asc',
              };
          break;
              
          case 'wigt-pb-spacer': 
            thisWidgetAttr = 'widgetVerticalSpace';
              thisWidgetAttrValues = {    
                widgetVerticalSpaceValue:'50',
                widgetVerticalSpaceValueTablet:'',
                widgetVerticalSpaceValueMobile:''
              };
          break;
          case 'wigt-pb-break': 
            thisWidgetAttr = 'widgetBreaker';
              thisWidgetAttrValues = {    
                widgetBreakerStyle:'solid',
                widgetBreakerWeight:'5',
                widgetBreakerColor:'#3a3a3a',
                widgetBreakerWidth:'50',
                widgetBreakerAlignment:'center',
                widgetBreakerSpacing:'15',
              };
          break;
          case 'wigt-pb-anchor': 
            thisWidgetAttr = 'widgetAnchor';
              thisWidgetAttrValues = {
                wdtanchorid:'',
              };
          break;
          case 'wigt-pb-iconList': 
            thisWidgetAttr = 'widgetIconList';
              thisWidgetAttrValues = {    
                iconListComplete:{
                  0: {
                    iconListItemText:' List Item 1',
                    iconListItemIcon: 'fa-check',
                    iconListItemLink: '',
                    iconListItemLinkOpen: '_blank',
                  },
                  1: {
                    iconListItemText:' List Item 2',
                    iconListItemIcon: 'fa-map-marker',
                    iconListItemLink: '',
                    iconListItemLinkOpen: '_blank',
                  },
                  2: {
                    iconListItemText:' List Item 3',
                    iconListItemIcon: 'fa-paper-plane',
                    iconListItemLink: '',
                    iconListItemLinkOpen: '_blank',
                  },
                },
                iconListLineHeight:'25',
                iconListAlignment:'left',
                iconListIconSize:'14',
                iconListIconColor: '#1e73be',
                iconListTextSize:'18',
                iconListTextIndent:'15',
                iconListTextColor:'#1e73be',
                iconListTextFontFamily: 'Arial',
                iconListItemLinkOpen:'_blank'
              };
          break;
          case 'wigt-pb-formBuilder': 
            thisWidgetAttr = 'widgetFormBuilder';
              thisWidgetAttrValues = {    
                widgetPbFbFormFeilds:{
                 0:{
                    fbFieldType: 'text',
                    thisFieldOptions: {
                      fbFieldLabel: 'Name',
                      fbFieldPlaceHolder: 'Name',
                      fbFieldRequired: 'false',
                      fbFieldWidth: '100',
                      multiOptionFieldValues:'',
                      fbtextareaRows: '5'
                    }
                  },
                  1:{
                    fbFieldType: 'email',
                    thisFieldOptions: {
                      fbFieldLabel: 'Email',
                      fbFieldPlaceHolder: 'Email',
                      fbFieldRequired: 'true',
                      fbFieldWidth: '100',
                      multiOptionFieldValues:'',
                      fbtextareaRows: '5'
                    }
                  },
                  2:{
                    fbFieldType: 'textarea',
                    thisFieldOptions: {
                      fbFieldLabel: 'Message',
                      fbFieldPlaceHolder: 'Your Message',
                      fbFieldRequired: 'true',
                      fbFieldWidth: '50',
                      multiOptionFieldValues:'',
                      fbtextareaRows: '5',
                      displayFieldsInline:'inline-block'
                    },
                  },
                },
                widgetPbFbFormFeildOptions: {
                  formBuilderFieldSize: 'medium',
                  formBuilderFieldLabelDisplay: 'unset',
                  formBuilderFieldBgColor: '#fff',
                  formBuilderFieldColor: '#333',
                  formBuilderFieldBorderColor: '#dddddd',
                  formBuilderFieldBorderWidth: '1',
                  formBuilderFieldBorderRadius: '3',
                  formBuilderLabelSize: '18',
                  formBuilderLabelColor:'#333'
                },
                widgetPbFbFormSubmitOptions:{
                  formBuilderBtnText:'Subscribe',
                  formBuilderBtnSize:'medium',
                  formBuilderBtnWidth: '100',
                  formBuilderBtnBgColor:'#d9534f',
                  formBuilderBtnColor:'#fff',
                  formBuilderBtnHoverBgColor:'#d9534f',
                  formBuilderBtnHoverTextColor:'#d9534f',
                  formBuilderBtnFontSize:'21',
                  formBuilderBtnBorderWidth:'0',
                  formBuilderBtnBorderColor:'#d9534f',
                  formBuilderBtnBorderRadius:'3',
                  formBuilderBtnAlignment:'left',
                },
                widgetPbFbFormEmailOptions:{
                  formEmailformName: 'PluginOps Form',
                  formEmailTo: admEMail,
                  formEmailSubject:'PluginOps New Submission',
                  formEmailFromEmail:'formBuilder@pluginops.com',
                  formEmailName: 'PluginOps',
                  formEmailFormat:'plain',
                  formSuccessAction:'',
                  formSuccessActionURL:'',
                  formSuccessMessage:'The form was sent successfully!',
                },
                widgetPbFbFormMailChimp: {
                  formBuilderEnableMailChimp: '',
                  formBuilderMCAccountName: '',
                  formBuilderMCApiKey: ''
                }
              };
          break;
          case 'wigt-pb-text': 
            thisWidgetAttr = 'widgetText';
              thisWidgetAttrValues = {
                widgetTextContent: 'Enter your content here.',
                widgetTextAlignment:'left',
                widgetTextTag:'h2',
                widgetTextColor:'#333',
                widgetTextSize:'36',
                widgetTextFamily:'lato',
                widgetTextWeight:'200',
                widgetTextTransform:'',
                widgetTextLineHeight:'',
                widgetTextSpacing: '',
                widgetTextBold: false,
                widgetTextItalic: false,
                widgetTextUnderlined: false,
                widgetTextSizeTablet:' ',
                widgetTextSizeMobile:' ',
                widgetTextLineHeightTablet:' ',
                widgetTextLineHeightMobile:' ',
                widgetTextSpacingTablet:' ',
                widgetTextSpacingMobile:' '
              };
          break;
          case 'wigt-pb-liveText':
            thisWidgetAttr = 'wLText';
            thisWidgetAttrValues = {
              wltc: '<h2 style="text-align:center;"> Click on this text to start editing. </h2>',
              wltfs: '',
            };
          break;
          case 'wigt-pb-embededVideo': 
            thisWidgetAttr = 'widgetEmbedVideo';
              thisWidgetAttrValues = {
                widgetEvidVideoType: 'youtube',
                widgetEvidVideoLink: 'https://www.youtube.com/watch?v=xbkwUPbRJH8',
                widgetEvidVideoAutoplay:'false',
                widgetEvidVideoPlayerControls:'true',
                widgetEvidVideoTitle:'true',
                widgetEvidVideoSuggested:'false',
                widgetEvidImageOverlay: 'false',
                widgetEvidImageUrl:pluginURL+'/images/dashboard/placeholder.jpg',
                widgetEvidImageIcon:'block',
                widgetEvidImageLightbox:'false',
                widgetEvidImageIconColor:'#333'
              };
          break;
          case 'wigt-pb-testimonialCarousel': 
            thisWidgetAttr = 'widgetTCarousel';
              thisWidgetAttrValues = {    
                tCarOps:{
                  tCarAutoplay: 'false',
                  tCarDelay: '3',
                  tCarSlideLoop: 'true',
                  tCarSlideTransition: 'fade',
                  tCarPagination: 'false',
                  tCarNav: 'true',
                  tNSlides:'2',
                },
                tCarSlides: {
                  0: {
                    tct:'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                    tcn:'John Doe',
                    tcj:'CEO, Tech Corps',
                    tcl:'none',
                    tci:pluginURL+'/images/dashboard/placeholder.jpg'
                  },
                  1: {
                    tct:'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                    tcn:'John Doe',
                    tcj:'CEO, Tech Corps',
                    tcl:'none',
                    tci:pluginURL+'/images/dashboard/placeholder.jpg'
                  },
                },
                tDesignOps:{
                  tcic: '#333',
                  tcis:'45',
                  tcist:'',
                  tcism:'',
                  tcntc:'#333',
                  tcntf:'Select',
                  tcnts:'14',
                  tcntst:'',
                  tcntsm:'',
                  tcttc:'#333',
                  tcttf:'Select',
                  tctts:'18',
                  tcttst:'',
                  tcttsm:'',
                  tcca:'center',
                  tcir:'200px',
                  tcisi:'60',
                }
              };
          break;
          case 'wigt-pb-poOptins': 
            thisWidgetAttr = 'widgetPoOptins';
              thisWidgetAttrValues = {
                widgetOptinId: 'Select',
              };
          break;
          case 'wigt-pb-shareThis': 
            thisWidgetAttr = 'widgetShareThis';
              thisWidgetAttrValues = {
                wdtstId: '',
                wdtstbt:'SBI',
              };
          break;
          case 'wigt-pb-navmenu': 
            thisWidgetAttr = 'widgetNavBuilder';
              thisWidgetAttrValues = {
                navItems: {
                  0: {
                    cnilab : 'Menu Page 0',
                    cniurl : '#',
                  },
                  1: {
                    cnilab : 'Menu Page 1',
                    cniurl : '#',
                  },
                  2: {
                    cnilab : 'Menu Page 2',
                    cniurl : '#',
                  },
                  3: {
                    cnilab : 'Menu Page 3',
                    cniurl : '#',
                  },
                  4: {
                    cnilab : 'Menu Page 4',
                    cniurl : '#',
                  },
                },
                navStyle: {
                  cnsfc: '#333',
                  cnsfhc: 'rgb(149, 149, 149)',
                  cnsbc: 'rgba(51, 51, 51, 0)',
                  cnshbc: 'rgba(51, 51, 51, 0)',
                  cnsnic: '#333',
                  cnsfs: '16',
                  cnsfst: '',
                  cnsfsm: '',
                  cnslg: '5',
                  cnslh: '8',
                  cnsff: 'Select',
                  cnslourl: '',
                  cnslos: 'Medium',
                  cnslayout: 'Horizontal',
                }
              };
          break;
          case 'wigt-pb-gallery':
            thisWidgetAttr = 'widgetIGallery';
              thisWidgetAttrValues = {
                gallItems: {
                  0: {
                    gur: pluginURL+'/images/dashboard/placeholder.jpg',
                    gti: '',
                    gca: '',
                  },
                  1: {
                    gur: pluginURL+'/images/dashboard/placeholder.jpg',
                    gti: '',
                    gca: '',
                  },
                  2: {
                    gur: pluginURL+'/images/dashboard/placeholder.jpg',
                    gti: '',
                    gca: '',
                  },
                },
                gallStyles:{
                  wgType: 'grid',
                  wgGC:'33',
                  wgGCT: '',
                  wgGCM: '',
                  wgGCG:'',
                  wgISD:'large',
                  wgICW:'',
                  wgICWT:'',
                  wgICWM:'',
                  wgICH:'',
                  wgICHT:'',
                  wgICHM:'',
                }
              };
          break;
          case 'wigt-pb-accordion':
            thisWidgetAttr = 'widgetAccordion';
              thisWidgetAttrValues = {
                accordionItems: {
                  0: {
                    accTitle: 'Title Accordion 1',
                    accContent: 'Enter content for accordion here.',
                  },
                  1: {
                    accTitle: 'Title Accordion 2',
                    accContent: 'Enter content for accordion here.',
                  },
                  2: {
                    accTitle: 'Title Accordion 3',
                    accContent: 'Enter content for accordion here.',
                  },
                },
                accordionIcon:{
                  acciClosed:'fas fa-angle-down',
                  acciOpen:'fas fa-angle-up',
                  acciAlign:'left',
                  acciColor:'#333',
                  acciAColor:'#E3E3E3',
                  acciGap:'10',
                },
                accordionSettings:{
                  accoHeight:'content',
                  accoActive:'true',
                  accocborc:'rgb(179, 179, 179)',
                  accocbort:'solid',
                },
                accordionTitle:{
                  acctbg:'#fff',
                  acctabg:'',
                  acctc:'#333',
                  acctac:'#E3E3E3',
                  hgap:'20',
                  vgap:'15',
                  borwt:'1',
                  borwb:'1',
                  borwl:'2',
                  borwr:'2',
                  typography:{
                    ffam:'Arial',
                    fsize:'24',
                    fsizeT:'',
                    fsizeM:'',
                    fsizeu:'px',
                    fsizeuT:'',
                    fsizeuM:'',
                    fwei:'400',
                    ftrans:'',
                    fstyl:'',
                    fdeco:'',
                    flinh:'',
                    flinhT:'',
                    flinhM:'',
                    fletsp:'',
                    fletspT:'',
                    fletspM:'',
                  },
                },
                accordionContent:{
                  acccbg:'',
                  acccc:'',
                  hgap:'',
                  vgap:'',
                  borwt:'1',
                  borwb:'1',
                  borwl:'2',
                  borwr:'2',
                  typography:{
                    ffam:'Arial',
                    fsize:'',
                    fsizeT:'',
                    fsizeM:'',
                    fsizeu:'px',
                    fsizeuT:'',
                    fsizeuM:'',
                    fwei:'',
                    ftrans:'',
                    fstyl:'',
                    fdeco:'',
                    flinh:'',
                    flinhT:'',
                    flinhM:'',
                    fletsp:'',
                    fletspT:'',
                    fletspM:'',
                  },
                }
              };
          break;
          case 'wigt-pb-tabs':
            thisWidgetAttr = 'widgetTabs';
              thisWidgetAttrValues = {
                tabItems: {
                  0: {
                    title: 'Title Tab 1',
                    icon:'',
                    content: 'Enter content for tab here. 1 ',
                  },
                  1: {
                    title: 'Title Tab 2',
                    icon:'',
                    content: 'Enter content for tab here. 2',
                  },
                  2: {
                    title: 'Title Tab 3',
                    icon:'',
                    content: 'Enter content for tab here. 3',
                  },
                },
                tabIcon:{
                  acciPos:'before',
                  acciGap:'10',
                },
                tabSettings:{
                  accoHeight:'content',
                  accoActive:'true',
                  accocborc:'rgb(179, 179, 179)',
                  accocbort:'solid',
                },
                tabTitle:{
                  acctbg:'#fff',
                  acctabg:'',
                  acctc:'#333',
                  acctac:'#E3E3E3',
                  hgap:'10',
                  vgap:'10',
                  borwt:'1',
                  borwb:'1',
                  borwl:'2',
                  borwr:'2',
                  typography:{
                    ffam:'Arial',
                    fsize:'24',
                    fsizeT:'',
                    fsizeM:'',
                    fsizeu:'px',
                    fsizeuT:'',
                    fsizeuM:'',
                    fwei:'400',
                    ftrans:'',
                    fstyl:'',
                    fdeco:'',
                    flinh:'',
                    flinhT:'',
                    flinhM:'',
                    fletsp:'',
                    fletspT:'',
                    fletspM:'',
                  },
                },
                tabContent:{
                  acccbg:'',
                  acccc:'',
                  hgap:'5',
                  vgap:'25',
                  borwt:'1',
                  borwb:'1',
                  borwl:'2',
                  borwr:'2',
                  typography:{
                    ffam:'Arial',
                    fsize:'17',
                    fsizeT:'',
                    fsizeM:'',
                    fsizeu:'px',
                    fsizeuT:'',
                    fsizeuM:'',
                    fwei:'',
                    ftrans:'',
                    fstyl:'',
                    fdeco:'',
                    flinh:'',
                    flinhT:'',
                    flinhM:'',
                    fletsp:'',
                    fletspT:'',
                    fletspM:'',
                  },
                }
              };
          break;
          case 'wigt-pb-popupClose':
            thisWidgetAttr = 'widgetClosePopUp';
              var thisWidgetAttrValues = {
                closeBtnText: 'I don\'t want your Offer',
                closeBtnLink: '#',
                closeBtnTextColor: '#636363',
                closeBtnFontSize: '16px',
                closeBtnFontSizeTablet:'',
                closeBtnFontSizeMobile:'',
                closeBtnBgColor: 'rgba(0,0,0,0)',
                closeBtnWidth: '80',
                closeBtnWidthPercent:'80',
                closeBtnWidthUnit: '%',
                closeBtnWidthUnitTablet: '%',
                closeBtnWidthUnitMobile: '%',
                closeBtnWidthPercentTablet:'',
                closeBtnWidthPercentMobile:'',
                closeBtnHeight: '10',
                closeBtnHeightTablet:'',
                closeBtnHeightMobile:'',
                closeBtnHoverBgColor: 'rgba(0,0,0,0)',
                closeBtnHoverColor: '#333',
                closeBtnBorderColor: 'rgba(0,0,0,0)',
                closeBtnBorderWidth: '0',
                closeBtnBorderRadius: '3',
                closeBtnButtonAlignment: 'center',
                closeBtnButtonFontFamily: 'Homemade Apple',
                closeBtnBold: true,
                closeBtnItalic: true,
                closeBtnUnderlined: true,
              };
          break;
          default : 
            alert('Widget of unknown type');
          break;
        }


        var globalWidgetAttrs = {
          widgetType:currentWidgetType,
          widgetStyling:'/* Custom CSS for widget here. */',
          widgetMtop:'0',
          widgetMleft:'0',
          widgetMbottom:'0',
          widgetMright:'0',
          widgetPtop:'0',
          widgetPleft:'0',
          widgetPbottom:'0',
          widgetPright:'0',
          widgetBgColor: 'transparent',
          widgetAnimation: 'none',
          widgetBorderWidth: '',
          widgetBorderStyle:'',
          widgetBorderColor:'',
          widgetBoxShadowH: '',
          widgetBoxShadowV: '',
          widgetBoxShadowBlur: '',
          widgetBoxShadowColor: '',
          [thisWidgetAttr] : thisWidgetAttrValues
        };



        globalWidgetAttrs['widgetType'] = currentWidgetType;
        $('.draggedWidgetAttributes').val(JSON.stringify(globalWidgetAttrs));

        ColcurrentEditableRowID = jQuery('.ColcurrentEditableRowID').val();
        currentEditableColId = jQuery('.currentEditableColId').val();
          

        $('.column').droppable({
            accept: ".wdt-draggable",
            snap:'.droppableBelowWidget',
            drop: function(event,ui){
              //$(ui.draggable).click();
              // $(".widget-Draggable").trigger("dragstop");
              var curr_droppable = $(this).attr('id');
              $('.widgetDroppedAtIndex').val('');

             $('#'+curr_droppable +' .wdgt-dropped').click();
            },
            over: function(){
              var curr_droppable = $(this).attr('id');
              prevDoppableBgColor = $('#'+curr_droppable).css('background');
              $('#'+curr_droppable).css('background','rgba(224, 241, 255, 0.85)');

            },
            out: function(){
              var curr_droppable = $(this).attr('id');
              $('#'+curr_droppable).css('background',prevDoppableBgColor);
            }

        });


      }

    }); 

    } catch(e) {
      // statements
      console.log(e);
    }

  });

  

});

  $('.add-widgets').click(function(){
    //$('.edit_form').append('<div class="wdt-droppable" data-dropabble="dropable'+counter+'">Drop a widget here</div>');

    pageBuilderApp.widgetList.add({
          widgetType:'wigt-WYSIWYG',
          widgetMtop:'0',
          widgetMleft:'0',
          widgetMbottom:'0',
          widgetMright:'0',
    });



    $('.wdt-droppable').droppable({
      accept: ".widget",
      drop: function(event,ui){
        var type = ui.draggable.data('type');
        var curr_droppable = $(this).attr('data-widgetid');
        //alert(curr_droppable);
        $('input[data-widgetType-id="'+curr_droppable+'"]').val(type);
        switch(type){
          case 'wigt-WYSIWYG': var texta = "HTML Editor"; break;
          case 'wigt-img': var texta = "Image Widget"; break;
          case 'wigt-menu': var texta = "Menu Widget"; break;
          case 'wigt-btn-gen': var texta = "Button Generator Extension"; break;
          default : var texta = 'No widget or extension'; break;
        }


        $('.widget-area-'+curr_droppable).html(texta+ ' is selected <br> To edit click the green button below. <br> To change widget just drop any other widget here.');

        $('.editWidget-'+curr_droppable).trigger('click');

        //console.log('input[data-widgetType-id="'+curr_droppable+'"]');
      }
    });

  });


}( jQuery ) );