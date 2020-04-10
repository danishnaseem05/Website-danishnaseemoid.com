( function( $ ) {
pageBuilderApp.ULPBPage = Backbone.Model.extend({
      defaults:{
        pageID: P_ID,
        postType: thisPostType,
        pageLink: '',
        pageTitle:'',
        pageStatus:'unpublished',
        pageBuilderVersion: PageBuilder_Version,
        pageOptions: {
          setFrontPage: 0,
          loadWpHead:true,
          loadWpFooter:true,
          pageBgImage: ' ',
          pageBgColor: 'transparent',
          pagePadding: {
            pagePaddingTop : '',
            pagePaddingBottom : '',
            pagePaddingLeft : '',
            pagePaddingRight : '',
          },
          pagePaddingTablet: {
            pagePaddingTopTablet : '',
            pagePaddingBottomTablet : '',
            pagePaddingLeftTablet : '',
            pagePaddingRightTablet : '',
          },
          pagePaddingMobile: {
            pagePaddingTopMobile : '',
            pagePaddingBottomMobile : '',
            pagePaddingLeftMobile : '',
            pagePaddingRightMobile : '',
          },
          pageSeoName: '',
          pageSeoDescription: '',
          pageSeoKeywords: '',
          pageSeoMetaTags:'',
          pageSeofbOgImage: '',
          pageFavIconUrl: '',
          pageLogoUrl: '',
          VariantB_ID: '',
          MultiVariantTesting: {
            VariantB: '',
            VariantC:'',
            VariantD:'',
          },
          POcustomCSS:'',
          POcustomJS:'',
          POPBDefaults: {
            POPBDefaultsEnable : 'false',
            POPB_typefaces: {
              typefaceHOne:'Arial',
              typefaceHTwo:'Arial',
              typefaceH3:'Arial',
              typefaceH4:'Arial',
              typefaceH5:'Arial',
              typefaceH6:'Arial',
              typefaceParagraph:'Arial',
              typefaceButton:'Arial',
              typefaceAnchorLink:'Arial'
            },
            POPB_typeSizes: {
              typeSizeHOne:'45',
              typeSizeHOneTablet:'',
              typeSizeHOneMobile:'',
              typeSizeHTwo:'32',
              typeSizeHTwoTablet:'',
              typeSizeHTwoMobile:'',
              typeSizeH3:'26',
              typeSizeH3Tablet:'',
              typeSizeH3Mobile:'',
              typeSizeH4:'22',
              typeSizeH4Tablet:'',
              typeSizeH4Mobile:'',
              typeSizeH5:'20',
              typeSizeH5Tablet:'',
              typeSizeH5Mobile:'',
              typeSizeH6:'18',
              typeSizeH6Tablet:'',
              typeSizeH6Mobile:'',
              typeSizeParagraph:'15',
              typeSizeParagraphTablet:'',
              typeSizeParagraphMobile:'',
              typeSizeButton:'16',
              typeSizeButtonTablet:'',
              typeSizeButtonMobile:'',
              typeSizeAnchorLink:'15',
              typeSizeAnchorLinkTablet:'',
              typeSizeAnchorLinkMobile:'',
            }
          }
        },
        Rows:{}
      },
      url: URLL
});

pageBuilderApp.ColWidget = Backbone.Model.extend({
      defaults:{
        widgetType:' ',
        widgetStyling:'/* Custom CSS for widget here. */',
        widgetMtop:'0',
        widgetMleft:'0',
        widgetMbottom:'0',
        widgetMright:'0',
        widgetPtop:'0',
        widgetPleft:'0',
        widgetPbottom:'0',
        widgetPright:'0',
          widgetPaddingTablet:{
            rPTT:'',
            rPBT:'',
            rPLT:'',
            rPRT:'',
          },
          widgetPaddingMobile:{
            rPTM:'',
            rPBM:'',
            rPLM:'',
            rPRM:'',
          },
          widgetMarginTablet:{
            rMTT:'',
            rMBT:'',
            rMLT:'',
            rMRT:'',
          },
          widgetMarginMobile:{
            rMTM:'',
            rMBM:'',
            rMLM:'',
            rMRM:'',
          },
        widgetBgColor: 'transparent',
        widgetAnimation: 'none',
        widgetBorderWidth: '',
        widgetBorderStyle:'',
        widgetBorderColor:'',
        widgetBoxShadowH: '',
        widgetBoxShadowV: '',
        widgetBoxShadowBlur: '',
        widgetBoxShadowColor: '',
        widgetIsInline:false,
        widgetIsInlineTablet:'',
        widgetIsInlineMobile:'',
        widgetCustomClass: '',
        widgBgImg:'',
        widgBackgroundType:'solid',
        widgGradient:{
          widgGradientColorFirst: '#dd9933',
          widgGradientLocationFirst:'55',
          widgGradientColorSecond:'#eeee22',
          widgGradientLocationSecond:'50',
          widgGradientType:'linear',
          widgGradientPosition:'top left',
          widgGradientAngle:'135',
        },
        widgHoverOptions: {
          widgBgColorHover:'',
          widgBackgroundTypeHover:'',
          widgHoverTransitionDuration:'',
          widgGradientHover:{
            widgGradientColorFirstHover: '',
            widgGradientLocationFirstHover:'',
            widgGradientColorSecondHover:'',
            widgGradientLocationSecondHover:'',
            widgGradientTypeHover:'linear',
            widgGradientPositionHover:'top left',
            widgGradientAngleHover:'',
          }
        },
        widgHideOnDesktop:'',
        widgHideOnTablet:'',
        widgHideOnMobile:'',
      },
      url: '/'
});


pageBuilderApp.RowCollection = Backbone.Collection.extend({
    model:pageBuilderApp.Row
});

pageBuilderApp.WidgetCollection = Backbone.Collection.extend({
    model:pageBuilderApp.ColWidget
});


}( jQuery ) );