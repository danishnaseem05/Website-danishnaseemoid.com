<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
   
  $this_widget_form_builder = $thisWidget['widgetFormBuilder'];

  $reCaptchaHtml = '';
  if (isset($this_widget_form_builder['widgetPbFbFormMailChimp']['fbgreCaptcha'] )) {
    if ( $this_widget_form_builder['widgetPbFbFormMailChimp']['fbgreCaptcha'] == 'true' ) {
      echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
      $reCaptchaHtml =  "<br><br><div class='g-recaptcha' data-sitekey='".$this_widget_form_builder['widgetPbFbFormMailChimp']['fbgreCSiteKey']."'></div>";
    }
  }

  $widgetPbFbFormFeilds = $this_widget_form_builder['widgetPbFbFormFeilds'];
  $widgetPbFbFormFeildOptions = $this_widget_form_builder['widgetPbFbFormFeildOptions'];
  $widgetPbFbFormSubmitOptions = $this_widget_form_builder['widgetPbFbFormSubmitOptions'];
  $widgetPbFbFormEmailOptions = $this_widget_form_builder['widgetPbFbFormEmailOptions'];

  $formBuilderFieldSize = 'pbField-'.$widgetPbFbFormFeildOptions['formBuilderFieldSize'];

  $formBuilderFieldVGap = ''; $formBuilderFieldHGap = '2';
  if (isset($widgetPbFbFormFeildOptions['formBuilderFieldVGap']) ) {
    
    if ($widgetPbFbFormFeildOptions['formBuilderFieldVGap'] != '') {
      $formBuilderFieldVGap = $widgetPbFbFormFeildOptions['formBuilderFieldVGap'];
    }
    if ($widgetPbFbFormFeildOptions['formBuilderFieldHGap'] != '') {
      $formBuilderFieldHGap = $widgetPbFbFormFeildOptions['formBuilderFieldHGap'];
    }
  }

  $formBuilderBtnVGap = ''; $formBuilderBtnHGap = '3';
  if (isset($widgetPbFbFormSubmitOptions['formBuilderBtnVGap']) ) {
    
    if ($widgetPbFbFormSubmitOptions['formBuilderBtnVGap'] != '') {
      $formBuilderBtnVGap = $widgetPbFbFormSubmitOptions['formBuilderBtnVGap'];
    }
    if ($widgetPbFbFormSubmitOptions['formBuilderBtnHGap'] != '') {
      $formBuilderBtnHGap = $widgetPbFbFormSubmitOptions['formBuilderBtnHGap'];
    }
  }

  $pbFormBuilderUniqueId = 'pb_FormBuilder_'.(rand(10,99)*120+200);
  
  $formBuilderbtnIcon = '';$formBuilderbtnIconBefore = '';$formBuilderbtnIconAfter = '';$formBuilderbtnIconAnimation = '';$formBuilderbtnIconHoverAnimationScript = '';
  if (isset($widgetPbFbFormSubmitOptions['formBuilderbtnSelectedIcon'])) {
    $formBuilderbtnSelectedIcon = $widgetPbFbFormSubmitOptions['formBuilderbtnSelectedIcon'];
    $formBuilderbtnIconPosition = $widgetPbFbFormSubmitOptions['formBuilderbtnIconPosition'];
    $formBuilderbtnIconAnimation = $widgetPbFbFormSubmitOptions['formBuilderbtnIconAnimation'];
    $formBuilderbtnIconGap = $widgetPbFbFormSubmitOptions['formBuilderbtnIconGap'];
    if ($formBuilderbtnSelectedIcon != '') {
      if ($formBuilderbtnIconPosition == 'before') {
        $formBuilderbtnIconGap = 'margin-right:'.$formBuilderbtnIconGap.
        'px;';
      } else {
        $formBuilderbtnIconGap = 'margin-left:'.$formBuilderbtnIconGap.
        'px;';
      }

      $faClassAppend = 'fa';
      if (strpos($formBuilderbtnSelectedIcon, 'fab') !== false || strpos($formBuilderbtnSelectedIcon, 'fas') !== false || strpos($formBuilderbtnSelectedIcon, 'far') !== false) {
        $faClassAppend = '';
      }
      $formBuilderbtnIcon = '<i style="'.$formBuilderbtnIconGap.'" class="'.$faClassAppend.' '.$formBuilderbtnSelectedIcon.'  btnIcon-'.$pbFormBuilderUniqueId.'"></i>';
      if ($formBuilderbtnIconAnimation != '') {
        $formBuilderbtnIconHoverAnimationScript = " <script>
          jQuery('.form-btn-".$pbFormBuilderUniqueId."').mouseenter(function(){
            jQuery('.btnIcon-".$pbFormBuilderUniqueId."').addClass('animated ".$formBuilderbtnIconAnimation."').one('animationend oAnimationEnd mozAnimationEnd webkitAnimationEnd',function(){ 
                jQuery('.btnIcon-".$pbFormBuilderUniqueId."').removeClass('animated ".$formBuilderbtnIconAnimation."') 
            });
          });
          </script> " ;

          array_push($POPBallWidgetsScriptsArray, $formBuilderbtnIconHoverAnimationScript);
          $formBuilderbtnIconHoverAnimationScript = '';
      }
    } else {
      $formBuilderbtnIcon = '';
    }
    if ($formBuilderbtnIconPosition == 'before') {
      $formBuilderbtnIconBefore = $formBuilderbtnIcon;
      $formBuilderbtnIconAfter = '';
    } else {
      $formBuilderbtnIconAfter = $formBuilderbtnIcon;
      $formBuilderbtnIconBefore = '';
    }
  }

  if (isset($widgetPbFbFormSubmitOptions['formBuilderBtnFontFamily'])) {
    array_push($thisColFontsToLoad, $widgetPbFbFormSubmitOptions['formBuilderBtnFontFamily']);
  }else{
    $widgetPbFbFormSubmitOptions['formBuilderBtnFontFamily'] = ' ';
  }
  if (isset($widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'])) {
    array_push($thisColFontsToLoad, $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily']);
  }else{
    $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'] = ' ';
  }

  $widgetFALoadScripts = true;
  ob_start();

  $widgetPbFbFormFeildsIndex = 0;
  foreach($widgetPbFbFormFeilds as $widgetPbFbFormFeild){

    $thisFieldOptions = $widgetPbFbFormFeild['thisFieldOptions'];
    $fbFieldRequired = '';
    $isfbFieldRequired = '';
    if ($thisFieldOptions['fbFieldRequired'] == 'true') {
      $fbFieldRequired = 'required="required"';
      $isfbFieldRequired = 'POFB_required_field_'.$pbFormBuilderUniqueId;
    }

    $presetValue = '';
    if (isset($thisFieldOptions['fbFieldPreset']) && !empty($thisFieldOptions['fbFieldPreset']) ) {
      $presetValue = ' value="'.$thisFieldOptions['fbFieldPreset'].'" ';
    }

    if (! isset( $thisFieldOptions['displayFieldsInline'] )) {
      $thisFieldOptions['displayFieldsInline'] = '';
    }
    
    $thisFieldAttr = 'style="width:99%;  "  placeholder="'.$thisFieldOptions['fbFieldPlaceHolder'].'" '.$fbFieldRequired.' "  id="fieldID-'.$widgetPbFbFormFeildsIndex.'" '.$presetValue.' ' ;
    $multiFieldStyleAttr = 'style="margin-right:25px; display:'.$thisFieldOptions['displayFieldsInline'].'; line-height:1.4em; "';

    $thisFieldName = $thisFieldOptions['fbFieldLabel'];
    if ( isset( $thisFieldOptions['fbFieldName'] ) ) {
      if ( $thisFieldOptions['fbFieldName'] != '' && !empty( $thisFieldOptions['fbFieldName'] ) ) {
        $thisFieldName = $thisFieldOptions['fbFieldName'];
      }
    }

    
    $pbThisFormFieldLabel = '<label for="fieldID-'.$widgetPbFbFormFeildsIndex.'" class="pbFormLabel"> '.$thisFieldOptions['fbFieldLabel'].' </label>';

    if ($thisFieldName == '') {
      $thisFieldName = $widgetPbFbFormFeild['fbFieldType'];
    }

    $thisFieldName = str_replace(' ', '', $thisFieldName);

    $thisFieldName = preg_replace('/[^A-Za-z0-9\-]/', '', $thisFieldName);

    switch ($widgetPbFbFormFeild['fbFieldType']) {
      case 'textarea':
           $pbThisFormField = '<textarea rows="'.$thisFieldOptions['fbtextareaRows'].'" name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'" '.$thisFieldAttr.' class="pbFormField  '.$isfbFieldRequired.'  '.$formBuilderFieldSize.'" ></textarea>';
      break;
      case 'radio':

           $multiOptionFieldValues = explode("\n", $thisFieldOptions['multiOptionFieldValues']);
           $allRadioItems = '';

           for ($pb_widget_form_loopi =0; $pb_widget_form_loopi< count($multiOptionFieldValues); $pb_widget_form_loopi++) {

             $thisMultiInputValue =  strip_tags($multiOptionFieldValues[$pb_widget_form_loopi]);
             $thisRadioLabel = $multiOptionFieldValues[$pb_widget_form_loopi].'</label>';
             $thisRadioItem = '<span '.$multiFieldStyleAttr.' class="pbFormMultiLabel"> <label for="fieldID-'.$widgetPbFbFormFeildsIndex.'-'.$pb_widget_form_loopi.'-'.$pbFormBuilderUniqueId.'"> <input type="radio" name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'[]"  id="fieldID-'.$widgetPbFbFormFeildsIndex.'-'.$pb_widget_form_loopi.'-'.$pbFormBuilderUniqueId.'" value="'.$thisMultiInputValue.'"  '.$fbFieldRequired.'> ' .$thisRadioLabel. ' </span>';
             
             $prevRadioFields = $allRadioItems;
             $allRadioItems = $prevRadioFields .  $thisRadioItem;
           }
           $pbThisFormField = $allRadioItems;

      break;
      case 'checkbox':
           $multiOptionFieldValues = explode("\n", $thisFieldOptions['multiOptionFieldValues']);
           $allRadioItems = '';

           for ($pb_widget_form_loopi =0; $pb_widget_form_loopi< count($multiOptionFieldValues); $pb_widget_form_loopi++) {

             $thisMultiInputValue =  strip_tags($multiOptionFieldValues[$pb_widget_form_loopi]);
             $thisRadioLabel = $multiOptionFieldValues[$pb_widget_form_loopi].'</label>';
             $thisRadioItem = '<span '.$multiFieldStyleAttr.' class="pbFormMultiLabel"> <label for="fieldID-'.$widgetPbFbFormFeildsIndex.'-'.$pb_widget_form_loopi.'-'.$pbFormBuilderUniqueId.'"> <input type="checkbox" name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'[]"  id="fieldID-'.$widgetPbFbFormFeildsIndex.'-'.$pb_widget_form_loopi.'-'.$pbFormBuilderUniqueId.'" value="'.$thisMultiInputValue.'"  '.$fbFieldRequired.' > ' .$thisRadioLabel. ' </span>';
             
             $prevRadioFields = $allRadioItems;
             $allRadioItems = $prevRadioFields .  $thisRadioItem;
           }
           $pbThisFormField = $allRadioItems;
      break;
      case 'select':
           $multiOptionFieldValues = explode("\n", $thisFieldOptions['multiOptionFieldValues']);
           $allRadioItems = '';

           for ($pb_widget_form_loopi =0; $pb_widget_form_loopi< count($multiOptionFieldValues); $pb_widget_form_loopi++) {

             $thisRadioItem = '<option  value="'.$multiOptionFieldValues[$pb_widget_form_loopi].'" > '.$multiOptionFieldValues[$pb_widget_form_loopi].' </option> ';
             
             $prevRadioFields = $allRadioItems;
             $allRadioItems = $prevRadioFields .  $thisRadioItem;
           }


           $pbThisFormField = '<select name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'"  id="fieldID-'.$widgetPbFbFormFeildsIndex.'"  '.$thisFieldAttr.' class="pbFormField  '.$isfbFieldRequired.'  '.$formBuilderFieldSize.'"  '.$fbFieldRequired.' >'. $allRadioItems .'</select>';
      break;
      case 'html':
          if (!isset($thisFieldOptions['fbTextContent'])) { $thisFieldOptions['fbTextContent'] = ''; }
          $pbThisFormField = '<div class="'.$thisFieldOptions['fbFieldName'].' pbFormHTML"  > '.$thisFieldOptions['fbTextContent'].'  </div>';
          $pbThisFormFieldLabel = '';
      break;
      default: 
           $pbThisFormField = '<input type="'.$widgetPbFbFormFeild['fbFieldType'].'" name="field-'.$widgetPbFbFormFeildsIndex.'-'.$thisFieldName.'"  '.$thisFieldAttr.' class="pbFormField  '.$isfbFieldRequired.'  '.$formBuilderFieldSize.'" '.$fbFieldRequired.' >';
      break;
    } //switch end

    $hiddenField = '';
    if ($widgetPbFbFormFeild['fbFieldType'] == 'hidden') {
      $hiddenField = 'display:none !important;';
    }

      
    $pbThisFormFieldWrapped =  '<div class="pluginops_form_inp_wrapper" style=" '.$hiddenField.' width:'.($thisFieldOptions['fbFieldWidth']-(int)$formBuilderFieldHGap  ).'%; margin-right:'.$formBuilderFieldHGap.'%; margin-top:'.$formBuilderFieldVGap.'%; display:inline-block;">' . $pbThisFormFieldLabel.' <br> '.$pbThisFormField .'</div>';

    echo $pbThisFormFieldWrapped;

    $widgetPbFbFormFeildsIndex++;
  }; //each loop end

  $pbFormAllFields = ob_get_contents();
  ob_end_clean();

  $buttonMargin = '2% 2% 2% 0';
  if ($widgetPbFbFormSubmitOptions['formBuilderBtnAlignment'] == 'center') {
    $calcMargin = 50 - ($widgetPbFbFormSubmitOptions['formBuilderBtnWidth']/2);
    $buttonMargin = '2% 2% 2% '.$calcMargin.'%';
  } else if($widgetPbFbFormSubmitOptions['formBuilderBtnAlignment'] == 'right') {
    $calcMargin = 100 - ($widgetPbFbFormSubmitOptions['formBuilderBtnWidth']);
    $buttonMargin = '2% 2% 2% '.$calcMargin.'%';
  }

  $formBuilderbtnIconLoading = "<i class='fas fa-spinner fa-spin formIconLoader' style='display:none;' ></i>";
  $formBuilderbtnIconSuccess = "<i class='fas fa-check-circle formIconSuccess' style='display:none;' ></i>";

  $pbFormBuilderSubmitStyles = ' style=" width:100%; background:'.$widgetPbFbFormSubmitOptions['formBuilderBtnBgColor'].'; color:'.$widgetPbFbFormSubmitOptions['formBuilderBtnColor'].'; font-size:'.$widgetPbFbFormSubmitOptions['formBuilderBtnFontSize'].'px;  border:'.$widgetPbFbFormSubmitOptions['formBuilderBtnBorderWidth'].'px solid '.$widgetPbFbFormSubmitOptions['formBuilderBtnBorderColor'].'; border-radius:'.$widgetPbFbFormSubmitOptions['formBuilderBtnBorderRadius'].'px; font-family:'.str_replace('+', ' ', $widgetPbFbFormSubmitOptions['formBuilderBtnFontFamily'] ).', sans-serif; " ';

  $pbFormBuilderSubmit = '<div class="pluginops_form_inp_wrapper" style="text-align:'.$widgetPbFbFormSubmitOptions['formBuilderBtnAlignment'].'; width:'.($widgetPbFbFormSubmitOptions['formBuilderBtnWidth']- (int)$formBuilderBtnHGap ).'%;  margin:'.$buttonMargin.';  margin-right:'.$formBuilderBtnHGap.'%; margin-top:'.$formBuilderBtnVGap.'%; display:inline-block;">  <button type="submit" '.$pbFormBuilderSubmitStyles.' class="pbField-'.$widgetPbFbFormSubmitOptions['formBuilderBtnSize'].' form-btn-'.$pbFormBuilderUniqueId.' ">'.$formBuilderbtnIconBefore.' '.$widgetPbFbFormSubmitOptions['formBuilderBtnText'].' '.$formBuilderbtnIconAfter.' </button> </div>';


  $pbFormBuilderExtraFields = " <input type='hidden' name='psID' value='$current_pageID'>
                                <input type='hidden' name='pbFormTargetInfo' value='$rowCount \n  $Columni \n $j '>
                                <input type='text' id='enteryoursubjecthere' name='enteryoursubjecthere'>
                                $reCaptchaHtml
                                ";

  $pbFormBuilderWrapper = '<form id="'.$pbFormBuilderUniqueId.'" action="'.admin_url('admin-ajax.php?action=ulpb_formBuilderEmail_ajax').'"  method="post"  > '.$pbFormAllFields.'  '.$pbFormBuilderExtraFields.'  '.wp_nonce_field('POPB_send_form_data','POPB_Form_Nonce',true,false). ' '. $pbFormBuilderSubmit.' </form>';

  $pbFormBuilderStylesID = '#'.$pbFormBuilderUniqueId;

  $pbThisFormBuilderStyles = '<style>
    '.$pbFormBuilderStylesID.' .pbFormField {
        background:'.$widgetPbFbFormFeildOptions['formBuilderFieldBgColor'].';  color:'.$widgetPbFbFormFeildOptions['formBuilderFieldColor'].'; border:'.$widgetPbFbFormFeildOptions['formBuilderFieldBorderWidth'].'px solid '.$widgetPbFbFormFeildOptions['formBuilderFieldBorderColor'].'; border-radius:'.$widgetPbFbFormFeildOptions['formBuilderFieldBorderRadius'].'px; font-family:'.str_replace('+', ' ', $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'] ).', sans-serif; 
      }
      '.$pbFormBuilderStylesID.' .pbFormField::placeholder {
        color:'.$widgetPbFbFormFeildOptions['formBuilderFieldColor'].';
      }

    '.$pbFormBuilderStylesID.' .pbFormLabel{
      font-size:'.$widgetPbFbFormFeildOptions['formBuilderLabelSize'].'px;
      font-family:'.str_replace('+', ' ', $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'] ).', sans-serif;
      color:'.$widgetPbFbFormFeildOptions['formBuilderLabelColor'].'; 
      display:'.$widgetPbFbFormFeildOptions['formBuilderFieldLabelDisplay'].'; 
      line-height:3em;
    }
    '.$pbFormBuilderStylesID.' .pbFormHTML{
      font-size:'.$widgetPbFbFormFeildOptions['formBuilderLabelSize'].'px;
      font-family:'.str_replace('+', ' ', $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'] ).', sans-serif;
      color:'.$widgetPbFbFormFeildOptions['formBuilderLabelColor'].'; 
      line-height:3em;
    }
    '.$pbFormBuilderStylesID.' button:hover {
      background:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverBgColor'].' !important; color:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverTextColor'].' !important; transition:all .5s; 
    }

    '.$pbFormBuilderStylesID.' .pbFormMultiLabel label { font-size:'.$widgetPbFbFormFeildOptions['formBuilderLabelSize'].'px;
     font-family:'.str_replace('+', ' ', $widgetPbFbFormFeildOptions['formBuilderFieldFontFamily'] ).', sans-serif;
     color:'.$widgetPbFbFormFeildOptions['formBuilderLabelColor'].';   font-weight:200; }  '.$pbFormBuilderStylesID.' button:hover { background:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverBgColor'].' !important; color:'.$widgetPbFbFormSubmitOptions['formBuilderBtnHoverTextColor'].' !important; transition:all .5s; }
  </style>';

  if ($widgetSubscribeFormWidget !== true) {
    $widgetCookieLibraryScript = "<script src='".ULPB_PLUGIN_URL."/js/cookie.js'></script>";
    array_push($POPBallWidgetsScriptsArray, $widgetCookieLibraryScript);
    $widgetSubscribeFormWidget = true;
  }

  $formSuccessAction = ''; $formSuccessActionURL = '';
  if (isset($widgetPbFbFormEmailOptions['formSuccessAction']) ) {
    $formSuccessAction = $widgetPbFbFormEmailOptions['formSuccessAction'];
    $formSuccessActionURL = $widgetPbFbFormEmailOptions['formSuccessActionURL'];
  }

  ob_start();

  if ( !isset($widgetPbFbFormEmailOptions['formFailureMessage'] )) {
    $widgetPbFbFormEmailOptions['formFailureMessage'] = 'Some Error Occured while sending the request!';
  }else{
    if ($widgetPbFbFormEmailOptions['formFailureMessage'] == '') {
      $widgetPbFbFormEmailOptions['formFailureMessage'] = 'Some Error Occured while sending the request!';
    }
  }

  if (!isset($widgetPbFbFormEmailOptions['formSuccessCustomAction'] )) {
    $widgetPbFbFormEmailOptions['formSuccessCustomAction'] = '';
  }

  if (!isset($widgetPbFbFormEmailOptions['formDuplicateMessage'] )) {
    $widgetPbFbFormEmailOptions['formDuplicateMessage'] = 'Information Already Exisits!';
  }
  if (!isset($widgetPbFbFormEmailOptions['formRequiredFieldMessage'] )) {
    $widgetPbFbFormEmailOptions['formRequiredFieldMessage'] = 'Please fill all the required * fields.';
  }
  if (!isset($widgetPbFbFormEmailOptions['formFailureCustomAction'] )) {
    $widgetPbFbFormEmailOptions['formFailureCustomAction'] = '';
  }

  if (!isset($this_widget_form_builder['widgetPbFbFormMailChimp']['wfb_cWebHookSuccResponse'] )) {
    $this_widget_form_builder['widgetPbFbFormMailChimp']['wfb_cWebHookSuccResponse'] = 'success';
  }
  
  $widgetPbFbFormEmailOptions['formSuccessMessage'] = str_replace("'", "\'", $widgetPbFbFormEmailOptions['formSuccessMessage']);

  $widgetPbFbFormEmailOptions['formSuccessMessage'] = str_replace('"', '\"', $widgetPbFbFormEmailOptions['formSuccessMessage']);

  $widgetPbFbFormEmailOptions['formFailureMessage'] = str_replace("'", "\'", $widgetPbFbFormEmailOptions['formFailureMessage']);

  $widgetPbFbFormEmailOptions['formFailureMessage'] = str_replace('"', '\"', $widgetPbFbFormEmailOptions['formFailureMessage']);


  ?>
  <script type="text/javascript">
    (function($){
      $(document).ready(function() {
        $('#enteryoursubjecthere').hide();
      $('#'+'<?php echo $pbFormBuilderUniqueId; ?>').on('submit', function()  {

        var allRequiredFields = $('<?php echo ".POFB_required_field_".$pbFormBuilderUniqueId; ?>');
        for(var i = 0; i < allRequiredFields.length; i++){
          if ( $(allRequiredFields[i]).val() == '' ) {
            $(allRequiredFields[i]).css('border', '2px solid #ff1f1f');
            $(allRequiredFields[i]).removeClass('animated');
            $(allRequiredFields[i]).removeClass('shake');
            $(allRequiredFields[i]).addClass('animated shake');
            $('<?php echo ".pb_unfilled_required_field_".$pbFormBuilderUniqueId; ?>').css('display','block');
            return false;
          }
        }


        var successMessage = "<?php echo $widgetPbFbFormEmailOptions['formSuccessMessage']; ?>";
        var errorMessage = "<?php echo $widgetPbFbFormEmailOptions['formFailureMessage']; ?>";
        var successAction = "<?php echo $formSuccessAction; ?>";
        var successActionUrl = "<?php echo $formSuccessActionURL; ?>";

          var buttonText = $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').html();

        $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').html('');
        $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').append("<i class='fas fa-spinner fa-spin formIconLoader'></i>");


        function checkResponse_<?php echo "$pbFormBuilderUniqueId"; ?>(message){


          var isAllSuccess = false;
          var isDuplicate = false;
          var errorMessages = '';
          $.each(message,function(index, val) {

            if(val == 'success'){

              isAllSuccess = true;

            }else if(val == 'Inactive'){

            }else if(val == 'Subscriber Already Exists'){

              if (index != 'database' ) {
                isDuplicate = true;
                if (errorMessages !== '') {
                  errorMessages = errorMessages + '\n <br> ' + index + ' : ' + val;
                }else{
                  errorMessages =  index + ' : ' + val;
                }
              }

            }else{

              if (index != 'database' && index != 'WebHook' && index != 'gRecaptcha') {
                isAllSuccess = false;
                if (errorMessages !== '') {
                  errorMessages = errorMessages + '\n <br> ' + index + ' : ' + val;
                }else{
                  errorMessages =  index + ' : ' + val;
                }
                
              }


              if (val == 'Sorry, Security error.') {
                isAllSuccess = false;
                errorMessages = val;
              }
              
            }

          });

          var calcResult = [];

          calcResult['isAllSuccess'] = isAllSuccess;
          calcResult['errors'] = errorMessages;
          calcResult['isDuplicate']= isDuplicate;

          return calcResult;

        }

        var form = $(this);
        form.siblings('.pb_duplicate').hide();
        form.siblings('.pb_error').hide();
        form.siblings('.pb_success').hide();
        var result = " ";
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(result){
              var result = JSON.parse(result);
              
              var emailResult = result['email'];
              var mcResult = result['mailchimp'];
              var grResult = result['getResponse'];
              var cmResult = result['campaignMonitor'];
              var acResult = result['activeCampaign'];
              var dripResult = result['drip'];
              var aweberResult = result['aweber'];
              var convertkitResult = result['convertkit'];
              var marketheroResult = result['markethero'];
              var SendinBlueResult = result['SendinBlue'];
              var MailPoetResult = result['MailPoet'];
              var webHookResult = result['WebHook'];
              var ConstantContactResult = result['ConstantContact'];
              var gRecaptcha = result['gRecaptcha'];

              var subAEStr = 'Subscriber Already Exists';
              var webHookResponse = '<?php echo $this_widget_form_builder['widgetPbFbFormMailChimp']['wfb_cWebHookSuccResponse']; ?>';


              var calculatedResponse = checkResponse_<?php echo "$pbFormBuilderUniqueId"; ?>(result);


              $('<?php echo ".pb_unfilled_required_field_".$pbFormBuilderUniqueId; ?>').css('display','none');

                <?php
                  if ( function_exists('ulpb_available_pro_widgets') ) {
                    echo '
                      if (result["database"] != "false") {
                        emailResult = "success";
                      }
                    ';
                  }
                ?>

                if (calculatedResponse['isAllSuccess'] == true ) {
                  
                  form.siblings('.pb_success').children('p').html( '<i class="fas fa-check"></i>' + " " + successMessage);
                  form.siblings('.pb_success').show();
                  $.cookie("pluginops_user_subscribed_form<?php echo $current_pageID; ?>", 'yes', {path: '/', expires : 30 });
                  
                  <?php echo $widgetPbFbFormEmailOptions['formSuccessCustomAction']; ?>
                  if (successAction == 'redirect') {
                    location.href = successActionUrl;
                  }
                } else{

                   errorMessage = calculatedResponse['errors'];
                   form.siblings('.pb_error').children('p').html(errorMessage);
                   form.siblings('.pb_error').show();
                   <?php echo $widgetPbFbFormEmailOptions['formFailureCustomAction']; ?>
                }

                if( calculatedResponse['isDuplicate'] == true ){
                  form.siblings('.pb_duplicate').children('p').html("<?php echo $widgetPbFbFormEmailOptions['formDuplicateMessage']; ?>");
                  form.siblings('.pb_duplicate').show();
                }


                if (gRecaptcha == 'Verification Failed') {
                  form.siblings('.pb_error').children('p').html('There was some problem in verification please reload the page and try again. ');
                  var greError =   result['gRecaptchaError'];
                  if (greError.includes('invalid-input-secret') ) {
                    form.siblings('.pb_error').children('p').html('Invalid ReCaptcha Sceret Key');
                  }
                  form.siblings('.pb_error').show();
                  <?php echo $widgetPbFbFormEmailOptions['formFailureCustomAction']; ?>
                }
                if (gRecaptcha == 'Captcha Not Set') {
                  form.siblings('.pb_error').children('p').html('Please click on I\'m not a robot checkbox.');
                  form.siblings('.pb_error').show();
                  <?php echo $widgetPbFbFormEmailOptions['formFailureCustomAction']; ?>
                }

                $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').html(buttonText);
                console.log('Database Result  : ' + result['database']);
                console.log('MailChimp Result  : ' + result['mailchimp']);
                console.log('GetResponse Result  : ' + result['getResponse']);
                console.log('Campaign Monitor Result  : ' + result['campaignMonitor']);
                console.log('Active Campaign Result  : ' + result['activeCampaign']);
                console.log('Drip Result  : ' + result['drip']);
                console.log('Aweber Result  : ' + result['aweber']);
                console.log('ConvertKit Result  : ' + result['convertkit']);
                console.log('Markethero Result  : ' + marketheroResult);
                console.log('SendinBlue Result  : ' + SendinBlueResult);
                console.log('MailPoet Result  : ' + MailPoetResult);
                console.log('ConstantContact Result  : ' + ConstantContactResult);
            },
            error: function(xhr, ajaxOptions, thrownError){
              form.siblings('.pb_error').children('p').html(thrownError);
              form.siblings('.pb_error').show();
              $('#'+'<?php echo $pbFormBuilderUniqueId; ?> button').html(buttonText);
            }
        });
                         
        // Prevents default submission of the form after clicking on the submit button. 
        return false;   
      });

    });

    })(jQuery);
  </script>
  <?php 

  //echo $formBuilderbtnIconHoverAnimationScript;

  $thisFormScript = ob_get_contents();
  ob_end_clean();
  
  array_push($POPBallWidgetsScriptsArray, $thisFormScript);


  $pb_On_complete_messages = '<div class="w3-panel w3-green pb_success" style="display:none;  width:'.(100 - (int)$formBuilderBtnHGap ).'%;  margin-right:'.$formBuilderBtnHGap.'%; "><p></p></div>
  <div class="w3-panel w3-red pb_error" style="display:none;"><p></p></div>  <div class="w3-panel w3-orange pb_duplicate" style="display:none;"><p></p></div>

  <div class="w3-panel w3-orange pb_unfilled_required_field_'.$pbFormBuilderUniqueId.'" style="display:none;"><p> '.$widgetPbFbFormEmailOptions['formRequiredFieldMessage'].' </p></div>
  ';

  if (isset($widgetPbFbFormFeildOptions['formBuilderLabelSizeTablet'])) {
    $thisWidgetResponsiveWidgetStylesTablet = "
      #$pbFormBuilderUniqueId label {
        font-size: ".$widgetPbFbFormFeildOptions['formBuilderLabelSizeTablet']."px !important;
      }
      #$pbFormBuilderUniqueId button {
        font-size: ".$widgetPbFbFormSubmitOptions['formBuilderBtnFontSizeTablet']."px !important;
      }
    ";
    $thisWidgetResponsiveWidgetStylesMobile = "
      #$pbFormBuilderUniqueId label {
        font-size: ".$widgetPbFbFormFeildOptions['formBuilderLabelSizeMobile']."px !important;
      }
      #$pbFormBuilderUniqueId button {
        font-size: ".$widgetPbFbFormSubmitOptions['formBuilderBtnFontSizeMobile']."px !important;
      }
    ";

    array_push($POPBNallRowStylesResponsiveTablet, $thisWidgetResponsiveWidgetStylesTablet);
    array_push($POPBNallRowStylesResponsiveMobile, $thisWidgetResponsiveWidgetStylesMobile);
  }


                

$widgetJQueryLoadScripts = true;

$widgetContent = $pbFormBuilderWrapper . " $pb_On_complete_messages  ". $pbThisFormBuilderStyles . '  ';


?>