( function( $ ) { 
  
pageBuilderApp.prevStateOption = false;
const setUpdatedOptsObject = (obj, path, val) => { 
    const keys = path.split('.');
    const lastKey = keys.pop();
    const lastObj = keys.reduce((obj, key) => 
        obj[key] = obj[key] || {}, 
        obj);
    pageBuilderApp.prevStateOption = _.clone(lastObj[lastKey]);
    lastObj[lastKey] = val;
};

function mergeNonsetObjectKeys(source, target) {
    Object.keys(target).forEach(function (k) {
      if (typeof source[k] === 'undefined') {
        source[k] = target[k];
      }
    });
}

function POPB_strip(html) {
        html = html.replace(/<br>/g, "$br$");
        
        html = html.replace(/<div>/g, "$br$");
        
        html = html.replace(/(?:\r\n|\r|\n)/g, '$br$');
        
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        html = tmp.textContent || tmp.innerText;
        
        html = html.replace(/\$br\$/g, "<br>");
        
        return html;
}

var WidgetDraggedAttr = '';
var isRenderDropLoop = false;
var checkIEUL = false;
pageBuilderApp.RowView = Backbone.View.extend({
    tagName: 'section',
    className: 'row',
    template: _.template($('#item-template').html()),
    events: {
      'click #rowDelete': 'deleteRow',
      'click #rowEdit': 'EditRow',
      'click #rowDuplicate': 'DuplicateRow',
      'click #editRowSave': 'updateRow',
      'click #setGlobalRow': 'setGlobalRow',
      'click #copySectionOps': 'copySectionOps',
      'click #pasteSectionOps': 'pasteSectionOps',
      'click #WidthSave': 'updateWidth',
      'click #RowHeightSave': 'updateRowHeight',
      'click #copyThisRowLS': 'copyThisRowLS',
      'click #pasteThisRowLS': 'pasteThisRowLS',
      'click div.editColumn': 'EditColumn',
      'click #copyColumnOps': 'copyColumnOps',
      'click #pasteColumnOps': 'pasteColumnOps',
      'click #flipColumns': 'flipColumns',
      'click #duplicateColumns': 'duplicateColumns',
      'click #reAddDeletedColumns': 'reAddDeletedColumns',
      'click #deleteColumns': 'deleteColumns',
      'click #editColumnSave': 'updateColumn',
      'click #editColumnSaveWidget': 'updateColumnWidgetTrigger',
      'click .draggableWidgets': 'widgetDrag',
      'click .wdgt-dropped': 'widgetDropped',
      'click .wdgt-colChange': 'widgetColChange',
      'click .wdgt-dragRemove': 'widgetDragRemove',
      'click .widgetDeleteHandle': 'deleteWidget',
      'click .widgetDuplicateHandle': 'duplicateWidget',
      'click .widgetEditHandle' : 'editWidget',
      'click .widgetCopyHandle': 'copyWidgetOps',
      'click .widgetPasteHandle': 'pasteWidgetOps',
      'click .po_widgets' : 'editWidgetTrigger',
      'click .addNewRow' : 'addNewRow',
      'click .addNewGlobalRow' : 'addNewGlobalRow',
      'click .addNewRowBlock' : 'addNewRowBlock',
      'click .setColbtn' : 'setColumnsOfThisModel',
      'click .inlineEditingSaveTrigger': 'widgetInlineEditorSave',
    },
    attributes: function() {
        if(this.model) {
            return {
                RowID: this.model.get('rowID')
            }
        }
        return {}
    },
    initialize: function(){
      _.bindAll(this, 'render','deleteRow','updateRow','EditRow','EditColumn','copyColumnOps','pasteColumnOps','flipColumns','duplicateColumns','reAddDeletedColumns','deleteColumns','updateColumn','updateWidth','DuplicateRow','widgetDrag','widgetDropped','widgetColChange','setGlobalRow','copySectionOps','pasteSectionOps','addNewRow','addNewGlobalRow','addNewRowBlock','setColumnsOfThisModel','widgetInlineEditorSave','updateRowHeight','copyThisRowLS','pasteThisRowLS','deleteWidgetFromList','updateColumnWidgetTrigger','copyWidgetOps','pasteWidgetOps','loadWidgetsInColumn');
    },
    render: function(){
      this.$el.html(this.template(this.model.toJSON() )  );
      var rowCID = this.model.cid;

      if (thisPostType == 'ulpb_global_rows') {
        $('.newRowBtnContainerVisible').remove();
      }
      else{

        var ifIsGlobal = this.model.get('globalRow');
        if (typeof(ifIsGlobal) != 'undefined') {

          if (ifIsGlobal['isGlobalRow'] == true) {

            getGlobalRowDataFromDb(ifIsGlobal['globalRowPid']);

            retrievedGlobalRowAttributes = $('.globalRowRetrievedAttributes').val();
            
            if (retrievedGlobalRowAttributes != '') {
              this.model.set(JSON.parse(retrievedGlobalRowAttributes) );
            }
            

            rowHeight = this.model.get('rowHeight');
            rowHeightUnit = this.model.get('rowHeightUnit');

            if (typeof(rowHeightUnit) == 'undefined' || rowHeightUnit == '') {
              rowHeightUnit = 'px';
            }else{
              rowHeightUnit = this.model.get('rowHeightUnit');
            }

            $('li[data-model-cid="'+rowCID+'"] section').append(
              '<div class="globalRowOverlay" style=" position:absolute; height:100%; z-index:9; width: 100%; left: 0px; right: 0px; "> <br><br><br> '+
                '<h3 style="color:#fff;"> This is a Global Row </h3> <br> <a href="'+admURL+'/post.php?post='+ifIsGlobal['globalRowPid']+'&action=edit"  target="_blank" >You can edit it here </a> '+
              '</div>'
            );

            $('li[data-model-cid="'+rowCID+'"]').mouseenter(function(){
              $('li[data-model-cid="'+rowCID+'"] section .globalRowOverlay').css('display', 'block');
            });
            $('li[data-model-cid="'+rowCID+'"]').mouseleave(function(){
              $('li[data-model-cid="'+rowCID+'"] section .globalRowOverlay').css('display', 'none');
            });

          }
        }
      }

      var rowID = this.model.get('rowID');
      var rowCID = this.model.cid;
      rowColumns = this.model.get('columns');
      rowHeight = this.model.get('rowHeight');
      rowHeightUnit = this.model.get('rowHeightUnit');
      var rowData = this.model.get('rowData');
      var row_bg_img = rowData['bg_img'];
      var row_bg_color = rowData['bg_color'];
      var row_margin = rowData['margin'];
      var row_padding = rowData['padding'];
      var custom_styling = rowData['customStyling'];
      var custom_JS = rowData['customJS'];

      if (typeof(rowHeightUnit) == 'undefined' || rowHeightUnit == '') {
          rowHeightUnit = 'px';
      }else{
          rowHeightUnit = this.model.get('rowHeightUnit');
      }

      var rowHideOnDesktop ="'display':'block'", rowHideOnTablet = "'display':'block'", rowHideOnMobile = "'display':'block'";
      if (typeof(rowData['rowHideOnDesktop']) !== 'undefined' ) {
        if (rowData['rowHideOnDesktop'] == 'hide') {
          rowHideOnDesktop = "display:'none' ,";
        }

        if (rowData['rowHideOnTablet'] == 'hide') {
          rowHideOnTablet = "display:'none' ,";
        }
        if (rowData['rowHideOnMobile'] == 'hide') {
          rowHideOnMobile = "display:'none' ,";
        }
      }

      var currRowDefaultMarginPadding  = ''+
        '<script>'+
        "jQuery('.responsiveBtn').live('click',function(){"+
        " if (jQuery(this).hasClass('rbt-l') ) { "+
        "  jQuery('#"+rowID+"').css({'margin-top':'"+row_margin['rowMarginTop']+"%', 'margin-bottom':'"+row_margin['rowMarginBottom']+"%', 'margin-left':'"+row_margin['rowMarginLeft']+"%', 'margin-right':'"+row_margin['rowMarginRight']+"%', });"+
        "  jQuery('#"+rowID+"').css({'padding-top':'"+row_padding['rowPaddingTop']+"%', 'padding-bottom':'"+row_padding['rowPaddingBottom']+"%', 'padding-left':'"+row_padding['rowPaddingLeft']+"%', 'padding-right':'"+row_padding['rowPaddingRight']+"%', "+rowHideOnDesktop+" });"+
        " }"+
        "});"+
        "var currentVPS = jQuery('.currentViewPortSize').val();"+
        "if ( currentVPS == 'rbt-l' ) { "+
        "  jQuery('#"+rowID+"').css({'margin-top':'"+row_margin['rowMarginTop']+"%', 'margin-bottom':'"+row_margin['rowMarginBottom']+"%', 'margin-left':'"+row_margin['rowMarginLeft']+"%', 'margin-right':'"+row_margin['rowMarginRight']+"%', });"+
        "  jQuery('#"+rowID+"').css({'padding-top':'"+row_padding['rowPaddingTop']+"%', 'padding-bottom':'"+row_padding['rowPaddingBottom']+"%', 'padding-left':'"+row_padding['rowPaddingLeft']+"%', 'padding-right':'"+row_padding['rowPaddingRight']+"%', "+rowHideOnDesktop+" });"+
        "}"+
        " "+
        '</script> ';

      if (typeof(rowData['rowCustomClass']) !== 'undefined' ) {
        $('li[data-model-cid="'+rowCID+'"] section').addClass(rowData['rowCustomClass']);
      }

      if (typeof(this.model.get('rowHeightTablet')) !== 'undefined') {
        rowHeightTablet = this.model.get('rowHeightTablet');
        rowHeightUnitTablet = this.model.get('rowHeightUnitTablet');
        rowHeightMobile = this.model.get('rowHeightMobile');
        rowHeightUnitMobile = this.model.get('rowHeightUnitMobile');
      }else{
        rowHeightTablet ='';
        rowHeightUnitTablet ='';
        rowHeightMobile ='';
        rowHeightUnitMobile ='';
      }


      var currRowMarginTablet = '';
      var currRowMarginMobile = '';
      var currRowMarginMobile = ''+
        '<script>'+
          "jQuery('.responsiveBtn').live('click',function(){"+
          " if (jQuery(this).hasClass('rbt-s') || jQuery(this).hasClass('rbt-m') ) { "+
          "  jQuery('#"+rowID+"').css({'margin':'0 auto', });"+

          "  jQuery('#"+rowID+"').css({'padding':'10px 0', });"+
          " }"+
          "});"+
          "var currentVPS = jQuery('.currentViewPortSize').val();"+
          "if ( currentVPS == 'rbt-s' || currentVPS == 'rbt-m' ) { "+
          "  jQuery('#"+rowID+"').css({'margin':'0 auto', });"+

          "  jQuery('#"+rowID+"').css({'padding':'10px 0', });"+
          "}"+
          " "+
        '</script> ';

      if (typeof(rowData['marginTablet']) !== 'undefined' ) {
        rowMarginTablet = rowData['marginTablet'];
        rowPaddingTablet = rowData['paddingTablet'];

        if (rowMarginTablet['rMTT'] == '') {  rowMarginTablet['rMTT'] = '0';  }
        if (rowMarginTablet['rMBT'] == '') {  rowMarginTablet['rMBT'] = '0';  }
        if (rowMarginTablet['rMLT'] == '') {  rowMarginTablet['rMLT'] = '0';  }
        if (rowMarginTablet['rMRT'] == '') {  rowMarginTablet['rMRT'] = '0';  }

        if (rowPaddingTablet['rPTT'] == '') {  rowPaddingTablet['rPTT'] = '1.5';  }
        if (rowPaddingTablet['rPBT'] == '') {  rowPaddingTablet['rPBT'] = '1.5';  }
        if (rowPaddingTablet['rPLT'] == '') {  rowPaddingTablet['rPLT'] = '1.5';  }
        if (rowPaddingTablet['rPRT'] == '') {  rowPaddingTablet['rPRT'] = '1.5';  }

        var currRowMarginTablet  = ''+
          '<script>'+
          "jQuery('.responsiveBtn').live('click',function(){"+
          " if (jQuery(this).hasClass('rbt-m') ) { "+
          "  jQuery('#"+rowID+"').css({'margin-top':'"+rowMarginTablet['rMTT']+"%', 'margin-bottom':'"+rowMarginTablet['rMBT']+"%', 'margin-left':'"+rowMarginTablet['rMLT']+"%', 'margin-right':'"+rowMarginTablet['rMRT']+"%',  "+rowHideOnTablet+" });"+

          "  jQuery('#"+rowID+"').css({'padding-top':'"+rowPaddingTablet['rPTT']+"%', 'padding-bottom':'"+rowPaddingTablet['rPBT']+"%', 'padding-left':'"+rowPaddingTablet['rPLT']+"%', 'padding-right':'"+rowPaddingTablet['rPRT']+"%', });"+
          " }"+
          "});"+
          "var currentVPS = jQuery('.currentViewPortSize').val();"+
          "if ( currentVPS == 'rbt-m' ) { "+
          "  jQuery('#"+rowID+"').css({'margin-top':'"+rowMarginTablet['rMTT']+"%', 'margin-bottom':'"+rowMarginTablet['rMBT']+"%', 'margin-left':'"+rowMarginTablet['rMLT']+"%', 'margin-right':'"+rowMarginTablet['rMRT']+"%', "+rowHideOnTablet+" });"+

          "  jQuery('#"+rowID+"').css({'padding-top':'"+rowPaddingTablet['rPTT']+"%', 'padding-bottom':'"+rowPaddingTablet['rPBT']+"%', 'padding-left':'"+rowPaddingTablet['rPLT']+"%', 'padding-right':'"+rowPaddingTablet['rPRT']+"%', });"+
          "}"+
          " "+
          '</script> ';
      }

      if (typeof(rowData['marginMobile']) !== 'undefined' ) {
        rowMarginMobile = rowData['marginMobile'];
        rowPaddingMobile = rowData['paddingMobile'];

        if (rowMarginMobile['rMTM'] == '') {  rowMarginMobile['rMTM'] = '0';  }
        if (rowMarginMobile['rMBM'] == '') {  rowMarginMobile['rMBM'] = '0';  }
        if (rowMarginMobile['rMLM'] == '') {  rowMarginMobile['rMLM'] = '0';  }
        if (rowMarginMobile['rMRM'] == '') {  rowMarginMobile['rMRM'] = '0';  }

        if (rowPaddingMobile['rPTM'] == '') {  rowPaddingMobile['rPTM'] = '1.5';  }
        if (rowPaddingMobile['rPBM'] == '') {  rowPaddingMobile['rPBM'] = '1.5';  }
        if (rowPaddingMobile['rPLM'] == '') {  rowPaddingMobile['rPLM'] = '1.5';  }
        if (rowPaddingMobile['rPRM'] == '') {  rowPaddingMobile['rPRM'] = '1.5';  }

        var currRowMarginMobile  = ''+
        '<script>'+
        "jQuery('.responsiveBtn').live('click',function(){"+
        " if (jQuery(this).hasClass('rbt-s') ) { "+
        "  jQuery('#"+rowID+"').css({'margin-top':'"+rowMarginMobile['rMTM']+"%', 'margin-bottom':'"+rowMarginMobile['rMBM']+"%', 'margin-left':'"+rowMarginMobile['rMLM']+"%', 'margin-right':'"+rowMarginMobile['rMRM']+"%', "+rowHideOnMobile+" });"+

        "  jQuery('#"+rowID+"').css({'padding-top':'"+rowPaddingMobile['rPTM']+"%', 'padding-bottom':'"+rowPaddingMobile['rPBM']+"%', 'padding-left':'"+rowPaddingMobile['rPLM']+"%', 'padding-right':'"+rowPaddingMobile['rPRM']+"%', });"+
        " }"+
        "});"+
        "var currentVPS = jQuery('.currentViewPortSize').val();"+
        "if ( currentVPS == 'rbt-s' ) { "+
        "  jQuery('#"+rowID+"').css({'margin-top':'"+rowMarginMobile['rMTM']+"%', 'margin-bottom':'"+rowMarginMobile['rMBM']+"%', 'margin-left':'"+rowMarginMobile['rMLM']+"%', 'margin-right':'"+rowMarginMobile['rMRM']+"%', "+rowHideOnMobile+" });"+

        "  jQuery('#"+rowID+"').css({'padding-top':'"+rowPaddingMobile['rPTM']+"%', 'padding-bottom':'"+rowPaddingMobile['rPBM']+"%', 'padding-left':'"+rowPaddingMobile['rPLM']+"%', 'padding-right':'"+rowPaddingMobile['rPRM']+"%', });"+
        "}"+
        " "+
        '</script> ';

      }


      var currentRowResponsiveTriggerScripts = '\n'+ currRowMarginTablet + '\n' + currRowMarginMobile + '\n' +currRowDefaultMarginPadding;

      var currRowPadding  = 'padding:'+row_padding['rowPaddingTop'] +'% '+row_padding['rowPaddingRight']+'% '+ row_padding['rowPaddingBottom'] +'% '+ row_padding['rowPaddingLeft']+'%; ';
      var currRowMargins  = 'margin:'+row_margin['rowMarginTop'] +'% '+row_margin['rowMarginRight']+'% '+ row_margin['rowMarginBottom'] +'% '+ row_margin['rowMarginLeft']+'%; ';

      var rowBackgroundOptions = 'background-color:'+row_bg_color+';';

      rowBackgroundParallax = '';
      if (typeof(rowData['rowBackgroundParallax']) !== 'undefined') {
        if (rowData['rowBackgroundParallax'] == 'true') {
          rowBackgroundParallax = 'background-attachment:fixed;';
        }
      }
      if (row_bg_img != '') {
        rowBackgroundOptions = 'background-image: url('+row_bg_img+'); background-repeat:no-repeat; background-position: center center;  background-size: cover; background-color:'+row_bg_color+';';
      }

      var defaultRowBackgroundType = 'solid';
      if (typeof(rowData['rowBackgroundType']) !== 'undefined') {
        defaultRowBackgroundType = rowData['rowBackgroundType'];
        if (rowData['rowBackgroundType'] == 'gradient') {
          var rowGradient = rowData['rowGradient'];

          if (rowGradient['rowGradientType'] == 'linear') {
            rowBackgroundOptions = 'background: linear-gradient('+rowGradient['rowGradientAngle']+'deg, '+rowGradient['rowGradientColorFirst']+' '+rowGradient['rowGradientLocationFirst']+'%,'+rowGradient['rowGradientColorSecond']+' '+rowGradient['rowGradientLocationSecond']+'%);';
          }

          if (rowGradient['rowGradientType'] == 'radial') {
            rowBackgroundOptions = 'background: radial-gradient(at '+rowGradient['rowGradientPosition']+', '+rowGradient['rowGradientColorFirst']+' '+rowGradient['rowGradientLocationFirst']+'%,'+rowGradient['rowGradientColorSecond']+' '+rowGradient['rowGradientLocationSecond']+'%);';
          }
          
        }
      }

      var thisRowHoverStyleTag = '';
      var thisRowHoverOption = '';
      if (typeof(rowData['rowHoverOptions']) !== 'undefined') {
        var rowHoverOptions = rowData['rowHoverOptions'];
        if (rowHoverOptions['rowBackgroundTypeHover'] == 'solid') {
          var thisRowHoverOption = ' #'+rowID+':hover { background:'+rowHoverOptions['rowBgColorHover']+' !important; transition: all '+rowHoverOptions['rowHoverTransitionDuration']+'s; }';
        }
        if (rowHoverOptions['rowBackgroundTypeHover'] == 'gradient') {
          var rowGradientHover = rowHoverOptions['rowGradientHover'];

          if (rowGradientHover['rowGradientTypeHover'] == 'linear') {
            thisRowHoverOption = ' #'+rowID+':hover { background: linear-gradient('+rowGradientHover['rowGradientAngleHover']+'deg, '+rowGradientHover['rowGradientColorFirstHover']+' '+rowGradientHover['rowGradientLocationFirstHover']+'%,'+rowGradientHover['rowGradientColorSecondHover']+' '+rowGradientHover['rowGradientLocationSecondHover']+'%) !important; transition: all '+rowHoverOptions['rowHoverTransitionDuration']+'s; }';
          }

          if (rowGradientHover['rowGradientTypeHover'] == 'radial') {

            thisRowHoverOption = ' #'+rowID+':hover { background: radial-gradient(at '+rowGradientHover['rowGradientPositionHover']+', '+rowGradientHover['rowGradientColorFirstHover']+' '+rowGradientHover['rowGradientLocationFirstHover']+'%,'+rowGradientHover['rowGradientColorSecondHover']+' '+rowGradientHover['rowGradientLocationSecondHover']+'%) !important; transition: all '+rowHoverOptions['rowHoverTransitionDuration']+'s; }';
          }
        }

        thisRowHoverStyleTag = '<style> '+thisRowHoverOption+' </style>';
      }

      rowOverlayBackgroundOptions = '';
      if (typeof(rowData['rowBgOverlayColor']) !== 'undefined') {
        var rowOverlayBackgroundOptions = 'background:'+rowData['rowBgOverlayColor']+'; background-color:'+rowData['rowBgOverlayColor']+';';
      }
      
      if (typeof(rowData['rowOverlayBackgroundType']) !== 'undefined') {
        if (rowData['rowOverlayBackgroundType'] == 'gradient') {
          var rowOverlayGradient = rowData['rowOverlayGradient'];

          if (rowOverlayGradient['rowOverlayGradientType'] == 'linear') {
            rowOverlayBackgroundOptions = 'background: linear-gradient('+rowOverlayGradient['rowOverlayGradientAngle']+'deg, '+rowOverlayGradient['rowOverlayGradientColorFirst']+' '+rowOverlayGradient['rowOverlayGradientLocationFirst']+'%,'+rowOverlayGradient['rowOverlayGradientColorSecond']+' '+rowOverlayGradient['rowOverlayGradientLocationSecond']+'%);';
          }

          if (rowOverlayGradient['rowOverlayGradientType'] == 'radial') {
            rowOverlayBackgroundOptions = 'background: radial-gradient(at '+rowOverlayGradient['rowOverlayGradientPosition']+', '+rowOverlayGradient['rowOverlayGradientColorFirst']+' '+rowOverlayGradient['rowOverlayGradientLocationFirst']+'%,'+rowOverlayGradient['rowOverlayGradientColorSecond']+' '+rowOverlayGradient['rowOverlayGradientLocationSecond']+'%);';
          }
          
        }
      }

      $(this.el).attr('style','height:auto; overflow:visible; '+rowBackgroundOptions+' '+rowBackgroundParallax+' '+currRowMargins+' '+currRowPadding +custom_styling);

      $(this.el).attr('id',rowID);

      var rennderredShapeTopScripts = '';
      if (typeof(rowData['bgSTop']) != 'undefined') {
        var bgSTop = rowData['bgSTop'];
        var shapeType = bgSTop['rbgstType'];
        var rennderredShapeTop = bgshapessvgrender(rowID, shapeType, false, bgSTop, 'false' );

        jQuery(this.el).append(rennderredShapeTop['html']);
        rennderredShapeTopScripts =  rennderredShapeTop['scripts'];

      }

      var rennderredShapeBottomScripts = '';
      if (typeof(rowData['bgSBottom']) != 'undefined') {
        var bgSBottom = rowData['bgSBottom'];
        var shapeType = bgSBottom['rbgsbType'];
        bgSTop = 'false';
        var rennderredShapeBottom = bgshapessvgrender(rowID, shapeType, true, 'false', bgSBottom );

        jQuery(this.el).append(rennderredShapeBottom['html']);
        rennderredShapeBottomScripts = rennderredShapeBottom['scripts'];

      }




      var VideoBgHtml = '';
      var VideoBgStyling = '';
      if (  typeof(rowData['video']) != 'undefined' )  {
        var rowVideo = rowData['video'];
        if (typeof(rowVideo['rowBgVideoEnable']) == 'undefined') {
          rowVideo['rowBgVideoEnable'] = '';
        }
        rowBgVideoEnable = rowVideo['rowBgVideoEnable'];
        if (rowBgVideoEnable == 'true') {
          rowBgVideoLoop = rowVideo['rowBgVideoLoop'];
          rowVideoMpfour = rowVideo['rowVideoMpfour'];
          rowVideoWebM = rowVideo['rowVideoWebM'];
          rowVideoThumb = rowVideo['rowVideoThumb'];

          rowVideoID = 'bgVid-'+rowID;
          
          var VideoBgHtml = '<video poster="'+rowVideoThumb+'" id="'+rowVideoID+'" playsinline autoplay muted '+rowBgVideoLoop+' > <source src="'+rowVideoWebM+'" type="video/webm"> <source src="'+rowVideoMpfour+'" type="video/mp4"> </video>';

          var ifVideoMp4RowStyles = '#'+rowID+' {overflow:hidden !important; position:relative; }';

          if (typeof(rowVideo['rowVideoType']) != 'undefined') {
            if (rowVideo['rowVideoType'] == 'yt') {
              ytvidId = ytVidURLParser(rowVideo['rowVideoYtUrl']);
              var VideoBgHtml = '<iframe id="'+rowVideoID+'" width="100%" height="100%" src="https://www.youtube.com/embed/'+ytvidId+'?rel=0&amp;controls=0&amp;showinfo=0;mute=1;autoplay=1&loop=1&playlist='+ytvidId+'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>';
              ifVideoMp4RowStyles = '';
            }
          }

          var VideoBgStyling = '<div class="rowBgVidStyles"> <style type="text/css">#'+rowVideoID+' { position: absolute; min-width: 100%; min-height: 100%;background: url("'+rowVideoThumb+'") no-repeat;background-size: cover;transition: 1s opacity; left:0; right:0; top: 0; } '+ifVideoMp4RowStyles+' </style> </div>';

          $(this.el).prepend(VideoBgHtml + VideoBgStyling);

        }
        
      }




      //New extra BG Options for Row
      currRowDefaultBackgroundOps = '';
      currRowtabletBackgroundOps = '';
      currRowmobileBackgroundOps = '';

      if (defaultRowBackgroundType == 'solid') {
        if (typeof(rowData['bgImgOps']) != 'undefined') {

          drbgImgOps = rowData['bgImgOps'];

          defaultRowBgImg = rowData['bg_img'];

          if (typeof(rowData['bg_imgT']) == 'undefined' ) {
            rowData['bg_imgT'] = '';
            rowData['bg_imgM'] = '';
          }
          
          tabletRowBgImg = rowData['bg_imgT'];
          mobileRowBgImg = rowData['bg_imgM'];
          if (tabletRowBgImg == '') { tabletRowBgImg = defaultRowBgImg; }
          if (mobileRowBgImg == '') { mobileRowBgImg = tabletRowBgImg; }


          defaultRowBgFixed = 'scroll';
          if (rowData['rowBackgroundParallax'] == 'true') { defaultRowBgFixed = 'fixed'; }
          tabletRowBgFixed = defaultRowBgFixed; mobileRowBgFixed = defaultRowBgFixed;

          if (typeof(drbgImgOps['parlxT']) == 'undefined' ) {
            drbgImgOps['parlxT'] = '';
            drbgImgOps['parlxM'] = '';
          }

          if (drbgImgOps['parlxT'] == 'true') { tabletRowBgFixed = 'fixed'; }
          if (drbgImgOps['parlxT'] == 'false') { tabletRowBgFixed = 'scroll'; }
          if (drbgImgOps['parlxM'] == 'true') { mobileRowBgFixed = 'fixed'; }
          if (drbgImgOps['parlxM'] == 'false') { mobileRowBgFixed = 'scroll'; }

          drbgImgOpsRep = drbgImgOps['rep'];
          drbgImgOpsRepT = drbgImgOps['repT'];
          drbgImgOpsRepM = drbgImgOps['repM'];

          // desktop
          if (drbgImgOps['pos'] == 'custom') {
            defaultBgImgPos = "'background-position-x': '"+drbgImgOps['xPos']+drbgImgOps['xPosU']+ "', " + "'background-position-y': '"+drbgImgOps['yPos']+drbgImgOps['yPosU']+ "', ";
          }else{
            defaultBgImgPos = "'background-position': '"+drbgImgOps['pos']+"', ";
          }

          if ( drbgImgOpsRep == '' || drbgImgOpsRep == 'default') { drbgImgOpsRep = 'no-repeat'; }

          if (drbgImgOps['size'] == 'custom') {
            defaultBgImgSize = "'background-size': '"+drbgImgOps['cWid']+drbgImgOps['widU']+"', " 
          }else{
            defaultBgImgSize = "'background-size': '"+drbgImgOps['size']+"', ";
          }
          
          var currRowDefaultBackgroundOps  = ''+
          "<script>"+
            "jQuery('.responsiveBtn').live('click',function(){"+
            "if (jQuery(this).hasClass('rbt-l') ) { "+
              "jQuery('#"+rowID+"').css({ "+
                "'background-image': 'url("+defaultRowBgImg+")', "+
                "'background-repeat': '"+drbgImgOpsRep+"', "+
                "'background-attachment': '"+defaultRowBgFixed+"', "+
                defaultBgImgPos +
                defaultBgImgSize+
              "});"+
            "}"+
            "});"+
            " "+
            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-l' ) { "+
              "jQuery('#"+rowID+"').css({ "+
                "'background-image': 'url("+defaultRowBgImg+")', "+
                "'background-repeat': '"+drbgImgOpsRep+"', "+
                "'background-attachment': '"+defaultRowBgFixed+"', "+
                defaultBgImgPos +
                defaultBgImgSize+
              "});"+
            "}"+
            " "+
          "</script>";



          // Tablet
          if (drbgImgOps['posT'] == 'custom') {
            tabletBgImgPos = "'background-position-x': '"+drbgImgOps['xPosT']+drbgImgOps['xPosUT']+ "', " + "'background-position-y': '"+drbgImgOps['yPosT']+drbgImgOps['yPosUT']+ "', ";
          } else if(drbgImgOps['posT'] == ''){
            tabletBgImgPos = defaultBgImgPos;
          }else{
            tabletBgImgPos = "'background-position': '"+drbgImgOps['posT']+"', ";
          }

          if (drbgImgOpsRepT == '' || drbgImgOpsRepT == 'default') { drbgImgOpsRepT = drbgImgOpsRep; }


          if (drbgImgOps['sizeT'] == 'custom') {
            tabletBgImgSize = "'background-size': '"+drbgImgOps['cWidT']+drbgImgOps['widUT']+"', " 
          }else if(drbgImgOps['sizeM'] == ''){
            tabletBgImgSize = defaultBgImgSize;
          }else{
            tabletBgImgSize = "'background-size': '"+drbgImgOps['sizeT']+"', ";
          }
          

          var currRowtabletBackgroundOps  = ''+
          "<script>"+
            "jQuery('.responsiveBtn').live('click',function(){"+
            "if (jQuery(this).hasClass('rbt-m') ) { "+
              "jQuery('#"+rowID+"').css({ "+
                "'background-image': 'url("+tabletRowBgImg+")', "+
                "'background-repeat': '"+drbgImgOpsRepT+"', "+
                "'background-attachment': '"+tabletRowBgFixed+"', "+
                tabletBgImgPos +
                tabletBgImgSize+
              "});"+
            "}"+
            "});"+
            " "+
            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-m' ) { "+
              "jQuery('#"+rowID+"').css({ "+
                "'background-image': 'url("+tabletRowBgImg+")', "+
                "'background-repeat': '"+drbgImgOpsRepT+"', "+
                "'background-attachment': '"+tabletRowBgFixed+"', "+
                tabletBgImgPos +
                tabletBgImgSize+
              "});"+
            "}"+
            " "+
          "</script>";




          // mobile
          if (drbgImgOps['posM'] == 'custom') {
            mobileBgImgPos = "'background-position-x': '"+drbgImgOps['xPosM']+drbgImgOps['xPosUM']+ "', " + "'background-position-y': '"+drbgImgOps['yPosM']+drbgImgOps['yPosUM']+ "', ";
          }else if(drbgImgOps['posT'] == ''){
            mobileBgImgPos = tabletBgImgPos;
          }else{
            mobileBgImgPos = "'background-position': '"+drbgImgOps['posM']+"', ";
          }

          if (drbgImgOpsRepM == '' || drbgImgOpsRepM == 'default') { drbgImgOpsRepM = drbgImgOpsRepT; }

          if (drbgImgOps['sizeM'] == 'custom') {
            mobileBgImgSize = "'background-size': '"+drbgImgOps['cWidM']+drbgImgOps['widM']+"', ";
          }else if(drbgImgOps['sizeM'] == ''){
            mobileBgImgSize = tabletBgImgSize;
          }else{
            mobileBgImgSize = "'background-size': '"+drbgImgOps['sizeM']+"', ";
          }
          
          var currRowmobileBackgroundOps  = ''+
          "<script>"+
            "jQuery('.responsiveBtn').live('click',function(){"+
            "if (jQuery(this).hasClass('rbt-s') ) { "+
              "jQuery('#"+rowID+"').css({ "+
                "'background-image': 'url("+mobileRowBgImg+")', "+
                "'background-repeat': '"+drbgImgOpsRepM+"', "+
                "'background-attachment': '"+mobileRowBgFixed+"', "+
                mobileBgImgPos +
                mobileBgImgSize+
              "});"+
            "}"+
            "});"+
            " "+
            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-s' ) { "+
              "jQuery('#"+rowID+"').css({ "+
                "'background-image': 'url("+mobileRowBgImg+")', "+
                "'background-repeat': '"+drbgImgOpsRepM+"', "+
                "'background-attachment': '"+mobileRowBgFixed+"', "+
                mobileBgImgPos +
                mobileBgImgSize+
              "});"+
            "}"+
            " "+
          "</script>";


        }
      }
        

      rowBgResScripts = currRowDefaultBackgroundOps + currRowtabletBackgroundOps + currRowmobileBackgroundOps;


      $(this.el).append(
        ' <div class="row-overlay" style="height:100%; '+rowOverlayBackgroundOptions+' top:0; left: 0; width: 100%; position:absolute;"></div>'
      );

      $(this.el).append(
        '<div id="ulpb_row_controls" class="ulpb_row_controls" style="display:none;">'+

          '<div id="edit_form_close" class="btn-red btn" style="display:none;"><span class="dashicons dashicons-no-alt"></span></div>'+

          '<div id="editRowSave" style="display:none;"><span style="padding: 25px 0px 25px 0px;background: transparent;border-radius: 5px;display:unset;font-size: 35px;color: #585858;border-top-left-radius: 0;border-bottom-left-radius: 0;" class="dashicons dashicons-arrow-left"></span> </div>'+

          '<div id="editRowSaveVisible" class=""><span class="dashicons dashicons-arrow-left editSaveVisibleIcon"></span> </div>'+

          '<div id="reAddDeletedColumns" style="display:none;" ></div>'+

          '<div id="copyThisRowLS" style="display:none;" ></div>'+
          '<div id="pasteThisRowLS" style="display:none;" ></div>'+

        '</div>'
      );

      $(this.el).append(
        '<div id="thisRowScripts" style="display:none !important;">'+ currentRowResponsiveTriggerScripts + rowBgResScripts + rennderredShapeTopScripts + rennderredShapeBottomScripts + thisRowHoverStyleTag +'</div>'
      );
      columnContainerSetWidth = 'max-width:100%;';
      if (typeof(rowData['conType']) != 'undefined' ) {
        if (rowData['conType'] == 'boxed') {
          if (rowData['conWidth'] != '') {
            columnContainerSetWidth = 'max-width:'+rowData['conWidth']+'px;';
          }
        }
      }
      $(this.el).append(
        '<div class="rowColumnsContainer" id="rowColCont-'+rowID+'" style='+columnContainerSetWidth+'></div>'
      );
    
      
      colControlsArray = [];
      for(var i = 1; i <= rowColumns; i++){
        var this_column = 'column'+i;
        var thisColumnModelData = this.model.get(this_column);
        if (typeof(thisColumnModelData) == 'undefined' ) {
          continue;
        }
        var this_column_options = thisColumnModelData['columnOptions'];
        var this_column_bg_color = this_column_options['bg_color'];
        var this_column_margin = this_column_options['margin'];
        var this_column_padding = this_column_options['padding'];
        var colWidth = this_column_options['width'];
        var columnCSS = this_column_options['columnCSS'];
        //var colWidthInPx = Math.floor( (1268*colWidth)/100);
        var columnMarginTop = this_column_margin['columnMarginTop'];
        var columnMarginRight = this_column_margin['columnMarginRight'];
        var columnMarginBottom = this_column_margin['columnMarginBottom'];
        var columnMarginLeft = this_column_margin['columnMarginLeft'];

        var columnPaddingTop = this_column_padding['columnPaddingTop'];
        var columnPaddingRight = this_column_padding['columnPaddingRight'];
        var columnPaddingBottom = this_column_padding['columnPaddingBottom'];
        var columnPaddingLeft = this_column_padding['columnPaddingLeft'];

        var this_column_margins = "margin:"+columnMarginTop+"% "+columnMarginRight+"% "+columnMarginBottom+"% "+columnMarginLeft+"%;   padding:"+columnPaddingTop+"% "+columnPaddingRight+"% "+columnPaddingBottom+"% "+columnPaddingLeft+"%;";

        this_col_shadow = '';
        if (typeof(this_column_options['colBoxShadow']) !== 'undefined') {
          colBoxShadow = this_column_options['colBoxShadow'];
          var this_col_shadow = 'box-shadow: '+colBoxShadow['colBoxShadowH']+'px  '+colBoxShadow['colBoxShadowV']+'px  '+colBoxShadow['colBoxShadowBlur']+'px '+colBoxShadow['colBoxShadowColor']+' ;  ';
        }

        if (typeof(this_column_options['colBorder']) !== 'undefined') {
          colBorder = this_column_options['colBorder'];
          if (typeof(colBorder['bwt']) == 'undefined') {
            
            colBorder['bwt'] = colBorder['colBorderWidth'];
            colBorder['bwb'] = colBorder['colBorderWidth'];
            colBorder['bwl'] = colBorder['colBorderWidth'];
            colBorder['bwr'] = colBorder['colBorderWidth'];
            // border radius
            colBorder['brt'] = colBorder['colBorderRadius'];
            colBorder['brb'] = colBorder['colBorderRadius'];
            colBorder['brl'] = colBorder['colBorderRadius'];
            colBorder['brr'] = colBorder['colBorderRadius'];

            this_column_options['colBorder'] = colBorder;
          }
        }


        if (typeof(this_column_options['colBorder']) !== 'undefined') {
          colBorder = this_column_options['colBorder'];
          if (typeof(colBorder['bwt']) == 'undefined') {
            
            colBorder['bwt'] = colBorder['colBorderWidth'];
            colBorder['bwb'] = colBorder['colBorderWidth'];
            colBorder['bwl'] = colBorder['colBorderWidth'];
            colBorder['bwr'] = colBorder['colBorderWidth'];
            // border radius
            colBorder['brt'] = colBorder['colBorderRadius'];
            colBorder['brb'] = colBorder['colBorderRadius'];
            colBorder['brl'] = colBorder['colBorderRadius'];
            colBorder['brr'] = colBorder['colBorderRadius'];

            this_column_options['colBorder'] = colBorder;
          }
        }


        this_col_border = ''; currColBorderDefault = ''; currColBorderTablet = ''; currColBorderMobile = '';
        if (typeof(this_column_options['colBorder']) !== 'undefined') {
          colBorder = this_column_options['colBorder'];
          var this_col_border =
            'border-top-width:'+colBorder['bwt']+'px;'+
            'border-bottom-width:'+colBorder['bwb']+'px;'+
            'border-left-width:'+colBorder['bwl']+'px;'+
            'border-right-width:'+colBorder['bwr']+'px;'+
            'border-style:'+colBorder['colBorderStyle']+';'+
            'border-color:'+colBorder['colBorderColor']+ ';'+
            'border-radius:'+colBorder['brt']+'px '+colBorder['brb']+'px '+colBorder['brr']+'px '+colBorder['brl']+'px;'
          ;


          if (typeof(colBorder['bwt']) != 'undefined') {
            var currColBorderDefault  = ''+
            '<script>'+
              "jQuery('.responsiveBtn').live('click',function(){"+

                "if (jQuery(this).hasClass('rbt-l') ) { "+
                  
                  "jQuery('#"+rowID+"-"+this_column+"').css({"+
                    "'border-top-width':'"+colBorder['bwt']+"px',"+
                    "'border-bottom-width':'"+colBorder['bwb']+"px',"+
                    "'border-left-width':'"+colBorder['bwl']+"px',"+
                    "'border-right-width':'"+colBorder['bwr']+"px',"+
                    "'border-radius':'"+colBorder['brt']+"px "+colBorder['brb']+"px "+colBorder['brr']+"px "+colBorder['brl']+"px',"+
                  "});"+

                "}"+

              "});"+

              "var currentVPS = jQuery('.currentViewPortSize').val();"+
              "if ( currentVPS == 'rbt-l' ) { "+
                  "jQuery('#"+rowID+"-"+this_column+"').css({"+
                    "'border-top-width':'"+colBorder['bwt']+"px',"+
                    "'border-bottom-width':'"+colBorder['bwb']+"px',"+
                    "'border-left-width':'"+colBorder['bwl']+"px',"+
                    "'border-right-width':'"+colBorder['bwr']+"px',"+
                    "'border-radius':'"+colBorder['brt']+"px "+colBorder['brb']+"px "+colBorder['brr']+"px "+colBorder['brl']+"px',"+
                  "});"+
              "}"+

            '</script>';

          }

          if (typeof(colBorder['bwtT']) != 'undefined') {
            var currColBorderTablet  = ''+
            '<script>'+
              "jQuery('.responsiveBtn').live('click',function(){"+

                "if (jQuery(this).hasClass('rbt-m') ) { "+
                  
                  "jQuery('#"+rowID+"-"+this_column+"').css({"+
                    "'border-top-width':'"+colBorder['bwtT']+"px',"+
                    "'border-bottom-width':'"+colBorder['bwbT']+"px',"+
                    "'border-left-width':'"+colBorder['bwlT']+"px',"+
                    "'border-right-width':'"+colBorder['bwrT']+"px',"+
                    "'border-radius':'"+colBorder['brtT']+"px "+colBorder['brbT']+"px "+colBorder['brrT']+"px "+colBorder['brlT']+"px',"+
                  "});"+

                "}"+

              "});"+

              "var currentVPS = jQuery('.currentViewPortSize').val();"+
              "if ( currentVPS == 'rbt-m' ) { "+
                  "jQuery('#"+rowID+"-"+this_column+"').css({"+
                    "'border-top-width':'"+colBorder['bwtT']+"px',"+
                    "'border-bottom-width':'"+colBorder['bwbT']+"px',"+
                    "'border-left-width':'"+colBorder['bwlT']+"px',"+
                    "'border-right-width':'"+colBorder['bwrT']+"px',"+
                    "'border-radius':'"+colBorder['brtT']+"px "+colBorder['brbT']+"px "+colBorder['brrT']+"px "+colBorder['brlT']+"px',"+
                  "});"+
              "}"+

            '</script>';

          }

          if (typeof(colBorder['bwtM']) != 'undefined') {
            var currColBorderMobile  = ''+
            '<script>'+
              "jQuery('.responsiveBtn').live('click',function(){"+

                "if (jQuery(this).hasClass('rbt-s') ) { "+
                  
                  "jQuery('#"+rowID+"-"+this_column+"').css({"+
                    "'border-top-width':'"+colBorder['bwtM']+"px',"+
                    "'border-bottom-width':'"+colBorder['bwbM']+"px',"+
                    "'border-left-width':'"+colBorder['bwlM']+"px',"+
                    "'border-right-width':'"+colBorder['bwrM']+"px',"+
                    "'border-radius':'"+colBorder['brtM']+"px "+colBorder['brbM']+"px "+colBorder['brrM']+"px "+colBorder['brlM']+"px',"+
                  "});"+

                "}"+

              "});"+

              "var currentVPS = jQuery('.currentViewPortSize').val();"+
              "if ( currentVPS == 'rbt-s' ) { "+
                  "jQuery('#"+rowID+"-"+this_column+"').css({"+
                    "'border-top-width':'"+colBorder['bwtM']+"px',"+
                    "'border-bottom-width':'"+colBorder['bwbM']+"px',"+
                    "'border-left-width':'"+colBorder['bwlM']+"px',"+
                    "'border-right-width':'"+colBorder['bwrM']+"px',"+
                    "'border-radius':'"+colBorder['brtM']+"px "+colBorder['brbM']+"px "+colBorder['brrM']+"px "+colBorder['brlM']+"px',"+
                  "});"+
              "}"+

            '</script>';

          } 

        }

        colBorderResponsiveScripts = currColBorderDefault + currColBorderTablet + currColBorderMobile;


        this_col_shadow = this_col_shadow + this_col_border;

        colWidthIsEmpty = false;
        var colWidthUnit = '%';
        if (colWidth == "" || colWidth == " ") {
          switch (rowColumns) {
            case '1':
              colWidth = 100;
              break;
            case '2':
              colWidth = 49;
              break;
            case '3':
              colWidth = 33;
              break;
            case '4':
              colWidth = 24;
              break;
            case '5':
              colWidth = 19;
              break;
            case '6':
              colWidth = 16.5;
              break;
            case '7':
              colWidth = 14.1;
              break;
            case '8':
              colWidth = 12;
              break;
            case '9':
              colWidth = 11;
              break;
            case '10':
              colWidth = 9.5;
              break;  
            default:
              colWidth = 99;
              break;
          }

          colWidthIsEmpty = true;
        }

        if ( parseInt(columnPaddingLeft) > 0 ) {  colWidth = parseInt(colWidth) - parseInt(columnPaddingLeft) }
        if ( parseInt(columnPaddingRight) > 0 ) {  colWidth = parseInt(colWidth) - parseInt(columnPaddingRight) }

        if (colWidthIsEmpty == true) {
          if ( parseInt(columnMarginLeft) > 0 ) {  colWidth = parseInt(colWidth) - parseInt(columnMarginLeft) }
          if ( parseInt(columnMarginRight) > 0 ) {  colWidth = parseInt(colWidth) - parseInt(columnMarginRight)  }
        }

          columnCustomClass = '';
        if (typeof(this_column_options['columnCustomClass']) !== 'undefined') {
          columnCustomClass = this_column_options['columnCustomClass'];
        }

        var colHideOnDesktop ="'display':'inline-block' ,", colHideOnTablet = "'display':'inline-block' ,", colHideOnMobile = "'display':'inline-block' ,";
        if (typeof(this_column_options['colHideOnDesktop']) !== 'undefined' ) {
          if (this_column_options['colHideOnDesktop'] == 'hide') {
            colHideOnDesktop = "display:'none' ,";
          }

          if (this_column_options['colHideOnTablet'] == 'hide') {
            colHideOnTablet = "display:'none' ,";
          }
          if (this_column_options['colHideOnMobile'] == 'hide') {
            colHideOnMobile = "display:'none' ,";
          }
        }

        colContentAlignD = '' , colContentAlignT = '', colContentAlignM = '';
        if (typeof(this_column_options['colCAD']) != 'undefined' ) {

          if (this_column_options['colCAD'] != 'default' && this_column_options['colCAD'] != '') {
            if (colHideOnDesktop != "display:'none' ,") {
              colContentAlignD = "display: 'flex' , 'justify-content': '"+this_column_options['colCAD']+"' , 'flex-direction': 'column' ,";
            }
          }

          if (this_column_options['colCAT'] != 'default' && this_column_options['colCAT'] != '') {
            if (colHideOnTablet != "display:'none' ,") {
              colContentAlignT = "display: 'flex' , 'justify-content': '"+this_column_options['colCAT']+"' , 'flex-direction': 'column' ,";
            }
          }

          if (this_column_options['colCAM'] != 'default' && this_column_options['colCAM'] != '') {
            if (colHideOnMobile != "display:'none' ,") {
              colContentAlignM = "display: 'flex' , 'justify-content': '"+this_column_options['colCAM']+"' , 'flex-direction': 'column' ,";
            }
          }

        }
        

        currColmarginDefault = '';
        currColmarginTablet = '';
        currColmarginMobile = '';

        var currColmarginDefault  = ''+
            '<script>'+
            "jQuery('.responsiveBtn').live('click',function(){"+
            " if (jQuery(this).hasClass('rbt-l') ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+columnMarginTop+"%', 'margin-bottom':'"+columnMarginBottom+"%', 'margin-left':'"+columnMarginLeft+"%', 'margin-right':'"+columnMarginRight+"%', 'min-height':'"+rowHeight+rowHeightUnit+"',  "+colHideOnDesktop+colContentAlignD+"});"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+columnPaddingTop+"%', 'padding-bottom':'"+columnPaddingBottom+"%', 'padding-left':'"+columnPaddingLeft+"%', 'padding-right':'"+columnPaddingRight+"%', 'width':'"+colWidth+colWidthUnit+"', });"+
            " }"+
            "});"+

            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-l' ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+columnMarginTop+"%', 'margin-bottom':'"+columnMarginBottom+"%', 'margin-left':'"+columnMarginLeft+"%', 'margin-right':'"+columnMarginRight+"%', 'min-height':'"+rowHeight+rowHeightUnit+"', "+colHideOnDesktop+colContentAlignD+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+columnPaddingTop+"%', 'padding-bottom':'"+columnPaddingBottom+"%', 'padding-left':'"+columnPaddingLeft+"%', 'padding-right':'"+columnPaddingRight+"%', 'width':'"+colWidth+colWidthUnit+"', });"+
            "}"+
            " "+
            '</script> ';

        if (typeof(this_column_options['paddingTablet']) !== 'undefined') {

          colPaddingTablet = this_column_options['paddingTablet'];
          colMarginTablet = this_column_options['marginTablet'];
          colWidthTablet = this_column_options['widthTablet'];

          if (colWidthTablet == '') {
            colWidthTablet = '99.9';
          }

          if (colWidthTablet != '') {
            if ( parseInt(colPaddingTablet['rPLT']) > 0 ) {  colWidthTablet = parseInt(colWidthTablet) - parseInt(colPaddingTablet['rPLT']) }
            if ( parseInt(colPaddingTablet['rPRT']) > 0 ) {  colWidthTablet = parseInt(colWidthTablet) - parseInt(colPaddingTablet['rPRT']) }
          }

          var currColmarginTablet  = ''+
            '<script>'+
            "jQuery('.responsiveBtn').live('click',function(){"+
            " if (jQuery(this).hasClass('rbt-m') ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+colMarginTablet['rMTT']+"%', 'margin-bottom':'"+colMarginTablet['rMBT']+"%', 'margin-left':'"+colMarginTablet['rMLT']+"%', 'margin-right':'"+colMarginTablet['rMRT']+"%',  'min-height':'"+rowHeightTablet+rowHeightUnitTablet+"', "+colHideOnTablet+colContentAlignT+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+colPaddingTablet['rPTT']+"%', 'padding-bottom':'"+colPaddingTablet['rPBT']+"%', 'padding-left':'"+colPaddingTablet['rPLT']+"%', 'padding-right':'"+colPaddingTablet['rPRT']+"%', 'width':'"+colWidthTablet+"%', });"+
            " }"+
            "});"+

            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-m' ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+colMarginTablet['rMTT']+"%', 'margin-bottom':'"+colMarginTablet['rMBT']+"%', 'margin-left':'"+colMarginTablet['rMLT']+"%', 'margin-right':'"+colMarginTablet['rMRT']+"%', 'min-height':'"+rowHeightTablet+rowHeightUnitTablet+"', "+colHideOnTablet+colContentAlignT+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+colPaddingTablet['rPTT']+"%', 'padding-bottom':'"+colPaddingTablet['rPBT']+"%', 'padding-left':'"+colPaddingTablet['rPLT']+"%', 'padding-right':'"+colPaddingTablet['rPRT']+"%', 'width':'"+colWidthTablet+"%', });"+
            "}"+
            " "+
            '</script> ';
          
        }

        var currColmarginMobile  = ''+
            '<script>'+
            "jQuery('.responsiveBtn').live('click',function(){"+
            " if (jQuery(this).hasClass('rbt-s') || jQuery(this).hasClass('rbt-m') ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+''+"%', 'margin-bottom':'"+'30px'+"', 'margin-left':'"+''+"%', 'margin-right':'"+''+"%', 'min-height':'"+ rowHeightMobile+rowHeightUnitMobile+"',});"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+''+"%', 'padding-bottom':'"+''+"%', 'padding-left':'"+''+"%', 'padding-right':'"+''+"%', 'width':'"+'99.9'+"%', });"+
            " }"+
            "});"+

            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-s' || currentVPS == 'rbt-m' ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+''+"%', 'margin-bottom':'"+'30px'+"', 'margin-left':'"+''+"%', 'margin-right':'"+''+"%', 'min-height':'"+ rowHeightMobile+rowHeightUnitMobile+"',});"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+''+"%', 'padding-bottom':'"+''+"%', 'padding-left':'"+''+"%', 'padding-right':'"+''+"%', 'width':'"+'99.9'+"%', });"+
            "}"+
            " "+
            '</script> ';
        if (typeof(this_column_options['paddingMobile']) !== 'undefined') {
          colPaddingMobile = this_column_options['paddingMobile'];
          colMarginMobile = this_column_options['marginMobile'];
          colWidthMobile = this_column_options['widthMobile'];

          if (colWidthMobile == '') {
            colWidthMobile = '99.9';
          }

          if (colWidthMobile != '') {
            if ( parseInt(colPaddingMobile['rPLM']) > 0 ) {  colWidthMobile = parseInt(colWidthMobile) - parseInt(colPaddingMobile['rPLM']) }
            if ( parseInt(colMarginMobile['rMRM']) > 0 ) {  colWidthMobile = parseInt(colWidthMobile) - parseInt(colMarginMobile['rMRM']) }
          }

          colMarginMobileBottomUnit = '%';
          if (colMarginMobile['rMBM'] == '') {
            colMarginMobile['rMBM'] = '';
            colMarginMobileBottomUnit = 'px';
          }

          currColmarginMobile  = ''+
            '<script>'+
            "jQuery('.responsiveBtn').live('click',function(){"+
            " if (jQuery(this).hasClass('rbt-s') ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+colMarginMobile['rMTM']+"%', 'margin-bottom':'"+colMarginMobile['rMBM']+colMarginMobileBottomUnit+"', 'margin-left':'"+colMarginMobile['rMLM']+"%', 'margin-right':'"+colMarginMobile['rMRM']+"%', 'min-height':'"+rowHeightMobile+rowHeightUnitMobile+"', "+colHideOnMobile+colContentAlignM+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+colPaddingMobile['rPTM']+"%', 'padding-bottom':'"+colPaddingMobile['rPBM']+"%', 'padding-left':'"+colPaddingMobile['rPLM']+"%', 'padding-right':'"+colPaddingMobile['rPRM']+"%', 'width':'"+colWidthMobile+"%', });"+
            " }"+
            "});"+

            "var currentVPS = jQuery('.currentViewPortSize').val();"+
            "if ( currentVPS == 'rbt-s' ) { "+
            "  jQuery('#"+rowID+"-"+this_column+"').css({'margin-top':'"+colMarginMobile['rMTM']+"%', 'margin-bottom':'"+colMarginMobile['rMBM']+colMarginMobileBottomUnit+"', 'margin-left':'"+colMarginMobile['rMLM']+"%', 'margin-right':'"+colMarginMobile['rMRM']+"%', 'min-height':'"+rowHeightMobile+rowHeightUnitMobile+"', "+colHideOnMobile+colContentAlignM+" });"+

            "  jQuery('#"+rowID+"-"+this_column+"').css({'padding-top':'"+colPaddingMobile['rPTM']+"%', 'padding-bottom':'"+colPaddingMobile['rPBM']+"%', 'padding-left':'"+colPaddingMobile['rPLM']+"%', 'padding-right':'"+colPaddingMobile['rPRM']+"%', 'width':'"+colWidthMobile+"%', });"+
            "}"+
            " "+
            '</script> ';
        }


        var colBackgroundOptions = 'background-color:'+this_column_bg_color+';';

        this_column_bg_img = '';
        if (typeof(this_column_options['colBgImg']) !== 'undefined') {
          this_column_bg_img = this_column_options['colBgImg'];
          if (this_column_bg_img !== '') {
            colBackgroundOptions = 'background-image: url('+this_column_bg_img+'); background-repeat: no-repeat; background-position:center; background-size:cover; background-color:'+this_column_bg_color+';';
          }
        }
        
        var defaultColBackgroundType = 'solid';
        if (typeof(this_column_options['colBackgroundType']) !== 'undefined') {

          defaultColBackgroundType = this_column_options['colBackgroundType'];
          if (this_column_options['colBackgroundType'] == 'gradient') {
            var colGradient = this_column_options['colGradient'];

            if (colGradient['colGradientType'] == 'linear') {
              colBackgroundOptions = 'background: linear-gradient('+colGradient['colGradientAngle']+'deg, '+colGradient['colGradientColorFirst']+' '+colGradient['colGradientLocationFirst']+'%,'+colGradient['colGradientColorSecond']+' '+colGradient['colGradientLocationSecond']+'%);';
            }

            if (colGradient['colGradientType'] == 'radial') {
              colBackgroundOptions = 'background: radial-gradient(at '+colGradient['colGradientPosition']+', '+colGradient['colGradientColorFirst']+' '+colGradient['colGradientLocationFirst']+'%,'+colGradient['colGradientColorSecond']+' '+colGradient['colGradientLocationSecond']+'%);';
            }
            
          }
        }

        var colID = rowID+'-'+this_column;
        var thisColHoverStyleTag = '';
        var thisColHoverOption = '';
        if (typeof(this_column_options['colHoverOptions']) !== 'undefined') {
          var colHoverOptions = this_column_options['colHoverOptions'];
          if (colHoverOptions['colBackgroundTypeHover'] == 'solid') {
            var thisColHoverOption = ' #'+colID+':hover { background:'+colHoverOptions['colBgColorHover']+' !important; transition: all '+colHoverOptions['colHoverTransitionDuration']+'s; }';
          }
          if (colHoverOptions['colBackgroundTypeHover'] == 'gradient') {
            var colGradientHover = colHoverOptions['colGradientHover'];

            if (colGradientHover['colGradientTypeHover'] == 'linear') {
              thisColHoverOption = ' #'+colID+':hover { background: linear-gradient('+colGradientHover['colGradientAngleHover']+'deg, '+colGradientHover['colGradientColorFirstHover']+' '+colGradientHover['colGradientLocationFirstHover']+'%,'+colGradientHover['colGradientColorSecondHover']+' '+colGradientHover['colGradientLocationSecondHover']+'%) !important; transition: all '+colHoverOptions['colHoverTransitionDuration']+'s; }';
            }

            if (colGradientHover['colGradientTypeHover'] == 'radial') {

              thisColHoverOption = ' #'+colID+':hover { background: radial-gradient(at '+colGradientHover['colGradientPositionHover']+', '+colGradientHover['colGradientColorFirstHover']+' '+colGradientHover['colGradientLocationFirstHover']+'%,'+colGradientHover['colGradientColorSecondHover']+' '+colGradientHover['colGradientLocationSecondHover']+'%) !important; transition: all '+colHoverOptions['colHoverTransitionDuration']+'s; }';
            }
          }

        }



        //New extra BG Options for column
        currColDefaultBackgroundOps = '';
        currColtabletBackgroundOps = '';
        currColmobileBackgroundOps = '';

        if (defaultColBackgroundType == 'solid') {
          if (typeof(this_column_options['bgImgOps']) != 'undefined') {

            drbgImgOps = this_column_options['bgImgOps'];

            defaultRowBgImg = this_column_options['colBgImg'];
            tabletRowBgImg = this_column_options['colBgImgT'];
            mobileRowBgImg = this_column_options['colBgImgM'];
            if (tabletRowBgImg == '') { tabletRowBgImg = defaultRowBgImg; }
            if (mobileRowBgImg == '') { mobileRowBgImg = tabletRowBgImg; }


            defaultRowBgFixed = 'scroll';
            if (drbgImgOps['parlx'] == 'true') { defaultRowBgFixed = 'fixed'; }
            tabletRowBgFixed = defaultRowBgFixed; mobileRowBgFixed = defaultRowBgFixed;
            if (drbgImgOps['parlxT'] == 'true') { tabletRowBgFixed = 'fixed'; }
            if (drbgImgOps['parlxT'] == 'false') { tabletRowBgFixed = 'scroll'; }
            if (drbgImgOps['parlxM'] == 'true') { mobileRowBgFixed = 'fixed'; }
            if (drbgImgOps['parlxM'] == 'false') { mobileRowBgFixed = 'scroll'; }

            drbgImgOpsRep = drbgImgOps['rep'];
            drbgImgOpsRepT = drbgImgOps['repT'];
            drbgImgOpsRepM = drbgImgOps['repM'];

            // desktop
            if (drbgImgOps['pos'] == 'custom') {
              defaultBgImgPos = "'background-position-x': '"+drbgImgOps['xPos']+drbgImgOps['xPosU']+ "', " + "'background-position-y': '"+drbgImgOps['yPos']+drbgImgOps['yPosU']+ "', ";
            }else{
              defaultBgImgPos = "'background-position': '"+drbgImgOps['pos']+"', ";
            }

            if ( drbgImgOpsRep == '' || drbgImgOpsRep == 'default') { drbgImgOpsRep = 'no-repeat'; }

            if (drbgImgOps['size'] == 'custom') {
              defaultBgImgSize = "'background-size': '"+drbgImgOps['cWid']+drbgImgOps['widU']+"', ";
            }else{
              defaultBgImgSize = "'background-size': '"+drbgImgOps['size']+"', ";
            }
            

            var currColDefaultBackgroundOps  = ''+
            "<script>"+
              "jQuery('.responsiveBtn').live('click',function(){"+
              "if (jQuery(this).hasClass('rbt-l') ) { "+
                "jQuery('#"+colID+"').css({ "+
                  "'background-image': 'url("+defaultRowBgImg+")', "+
                  "'background-repeat': '"+drbgImgOpsRep+"', "+
                  "'background-attachment': '"+defaultRowBgFixed+"', "+
                  defaultBgImgPos +
                  defaultBgImgSize+
                "});"+
              "}"+
              "});"+
              " "+
              "var currentVPS = jQuery('.currentViewPortSize').val();"+
              "if ( currentVPS == 'rbt-l' ) { "+
                "jQuery('#"+colID+"').css({ "+
                  "'background-image': 'url("+defaultRowBgImg+")', "+
                  "'background-repeat': '"+drbgImgOpsRep+"', "+
                  "'background-attachment': '"+defaultRowBgFixed+"', "+
                  defaultBgImgPos +
                  defaultBgImgSize+
                "});"+
              "}"+
              " "+
            "</script>";



            // Tablet
            if (drbgImgOps['posT'] == 'custom') {
              tabletBgImgPos = "'background-position-x': '"+drbgImgOps['xPosT']+drbgImgOps['xPosUT']+ "', " + "'background-position-y': '"+drbgImgOps['yPosT']+drbgImgOps['yPosUT']+ "', ";
            } else if(drbgImgOps['posT'] == ''){
              tabletBgImgPos = defaultBgImgPos;
            }else{
              tabletBgImgPos = "'background-position': '"+drbgImgOps['posT']+"', ";
            }

            if (drbgImgOpsRepT == '' || drbgImgOpsRepT == 'default') { drbgImgOpsRepT = drbgImgOpsRep; }


            if (drbgImgOps['sizeT'] == 'custom') {
              tabletBgImgSize = "'background-size': '"+drbgImgOps['cWidT']+drbgImgOps['widUT']+"', " 
            }else if(drbgImgOps['sizeM'] == ''){
              tabletBgImgSize = defaultBgImgSize;
            }else{
              tabletBgImgSize = "'background-size': '"+drbgImgOps['sizeT']+"', ";
            }
            

            var currColtabletBackgroundOps  = ''+
            "<script>"+
              "jQuery('.responsiveBtn').live('click',function(){"+
              "if (jQuery(this).hasClass('rbt-m') ) { "+
                "jQuery('#"+colID+"').css({ "+
                  "'background-image': 'url("+tabletRowBgImg+")', "+
                  "'background-repeat': '"+drbgImgOpsRepT+"', "+
                  "'background-attachment': '"+tabletRowBgFixed+"', "+
                  tabletBgImgPos +
                  tabletBgImgSize+
                "});"+
              "}"+
              "});"+
              " "+
              "var currentVPS = jQuery('.currentViewPortSize').val();"+
              "if ( currentVPS == 'rbt-m' ) { "+
                "jQuery('#"+colID+"').css({ "+
                  "'background-image': 'url("+tabletRowBgImg+")', "+
                  "'background-repeat': '"+drbgImgOpsRepT+"', "+
                  "'background-attachment': '"+tabletRowBgFixed+"', "+
                  tabletBgImgPos +
                  tabletBgImgSize+
                "});"+
              "}"+
              " "+
            "</script>";




            // mobile
            if (drbgImgOps['posM'] == 'custom') {
              mobileBgImgPos = "'background-position-x': '"+drbgImgOps['xPosM']+drbgImgOps['xPosUM']+ "', " + "'background-position-y': '"+drbgImgOps['yPosM']+drbgImgOps['yPosUM']+ "', ";
            }else if(drbgImgOps['posT'] == ''){
              mobileBgImgPos = tabletBgImgPos;
            }else{
              mobileBgImgPos = "'background-position': '"+drbgImgOps['posM']+"', ";
            }

            if (drbgImgOpsRepM == '' || drbgImgOpsRepM == 'default') { drbgImgOpsRepM = drbgImgOpsRepT; }

            if (drbgImgOps['sizeM'] == 'custom') {
              mobileBgImgSize = "'background-size': '"+drbgImgOps['cWidM']+drbgImgOps['widM']+"', ";
            }else if(drbgImgOps['sizeM'] == ''){
              mobileBgImgSize = tabletBgImgSize;
            }else{
              mobileBgImgSize = "'background-size': '"+drbgImgOps['sizeM']+"', ";
            }
            
            var currColmobileBackgroundOps  = ''+
            "<script>"+
              "jQuery('.responsiveBtn').live('click',function(){"+
              "if (jQuery(this).hasClass('rbt-s') ) { "+
                "jQuery('#"+colID+"').css({ "+
                  "'background-image': 'url("+mobileRowBgImg+")', "+
                  "'background-repeat': '"+drbgImgOpsRepM+"', "+
                  "'background-attachment': '"+mobileRowBgFixed+"', "+
                  mobileBgImgPos +
                  mobileBgImgSize+
                "});"+
              "}"+
              "});"+
              " "+
              "var currentVPS = jQuery('.currentViewPortSize').val();"+
              "if ( currentVPS == 'rbt-s' ) { "+
                "jQuery('#"+colID+"').css({ "+
                  "'background-image': 'url("+mobileRowBgImg+")', "+
                  "'background-repeat': '"+drbgImgOpsRepM+"', "+
                  "'background-attachment': '"+mobileRowBgFixed+"', "+
                  mobileBgImgPos +
                  mobileBgImgSize+
                "});"+
              "}"+
              " "+
            "</script>";


          }
        }
          

        colBgResScripts = currColDefaultBackgroundOps + currColtabletBackgroundOps + currColmobileBackgroundOps;



        
        var currColResponsiveScriptsTrigger = ' <div id="columnResponsiveScripts" style="display:none;">  \n ' + currColmarginTablet + ' \n ' + currColmarginMobile + ' \n ' +currColmarginDefault + colBgResScripts +  colBorderResponsiveScripts + '</div>';

        thisColHoverStyleTag = '<div id="columnStyleTag" style="display:none;"> <style> '+thisColHoverOption+' </style> </div> ';



        var colEditBtn = "<div class='editColumn editColBtnTop'  data-col_id="+this_column+" data-overlay_id="+this_column+" ><span class='dashicons dashicons-edit' style='color:#fff;' data-col_id="+this_column+" data-overlay_id="+this_column+"></span></div>    <div id='flipColumns' class='flipColumns editColBtnTop' style='margin-left: 40px;background:#607d8b; ' data-col_id="+this_column+" data-colIndex="+i+" data-overlay_id="+this_column+" title='Flip with Next Column' > <i class='fa fa-exchange' data-col_id="+this_column+" data-colIndex="+i+" ></i> </div>              <div id='duplicateColumns' class='duplicateColumns editColBtnTop' style='margin-left: 74px;background:#607d8b; ' data-col_id="+this_column+" data-colIndex="+i+" data-overlay_id="+this_column+" title='Duplicate Column' > <span class='dashicons dashicons-admin-page' data-col_id="+this_column+" data-colIndex="+i+" ></span> </div>             <div id='deleteColumns' class='deleteColumns editColBtnTop' style='margin-left: 111px;background:#607d8b; ' data-col_id="+this_column+" data-colIndex="+i+" data-overlay_id="+this_column+" title='Delete Column' > <span class='dashicons dashicons-trash' data-col_id="+this_column+" data-colIndex="+i+" ></span> </div>                   <div id='copyColumnOps' class='copyColumnOps editColBtnTop' style='margin-left: 148px;background:#607d8b; ' data-col_id="+this_column+" data-overlay_id="+this_column+" title='Copy Column Options' > Copy </div>          <div id='pasteColumnOps' class='pasteColumnOps editColBtnTop' style='margin-left: 198px; background:#607d8b; display:none;' data-col_id="+this_column+" data-overlay_id="+this_column+" data-colIndex="+i+" title='Paste Column Options'> Paste </div>  "+currColResponsiveScriptsTrigger + thisColHoverStyleTag +"</div>";
        
        var colControls = 
          '<div id="ulpb_column_controls" class="ulpb_column_controls  ulpb_column_controls'+this_column+'" style="display:none;" > '+

            '<div id="edit_form_closeCol" style="display:none;" class="btn-red btn"><span class="dashicons dashicons-no-alt"></span></div> '+

            '<div id="editColumnSave" style="display:none;" data-col_id='+this_column+' ></div>'+

            '<div id="editColumnSaveWidget" style="display:none;" data-col_id='+this_column+' ></div>'+

            '<div id="editColumnSaveVisible" data-col_id='+this_column+' ><span class="dashicons dashicons-arrow-left editSaveVisibleIcon"  data-col_id='+this_column+'></span></div><br>'+

          '</div> \n'
        ;
        
        $('#rowColCont-'+rowID ).append('<div class="column '+this_column+' '+columnCustomClass+' " id='+rowID+'-'+this_column+' data-col_id='+this_column+' style="width:' + colWidth +colWidthUnit+';  min-height:'+rowHeight+rowHeightUnit+'; '+colBackgroundOptions+' '+this_column_margins+'  '+this_col_shadow +'  '+columnCSS+'"> <div id="WidthSave" class="pb_hidden"></div> <div id="RowHeightSave" class="pb_hidden"></div>  <div class="wdgt-dropped" style="display:none;" data-this_col_id='+this_column+'></div>  <div class="wdgt-colChange" style="display:none;" data-this_col_id='+this_column+'></div> '+colEditBtn+'</div>');


        $('#'+rowID+'-'+this_column).mouseenter(function(ev){
          $(this).children('.editColBtnTop').css('display','block');
          $(this).css('outline','2px solid #2B87DA');
          if (pageBuilderApp.copiedColOps == '') {
            $('.pasteColumnOps').css('display','none');
          }
        });
        $('#'+rowID+'-'+this_column).mouseleave(function(){
          $('.editColBtnTop').css('display','none');
          $(this).css('outline','none');
        });

        // Column Widgets
        var this_column_widgets = thisColumnModelData['colWidgets'];
        var this_column_widgets_array = _.values(this_column_widgets);

        var thisColFontsToLoad = [];

        for (var j = 0; j < this_column_widgets_array.length; j++) {
          this_column_widgets_array_C = this_column_widgets_array[j];
          var thisRenderredWidget = completeWidgetRender(this_column_widgets_array_C, j, this_column, rowID, thisColFontsToLoad);

          $('section[RowID="'+rowID+'"] '+'.'+this_column).append(thisRenderredWidget['RenderredWidgetHtmlDefault']);

          $('#'+rowID+'-'+this_column + ' .widget-Draggable').mouseenter(function(ev){
            $(this).children('.widgetHandle').css('display','block');
            if (pageBuilderApp.copiedWidgOps == '') {
              jQuery('.widgetPasteHandle').css('display','none');
            }

          });
          $('#'+rowID+'-'+this_column + ' .widget-Draggable').mouseleave(function(){ 
            $('.widgetHandle').css('display','none'); 
          });

          $('.widget-Draggable').draggable({
                helper: function(event){
                  var thisELE  = $(event.currentTarget);
                  
                  var elMarigns  = thisELE.css('margin-top') + ' ' + thisELE.css('margin-right') + ' ' +thisELE.css('margin-bottom') + ' ' +thisELE.css('margin-left');

                  return $("<div class='widgetDragHelper' style='margin:"+elMarigns+"; padding: 25px; background: #333; border-radius: 100px;'> <span class='dashicons dashicons-move' style='color:#fff;' title='Move'></span> </div>");
                }, cursor: "move", appendTo: "#container", handle:'.widgetMoveHandle',
                start: function(event,ui){
                  $(event.target).attr('style','display:none;');
                  $('.isDroppedOnDroppable').val('false');
                  $(this).children('.draggableWidgets').click();
                },
                stop: function(event,ui){
                  $('.droppableBelowWidget').hide(250);

                  var isDroppedOnDroppable = $('.isDroppedOnDroppable').val();

                  if (isDroppedOnDroppable != 'true') {
                    $(event.target).attr('style','display:block;');
                  }
                },
          });

        } // Widget Loop

        if (this_column_widgets_array.length == 0) {
        $('section[RowID="'+rowID+'"] '+'.'+this_column).append("<div class='editColumn emptyColumnIcon' data-col_id="+this_column+" data-overlay_id="+this_column+" title='Add Widgets'><span class='dashicons dashicons-plus' style='color:#fff; font-size:2em;' data-col_id="+this_column+" data-overlay_id="+this_column+"></span></div></div> <div id='WidthSave' class='pb_hidden'></div>");
        }

        thisColFontsToLoadSeparatedValue = 'Allerta';
        for(var w = 0; w < thisColFontsToLoad.length; w++){
          thisColFontsToLoadSeparatedNewValue = thisColFontsToLoad[w];

          if (thisColFontsToLoadSeparatedValue !== 'Select' || thisColFontsToLoadSeparatedValue !== 'Arial' || thisColFontsToLoadSeparatedValue !== 'Arial Black' || thisColFontsToLoadSeparatedValue !== 'sans-serif' || thisColFontsToLoadSeparatedValue !== 'Helvetica' || thisColFontsToLoadSeparatedValue !== 'Serif' || thisColFontsToLoadSeparatedValue !== 'Arial' || thisColFontsToLoadSeparatedValue !== 'Tahoma' || thisColFontsToLoadSeparatedValue !== 'Verdana' || thisColFontsToLoadSeparatedValue !== 'Monaco' || thisColFontsToLoadSeparatedValue !== 'select') {
            filteredFontFamilyName = '|' +thisColFontsToLoadSeparatedNewValue;
          }else{
            filteredFontFamilyName = '';
          }

          thisColFontsToLoadSeparatedValue = thisColFontsToLoadSeparatedValue + filteredFontFamilyName;
        }

        $('section[RowID="'+rowID+'"] '+'.'+this_column).append('<link rel="stylesheet"href="https://fonts.googleapis.com/css?family='+thisColFontsToLoadSeparatedValue+'">');

        colControlsArray.push(colControls);

      } // column loop

      colControlsArrayAllValues = '';
      for(var w = 0; w < colControlsArray.length; w++){
          colControlsArraySeparatedNewValue = colControlsArray[w];

          colControlsArrayAllValues = colControlsArrayAllValues + '\n' +colControlsArraySeparatedNewValue;
        }

        $(this.el).append(colControlsArrayAllValues);


      if (rowColumns < 1) {
        rowWithNoColumnContainer = $('.rowWithNoColumnContainer').html();

        $(this.el).append(rowWithNoColumnContainer);

      }


      var pbContainer = $('#container');
      var pbWrapperWidth = $('#container').width();
      var prevDoppableBgColor = '';
        $('.column').droppable({
          accept: ".widget-Draggable",
          snap:'.column',
          drop: function(event,ui){
            //$(ui.draggable).click();
            // $(".widget-Draggable").trigger("dragstop");
            var curr_droppable = $(this).attr('id');
            $('.widgetDroppedAtIndex').val('');
            $('.isDroppedOnDroppable').val('true');
           $('#'+curr_droppable +' .wdgt-dropped').click();
          },
          over: function(){
            var curr_droppable = $(this).attr('id');
            prevDoppableBgColor = $('#'+curr_droppable).css('background');
            $(this).css('background','rgba(224, 241, 255, 0.85)');
          },
          out: function(){
            var curr_droppable = $(this).attr('id');
            $('#'+curr_droppable).css('background',prevDoppableBgColor);
          }

        } );

        $('.pb_widgetr').droppable({
          accept: ".widget-Draggable, .wdt-draggable",
          snap:'.droppableBelowWidget',
          greedy: true,
          drop: function(event,ui){
            //$(ui.draggable).click();
            // $(".widget-Draggable").trigger("dragstop");
            var curr_droppable = $(this).attr('data-targetColId');
            var thisDroppableWidgetIndex = $(this).attr('data-widgetIndex');
            $('.widgetDroppedAtIndex').val(thisDroppableWidgetIndex);
              $('.isDroppedOnDroppable').val('true');

           $('#'+curr_droppable +' .wdgt-dropped').click();
          },
          over: function(){
            $(this).children('.droppableBelowWidget').css('display','block');
            $(this).children('.droppableBelowWidget').css('background','rgba(213, 249, 215, 0.85)');
          },
          out: function(){
            $(this).children('.droppableBelowWidget').css('display','none');
          }

        } );

        

       

        var PbWrapperWidth = $('#container').width();
      $('.column').resizable({
        containment: '#container',
        handles: 'e,s',
        start: function (event, ui) {
          ui.element.css('min-height', 'auto');
          resizedAxis = ui.element.data('ui-resizable').axis;
          if (resizedAxis == 'e') {
            this.widthOfNextEl = ui.originalSize.width + ui.element.next().innerWidth();
            $(this).prepend('<div style="text-align:center; color:#fff; padding:5px;" id="thisElWidth"> '+$(this).width() +' </div>');

            $( ui.element.next() ).prepend('<div style="text-align:center; color:#fff; padding:5px;" id="nextElWidth"> '+$( ui.element.next() ).width() +' </div>');
          }
            
        },
        resize: function (event, ui) {
          resizedAxis = ui.element.data('ui-resizable').axis;
          if (resizedAxis == 'e') {
            var currentPbWrapperWidth = ui.element.parent().width();
            ui.element.next().width( (this.widthOfNextEl - ui.size.width) - 1 );
            ui.element.children('.overlay').width(ui.size.width);
            ui.element.next().children('.overlay').width(ui.element.next().width());

            $(this).children('#thisElWidth').html( Math.round(ui.element.outerWidth()/ currentPbWrapperWidth * 100) + '<span>%</span>'  );
            var nextElWidthOutput = ui.element.next().width()/ currentPbWrapperWidth * 100;
            ui.element.next().children('#nextElWidth').html( Math.round(nextElWidthOutput ) + '<span>%</span>' );
          }
          if (resizedAxis == 's') {

          }
            
        },
        stop: function(event, ui) {
          resizedAxis = ui.element.data('ui-resizable').axis;

          if (resizedAxis == 'e') {
            var currentPbWrapperWidth = ui.element.parent().width();
            var colPercentageWidth = ui.element.outerWidth()/ currentPbWrapperWidth * 100;
            ui.element.css('width', colPercentageWidth + '%');
            var nextCol = ui.element.next();
            var nextColPercentWidth= nextCol.outerWidth()/ currentPbWrapperWidth * 100;
            nextCol.css('width', nextColPercentWidth + '%');
            //console.log(currentPbWrapperWidth);
            ui.element.children('.overlay').css('width','100' + '%');
            ui.element.next().children('.overlay').css('width','100' + '%');
            var thisResizedColID = $(ui.element).attr('data-col_id');
            $('.currentResizedRowColTarget').val(thisResizedColID);
            nextResizedColID = thisResizedColID.replace(/\D/g,'');
            $('.currentResizedRowColTargetNext').val(nextResizedColID);
            $(ui.element).children('#WidthSave').trigger('click');
            
            var curreViewportS = $('.currentViewPortSize').val();

            jQuery('.edit_column').hide(300);
            jQuery('.ulpb_column_controls').hide();

            $(this).children('#thisElWidth').remove();
            ui.element.next().children('#nextElWidth').remove();
          }
          if (resizedAxis == 's') {
            var currentPbWrapperHeight = $(window).height();
            var elHeight = ui.element.height();
            var colPercentageHeight = ui.element.height()/ currentPbWrapperHeight * 100;

            if (rowHeightUnit == 'px') {}
              ui.element.css('height', elHeight + 'px');

            if (curreViewportS == 'rbt-l') {
                ui.element.css('height', elHeight + 'px');
            }
            if (curreViewportS == 'rbt-m') {
                ui.element.css('height', elHeight + 'px');
            }
            if (curreViewportS == 'rbt-s') {
                ui.element.css('height', elHeight + 'px');
            }
            $('.currentResizedRowHeight').val(elHeight);

            $(ui.element).children('#RowHeightSave').trigger('click');

          }
            
        }
      });

      // Row and column buttons
      displayGButton = 'none';
      if (isGlobalRowActive == "true") {
        displayGButton = 'inline-block';
      }

      $(this.el).append(
        '<div style=" display:block;clear:left;position: relative;z-index:99;bottom: 4px;right: 5px;" class="rowAllEditBtnContainer" >'+
          '<div id="rowDelete" class="row-btn btn-red btn" title="Delete Row" ><span class="dashicons dashicons-trash"></span></div> '+
          '<div id="rowEdit" class="row-btn btn"  title="Edit Row"> <span class="dashicons dashicons-edit"></span></div> '+
          '<div id="rowDuplicate" class="row-btn btn" title="Duplicate"> <span class="dashicons dashicons-admin-page"></span></div> '+
          '<div id="setGlobalRow" style="background:#F0D53D;diplay:none;" class="row-btn btn globalRowBtn" title="Set Global"> <span class="dashicons dashicons-networking"></span></div> '+
          '<div class="pbHandle row-btn btn" style="background: rgb(45, 60, 60) !important;"><span class="dashicons dashicons-move" title="Move"></span></div>    '+
          '<div class="addNewRowSection row-btn btn" style="background:#2196f3 !important;"><span class="dashicons dashicons-plus" title="Add New Section Options"></span></div>   '+
          '<div id="copySectionOps" class="row-btn btn" style="background:#607D8B  !important;" title="Copy Options"> <span>Copy</span> </div>  '+
          '<div id="pasteSectionOps" class="row-btn btn pasteSectionOps" style="background:#607D8B  !important; display:none;" title="Paste Section Options"> <span>Paste</span> </div>  '+
        '</div>'
      );
      
        $(this.el).append(
          '<div class="newRowBtnContainer" >'+
            '<div class="newRowBtnContainerSections">'+
              '<div class="addNewRow  row-section-btn" style="background:#5AB1F7;" > ADD NEW SECTION</div>'+
            '</div>'+
            '<div class="newRowBtnContainerSections" style="display:'+displayGButton+';">'+
              '<div class="addNewGlobalRow  row-section-btn" style="background:#F1D204; " > INSERT GLOBAL ROW</div>'+
            '</div>'+
            '<div class="newRowBtnContainerSections"  >'+
              '<div class="addNewRowBlock  row-section-btn" style="background:#4CAF50; " > INSERT DESIGN BLOCK</div>   </div>'+
            '</div>'+
          '</div>'
        );


        $('li[data-model-cid="'+rowCID+'"] .addNewRowSection').click( function(){

          $('li[data-model-cid="'+rowCID+'"] .newRowBtnContainer').css('display','block');
        } );

      $('li[data-model-cid="'+rowCID+'"]').mouseenter(function(){
        $('li[data-model-cid="'+rowCID+'"] .row-btn').css('display','block');

        if (isGlobalRowActive !== 'true') {
          $('.globalRowBtn').css('display','none');
        }
        if (pageBuilderApp.copiedSecOps == '') {
          $('.pasteSectionOps').css('display','none');
        }

        if (thisPostType == 'ulpb_global_rows') {
          jQuery('.addNewRowSection').css('display','none');
          jQuery('#rowDuplicate').css('display','none');
          jQuery('#setGlobalRow').css('display','none');
        }
        
      });
      $('.row').mouseleave(function(){

       $('.row-btn').css('display','none');
       $('.newRowBtnContainer').css('display','none');
      });

      // Save the current model
      return this;
    },
    widgetDrag: function(ev){

      var this_row = $(ev.target).attr('data-widg_row_id');
      var this_column = $(ev.target).attr('data-wid_col_id');
      var this_widget = $(ev.target).attr('data-widget_id');
      var thisColumnData = this.model.get(this_column);
      //console.log($(ev.target) );
      var this_column_widgets = thisColumnData['colWidgets'];
      var WidgetDraggedAttr = this_column_widgets[this_widget];

      $('.widgetDraggedRowId').val(this_row);
      $('.widgetDraggedColIndex').val(this_column);
      $('.widgetDraggedIndex').val(this_widget);

      /*
     this_column_widgets.splice(this_widget, 1);
      
      var thisColumnModelData = this.model.get(this_column);
        var this_column_widgets = thisColumnModelData['colWidgets'];
        var this_column_options = thisColumnModelData['columnOptions'];

        this.model.set({
          [this_column] : {
            colWidgets: this_column_widgets,
            columnOptions: this_column_options,
          }
        });
        */
      
      //$(this.el).html('');
      //$('.edit_column').slideUp();
      //$('#ulpb_column_controls').remove();


     // this.render();

      $('.draggedWidgetAttributes').val(JSON.stringify(WidgetDraggedAttr));
       thisColumnData = this.model.get(this_column);
      
    },
    widgetDropped: function(ev){

      var widgetDroppedAttributes = $('.draggedWidgetAttributes').val();
      var widgetDroppedAtIndex = $('.widgetDroppedAtIndex').val()
      var rowID =  this.model.get('rowID');
      var this_column = $(ev.target).attr('data-this_col_id');
      var thisColumnData = this.model.get(this_column);
      var this_column_widgets = thisColumnData['colWidgets'];
      if (widgetDroppedAttributes != '' && typeof(widgetDroppedAttributes) !='undefined' ) {
        if (this_column_widgets == 0) {
          this_column_widgets = [];
          this_column_widgets.push(JSON.parse(widgetDroppedAttributes) );
        } else if(typeof(this_column_widgets) == 'undefined' ) {
          this_column_widgets = [];
          this_column_widgets.push(JSON.parse(widgetDroppedAttributes) );
        } else{
            if (widgetDroppedAtIndex == '') {
              this_column_widgets.push(JSON.parse(widgetDroppedAttributes) );
            } else{
              this_column_widgets.splice(widgetDroppedAtIndex, 0, JSON.parse(widgetDroppedAttributes));
            }
          
         
         // this_column_widgets.push(JSON.parse(widgetDroppedAttributes) );
        }

        
      }

      $('.widgetDroppedRowId').val( rowID );
      $('.widgetDroppedColIndex').val( this_column );
      $('.widgetDroppedIndex').val( widgetDroppedAtIndex );
      var dragged_widg_row = $('.widgetDraggedRowId').val();
      var dragged_widg_col = $('.widgetDraggedColIndex').val();
      var dragged_widg_widg = $('.widgetDraggedIndex').val();

      $(".draggedRemove_"+dragged_widg_row+"_"+dragged_widg_col+"_widg_"+dragged_widg_widg+" ").trigger('click');
      //console.log(this_column_widgets);
      //console.log(WidgetDraggedAttr);
      
      //var widgets = pageBuilderApp.widgetList.toJSON();
      var thisColumnModelData = this.model.get(this_column);
      var this_column_options = thisColumnModelData['columnOptions'];

      this.model.set({
        [this_column] : {
          colWidgets: this_column_widgets,
          columnOptions: this_column_options,
        }
      });
      
      
      pageBuilderApp.widgetList.reset();
      pageBuilderApp.widgetList.add(this_column_widgets);

      var specialAction = 'add'; 
      if (pageBuilderApp.isWidgetDeletAction == true) {
        var specialAction = 'delete';
      }

      if (pageBuilderApp.isDefaultWidget != true) {
        var specialAction = 'dragAdd';
      }

      if (widgetDroppedAtIndex == '') {
        widgetDroppedAtIndex = $('#'+rowID+'-'+this_column + ' .widget-Draggable').length;
      }

      if (pageBuilderApp.dontSendToStack != true) {
        var thisChangeRedoProps = {
          changeModelType : 'widgetSpecialAction',
          specialAction:specialAction,
          thisModelElId:rowID,
          thisColId:this_column,
          thisWidgetId:parseInt(widgetDroppedAtIndex),
          changedOpValue:widgetDroppedAttributes,
        }

        sendDataBackToUndoStack(thisChangeRedoProps);
      }
      
      pageBuilderApp.isDefaultWidget = false;
      pageBuilderApp.dontSendToStack = false;
      pageBuilderApp.isWidgetDeletAction = false;
      $(this.el).html('');
      //$('.edit_column').slideUp();
      //$('#ulpb_column_controls').remove();
      this.render();
      jQuery('.edit_column').hide(300);
      hideWidgetOpsPanel();
      resizeWindowClose();

      $('.isChagesMade').val('true');
    },
    widgetColChange: function(ev){
      $(this.el).html('');
      this.render();
    },
    widgetDragRemove: function(ev){
      var droppedOnRow = $('.widgetDroppedRowId').val();
      var widgetDroppedColIndex = $('.widgetDroppedColIndex').val();
      var widgetDroppedIndex = $('.widgetDroppedIndex').val();
      var this_row = $('.widgetDraggedRowId').val();
      var this_column = $('.widgetDraggedColIndex').val();
      var this_widget = parseInt($('.widgetDraggedIndex').val() );
      

      var thisColumnData = this.model.get(this_column);
        
      var this_column_widgets = thisColumnData['colWidgets'];
      if (widgetDroppedIndex == '') {
        widgetDroppedIndex = this_widget;
      }
      if (droppedOnRow == this_row && widgetDroppedColIndex == this_column) {
        if (widgetDroppedIndex < this_widget ) {
          var removedWidgetAttrs = this_column_widgets.splice((this_widget+1), 1);
        }
        if (widgetDroppedIndex > this_widget) {
          if (this_widget != 0) {
            var removedWidgetAttrs = this_column_widgets.splice(this_widget, 1);
          }else{
            var removedWidgetAttrs = this_column_widgets.splice(this_widget, 1);
          }
        }
        if (widgetDroppedIndex == this_widget ) {
          var removedWidgetAttrs = this_column_widgets.splice(this_widget, 1);
        }
        
      }else{
        var removedWidgetAttrs = this_column_widgets.splice(this_widget, 1);
      }
      
      
      var thisColumnModelData = this.model.get(this_column);
      var this_column_widgets = thisColumnModelData['colWidgets'];
      var this_column_options = thisColumnModelData['columnOptions'];

      this.model.set({
        [this_column] : {
          colWidgets: this_column_widgets,
          columnOptions: this_column_options,
        }
      });

      var specialAction = 'dragDelete';
      var thisChangeRedoProps = {
        changeModelType : 'widgetSpecialAction',
        specialAction:specialAction,
        thisModelElId:this_row,
        thisColId:this_column,
        thisWidgetId:this_widget,
        changedOpValue:JSON.stringify(removedWidgetAttrs[0]),
      }

      sendDataBackToUndoStack(thisChangeRedoProps);

      $('.widgetDraggedRowId').val('');
      $('.widgetDraggedColIndex').val('');
      $('.widgetDraggedIndex').val('');
      $('.widgetDroppedRowId').val( '' );
      $('.widgetDroppedColIndex').val( '' );
      $('.widgetDroppedIndex').val('')
      $(this.el).html('');
      //$('.edit_column').slideUp();
      //$('#ulpb_column_controls').remove();
      this.render();
    },
    deleteWidget: function(ev){

      thisWidgetEl = $(ev.target );

      thisWidgetParentColID = thisWidgetEl.attr('data-parentwidgetid');
      thisWidgetIndex = parseInt( thisWidgetEl.attr('data-widget_id') );

      // $('#'+thisWidgetParentColID).children('.editColumn').click();
      
      pageBuilderApp.currentlyEditedColId = thisWidgetParentColID;
      pageBuilderApp.currentlyEditedWidgId = thisWidgetIndex;
      pageBuilderApp.currentlyEditedThisCol = thisWidgetEl.attr('data-wid_col_id');
      pageBuilderApp.currentlyEditedThisRow = this.model.get('rowID');

      jQuery('.ColcurrentEditableRowID').val(pageBuilderApp.currentlyEditedThisRow);
      jQuery('.currentEditableColId').val(pageBuilderApp.currentlyEditedThisCol);

      this.loadWidgetsInColumn();

      $('#widgets li:nth-child('+(thisWidgetIndex+1)+')').children().children('.wdt-edit-controls').children('#widgetDelete').click();

      jQuery('.edit_column').hide(0);
      jQuery('.ulpb_column_controls').hide();
      resizeWindowClose();
    
    },
    deleteWidgetFromList: function(){

          var this_column = $('.widgDeleteColIndex').val();
          var this_widget = $('.widgDeleteIndex').val();
          if (this_column != '' && this_widget != '') {
            var thisColumnData = this.model.get(this_column);
            var this_column_widgets = thisColumnData['colWidgets'];
            var WidgetDraggedAttr = this_column_widgets[this_widget];
            this_column_widgets.splice(this_widget, 1);

              var thisColumnModelData = this.model.get(this_column);
              var this_column_widgets = thisColumnModelData['colWidgets'];
              var this_column_options = thisColumnModelData['columnOptions'];

              this.model.set({
                [this_column] : {
                  colWidgets: this_column_widgets,
                  columnOptions: this_column_options,
                }
              });
              hideWidgetOpsPanel();
            jQuery('.edit_column').hide(300);
            
            $(this.el).html('');
            this.render();
            
            $('.isChagesMade').val('true');
          }
          return;
    },
    duplicateWidget: function(ev){
      var this_column = $(ev.target).attr('data-wid_col_id');
      var this_widget = $(ev.target).attr('data-widget_id');
      var thisColumnData = this.model.get(this_column);
      var rowID = this.model.get('rowID');
      var this_column_widgets = thisColumnData['colWidgets'];
      var WidgetDraggedAttr = jQuery.extend(true, {}, this_column_widgets[this_widget]);
      var currentItemIndex = this_column_widgets.indexOf(this_column_widgets[this_widget]);
      this_column_widgets.splice(currentItemIndex, 0, WidgetDraggedAttr);

      var thisColumnModelData = this.model.get(this_column);
      var this_column_widgets = thisColumnModelData['colWidgets'];
      var this_column_options = thisColumnModelData['columnOptions'];

      this.model.set({
        [this_column] : {
          colWidgets: this_column_widgets,
          columnOptions: this_column_options,
        }
      });


      var thisChangeRedoProps = {
          changeModelType : 'widgetSpecialAction',
          specialAction:'add',
          thisModelElId:rowID,
          thisColId:this_column,
          thisWidgetId:currentItemIndex,
          changedOpValue:JSON.stringify(WidgetDraggedAttr),
      }
      sendDataBackToUndoStack(thisChangeRedoProps);
      
      hideWidgetOpsPanel();
      jQuery('.edit_column').hide(0);
      pageBuilderApp.widgetList.reset();
      pageBuilderApp.widgetList.add(this_column_widgets);
      $(this.el).html('');
      this.render();
      $('.isChagesMade').val('true');
    },
    editWidgetTrigger: function(ev){

      thisWidgetEl = $(ev.currentTarget );


      editWidgetTrigger = true;

      

      if ( $(ev.target).is('span') ) {
        isEditWidgetButtonClicked = $(ev.target).parent().hasClass('widgetHandle');
        draggableWidgets = $(ev.target).parent().hasClass('draggableWidgets');
        wdgtdragRemove = $(ev.target).parent().hasClass('wdgt-dragRemove');
      }else{
        isEditWidgetButtonClicked = $(ev.target).hasClass('widgetHandle');
        draggableWidgets = $(ev.target).hasClass('draggableWidgets');
        wdgtdragRemove = $(ev.target).hasClass('wdgt-dragRemove');
      }

      
      if (draggableWidgets == true || wdgtdragRemove == true) {
        editWidgetTrigger = false;
      }


      if ($(ev.currentTarget).find('.liveTextActive').length > 0){
        editWidgetTrigger = false;
      }
      if (isEditWidgetButtonClicked == true ){
        editWidgetTrigger = false;
      }

      
      if ( pageBuilderApp.currentlyEditingWidget == thisWidgetEl.attr('id') ) {
        editWidgetTrigger = false;
        if ( $('.columnWidgetPopup:visible').length < 1  ) {
          editWidgetTrigger = true;
        }
      }
    


      if(editWidgetTrigger == true){

        thisWidgetParentColID = thisWidgetEl.attr('data-parentwidgetid');
        thisWidgetIndex = parseInt( thisWidgetEl.attr('data-widget_id') );

        pageBuilderApp.currentlyEditedColId = thisWidgetParentColID;
        pageBuilderApp.currentlyEditedWidgId = thisWidgetIndex;
        pageBuilderApp.currentlyEditedThisCol = thisWidgetEl.attr('data-wid_col_id');
        pageBuilderApp.currentlyEditedThisRow = this.model.get('rowID');

        jQuery('.ColcurrentEditableRowID').val(pageBuilderApp.currentlyEditedThisRow);
        jQuery('.currentEditableColId').val(pageBuilderApp.currentlyEditedThisCol);

        this.loadWidgetsInColumn();

        // $('#'+thisWidgetParentColID).children('.editColumn').click();

        pageBuilderApp.currentlyEditingWidget = '';
        pageBuilderApp.currentlyEditingWidget = thisWidgetEl.attr('id');
          
        $('#widgets li:nth-child('+(thisWidgetIndex+1)+')').children().children('.wdt-edit-controls').children('#widgetEdit').click();


        $('.animateWidgetId').val(thisWidgetIndex);
        $('.pageops_modal').hide(300);
        $('.insertRowBlock').hide(300);
      }

        
    },
    loadWidgetsInColumn: function(){

      var thisColumnData = this.model.get(pageBuilderApp.currentlyEditedThisCol);
      var this_column_widgets = thisColumnData['colWidgets'];

      pageBuilderApp.widgetList.reset();
      if (this_column_widgets) {
        pageBuilderApp.widgetList.add(this_column_widgets);
      }
      $('.checkIfWidgetsAreLoadedInColumn').val('true');

    },
    editWidget: function(ev){
      thisWidgetEl = $(ev.target );

      thisWidgetParentColID = thisWidgetEl.attr('data-parentwidgetid');
      thisWidgetIndex = parseInt( thisWidgetEl.attr('data-widget_id') );

      pageBuilderApp.currentlyEditedColId = thisWidgetParentColID;
      pageBuilderApp.currentlyEditedWidgId = thisWidgetIndex;
      pageBuilderApp.currentlyEditedThisCol = thisWidgetEl.attr('data-wid_col_id');
      pageBuilderApp.currentlyEditedThisRow = this.model.get('rowID');

      jQuery('.ColcurrentEditableRowID').val(pageBuilderApp.currentlyEditedThisRow);
      jQuery('.currentEditableColId').val(pageBuilderApp.currentlyEditedThisCol);

      this.loadWidgetsInColumn();

      // $('#'+thisWidgetParentColID).children('.editColumn').click();
        
      $('#widgets li:nth-child('+(thisWidgetIndex+1)+')').children().children('.wdt-edit-controls').children('#widgetEdit').click();


      $('.animateWidgetId').val(thisWidgetIndex);
      $('.pageops_modal').hide(300);
      $('.insertRowBlock').hide(300);
    },
    copyWidgetOps: function(ev){
      var this_column = $(ev.target).attr('data-wid_col_id');
      var this_widget = $(ev.target).attr('data-widget_id');
      var thisColumnData = this.model.get(this_column);
      var this_column_widgets = thisColumnData['colWidgets'];
      var WidgetCopiedAttr = jQuery.extend(true, {}, this_column_widgets[this_widget]);

      pageBuilderApp.copiedWidgOps = JSON.stringify(WidgetCopiedAttr);
    },
    pasteWidgetOps: function(ev){

      thisWidgetEl = $(ev.target );

      thisWidgetParentColID = thisWidgetEl.attr('data-parentwidgetid');
      thisWidgetIndex = parseInt( thisWidgetEl.attr('data-widget_id') );

      pageBuilderApp.currentlyEditedColId = thisWidgetParentColID;
      pageBuilderApp.currentlyEditedWidgId = thisWidgetIndex;
      pageBuilderApp.currentlyEditedThisCol = thisWidgetEl.attr('data-wid_col_id');
      pageBuilderApp.currentlyEditedThisRow = this.model.get('rowID');

      jQuery('.ColcurrentEditableRowID').val(pageBuilderApp.currentlyEditedThisRow);
      jQuery('.currentEditableColId').val(pageBuilderApp.currentlyEditedThisCol);

      this.loadWidgetsInColumn();

      // $('#'+thisWidgetParentColID).children('.editColumn').click();


      $('#widgets li:nth-child('+(thisWidgetIndex+1)+')').children().children('.wdt-edit-controls').children('#pasteCopiedOptions').click();


    },
    deleteRow: function(){

      $('.deleteRowIndex').val( this.model.get('rowID') );
      var thatThis = this;

      $('.popb_confirm_action_popup').css('display','block');

      $('.popb_confirm_subMessage_row').text("Do you want to delete this Row ? Deleted Items can be restored by clicking on redo button at top bar.");

      $('.confirm_no').click(function(){
        $('.popb_confirm_action_popup').css('display','none');
        $('.deleteRowIndex').val('');
        return;
      });

      $('.confirm_yes').one('click',function(){
        var deleteRowIndex = $('.deleteRowIndex').val();
        rowID = thatThis.model.get('rowID');
        rowCID = thatThis.model.cid;
        modelIndex = pageBuilderApp.rowList.indexOf(thatThis.model);
        modelAttributes = thatThis.model.clone();
        if (deleteRowIndex != '') {
          if (rowID == deleteRowIndex) {

            var thisChangeRedoProps = {
              changeModelType : 'rowSpecialAction',
              thisModelCId: rowCID, 
              thisModelElId:rowID,
              specialAction:'delete',
              thisModelVal:modelAttributes,
              thisModelIndex:modelIndex
            }

            sendDataBackToUndoStack(thisChangeRedoProps);

            thatThis.model.destroy();
            $(thatThis.el).remove();
            $('.deleteRowIndex').val('');
          }
        }
        $('.edit_row').hide(300);
        hideWidgetOpsPanel();
        $('.pageops_modal').hide(300);
        $('.insertRowBlock').hide(300);
        $('.edit_column').hide(300);
        $('.ulpb_row_controls').css('display','none');
        $('.popb_confirm_action_popup').css('display','none');
        return;
      });
      return;
    },
    EditRow: function(){
      //console.log(JSON.stringify(this.model) );
      $('.ulpb_column_controls').hide();
      hideWidgetOpsPanel();
      $('.pageops_modal').hide(300);
      $('.edit_column').hide(300);
      $('.insertRowBlock').hide(300);

      var rowID = this.model.get('rowID');
      $('.currentEditingRow').val(rowID);
      var row_height = this.model.get('rowHeight');
      var row_height_unit = this.model.get('rowHeightUnit');
      var row_no_columns = this.model.get('columns');
      var rowData = this.model.get('rowData');

      var row_margin = rowData['margin'];
      var rowMarginTop = row_margin['rowMarginTop'];
      var rowMarginBottom = row_margin['rowMarginBottom'];
      var rowMarginLeft = row_margin['rowMarginLeft'];
      var rowMarginRight = row_margin['rowMarginRight'];

      var row_padding = rowData['padding'];
      var rowPaddingTop = row_padding['rowPaddingTop'];
      var rowPaddingBottom = row_padding['rowPaddingBottom'];
      var rowPaddingLeft = row_padding['rowPaddingLeft'];
      var rowPaddingRight = row_padding['rowPaddingRight'];

      var row_video = '';
      if (typeof(rowData['video']) != "undefined"){
        var row_video = rowData['video'];
        var rowBgVideoEnable = row_video['rowBgVideoEnable'];
        var rowBgVideoLoop = row_video['rowBgVideoLoop'];
        var rowVideoMpfour = row_video['rowVideoMpfour'];
        var rowVideoWebM = row_video['rowVideoWebM'];
        var rowVideoThumb = row_video['rowVideoThumb'];
      }

      if (typeof(rowData['rowGradient']) !== "undefined"){
        var rowGradient = rowData['rowGradient'];

        $.each(rowGradient, function(index,val){
          $('.'+index).val(val);

          if (index == 'rowGradientColorFirst') {
            $('.rowGradientColorFirst').spectrum( 'set', val );
          }
          if (index == 'rowGradientColorSecond') {
            $('.rowGradientColorSecond').spectrum( 'set', val );
          }

          if (index == 'rowGradientLocationFirst') {
            $( ".PoPbrangeSlider" ).slider({
              value:val,
            });
          }

        });

        if (rowGradient['rowGradientType'] == 'linear') {
          $('.radialInput').css('display','none');
          $('.linearInput').css('display','block');
        } else if (rowGradient['rowGradientType'] == 'radial') {
          $('.radialInput').css('display','block');
          $('.linearInput').css('display','none');
        }

      }else{
        $('.rowGradientColorFirst').val('');
        $('.rowGradientLocationFirst').val('');
        $('.rowGradientColorSecond').val('');
        $('.rowGradientLocationSecond').val('');
        $('.rowGradientType').val('');
        $('.rowGradientPosition').val('');
        $('.rowGradientAngle').val('');
      }

      if (typeof(rowData['rowBackgroundType']) !== "undefined"){
        if (rowData['rowBackgroundType'] == 'solid') {
          $("#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeSolid").prop("checked", true);
          $('#defaultRowBgOptions .POPBInputNormalRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOptions .POPBInputNormalRow .popb_tab_content').css('display','none');
          $('#defaultRowBgOptions .POPBInputNormalRow .content_popb_tab_1').css('display','block');
        }
        if (rowData['rowBackgroundType'] == 'gradient') {
          $(".rowBackgroundTypeGradient").prop("checked", true);
          $('#defaultRowBgOptions .POPBInputNormalRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeGradient').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOptions .POPBInputNormalRow .popb_tab_content').css('display','none');
          $('#defaultRowBgOptions .POPBInputNormalRow .content_popb_tab_2').css('display','block');
        }
      }else{
          $("#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeSolid").prop("checked", true);
          $('#defaultRowBgOptions .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOptions .POPBInputNormalRow .rowBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOptions .popb_tab_content').css('display','none');
          $('#defaultRowBgOptions .content_popb_tab_1').css('display','block');
      }

      if (typeof(rowData['rowHoverOptions']) !== "undefined") {
        var rowHoverOptions = rowData['rowHoverOptions'];

        if (rowHoverOptions['rowBgColorHover'] != '' || typeof(rowHoverOptions['rowBgColorHover']) != 'undefined' ) {
          $('.rowBgColorHover').val(rowHoverOptions['rowBgColorHover']);
          $('.rowBgColorHover').spectrum( 'set', rowHoverOptions['rowBgColorHover'] );
        }else{
          $('.rowBgColorHover').val('');
        }
        
        $('.rowHoverTransitionDuration').val(rowHoverOptions['rowHoverTransitionDuration']);
        
        if (rowHoverOptions['rowBackgroundTypeHover'] == 'solid') {
          $(".rowBackgroundTypeSolidHover").prop("checked", true);
          $('.POPBInputHoverRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.rowBackgroundTypeSolidHover').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputHoverRow .popb_tab_content').css('display','none');
          $('.POPBInputHoverRow .content_popb_tab_1').css('display','block');
        }
        if (rowHoverOptions['rowBackgroundTypeHover'] == 'gradient') {
          $(".rowBackgroundTypeGradientHover").prop("checked", true);
          $('.POPBInputHoverRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.rowBackgroundTypeGradientHover').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputHoverRow .popb_tab_content').css('display','none');
          $('.POPBInputHoverRow .content_popb_tab_2').css('display','block');
        }
        if (rowHoverOptions['rowBackgroundTypeHover'] == '' || typeof(rowHoverOptions['rowBackgroundTypeHover']) == 'undefined' ) {
          $(".rowBackgroundTypeSolidHover").prop("checked", false);
          $(".rowBackgroundTypeGradientHover").prop("checked", false);
          $('.POPBInputHoverRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
        }

        var rowGradientHover = rowHoverOptions['rowGradientHover'];
        $.each(rowGradientHover, function(index,val){
          $('.'+index).val(val);

          if (index == 'rowGradientColorFirstHover') {
            $('.rowGradientColorFirstHover').spectrum( 'set', val );
          }
          if (index == 'rowGradientColorSecondHover') {
            $('.rowGradientColorSecondHover').spectrum( 'set', val );
          }
        });

        if (rowGradientHover['rowGradientTypeHover'] == 'linear') {
          $('.radialInputHover').css('display','none');
          $('.linearInputHover').css('display','block');
        } else if (rowGradientHover['rowGradientTypeHover'] == 'radial') {
          $('.radialInputHover').css('display','block');
          $('.linearInputHover').css('display','none');
        }

      }else{
        $('.rowBgColorHover').val('');
        $('.rowGradientColorFirstHover').val('');
        $('.rowGradientLocationFirstHover').val('');
        $('.rowGradientColorSecondHover').val('');
        $('.rowGradientLocationSecondHover').val('');
        $('.rowGradientTypeHover').val('');
        $('.rowGradientPositionHover').val('');
        $('.rowGradientAngleHover').val('');
        $(".rowBackgroundTypeSolidHover").prop("checked", false);
        $(".rowBackgroundTypeGradientHover").prop("checked", false);
      }


      if (typeof(rowData['rowBgOverlayColor']) !== "undefined"){
        $('.rowBgOverlayColor').val(rowData['rowBgOverlayColor']);
        $('.rowBgOverlayColor').spectrum( 'set', rowData['rowBgOverlayColor'] );
      }else{
        $('.rowBgOverlayColor').val('');
      }
      if (typeof(rowData['rowOverlayGradient']) !== "undefined"){
        var rowOverlayGradient = rowData['rowOverlayGradient'];

        $.each(rowOverlayGradient, function(index,val){
          $('.'+index).val(val);

          if (index == 'rowOverlayGradientColorFirst') {
            $('.rowOverlayGradientColorFirst').spectrum( 'set', val );
            $('.rowOverlayGradientColorFirst').val( val );
          }
          if (index == 'rowOverlayGradientColorSecond') {
            $('.rowOverlayGradientColorSecond').spectrum( 'set', val );
            $('.rowOverlayGradientColorSecond').val( val );
          }

        });

        if (rowOverlayGradient['rowOverlayGradientType'] == 'linear') {
          $('.radialInput').css('display','none');
          $('.linearInput').css('display','block');
        } else if (rowOverlayGradient['rowOverlayGradientType'] == 'radial') {
          $('.radialInput').css('display','block');
          $('.linearInput').css('display','none');
        }

      }else{
        $('.rowOverlayGradientColorFirst').val('');
        $('.rowOverlayGradientLocationFirst').val('');
        $('.rowOverlayGradientColorSecond').val('');
        $('.rowOverlayGradientLocationSecond').val('');
        $('.rowOverlayGradientType').val('');
        $('.rowOverlayGradientPosition').val('');
        $('.rowOverlayGradientAngle').val('');
      }

      if (typeof(rowData['rowOverlayBackgroundType']) !== "undefined"){
        if (rowData['rowOverlayBackgroundType'] == 'solid') {
          $("#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .rowOverlayBackgroundTypeSolid").prop("checked", true);
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .rowOverlayBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .popb_tab_content').css('display','none');
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .content_popb_tab_1').css('display','block');
        }
        if (rowData['rowOverlayBackgroundType'] == 'gradient') {
          $("#defaultRowBgOverlayOptions .rowOverlayBackgroundTypeGradient").prop("checked", true);
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOverlayOptions .rowOverlayBackgroundTypeGradient').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .popb_tab_content').css('display','none');
          $('#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .content_popb_tab_2').css('display','block');
        }
      }else{
          $("#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .rowOverlayBackgroundTypeSolid").prop("checked", false);
          $("#defaultRowBgOverlayOptions .POPBInputNormalRowOverlay .rowOverlayBackgroundTypeGradient").prop("checked", false);
          $('#defaultRowBgOverlayOptions .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('#defaultRowBgOverlayOptions .popb_tab_content').css('display','none');
          $('#defaultRowBgOverlayOptions .content_popb_tab_1').css('display','block');
      }

      if (typeof(rowData['rowBackgroundParallax']) !== 'undefined') {
        if (rowData['rowBackgroundParallax'] == 'true') {
          $('.rowBackgroundParallax').val('true');
        }else{
          $('.rowBackgroundParallax').val('false');
        }
      }else{
        $('.rowBackgroundParallax').val('');
      }


      if (typeof(rowData['rowCustomClass']) !== "undefined"){
        $('.rowCustomClass').val(rowData['rowCustomClass']);
      }else{
        $('.rowCustomClass').val('');
      }

      if (typeof(rowData['rowHideOnDesktop']) !== "undefined"){
        $('.rowHideOnDesktop').val(rowData['rowHideOnDesktop']);
        $('.rowHideOnTablet').val(rowData['rowHideOnTablet']);
        $('.rowHideOnMobile').val(rowData['rowHideOnMobile']);
      }else{
        $('.rowHideOnDesktop').val('');
        $('.rowHideOnTablet').val('');
        $('.rowHideOnMobile').val('');
      }

      if (typeof(this.model.get('rowHeightTablet')) !== "undefined"){

        $('.rowHeightTablet').val(this.model.get('rowHeightTablet'));
        $('.rowHeightUnitTablet').val(this.model.get('rowHeightUnitTablet'));
        $('.rowHeightMobile').val(this.model.get('rowHeightMobile'));
        $('.rowHeightUnitMobile').val(this.model.get('rowHeightUnitMobile'));
      }else{
        $('.rowHeightTablet').val('');
        $('.rowHeightUnitTablet').val('');
        $('.rowHeightMobile').val('');
        $('.rowHeightUnitMobile').val('');
      }

      if (typeof(rowData['marginTablet']) !== "undefined"){
        var marginTablet = rowData['marginTablet'];
        $('.rowMarginTopTablet').val(marginTablet['rMTT']);
        $('.rowMarginBottomTablet').val(marginTablet['rMBT']);
        $('.rowMarginLeftTablet').val(marginTablet['rMLT']);
        $('.rowMarginRightTablet').val(marginTablet['rMRT']);
      }else{
        $('.rowMarginTopTablet').val('');
        $('.rowMarginBottomTablet').val('');
        $('.rowMarginLeftTablet').val('');
        $('.rowMarginRightTablet').val('');
      }

      if (typeof(rowData['marginMobile']) !== "undefined"){
        var marginMobile = rowData['marginMobile'];
        $('.rowMarginTopMobile').val(marginMobile['rMTM']);
        $('.rowMarginBottomMobile').val(marginMobile['rMBM']);
        $('.rowMarginLeftMobile').val(marginMobile['rMLM']);
        $('.rowMarginRightMobile').val(marginMobile['rMRM']);
      } else{
        $('.rowMarginTopMobile').val('');
        $('.rowMarginBottomMobile').val('');
        $('.rowMarginLeftMobile').val('');
        $('.rowMarginRightMobile').val('');
      }

      if (typeof(rowData['paddingTablet']) !== "undefined"){
        var paddingTablet = rowData['paddingTablet'];
        $('.rowPaddingTopTablet').val(paddingTablet['rPTT']);
        $('.rowPaddingBottomTablet').val(paddingTablet['rPBT']);
        $('.rowPaddingLeftTablet').val(paddingTablet['rPLT']);
        $('.rowPaddingRightTablet').val(paddingTablet['rPRT']);
      } else{
        $('.rowPaddingTopTablet').val(' ');
        $('.rowPaddingBottomTablet').val(' ');
        $('.rowPaddingLeftTablet').val(' ');
        $('.rowPaddingRightTablet').val(' ');
      }

      if (typeof(rowData['paddingMobile']) !== "undefined"){
        var paddingMobile = rowData['paddingMobile'];
        $('.rowPaddingTopMobile').val(paddingMobile['rPTM']);
        $('.rowPaddingBottomMobile').val(paddingMobile['rPBM']);
        $('.rowPaddingLeftMobile').val(paddingMobile['rPLM']);
        $('.rowPaddingRightMobile').val(paddingMobile['rPRM']);
      } else{
        $('.rowPaddingTopMobile').val('');
        $('.rowPaddingBottomMobile').val('');
        $('.rowPaddingLeftMobile').val('');
        $('.rowPaddingRightMobile').val('');
      }

      if (typeof(rowData['bgSTop']) !== "undefined"){
        var bgSTop = rowData['bgSTop'];
        $('.rbgstType').val(bgSTop['rbgstType']);
        $('.rbgstColor').val(bgSTop['rbgstColor']);
        $('.rbgstWidth').val(bgSTop['rbgstWidth']);
        $('.rbgstWidtht').val(bgSTop['rbgstWidtht']);
        $('.rbgstWidthm').val(bgSTop['rbgstWidthm']);
        $('.rbgstHeight').val(bgSTop['rbgstHeight']);
        $('.rbgstHeightt').val(bgSTop['rbgstHeightt']);
        $('.rbgstHeightm').val(bgSTop['rbgstHeightm']);
        $('.rbgstFlipped').val(bgSTop['rbgstFlipped']);
        $('.rbgstFront').val(bgSTop['rbgstFront']);

        var bgSBottom = rowData['bgSBottom'];
        $('.rbgsbType').val(bgSBottom['rbgsbType']);
        $('.rbgsbColor').val(bgSBottom['rbgsbColor']);
        $('.rbgsbWidth').val(bgSBottom['rbgsbWidth']);
        $('.rbgsbWidtht').val(bgSBottom['rbgsbWidtht']);
        $('.rbgsbWidthm').val(bgSBottom['rbgsbWidthm']);
        $('.rbgsbHeight').val(bgSBottom['rbgsbHeight']);
        $('.rbgsbHeightt').val(bgSBottom['rbgsbHeightt']);
        $('.rbgsbHeightm').val(bgSBottom['rbgsbHeightm']);
        $('.rbgsbFlipped').val(bgSBottom['rbgsbFlipped']);
        $('.rbgsbFront').val(bgSBottom['rbgsbFront']);

        $('.rbgstColor').spectrum( 'set', bgSTop['rbgstColor'] );
        $('.rbgsbColor').spectrum( 'set', bgSBottom['rbgsbColor'] );
      }else{
        $('.rbgstType').val('none');
        $('.rbgstWidth').val('100');
        $('.rbgstHeight').val('200');

        $('.rbgsbType').val('none');
        $('.rbgsbWidth').val('100');
        $('.rbgsbHeight').val('200');
      }

      $('.rowBgImgCustomPositionDiv').css('display','none');
      $('.rowBgImgCustomSizeDiv').css('display','none');
      $('.imageBackgroundOpsRow').css('display','none');

      if (typeof(rowData['bg_imgT']) == 'undefined' ) { rowData['bg_imgT'] = ''; }
      if (typeof(rowData['bg_imgM']) == 'undefined' ) { rowData['bg_imgM'] = ''; }

      if (rowData['bg_img'] != '' || rowData['bg_imgT'] != '' || rowData['bg_imgM'] != '') {
        $('.imageBackgroundOpsRow').css('display','block');      
      }else{
        $('.imageBackgroundOpsRow').css('display','none');
      }

      if (typeof(rowData['bgImgOps']) != "undefined"){
        $.each(rowData['bgImgOps'], function(index,value){
          $('[data-optname="rowData.bgImgOps.'+index+'"]').val(value);

          
          if (index == 'pos' || index == 'posT' || index == 'posM') {
            if (value == 'custom') {
              $('.rowBgImgCustomPositionDiv').css('display','block');
            }
          }

          
          if (index == 'size' || index == 'sizeT' || index == 'sizeM') {
            if (value == 'custom') {
              $('.rowBgImgCustomSizeDiv').css('display','block');
            }
          }
          
        });
      }else{

        $('[data-optname="rowData.bgImgOps.pos"]').val('');
        $('[data-optname="rowData.bgImgOps.posT"]').val('');
        $('[data-optname="rowData.bgImgOps.posM"]').val('');
        $('[data-optname="rowData.bgImgOps.xPos"]').val('');
        $('[data-optname="rowData.bgImgOps.xPosT"]').val('');
        $('[data-optname="rowData.bgImgOps.xPosM"]').val('');
        $('[data-optname="rowData.bgImgOps.xPosU"]').val('px');
        $('[data-optname="rowData.bgImgOps.xPosUT"]').val('px');
        $('[data-optname="rowData.bgImgOps.xPosUM"]').val('px');
        $('[data-optname="rowData.bgImgOps.yPos"]').val('');
        $('[data-optname="rowData.bgImgOps.yPosT"]').val('');
        $('[data-optname="rowData.bgImgOps.yPosM"]').val('');
        $('[data-optname="rowData.bgImgOps.yPosU"]').val('px');
        $('[data-optname="rowData.bgImgOps.yPosUT"]').val('px');
        $('[data-optname="rowData.bgImgOps.yPosUM"]').val('px');
        $('[data-optname="rowData.bgImgOps.rep"]').val('default');
        $('[data-optname="rowData.bgImgOps.repT"]').val('default');
        $('[data-optname="rowData.bgImgOps.repM"]').val('default');
        $('[data-optname="rowData.bgImgOps.size"]').val('');
        $('[data-optname="rowData.bgImgOps.sizeT"]').val('');
        $('[data-optname="rowData.bgImgOps.sizeM"]').val('');
        $('[data-optname="rowData.bgImgOps.cWid"]').val('');
        $('[data-optname="rowData.bgImgOps.cWidT"]').val('');
        $('[data-optname="rowData.bgImgOps.cWidM"]').val('');
        $('[data-optname="rowData.bgImgOps.widU"]').val('px');
        $('[data-optname="rowData.bgImgOps.widUT"]').val('px');
        $('[data-optname="rowData.bgImgOps.widUM"]').val('px');
        $('[data-optname="rowData.bgImgOps.parlxT"]').val('');
        $('[data-optname="rowData.bgImgOps.parlxM"]').val('');
      }
      

      var customStyling = rowData['customStyling'];
      var customJS = rowData['customJS'];

      $('[data-optname="rowData.bg_imgT"]').val('');
      $('[data-optname="rowData.bg_imgM"]').val('');
      $('[data-optname="rowData.conType"]').val('');
      $('[data-optname="rowData.conWidth"]').val('');

      if (rowData['conType'] == 'boxed') {
        $('.boxedWidthOptionContainer').css('display','block');
      }else{
        $('.boxedWidthOptionContainer').css('display','none');
      }

      $('#row_height').val(row_height);
      $('.row_height_unit').val(row_height_unit);
      $('#number_of_columns').val(row_no_columns);
      $('.rowBgImg').val(rowData['bg_img']);
      $('[data-optname="rowData.bg_imgT"]').val(rowData['bg_imgT']);
      $('[data-optname="rowData.bg_imgM"]').val(rowData['bg_imgM']);
      $('[data-optname="rowData.conType"]').val(rowData['conType']);
      $('[data-optname="rowData.conWidth"]').val(rowData['conWidth']);
      $('.rowBgColor').val(rowData['bg_color']);
      $('.rowMarginTop').val(rowMarginTop);
      $('.rowMarginBottom').val(rowMarginBottom);
      $('.rowMarginLeft').val(rowMarginLeft);
      $('.rowMarginRight').val(rowMarginRight);
      $('.rowPaddingTop').val(rowPaddingTop);
      $('.rowPaddingBottom').val(rowPaddingBottom);
      $('.rowPaddingLeft').val(rowPaddingLeft);
      $('.rowPaddingRight').val(rowPaddingRight);
      $('.rowCustomStyling').val(customStyling);
      $('.rowCustomJS').val(customJS);

      $('.rowBgVideoEnable').val(rowBgVideoEnable);
      $('.rowBgVideoLoop').val(rowBgVideoLoop);
      $('.rowVideoMpfour').val(rowVideoMpfour);
      $('.rowVideoWebM').val(rowVideoWebM);
      $('.rowVideoThumb').val(rowVideoThumb);

      if (typeof(row_video['rowVideoType']) != 'undefined') {
        $('.rowVideoType').val(row_video['rowVideoType']);
        $('.rowVideoYtUrl').val(row_video['rowVideoYtUrl']);
        if (row_video['rowVideoType'] == 'mp4') {
          $('.bgrowmp4').css("display",'block');
          $('.bgrowyt').css("display",'none');
        }
        if (row_video['rowVideoType'] == 'yt') {
          $('.bgrowyt').css("display",'block');
          $('.bgrowmp4').css("display",'none');
        }
      }else{
        $('.rowVideoType').val('mp4');
        $('.rowVideoYtUrl').val('');
      }

      $('.rowBgColor').spectrum( 'set', rowData['bg_color'] );

      $('.edit_options_right').append('<div class="column rules"></div>');

      
        
        

      $('.edit_row').show(300);
      if (typeof(resizeWindowOpen) != 'undefined') {
        resizeWindowOpen();
      }
      jQuery('section[rowid="'+rowID+'"]').children('#ulpb_row_controls').show();
      

    },
    updateRow: function(){
      //console.log( 'updateRow triggered');

      //var tua0 = performance.now();

      var updatedRowOpName = pageBuilderApp.changedRowOpName;

      if (typeof(updatedRowOpName) == 'undefined') {
        updatedRowOpName = ' ';
        return;
      }

      var thischangedValue = $('[data-optname="'+updatedRowOpName+'"]').val();

      var thisModelDataAttr = _.clone(this.model.attributes);

      var defaultModelDataAttr = JSON.stringify( _.clone( pageBuilderApp.rowModelDefaultOps ) );

      defaultModelDataAttr = JSON.parse(defaultModelDataAttr);

      var prevRowCols = this.model.get('columns');
      var prevRowHeight = this.model.get('rowHeight');
      var prevRowHeightTablet = this.model.get('rowHeightTablet');
      var prevRowHeightMobile = this.model.get('rowHeightMobile');
      var prevRowHeightUnit = this.model.get('rowHeightUnit');
      var prevRowHeightUnitTablet = this.model.get('rowHeightUnitTablet');
      var prevRowHeightUnitMobile = this.model.get('rowHeightUnitMobile');

      mergeNonsetObjectKeys(thisModelDataAttr['rowData'], defaultModelDataAttr['rowData'] );

      if (updatedRowOpName == 'rowData.rowBackgroundType' || updatedRowOpName == 'rowData.rowHoverOptions.rowBackgroundTypeHover' || updatedRowOpName == 'rowData.rowOverlayBackgroundType') {
        thischangedValue = $('[data-optname="'+updatedRowOpName+'"]:checked').val();
      }

      setUpdatedOptsObject(thisModelDataAttr, updatedRowOpName, thischangedValue);

      
      if (updatedRowOpName) {
        this.model.set(thisModelDataAttr);
      }

      rowColumns = this.model.get('columns');

      var numberOfColumnsSelected = rowColumns;
      if ( typeof(this.model.get('column'+numberOfColumnsSelected)) == 'undefined') {
        var thisModelDefaultColOps = JSON.stringify( _.clone(pageBuilderApp.thisModelDefaultColOps) );
        thisModelDefaultColOps = JSON.parse(thisModelDefaultColOps); 

        for( var i = 0; i <= numberOfColumnsSelected; i++){
          if ( typeof(this.model.get('column'+i)) == 'undefined') {
            this_column = 'column'+i;
            this.model.set({

              [this_column] :  thisModelDefaultColOps,

            });

            var thiscolas = this.model.get(this_column);
            console.log(thiscolas['columnOptions']['padding']['columnPaddingLeft']);
          }
        }
      }

      rowID = this.model.get('rowID');
      rowCID = this.model.cid;
      rowColumns = this.model.get('columns');
      rowHeightUnit = this.model.get('rowHeightUnit');
      rowData = this.model.get('rowData');
      rowHeightTablet = this.model.get('rowHeightTablet');
      rowHeightUnitTablet = this.model.get('rowHeightUnitTablet');
      rowHeightMobile = this.model.get('rowHeightMobile');
      rowHeightUnitMobile = this.model.get('rowHeightUnitMobile');
      
      
      rowOptionsRender(rowID,rowCID,rowColumns,rowHeightUnit,rowData, rowHeightTablet,rowHeightUnitTablet,rowHeightMobile,rowHeightUnitMobile);

      var aftRowCols = this.model.get('columns');
      var aftRowHeight = this.model.get('rowHeight');
      var aftRowHeightTablet = this.model.get('rowHeightTablet');
      var aftRowHeightMobile = this.model.get('rowHeightMobile');
      var aftRowHeightUnit = this.model.get('rowHeightUnit');
      var aftRowHeightUnitTablet = this.model.get('rowHeightUnitTablet');
      var aftRowHeightUnitMobile = this.model.get('rowHeightUnitMobile');
      var aftrowVideo = this.model.attributes['rowData']['video'];
      
      if ( aftRowCols != prevRowCols || aftRowHeight != prevRowHeight || aftRowHeightTablet != prevRowHeightTablet || aftRowHeightMobile != prevRowHeightMobile || aftRowHeightUnit != prevRowHeightUnit || aftRowHeightUnitTablet != prevRowHeightUnitTablet || aftRowHeightUnitMobile != prevRowHeightUnitMobile ) {
          $(this.el).html('');
          this.render();
      }

      if ( updatedRowOpName.includes("video") ) {
          $(this.el).html('');
          this.render();
      }

      if (pageBuilderApp.isColumnAction == false) {
        var thisChangeRedoProps = {
          changeModelType : 'row',
          thisModelCId: rowCID, 
          thisModelElId:rowID,
          changedOpName:updatedRowOpName,
          changedOpValue:pageBuilderApp.prevStateOption,
        }

        sendDataBackToUndoStack(thisChangeRedoProps); 
      }

      pageBuilderApp.isColumnAction = false;


      //var tua1 = performance.now();
      //console.log("Call to updateRowTrigger took " + (tua1 - tua0) + " milliseconds.");
      //$('.isChagesMade').val('true');
      jQuery('section[rowid="'+rowID+'"]').children('#ulpb_row_controls').show();
    },
    setGlobalRow: function(){

      if (isGlobalRowActive !== 'true') {
          alert('Global Row Extension is not active.');
        }else{
          var askGlobalRowName = prompt('Name of the row ?');
          var globalRowAttrToSet  = this.model.attributes;

          if (askGlobalRowName != null) {
            if (askGlobalRowName == '') {
              askGlobalRowName = "Global Row";
            }
            var ifIsGlobal = this.model.get('globalRow');

            if (typeof(ifIsGlobal) != 'undefined') {
            } else {

              var retrievedPostID = sendGlobalRowDataToDb(globalRowAttrToSet,askGlobalRowName);

                this.model.set({
                  globalRow:{
                    isGlobalRow: true,
                    globalRowPid: $('.globalRowRetrievedPostID').val()
                  }
                });
                
            } 
            $('.isChagesMade').val('true');
            $(this.el).html('');
            this.render();
          }
        }
    },
    DuplicateRow: function(){
      newRowModel = this.model.clone();
      var modelIndex = pageBuilderApp.rowList.indexOf(this.model);
      rowID = 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100);
      newRowModel.set({
        rowID: rowID,
        globalRow: 'unset'
      });
      newRowModel.unset('globalRow');
      var stuffedModel = JSON.stringify(newRowModel.attributes);
      var unStuffedModel = JSON.parse(stuffedModel);
      pageBuilderApp.rowList.add(unStuffedModel, {at: modelIndex+1} );
      var duplicatedModel = pageBuilderApp.rowList.at(modelIndex+1);
      rowCID = duplicatedModel.cid;
      var thisChangeRedoProps = {
        changeModelType : 'rowSpecialAction',
        thisModelCId: rowCID,
        thisModelElId:rowID,
        specialAction:'duplicate',
        thisModelVal:duplicatedModel,
        thisModelIndex:modelIndex+1
      }

      sendDataBackToUndoStack(thisChangeRedoProps);

      $('.isChagesMade').val('true');
      $(this.el).html('');
      this.render();
    },
    addNewRow: function(){
      var modelIndex = pageBuilderApp.rowList.indexOf(this.model);
      var rowID = 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100);

      var defaultModelDataAttr = JSON.stringify( _.clone( pageBuilderApp.rowModelDefaultOps ) );
      defaultModelDataAttr = JSON.parse(defaultModelDataAttr);

      pageBuilderApp.rowList.add({
        rowID: rowID,
        rowHeight: 100,
        columns: 0,
        rowData: defaultModelDataAttr['rowData'],
      }, {at: modelIndex+1} );

      var duplicatedModel = pageBuilderApp.rowList.at(modelIndex+1);
      rowCID = duplicatedModel.cid;
      var thisChangeRedoProps = {
        changeModelType : 'rowSpecialAction',
        thisModelCId: rowCID,
        thisModelElId:rowID,
        specialAction:'duplicate',
        thisModelVal:duplicatedModel,
        thisModelIndex:modelIndex+1
      }

      sendDataBackToUndoStack(thisChangeRedoProps);

      $('.isChagesMade').val('true');
    },
    copySectionOps: function(){
      var thisSectionOps = this.model.get('rowData');
      pageBuilderApp.copiedSecOps = JSON.stringify(thisSectionOps);
    },
    pasteSectionOps: function(){

      var thisSectionOpsBefore = this.model.get('rowData');

      if (pageBuilderApp.copiedSecOps != '') {
        var sectionOpsToPaste =  JSON.parse(pageBuilderApp.copiedSecOps);
        this.model.set('rowData',sectionOpsToPaste );
      }

      var rowID = this.model.get('rowID');
      var rowCID = this.model.cid;
      modelIndex = pageBuilderApp.rowList.indexOf(this.model);

      var thisChangeRedoProps = {
        changeModelType : 'rowSpecialAction',
        thisModelCId: rowCID,
        thisModelElId:rowID,
        specialAction:'sectionOps',
        thisModelVal:thisSectionOpsBefore,
        thisModelIndex:modelIndex
      }

      sendDataBackToUndoStack(thisChangeRedoProps);

      pageBuilderApp.copiedSecOps = '';
      $(this.el).html('');
      this.render();
    },
    addNewRowBlock: function(ev){
      var modelIndex = pageBuilderApp.rowList.indexOf(this.model);
      $('.insertRowBlockAtIndex').val(modelIndex);

      $('.ulpb_column_controls').hide();
      $('.columnWidgetPopup').hide(300);
      $('.editWidgetSaveButton').hide(50);
      $('.pageops_modal').hide(300);
      $('.edit_column').hide(300);

      $('.insertRowBlock').show(300);
    },
    addNewGlobalRow: function(){
      var modelIndex = pageBuilderApp.rowList.indexOf(this.model);
      
      $('.insert_Global_row').show(300);
        
      $('.addNewGlobalRowClosebutton').one('click',function(){
                $('.globalRowRetrievedAttributes').val('');
                selectGlobalRowToInsert = $('.selectGlobalRowToInsert').val();

                if (selectGlobalRowToInsert != '') {
                  getGlobalRowDataFromDb(selectGlobalRowToInsert);
                }
                
                retrievedGlobalRowAttributes = $('.globalRowRetrievedAttributes').val();
                retrievedGlobalRowAttributes = JSON.parse(retrievedGlobalRowAttributes);

                if (retrievedGlobalRowAttributes != '') {
                  pageBuilderApp.rowList.add( retrievedGlobalRowAttributes , {at: modelIndex+1} );
                }


                var duplicatedModel = pageBuilderApp.rowList.at(modelIndex+1);
                var rowCID = duplicatedModel.cid;
                var rowID = duplicatedModel.attributes.rowID;
                var thisChangeRedoProps = {
                  changeModelType: 'rowSpecialAction',
                  thisModelCId: rowCID,
                  thisModelElId:rowID,
                  specialAction:'NewGlobalRow',
                  thisModelVal:selectGlobalRowToInsert,
                  thisModelIndex:modelIndex+1
                }

                sendDataBackToUndoStack(thisChangeRedoProps);

          $('.insert_Global_row').hide(300);
      });

      $('.isChagesMade').val('true');
    },
    EditColumn: function(ev){

      $('.pbSearchWidget').val('');
      jQuery('.widget').show();
      $('.checkIfWidgetsAreLoadedInColumn').val('false');
      $('.columnWidgetPopup, .edit_row').hide(300);
      $('.ulpb_row_controls, .edit_row').hide(300);
      $('.pageops_modal').hide(300);
      $('.insertRowBlock').hide(300);

      var this_column = $(ev.target).attr('data-col_id');
      var rowID = this.model.get('rowID');
      $('.ColcurrentEditableRowID').val(rowID);
      $('.currentEditableColId').val(this_column);

      pageBuilderApp.currentlyEditedColId = rowID+'-'+this_column;
      pageBuilderApp.currentlyEditedThisCol = this_column;
      pageBuilderApp.currentlyEditedThisRow = this.model.get('rowID');


      var pbWrapperWidth = $('section[RowID="'+rowID+'"]').width();
      var thisColumnData = this.model.get(this_column);
      var this_column_widgets = thisColumnData['colWidgets'];
      var this_column_content = thisColumnData['columnContent'];
      var this_column_type = thisColumnData['columnType'];
      var this_column_options = thisColumnData['columnOptions'];
      var this_column_bg_color = this_column_options['bg_color'];
      var this_column_width = this_column_options['width'];
      var columnCSS = this_column_options['columnCSS'];
      var this_column_margin = this_column_options['margin'];
      var columnMarginTop = this_column_margin['columnMarginTop'];
      var columnMarginBottom = this_column_margin['columnMarginBottom'];
      var columnMarginLeft = this_column_margin['columnMarginLeft'];
      var columnMarginRight = this_column_margin['columnMarginRight'];

      var this_column_padding = this_column_options['padding'];
      var columnPaddingTop = this_column_padding['columnPaddingTop'];
      var columnPaddingBottom = this_column_padding['columnPaddingBottom'];
      var columnPaddingLeft = this_column_padding['columnPaddingLeft'];
      var columnPaddingRight = this_column_padding['columnPaddingRight'];
      
      var colWidth = $('section[RowID="'+rowID+'"]'+' .'+this_column).width();
      var colWidthPercentage  = ( (colWidth/pbWrapperWidth) * 100);
      colWidthPercentage = colWidthPercentage.toFixed(2);
      $('#columnBgColor').val(this_column_bg_color);
      $('#columnMargin').val(this_column_margin);
      $('#columnPadding').val(this_column_padding);
      $('#columnWidth').val(this_column_width);
      $('.widget-type-field').val(this_column_type);

      $('#columnBgColor').spectrum( 'set', this_column_bg_color );

      if (typeof(this_column_options['columnCSS']) == 'undefined') { columnCSS = '/* Add your custom CSS for this column here.*/'}
      $('.columnCustomStyling').val(columnCSS);

      $('.columnMarginTop').val(columnMarginTop);
      $('.columnMarginBottom').val(columnMarginBottom);
      $('.columnMarginLeft').val(columnMarginLeft);
      $('.columnMarginRight').val(columnMarginRight);
      $('.columnPaddingTop').val(columnPaddingTop);
      $('.columnPaddingBottom').val(columnPaddingBottom);
      $('.columnPaddingLeft').val(columnPaddingLeft);
      $('.columnPaddingRight').val(columnPaddingRight);

      if (typeof(this_column_options['columnCustomClass']) !== 'undefined') {
        $('.columnCustomClass').val(this_column_options['columnCustomClass']);
      }else{
        $('.columnCustomClass').val('');
      }

      if (typeof(this_column_options['colCAD']) != 'undefined') {
        $('.colCAD').val(this_column_options['colCAD']);
        $('.colCAT').val(this_column_options['colCAT']);
        $('.colCAM').val(this_column_options['colCAM']);
      }else{
        $('.colCAD').val('default');
        $('.colCAT').val('default');
        $('.colCAM').val('default');
      }

      if (typeof(this_column_options['colHideOnDesktop']) !== 'undefined') {
        $('.colHideOnDesktop').val(this_column_options['colHideOnDesktop']);
        $('.colHideOnTablet').val(this_column_options['colHideOnTablet']);
        $('.colHideOnMobile').val(this_column_options['colHideOnMobile']);
      }else{
        $('.colHideOnDesktop').val('');
        $('.colHideOnTablet').val('');
        $('.colHideOnMobile').val('');
      }

      if (typeof(this_column_options['colBoxShadow']) !== 'undefined') {
        colBoxShadow = this_column_options['colBoxShadow'];
        $('.colBoxShadowH').val(colBoxShadow['colBoxShadowH']);
        $('.colBoxShadowV').val(colBoxShadow['colBoxShadowV']);
        $('.colBoxShadowBlur').val(colBoxShadow['colBoxShadowBlur']);
        $('.colBoxShadowColor').val(colBoxShadow['colBoxShadowColor']);
        $(".colBoxShadowColor").spectrum( 'set', colBoxShadow['colBoxShadowColor'] );
      }else{
        $('.colBoxShadowH').val('');
        $('.colBoxShadowV').val('');
        $('.colBoxShadowBlur').val('');
        $('.colBoxShadowColor').val('');
      }


      //border desktop
      $('[data-optname="colBorder.bwt"]').val('');
      $('[data-optname="colBorder.bwb"]').val('');
      $('[data-optname="colBorder.bwl"]').val('');
      $('[data-optname="colBorder.bwr"]').val('');
      //tablet
      $('[data-optname="colBorder.bwtT"]').val(' ');
      $('[data-optname="colBorder.bwbT"]').val(' ');
      $('[data-optname="colBorder.bwlT"]').val(' ');
      $('[data-optname="colBorder.bwrT"]').val(' ');
      //mobile
      $('[data-optname="colBorder.bwtM"]').val(' ');
      $('[data-optname="colBorder.bwbM"]').val(' ');
      $('[data-optname="colBorder.bwlM"]').val(' ');
      $('[data-optname="colBorder.bwrM"]').val(' ');
      //border radius
      $('[data-optname="colBorder.brt"]').val('');
      $('[data-optname="colBorder.brb"]').val('');
      $('[data-optname="colBorder.brl"]').val('');
      $('[data-optname="colBorder.brr"]').val('');
      //tablet
      $('[data-optname="colBorder.brtT"]').val(' ');
      $('[data-optname="colBorder.brbT"]').val(' ');
      $('[data-optname="colBorder.brlT"]').val(' ');
      $('[data-optname="colBorder.brrT"]').val(' ');
      //mobile
      $('[data-optname="colBorder.brtM"]').val(' ');
      $('[data-optname="colBorder.brbM"]').val(' ');
      $('[data-optname="colBorder.brlM"]').val(' ');
      $('[data-optname="colBorder.brrM"]').val(' ');
      //border
      $('[data-optname="colBorder.colBorderStyle"]').val('');
      $('[data-optname="colBorder.colBorderColor"]').val('');
      if (typeof(this_column_options['colBorder']) !== 'undefined') {
        colBorder = this_column_options['colBorder'];

        if (typeof(colBorder['bwt']) == 'undefined') {
          colBorder['bwt'] = colBorder['colBorderWidth'];
          colBorder['bwb'] = colBorder['colBorderWidth'];
          colBorder['bwl'] = colBorder['colBorderWidth'];
          colBorder['bwr'] = colBorder['colBorderWidth'];

          colBorder['brt'] = colBorder['colBorderRadius'];
          colBorder['brb'] = colBorder['colBorderRadius'];
          colBorder['brl'] = colBorder['colBorderRadius'];
          colBorder['brr'] = colBorder['colBorderRadius'];
        }
        $.each(colBorder,function(index, val) {
          $('[data-optname="colBorder.'+index+'"]').val(val);
        });
      }

      if (typeof(this_column_options['paddingTablet']) !== 'undefined'){
        colMarginTablet = this_column_options['marginTablet'];
        colPaddingTablet = this_column_options['paddingTablet'];

        colMarginMobile = this_column_options['marginMobile'];
        colPaddingMobile = this_column_options['paddingMobile'];

        $('.columnWidthTablet').val(this_column_options['widthTablet']);
        $('.columnWidthMobile').val(this_column_options['widthMobile']);

        $('.columnMarginTopTablet').val(colMarginTablet['rMTT']);
        $('.columnMarginBottomTablet').val(colMarginTablet['rMBT']);
        $('.columnMarginLeftTablet').val(colMarginTablet['rMLT']);
        $('.columnMarginRightTablet').val(colMarginTablet['rMRT']);

        $('.columnPaddingTopTablet').val(colPaddingTablet['rPTT']);
        $('.columnPaddingBottomTablet').val(colPaddingTablet['rPBT']);
        $('.columnPaddingLeftTablet').val(colPaddingTablet['rPLT']);
        $('.columnPaddingRightTablet').val(colPaddingTablet['rPRT']);

        $('.columnMarginTopMobile').val(colMarginMobile['rMTM']);
        $('.columnMarginBottomMobile').val(colMarginMobile['rMBM']);
        $('.columnMarginLeftMobile').val(colMarginMobile['rMLM']);
        $('.columnMarginRightMobile').val(colMarginMobile['rMRM']);

        $('.columnPaddingTopMobile').val(colPaddingMobile['rPTM']);
        $('.columnPaddingBottomMobile').val(colPaddingMobile['rPBM']);
        $('.columnPaddingLeftMobile').val(colPaddingMobile['rPLM']);
        $('.columnPaddingRightMobile').val(colPaddingMobile['rPRM']);

      } else{
        $('.columnWidthTablet').val('');
        $('.columnWidthMobile').val('');

        $('.columnMarginTopTablet').val('');
        $('.columnMarginBottomTablet').val('');
        $('.columnMarginLeftTablet').val('');
        $('.columnMarginRightTablet').val('');

        $('.columnPaddingTopTablet').val('');
        $('.columnPaddingBottomTablet').val('');
        $('.columnPaddingLeftTablet').val('');
        $('.columnPaddingRightTablet').val('');

        $('.columnMarginTopMobile').val('');
        $('.columnMarginBottomMobile').val('');
        $('.columnMarginLeftMobile').val('');
        $('.columnMarginRightMobile').val('');

        $('.columnPaddingTopMobile').val('');
        $('.columnPaddingBottomMobile').val('');
        $('.columnPaddingLeftMobile').val('');
        $('.columnPaddingRightMobile').val('');
      }

      if (typeof(this_column_options['colBgImg']) !== "undefined"){
        $('.colBgImg').val(this_column_options['colBgImg']);
      }else{
        $('.colBgImg').val('');
      }

      if (typeof(this_column_options['colBgImgT']) !== "undefined"){
        $('[data-optname="colBgImgT"]').val(this_column_options['colBgImgT']);
      }else{
        $('[data-optname="colBgImgT"]').val('');
      }
      if (typeof(this_column_options['colBgImgM']) !== "undefined"){
        $('[data-optname="colBgImgM"]').val(this_column_options['colBgImgM']);
      }else{
        $('[data-optname="colBgImgM"]').val('');
      }


      if (typeof(this_column_options['colGradient']) !== "undefined"){
        var colGradient = this_column_options['colGradient'];

        $.each(colGradient, function(index,val){
          $('.'+index).val(val);

          if (index == 'colGradientColorFirst') {
            $('.colGradientColorFirst').spectrum( 'set', val );
          }
          if (index == 'colGradientColorSecond') {
            $('.colGradientColorSecond').spectrum( 'set', val );
          }

        });

        if (colGradient['colGradientType'] == 'linear') {
          $('.radialInput').css('display','none');
          $('.linearInput').css('display','block');
        } else if (colGradient['colGradientType'] == 'radial') {
          $('.radialInput').css('display','block');
          $('.linearInput').css('display','none');
        }

      }else{
        $('.colGradientColorFirst').val('');
        $('.colGradientLocationFirst').val('');
        $('.colGradientColorSecond').val('');
        $('.colGradientLocationSecond').val('');
        $('.colGradientType').val('');
        $('.colGradientPosition').val('');
        $('.colGradientAngle').val('');
      }



      if (typeof(this_column_options['colBackgroundType']) !== "undefined"){
        if (this_column_options['colBackgroundType'] == 'solid') {
          $(".POPBInputNormalRow .colBackgroundTypeSolid").prop("checked", true);
          $('.POPBInputNormalRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.POPBInputNormalRow .colBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputNormalRow .popb_tab_content').css('display','none');
          $('.POPBInputNormalRow .content_popb_tab_1').css('display','block');
        }
        if (this_column_options['colBackgroundType'] == 'gradient') {
          $(".colBackgroundTypeGradient").prop("checked", true);
          $('.POPBInputNormalRow .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.colBackgroundTypeGradient').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputNormalRow .popb_tab_content').css('display','none');
          $('.POPBInputNormalRow .content_popb_tab_2').css('display','block');
        }
      }else{
          $(".POPBInputNormalRow .colBackgroundTypeSolid").prop("checked", true);
          $('.popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.POPBInputNormalRow .colBackgroundTypeSolid').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.popb_tab_content').css('display','none');
          $('.content_popb_tab_1').css('display','block');
      }



      if (typeof(this_column_options['colHoverOptions']) !== "undefined") {
        var colHoverOptions = this_column_options['colHoverOptions'];
        if (colHoverOptions['colBgColorHover'] != '') {
          $('.colBgColorHover').val(colHoverOptions['colBgColorHover']);
          $('.colBgColorHover').spectrum( 'set', colHoverOptions['colBgColorHover'] );
        }else{
          $('.colBgColorHover').val('');
        }

        $('.colHoverTransitionDuration').val(colHoverOptions['colHoverTransitionDuration']);

        

        if (colHoverOptions['colBackgroundTypeHover'] == 'solid') {
          $(".colBackgroundTypeSolidHover").prop("checked", true);
          $(".colBackgroundTypeGradientHover").prop("checked", false);
          $('.POPBInputHovercol .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.colBackgroundTypeSolidHover').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputHovercol .popb_tab_content').css('display','none');
          $('.POPBInputHovercol .content_popb_tab_1').css('display','block');
        }
        if (colHoverOptions['colBackgroundTypeHover'] == 'gradient') {
          $(".colBackgroundTypeGradientHover").prop("checked", true);
          $(".colBackgroundTypeSolidHover").prop("checked", false);
          $('.POPBInputHovercol .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
          $('.colBackgroundTypeGradientHover').prev('label').css({'background':'#a5a5a5', 'color':'#fff'});
          $('.POPBInputHovercol .popb_tab_content').css('display','none');
          $('.POPBInputHovercol .content_popb_tab_2').css('display','block');
        }
        if (colHoverOptions['colBackgroundTypeHover'] == '' || typeof(colHoverOptions['colBackgroundTypeHover']) == 'undefined' ) {
          $(".colBackgroundTypeSolidHover").prop("checked", false);
          $(".colBackgroundTypeGradientHover").prop("checked", false);
          $('.POPBInputHovercol .popbNavItem label').css({'background':'#f1f1f1', 'color':'#333'});
        }

        var colGradientHover = colHoverOptions['colGradientHover'];
        $.each(colGradientHover, function(index,val){
          $('.'+index).val(val);

          if (index == 'colGradientColorFirstHover') {
            $('.colGradientColorFirstHover').spectrum( 'set', val );
          }
          if (index == 'colGradientColorSecondHover') {
            $('.colGradientColorSecondHover').spectrum( 'set', val );
          }
        });

        if (colGradientHover['colGradientTypeHover'] == 'linear') {
          $('.radialInputHover').css('display','none');
          $('.linearInputHover').css('display','block');
        } else if (colGradientHover['colGradientTypeHover'] == 'radial') {
          $('.radialInputHover').css('display','block');
          $('.linearInputHover').css('display','none');
        }

      }else{
        $('.colBgColorHover').val('');
        $('.colHoverTransitionDuration').val('');
        $('.colGradientColorFirstHover').val('');
        $('.colGradientLocationFirstHover').val('');
        $('.colGradientColorSecondHover').val('');
        $('.colGradientLocationSecondHover').val('');
        $('.colGradientTypeHover').val('');
        $('.colGradientPositionHover').val('');
        $('.colGradientAngleHover').val('');
      }


      //New extra BG Options for column

      if (typeof(this_column_options['colBgImgT']) == 'undefined' ) { this_column_options['colBgImgT'] = ''; }
      if (typeof(this_column_options['colBgImgM']) == 'undefined' ) { this_column_options['colBgImgM'] = ''; }

      if (this_column_options['colBgImg'] != '' || this_column_options['colBgImgT'] != '' || this_column_options['colBgImgM'] != '' ) {
        $('.imageBackgroundOpsCol').css('display','block');
      }else{
        $('.imageBackgroundOpsCol').css('display','none');
      }

      if (typeof(this_column_options['bgImgOps']) != "undefined"){
        $.each(this_column_options['bgImgOps'], function(index,value){
          $('[data-optname="bgImgOps.'+index+'"]').val(value);

          
          if (index == 'pos' || index == 'posT' || index == 'posM') {
            if (value == 'custom') {
              $('.rowBgImgCustomPositionDiv').css('display','block');
            }
          }

          
          if (index == 'size' || index == 'sizeT' || index == 'sizeM') {
            if (value == 'custom') {
              $('.rowBgImgCustomSizeDiv').css('display','block');
            }
          }
          
        });
      }else{

        $('[data-optname="bgImgOps.pos"]').val('');
        $('[data-optname="bgImgOps.posT"]').val('');
        $('[data-optname="bgImgOps.posM"]').val('');
        $('[data-optname="bgImgOps.xPos"]').val('');
        $('[data-optname="bgImgOps.xPosT"]').val('');
        $('[data-optname="bgImgOps.xPosM"]').val('');
        $('[data-optname="bgImgOps.xPosU"]').val('px');
        $('[data-optname="bgImgOps.xPosUT"]').val('px');
        $('[data-optname="bgImgOps.xPosUM"]').val('px');
        $('[data-optname="bgImgOps.yPos"]').val('');
        $('[data-optname="bgImgOps.yPosT"]').val('');
        $('[data-optname="bgImgOps.yPosM"]').val('');
        $('[data-optname="bgImgOps.yPosU"]').val('px');
        $('[data-optname="bgImgOps.yPosUT"]').val('px');
        $('[data-optname="bgImgOps.yPosUM"]').val('px');
        $('[data-optname="bgImgOps.rep"]').val('default');
        $('[data-optname="bgImgOps.repT"]').val('default');
        $('[data-optname="bgImgOps.repM"]').val('default');
        $('[data-optname="bgImgOps.size"]').val('');
        $('[data-optname="bgImgOps.sizeT"]').val('');
        $('[data-optname="bgImgOps.sizeM"]').val('');
        $('[data-optname="bgImgOps.cWid"]').val('');
        $('[data-optname="bgImgOps.cWidT"]').val('');
        $('[data-optname="bgImgOps.cWidM"]').val('');
        $('[data-optname="bgImgOps.widU"]').val('px');
        $('[data-optname="bgImgOps.widUT"]').val('px');
        $('[data-optname="bgImgOps.widUM"]').val('px');
        $('[data-optname="bgImgOps.parlx"]').val('');
        $('[data-optname="bgImgOps.parlxT"]').val('');
        $('[data-optname="bgImgOps.parlxM"]').val('');
      }



      $('.columnBgColor').spectrum( 'set', this_column_bg_color );
      

      pageBuilderApp.widgetList.reset();
      if (this_column_widgets) {
        pageBuilderApp.widgetList.add(this_column_widgets);
      }

      $('.checkIfWidgetsAreLoadedInColumn').val('true');
        
      jQuery('.edit_column').show(50);
      var rowID = this.model.get('rowID');
      jQuery('section[rowid="'+rowID+'"]').children('.ulpb_column_controls'+this_column).show();

      if (typeof(resizeWindowOpen) != 'undefined') {
        resizeWindowOpen();
      }

      if($(ev.target).hasClass('emptyColumnIcon') ){
        $('.colNewWidgetTabBtn').click();
        var currentAttrValue = jQuery('.colNewWidgetTabBtn').children('a').attr('href');
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
        jQuery('.colNewWidgetTabBtn').addClass('active').siblings().removeClass('active');
      }else{
        $('.colOpsTabBtn').click();

        var currentAttrValue = jQuery('.colOpsTabBtn').children('a').attr('href');    

        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();      
        jQuery('.colOpsTabBtn').addClass('active').siblings().removeClass('active');

      }

    },
    copyColumnOps: function(ev){
      var this_column = $(ev.target).attr('data-col_id');
      var thisColToCopyData = this.model.get(this_column);
      var this_column_options = thisColToCopyData['columnOptions'];
      pageBuilderApp.copiedColOps = JSON.stringify(this_column_options);
      return;
    },
    pasteColumnOps: function(ev){

      if (pageBuilderApp.copiedColOps != '') {

        var pc0this_column = $(ev.target).attr('data-col_id');
        var this_column_index = $(ev.target).attr('data-colIndex');
        var thisColDefaultData = this.model.get(pc0this_column);
        var copiedColOps =  JSON.parse(pageBuilderApp.copiedColOps);

        var thisColOpsBefore = _.clone( thisColDefaultData['columnOptions'] );

        this.model.set({
          [pc0this_column] : {
            colWidgets: thisColDefaultData['colWidgets'],
            columnOptions:copiedColOps
          }
        });

        var rowID = this.model.get('rowID');
        var rowColumns = this.model.get('columns');
        var thisColumnModelData = this.model.get(pc0this_column);

        var rowHeightData = {
          rowHeight : this.model.get('rowHeight'),
          rowHeightUnit : this.model.get('rowHeightUnit'),
          rowHeightTablet : this.model.get('rowHeightTablet'),
          rowHeightUnitTablet : this.model.get('rowHeightUnitTablet'),
          rowHeightMobile : this.model.get('rowHeightMobile'),
          rowHeightUnitMobile : this.model.get('rowHeightUnitMobile'),
        }

        rowColumnSingleRender(thisColumnModelData, rowID ,pc0this_column,rowColumns ,rowHeightData);

        var thisChangeRedoProps = {
            changeModelType : 'colSpecialAction',
            thisModelElId:rowID,
            specialAction:'colOps',
            thisModelVal:thisColOpsBefore,
            thisModelIndex:this_column_index
        }

        sendDataBackToUndoStack(thisChangeRedoProps);

        pageBuilderApp.copiedColOps = '';

      }
    },
    flipColumns: function(ev){
      var rowID = this.model.get('rowID');
      var rowColumns = this.model.get('columns');
      var rowColumns = parseInt(rowColumns);
      var this_column = $(ev.target).attr('data-col_id');
      var this_column_index = $(ev.target).attr('data-colIndex');
      var thisColToCopyData = this.model.get(this_column);

      var nxtColIndex = parseInt(this_column_index)+1;
      var nxtColName = 'column'+ nxtColIndex;
      var nextColToCopyData = this.model.get( nxtColName );

      if (nxtColIndex <= rowColumns) {
        this.model.set({
          [this_column] : {
            colWidgets: nextColToCopyData['colWidgets'],
            columnOptions:nextColToCopyData['columnOptions'],
          },
        });

        this.model.set({
          [nxtColName] : {
            colWidgets: thisColToCopyData['colWidgets'],
            columnOptions:thisColToCopyData['columnOptions'],
          },
        });


        var thisChangeRedoProps = {
          changeModelType : 'colSpecialAction',
          thisModelElId:rowID,
          specialAction:'flip',
          thisModelIndex:this_column_index
        }

        sendDataBackToUndoStack(thisChangeRedoProps);


        $(this.el).html('');
        this.render();
      }else{
        $('#'+rowID+'-'+this_column).css('outline','2px solid #F44336');
      }

    },
    reAddDeletedColumns: function(ev){

      var rowID = this.model.get('rowID');
      var rowColumns = this.model.get('columns');
      var rowColumns = parseInt(rowColumns);

      var thisUndoRedoChange = pageBuilderApp.undoRedoDeletedCol;
      var this_column = 'column'+thisUndoRedoChange['thisModelIndex'];
      var this_column_index = thisUndoRedoChange['thisModelIndex'];


      if (rowColumns < 8 ) {

        var referenceColumnStorage = [];
        for(var i = 1; i <= (rowColumns); i++ ){
          referenceColumnStorage.push( this.model.get('column'+i) );
        }

        for(var i = (parseInt(this_column_index) +1 ); i <= (rowColumns+1); i++ ){

          
          if (i == parseInt(this_column_index) ) {
            console.log(i);
            console.log(thisUndoRedoChange['thisModelVal']);

          }else{

            var thisColWidgets = _.clone( referenceColumnStorage[i-2]['colWidgets'] );
            thisColName = 'column'+i;
            this.model.set({
              [thisColName] : {
                colWidgets: thisColWidgets,
                columnOptions: referenceColumnStorage[i-2]['columnOptions'],
              },
            });

          }

        }

        this.model.set({
              [this_column] : {
                colWidgets: _.clone( thisUndoRedoChange['thisModelVal']['colWidgets'] ),
                columnOptions:thisUndoRedoChange['thisModelVal']['columnOptions'],
              },
        });

        $.each(referenceColumnStorage, function(i,v){
          delete referenceColumnStorage[i];
        });

        if (pageBuilderApp.undeRedoActionDuplicateCol != true) {
          sendDataBackToUndoStack(thisUndoRedoChange);
        }
        
        pageBuilderApp.undoRedoDeletedCol = '';
        pageBuilderApp.undeRedoActionDuplicateCol = false;
        pageBuilderApp.changedRowOpName =  'columns';
        $('#number_of_columns').val( rowColumns + 1 );
        pageBuilderApp.isColumnAction = true;
        this.updateRow();

      }else{
        $('#'+rowID+'-'+this_column).css('outline','2px solid #F44336');
      }
      
    },
    duplicateColumns: function(ev){
      var rowID = this.model.get('rowID');
      var rowColumns = this.model.get('columns');
      var rowColumns = parseInt(rowColumns);
      var this_column = $(ev.target).attr('data-col_id');
      var this_column_index = $(ev.target).attr('data-colIndex');

      var thisColumnData = _.clone(this.model.get(this_column));
      var rowCID = this.model.cid;

      if (rowColumns < 8 ) {

      var referenceColumnStorage = [];
      for(var i = 1; i <= (rowColumns); i++ ){
        referenceColumnStorage.push( this.model.get('column'+i) );
      }

      for(var i = (parseInt(this_column_index) +1 ); i <= (rowColumns+1); i++ ){
          var thisColWidgets = _.clone( referenceColumnStorage[i-2]['colWidgets'] );
          var thisColumnOptions = JSON.stringify( _.clone( referenceColumnStorage[i-2]['columnOptions'] ) );
          thisColumnOptions = JSON.parse(thisColumnOptions);

          thisColName = 'column'+i;
          this.model.set({
            [thisColName] : {
              colWidgets: thisColWidgets,
              columnOptions: thisColumnOptions,
            },
          });

          thisColumnOptions = '';
          thisColWidgets = '';
      }

      $.each(referenceColumnStorage, function(i,v){
        delete referenceColumnStorage[i];
      });

      pageBuilderApp.changedRowOpName =  'columns';
      $('#number_of_columns').val( rowColumns + 1 );
      pageBuilderApp.isColumnAction = true;
      this.updateRow();
      $('.ulpb_row_controls').css('display','none');

      var thisChangeRedoProps = {
        changeModelType : 'colSpecialAction',
        thisModelCId: rowCID, 
        thisModelElId:rowID,
        specialAction:'duplicate',
        thisModelVal:thisColumnData,
        thisModelIndex:this_column_index
      }

      sendDataBackToUndoStack(thisChangeRedoProps);

      }else{
        $('#'+rowID+'-'+this_column).css('outline','2px solid #F44336');
      }
      
    },
    deleteColumns: function(ev){
      var rowID = this.model.get('rowID');
      var rowColumns = this.model.get('columns');
      var rowColumns = parseInt(rowColumns);
      var this_column = $(ev.target).attr('data-col_id');
      var this_column_index = $(ev.target).attr('data-colIndex');

      var thisColumnData = this.model.get(this_column);
      var rowCID = this.model.cid;


      if (rowColumns < 12 ) {

        var referenceColumnStorage = [];
        for(var i = 1; i <= (rowColumns); i++ ){
          referenceColumnStorage.push( this.model.get('column'+i) );
        }

        for(var i = parseInt(this_column_index); i <= (rowColumns); i++ ){
          thisColName = 'column'+i;

          if (i == rowColumns) {
            var thisModelDefaultColOps = JSON.stringify( _.clone(pageBuilderApp.thisModelDefaultColOps) );
            thisModelDefaultColOps = JSON.parse(thisModelDefaultColOps); 

            this.model.set({
              [thisColName] : thisModelDefaultColOps,
            });
          }else{
            var thisColWidgets = _.clone( referenceColumnStorage[i]['colWidgets'] );
            this.model.set({
              [thisColName] : {
                colWidgets: thisColWidgets,
                columnOptions: referenceColumnStorage[i]['columnOptions'],
              },
            });
          }

        }

        $.each(referenceColumnStorage, function(i,v){
          delete referenceColumnStorage[i];
        });


        pageBuilderApp.changedRowOpName =  'columns';
        $('#number_of_columns').val( rowColumns - 1 );
        pageBuilderApp.isColumnAction = true;
        this.updateRow();
        $('.ulpb_row_controls').css('display','none');

        var thisChangeRedoProps = {
          changeModelType : 'colSpecialAction',
          thisModelCId: rowCID, 
          thisModelElId:rowID,
          specialAction:'delete',
          thisModelVal:thisColumnData,
          thisModelIndex:this_column_index
        }

        if (pageBuilderApp.undeRedoActionDuplicateCol != true) {
          sendDataBackToUndoStack(thisChangeRedoProps);
        }

        pageBuilderApp.undeRedoActionDuplicateCol = false;

      }else{
        $('#'+rowID+'-'+this_column).css('outline','2px solid #F44336');
      }

      $('.ulpb_row_controls').css('display','none');
      
    },
    updateColumn: function(ev){
      //console.log('updateColumn triggered');

      //var tua0 = performance.now();

      var columnToUpdate =  $(ev.target).attr('data-col_id');

      var updatedColOpName = pageBuilderApp.changedColOpName;
      var thischangedValue = $('[data-optname="'+updatedColOpName+'"]').val();

      if (typeof(updatedColOpName) == 'undefined') {
        updatedColOpName = ' ';
      }

      var thisModelDataAt = JSON.stringify( _.clone(this.model.attributes[columnToUpdate]['columnOptions']) );
      thisModelDataAttr = JSON.parse(thisModelDataAt);

      if (typeof(thisModelDataAttr['colBorder']) !== 'undefined') {
        colBorder = thisModelDataAttr['colBorder'];

        if (typeof(colBorder['bwt']) == 'undefined') {

          colBorder['bwt'] = colBorder['colBorderWidth'];
          colBorder['bwb'] = colBorder['colBorderWidth'];
          colBorder['bwl'] = colBorder['colBorderWidth'];
          colBorder['bwr'] = colBorder['colBorderWidth'];

          colBorder['brt'] = colBorder['colBorderRadius'];
          colBorder['brb'] = colBorder['colBorderRadius'];
          colBorder['brl'] = colBorder['colBorderRadius'];
          colBorder['brr'] = colBorder['colBorderRadius'];
        }

      }


      var thisModelDefaultColOps = JSON.stringify( _.clone(pageBuilderApp.thisModelDefaultColOps) );

      thisModelDefaultColOps = JSON.parse(thisModelDefaultColOps);

      mergeNonsetObjectKeys(thisModelDataAttr, thisModelDefaultColOps.columnOptions );


      if (updatedColOpName == 'colBackgroundType' || updatedColOpName == 'colHoverOptions.colBackgroundTypeHover' ) {
        thischangedValue = $('[data-optname="'+updatedColOpName+'"]:checked').val();
      }

      setUpdatedOptsObject(thisModelDataAttr, updatedColOpName, thischangedValue);

      var widgets = pageBuilderApp.widgetList.toJSON();

      this.model.set({
        [columnToUpdate] : {
          colWidgets: widgets,
          columnOptions:thisModelDataAttr
        }
      });


      var rowID = this.model.get('rowID');
      var rowCID = this.model.cid;
      var rowColumns = this.model.get('columns');
      var thisColumnModelData = this.model.get(columnToUpdate);

      var rowHeightData = {
        rowHeight : this.model.get('rowHeight'),
        rowHeightUnit : this.model.get('rowHeightUnit'),
        rowHeightTablet : this.model.get('rowHeightTablet'),
        rowHeightUnitTablet : this.model.get('rowHeightUnitTablet'),
        rowHeightMobile : this.model.get('rowHeightMobile'),
        rowHeightUnitMobile : this.model.get('rowHeightUnitMobile'),
      }

      rowColumnSingleRender(thisColumnModelData, rowID ,columnToUpdate,rowColumns , rowHeightData);


      if (pageBuilderApp.undoRedoColDragWidth == true) {

        var thisChangeRedoProps = {
          changeModelType : 'column',
          thisModelCId: rowCID,
          thisModelElId:rowID,
          thisColId:columnToUpdate,
          specialAction:'colWidth',
          changedOpName:updatedColOpName,
          changedOpValue:pageBuilderApp.prevStateOption,
        }

        sendDataBackToUndoStack(thisChangeRedoProps); 

      }else{

        var thisChangeRedoProps = {
          changeModelType : 'column',
          thisModelCId: rowCID, 
          thisModelElId:rowID,
          thisColId:columnToUpdate,
          changedOpName:updatedColOpName,
          changedOpValue:pageBuilderApp.prevStateOption,
        }

        sendDataBackToUndoStack(thisChangeRedoProps);

      }

        

        //$('section[rowid="'+rowID+'"]').children('.ulpb_column_controls'+columnToUpdate).show();
        //$('.isChagesMade').val('true');
      //var tua1 = performance.now();
      //console.log("Call to updateColumn took " + (tua1 - tua0) + " milliseconds.");

      
    }, // prev took 95-115ms now 5-15ms
    updateColumnWidgetTrigger: function(ev){
      //console.log('updateColumnWidgetTrigger triggered');

      // var tua0 = performance.now();

      var columnToUpdate =  $(ev.target).attr('data-col_id');
      
      var thisModelColDataAttr = this.model.attributes[columnToUpdate];
      
      var widgets = pageBuilderApp.widgetList.toJSON();

      this.model.set({
        [columnToUpdate] : {
          colWidgets: widgets,
          columnOptions:thisModelColDataAttr['columnOptions']
        }
      });
      
      //$('.isChagesMade').val('true');
      // var tua1 = performance.now();
      //console.log("Call to updateColumnWidgetTrigger took " + (tua1 - tua0) + " milliseconds.");
    }, // prev took 110 - 140ms || Now it takes 0.1-1ms
    updateWidth: function() {
      rowColumns = this.model.get('columns');
      var this_column = $('.currentResizedRowColTarget').val();
      var thisColumnModelData = this.model.get(this_column);
      var this_column_widgets = thisColumnModelData['colWidgets'];
      var this_column_options = thisColumnModelData['columnOptions'];
      var this_column_bg_color = this_column_options['bg_color'];
      var this_column_margin = this_column_options['margin'];
      var this_column_padding = this_column_options['padding'];
      var columnCSS = this_column_options['columnCSS'];

      var rowID = this.model.get('rowID');
      var colWidth = $('section[RowID="'+rowID+'"]'+' .'+this_column).width();
      var pbWrapperWidth = $('section[RowID="'+rowID+'"] .rowColumnsContainer').width();
      var colWidthPercentage  = ( (colWidth/pbWrapperWidth) * 100);
      colWidthPercentage = colWidthPercentage.toFixed(2);
      var savedColWidth = this_column_options['width'];

      var changedOpName = 'width';

      var currentViewPortColOps = $('.currentViewPortSize').val();
      if (currentViewPortColOps == 'rbt-l') {
        changedOpName = 'width';
      }
        if (currentViewPortColOps == 'rbt-m') {
        changedOpName = 'widthTablet';
      }
        if (currentViewPortColOps == 'rbt-s') {
        changedOpName = 'widthMobile';
      }


      // asdasd

      var columnToUpdate = this_column;
      var updatedColOpName = changedOpName;
      var thischangedValue = colWidthPercentage;

      if (typeof(updatedColOpName) == 'undefined') {
        updatedColOpName = ' ';
      }

      var thisModelDataAttr = _.clone(this.model.attributes[columnToUpdate]['columnOptions']);

      var thisModelDefaultColOps = JSON.stringify( _.clone(pageBuilderApp.thisModelDefaultColOps) );

      thisModelDefaultColOps = JSON.parse(thisModelDefaultColOps);

      mergeNonsetObjectKeys(thisModelDataAttr, thisModelDefaultColOps.columnOptions );


      if (updatedColOpName == 'colBackgroundType' || updatedColOpName == 'colHoverOptions.colBackgroundTypeHover' ) {
        thischangedValue = $('[data-optname="'+updatedColOpName+'"]:checked').val();
      }

      setUpdatedOptsObject(thisModelDataAttr, updatedColOpName, thischangedValue);


      this.model.set({
        [columnToUpdate] : {
          colWidgets: this_column_widgets,
          columnOptions:thisModelDataAttr
        }
      });

      var rowID = this.model.get('rowID');
      var rowCID = this.model.cid;
      var rowColumns = this.model.get('columns');
      var thisColumnModelData = this.model.get(columnToUpdate);

      var rowHeightData = {
        rowHeight : this.model.get('rowHeight'),
        rowHeightUnit : this.model.get('rowHeightUnit'),
        rowHeightTablet : this.model.get('rowHeightTablet'),
        rowHeightUnitTablet : this.model.get('rowHeightUnitTablet'),
        rowHeightMobile : this.model.get('rowHeightMobile'),
        rowHeightUnitMobile : this.model.get('rowHeightUnitMobile'),
      }

      rowColumnSingleRender(thisColumnModelData, rowID ,columnToUpdate,rowColumns , rowHeightData);


      var thisChangeRedoProps = {
        changeModelType : 'column',
        thisModelCId: rowCID,
        thisModelElId:rowID,
        thisColId:columnToUpdate,
        specialAction:'colWidth',
        changedOpName:updatedColOpName,
        changedOpValue:pageBuilderApp.prevStateOption,
      }

      sendDataBackToUndoStack(thisChangeRedoProps);

      currentResizedRowColTargetNext = $('.currentResizedRowColTargetNext').val();
      if (currentResizedRowColTargetNext != 'false') {
        currentResizedRowColTargetNext++;
        nextColumn = 'column'+currentResizedRowColTargetNext;
        $('.currentResizedRowColTarget').val(nextColumn);
        $('.currentResizedRowColTargetNext').val('false');
        $('section[RowID="'+rowID+'"]'+' .'+nextColumn).children('#WidthSave').trigger('click');
      }



      $('.isChagesMade').val('true');
    },
    updateRowHeight: function() {

      var rowID = this.model.get('rowID');
      var currentResizedRowHeight = $('.currentResizedRowHeight').val();
      var curreViewportS = $('.currentViewPortSize').val();
      var rowCID = this.model.cid;
      var updatedRowOpName = 'rowHeight';
      var prevOpsValue = this.model.get('rowHeight');


      if (curreViewportS == 'rbt-l') {

        this.model.set('rowHeight',currentResizedRowHeight);
        this.model.set('rowHeightUnit','px');

      } else if (curreViewportS == 'rbt-m') {

        updatedRowOpName = 'rowHeightTablet';
        var prevOpsValue = this.model.get('rowHeightTablet');
        this.model.set('rowHeightTablet',currentResizedRowHeight);
        this.model.set('rowHeightUnitTablet','px');

      } else if (curreViewportS == 'rbt-s') {

        updatedRowOpName = 'rowHeightMobile';
        var prevOpsValue = this.model.get('rowHeightMobile');
        this.model.set('rowHeightMobile',currentResizedRowHeight);
        this.model.set('rowHeightUnitMobile','px');

      } else{

        this.model.set('rowHeight',currentResizedRowHeight);
        this.model.set('rowHeightUnit','px');

      }

      var thisChangeRedoProps = {
        changeModelType : 'row',
        thisModelCId: rowCID, 
        thisModelElId:rowID,
        changedOpName:updatedRowOpName,
        changedOpValue:prevOpsValue,
      }

      sendDataBackToUndoStack(thisChangeRedoProps); 
    
    },
    copyThisRowLS: function() {

      var thisRowAllAttrs = JSON.stringify(this.model.attributes);

      $('#copyRowAttrsAllInput').val('"'+thisRowAllAttrs+'"');
      $('#copyRowAttrsAllInput').attr('value','"'+thisRowAllAttrs+'"');

      var copyText = $('<input>').val(thisRowAllAttrs).appendTo('body').select();
      document.execCommand('copy');
      copyText.remove();

    },
    pasteThisRowLS: function() {


      var thisSectionBefore = this.model.attributes;
      var copiedRowAttrs = jQuery('.pasteCopyAttrInput').val();

      if (copiedRowAttrs != '') {
        var sectionOpsToPaste =  JSON.parse(copiedRowAttrs);
        if (typeof(sectionOpsToPaste['rowID']) != 'undefined') {
          sectionOpsToPaste['rowID'] = 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100);

          var modelIndex = pageBuilderApp.rowList.indexOf(this.model);
          pageBuilderApp.rowList.add(sectionOpsToPaste, {at: modelIndex+1} );

          modelIndex = pageBuilderApp.rowList.indexOf(this.model);
          var duplicatedModel = pageBuilderApp.rowList.at(modelIndex+1);
          rowCID = duplicatedModel.cid;
          var thisChangeRedoProps = {
            changeModelType : 'rowSpecialAction',
            thisModelCId: rowCID,
            thisModelElId:sectionOpsToPaste['rowID'],
            specialAction:'duplicate',
            thisModelVal:sectionOpsToPaste,
            thisModelIndex:modelIndex+1
          }

          sendDataBackToUndoStack(thisChangeRedoProps);
          
        }
        
      }

          

      sectionOpsToPaste = '';
      thisSectionBefore = '';
      copiedRowAttrs = '';
      $(this.el).html('');
      this.render();

    },
    setColumnsOfThisModel: function(ev){
      var numberOfColumnsSelected =  $(ev.target).parent().attr('data-colNumber');

      pageBuilderApp.changedRowOpName =  'columns';
      $('#number_of_columns').val(numberOfColumnsSelected);
      this.updateRow();

      
      $('.isChagesMade').val('true');
    },
    widgetInlineEditorSave: function(ev){

      var this_column = $(ev.currentTarget).attr('data-wid_col_id');
      var this_widget = $(ev.currentTarget).attr('data-widget_id');
      var this_widget_type = $(ev.currentTarget).attr('data-widgettype');
      var thisColumnData = this.model.get(this_column);
      var this_widget = parseInt(this_widget);

      pageBuilderApp.changedOpType = 'specific';
      

      thisWidgetParentColID = $(ev.currentTarget).attr('data-parentwidgetid');
      $("body").unbind("click");

      /*
        thisWidgetIndex = this_widget;
        thisWidgetEl = $(ev.target );
        pageBuilderApp.currentlyEditedColId = thisWidgetParentColID;
        pageBuilderApp.currentlyEditedWidgId = thisWidgetIndex;
        pageBuilderApp.currentlyEditedThisCol = thisWidgetEl.attr('data-wid_col_id');
        pageBuilderApp.currentlyEditedThisRow = this.model.get('rowID');

        jQuery('.ColcurrentEditableRowID').val(pageBuilderApp.currentlyEditedThisRow);
        jQuery('.currentEditableColId').val(pageBuilderApp.currentlyEditedThisCol);

        this.loadWidgetsInColumn();
      */

      try {
      // statements
      var EditedData = ' ';
      if (this_widget_type == 'wigt-pb-text') {
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();
        var parentElType = thisColumnData['colWidgets'][this_widget]['widgetText']['widgetTextTag'];
        if (parentElType == 'p') {
          if ( EditedData.includes('ltwFontScript') == false ) {
            var ltwFontScript = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').siblings('.ltwFontScript').html();
            ltwFontScript = '<div class="ltwFontScript" style="display:none;"> '+ltwFontScript+' </div>';
            EditedData = EditedData+ltwFontScript;
          }
        }
          //console.log(EditedData);
        
        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          widgetTextContent : EditedData,
          widgetTextAlignment : pageBuilderApp.pbTextAlignment,
        }
      } else if(this_widget_type == 'wigt-WYSIWYG'){

        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').children('.defaultELt').html();

        var liveTextFontScripts = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').children('.ltwFontScript').html();

        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          widgetContent : EditedData,
          widgetContentFonts : liveTextFontScripts
        }

      } else if(this_widget_type == 'wigt-btn-gen' || this_widget_type == 'wigt-pb-formBuilder'){
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();
        
        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          btnText : EditedData,
        }
      } else if(this_widget_type == 'wigt-pb-pricing'){
        var thisFieldName = $(ev.currentTarget).attr('data-fieldName');
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();

        var pricingbtnText = thisColumnData['colWidgets'][this_widget]['widgetPricing']['pricingbtnText'];
        var pbPricingHeaderText = thisColumnData['colWidgets'][this_widget]['widgetPricing']['pbPricingHeaderText'];
        var pbPricingContent = thisColumnData['colWidgets'][this_widget]['widgetPricing']['pbPricingContent'];

        if (thisFieldName == 'pricingbtnText') {
          pageBuilderApp.copiedInlineOps = {
            widgetType : this_widget_type,
            pricingbtnText : EditedData,
            pbPricingHeaderText: pbPricingHeaderText,
            pbPricingContent: pbPricingContent,
          }
        }
        if (thisFieldName == 'pbPricingHeaderText') {
          pageBuilderApp.copiedInlineOps = {
            widgetType : this_widget_type,
            pricingbtnText : pricingbtnText,
            pbPricingHeaderText: EditedData,
            pbPricingContent: pbPricingContent,
          }
        }
        if (thisFieldName == 'pbPricingContent') {
          pageBuilderApp.copiedInlineOps = {
            widgetType : this_widget_type,
            pricingbtnText : pricingbtnText,
            pbPricingHeaderText: pbPricingHeaderText,
            pbPricingContent: EditedData,
          }

        }

        //thisColumnData['colWidgets'][this_widget]['widgetPricing'][thisFieldName] = EditedData;

      }else if(this_widget_type == 'wigt-pb-navmenu'){

        var thisFieldName = $(ev.currentTarget).attr('data-fieldName');
        var thisFieldIndex = $(ev.currentTarget).attr('data-fieldIndex'); 
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();

        if (thisFieldName == 'cnilab') {
          pageBuilderApp.copiedInlineOps = {
            widgetType : this_widget_type,
            editedFieldName : thisFieldName,
            editedFieldIndex : thisFieldIndex,
            widgetContent : EditedData,

          }
        }

      }else if(this_widget_type == 'wigt-pb-cards'){

        var thisFieldName = $(ev.currentTarget).attr('data-fieldName');
        var thisFieldIndex = $(ev.currentTarget).attr('data-fieldIndex'); 
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();

        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          editedFieldName : thisFieldName,
          editedFieldIndex : thisFieldIndex,
          widgetContent : EditedData,
        }

      }else if(this_widget_type == 'wigt-pb-testimonial'){

        var thisFieldName = $(ev.currentTarget).attr('data-fieldName');
        var EditedData = $(ev.currentTarget).prev('#widgetInlineEditor').html();
        thisColumnData['colWidgets'][this_widget]['widgetTestimonial'][thisFieldName] = POPB_strip(EditedData);

      } else if (this_widget_type == 'wigt-pb-liveText') {

        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').children('.defaultELt').html();
        if (EditedData == '') { EditedData = ' '; }

        if (EditedData.indexOf('elLtWrapped') >= 0 ) {

        }else{
          thisElStyles = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').children('.defaultELt').attr('style');
          EditedData = '<p class="elLtWrapped" style="'+thisElStyles+'">'+EditedData+'</p>';
        }

        var liveTextFontScripts = $(ev.currentTarget).siblings('.liveTextWrap').children('.ltwFontScript').html();

        

        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          wltc : EditedData,
          wltfs : liveTextFontScripts
        }
      } else if(this_widget_type == 'wigt-img'){
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();
        pageBuilderApp.copiedInlineOps = {
          widgetType : this_widget_type,
          captionText : EditedData,
        }
      } else if(this_widget_type == 'wigt-pb-accordion'){

        var thisFieldName = $(ev.currentTarget).attr('data-fieldName');
        var thisFieldIndex = $(ev.currentTarget).attr('data-fieldIndex'); 
        var EditedData = $(ev.currentTarget).siblings('.liveTextWrap').children('.eltEditable').html();

        if (thisFieldName == 'accContent') {

          pageBuilderApp.copiedInlineOps = {
            widgetType : this_widget_type,
            editedFieldName : thisFieldName,
            editedFieldIndex : thisFieldIndex,
            widgetContent : EditedData,

          }

          var editorId = 'accordionEditor_'+thisFieldIndex;

          tinyMCE.get(editorId).setContent(EditedData);
          jQuery('#'+editorId).val(EditedData);

          pageBuilderApp.changedOpType = 'specific';
          pageBuilderApp.changedOpName =  editorId;
          var that = jQuery('.closeWidgetPopup').attr('data-CurrWidget');
          
          jQuery('div[data-saveCurrWidget="'+that+'"]').click();

          ColcurrentEditableRowID = jQuery('.ColcurrentEditableRowID').val();
          currentEditableColId = jQuery('.currentEditableColId').val();
          jQuery('section[rowid="'+ColcurrentEditableRowID+'"]').children('.ulpb_column_controls'+currentEditableColId).children('#editColumnSaveWidget').click();

        }

      }

      pageBuilderApp.copiedInlineOps['thisWidgetId'] = this_widget;
      $('#widgets li:nth-child('+(this_widget+1)+')').children().children('.wdt-edit-controls').children('#updateInlineTextChanges').click();

      } catch(e) {
        // statements
        console.log(e);
      }

      //now integrating this
      /*
      var this_column_widgets = thisColumnData['colWidgets'];
      var this_column_options = thisColumnData['columnOptions'];

        this.model.set({
          [this_column] : {
            colWidgets: this_column_widgets,
            columnOptions: this_column_options,
          }
        });
      */

      checkIEUL = true;

      // jQuery('.edit_column').hide(0);
      // jQuery('.ulpb_column_controls').hide(0);

      // if ($('.edit_row').is(':visible')) {

      // }else{
      //   resizeWindowClose();
      // }
      
      $("body").unbind("click");
      //$('.isChagesMade').val('true');


      return;
    },


});

}( jQuery ) );