( function( $ ) {
    pageBuilderApp.Row = Backbone.Model.extend({
      defaults:{
        rowID: 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100),
        rowHeight: 100,
        rowHeightUnit:'px',
        rowHeightTablet: '',
        rowHeightUnitTablet:'',
        rowHeightMobile: '',
        rowHeightUnitMobile:'',
        columns: 1,
        rowData: {
          rowCustomClass:'',
          bg_color: '#fff',
          bg_img: '',
          bg_imgT:'',
          bg_imgM:'',
          conType:'',
          conWidth:'1280',
          margin: {
            rowMarginTop: 0,
            rowMarginBottom: 0,
            rowMarginLeft: 0,
            rowMarginRight: 0,
          },
          marginTablet:{
            rMTT:'',
            rMBT:'',
            rMLT:'',
            rMRT:'',
          },
          marginMobile:{
            rMTM:'',
            rMBM:'',
            rMLM:'',
            rMRM:'',
          },
          padding:{
            rowPaddingTop: 0,
            rowPaddingBottom: 0,
            rowPaddingLeft: 0,
            rowPaddingRight: 0,
          },
          paddingTablet:{
            rPTT:'',
            rPBT:'',
            rPLT:'',
            rPRT:'',
          },
          paddingMobile:{
            rPTM:'',
            rPBM:'',
            rPLM:'',
            rPRM:'',
          },
          video:{
            rowBgVideoEnable: 'false',
            rowBgVideoLoop: 'loop',
            rowVideoMpfour: ' ',
            rowVideoWebM: ' ',
            rowVideoThumb: ' ',
          },
          customStyling: '',
          customJS: ' ',
          rowBackgroundType:'solid',
          rowGradient:{
            rowGradientColorFirst: '#dd9933',
            rowGradientLocationFirst:'55',
            rowGradientColorSecond:'#eeee22',
            rowGradientLocationSecond:'50',
            rowGradientType:'linear',
            rowGradientPosition:'top left',
            rowGradientAngle:'135',
          },
          rowHoverOptions: {
            rowBgColorHover:'',
            rowBackgroundTypeHover:'',
            rowHoverTransitionDuration:'',
            rowGradientHover:{
              rowGradientColorFirstHover: '',
              rowGradientLocationFirstHover:'',
              rowGradientColorSecondHover:'',
              rowGradientLocationSecondHover:'',
              rowGradientTypeHover:'linear',
              rowGradientPositionHover:'top left',
              rowGradientAngleHover:'',
            }
          },
          rowOverlayBackgroundType: '',
          rowOverlayGradient:{
            rowOverlayGradientColorFirst:  '',
            rowOverlayGradientLocationFirst: '',
            rowOverlayGradientColorSecond: '',
            rowOverlayGradientLocationSecond: '',
            rowOverlayGradientType: '',
            rowOverlayGradientPosition: '',
            rowOverlayGradientAngle: '',
          },
          rowHideOnDesktop:'',
          rowHideOnTablet:'',
          rowHideOnMobile:'',
          bgSTop: {
            rbgstType: 'none',
            rbgstColor:'#e3e3e3',
            rbgstWidth:'100',
            rbgstWidtht:'',
            rbgstWidthm:'',
            rbgstHeight:'200',
            rbgstHeightt:'',
            rbgstHeightm:'',
            rbgstFlipped:'none',
            rbgstFront:'back',
          },
          bgSBottom: {
            rbgsbType: 'none',
            rbgsbColor:'#e3e3e3',
            rbgsbWidth:'100',
            rbgsbWidtht:'',
            rbgsbWidthm:'',
            rbgsbHeight:'200',
            rbgsbHeightt:'',
            rbgsbHeightm:'',
            rbgsbFlipped:'none',
            rbgsbFront:'back',
          },
          bgImgOps:{
            pos:'center center',
            posT:'',
            posM:'',
            xPos:'',
            xPosT:'',
            xPosM:'',
            xPosU:'px',
            xPosUT:'px',
            xPosUM:'px',
            yPos:'',
            yPosT:'',
            yPosM:'',
            yPosU:'px',
            yPosUT:'px',
            yPosUM:'px',
            rep:'default',
            repT:'default',
            repM:'default',
            size:'cover',
            sizeT:'',
            sizeM:'',
            cWid:'',
            cWidT:'',
            cWidM:'',
            widU:'px',
            widUT:'px',
            widUM:'px',
            parlxT:'',
            parlxM:'',
          },
        },
      },
      url: "/"
    });


pageBuilderApp.thisModelDefaultColOps = {
  colWidgets: [],
  columnOptions:{
    bg_color: 'transparent',
    margin: {
      columnMarginTop: 0,
      columnMarginBottom: 0,
      columnMarginLeft: 0,
      columnMarginRight: 0,
    },
    padding:{
      columnPaddingTop: 0,
      columnPaddingBottom: 0,
      columnPaddingLeft: 0,
      columnPaddingRight: 0,
    },
    paddingTablet:{
      rPTT:'',
      rPBT:'',
      rPLT:'',
      rPRT:'',
    },
    paddingMobile:{
      rPTM:'',
      rPBM:'',
      rPLM:'',
      rPRM:'',
    },
    marginTablet:{
      rMTT:'',
      rMBT:'',
      rMLT:'',
      rMRT:'',
    },
    marginMobile:{
      rMTM:'',
      rMBM:'',
      rMLM:'',
      rMRM:'',
    },
    width: '',
    widthTablet: '',
    widthMobile: '',
    columnCSS:'/* Add column custom styling here */',
    columnCustomClass:'',
    colHideOnDesktop:'',
    colHideOnTablet:'',
    colHideOnMobile:'',
    colBoxShadow: {
      colBoxShadowH:'',
      colBoxShadowV:'',
      colBoxShadowBlur:'',
      colBoxShadowColor:'',
    },
    colBgImg:'',
    colBgImgT:'',
    colBgImgM:'',
    colBackgroundType:'solid',
    colGradient:{
      colGradientColorFirst: '#dd9933',
      colGradientLocationFirst:'55',
      colGradientColorSecond:'#eeee22',
      colGradientLocationSecond:'50',
      colGradientType:'linear',
      colGradientPosition:'top left',
      colGradientAngle:'135',
    },
    colHoverOptions: {
      colBgColorHover:'',
      colBackgroundTypeHover:'',
      colHoverTransitionDuration:'',
        colGradientHover:{
          colGradientColorFirstHover: '',
          colGradientLocationFirstHover:'',
          colGradientColorSecondHover:'',
          colGradientLocationSecondHover:'',
          colGradientTypeHover:'linear',
          colGradientPositionHover:'top left',
          colGradientAngleHover:'',
        },
    },
    bgImgOps:{
      pos:'center center',
      posT:'',
      posM:'',
      xPos:'',
      xPosT:'',
      xPosM:'',
      xPosU:'px',
      xPosUT:'px',
      xPosUM:'px',
      yPos:'',
      yPosT:'',
      yPosM:'',
      yPosU:'px',
      yPosUT:'px',
      yPosUM:'px',
      rep:'default',
      repT:'default',
      repM:'default',
      size:'cover',
      sizeT:'',
      sizeM:'',
      cWid:'',
      cWidT:'',
      cWidM:'',
      widU:'px',
      widUT:'px',
      widUM:'px',
      parlxT:'',
      parlxM:'',
    },
    colBorder:{
      bwt:'',
      bwb:'',
      bwl:'',
      bwr:'',
      //tablet
      bwtT:'',
      bwbT:'',
      bwlT:'',
      bwrT:'',
      //mobile
      bwtM:'',
      bwbM:'',
      bwlM:'',
      bwrM:'',
      //border radius
      brt:'',
      brb:'',
      brl:'',
      brr:'',
      //tablet
      brtT:'',
      brbT:'',
      brlT:'',
      brrT:'',
      //mobile
      brtM:'',
      brbM:'',
      brlM:'',
      brrM:'',
      //border
      colBorderStyle:'',
      colBorderColor:'',
    }
  }
}


pageBuilderApp.rowModelDefaultOps = {
        rowData: {
          rowCustomClass:'',
          bg_color: '#fff',
          bg_img: '',
          bg_imgT:'',
          bg_imgM:'',
          conType:'',
          conWidth:'1280',
          margin: {
            rowMarginTop: 0,
            rowMarginBottom: 0,
            rowMarginLeft: 0,
            rowMarginRight: 0,
          },
          marginTablet:{
            rMTT:'',
            rMBT:'',
            rMLT:'',
            rMRT:'',
          },
          marginMobile:{
            rMTM:'',
            rMBM:'',
            rMLM:'',
            rMRM:'',
          },
          padding:{
            rowPaddingTop: 0,
            rowPaddingBottom: 0,
            rowPaddingLeft: 0,
            rowPaddingRight: 0,
          },
          paddingTablet:{
            rPTT:'',
            rPBT:'',
            rPLT:'',
            rPRT:'',
          },
          paddingMobile:{
            rPTM:'',
            rPBM:'',
            rPLM:'',
            rPRM:'',
          },
          video:{
            rowBgVideoEnable: 'false',
            rowBgVideoLoop: 'loop',
            rowVideoMpfour: '',
            rowVideoWebM: '',
            rowVideoThumb: '',
          },
          customStyling: '',
          customJS: '',
          rowBackgroundType:'solid',
          rowGradient:{
            rowGradientColorFirst: '#dd9933',
            rowGradientLocationFirst:'55',
            rowGradientColorSecond:'#eeee22',
            rowGradientLocationSecond:'50',
            rowGradientType:'linear',
            rowGradientPosition:'top left',
            rowGradientAngle:'135',
          },
          rowHoverOptions: {
            rowBgColorHover:'',
            rowBackgroundTypeHover:'',
            rowHoverTransitionDuration:'',
            rowGradientHover:{
              rowGradientColorFirstHover: '',
              rowGradientLocationFirstHover:'',
              rowGradientColorSecondHover:'',
              rowGradientLocationSecondHover:'',
              rowGradientTypeHover:'linear',
              rowGradientPositionHover:'top left',
              rowGradientAngleHover:'',
            }
          },
          rowOverlayBackgroundType: '',
          rowOverlayGradient:{
            rowOverlayGradientColorFirst:  '',
            rowOverlayGradientLocationFirst: '',
            rowOverlayGradientColorSecond: '',
            rowOverlayGradientLocationSecond: '',
            rowOverlayGradientType: '',
            rowOverlayGradientPosition: '',
            rowOverlayGradientAngle: '',
          },
          rowHideOnDesktop:'',
          rowHideOnTablet:'',
          rowHideOnMobile:'',
          bgSTop: {
            rbgstType: 'none',
            rbgstColor:'#e3e3e3',
            rbgstWidth:'100',
            rbgstWidtht:'',
            rbgstWidthm:'',
            rbgstHeight:'200',
            rbgstHeightt:'',
            rbgstHeightm:'',
            rbgstFlipped:'none',
            rbgstFront:'back',
          },
          bgSBottom: {
            rbgsbType: 'none',
            rbgsbColor:'#e3e3e3',
            rbgsbWidth:'100',
            rbgsbWidtht:'',
            rbgsbWidthm:'',
            rbgsbHeight:'200',
            rbgsbHeightt:'',
            rbgsbHeightm:'',
            rbgsbFlipped:'none',
            rbgsbFront:'back',
          },
          bgImgOps:{
            pos:'center center',
            posT:'',
            posM:'',
            xPos:'',
            xPosT:'',
            xPosM:'',
            xPosU:'px',
            xPosUT:'px',
            xPosUM:'px',
            yPos:'',
            yPosT:'',
            yPosM:'',
            yPosU:'px',
            yPosUT:'px',
            yPosUM:'px',
            rep:'default',
            repT:'default',
            repM:'default',
            size:'cover',
            sizeT:'',
            sizeM:'',
            cWid:'',
            cWidT:'',
            cWidM:'',
            widU:'px',
            widUT:'px',
            widUM:'px',
            parlxT:'',
            parlxM:'',
          },
        }
}




/*
pageBuilderApp.Column = Backbone.Model.extend({
  defaults: {
      colWidgets: [],
      columnOptions:{
      bg_color: 'transparent',
      margin: {
        columnMarginTop: 0,
        columnMarginBottom: 0,
        columnMarginLeft: 0,
        columnMarginRight: 0,
        },
      padding:{
        columnPaddingTop: 0,
        columnPaddingBottom: 0,
        columnPaddingLeft: 0,
        columnPaddingRight: 0,
      },
      width: '',
      widthTablet: '',
          widthMobile: '',
      }
  }
});
*/

}( jQuery ) );