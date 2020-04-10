<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="tabs" style="">
  <ul class="tab-links">
    <li class="active"><a href="#customNavTab1" class="tab_link">Menu links</a></li>
    <li><a href="#customNavTab2" class="tab_link">Design</a></li>
  </ul>
<div class="tab-content" style="box-shadow:none;">
	<div id="customNavTab1" class="tab active" style="min-width: 380px;">
          <div class="btn btn-blue" id="addNewMenuItem" > <span class="dashicons dashicons-plus-alt"></span> Add Menu Link </div>
          <br>
          <br>
          <ul class="sortableAccordionWidget    customNavItemsContainer"></ul>
          <br><br><br><br><hr><br>
	</div>
	<div id="customNavTab2" class="tab" style="background: #fff; padding:20px 10px 20px 25px; width: 99%;">
        <br>
        <br>
        <label> Eanble Hamburger Mode :</label>
        <select class="cnsresop">
            <option value="true">Yes</option>
            <option value="false">No</option>
        </select>
        <br><br>
        <p><i>Note :</i> Responsive Hamburger navigation will only work on Tablet and Phone Screens.</p>
        <hr><br>
        <label> Layout :</label>
        <select class="cnslayout">
            <option value="Horizontal">Horizontal</option>
            <option value="Vertical">Vertical</option>
        </select>
        <br><br><hr><br>
        <label> Alignment :</label>
        <select class="cnsalign">
            <option value="">Select</option>
            <option value="center">Center</option>
            <option value="left">Left</option>
            <!-- <option value="right">Right</option> -->
        </select>
        <br><br><hr><br>
        <label>Font Color :</label>
        <input type="text" class="color-picker_btn_two cnsfc" id="cnsfc" value='#333333'>
        <br>
        <br>
        <hr>
        <br>
        <label>Hover Color :</label>
        <input type="text" class="color-picker_btn_two cnsfhc" id="cnsfhc" value='#333333'>
        <br>
        <br>
        <hr>
        <br>
        <label>Background Color :</label>
        <input type="text" class="color-picker_btn_two cnsbc" id="cnsbc" value='rgba(51, 51, 51, 0)'>
        <br>
        <br>
        <hr>
        <br>
        <label>Hover Background Color :</label>
        <input type="text" class="color-picker_btn_two cnshbc" id="cnshbc" value='#333333'>
        <br>
        <br>
        <hr>
        <br>
        <label>Nav Icon Color :</label>
        <input type="text" class="color-picker_btn_two cnsnic" id="cnsnic" value='#333333'>
        <br>
        <br>
        <hr>
        <br>
        <div>
            <h4>Text Size 
                <span class="responsiveBtn rbt-l " > <i class="fa fa-desktop"></i> </span>   
                <span class="responsiveBtn rbt-m " > <i class="fa fa-tablet"></i> </span>
                <span class="responsiveBtn rbt-s " > <i class="fa fa-mobile-phone"></i> </span>
            </h4>
            <div class="responsiveOps responsiveOptionsContainterLarge">
                <label></label>
                <input type="number" class="cnsfs"> px
            </div>
            <div class="responsiveOps responsiveOptionsContainterMedium" style="display: none;">
                <label></label>
                <input type="number" class="cnsfst"> px
            </div>
            <div class="responsiveOps responsiveOptionsContainterSmall" style="display: none;">
                <label></label>
                <input type="number" class="cnsfsm"> px
            </div>
        </div>
        <br>
        <br>
        <hr>
        <br>
        <label>Links Gap :</label>
        <input type="number" class="cnslg" id="cnslg"> px
        <br><br><br><br><hr><br>
        <label>Links Height :</label>
        <input type="number" class="cnslh" id="cnslh"> px
        <br><br><br><br><hr><br>
        <label>Menu Font Family:</label>
        <input class="cnsff gFontSelectorulpb" id="cnsff">
        <br><br><br><br><hr><br>
        <label>Logo Image :</label>
        <input id="image_location1" type="text" class=" cnslourl upload_image_button2"  name='lpp_add_img_1' value=' ' placeholder='Insert Image URL here' />
        <label></label>
        <input id="image_location1" type="button" class="upload_bg" data-id="2" value="Upload" />
        <br><br><br><br><hr><br>
        <label> Logo Size :</label>
        <select class="cnslayout">
            <option value="Small">Small</option>
            <option value="Medium">Medium</option>
            <option value="Large">Large</option>
        </select>
        <br><br><br><hr><br><br><br><br><br><br>
	</div>
</div>
</div>
<script type="text/javascript">
    (function($){

        jQuery('#addNewMenuItem').click(function(){
        jQuery('.customNavItemsContainer').append(
            
            '<li>'+
                '<h3 class="handleHeader">Menu Link <span class="dashicons dashicons-trash slideRemoveButton" style="float: right;"></span> </h3>'+
                '<div  class="accordContentHolder">'+
                    '<label> Link Label :</label>'+
                    '<input type="text" class="cnilab" value="Menu Item"> <br> <br>'+
                    '<label> Link URL :</label>'+
                    '<input type="text" class="cniurl" value="#">  <br> <br>'+
                '</div>'+
            '</li>'

        );

        jQuery( '.customNavItemsContainer' ).accordion( "refresh" );

        jQuery('.closeWidgetPopup').click();

    }); // CLICK function ends here.

    $(document).on( 'change','.customNavItemsContainer input', function(){
        if ( $(this).hasClass('cnilab') ) {
            if ($(this).val() == '') {                
            } else{
                $(this).parent().parent().siblings('.handleHeader').html($(this).val() + '<span class="dashicons dashicons-trash slideRemoveButton" style="float: right;"></span>');
            }
        }

        jQuery('.closeWidgetPopup').click();
    });


    })(jQuery);
</script>