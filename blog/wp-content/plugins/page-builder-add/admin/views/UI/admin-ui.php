<?php if ( ! defined( 'ABSPATH' ) ) exit; 


if (isset( $_GET['thisPostID'] )) {
  $postId = sanitize_text_field($_GET['thisPostID']);
  $post = get_post($postId);
}elseif(isset( $_GET['post'] )) {
  $postId = sanitize_text_field($_GET['post']);
}else{
  if (isset($post)) {
    $postId = $post->ID;
  }else{
    $postId = false;
  }
}

if (isset( $_GET['thisPostType'] )) {
  $thisPostType = sanitize_text_field($_GET['thisPostType']);
}else{
  $thisPostType = get_post_type($postId);
}

if (isset($post)) {
  $post_slug = $post->post_name;
}else{
  $post_slug = '';
}


if ($post_slug == '') {
  $hidePermalink = '';
}else{
  $hidePermalink = '';
}

$is_front_page = get_post_meta($postId, 'ULPB_FrontPage', true );
$loadWpHead = get_post_meta($postId, 'ULPB_loadWpHead', true );
$loadWpFooter = get_post_meta($postId, 'ULPB_loadWpFooter', true );

if ($loadWpHead == '') {
  $loadWpHead = 'true';
}

if ($loadWpFooter == '') {
  $loadWpFooter = 'true';
}

$plugData = get_plugin_data(ULPB_PLUGIN_PATH.'/page-builder-add.php',false,true);

$pb_current_user = wp_get_current_user();


$landingPageSafeModeFeature = get_option( 'landingPageSafeModeFeature', false );

$plugOps_pageBuilder_data_nonce = wp_create_nonce( 'POPB_data_nonce' );
$plugOps_pageBuilder_switch_nonce = wp_create_nonce( 'POPB_switch_nonce' );


$mcActive = 'false';  $GRisActive = 'false';
if ( is_plugin_active('page-builder-add-mailchimp-extension/page-builder-add-mailchimp-extension.php') || is_plugin_active('PluginOps-Extensions-Pack/extension-pack.php') ) {
  $mcActive = 'true';
}

if ( is_plugin_active('page-builder-add-global-row-extension/page-builder-add-global-row.php') || is_plugin_active('PluginOps-Extensions-Pack/extension-pack.php') ) {
  $GRisActive = 'true';
}



if (isset( $_GET['thisPostID'] ) ) {
  $thisLPTitle = get_the_title( $postId );
  ?>
  <br><br><br>
  <div id="titlediv">
    <div id="titlewrap">
      <label class="screen-reader-text" id="title-prompt-text" for="title"> </label>
      <input type="text" name="post_title" size="30" value="<?php echo($thisLPTitle); ?>" id="title" spellcheck="true" autocomplete="off" placeholder="Enter Page Title">
    </div>
    <div class="inside">
      <div id="edit-slug-box" class="hide-if-no-js" style="<?php echo "$hidePermalink"; ?>">
      <strong>Permalink:</strong>
      <span id="sample-permalink">
        <a href="<?php echo(site_url( ) ); ?>/?post_type=ulpb_post&amp;p=<?php echo($postId); ?>&amp;preview=true" target="wp-preview-4882"><?php echo(site_url( ) ); ?>/<span id="editable-post-name"><?php echo $post_slug; ?></span>/</a>
      </span>
      ‎<span id="edit-slug-buttons">
        <input type="text" class="editable-post-name-field" style="display: none; width: auto; height:24px; font-size: 13px; ">
        <button type="button" class="edit-slug button button-small hide-if-no-js" aria-label="Edit permalink">Edit</button>
        <button type="button" class="savePermalink  button button-small" style="display: none;">OK</button>
      </span>
      </div>
    </div>
    <span id="editable-post-name-full"><?php echo $post_slug; ?></span>
  </div>
  </div>
  <script type="text/javascript">
    ( function( $ ) {

      $('.edit-slug').click(function(){
          var prevTxt = $('#editable-post-name').text();
          $('.editable-post-name-field').val(prevTxt);
          $('#editable-post-name').css('display','none');
          $('.edit-slug').css('display','none');
          $('.editable-post-name-field').css('display','inline-block');
          $('.savePermalink').css('display','inline-block');
      });

      $('.savePermalink').click(function(){

        var postId = "<?php echo $postId; ?>";
        var new_slug = $( '.editable-post-name-field' ).val();

        $.ajax({
          url: "<?php echo admin_url('admin-ajax.php?action=ulpb_get_sample_permalink_for_landingpages&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>",
          method: 'post',
          data: {
            post_id: postId,
            new_slug: new_slug,
          },
          success: function(result){
            $('#editable-post-name').html( result );
            $('#editable-post-name-full').html( result );
            $('#editable-post-name').css('display','inline-block');
            $('.edit-slug').css('display','inline-block');
            $('.editable-post-name-field').css('display','none');
            $('.savePermalink').css('display','none');
            $( '.edit-slug' ).focus();
          }
        });

      });
    })(jQuery);
  </script>
  <?php
}


$checkPBactive = get_post_meta( $postId, 'ulpb_page_builder_active', 'true');
if ($checkPBactive == 'true') {
  echo '<div class="tab-editor-deactivate switch_button">Deactivate PluginOps Page Builder For This Page </div>';
}

$thisLandingPageShareURL = urlencode("Wow! I just created this #amazing #landingpage with #PluginOps Landing Page Builder. \n". get_preview_post_link($postId) );
?>

 <script>
  var popb_errorLog = {};
  var landingPageSafeModeFeature = '<?php echo $landingPageSafeModeFeature; ?>';
  var nonInvasveKnownErrors = [
      "Uncaught TypeError: Cannot read property 'hasClass' of undefined",
      "Uncaught TypeError: Cannot read property 'top' of undefined",
      "TypeError: thisColumnData.colWidgets[this_widget].widgetText is undefined",
      "Uncaught TypeError: Cannot read property 'indexOf' of undefined",
      "Uncaught TypeError: Cannot read property 'replace' of undefined",
      "ResizeObserver loop limit exceeded",
      "Uncaught TypeError: Cannot read property 'innerHTML' of undefined",
  ];

  window.onerror = function (msg, url, line) {

    
    if ( nonInvasveKnownErrors.indexOf(msg) == -1 ) {

      /*
      jQuery('.popb_safemode_popup').css('display','block');

      jQuery('.confirm_safemode_no').on('click',function(){
        jQuery('.popb_safemode_popup').css('display','none');
        location.reload();
      });

      popb_errorLog.errorMsg = msg;
      popb_errorLog.errorURL = url;
      popb_errorLog.errorLine = line;


      jQuery('.confirm_safemode_yes').on('click',function(){

          var result = " ";
          var form = jQuery('.insertTemplateForm');
          var errordata = 

          jQuery.ajax({
              url: "<?php echo admin_url('admin-ajax.php?action=popb_enable_safe_mode&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>",
              method: 'post',
              data:{
                errorMsg : popb_errorLog.errorMsg,
                errorURL : popb_errorLog.errorURL,
                errorLine : popb_errorLog.errorLine
              },
              success: function(result){
                  location.reload();
              }
          });

      });
      */

    }
      

  }

</script>
  <!-- ========= -->
  <!-- Your HTML -->
  <!-- ========= -->

  <?php include('tabs.php'); ?>
  <?php include('edit-column.php'); ?>
  <?php include('edit-row.php'); ?>
  <?php include('edit-widget.php'); ?>
  <?php include('new-row.php'); ?>
  <?php include('side-panel.php'); ?>
  <?php include('row-blocks.php'); ?>


<style type="text/css" id="PBPO_customCSS"></style>
  


<div class="lpp_modal pb_loader_container">
  <div class="pb_loader"></div>
</div>

<div class="lpp_modal pb_preview_container" style="">
  <div class="pb_temp_prev" style="text-align: center; overflow: visible; position: absolute;" ></div>
</div>

<div class="lpp_modal popb_confirm_action_popup">
  <div class="popb_confirm_container">
    <h2 class="popb_confirm_message popb_confirm_message_row">Are you sure you want to do this ? </h2>
    <h4 class="popb_confirm_subMessage  popb_confirm_subMessage_row">You will lose any unsaved changes.</h4>
    <div id="confirm_yes" class="confirm_btn confirm_btn_green confirm_yes">Yes</div>
    <div class="confirm_btn confirm_btn_grey confirm_no">Cancel</div>
  </div>
</div>

<div class="lpp_modal popb_confirm_template_action_popup">
  <div class="popb_confirm_container">
    <div class="popb_popup_close confirm_template_no" id="popb_popup_close" style="" ></div>
    <h2 class="popb_confirm_message" style="line-height: 1.3em;">Do you want replace current landing page or insert below the current one ? </h2>
    <h4 class="popb_confirm_subMessage">Replacing the template will delete current landing page content & design</h4>
    <div id="confirm_template_yes" class="confirm_btn confirm_btn_green confirm_template_yes" data-tempisreplace='false'>Insert</div>
    <div class="confirm_btn confirm_btn_grey confirm_template_yes confirm_template_yes_replace" data-tempisreplace='true'>Replace</div>
  </div>
</div>

<div class="lpp_modal popb_safemode_popup">
  <div class="popb_confirm_container">
    <div class="popb_popup_close" id="popb_popup_close" style="" ></div>
    <h2 class="popb_confirm_message" style="line-height: 1.3em;">There was some error loading the editor </h2>
    <h4 class="popb_confirm_subMessage">Enable safe mode and send error data to PluginOps and reload the editor page to see if that fixes the error, If error persists please contact support.</h4>
    <div id="confirm_safemode_yes" class="confirm_btn confirm_btn_green confirm_safemode_yes" data-doActionValue='true'>Enable Safe Mode</div>
    <div class="confirm_btn confirm_btn_grey confirm_safemode_no" data-doActionValue='false'>No, Just Reload</div>
  </div>
</div>


<div class="lpp_modal popb_post_publish_share">
  <div class="popb_confirm_container">
    <div class="popb_popup_close postSharePopUpHide" id="popb_popup_close" style="" ></div>
    <h2 class="popb_confirm_message">Awesome! You Have Published Your Landing Page </h2>
    <h4 class="popb_confirm_subMessage">Share it with your friends on social media.</h4>
    <a class="twitter-share-button confirm_btn" style="background: #358fde;"  href="https://twitter.com/intent/tweet?text=<?php echo $thisLandingPageShareURL; ?>"data-size="large" target="_blank">
      Tweet 
      <i class="fa fa-twitter"></i>
    </a>
  </div>
</div>

<div class="lpp_modal pb_preview_fields_container" style="">
  <div class="pb_fields_prev" style="
    overflow: visible;
    background: #fff;
    width: 48%;
    height: 80vh;
    margin: 3% 24% 0 24%;
    position: absolute;
    padding: 10px 40px;
    border-radius: 5px;
    text-align: center;
  " >
    <span class="dashicons dashicons-no formEntriesPreviewClose" style="
      float: right;
      font-size: 21px;
      margin: 5px 10px;
      cursor: pointer;
      background: #dadada;
      padding: 7px 7px;
      text-align: center;
      border-radius: 40px;
    "></span>
    <br><h2 style="text-align: center; color: #333; font-size:24px;">Form Entries</h2>
    <table class='w3-table w3-striped w3-bordered w3-card-4 formFieldsPreviewTable' style="margin-top: 50px;">
    </table>
  </div>
</div>


<input type="hidden" class="runTemplateUpdateFunction">


<input type="hidden" class="draggedWidgetAttributes" value='' >
<input type="hidden" class="draggedWidgetIndex" value='' >
<input type="hidden" class="widgetDroppedAtIndex" value='' >


<input type="hidden" class="mailchimpListIdHolder" value='' >
<input type="hidden" class="mailchimpApiKeyHolder" value='' >


<input type="hidden" class="globalRowRetrievedPostID" value='' >
<input type="hidden" class="globalRowRetrievedAttributes" value='' >

<input type="hidden" class="insertRowBlockAtIndex" value='' >


<input type="hidden" class="allTextEditableWidgetIds">

<input type="hidden" class="checkIfWidgetsAreLoadedInColumn">

<input type="hidden" class="isChagesMade" value="false">


<input type="hidden" class="currentViewPortSize" value="rbt-l">

<input type="hidden" class="currentResizedRowTarget">
<input type="hidden" class="currentResizedRowColTarget">
<input type="hidden" class="currentResizedRowColTargetNext">
<input type="hidden" class="currentResizedRowHeight">

<input type="hidden" class="isAnimateTrue">
<input type="hidden" class="animateWidgetId">


<input type="hidden" class="widgetDroppedRowId">
<input type="hidden" class="widgetDroppedColIndex">
<input type="hidden" class="widgetDroppedIndex">

<input type="hidden" class="widgetDraggedRowId">
<input type="hidden" class="widgetDraggedIndex">
<input type="hidden" class="widgetDraggedColIndex">

<input type="hidden" class="widgetDeletionCompleted" value="false">

<input type="hidden" class="isDroppedOnDroppable">

<input type="hidden" class="deleteRowIndex">
<input type="hidden" class="widgDeleteColIndex">
<input type="hidden" class="widgDeleteIndex">

<input type="hidden" class="currentlyEditedColId">
<input type="hidden" class="currentlyEditedWidgId">
<input type="hidden" class="currentlyEditedThisCol">
<input type="hidden" class="currentlyEditedThisRow">

<input type="hidden" class="ifEmptyFeaturedImageUrl">

<input type="text" id="copyRowAttrsAllInput" style="display: none !important;" >


<div id="pageStatusHolder" style="display: none;">
</div>

<div style="display: none;" class="rowWithNoColumnContainer">
  <div class="rowWithNoColumn" >
    <h5> SELECT COLUMN STRUCTURE </h5>
    <div class=" setColbtn" data-colNumber="1">
      <img src="<?php echo ULPB_PLUGIN_URL.'/images/icons/1.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="2">
      <img src="<?php echo ULPB_PLUGIN_URL.'/images/icons/2.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="3">
      <img src="<?php echo ULPB_PLUGIN_URL.'/images/icons/3.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="4">
      <img src="<?php echo ULPB_PLUGIN_URL.'/images/icons/4.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="5">
      <img src="<?php echo ULPB_PLUGIN_URL.'/images/icons/5.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="6">
      <img src="<?php echo ULPB_PLUGIN_URL.'/images/icons/6.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="7">
      <img src="<?php echo ULPB_PLUGIN_URL.'/images/icons/7.png' ?>">
    </div>
    <div class=" setColbtn" data-colNumber="8">
      <img src="<?php echo ULPB_PLUGIN_URL.'/images/icons/8.png' ?>">
    </div>


  </div>
</div>



<script type="text/javascript">
  var pageBuilderApp = {};
  pageBuilderApp.currentlyEditedColId = '';
  pageBuilderApp.currentlyEditedWidgId = '';
  pageBuilderApp.currentlyEditedThisCol = '';
  pageBuilderApp.currentlyEditedThisRow = '';
  pageBuilderApp.animateWidgetId = '';
  pageBuilderApp.copiedSecOps = '';
  pageBuilderApp.copiedColOps = '';
  pageBuilderApp.copiedWidgOps = '';
  pageBuilderApp.copiedInlineOps = '';
  pageBuilderApp.setFeaturedImageIfEmpty = '';
  pageBuilderApp.isColumnAction = false;
  pageBuilderApp.undoRedoColDragWidth = false;

  <?php
    if (!function_exists('ulpb_available_pro_widgets') ) {
      echo "pageBuilderApp.premActive = 'false';";
    }else{
      echo "pageBuilderApp.premActive = 'true';";
    }
  ?>

  var URLL = "<?php echo admin_url('admin-ajax.php?action=ulpb_admin_data&page_id='.$postId.'&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>";
  var PageBuilderAdminImageFolderPath = '<?php echo ULPB_PLUGIN_URL."/images/menu/"; ?>';
  var P_ID = "<?php echo $postId; ?>";
  var P_menu  = "<?php foreach($menus as $menu){  echo "$menu->name"; } ?>";
  var PageBuilder_Version = "<?php echo $plugData['Version']; ?>";
  var admURL = "<?php echo admin_url(); ?>";
  var siteURLpb = "<?php echo site_url(); ?>";
  var isPub = "<?php echo get_post_status( $postId ); ?>";
  var thisPostType = "<?php echo $thisPostType ?>";
  var pbWrapperWidth = jQuery('#container').width();
  var pluginURL  = '<?php echo ULPB_PLUGIN_URL; ?>';
  var admEMail = '<?php echo  $pb_current_user->user_email; ?>';
  var isMCActive = "<?php echo $mcActive; ?>";
  var isGlobalRowActive = "<?php echo $GRisActive; ?>";

  var shortCodeRenderWidgetNO = '<?php echo $plugOps_pageBuilder_data_nonce; ?>';

</script>

<script type="text/javascript">
        pageBuilderApp.tinymce =  {
            wpautop: true ,
            theme: 'modern' ,
            skin: 'lightgray' ,
            language: 'en' ,
            formats: {
                alignleft: [
                    {selector: 'p, h1, h2, h3, h4, h5, h6, td, th, div, ul, ol, li' , styles: {textAlign: 'left' }},
                    {selector: 'img, table, dl.wp-caption' , classes: 'alignleft' }
                ],
                aligncenter: [
                    {selector: 'p, h1, h2, h3, h4, h5, h6, td, th, div, ul, ol, li' , styles: {textAlign: 'center' }},
                    {selector: 'img, table, dl.wp-caption' , classes: 'aligncenter' }
                ],
                alignright: [
                    {selector: 'p, h1, h2, h3, h4, h5, h6, td, th, div, ul, ol, li' , styles: {textAlign: 'right' }},
                    {selector: 'img, table, dl.wp-caption' , classes: 'alignright' }
                ],
                strikethrough: {inline: 'del' }
            },
            relative_urls: false ,
            remove_script_host: false ,
            convert_urls: false ,
            browser_spellcheck: true ,
            fix_list_elements: true ,
            entities: '38, amp, 60, lt, 62, gt ' ,
            entity_encoding: 'raw' ,
            keep_styles: false ,
            paste_webkit_styles: 'font-weight font-style color' ,
            preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform' ,
            tabfocus_elements: ': prev ,: next' ,
            plugins: 'charmap, hr, media, paste, tabfocus, textcolor, fullscreen, wordpress, wpeditimage, wpgallery, wplink, wpdialogs, wpview' ,
            resize: 'vertical' ,
            menubar: false ,
            indent: false ,
            toolbar1: 'bold, italic, strikethrough, bullist, numlist, blockquote, hr, alignleft, aligncenter, alignright, link, unlink, wp_more, spellchecker, fullscreen, wp_adv' ,
            toolbar2: 'formatselect, underline, alignjustify, forecolor, pastetext, removeformat, charmap, outdent, indent, undo, redo, wp_help' ,
            toolbar3: '' ,
            toolbar4: '' ,
            body_class: 'id post-type-post post-status-publish post-format-standard' ,
            wpeditimage_disable_captions: false ,
            wpeditimage_html5_captions: true

        };
</script>

<script src="<?php echo ULPB_PLUGIN_URL.'/js/fa.js'; ?>"></script>
<script type="text/javascript">

  /*  Save Triggers Removed From Here */


  if (isGlobalRowActive == "true") {
      jQuery('.addNewGlobalRowVisible').parent().css('display','inline-block');
  }

  

  jQuery('#menuWrap').click(function(){return false;});
  jQuery('#lpb_menu_widget').click(function(){return false;});
  

</script> 

<script type="text/javascript">
  jQuery(function ($) {

    $('#pbWrapper').css('display','none');
    $('.newRowBtnContainerVisible').css('display','none');

    jQuery('.pb_fullScreenEditorButton').click(function(){
      $('.pb_editor_tab_content').attr('style','overflow: hidden;background: #fff;position: absolute;width: 100%;left: 0;right: 15;top: 0;');
      $('#adminmenumain, .pb_fullScreenEditorButton, #wpadminbar, #postbox-container-2, .postbox').css('display','none');
      $('#wpcontent').attr('style','margin-left:0; padding-left:0;');
      $('.pb_fullScreenEditorButtonClose').show();
      $('#pbWrapper').css('display','block');
      $('.newRowBtnContainerVisible').css('display','block');

      $(this).addClass('EditorActive');
    });

    jQuery('.pb_fullScreenEditorButtonClose').click(function(){
      $('.pb_editor_tab_content').attr('style','overflow: hidden;background: #fff;');
      $('.pb_fullScreenEditorButtonClose').css('display','none');
      $('#wpcontent').attr('style','');
      $('#adminmenumain, .pb_fullScreenEditorButton, #postbox-container-2 , .postbox').show();

      $('#submitdiv').css('display','none');
      $('#pbWrapper').css('display','none');
      $('.newRowBtnContainerVisible').css('display','none');
      $('.pb_fullScreenEditorButton').removeClass('EditorActive');
      $('.edit_row').hide(300);
      $('.columnWidgetPopup').hide(300);
      $('.pageops_modal').hide(300);
      $('.edit_column').hide(300);
      $('.ulpb_column_controls, .ulpb_row_controls').css('display','none');
    });

    $(document).ready(function(){
      $('.pb_fullScreenEditorButton').css('display','block');
      $('.pb_loader_container_pageload').css('display','none');

    });

  });
</script>

<script type="text/javascript">

  jQuery('.insertTemplateFormSubmit').on('click', function(e)  {

    var confirmIt =  confirm('Are you sure ? It will insert the temlate below your existing content.');
    if (confirmIt == true) {

        var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=ulpb_insert_template'); ?>&insertTemplateNonce="+shortCodeRenderWidgetNO;
        var result = " ";
        var form = jQuery('.insertTemplateForm');

        jQuery.ajax({
            url: insSubmit_URl,
            method: 'post',
            data: form.serialize(),
            success: function(result){
                resonse = JSON.parse(result);
                if (resonse['Message'] == 'Success'){
                  jQuery.each(resonse['Rows'], function(index,val){
                    val['rowID'] = 'ulpb_Row'+Math.floor((Math.random() * 200000) + 100);
                    collectionSize = pageBuilderApp.rowList.length;
                    pageBuilderApp.rowList.add(val, {at: collectionSize+1} );
                  });
                  alert('Selected Template Added Successfully.');
                }else{
                  jQuery('.upt_response').html('There is some bug which is preventing this page to be updated, Contact the <a href="https://wordpress.org/support/plugin/page-builder-add" target="_blank" > Bug Killers </a>');
                }
            }
        });
         
    }
    return false;
  });



  (function($){
    $(document).ready(function() {
    $('.empty_button_form').on('submit',function(){
         
         
        $('#response').text('Processing'); 
         
        var form = $(this);
        $.ajax({
            url: form.attr('action')+'&subsListEmpty='+shortCodeRenderWidgetNO,
            method: form.attr('method'),
            data: form.serialize(),
            success: function(result){
                $('.download_file_link').css('display','none');
                if (result == 'Success'){
                    $('#response').text(result);  
                }else {
                    $('#response').text(result);
                }
            }
        });
         
        
        return false;   
    });

    $('.download_button_form').on('submit',function(){
         
         
        $('#response').text('Processing'); 
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(result){
                if (result == 'success'){
                    $('#response').text(result);  
                }else {
                    $('#response').text(result);
                }
            }
        });
         
        
        return false;   
    });

    jQuery('.emptyFormDataBtn').on('click', function(e)  {

      var confirmIt =  confirm('Are you sure ? It will delete all your form data for eternity.');
      if (confirmIt == true) {

          var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=ulpb_empty_form_builder_data'); ?>&submitNonce="+shortCodeRenderWidgetNO;
          var result = " ";
          var form = jQuery('#formBuilderDataListEmpty');

          jQuery.ajax({
              url: insSubmit_URl,
              method: 'post',
              data: form.serialize(),
              success: function(result){
                  if (result == 'Success'){

                    $('.emptyFormDataBtn').hide();
                    $('#formBuilderDataListEmpty p ').text('All data has been dumped successfully.');
                  }else{
                    $('#formBuilderDataListEmpty p ').text('Already empty.');
                  }
              }
          });
 
      }
      return false;
    });


    jQuery('.entryDeleteBtn').on('click', function(e)  {

      var entryIndex = $(this).attr('data-entryIndex');
      var confirmIt =  confirm('Are you sure ? It will delete this data entry for eternity.');
      if (confirmIt == true) {

        var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=ulpb_delete_form_builder_entry'); ?>&postID="+P_ID+"&dataEntryIndex="+entryIndex+"&submitNonce="+shortCodeRenderWidgetNO;
        var result = " ";
        var form = jQuery('#formBuilderDataListEmpty');
        jQuery.ajax({
            url: insSubmit_URl,
            method: 'post',
            data: form.serialize(),
            success: function(result){
                if (result == 'success'){
                  $('.edb-'+entryIndex).parent().parent().hide();
                }else{
                  $('#formBuilderDataListEmpty p ').text('Already empty.');
                }
            }
        });
         
       
      }
        return false;
    });


    jQuery('#resetAnalyticsBtn').on('click', function(e)  {

      var confirmIt =  confirm('Are you sure ? It will delete this data for eternity.');
      if (confirmIt == true) {

        var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=ulpb_delete_optin_analytics'); ?>&postID="+P_ID+"&actionConfirmed="+confirmIt+"&submitNonce="+shortCodeRenderWidgetNO;
        var result = " ";
        var form = jQuery('#formBuilderDataListEmpty');
        jQuery.ajax({
            url: insSubmit_URl,
            method: 'post',
            data: form.serialize(),
            success: function(result){
                if (result == 'success'){
                  $('#resetAnalyticsBtn').text('Analytics reset completed.');
                }else{
                  $('#resetAnalyticsBtn').text('Some error occurred!');
                }
            }
        });
         
       
      }
        return false;
    });

    jQuery('.analyticsDateRange').on('change', function(e)  {

      var confirmIt =  true;
      if (confirmIt == true) {

        var dateRange = jQuery('.analyticsDateRange').val();
        var insSubmit_URl = "<?php echo admin_url('admin-ajax.php?action=ulpb_get_new_analytics'); ?>&postID="+P_ID+"&actionConfirmed="+confirmIt+"&dateRange="+dateRange+"&submitNonce="+shortCodeRenderWidgetNO;
        var result = " ";
        var form = jQuery('#formBuilderDataListEmpty');
        jQuery.ajax({
            url: insSubmit_URl,
            method: 'post',
            data: form.serialize(),
            success: function(result){
              result = JSON.parse(result);
                if (result['message'] == 'success'){
                  $('#mainAnalyticsContainer').html(' ');
                  $('#mainAnalyticsContainer').html(result['analytics']);
                }else{
                  $('#resetAnalyticsBtn').text('Some error occurred!');
                }
            }
        });
         
       
      }
        return false;
    });

    jQuery(document).ready(function(){  jQuery('.analyticsDateRange').trigger('change');  });


  $('.popb_checkbox').checkboxradio({
      icon: false
  });


  $('#aweberConnectButton').click(function(){
        $('.aweberLoader').text('Connecting...');
        $('.aweberLoader').show();
        var form = $('#aweberConnectForm');
        $.ajax({
            url: "<?php echo admin_url('admin-ajax.php?action=ulpb_aweber_connect&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>",
            method: form.attr('method'),
            data: form.serialize(),
            success: function(result){
                var parsedResult= JSON.parse(result);
                if (parsedResult['queryMessage'] == 'success' ) {
                  $('.aweberLoader').hide();
                  $('.aweberConnectionSetupOne').hide('slow');
                  $('.aweberConnectionSetupTwo').show('slow');
                  $('#formBuilderAweberList').html(parsedResult['allLists']);
                }else{
                  $('.aweberLoader').text('Connection unsuccesful, Please try getting your authorization code again.');
                }
                
            }
        });
  });

  $(document).ready( function() {
    $.ajax({
            url: "<?php echo admin_url('admin-ajax.php?action=ulpb_aweber_connection_check&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>",
            method: 'post',
            data: '',
            success: function(result){
                var parsedResult= JSON.parse(result);
                if (parsedResult['queryMessage'] == 'success' ) {

                  $('.aweberConnectionSetupOne').hide('slow');
                  $('.aweberConnectionSetupTwo').show('slow');
                  $('#formBuilderAweberList').html(parsedResult['allLists']);
                }else{
                  $('.aweberLoader').text(' ');
                  $('.aweberConnectionSetupOne').show('slow');
                  $('.aweberConnectionSetupTwo').hide('slow');
                }
                
            }
    });
  });

  $('#mcGetGrpsBtn').click(function(){
        $('.mcGroupsLoader').text('Connecting...');
        $('.mcGroupsLoader').show();
        var form = $('#aweberConnectForm');

        apiKey = $('.formBuilderMCApiKey').val();
        listID = $('.formBuilderMCAccountName').val();

        $('.mcgrpsContainer').css('display','none');

        if (apiKey == '' || listID == '') {
          $('.mcGroupsLoader').text("Api key or List ID can't be empty");
        }else{
          $.ajax({
              url: "<?php echo admin_url('admin-ajax.php?action=ulpb_getMCGroupIds&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>&apiKey="+apiKey+"&listID="+listID+"",
              method: form.attr('method'),
              data: form.serialize(),
              async:true,
              success: function(result){
                var parsedResult= JSON.parse(result);
                thisMcAllGroups = '';
                if (typeof( parsedResult['success']) != 'undefined' ) {
                  
                  groups = parsedResult['success'];
                  $.each(groups, function(title,arrayVal){
                    thisOptGroup = '<optgroup label="'+title+'">';

                    thisgrpFields = '';
                    $.each(arrayVal, function(index, val){
                      $.each(val,function(grpId, grpName){
                        thisField = '<option value="'+grpName+'"> '+grpId +' </option>';
                        thisgrpFields =  thisgrpFields + thisField;
                      });
                    });

                    thisCompleteOptGroup = thisOptGroup + thisgrpFields + "</optgroup>";

                    thisMcAllGroups = thisMcAllGroups + thisCompleteOptGroup;

                  });

                  $('.formBuilderMCGroups').html( "<option value='false'>None</option>" + thisMcAllGroups);
                  $('.mcgrpsContainer').css('display','block');
                  $('.mcGroupsLoader').text('');

                  $('.formBuilderMCGroups').val(pageBuilderApp.thisMCSelectedGroup);

                }else{
                  $('.mcGroupsLoader').text(parsedResult['error']);
                }
                
              }
          });
        }

  });

  $('#ckGetseqsBtn').click(function(){
        $('.ckSeqsLoader').text('Connecting...');
        $('.ckSeqsLoader').show();
        var form = $('#aweberConnectForm');

        apiKey = $('.formBuilderConvertKitApiKey').val();

        $('.ckSeqsContainer').css('display','none');

        if (apiKey == '') {
          $('.ckSeqsLoader').text("Api key or List ID can't be empty");
        }else{
          $.ajax({
            url: "<?php echo admin_url('admin-ajax.php?action=ulpb_getCkSequenceIds&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>&apiKey="+apiKey,
              method: form.attr('method'),
              data: form.serialize(),
              async:true,
              success: function(result){
                var parsedResult= JSON.parse(result);
                thisMcAllGroups = '';
                if (typeof( parsedResult['success']) != 'undefined' ) {
                  
                  ck_sequences = parsedResult['success'];

                  thisgrpFields = '';
                  $.each(ck_sequences, function(index,val){
                    
                    thisField = '<option value="'+val['id']+'"> '+val['name'] +' </option>';
                    thisgrpFields =  thisgrpFields + thisField;

                  });

                  $('.formBuilderConvertKitAccountName').html( "<option value='false'>None</option>" + thisgrpFields);
                  $('.ckSeqsContainer').css('display','block');
                  $('.ckSeqsLoader').text('');

                  $('.formBuilderConvertKitAccountName').val(pageBuilderApp.thisCkSelectedSeq);

                }else{
                  $('.ckSeqsLoader').text(parsedResult['error']);
                }
                
              }
          });
        }

  });

  $('#CC_ConnectButton').click(function(){
        $('.CC_loader').text('Connecting...');
        $('.CC_loader').show();
        var form = $('#aweberConnectForm');

        ccToken = $('.wfbCCAccessKey').val();

        $.ajax({
              url: "<?php echo admin_url('admin-ajax.php?action=ulpb_getConstantContactLists&POPB_nonce='.$plugOps_pageBuilder_data_nonce ); ?>&ccToken="+ccToken+"",
              method: 'post',
              data: form.serialize(),
              async:true,
              success: function(result){
                var parsedResult = JSON.parse(result);
                thisMcAllGroups = '';
                if (typeof( parsedResult['success']) != 'undefined' ) {
                  
                  CClists = parsedResult['success'];
                
                  thisCCLists = '';
                  $.each(CClists, function(index,val){

                    thisField = '<option value="'+index+'"> '+val +' </option>';
                    thisCCLists =  thisCCLists + thisField;

                  });

                  $('.wfbCCLists').html( thisCCLists);
                  $('.cc_token_container').css('display','none');
                  $('.cc_lists_container').css('display','block');
                  $('.CC_loader').text('connected');

                }else{

                  if (parsedResult['error'] == 'Unauthorized') {
                    $('.CC_loader').text( 'Please get a new access token and reconncet.');
                  }else{
                    $('.CC_loader').text(parsedResult['error']);
                  }

                }
                
              }
        });
  });

  $('#CC_ConnectButton').trigger('click');


});


})(jQuery);

</script>


<script type="text/javascript">
    ( function( $ ) {
        
        $(document).ready(function(){
            var isProActive = 'false';
            var rowBlockNames = {
                'RB-1':{
                    tempname : 1,
                    tempCat:'Header',
                    isPro:false,
                },
                'RB-2':{
                    tempname : 2,
                    tempCat:'Header',
                    isPro:false,
                },
                'RB-3':{
                    tempname : 3,
                    tempCat:'Header',
                    isPro:false,
                },
                'RB-4':{
                    tempname : 4,
                    tempCat:'Header',
                    isPro:false,
                },
                'RB-5':{
                    tempname : 5,
                    tempCat:'Feature',
                    isPro:false,
                },
                'RB-6':{
                    tempname : 6,
                    tempCat:'Feature Text',
                    isPro:false,
                },
                'RB-7':{
                    tempname : 7,
                    tempCat:'Feature',
                    isPro:false,
                },
                'RB-8':{
                    tempname : 8,
                    tempCat:'Feature Text',
                    isPro:false,
                },
                'RB-9':{
                    tempname : 9,
                    tempCat:'Text',
                    isPro:false,
                },
                'RB-10':{
                    tempname : 10,
                    tempCat:'Feature Text',
                    isPro:false,
                },
                'RB-11':{
                    tempname : 11,
                    tempCat:'Pricing',
                    isPro:false,
                },
                'RB-12':{
                    tempname : 12,
                    tempCat:'Pricing',
                    isPro:false,
                },
                'RB-13':{
                    tempname : 13,
                    tempCat:'Pricing',
                    isPro:false,
                },
                'RB-14':{
                    tempname : 14,
                    tempCat:'Pricing',
                    isPro:false,
                },
                'RB-15':{
                    tempname : 15,
                    tempCat:'Pricing',
                    isPro:false,
                },
                'RB-16':{
                    tempname : 16,
                    tempCat:'Testimonial',
                    isPro:false,
                },
                'RB-17':{
                    tempname : 17,
                    tempCat:'Feature',
                    isPro:false,
                },
                'RB-18':{
                    tempname : 18,
                    tempCat:'Footer Call To Action',
                    isPro:false,
                },
                'RB-19':{
                    tempname : 19,
                    tempCat:'Testimonial',
                    isPro:false,
                },
                'RB-20':{
                    tempname : 20,
                    tempCat:'Header',
                    isPro:false,
                },
                'RB-21':{
                    tempname : 21,
                    tempCat:'Call To Action',
                    isPro:false,
                },
                'RB-22':{
                    tempname : 22,
                    tempCat:'Call To Action',
                    isPro:false,
                },
                'RB-23':{
                    tempname : 23,
                    tempCat:'Feature',
                    isPro:false,
                },
                'RB-24':{
                    tempname : 24,
                    tempCat:'Feature',
                    isPro:false,
                },
                'RB-25':{
                    tempname : 25,
                    tempCat:'Testimonial',
                    isPro:false,
                },
                'RB-26':{
                    tempname : 26,
                    tempCat:'Pricing',
                    isPro:false,
                },
                'RB-27':{
                    tempname : 27,
                    tempCat:'Call To Action , Footer',
                    isPro:false,
                },
                'RB-28':{
                    tempname : 28,
                    tempCat:'Call To Action',
                    isPro:false,
                },
                'RB-29':{
                    tempname : 29,
                    tempCat:'Call To Action',
                    isPro:false,
                },
                'RB-30':{
                    tempname : 30,
                    tempCat:'Call To Action',
                    isPro:false,
                },
            };
            $.each(rowBlockNames, function(index,val){
                if (val['isPro'] == true  && isProActive == 'false') {
                    var insertBtn = '<div class="rowBlockProUpdateBtn" data-rowBlockName="'+'protemp'+'"> Pro <i class="fa fa-ban"></i> </div>';
                }else{
                    var insertBtn = '<div class="rowBlockUpdateBtn" data-rowBlockName="'+val['tempname']+'"> Insert <i class="fa fa-download" data-rowBlockName="'+val['tempname']+'" ></i> </div>';
                }

                $('#rowBlocksContainer').append(
                    '<div id="rowBlock" class="rowBlock-'+val['tempname']+' rowBlock template-card">'
                        +'<div id="rowBlock-'+val['tempname']+'" class="tempPrev"> <p id="rowBlock-'+val['tempname']+'"><b>Preview</b></p></div>'
                        +'<label for="rowBlock-'+val['tempname']+'"> <img src="<?php echo $rowBlockImagesURL; ?>'+val['tempname']+'.png" data-img_src="https://ps.w.org/page-builder-add/assets/screenshot-'+val['tempname']+'.png" class="card-img rowBlock-'+val['tempname']+'">'
                        +'<p class="card-desc"></p> </label>'
                        +insertBtn
                        +'<span class="block-cats-displayed">'+val['tempCat']+'</span>'
                    +'</div>'
                );
            });

            jQuery('.rowBlocksFilterSelector').on('change', function(){
                var WidgetSearchQuery =  jQuery(this).val();
                jQuery('.rowBlock').hide();
                
                jQuery('.rowBlock:contains("'+WidgetSearchQuery+'")').show();

                if (WidgetSearchQuery == 'All') {
                  jQuery('.rowBlock').show();
                }
            });


            if (isProActive == 'true') {
              $('.nonPremUserNotice').css('display','none');
            }
        });
    }( jQuery ) );
</script>


<div id="fontLoaderContainer"></div>
<link rel="stylesheet" type="text/css" href="<?php echo ULPB_PLUGIN_URL.'/styles/wooStyles.css'; ?>">
<style type="text/css">
  #PbaceEditorJS, #PbaceEditorCSS,#PbColaceEditorCSS, #PbPOaceEditorCSS, #PbPOaceEditorJS { 
        padding: 20px; margin: 20px;
        width: 80%; min-height: 450px;
    }
    #pbWrapper{
      display: none;
    }
</style>

<style type="text/css" id="POPBGlobalStylesTag"></style>

<style type="text/css" id="POPBDeafaultResponsiveStylesTag"></style>

<div id="allresponsiveScriptStylesTag" style="display: none !important;"></div>



<style>
      #popb_popup_close:before {
        transform: rotate(45deg);
      }
      #popb_popup_close:after {
        transform: rotate(-45deg);
      }
      #popb_popup_close:after, #popb_popup_close:before {
        background-color: #414141;
        content: '';
        position: absolute;
        left: 14px;
        height: 14px;
        top: 8px;
        width: 2px;
      }

      #popb_popup_close {
        width: 30px;
        height: 30px;
        background-color: #fff;
        border-radius: 100%;
        box-shadow: 0px 2px 2px 0px rgba(0,0,0,0.2);
        cursor: pointer;
        position: absolute;
        right: -15px;
        top: -15px;
        z-index: 2;
        float: right;
        clear: left;
      }

      .popb_popup_close:hover{
        background-color: #7a7a7a !important;
        transition: all .5s;
      }
      .popb_popup_close:hover::after, .popb_popup_close:hover::before {
        background-color: #fff !important;
        transition: all .5s;
      }
</style>

<?php

$checkPBactive = get_post_meta( $postId, 'ulpb_page_builder_active', 'true');
if ($checkPBactive == 'true') {
  ?>
  <style type="text/css">
      #submitdiv,.wp-editor-expand,.fl-builder-admin{
        display: none !important;
      }
      .AdvancedOption{
        display: none;
      }
  </style>

  <script type="text/javascript">
    (function($){

      jQuery('.tab-editor-deactivate').on('click', function(e)  {

        $('#SavePageOther').trigger('click');
          var submit_URl = "<?php echo admin_url('admin-ajax.php?action=ulpb_activate_pb_request&page_id='.$postId.'ulpbActivate=DeactivatePB').'&POPB_Switch_Nonce='.$plugOps_pageBuilder_switch_nonce; ?>";
          var result = " ";
          $.ajax({
              url: submit_URl,
              method: 'get',
              data: '',
              success: function(result){
                setTimeout(function(){
                   window.location.href = admURL+'post.php?post='+P_ID+'&action=edit';
                }, 1600);
              }
          });
           
          // Prevents default submission of the form after clicking on the submit button. 
          return false;   
      });

    })(jQuery);
  </script>
  <style type="text/css">
    .switch_button{
      margin-top:20px;
      text-decoration: none;
      background-color: #333;
        border-radius: 3px;
        border: none;
        padding: 10px 20px 10px 20px;
        color: #FFF;
        font-size: 16px;
        float: left;
        cursor: pointer;
    }
  </style>
  <?php 
}


?>