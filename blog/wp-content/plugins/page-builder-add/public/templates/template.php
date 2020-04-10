 <?php if ( ! defined( 'ABSPATH' ) ) exit;

error_reporting(0);

if (defined('AUTOPTIMIZE_PLUGIN_VERSION') ) {

  if (! function_exists('pluginops_pb_ao_override_jsexclude')) {
    add_filter('autoptimize_filter_js_exclude','pluginops_pb_ao_override_jsexclude',10,1);
    function pluginops_pb_ao_override_jsexclude($exclude) {
      return $exclude.", jquery.js, jquery.min.js, moment.js , cookie.js , jquery-ui.js , fa.js , video.js , slider.min.js , countdown.js , moment-timezone-with-data-2010-2020.min.js ,  moment.min.js , owl.carousel.js";
    }
  }
    

  if ( !function_exists('pluginops_pb_ao_css_include_inline')) {
    add_filter('autoptimize_css_include_inline','pluginops_pb_ao_css_include_inline',10,1);
    function pluginops_pb_ao_css_include_inline() {
        return false;
    }
  }
  
}


$landingPageLinkTrackingFeature = get_option( 'landingPageLinkTrackingFeature', false );
if ($landingPageLinkTrackingFeature != 'disabled' || $landingPageLinkTrackingFeature == false) {
  $landingPageLinkTrackingFeatureisEnabled = true;
}else{
  $landingPageLinkTrackingFeatureisEnabled = false;
}


if ( isset($_GET['popb_track_url']) && isset($_GET['popb_pID']) ) {
  $popb_track_url = sanitize_text_field( $_GET['popb_track_url'] );
  $post_id = sanitize_text_field( $_GET['popb_pID'] );

  if (!empty($popb_track_url) && !empty($post_id)) {
    $postStatus = get_post_status( $post_id );
    if ($postStatus == 'publish') {

      $allowed = array();
      $pluginOpsUserTimeZone = get_option('timezone_string');
      date_default_timezone_set($pluginOpsUserTimeZone);
      $todaysDate = date('d-m-Y');

      $ctnTotal = get_post_meta($post_id,'ctnTotal',true);
      $ctnTotal++;
      $updateResultConversionCount = update_post_meta( $post_id, 'ctnTotal', $ctnTotal, $unique = false);
      $ctrLinks = get_post_meta($post_id,'ctrTpLinks',true);

      if (! is_array($ctrLinks)) {
        $ctrLinks = array();
      }

      if (!isset($ctrLinks[$popb_track_url])) {
        $ctrLinks[$popb_track_url] = array();
      }

      if (!isset( $ctrLinks[$popb_track_url][$todaysDate] )) {
        $ctrLinks[$popb_track_url][$todaysDate] = 0;
      }

      $ctrLinks[$popb_track_url][$todaysDate]++;
      update_post_meta( $post_id, 'ctrTpLinks', $ctrLinks, $unique = false);

    }
    
    if ( headers_sent() ) {
      echo "<script> location.href = '$popb_track_url' </script>";
    }else{
      header('Location: ' . $popb_track_url, true, 302);
    }
    
    exit();

  }
}

$current_pageID = $post->ID;

if (isset($isShortCodeTemplate)) {
  if ($isShortCodeTemplate == true) {
    $current_pageID = $template_id;
  }
} else{ 
  $isShortCodeTemplate = '';
}


// if (!isset($data['pageOptions'])) {
//   return;
// }


$data = get_post_meta( $current_pageID, 'ULPB_DATA', true );

if (!empty($data)) {
  $currentPageTitle = $data['pageOptions']['pageSeoName'];
}
$current_postType = get_post_type( $current_pageID );


$currentVariant = 'A';
$abTestingActive = false;
if (isset($data['pageOptions']['MultiVariantTesting']) && $data['pageOptions']['MultiVariantTesting'] != null ) {

  $VariantB_ID = $data['pageOptions']['MultiVariantTesting']['VariantB'];
  $VariantC_ID = $data['pageOptions']['MultiVariantTesting']['VariantC'];
  $VariantD_ID = $data['pageOptions']['MultiVariantTesting']['VariantD'];
  
  if ( ($VariantB_ID != 'none' && $VariantB_ID != null && $VariantB_ID != '') || ($VariantC_ID != 'none' && $VariantC_ID != null && $VariantC_ID != '') || ($VariantD_ID != 'none' && $VariantD_ID != null && $VariantD_ID != '') ) {
    include 'ab-lib/phpab.php';
    $startAbTest = new phpab('AbTestOne');

    if ($VariantB_ID != 'none' && $VariantB_ID != null && $VariantB_ID != '') {
      $startAbTest->add_variation('variantb');
      $abTestingActive = true;
    }
    if ($VariantC_ID != 'none' && $VariantC_ID != null && $VariantC_ID != '') {
      $startAbTest->add_variation('variantc');
    }
    if ($VariantD_ID != 'none' && $VariantD_ID != null && $VariantD_ID != '') {
      $startAbTest->add_variation('variantd');
    }
   // var_dump($startAbTest->get_user_segment());

    if ($startAbTest->get_user_segment() == 'variantb') {
      $data = get_post_meta( $VariantB_ID, 'ULPB_DATA', true );
      $current_pageID = $VariantB_ID;
      $currentVariant = 'B';
    }else if ($startAbTest->get_user_segment() == 'variantc') {
      $data = get_post_meta( $VariantC_ID, 'ULPB_DATA', true );
      $current_pageID = $VariantC_ID;
      $currentVariant = 'C';
    }else if ($startAbTest->get_user_segment() == 'variantd') {
      $data = get_post_meta( $VariantD_ID, 'ULPB_DATA', true );
      $current_pageID = $VariantD_ID;
      $currentVariant = 'D';
    }else{
      $data = get_post_meta( $current_pageID, 'ULPB_DATA', true );
    }
  }
}

if (isset( $data['RowsDivided'] )) {
  if ($data['RowsDivided'] > 0 ) {
    $rowsCollection = array();
    for($i = 0; $i< $data['RowsDivided']; $i++ ){
      $theseRows =  get_post_meta($current_pageID, 'ULPB_DATA_Rows_Part_'.$i, true);
      foreach ($theseRows as $value) {
        array_push($data['Rows'], $value );
      }
    }
  }
}

$lp_thisPostType = get_post_type( $current_pageID );
  
  $landingpagejQueryloadScripts = '<script type="text/javascript" src="'.ULPB_PLUGIN_URL.'/js/jquery.min.js"></script>' . '<script type="text/javascript" src="'.ULPB_PLUGIN_URL.'/js/jquery-ui.js"></script>' . '<link rel="stylesheet" type="text/css" href="'.ULPB_PLUGIN_URL.'/js/Backbone-resources/jquery-ui.css">';

  if ( $lp_thisPostType != 'ulpb_post') {
    $landingpagejQueryloadScripts = '' . '<script type="text/javascript" src="'.ULPB_PLUGIN_URL.'/js/jquery-ui.js"></script>' . '<link rel="stylesheet" type="text/css" href="'.ULPB_PLUGIN_URL.'/js/Backbone-resources/jquery-ui.css">';
  }

  $widgetAnimationTriggerScript = '';
  $widgetCounterLoadScripts = false;
  $widgetCountDownLoadScripts = false;
  $widgetSliderLoadScripts = false;
  $widgetFALoadScripts = false;
  $widgetVideoLoadScripts = false;
  $widgetOwlLoadScripts = false;
  $widgetWooCommLoadScripts = false;
  $widgetPostsSliderExternalScripts = false;
  $widgetSubscribeFormWidget = false;
  $shapesinluded = false;
  $widgetMasonryLoadScripts = false;
  $widgetJQueryLoadScripts = false;
  $widget_postsSliderLibrary = false;



  $POPBNallRowStyles = array();
  $POPBNallRowStylesResponsiveTablet = array();
  $POPBNallRowStylesResponsiveMobile = array();
  $POPBallColumnStylesArray = array();
  $POPBallWidgetsStylesArray = array();
  $POPBallWidgetsScriptsArray = array();
  $thisColFontsToLoad = array();
  $POPB_popups_loaded = array();
  $widgetTextFontsBulk = array();





if (!empty($data)) {
  include('header.php');
?>

<?php 
  if ($current_postType == 'post' || $current_postType == 'page' || $isShortCodeTemplate == true ){ echo "<div class='ulpb_PageBody ulpb_PageBody_$current_pageID'>";
  }else{ echo "<body class='ulpb_PageBody ulpb_PageBody_$current_pageID'>";  echo "<div id='fullPageBgOverlay_$current_pageID' class='fullPageBgOverlay' style='height: 100%; top: 0 !important; bottom:0 !important; left: 0 !important; right:0 !important;  width: 100%; position: fixed;'></div>";
  }
?>

  <?php

  $ulpb_global_tracking_scriptsBodyTag = get_option( 'ulpb_global_tracking_scriptsBodyTag' );

  echo " $ulpb_global_tracking_scriptsBodyTag ";

  $rows = $data['Rows'];

  $rowCount = 0;

  

  foreach ($rows as $row) {

    if (isset($row['globalRow'])) {

      if (isset($row['globalRow']['globalRowPid'])) {
        $isGlobalRow = $row['globalRow']['isGlobalRow'];
        if ($isGlobalRow == true) {
          $globalRowPostData = get_post_meta( $row['globalRow']['globalRowPid'], 'ULPB_DATA', true );
          $row = $globalRowPostData['Rows'][0]; 
        }
      }
    }
    $rowID = $row["rowID"];
  	$columns = $row['columns'];
  	$rowHeight = $row['rowHeight'];
  	$rowData = $row['rowData'];
    $rowMargins = $rowData['margin'];
    $rowPadding = $rowData['padding'];
  	$rowBgColor = $rowData['bg_color'];
  	$rowBgImg = $rowData['bg_img'];
    $currentRowcustomCss = $rowData['customStyling'];
    $currentRowcustomJS = $rowData['customJS'];

    $rowMarginTop = $rowMargins['rowMarginTop'];
    $rowMarginBottom = $rowMargins['rowMarginBottom'];
    $rowMarginLeft = $rowMargins['rowMarginLeft'];
    $rowMarginRight = $rowMargins['rowMarginRight'];

    $rowPaddingTop = $rowPadding['rowPaddingTop'];
    $rowPaddingBottom = $rowPadding['rowPaddingBottom'];
    $rowPaddingLeft = $rowPadding['rowPaddingLeft'];
    $rowPaddingRight = $rowPadding['rowPaddingRight'];

    if (!isset($row['rowHeightUnit']) ) {
      $rowHeightUnit = 'px';
    }else{  
      if ($row['rowHeightUnit'] == '') {
        $rowHeightUnit = 'px';
      } else{
        $rowHeightUnit = $row['rowHeightUnit'];
      }
    }

    if (isset($row['rowHeightTablet']) ) {
      $rowHeightTablet = $row['rowHeightTablet'];
      $rowHeightUnitTablet = $row['rowHeightUnitTablet'];
      $rowHeightMobile = $row['rowHeightMobile'];
      $rowHeightUnitMobile = $row['rowHeightUnitMobile'];
    }else{
      $rowHeightTablet = '';
      $rowHeightUnitTablet = '';
      $rowHeightMobile = '';
      $rowHeightUnitMobile = '';
    }

    $rowBackgroundParallax = '';
    if (isset($rowData['rowBackgroundParallax'])) {
      if ($rowData['rowBackgroundParallax'] == 'true') {
        $rowBackgroundParallax = 'background-attachment:fixed !important;';
      }
    }else{
      $rowData['rowBackgroundParallax'] = '';
    }

    if ($rowBgImg != '' && $rowBgColor == '' ) {
      $rowBgColor = 'transparent';
    }


    $currRowDefaultBackgroundOps = ''; 
    if ( isset($rowData['bgImgOps']) ) {

      $drbgImgOps = $rowData['bgImgOps'];

      $defaultRowBgImg = $rowData['bg_img'];
      $tabletRowBgImg = $rowData['bg_imgT'];
      $mobileRowBgImg = $rowData['bg_imgM'];
      if ($tabletRowBgImg == '') { $tabletRowBgImg = $defaultRowBgImg; }
      if ($mobileRowBgImg == '') { $mobileRowBgImg = $tabletRowBgImg; }


      $defaultRowBgFixed = 'scroll';
      if ($rowData['rowBackgroundParallax'] == 'true') { $defaultRowBgFixed = 'fixed'; }
      $tabletRowBgFixed = $defaultRowBgFixed; $mobileRowBgFixed = $defaultRowBgFixed;
      if ($drbgImgOps['parlxT'] == 'true') { $tabletRowBgFixed = 'fixed'; }
      if ($drbgImgOps['parlxT'] == 'false') { $tabletRowBgFixed = 'scroll'; }
      if ($drbgImgOps['parlxM'] == 'true') { $mobileRowBgFixed = 'fixed'; }
      if ($drbgImgOps['parlxM'] == 'false') { $mobileRowBgFixed = 'scroll'; }

      $drbgImgOpsRep = $drbgImgOps['rep'];
      $drbgImgOpsRepT = $drbgImgOps['repT'];
      $drbgImgOpsRepM = $drbgImgOps['repM'];

      // desktop
      if ($drbgImgOps['pos'] == 'custom') {
        $defaultBgImgPos = 
        "background-position-x: ".$drbgImgOps['xPos'].$drbgImgOps['xPosU']. "; " . 
        "background-position-y: ".$drbgImgOps['yPos'].$drbgImgOps['yPosU']. "; ";
      }else{
        $defaultBgImgPos = "background-position: ".$drbgImgOps['pos']."; ";
      }

      if ( $drbgImgOpsRep == '' || $drbgImgOpsRep == 'default') { $drbgImgOpsRep = 'no-repeat'; }

      if ($drbgImgOps['size'] == 'custom') {
        $defaultBgImgSize = "background-size: ".$drbgImgOps['cWid'].$drbgImgOps['widU']."; ";
      }else{
        $defaultBgImgSize = "background-size: ".$drbgImgOps['size']."; ";
      }
        
      $currRowDefaultBackgroundOps = "
          background-color:$rowBgColor ;
          background-image: url($defaultRowBgImg);
          background-repeat: $drbgImgOpsRep;
          background-attachment: $defaultRowBgFixed;
          $defaultBgImgPos
          $defaultBgImgSize
        
      ";

         



      // Tablet
      if ($drbgImgOps['posT'] == 'custom') {
          $tabletBgImgPos = "background-position-x: ".$drbgImgOps['xPosT'].$drbgImgOps['xPosUT']. "; " .
           "background-position-y: ".$drbgImgOps['yPosT'].$drbgImgOps['yPosUT']. "; ";
      } else if($drbgImgOps['posT'] == ''){
        $tabletBgImgPos = $defaultBgImgPos;
      }else{
        $tabletBgImgPos = "background-position: ".$drbgImgOps['posT']."; ";
      }

      if ($drbgImgOpsRepT == '' || $drbgImgOpsRepT == 'default') { $drbgImgOpsRepT = $drbgImgOpsRep; }


      if ($drbgImgOps['sizeT'] == 'custom') {
        $tabletBgImgSize = "background-size: ".$drbgImgOps['cWidT'].$drbgImgOps['widUT']."; ";
      }else if($drbgImgOps['sizeM'] == ''){
        $tabletBgImgSize = $defaultBgImgSize;
      }else{
        $tabletBgImgSize = "background-size: ".$drbgImgOps['sizeT']."; ";
      }
      

      $currRowtabletBackgroundOps = "
        #".$row["rowID"]." {
          background-image: url($tabletRowBgImg) !important;
          background-repeat: $drbgImgOpsRepT !important;
          background-attachment: $tabletRowBgFixed !important;
          $tabletBgImgPos
          $tabletBgImgSize
        }
      ";




      // mobile
      if ($drbgImgOps['posM'] == 'custom') {
        $mobileBgImgPos = 
        "background-position-x: ".$drbgImgOps['xPosM'].$drbgImgOps['xPosUM']. "; " . 
        "background-position-y: ".$drbgImgOps['yPosM'].$drbgImgOps['yPosUM']. "; ";
      }else if($drbgImgOps['posT'] == ''){
        $mobileBgImgPos = $tabletBgImgPos;
      }else{
        $mobileBgImgPos = "background-position: ".$drbgImgOps['posM']."; ";
      }

      if ($drbgImgOpsRepM == '' || $drbgImgOpsRepM == 'default') { $drbgImgOpsRepM = $drbgImgOpsRepT; }

      if ($drbgImgOps['sizeM'] == 'custom') {
        $mobileBgImgSize = "background-size: ".$drbgImgOps['cWidM'].$drbgImgOps['widM']."; ";
      }else if($drbgImgOps['sizeM'] == ''){
        $mobileBgImgSize = $tabletBgImgSize;
      }else{
        $mobileBgImgSize = "background-size: ".$drbgImgOps['sizeM']."; ";
      }

      $currRowmobileBackgroundOps = "
        #".$row["rowID"]." {
          background-image: url($mobileRowBgImg) !important;
          background-repeat: $drbgImgOpsRepM !important;
          background-attachment: $mobileRowBgFixed !important;
          $mobileBgImgPos
          $mobileBgImgSize
        }
      ";


      if ($rowData['rowBackgroundType'] !== 'gradient') {
        array_push($POPBNallRowStylesResponsiveTablet, $currRowtabletBackgroundOps);
        array_push($POPBNallRowStylesResponsiveMobile, $currRowmobileBackgroundOps);
      }
      
        
    } // latest row bg options ends 


    $rowBackgroundOptions  = "background-image:url($rowBgImg); background-repeat:no-repeat; background-position:center center; background-size:cover; background-color:$rowBgColor ; "; // old row bg ops

    if ($currRowDefaultBackgroundOps != '') {  $rowBackgroundOptions = $currRowDefaultBackgroundOps;  } // set latest row bg options if available

    if (isset($rowData['rowBackgroundType'])) {
      if ($rowData['rowBackgroundType'] == 'gradient') {
        $rowGradient = $rowData['rowGradient'];
        if ($rowGradient['rowGradientType'] == 'linear') {
          $rowBackgroundOptions = 'background: linear-gradient('.$rowGradient['rowGradientAngle'].'deg, '.$rowGradient['rowGradientColorFirst'].' '.$rowGradient['rowGradientLocationFirst'].'%,'.$rowGradient['rowGradientColorSecond'].' '.$rowGradient['rowGradientLocationSecond'].'%) ;';
        }
        if ($rowGradient['rowGradientType'] == 'radial') {
          $rowBackgroundOptions = 'background: radial-gradient(at '.$rowGradient['rowGradientPosition'].', '.$rowGradient['rowGradientColorFirst'].' '.$rowGradient['rowGradientLocationFirst'].'%,'.$rowGradient['rowGradientColorSecond'].' '.$rowGradient['rowGradientLocationSecond'].'%) ;';
        }
      }
    }

    $rowOverlayBackgroundOptions = '';
    if (isset($rowData['rowBgOverlayColor'])) {
      $rowOverlayBackgroundOptions  = " background:".$rowData['rowBgOverlayColor']." ; background-color:".$rowData['rowBgOverlayColor']." ;";
    }
    if (isset($rowData['rowOverlayBackgroundType'])) {
      if ($rowData['rowOverlayBackgroundType'] == 'gradient') {
        $rowOverlayGradient = $rowData['rowOverlayGradient'];
        if ($rowOverlayGradient['rowOverlayGradientType'] == 'linear') {
          $rowOverlayBackgroundOptions = 'background: linear-gradient('.$rowOverlayGradient['rowOverlayGradientAngle'].'deg, '.$rowOverlayGradient['rowOverlayGradientColorFirst'].' '.$rowOverlayGradient['rowOverlayGradientLocationFirst'].'%,'.$rowOverlayGradient['rowOverlayGradientColorSecond'].' '.$rowOverlayGradient['rowOverlayGradientLocationSecond'].'%) ;';
        }
        if ($rowOverlayGradient['rowOverlayGradientType'] == 'radial') {
          $rowOverlayBackgroundOptions = 'background: radial-gradient(at '.$rowOverlayGradient['rowOverlayGradientPosition'].', '.$rowOverlayGradient['rowOverlayGradientColorFirst'].' '.$rowOverlayGradient['rowOverlayGradientLocationFirst'].'%,'.$rowOverlayGradient['rowOverlayGradientColorSecond'].' '.$rowOverlayGradient['rowOverlayGradientLocationSecond'].'%) ;';
        }
      }
    }


    $thisRowHoverOption = '';
    if (isset($rowData['rowHoverOptions'])) {
        $rowHoverOptions = $rowData['rowHoverOptions'];
        if (isset($rowHoverOptions['rowBackgroundTypeHover'])) {
          if ($rowHoverOptions['rowBackgroundTypeHover'] == 'solid') {
            $thisRowHoverOption = ' #'.$row['rowID'].':hover { background:'.$rowHoverOptions['rowBgColorHover'].' !important; transition: all '.$rowHoverOptions['rowHoverTransitionDuration'].'s; }';
          }
          if ($rowHoverOptions['rowBackgroundTypeHover'] == 'gradient') {
            $rowGradientHover = $rowHoverOptions['rowGradientHover'];

            if ($rowGradientHover['rowGradientTypeHover'] == 'linear') {
              $thisRowHoverOption = ' #'.$row['rowID'].':hover { background: linear-gradient('.$rowGradientHover['rowGradientAngleHover'].'deg, '.$rowGradientHover['rowGradientColorFirstHover'].' '.$rowGradientHover['rowGradientLocationFirstHover'].'%,'.$rowGradientHover['rowGradientColorSecondHover'].' '.$rowGradientHover['rowGradientLocationSecondHover'].'%) !important; transition: all '.$rowHoverOptions['rowHoverTransitionDuration'].'s; }';
            }

            if ($rowGradientHover['rowGradientTypeHover'] == 'radial') {

              $thisRowHoverOption = ' #'.$row['rowID'].':hover { background: radial-gradient(at '.$rowGradientHover['rowGradientPositionHover'].', '.$rowGradientHover['rowGradientColorFirstHover'].' '.$rowGradientHover['rowGradientLocationFirstHover'].'%,'.$rowGradientHover['rowGradientColorSecondHover'].' '.$rowGradientHover['rowGradientLocationSecondHover'].'%) !important; transition: all '.$rowHoverOptions['rowHoverTransitionDuration'].'s; }';
            }
          }
        }

      }


    $rowCustomClass ='';
    if (isset($rowData['rowCustomClass'])) {
      $rowCustomClass = $rowData['rowCustomClass'];
    }

    $rowHideOnDesktop = "display:block"; $rowHideOnTablet = "display:block"; $rowHideOnMobile = "display:block";
    if (isset($rowData['rowHideOnDesktop']) ) {
      if ($rowData['rowHideOnDesktop'] == 'hide') {
        $rowHideOnDesktop ="display:none";
      }
      if ($rowData['rowHideOnTablet'] == 'hide') {
        $rowHideOnTablet ="display:none !important;";
      }
      if ($rowData['rowHideOnMobile'] == 'hide') {
        $rowHideOnMobile ="display:none !important;";
      }
    }
    
    if (isset($rowData['marginTablet'])) {

      $rowMarginTablet = $rowData['marginTablet'];
      $rowMarginMobile = $rowData['marginMobile'];
      $rowPaddingTablet = $rowData['paddingTablet'];
      $rowPaddingMobile = $rowData['paddingMobile'];
      
      $thisRowResponsiveRowStylesTablet = "
        #".$row["rowID"]." {
         margin-top: ".$rowMarginTablet['rMTT']."% !important;
         margin-bottom: ".$rowMarginTablet['rMBT']."% !important;
         margin-left: ".$rowMarginTablet['rMLT']."% !important;
         margin-right: ".$rowMarginTablet['rMRT']."% !important;

         padding-top: ".$rowPaddingTablet['rPTT']."% !important;
         padding-bottom: ".$rowPaddingTablet['rPBT']."% !important;
         padding-left: ".$rowPaddingTablet['rPLT']."% !important;
         padding-right: ".$rowPaddingTablet['rPRT']."% !important;

         min-height:".$rowHeightTablet."$rowHeightUnitTablet !important;
         $rowHideOnTablet
        }
      
      ";
      $thisRowResponsiveRowStylesMobile = "
      
        #".$row["rowID"]." {
         margin-top: ".$rowMarginMobile['rMTM']."% !important;
         margin-bottom: ".$rowMarginMobile['rMBM']."% !important;
         margin-left: ".$rowMarginMobile['rMLM']."% !important;
         margin-right: ".$rowMarginMobile['rMRM']."% !important;

         padding-top: ".$rowPaddingMobile['rPTM']."% !important;
         padding-bottom: ".$rowPaddingMobile['rPBM']."% !important;
         padding-left: ".$rowPaddingMobile['rPLM']."% !important;
         padding-right: ".$rowPaddingMobile['rPRM']."% !important;

         min-height:".$rowHeightMobile."$rowHeightUnitMobile !important;
         $rowHideOnMobile
        }
      ";

      array_push($POPBNallRowStylesResponsiveTablet, $thisRowResponsiveRowStylesTablet);
      array_push($POPBNallRowStylesResponsiveMobile, $thisRowResponsiveRowStylesMobile);
    }

    $rowMarginStyle = "margin:$rowMarginTop"."% $rowMarginRight"."% $rowMarginBottom"."% $rowMarginLeft"."%;";

    $rowPaddingStyle = "padding:$rowPaddingTop"."% $rowPaddingRight"."% $rowPaddingBottom"."% $rowPaddingLeft"."%;";

  	$currentRowStyles = "#".$row["rowID"]."{   min-height:$rowHeight"."$rowHeightUnit; $rowPaddingStyle  $rowMarginStyle  $rowBackgroundOptions  $rowBackgroundParallax  $currentRowcustomCss  $rowHideOnDesktop }     $thisRowHoverOption ";

    array_push($POPBNallRowStyles, $currentRowStyles);

  	//echo($row['rowID']."<br>");
  	

  	?>

    <script type="text/javascript">
      <?php echo $currentRowcustomJS; ?>
    </script>
  	<div class='pops-row w3-row  <?php echo $rowCustomClass ?>' data-row_id='<?php echo $row["rowID"]; ?>' id='<?php echo $row["rowID"]; ?>'>
      <div class="overlay-row" style="<?php echo "$rowOverlayBackgroundOptions"; ?>"></div>

      <?php

        if (isset($rowData['bgSTop']) ) {
          $bgSTop = $rowData['bgSTop'];
          $bgShapeTop = '';
          $rowID = $row["rowID"];
          $positionID  = 'top';
          $shapeType = $bgSTop['rbgstType'];
          if ($shapesinluded == false) {
            include_once 'svgShapes.php';
            $shapesinluded = true;
          }

          $invertTransform = '';
          if ($shapeType == 'random' ) {
            $invertTransform = 'transform:rotate(180deg); -webkit-transform:rotate(180deg);  -moz-transform:rotate(180deg);  -ms-transform:rotate(180deg);';
          }

          if (function_exists('bgSvgShapesRenderCode') ) {
            $bgShapesArray = bgSvgShapesRenderCode($rowID, $positionID, $shapeType);
            $bgShapeTop = $bgShapesArray['shape'];
            $vieBoxAttr = $bgShapesArray['shapeAttr'];
          }

          $renderredHTML = '';
          $returnScripts = '';

          
          if ($bgSTop != 'false') {
            $isFlipped = '';
            if ($bgSTop['rbgstFlipped'] == 'true') {
              $isFlipped = 'transform:rotateY(180deg); -webkit-transform:rotateY(180deg);  -moz-transform:rotateY(180deg);  -ms-transform:rotateY(180deg);';
            }

            if ($bgSTop['rbgstType'] == 'none') {
              $bgShapeTop = '';
            }
            $onFront = '';
            if ($bgSTop['rbgstFront'] == 'true') {
              $onFront = 'z-index:2;'; 
            }

            if ($bgShapeTop != '') {

              $renderredShapeHTML = 
              '<div class="bgShapes bgShapeTop-'.$row["rowID"].'"  style="overflow: hidden; position: absolute; left: 0; width: 100%; direction: ltr; top: -2px; text-align:center; '.$onFront.' '.$invertTransform.' ">'.
                  '<svg xmlns="http://www.w3.org/2000/svg" viewBox="'.$vieBoxAttr.'" preserveAspectRatio="none" style="width: calc('.$bgSTop['rbgstWidth'].'% + 1.5px); height: '.$bgSTop['rbgstHeight'].'px;  position: relative; '.$isFlipped.'" >'.
                  $bgShapeTop.
                '</svg>'.
              ' <style>  .po-top-path-'.$row["rowID"].' { fill:'.$bgSTop['rbgstColor'].'; } </style> </div>  ';

              echo "$renderredShapeHTML";

              $thisShapeResponsiveTablet = "
                .bgShapeTop-".$row["rowID"]." svg {
                  width: calc(".$bgSTop['rbgstWidtht']."% + 1.5px) !important;
                  height:".$bgSTop['rbgstHeightt']."px !important;
                }
              ";

              $thisShapeResponsiveMobile = "
                .bgShapeTop-".$row["rowID"]." svg {
                  width: calc(".$bgSTop['rbgstWidthm']."% + 1.5px) !important;
                  height:".$bgSTop['rbgstHeightm']."px !important;
                }
              ";
              array_push($POPBNallRowStylesResponsiveTablet, $thisShapeResponsiveTablet);
              array_push($POPBNallRowStylesResponsiveMobile, $thisShapeResponsiveMobile);


            }
          }

        }

        if (isset($rowData['bgSBottom']) ) {
          $bgSBottom = $rowData['bgSBottom'];
          $bgShapeBottom = '';
          $rowID = $row["rowID"];
          $positionID  = 'bottom';
          $shapeType = $bgSBottom['rbgsbType'];
          if ($shapesinluded == false) {
            include_once 'svgShapes.php';
            $shapesinluded = true;
          }

          $invertTransform = '';
          if ($shapeType == 'random' ) {
            $invertTransform = 'transform:rotate(0deg); -webkit-transform:rotate(0deg);  -moz-transform:rotate(0deg);  -ms-transform:rotate(0deg);';
          }

          if (function_exists('bgSvgShapesRenderCode') ) {
            $bgShapesArray = bgSvgShapesRenderCode($rowID, $positionID, $shapeType);
            $bgShapeBottom = $bgShapesArray['shape'];
            $vieBoxAttr = $bgShapesArray['shapeAttr'];
          }

          $renderredHTML = '';
          $returnScripts = '';

          
          if ($bgSBottom != 'false') {
            $isFlipped = '';
            if ($bgSBottom['rbgsbFlipped'] == 'true') {
              $isFlipped = 'transform:rotateY(180deg); -webkit-transform:rotateY(180deg);  -moz-transform:rotateY(180deg);  -ms-transform:rotateY(180deg);';
            }

            if ($bgSBottom['rbgsbType'] == 'none') {
              $bgShapeBottom = '';
            }
            $onFront = '';
            if ($bgSBottom['rbgsbFront'] == 'true') {
              $onFront = 'z-index:2;'; 
            }

            if ($bgShapeBottom != '') {

              $renderredShapeHTML = 
              '<div class="bgShapes bgShapeBottom-'.$row["rowID"].'"  style="overflow: hidden; position: absolute; left: 0; width: 100%; direction: ltr;  bottom: -1px; transform: rotate(180deg); -webkit-transform: rotate(180deg);  -moz-transform: rotate(180deg);  -ms-transform: rotate(180deg);  text-align:center; '.$onFront.' '.$invertTransform.' ">'.
                  '<svg xmlns="http://www.w3.org/2000/svg" viewBox="'.$vieBoxAttr.'" preserveAspectRatio="none" style="width: calc('.$bgSBottom['rbgsbWidth'].'% + 1.5px); height: '.$bgSBottom['rbgsbHeight'].'px;  position: relative; '.$isFlipped.'" >'.
                  $bgShapeBottom.
                '</svg>'.
              ' <style>  .po-bottom-path-'.$row["rowID"].' { fill:'.$bgSBottom['rbgsbColor'].'; } </style> </div>  ';

              echo "$renderredShapeHTML";

              $thisShapeResponsiveTablet = "
                .bgShapeBottom-".$row["rowID"]." svg {
                  width: calc(".$bgSBottom['rbgsbWidtht']."% + 1.5px) !important;
                  height:".$bgSBottom['rbgsbHeightt']."px !important;
                }
              ";

              $thisShapeResponsiveMobile = "
                .bgShapeBottom-".$row["rowID"]." svg {
                  width: calc(".$bgSBottom['rbgsbWidthm']."% + 1.5px) !important;
                  height:".$bgSBottom['rbgsbHeightm']."px !important;
                }
              ";
              array_push($POPBNallRowStylesResponsiveTablet, $thisShapeResponsiveTablet);
              array_push($POPBNallRowStylesResponsiveMobile, $thisShapeResponsiveMobile);


            }
          }

        }

      ?>

      

        <?php
          if (isset($rowData['video'])) {
            $rowVideo = $rowData['video'];
            $rowBgVideoEnable = $rowVideo['rowBgVideoEnable'];
            if ($rowBgVideoEnable == 'true') {
              $rowBgVideoLoop = $rowVideo['rowBgVideoLoop'];
              $rowVideoMpfour = $rowVideo['rowVideoMpfour'];
              $rowVideoWebM = $rowVideo['rowVideoWebM'];
              $rowVideoThumb = $rowVideo['rowVideoThumb'];

              if ( !isset($rowVideo['rowVideoType']) ) { $rowVideo['rowVideoType'] = ''; }

              if ($rowVideo['rowVideoType'] == 'yt') {
                 $YtregExp = "/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/";
                 $YTurlMatch = preg_match($YtregExp, $rowVideo['rowVideoYtUrl'],$YTurlMatches);
                 if($YTurlMatch == 1){
                  $ytvidId =  $YTurlMatches[7];
                 }else{
                  $ytvidId = 'false';
                 }
                $VideoBgHtml = '<iframe id="bgVid-'.$row["rowID"].'" width="100%" height="100%" src="https://www.youtube.com/embed/'.$ytvidId.'?rel=0&amp;controls=0&amp;showinfo=0;mute=1;autoplay=1&loop=1&playlist='.$ytvidId.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen ></iframe>';
                echo "$VideoBgHtml";
                ?>
                  <style type="text/css">
                    #bgVid-<?php echo $row["rowID"]; ?> { 
                      position: absolute;
                      min-width: 100%;
                      min-height: 100%;
                      width: auto;
                      height: auto;
                      z-index: -100;
                      background: url('<?php echo $rowVideoThumb; ?>') no-repeat;
                      background-size: cover;
                      transition: 1s opacity;
                      left: 0;
                      right: 0;
                      top: 0;
                    }
                    <?php echo '#'.$row["rowID"]; ?> {
                      background: transparent !important;
                      background-color: transparent !important;
                    }
                  </style>
                <?php
              }else{

                ?>
                  <video poster="<?php echo $rowVideoThumb; ?>" id="bgVid-<?php echo $row["rowID"]; ?>" playsinline autoplay muted <?php echo $rowBgVideoLoop; ?> >
                    <source src="<?php echo $rowVideoWebM; ?>" type="video/webm">
                    <source src="<?php echo $rowVideoMpfour; ?>" type="video/mp4">
                  </video>
                  <style type="text/css">
                    #bgVid-<?php echo $row["rowID"]; ?> { 
                      position: absolute;
                      min-width: 100%;
                      min-height: 100%;
                      width: auto;
                      height: auto;
                      z-index: -100;
                      background: url('<?php echo $rowVideoThumb; ?>') no-repeat;
                      background-size: cover;
                      transition: 1s opacity;
                      left: 0; 
                      right: 0;
                      top: 0;
                    }
                    <?php echo '#'.$row["rowID"]; ?> {
                      background: transparent !important;
                      background-color: transparent !important;
                      overflow: hidden;
                      position: relative;
                    }
                  </style>

                <?php
              }

            }
            
          }
        ?>

        <?php
          $columnContainerSetWidth = 'max-width:100% !important;';
          if (isset($rowData['conType'])) {
            if ($rowData['conType'] == 'boxed') {
              if ($rowData['conWidth'] != '') {
                $columnContainerSetWidth = 'max-width:'.$rowData['conWidth'].'px !important;';
              }
            }
          }
        ?>
      
      <div class="rowColumnsContainer" id="rowColCont-<?php echo $rowID; ?>" style="margin:0 auto !important; <?php echo "$columnContainerSetWidth"; ?>">
  	   <?php include('columns.php'); ?>
      </div>
      
  	</div>
  	<?php 
    $rowCount++;
  } // ForEach loop for rows ends here

  echo '<style type="text/css">';
  foreach ($POPBNallRowStyles as $value) {
    echo $value . "  ";
  }

  foreach ($POPBallColumnStylesArray as $value) {
    echo $value . "  ";
  }

  foreach ($POPBallWidgetsStylesArray as $value) {
    echo $value . "  ";
  }


  echo " \n @media only screen and (min-width : 768px) and (max-width : 1024px) { ";
  foreach ($POPBNallRowStylesResponsiveTablet as $value) {
    echo $value . "  ";
  }
  echo " } ";

  echo " \n @media only screen and (min-width : 320px) and (max-width : 480px) { ";
  foreach ($POPBNallRowStylesResponsiveMobile as $value) {
    echo $value . "  ";
  }
  echo " } ";

  echo '</style>';


  if ($widgetJQueryLoadScripts == true) {
    echo $landingpagejQueryloadScripts;
    $widgetJQueryLoadScripts = true;
  }


  foreach ($POPBallWidgetsScriptsArray as $value) {
    echo $value . "  ";
  }

  $aller = '';
  echo "<div class='popb_footer_scripts' style='display:none !important;'>";
  $thisColFontsToLoadSeparatedValue = 'Allerta';
      foreach ($thisColFontsToLoad as $value) {

        if ($value !== '') {
          $aller = strpos($thisColFontsToLoadSeparatedValue, $value);
        }
        
        if ($value == 'Select' || $value == 'select' || $value == 'Arial' || $value == 'sans-serif' || $value == 'Arial Black' || $value == 'sans' || $value == 'Helvetica' || $value == 'Serif' || $value == 'Tahoma' || $value == 'Verdana' || $value == 'Monaco' || $aller !== false) {
        }else{
          if ($value != '') {
            $thisColFontsToLoadSeparatedValue = $thisColFontsToLoadSeparatedValue . '|' .$value;
          }
        }
        
      }

      echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='.$thisColFontsToLoadSeparatedValue.'">';


  foreach ($widgetTextFontsBulk as $value) {
    if ( !isset($value) ) { $value = '';  }

    $checkFontValue = str_replace(' ', '', $value); 
    if ($checkFontValue != '' && $checkFontValue != ' ' && $checkFontValue != 'undefined') {
      echo "$value";
    }

  }
  echo '</div>'; //  .popb_footer_scripts end


  ?>

<?php
    if (class_exists('E_Clean_Exit') ) {
      if (! did_action( 'wp_footer' ) ) { wp_footer();  $loadWpFooter = 'true'; }  
    }

    if ($loadWpFooter === 'true') { 
      if (! did_action( 'wp_footer' ) ) {

        if ($current_postType != 'page' || $current_postType != 'post') {
          wp_footer(); 
        }

      }  
    }
?>


<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery('.pb_img_thumbnail').on('click',function(){
      var clikedElID = jQuery(this).attr('id');
      jQuery('#pb_lightbox'+clikedElID).css('display','block');
    });

    jQuery('.pb_single_img_lightbox').on('click',function(){
      jQuery(this).css('display','none');
    });
  });
  
</script>

<?php  echo  '<script type="text/javascript">     
  
  if(typeof pluginOpsCheckElViewFrame != "function" ){
    function pluginOpsCheckElViewFrame (el) {

      if (typeof jQuery === "function" && el instanceof jQuery) {
          el = el[0];
      }

      if( typeof(el.getBoundingClientRect) == "function"  ){
        var rect = el.getBoundingClientRect();

        if( rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && 
            rect.right <= (window.innerWidth || document.documentElement.clientWidth) ) {
              return "InView";
            }else{
              return "NotInView";
            }
      }else{
        return "Function didnt work";
      }

    }
  }
    

  '.   $widgetAnimationTriggerScript  . "    jQuery(window).scroll();</script>  \n" ; ?>

<?php


if ($widgetSliderLoadScripts == true) {
  echo "<script src='".ULPB_PLUGIN_URL."/js/slider.min.js'></script>";
}



if ($widgetFALoadScripts == true) {
  echo "<script src='".ULPB_PLUGIN_URL."/js/fa.js'></script>";
} 

if ($widgetVideoLoadScripts == true) {
  echo "<link href='".ULPB_PLUGIN_URL."/js/videoJS/video-js.css' rel='stylesheet'>";
  echo "<script src='".ULPB_PLUGIN_URL."/js/videoJS/video.js'></script>";
} 

if ($widgetOwlLoadScripts == true) {
  echo "
  <link rel='stylesheet' type='text/css' href='".ULPB_PLUGIN_URL."/public/scripts/owl-carousel/owl.carousel.css'>
  <link rel='stylesheet' type='text/css' href='".ULPB_PLUGIN_URL."/public/scripts/owl-carousel/owl.theme.css'>
  <link rel='stylesheet' type='text/css' href='".ULPB_PLUGIN_URL."/public/scripts/owl-carousel/owl.transitions.css'>";
} 

if ($widgetWooCommLoadScripts == true) {
  echo "<link href='".ULPB_PLUGIN_URL."/styles/wooStyles.css' rel='stylesheet'>";
} 


?>
<?php
if (current_user_can( 'publish_pages' ) ) {

  if ($abTestingActive == true) {
    ?>
    <div class="variantTypePopUp">
      <h4>Variant</h4>
      <h2><?php echo $currentVariant; ?></h2>
    </div>
    <?php
  }

}
?>


<?php if ($current_postType == 'post' || $current_postType == 'page' || $isShortCodeTemplate == true ){ echo "</div>";} else{ echo "</body>"; }   ?>

<?php
} else{
  echo "<h3> Please add some content in your page.</h3>";
}


?>


<link rel="stylesheet" type="text/css" href="<?php echo ULPB_PLUGIN_URL."/public/templates/animate.min.css"; ?>">


<!-- <style type="text/css">
  <?php 
  // if (isset( $data['pageOptions']['poCustomFonts'] )) {
  //   foreach ($data['pageOptions']['poCustomFonts'] as $key => $value) {
  //     echo '@font-face {
  //       font-family: "'.$value['poCfName'].'"
  //       src: url("'.$value['poCfFileUrlEot'].'");
  //       src: url("'.$value['poCfFileUrlWoff'].'") format("woff"), 
  //            url("'.$value['poCfFileUrlOtf'].'") format("opentype");
  //     }';
  //   }
  // }

  ?>
</style> -->


</html>