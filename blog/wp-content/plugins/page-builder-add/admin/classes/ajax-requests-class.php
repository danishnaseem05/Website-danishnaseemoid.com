<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class ULPB_Ajax_Requests {

	function __construct(){

		$this->_init();
		$this->_hooks();
		$this->_filters();

	}

	function _init(){
	}

	function _hooks(){

		//add_action( 'wp_ajax_nopriv_ulpb_admin_data', array( $this,'ulpb_admin_ajax')  );
		add_action( 'wp_ajax_ulpb_admin_data', array( $this,'ulpb_admin_ajax') );

		add_action( 'wp_ajax_ulpb_settings_data', array( $this,'ulpb_settings_page_ajax') );

		add_action( 'wp_ajax_ulpb_tracking_scripts_data', array( $this,'ulpb_tracking_scripts_data') );

		add_action( 'wp_ajax_nopriv_ulpb_subscribeForm_data', array( $this,'ulpb_subscribeForm_ajax')  );
		add_action( 'wp_ajax_ulpb_subscribeForm_data', array( $this,'ulpb_subscribeForm_ajax') );

		add_action( 'wp_ajax_nopriv_ulpb_formBuilderEmail_ajax', array( $this,'ulpb_formBuilderEmail_ajax')  );
		add_action( 'wp_ajax_ulpb_formBuilderEmail_ajax', array( $this,'ulpb_formBuilderEmail_ajax') );

		add_action( 'wp_ajax_nopriv_ulpb_subscribeForm_mailchimp_data', array( $this,'ulpb_subscribeForm_mailchimp_ajax')  );
		add_action( 'wp_ajax_ulpb_subscribeForm_mailchimp_data', array( $this,'ulpb_subscribeForm_mailchimp_ajax') );

		add_action( 'wp_ajax_nopriv_ulpb_cta_click_conversion_record', array( $this,'ulpb_cta_click_conversion_record')  );
		add_action( 'wp_ajax_ulpb_cta_click_conversion_record', array( $this,'ulpb_cta_click_conversion_record') );

		//add_action( 'wp_ajax_nopriv_ulpb_loadShortcode_content', array( $this,'ulpb_loadShortcode_content')  );
		add_action( 'wp_ajax_ulpb_loadShortcode_content', array( $this,'ulpb_loadShortcode_content') );

		//add_action( 'wp_ajax_nopriv_ulpb_get_global_row_content', array( $this,'ulpb_get_global_row_content')  );
		add_action( 'wp_ajax_ulpb_get_global_row_content', array( $this,'ulpb_get_global_row_content') );

		//add_action( 'wp_ajax_nopriv_ulpb_insert_template', array( $this,'ulpb_insert_template')  );
		add_action( 'wp_ajax_ulpb_insert_template', array( $this,'ulpb_insert_template') );

		//add_action( 'wp_ajax_nopriv_ulpb_subscribe_list_empty', array( $this,'ulpb_subscribe_list_empty_wp_ajax')  );
		add_action( 'wp_ajax_ulpb_subscribe_list_empty', array( $this,'ulpb_subscribe_list_empty_wp_ajax') );

		//add_action( 'wp_ajax_nopriv_ulpb_activate_pb_request', array( $this,'ulpb_update_pagebuilder_active_option')  );
		add_action( 'wp_ajax_ulpb_activate_pb_request', array( $this,'ulpb_update_pagebuilder_active_option') );

		add_action( 'wp_ajax_ulpb_empty_form_builder_data', array( $this,'ulpb_empty_form_builder_data') );

		add_action( 'wp_ajax_ulpb_delete_form_builder_entry', array( $this,'ulpb_delete_form_builder_entry') );

		add_action( 'wp_ajax_ulpb_delete_optin_analytics', array( $this,'ulpb_delete_optin_analytics') );

		add_action( 'wp_ajax_ulpb_get_new_analytics', array( $this,'ulpb_get_new_analytics') );

		add_action( 'wp_ajax_popb_send_user_feedback', array( $this, 'popb_send_user_feedback' ) );

		add_action( 'wp_ajax_ulpb_aweber_connect', array( $this, 'ulpb_aweber_connect' ) );
		add_action( 'wp_ajax_ulpb_aweber_connection_check', array( $this, 'ulpb_aweber_connection_check' ) );

		add_action( 'wp_ajax_ulpb_getMCGroupIds', array( $this, 'ulpb_getMCGroupIds' ) );

		add_action( 'wp_ajax_ulpb_getCkSequenceIds', array( $this, 'ulpb_getCkSequenceIds' ) );

		add_action( 'wp_ajax_ulpb_get_sample_permalink_for_landingpages', array( $this, 'get_sample_permalink_for_landingpages' ) );

		add_action( 'wp_ajax_popb_update_data_collection_option', array( $this, 'popb_update_data_collection_option' ) );

		add_action( 'wp_ajax_ulpb_getConstantContactLists', array( $this, 'ulpb_getConstantContactLists' ) );

		add_action( 'wp_ajax_popb_enable_safe_mode', array( $this, 'popb_enable_safe_mode' ) );

		
		
	}

	function _filters(){

	}





function ulpb_admin_ajax(){


	if( current_user_can('editor') || current_user_can('administrator') ) {

		$nonce = $_REQUEST['POPB_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'POPB_data_nonce' ) ) {
			die( 'Invalid Nonce' ); 
		}else{
			$requestMethod = $_SERVER['REQUEST_METHOD'];
			if (isset($_SERVER['HTTP_METHOD'])) {
				$requestMethod = $_SERVER['HTTP_METHOD'];
			}
			if($requestMethod == 'GET') {

		    	$page_id = intval($_GET['page_id']);
		    	$data = get_post_meta( $page_id, 'ULPB_DATA', true );

		    	if (isset( $data['RowsDivided'] )) {
				  if ($data['RowsDivided'] > 0 ) {
				    $rowsCollection = array();
				    for($i = 0; $i< $data['RowsDivided']; $i++ ){
				      $theseRows =  get_post_meta($page_id, 'ULPB_DATA_Rows_Part_'.$i, true);
				      foreach ($theseRows as $value) {
				        array_push($data['Rows'], $value );
				      }
				    }
				  }
				}
		    	
		   		echo json_encode( $data );
		   		
		   		exit();
			} elseif($requestMethod == 'PUT') {
			} elseif ($requestMethod == 'POST') {

				$data = json_decode( file_get_contents( 'php://input' ), true );
				$page_id  = intval($data['pageID']);
				$postType  = $data['postType'];
				$pageStatus = $data['pageStatus'];
				$pageTitle    = $data['pageOptions']['pageSeoName'];
				$pageLink    = $data['pageOptions']['pageLink'];
				$setFrontPage    = $data['pageOptions']['setFrontPage'];
				$loadWpHead    = $data['pageOptions']['loadWpHead'];
				$loadWpFooter    = $data['pageOptions']['loadWpFooter'];
				$MultiVariantTesting    = $data['pageOptions']['MultiVariantTesting'];
				$user_id = get_current_user_id();

				$checkpost = get_post( $page_id );
				$globalPostUniquenessCheck = wp_unique_post_slug($pageLink, $checkpost->ID, $checkpost->post_status, 'page', $checkpost->post_parent);

				$post = array(
					'ID'		=> wp_strip_all_tags($page_id),
					'post_title' => wp_strip_all_tags($pageTitle),
					'post_name' => wp_strip_all_tags($globalPostUniquenessCheck),
					'post_status' => wp_strip_all_tags($pageStatus),           
					'post_type' => "$postType"  
				);
				
				$checkIfPostExists = get_post_status($page_id);

				if ($checkIfPostExists != false) {
					$post_id =  wp_update_post($post);
				} else{
				 	$post_id =  wp_insert_post($post);
				}

				$rowCount = count($data['Rows']);

				$data['RowsDivided'] = 0;

				if ($rowCount > 14 && $rowCount < 20) {
					$data['RowsDivided'] = 2;
				}
				if ($rowCount > 19 && $rowCount < 30) {
					$data['RowsDivided'] = 3;
				}
				if ($rowCount > 29 && $rowCount < 40) {
					$data['RowsDivided'] = 4;
				}
				if ($rowCount > 39 && $rowCount < 50) {
					$data['RowsDivided'] = 5;
				}
				if ($rowCount > 49 && $rowCount < 60) {
					$data['RowsDivided'] = 6;
				}
				if ($rowCount > 59 && $rowCount < 80) {
					$data['RowsDivided'] = 8;
				}

				function ulpb_array_divide($array, $segmentCount) {
				    $dataCount = count($array);
				    if ($dataCount == 0) return false;
				    $segmentLimit = ceil($dataCount / $segmentCount);
				    $outputArray = array();
				    while($dataCount > $segmentLimit) {
				        $outputArray[] = array_splice($array,0,$segmentLimit);
				        $dataCount = count($array);
				    }
				    if($dataCount > 0) $outputArray[] = $array;
				    return $outputArray;
				}

				if ($data['RowsDivided'] > 0) {
					$dividedRowsCollection = ulpb_array_divide($data['Rows'], $data['RowsDivided'] );
					for($i = 0; $i< $data['RowsDivided']; $i++ ){
						update_post_meta( $page_id, 'ULPB_DATA_Rows_Part_'.$i, $dividedRowsCollection[$i] );
					}
					$data['Rows'] = array();
				}else{
					for($i = 0; $i< 8; $i++ ){
						delete_post_meta( $page_id, 'ULPB_DATA_Rows_Part_'.$i );
					}
				}

				$dataTestprev = get_post_meta( $page_id, 'ULPB_DATA', true );
				update_post_meta( $page_id, 'ULPB_DATA', $data );
				update_post_meta( $page_id, 'ULPB_FrontPage', $setFrontPage);
				update_post_meta( $page_id, 'ULPB_loadWpHead', $loadWpHead);
				update_post_meta( $page_id, 'ULPB_loadWpFooter', $loadWpFooter);
				update_post_meta( $page_id, 'ULPB_MultiVariantTesting', $MultiVariantTesting);


				$imgUrl = esc_url_raw( $data['pageOptions']['pageSeofbOgImage'] );
				function popses_get_image_attachement_id($imgUrl) {
				    global $wpdb;
				    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $imgUrl )); 
				        return $attachment[0]; 
				}
				$imgAttachementId = popses_get_image_attachement_id($imgUrl);
				set_post_thumbnail( $page_id, $imgAttachementId );

				$dataTest = get_post_meta( $page_id, 'ULPB_DATA', true );
				if (!empty($dataTest)) {
					echo json_encode( 'Data Saved' );
				}else{
					echo "Not Saved";
				}
		   		exit();
			}
		}
	}

} //  ulpb_admin_ajax() ends here . 


function ulpb_settings_page_ajax(){

	$plugOps_pageBuilder_settings_nonce = $_REQUEST['POPB_settings_page_nonce'];
	if ( ! wp_verify_nonce( $plugOps_pageBuilder_settings_nonce, 'POPB_settings_nonce' ) ) {
		die( 'Security check' ); 
	}else{

		$selectedPostTypes = $_POST['selectedPostTypes'];

		$landingPageAsComingSoonPage = sanitize_text_field( $_POST['landingPageAsComingSoonPage'] );

		$ulpb_setting_optionOne =	update_option( 'landingPageAsComingSoonPage',$landingPageAsComingSoonPage);

		$selectedPostTypes = array_map( 'esc_attr', $selectedPostTypes );
		$ulpb_setting_optionTwo =	update_option( 'page_builder_SupportedPostTypes',$selectedPostTypes);


		$landingPageLinkTrackingFeature = sanitize_text_field( $_POST['landingPageLinkTrackingFeature'] );
		$ulpb_setting_optionThree =	update_option( 'landingPageLinkTrackingFeature', $landingPageLinkTrackingFeature );

		$popbLandingpageUrlKeyword = sanitize_text_field( $_POST['popbLandingpageUrlKeyword'] );

		$popbLandingpageUrlKeyword = strtolower($popbLandingpageUrlKeyword);
		$ulpb_setting_optionFour =	update_option( 'popbLandingpageUrlKeyword', $popbLandingpageUrlKeyword );

		$landingPageSafeModeFeature = sanitize_text_field( $_POST['landingPageSafeModeFeature'] );
		$ulpb_setting_optionFive =	update_option( 'landingPageSafeModeFeature', $landingPageSafeModeFeature );

		if ($ulpb_setting_optionTwo == true || $ulpb_setting_optionOne == true || $ulpb_setting_optionThree == true || $ulpb_setting_optionFour == true  || $ulpb_setting_optionFive == true) {
			echo "Data Saved Successfully";
		}else{
			echo "Please make some changes first.";
		}

		if ($ulpb_setting_optionFour == true) {
			update_option( 'cpt_reset_ulpb_pluginops', false );
		}

		exit();
	}
}


function ulpb_tracking_scripts_data(){

	$plugOps_pageBuilder_settings_nonce = $_REQUEST['POPB_settings_page_nonce'];
	if ( ! wp_verify_nonce( $plugOps_pageBuilder_settings_nonce, 'POPB_settings_nonce' ) ) {
		die( 'Security check' ); 
	}else{

		$TrackingScriptsField = stripcslashes( $_POST['TrackingScriptsField'] );

		$TrackingScriptsFieldTwo = stripcslashes( $_POST['TrackingScriptsFieldBody'] );

		$ulpb_setting_option =	update_option( 'ulpb_global_tracking_scripts',$TrackingScriptsField);

		$ulpb_setting_optionTwo =	update_option( 'ulpb_global_tracking_scriptsBodyTag',$TrackingScriptsFieldTwo);
		
		if ($ulpb_setting_option == true || $ulpb_setting_optionTwo == true) {
			echo "Saved Successfully";
		}else{
			echo "Some Error Occurred Or Field is empty.";
		}
		exit();
	}
}



function ulpb_subscribeForm_ajax($post){

	function check_input($data){
	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	}


	if ( 
	    ! isset( $_POST['POPB_SubsForm_Nonce'] ) 
	    || ! wp_verify_nonce( $_POST['POPB_SubsForm_Nonce'], 'POPB_send_Subsform_data' ) 
	) {

	   $returnSuccessArray['database'] = 'Sorry, Security error.';
	   echo json_encode($returnSuccessArray);
	   exit;

	} else {

	   	function ssm_send_to_db() {

			$ssm_post_id = filter_var($_REQUEST['sm_pID'],FILTER_SANITIZE_STRING);
			$s_name = filter_var($_REQUEST['sm_name'],FILTER_SANITIZE_STRING);
			$s_email = filter_var($_REQUEST['sm_email'],FILTER_SANITIZE_EMAIL);
			
			$PSdata = get_post_meta( $ssm_post_id, 'ULPB_DATA', true );

			if (!filter_var($s_email, FILTER_VALIDATE_EMAIL)) {
		      echo  "Invalid email format"; 
		      exit;
		    }
			
			$ssm_Name = wp_strip_all_tags($s_name);
			$ssm_Email = wp_strip_all_tags($s_email);

			$ssm_subscribers_list = get_post_meta($ssm_post_id,'ssm_subscribers_list',true);
			$array_size_prev = count($ssm_subscribers_list); 

			$ssm_conversion_count = get_post_meta($ssm_post_id,'ssm_conversion_count',true);
			$ssm_conversion_count++;


			if ( ! is_array( $ssm_subscribers_list ) )
				$ssm_subscribers_list = array();


			$userTimeZone = get_option('timezone_string');
			date_default_timezone_set($userTimeZone);
			$todaysDate = date("d-m-Y");

			$newSubscriber = array(
					'name' => $ssm_Name,
					'email' => $ssm_Email,
					'date' =>  $todaysDate,
				);


			$ssm_subscribers_list_pid = $ssm_subscribers_list;

			$subscriberExists = false;
			foreach ($ssm_subscribers_list_pid as $key => $value) {
				if ($value['email'] == $ssm_Email) {
					$subscriberExists = true;
				}
			}
			if ($subscriberExists == true) {
			}else{
				array_push($ssm_subscribers_list_pid, $newSubscriber);
				update_post_meta( $ssm_post_id, 'ssm_subscribers_list', $ssm_subscribers_list_pid, $unique = false);
				$updateResultConversionCount = update_post_meta( $ssm_post_id, 'ssm_conversion_count', $ssm_conversion_count, $unique = false);

				$ssm_subscribers_list2 = get_post_meta($ssm_post_id,'ssm_subscribers_list',true);
				$array_size_aft = count($ssm_subscribers_list2);
			}
			
			
			return true;
		}

		function send_data_to_gr_api_sub_form($apikey,$accountName, $name, $email){
			
			require ULPB_PLUGIN_PATH.'/integrations/jsonRPCClient.php';

			$api_key = $apikey;

			$api_url = 'http://api2.getresponse.com';

			$client = new jsonRPCClient($api_url);

			$campaign_name_wp = $accountName;
			$user_given_campaign_name = array ( 'EQUALS' => $campaign_name_wp);


			try {
				$campaigns = $client->get_campaigns(
				    $api_key,
				    array (
				        'name' => $user_given_campaign_name
				    )
				);

				$campaign_keys = array_keys($campaigns);
				$CAMPAIGN_ID = array_pop($campaign_keys);

				if (empty($name)) {
					$name = ' ';
				}
				$result = $client->add_contact(
				    $api_key,
				    array (
				        'campaign'  => $CAMPAIGN_ID,
				        'name'      => "$name",
				        'email'     => "$email",
				        'cycle_day' => '0',
				    )
				);

				return "success";
			} catch (Exception $e) {
				$gr_contact_exists = "Contact already added to target campaign";
				$gr_contact_queue = "Contact already queued for target campaign";
				$gr_invalid_params = "Request have return error: Invalid params";
				$gr_invalid_api = "Request have return error: API key verification failed";
				$gr__invalid_param_result = strstr($e, $gr_invalid_params, $before_needle = true);
				$gr__invalid_api_result = strstr($e, $gr_invalid_api, $before_needle = true);
				$gr__c_exists_result = strstr($e, $gr_contact_exists, $before_needle = true);
				$gr_contact_queue_results = strstr($e, $gr_contact_queue, $before_needle = true);
				if($gr__invalid_param_result == true){
					return "Invalid Parameters Supplied";
				} else if($gr__invalid_api_result == true){
					return "Invalid API Key Or List Name";
				} else if ($gr__c_exists_result == true) {
					return 'Subscriber Already Exists';
				} else if ($gr_contact_queue_results == true) {
					return 'Subscriber Already Exists';
				} else{
					return "Unknown error occurred!".$e;
				}
			}
		}

		function ssm_send_to_campaign_monitor($apikey, $listID, $name,$email){
			require_once ULPB_PLUGIN_PATH.'/integrations/createsend/csrest_subscribers.php';

			$auth = array('api_key' => $apikey);
			$wrap = new CS_REST_Subscribers($listID,$auth);

			$result = $wrap->add(
				array(
					'EmailAddress' => $email,
					'Name'		   => $name, 
					'Resubscribe' => true,
				)
			);
			
			if($result->was_successful()) {
			    return "success";
			} else {
			    return $result->response;
			}
		}

		function ssm_send_to_active_campaign($apikey,$apiAddress, $listID, $name,$email){

			$url = $apiAddress;
			$params = array(
			    'api_key'      => $apikey,
			    'api_action'   => 'contact_add',
			    'api_output'   => 'serialize',
			);

			$post = array(
			    'email'                    => $email,
			    'first_name'               => $name,
			    'tags'                     => 'PluginOps Forms',
			    'p['.$listID.']'           => $listID,
			    'status['.$listID.']'      => 1, 
			    'instantresponders[123]'   => 1, 
			);

			$query = "";
			foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
			$query = rtrim($query, '& ');

			$data = "";
			foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
			$data = rtrim($data, '& ');

			$url = rtrim($url, '/ ');

			if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
			    return('JSON not supported. (introduced in PHP 5.2.0)');
			}

			$api = $url . '/admin/api.php?' . $query;

			$response = wp_remote_post( $api, array(
				'method' => 'POST',
				'timeout' => 5,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'body' => $data,
				)
			);

			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				return "Something went wrong: $error_message";
			}
			if ( !$response ) {
				return('Nothing was returned. Do you have a connection to Email Marketing server?');
			}
			if (!is_array($response)) {
				$response = array();
				$response['body'] = 'Nothing Returned';
			}
			$result = unserialize($response['body']);
			if ($result == false) {
				$response = json_decode( $response['body'] );
				$result = array();
				$result['result_code'] = $response->result_code;
				$result['result_message'] = $response->result_message;
			}

			/*
			if ( !function_exists('curl_init') ) return('CURL not supported. (introduced in PHP 4.0.2)');
			$request = curl_init($api); 
			curl_setopt($request, CURLOPT_HEADER, 0); 
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($request, CURLOPT_POSTFIELDS, $data); 
			curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
			$response = (string)curl_exec($request); 
			curl_close($request); 

			if ( !$response ) {
			    return('Nothing was returned. Do you have a connection to Email Marketing server?');
			}
			$result = unserialize($response);
			*/

			if ($result['result_code']) {
				return 'success';
			}else{
				return $result['result_message'];
			}
		}

		function ssm_send_to_mailchimp($apikey, $listID,$name, $email) {
			    
			    list(, $dataCenter) = explode('-', $apikey);
	            $auth = base64_encode( 'user:'.$apikey );
	            $merge_vars = Array( 
			        'EMAIL' => $email,
			        'FNAME' => $name
			    );
	            $data = array(
	                'apikey'        => $apikey,
	                'email_address' => $email,
	                'status'        => 'subscribed',
	                'merge_fields'  => $merge_vars
	            );
	            $json_data = json_encode($data);

	            $response = wp_remote_post( 'https://'.$dataCenter.'.api.mailchimp.com/3.0/lists/'.$listID.'/members/', array(
		           	'headers'     => array('Content-Type' => 'application/json; charset=utf-8' , 'Authorization' => 'Basic '.$auth),
					'method' => 'POST',
					'timeout' => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'body' => $json_data,
					)
				);

				if ( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   return "Something went wrong: $error_message";
				}
				if (!is_array($response)) {
					$response = array();
					$response['body'] = 'Nothing Returned';
				}
				$result = $response['body'];

	            /*
	            if ( !function_exists('curl_init') ) return('CURL not supported. (introduced in PHP 4.0.2)');
	            $ch = curl_init();
	            curl_setopt($ch, CURLOPT_URL, 'https://'.$dataCenter.'.api.mailchimp.com/3.0/lists/'.$listID.'/members/');
	            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',  'Authorization: Basic '.$auth));
	            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	            curl_setopt($ch, CURLOPT_POST, true);
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                         
	            $result = curl_exec($ch);
				*/

	            $returnResult = "$result";
	            if ($result != false && $result != '') {
	            	$exists = "Member Exists";
	            	$Wlist = "Resource Not Found";
	            	$Wapi = "API Key Invalid";
	            	$Rsuccess = 'subscribed';

	            	if (strpos($result, $exists) !== false) {
					    $returnResult = 'Subscriber Already Exists';
					}
					if (strpos($result, $Wlist) !== false) {
					    $returnResult = 'Invalid List ID';
					}
					if (strpos($result, $Wapi) !== false) {
					    $returnResult = 'Invalid API Key';
					}
					if (strpos($result, $Rsuccess) !== false) {
					    $returnResult = 'success';
					}
	            }

	            return $returnResult;
		}

		function ssm_send_to_drip($apikey,$apiAddress, $name,$email,$formName,$customs){
			require_once ULPB_PLUGIN_PATH.'/integrations/drip/Drip.php';
			require_once ULPB_PLUGIN_PATH.'/integrations/drip/Dataset.php';
			require_once ULPB_PLUGIN_PATH.'/integrations/drip/Response.php';
			require_once ULPB_PLUGIN_PATH.'/integrations/drip/Batch.php';
			
			$Drip = new Drip($apikey, $apiAddress);

			$data = new Dataset('subscribers', array(
				'email' => $email,
				'custom_fields'=> array(
					'name' => "$name"
				),
				'tags' => "$formName"
			) );

			$Response = $Drip->post('subscribers', $data);

			if ($Response->status == 200) {
			  return "success";
			} else {
			  return $Response->message;
			}
		}

		$ssm_post_id = filter_var($_REQUEST['sm_pID'],FILTER_SANITIZE_STRING);

		$PSdata = get_post_meta( $ssm_post_id, 'ULPB_DATA', true );

			if (isset( $PSdata['RowsDivided'] )) {
			  if ($PSdata['RowsDivided'] > 0 ) {
			    $rowsCollection = array();
			    for($i = 0; $i< $PSdata['RowsDivided']; $i++ ){
			      $theseRows =  get_post_meta($ssm_post_id, 'ULPB_DATA_Rows_Part_'.$i, true);
			      foreach ($theseRows as $value) {
			        array_push($PSdata['Rows'], $value );
			      }
			    }
			  }
			}

		$multiOptionFieldValues = explode("\n", sanitize_textarea_field($_POST['pbFormTargetInfo']) );

		$currRow = (int)$multiOptionFieldValues[0];
		$currCol = trim($multiOptionFieldValues[1]);
		$currWidget = (int)$multiOptionFieldValues[2];

		$SubscribeFormBuilder = $PSdata['Rows'][$currRow][$currCol]['colWidgets'][$currWidget]['widgetSubscribeForm'];
		if (isset($SubscribeFormBuilder['formIntegrations'])) {
			$formIntegrations = $SubscribeFormBuilder['formIntegrations'];
			$getResponse = $formIntegrations['getResponse'];
			$isGrActive = $getResponse['isGrActive'];
			$GrApiKey = $getResponse['GrApiKey'];
			$GrAccountName = $getResponse['GrAccountName'];

			$campaignMonitor = $formIntegrations['campaignMonitor'];
			$isCmActive = $campaignMonitor['isCmActive'];
			$CmApiKey = $campaignMonitor['CmApiKey'];
			$CmAccountName = $campaignMonitor['CmAccountName'];

			$activeCampaign = $formIntegrations['activeCampaign'];
			$isAcActive = $activeCampaign['isAcActive'];
			$AcApiKey = $activeCampaign['AcApiKey'];
			$AcApiURL = $activeCampaign['AcApiURL'];
			$AcAccountName = $activeCampaign['AcAccountName'];

			$isDripActive = '';

			if (isset($formIntegrations['drip'])) {
				$drip = $formIntegrations['drip'];
				$isDripActive = $drip['isDripActive'];
				$DripApiKey = $drip['DripApiKey'];
				$DripAccountName = $drip['DripAccountName'];
			}

			if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
			   $AcApiURL = str_replace('https', 'http', $AcApiURL);
			}
		}

		if (isset($SubscribeFormBuilder['formDataMailChimpApi'])) {
			$dataApiKeyMC = $SubscribeFormBuilder['formDataMailChimpApi'];
			$dataListIdMC = $SubscribeFormBuilder['formDataMailChimpListId'];
		}else{
			$dataApiKeyMC = $PSdata['formMailchimp']['apiKey'];
			$dataListIdMC = $PSdata['formMailchimp']['listId'];
		}
		if (isset($SubscribeFormBuilder['isMcActive'])) {
			$isMcActive = $SubscribeFormBuilder['isMcActive'];
		}

		$data = check_input($_REQUEST['sm_name']);
		$data .= check_input($_REQUEST['sm_email']);
		$data_lower = strtolower($data);
		$data_spaces = str_replace(' ','',$data_lower);

		$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
		if (!preg_match($pattern,$data_spaces))
		{
		    echo ("Invalid Input");
		    exit;
		}
		else{

			$checkss = ssm_send_to_db();
			$ssm_post_id = filter_var($_REQUEST['sm_pID'],FILTER_SANITIZE_STRING);
			$sub_url ='';

			$submitterName  = check_input($_REQUEST['sm_name']);
			$submitterEmail = check_input($_REQUEST['sm_email']);

			$is_extensionActive = true;

			$returnSuccessArray = array();

			$getResponseResult = 'Inactive';
			if ($isGrActive == 'true' && $is_extensionActive == true) {
				$getResponseResult = send_data_to_gr_api_sub_form($GrApiKey,$GrAccountName, $submitterName, $submitterEmail, $GR_customs_array);
			}

			$campaignMonitorResult = 'Inactive';
			if ($isCmActive == 'true' && $is_extensionActive == true) {
				$campaignMonitorResult = ssm_send_to_campaign_monitor($CmApiKey,$CmAccountName,$submitterName, $submitterEmail);
			}

			$activeCampaignResult = 'Inactive';
			if ($isAcActive == 'true' && $is_extensionActive == true) {
				$activeCampaignResult = ssm_send_to_active_campaign($AcApiKey,$AcApiURL, $AcAccountName, $submitterName,$submitterEmail);
			}

			$mailChimpResult = 'Inactive';
			if ($isMcActive == 'true' && $is_extensionActive == true) {
				$mailChimpResult = ssm_send_to_mailchimp($dataApiKeyMC,$dataListIdMC,$submitterName, $submitterEmail);
			}

			$dripResult = 'Inactive';
			if ($isDripActive == 'true' && $is_extensionActive == true) {
				$customs = array();
				$dripResult = ssm_send_to_drip($DripApiKey,$DripAccountName,$submitterName, $submitterEmail, 'PluginOps Subscribe Form',$customs);
			}
			
			$returnSuccessArray['activeCampaign'] = $activeCampaignResult;

			$returnSuccessArray['campaignMonitor'] = $campaignMonitorResult;

			$returnSuccessArray['getResponse'] = $getResponseResult;

			$returnSuccessArray['drip'] = $dripResult;
				
			if ($checkss > 0) {
				$returnSuccessArray['database'] = 'success';
			}
			else{
				$returnSuccessArray['database'] = $checkss;
				
			}
			$returnSuccessArray['mailchimp'] = $mailChimpResult;

			echo json_encode($returnSuccessArray);
			exit();
		}
	}
}




function ulpb_subscribeForm_mailchimp_ajax(){
	function check_input($data)
	{
	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	}

	if ( 
	    ! isset( $_POST['POPB_SubsForm_Nonce'] ) 
	    || ! wp_verify_nonce( $_POST['POPB_SubsForm_Nonce'], 'POPB_send_Subsform_data' ) 
	) {

	   print 'Sorry, Security error.';
	   exit;

	} else {

		$ssm_post_id = check_input($_POST['sm_pID']);
		$PSdata = get_post_meta( $ssm_post_id, 'ULPB_DATA', true );

			if (isset( $PSdata['RowsDivided'] )) {
			  if ($PSdata['RowsDivided'] > 0 ) {
			    $rowsCollection = array();
			    for($i = 0; $i< $PSdata['RowsDivided']; $i++ ){
			      $theseRows =  get_post_meta($ssm_post_id, 'ULPB_DATA_Rows_Part_'.$i, true);
			      foreach ($theseRows as $value) {
			        array_push($PSdata['Rows'], $value );
			      }
			    }
			  }
			}

		$multiOptionFieldValues = explode("\n", sanitize_textarea_field($_POST['pbFormTargetInfo']) );

		$currRow = (int)$multiOptionFieldValues[0];
		$currCol = trim($multiOptionFieldValues[1]);
		$currWidget = (int)$multiOptionFieldValues[2];

		$SubscribeFormBuilder = $PSdata['Rows'][$currRow][$currCol]['colWidgets'][$currWidget]['widgetSubscribeForm'];

		if (isset($SubscribeFormBuilder['formDataMailChimpApi'])) {
			$dataApiKey = $SubscribeFormBuilder['formDataMailChimpApi'];
			$dataListId = $SubscribeFormBuilder['formDataMailChimpListId'];
		}else{
			$dataApiKey = $PSdata['formMailchimp']['apiKey'];
			$dataListId = $PSdata['formMailchimp']['listId'];
		}
		
		include ULPB_PLUGIN_PATH.'/integrations/MCAPI.class.php';
		$sm_api_key = $dataApiKey;
		$api = new MCAPI($sm_api_key);

		$ssm_post_id = check_input($_REQUEST['sm_pID']);
		$smf_fName = check_input($_REQUEST['sm_name']);
		$smf_email = check_input($_REQUEST['sm_email']);

			$merge_vars = Array( 
		        'EMAIL' => $smf_email,
		        'FNAME' => $smf_fName
		    );
			
		$data = check_input($_REQUEST['sm_name']);
		$data .= check_input($_REQUEST['sm_email']);
		$data_lower = strtolower($data);
		$data_spaces = str_replace(' ','',$data_lower);

		$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
		if (!preg_match($pattern,$data_spaces))
		{
		    echo ("Invalid Input");
		    exit; 
		}
		else{
			$list_id = $dataListId;
			$ssm_post_id = check_input($_REQUEST['sm_pID']);
			$retval = $api->listSubscribe($list_id, $smf_email,$merge_vars, 'html',false,true);

			$s_name = $smf_fName;
			$s_email = $smf_email;

			if (!filter_var($s_email, FILTER_VALIDATE_EMAIL)) {
			    echo  "Invalid email format"; 
			    exit;
			}
				
			$ssm_Name = wp_strip_all_tags($s_name);
			$ssm_Email = wp_strip_all_tags($s_email);

			$ssm_subscribers_list = get_post_meta($ssm_post_id,'ssm_subscribers_list',true);
			$array_size_prev = count($ssm_subscribers_list); 

			if ( ! is_array( $ssm_subscribers_list ) ){
				$ssm_subscribers_list = array();
			}

			$array_size_prev = count($ssm_subscribers_list);

			$newSubscriber = array(
						'name' => $ssm_Name,
						'email' => $ssm_Email
					);


			$ssm_subscribers_list_pid = $ssm_subscribers_list;

			array_push($ssm_subscribers_list_pid, $newSubscriber);
			
			$updateResultSubsList = update_post_meta( $ssm_post_id, 'ssm_subscribers_list', $ssm_subscribers_list_pid, $unique = false);

			

			$returnSuccessArray = array();
				
			if ($retval == true) {
				$returnSuccessArray['mailchimp'] = 'success';
			} else{
				$McErr = '';
				if($api->errorCode) {
					if((string)$api->errorCode == '-99'){
						$McErr = "Connection Blocked - Error code = -99";
					} elseif ((string)$api->errorCode == '214') {
						$McErr = "Subscriber Already Exists - Error code = 214";

					} elseif ((string)$api->errorCode == '104') {
						$McErr = "Invalid API Key Or List Name - Error code = 104";
									
					} elseif ((string)$api->errorCode == '200') {
						$McErr = "Invalid API Key Or List Name - Error code = 200";
						
					}  else {
						$McErr = "Unknown Error Occurred";
									
					}
				}
				$returnSuccessArray['mailchimp'] = $McErr;
			}

			$returnSuccessArray['database'] = $updateResultSubsList;

			echo json_encode($returnSuccessArray);
			exit();
			
		}
		
	}

		
}


function ulpb_formBuilderEmail_ajax(){


	if ( 
	    ! isset( $_POST['POPB_Form_Nonce'] ) 
	    || ! wp_verify_nonce( $_POST['POPB_Form_Nonce'], 'POPB_send_form_data' ) 
	) {

	   $returnSuccessArray['database'] = 'Sorry, Security error.';
	   echo json_encode($returnSuccessArray);
	   exit;

	} else {

		if ( sanitize_text_field($_POST['enteryoursubjecthere']) ) {
			print 'Thank you!';
			exit;
		}else{

		
			$ssm_post_id = sanitize_text_field( $_POST['psID'] );

			$PSdata = get_post_meta( $ssm_post_id, 'ULPB_DATA', true );

			if (isset( $PSdata['RowsDivided'] )) {
			  if ($PSdata['RowsDivided'] > 0 ) {
			    $rowsCollection = array();
			    for($i = 0; $i< $PSdata['RowsDivided']; $i++ ){
			      $theseRows =  get_post_meta($ssm_post_id, 'ULPB_DATA_Rows_Part_'.$i, true);
			      foreach ($theseRows as $value) {
			        array_push($PSdata['Rows'], $value );
			      }
			    }
			  }
			}

			$multiOptionFieldValues = explode("\n", sanitize_textarea_field($_POST['pbFormTargetInfo']) );

			$currRow = (int)$multiOptionFieldValues[0];
			$currCol = trim($multiOptionFieldValues[1]);
			$currWidget = (int)$multiOptionFieldValues[2];

			$formBuilder = $PSdata['Rows'][$currRow][$currCol]['colWidgets'][$currWidget]['widgetFormBuilder'];
			$thisFormEmailOptions = $formBuilder['widgetPbFbFormEmailOptions'];


			$formBuilderEnableMailChimp ='';
			$api = '';
			$list_id = '';
			$merge_vars = Array();
			$mc_submitterEmail = '';
			$merge_vars = Array();

			$returnSuccessArray = array();

			if (isset( $formBuilder['widgetPbFbFormMailChimp']['fbgreCaptcha'] )) {
				if ( $formBuilder['widgetPbFbFormMailChimp']['fbgreCaptcha'] == 'true' ) {
					if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
				        $gresecret = $formBuilder['widgetPbFbFormMailChimp']['fbgreCSiteSecret'];
				       // $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$gresecret.'&response='.$_POST['g-recaptcha-response']);
				        

				        $output = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify?secret='.$gresecret.'&response='.$_POST['g-recaptcha-response'] );
				        
				        /* 
						if ( !function_exists('curl_init') ){ 
				        	$captchaResult = 'CURL not supported. (introduced in PHP 4.0.2)';
					        $returnSuccessArray['gRecaptcha'] = $captchaResult;
							echo json_encode($returnSuccessArray);
							exit();
				        }
				        $ch = curl_init();
						curl_setopt_array($ch, [
						    CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
						    CURLOPT_POST => true,
						    CURLOPT_POSTFIELDS => [
						        'secret' => $gresecret,
						        'response' => $_POST['g-recaptcha-response'],
						    ],
						    CURLOPT_RETURNTRANSFER => true
						]);
						$output = curl_exec($ch);
						curl_close($ch);
						*/
						if (!is_array($output)) {
							$output = array();
							$output['body'] = '';
						}
						$responseData = json_decode($output['body']);
				        if($responseData->success){
				        	$returnSuccessArray['gRecaptcha'] = 'true';
				        }else{
				            $captchaResult = 'Verification Failed';
				            $returnSuccessArray['gRecaptcha'] = $captchaResult;
				            $returnSuccessArray['gRecaptchaError'] = ($responseData);
							echo json_encode($returnSuccessArray);
							exit();
				        }
				    }else{
				        $captchaResult = 'Captcha Not Set';
				        $returnSuccessArray['gRecaptcha'] = $captchaResult;
						echo json_encode($returnSuccessArray);
						exit();
				    }
				}
			}


			$is_MCextensionActive = true;
			$formBuilderEnableGetResponse = '';
			if (isset($formBuilder['widgetPbFbFormMailChimp']) && $is_MCextensionActive == true ) {

				$widgetPbFbFormMailChimp = $formBuilder['widgetPbFbFormMailChimp'];
				$formBuilderEnableMailChimp = $widgetPbFbFormMailChimp['formBuilderEnableMailChimp'];

				if ($formBuilderEnableMailChimp == 'true') {
					$formBuilderMCAccountName = $widgetPbFbFormMailChimp['formBuilderMCAccountName'];
					$formBuilderMCApiKey = $widgetPbFbFormMailChimp['formBuilderMCApiKey'];

					$list_id = $formBuilderMCAccountName;

					if (!isset($widgetPbFbFormMailChimp['formBuilderMCDoubleOptin'])) {
						$widgetPbFbFormMailChimp['formBuilderMCDoubleOptin'] = 'false';
					}


					if ($widgetPbFbFormMailChimp['formBuilderMCDoubleOptin'] == 'false') {
						$dblOptin = 'subscribed';
					}
					if ($widgetPbFbFormMailChimp['formBuilderMCDoubleOptin'] == 'true') {
						$dblOptin = 'pending';
					}

					if (!isset($widgetPbFbFormMailChimp['formBuilderMCGroups'])) {
						$widgetPbFbFormMailChimp['formBuilderMCGroups'] = 'false';
					}

					$formBuilderMCGroups = $widgetPbFbFormMailChimp['formBuilderMCGroups'];

					if (!isset($widgetPbFbFormMailChimp['formBuilderMCTags'])) {
						$widgetPbFbFormMailChimp['formBuilderMCTags'] = 'false';
					}

					$formBuilderMCTags = $widgetPbFbFormMailChimp['formBuilderMCTags'];

					$merge_vars = Array();
					$mc_submitterEmail = '';
				}

				if(isset($widgetPbFbFormMailChimp['formBuilderEnableGetResponse'])){
					$formBuilderEnableGetResponse = $widgetPbFbFormMailChimp['formBuilderEnableGetResponse'];
					$formBuilderGRAccountName = $widgetPbFbFormMailChimp['formBuilderGRAccountName'];
					$formBuilderGRApiKey = $widgetPbFbFormMailChimp['formBuilderGRApiKey'];
				}
				if(isset($widgetPbFbFormMailChimp['formBuilderEnableCM'])){
					$formBuilderEnableCM = $widgetPbFbFormMailChimp['formBuilderEnableCM'];
					$formBuilderCMAccountName = $widgetPbFbFormMailChimp['formBuilderCMAccountName'];
					$formBuilderCMApiKey = $widgetPbFbFormMailChimp['formBuilderCMApiKey'];
				}
				if(isset($widgetPbFbFormMailChimp['formBuilderEnableAC'])){
					$formBuilderEnableAC = $widgetPbFbFormMailChimp['formBuilderEnableAC'];
					$formBuilderACAccountName = $widgetPbFbFormMailChimp['formBuilderACAccountName'];
					$formBuilderACApiKey = $widgetPbFbFormMailChimp['formBuilderACApiKey'];
					$formBuilderACApiUrl = $widgetPbFbFormMailChimp['formBuilderACApiUrl'];

					if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
					   $formBuilderACApiUrl = str_replace('https', 'http', $formBuilderACApiUrl);
					}
				}
				$formBuilderEnableDrip = '';
				if(isset($widgetPbFbFormMailChimp['formBuilderEnableDrip'])){
					$formBuilderEnableDrip = $widgetPbFbFormMailChimp['formBuilderEnableDrip'];
					$formBuilderDripAccountName = $widgetPbFbFormMailChimp['formBuilderDripAccountName'];
					$formBuilderDripApiKey = $widgetPbFbFormMailChimp['formBuilderDripApiKey'];
				}

				$formBuilderEnableAweber = '';
				if(isset($widgetPbFbFormMailChimp['formBuilderEnableAweber'])){
					$formBuilderEnableAweber = $widgetPbFbFormMailChimp['formBuilderEnableAweber'];
					$formBuilderAweberList = $widgetPbFbFormMailChimp['formBuilderAweberList'];
				}

				$formBuilderEnableConvertKit = '';
				if(isset($widgetPbFbFormMailChimp['formBuilderEnableConvertKit'])){
					$formBuilderEnableConvertKit = $widgetPbFbFormMailChimp['formBuilderEnableConvertKit'];
					$formBuilderConvertKitAccountName = $widgetPbFbFormMailChimp['formBuilderConvertKitAccountName'];
					$formBuilderConvertKitApiKey = $widgetPbFbFormMailChimp['formBuilderConvertKitApiKey'];
				}
				$wfb_cWebHook = '';
				if(isset($widgetPbFbFormMailChimp['wfb_cWebHook'])){
					$wfb_cWebHook = $widgetPbFbFormMailChimp['wfb_cWebHook'];
					$wfb_cWebHookURL = $widgetPbFbFormMailChimp['wfb_cWebHookURL'];
				}

				$wfb_cWebHookType = 'POST';
				if(isset($widgetPbFbFormMailChimp['wfb_cWebHookType'])){
					$wfb_cWebHookType = $widgetPbFbFormMailChimp['wfb_cWebHookType'];
				}

				$wfb_zapWebHook = '';
				if(isset($widgetPbFbFormMailChimp['wfb_zapWebHook'])){
					$wfb_zapWebHook = $widgetPbFbFormMailChimp['wfb_zapWebHook'];
					$wfb_zapWebHookURL = $widgetPbFbFormMailChimp['wfb_zapWebHookURL'];
				}

				$wfb_zapWebHookType = 'POST';
				if(isset($widgetPbFbFormMailChimp['wfb_zapWebHookType'])){
					$wfb_zapWebHookType = $widgetPbFbFormMailChimp['wfb_zapWebHookType'];
				}


				$wfbMHEnable = '';
				if (isset($widgetPbFbFormMailChimp['wfbMHEnable'])) {
					$wfbMHEnable = $widgetPbFbFormMailChimp['wfbMHEnable'];
					$wfbMHApiKey = $widgetPbFbFormMailChimp['wfbMHApiKey'];
				}

				$wfbSibEnable = '';
				if (isset($widgetPbFbFormMailChimp['wfbSibEnable'])) {
					$wfbSibEnable = $widgetPbFbFormMailChimp['wfbSibEnable'];
					$wfbSibApiKey = $widgetPbFbFormMailChimp['wfbSibApiKey'];

					$wfbSibListIds = $widgetPbFbFormMailChimp['wfbSibListIds'];
				}

				$wfbMPEnable = '';
				if (isset($widgetPbFbFormMailChimp['wfbMPEnable'])) {
					$wfbMPEnable = $widgetPbFbFormMailChimp['wfbMPEnable'];
					$wfbMPList = $widgetPbFbFormMailChimp['wfbMPList'];
					$wfbMPConfEmail = $widgetPbFbFormMailChimp['wfbMPConfEmail'];
					$wfbMPWelcEmail = $widgetPbFbFormMailChimp['wfbMPWelcEmail'];
				}

				$wfbCCEnable = '';
				if (isset($widgetPbFbFormMailChimp['wfbCCEnable'])) {
					$wfbCCEnable = $widgetPbFbFormMailChimp['wfbCCEnable'];
					$wfbCCLists = $widgetPbFbFormMailChimp['wfbCCLists'];
				}

			}

			$widgetPbFbFormAllowDuplicates = 'true';
			if (isset($formBuilder['widgetPbFbFormAllowDuplicates'])) {
				$widgetPbFbFormAllowDuplicates = $formBuilder['widgetPbFbFormAllowDuplicates'];
			}



			$pbAllFormData = '';
			$fieldCount = 0;
			$submitterEmail = '';
			$submitterName = '';
			$formNameText = $thisFormEmailOptions['formEmailformName'];

			if ($formNameText == '') {
				$formNameText = 'PluginOps Form';
			}

			$GR_customs_array = array();
			$GR_customs_array_new = array();
			$CM_customs_array = array();
			$drip_customs_array = array();
			$aweber_customs_array = array();
			$default_customs_array = array();

			foreach ($_POST as $key => $value) {

				$thisFieldName =  sanitize_text_field( str_replace("field-$fieldCount-","" ,$key) );
				if ($thisFieldName == 'psID' || $thisFieldName == 'pbFormTargetInfo' || $thisFieldName == 'POPB_Form_Nonce' || $thisFieldName == '_wp_http_referer' || $thisFieldName == 'enteryoursubjecthere' || $thisFieldName == 'g-recaptcha-response') {
				}else{

					if (is_array($value)) {
						$valueArray = $value;
						$value = '';
						foreach ($valueArray as $k => $v) {
							$newVal = ($k+1) . " : ". $v. ", \n";
							$value = $value. $newVal;
						}
					}
					if ($thisFormEmailOptions['formEmailFormat'] == 'plain') {


						$pbAllFormDataPlainThisValue = $thisFieldName." : \n ". sanitize_text_field($value) .' ' ;
						$pbAllFormDataPrevValues = $pbAllFormData;
						$pbAllFormData = $pbAllFormDataPrevValues."\n \n".$pbAllFormDataPlainThisValue;
						$formName = $thisFormEmailOptions['formEmailformName']."\n \n";

						if (strtolower($thisFieldName) == 'email' || strtolower($thisFieldName) == 'enter email' || strtolower($thisFieldName) == 'your email' || strtolower($thisFieldName) == 'email here') {
							$submitterEmail = sanitize_text_field($value);
						}
						if (strtolower($thisFieldName) == 'name' || strtolower($thisFieldName) == 'first name' || strtolower($thisFieldName) == 'firstname') {
							$submitterName = sanitize_text_field($value);
						}

					} else{
						$pbAllFormDataHTMLThisValue = "<p><b>".$thisFieldName." : </b>  ". sanitize_text_field($value) ."</p>  <br> <hr>" ;
						$pbAllFormDataPrevValues = $pbAllFormData;
						$pbAllFormData = $pbAllFormDataPrevValues."<br>".$pbAllFormDataHTMLThisValue;
						$formName = '<br> <h3>'.$thisFormEmailOptions['formEmailformName'].'</h3> <br>';

						if (strtolower($thisFieldName) == 'email' || strtolower($thisFieldName) == 'enter email' || strtolower($thisFieldName) == 'your email' || strtolower($thisFieldName) == 'email here') {
							$submitterEmail = sanitize_text_field($value);
						}
						if (strtolower($thisFieldName) == 'name' || strtolower($thisFieldName) == 'first name' || strtolower($thisFieldName) == 'firstname') {
							$submitterName = sanitize_text_field($value);
						}
					}


						$merge_vars[strtoupper($thisFieldName)] = sanitize_text_field($value);
						$default_customs_array[ $thisFieldName ] = sanitize_text_field($value);

						if (strtolower($thisFieldName) != 'email' && strtolower($thisFieldName) != 'name') { // customs fields without name & email keys.
							$currentFieldToAddInCustoms = array('name' => strtolower($thisFieldName), 'content' =>  sanitize_text_field($value) );
							$currentFieldToAddInCustomsCM = array('key' => strtolower($thisFieldName), 'value' =>  sanitize_text_field($value) );

							$currentFeildToAddForGRNew = array(
								'customFieldId' => strtolower($thisFieldName),
								'value' => array( sanitize_text_field($value)  )
							);

							$thisFieldNameAweber = str_replace("_"," ",$thisFieldName);

							$drip_customs_array[ strtolower($thisFieldName) ] = preg_replace("/[^ \w]+/", "", sanitize_text_field($value) ) ;

							$aweber_customs_array[ strtolower($thisFieldNameAweber) ] = preg_replace("/[^ \w]+/", "", sanitize_text_field($value) ) ;


							$GR_customs_array_new[ strtolower($thisFieldName) ] = sanitize_text_field( $value);

							array_push($GR_customs_array, $currentFieldToAddInCustoms);
							array_push($CM_customs_array, $currentFieldToAddInCustomsCM);
						}

						if (strtolower($thisFieldName) == 'email' || strtolower($thisFieldName) == 'enteremail' || strtolower($thisFieldName) == 'youremail' || strtolower($thisFieldName) == 'emailhere' || strtolower($thisFieldName) == 'emailaddress' ) { 
							$mc_submitterEmail = sanitize_text_field($value);
						}
						if (strtolower($thisFieldName) == 'name' || strtolower($thisFieldName) == 'firstname' || strtolower($thisFieldName) == 'firstname' || strtolower($thisFieldName) == 'entername' || strtolower($thisFieldName) == 'yourname' || strtolower($thisFieldName) == 'namehere') {
							$submitterName = sanitize_text_field($value);
						}
					
				}

				
				

				$fieldCount++;
			}


			if ($thisFormEmailOptions['formEmailFormat'] == 'HTML') {

				$pbAllEmailData =  "<html>
				<head>
				    <title> New Form Submission </title>
				</head>
				<body> 
				<div style='font-family:Helvetica,Arial,sans-serif;box-sizing:border-box;font-size:14px;width:100%!important;height:100%;line-height:1.6em;background-color:#f6f6f6;margin:0; padding:2% 10%;'>
				<div style='padding:2% 10%; background: #fff; margin:3% 10%;'>  ".$formName.$pbAllFormData." </div> </div>
				</body> </html>";

				add_filter( 'wp_mail_content_type', 'pb_form_email_set_html_mail_content_type' );
				function pb_form_email_set_html_mail_content_type() {
				    	return 'text/html';
				}
			}
			else{
				$pbAllEmailData =  $formName.$pbAllFormData;
			}

			$fromEmailAddress = home_url();
			$fromEmailAddress = trim($fromEmailAddress, '/');

			if (!preg_match('#^http(s)?://#', $fromEmailAddress)) {
			    $fromEmailAddress = 'http://' . $fromEmailAddress;
			}

			$urlParts = parse_url($fromEmailAddress);

			$OnlyDomain = preg_replace('/^www\./', '', $urlParts['host']);

			$fromEmailAddress =  "pluginOpsForm@".$OnlyDomain;

			if (isset( $thisFormEmailOptions['formEmailfromEmail'] ) ) {
				if (!empty( $thisFormEmailOptions['formEmailfromEmail']  ) && $thisFormEmailOptions['formEmailfromEmail']  != '') {

					if (filter_var($thisFormEmailOptions['formEmailfromEmail'], FILTER_VALIDATE_EMAIL)) {
					  $fromEmailAddress = $thisFormEmailOptions['formEmailfromEmail'];
					}
					
				}
			}

			if ($submitterName == '') {
				$submitterName = $thisFormEmailOptions['formEmailName'];
			}

			if ($submitterName == '' || $submitterName == 'PluginOps') {
				$submitterName = 'Not Set ';
			}
			
			$headers[]= "From: ".$submitterName." <".$fromEmailAddress.">";

			if (!empty($submitterEmail)) {
				$headers[] = "Reply-To: ".$submitterName." <".$submitterEmail.">";
			}

			if ($thisFormEmailOptions['formEmailSubject'] == '') {
				$formEmailSubject = 'PluginOps Form Submission';
			}else{
				$formEmailSubject = $thisFormEmailOptions['formEmailSubject'];
			}

			$pb_sendMail = wp_mail( $thisFormEmailOptions['formEmailTo'], $formEmailSubject, $pbAllEmailData, $headers );


			remove_filter( 'wp_mail_content_type', 'pb_form_email_set_html_mail_content_type' );

			
			function send_data_to_gr_api($apikey,$accountName, $name, $email, $customs ){


				$url = "https://api.getresponse.com/v3/custom-fields";
		        $headers = array();
		        $headers[] = "X-Auth-Token: api-key $apikey";
		        $state_ch = curl_init();
		        curl_setopt($state_ch, CURLOPT_URL, $url);
		        curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
		        $state_result = curl_exec ($state_ch);
		        $state_result = json_decode($state_result);
		        $debug = 1;

		        $customFeildsPostData = array();

		        foreach ($state_result as $key => $value) {

		        	$thisField = array();
		        	$thisField['name'] = $value->name;
		        	$thisField['id'] = $value->customFieldId;

		        	if ( $customs[ strtolower($value->name) ] ) {
		        		$currentFeildToAddForGRNew = array(
							'customFieldId' => $value->customFieldId,
							'value' => array(  $customs[ strtolower($value->name) ] )
						);

						array_push($customFeildsPostData, $currentFeildToAddForGRNew);
		        	}

		        }



		        $url = "https://api.getresponse.com/v3/campaigns";
		        $headers = array();
		        $headers[] = "X-Auth-Token: api-key $apikey";
		        $state_ch = curl_init();
		        curl_setopt($state_ch, CURLOPT_URL, $url);
		        curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
		        $state_result = curl_exec ($state_ch);
		        $state_result = json_decode($state_result);
		        $debug = 1;

		        $campaignId = '';
		        foreach ($state_result as $key => $value) {

		        	$thisField = array();
		        	if ($accountName == $value->name) {
		        		$campaignId = $value->campaignId;
		        	}

		        }



				$urlPost = "https://api.getresponse.com/v3/contacts";
				$data = array(
					'name'		=>	$name,
					'campaign'	=> array( 'campaignId' => $campaignId),
					'email'		=> $email,
					'customFieldValues' => $customFeildsPostData
				);

				$json_data = json_encode($data);
		        
		        $headers = array();
		        $headers[] = 'Content-Type: application/json';
		        $headers[] = "X-Auth-Token: api-key $apikey";
		        $state_ch = curl_init();
		        curl_setopt($state_ch, CURLOPT_URL, $urlPost);
		        curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
		        curl_setopt($state_ch, CURLOPT_POSTFIELDS, $json_data);
		        curl_setopt($state_ch, CURLOPT_TIMEOUT, 10);
		        curl_setopt($state_ch, CURLOPT_POST, true);
		        curl_setopt($state_ch, CURLOPT_SSL_VERIFYPEER, false);
		        $response = curl_exec ($state_ch);
		        $response = json_decode($response);
		        $debug = 1;

		        if ($response) {
		        	if ($response->codeDescription) {
			        	return $response->codeDescription . "  " . $response->message;
			        }
		        }else{
		        	return "success";
		        }
			        
			}


			function ssm_send_to_campaign_monitor_formBuilder($apikey, $listID, $name,$email, $customs){
				require_once ULPB_PLUGIN_PATH.'/integrations/createsend/csrest_subscribers.php';

				$auth = array('api_key' => $apikey);
				$wrap = new CS_REST_Subscribers($listID,$auth);

				$result = $wrap->add(
					array(
						'EmailAddress' => $email,
						'Name'		   => $name, 
						'Resubscribe'  => true,
						'CustomFields' => $customs,
					)
				);
				
				if($result->was_successful()) {
				    return "success";
				} else {
				    return $result->response;
				}
			}


			function ssm_send_to_active_campaign_formBuilder($apikey,$apiAddress, $listID, $name,$email,$customs){

				$url = $apiAddress;
				$params = array(
				    'api_key'      => $apikey,
				    'api_action'   => 'contact_add',
				    'api_output'   => 'serialize',
				);

				$post = array(
				    'email'                    => $email,
				    'first_name'               => $name,
				    'tags'                     => 'PluginOps Form',
				    'p['.$listID.']'           => $listID,
				    'status['.$listID.']'      => 1,
				    'instantresponders[123]'   => 1,
				);

				foreach ($customs as $key => $value) {
					$fieldName = strtoupper($value['name']);

					if ($fieldName == 'NAME' || $fieldName == 'EMAIL') {
						
					}else{
						$post['field[%'.$fieldName.'%,0]'] = $value['content'];
					}
				}
				$query = "";
				foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
				$query = rtrim($query, '& ');

				$data = "";
				foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
				$data = rtrim($data, '& ');

				$url = rtrim($url, '/ ');

				if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
				    return('JSON not supported. (introduced in PHP 5.2.0)');
				}

				$api = $url . '/admin/api.php?' . $query;

				$response = wp_remote_post( $api, array(
					'method' => 'POST',
					'timeout' => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'body' => $data,
				    )
				);

				if ( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   return "Something went wrong: $error_message";
				}
				if ( !$response ) {
				    return('Nothing was returned. Do you have a connection to Email Marketing server?');
				}
				if (!is_array($response)) {
					$response = array();
					$response['body'] = 'Nothing Returned';
				}
				$result = unserialize($response['body']);
				if ($result == false) {
					$response = json_decode( $response['body'] );
					$result = array();
					$result['result_code'] = $response->result_code;
					$result['result_message'] = $response->result_message;
				}

				/*
				$request = curl_init($api); 
				curl_setopt($request, CURLOPT_HEADER, 0); 
				curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($request, CURLOPT_POSTFIELDS, $data); 
				curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
				$response = (string)curl_exec($request); 
				curl_close($request); 
				*/

				if ($result['result_code'] ) {
					return 'success';
				}else{
					return $result['result_message'];
				}
			}

			function ssm_send_to_drip_formBuilder($apikey,$apiAddress, $name,$email,$formName,$customs){
				require_once ULPB_PLUGIN_PATH.'/integrations/drip/Drip.php';
				require_once ULPB_PLUGIN_PATH.'/integrations/drip/Dataset.php';
				require_once ULPB_PLUGIN_PATH.'/integrations/drip/Response.php';
				require_once ULPB_PLUGIN_PATH.'/integrations/drip/Batch.php';

				$Drip = new Drip($apikey, $apiAddress);

				$data = new Dataset('subscribers', array(
					'email' => $email,
					'custom_fields'=> $customs,
					'tags' => "$formName"
				) );

				$Response = $Drip->post('subscribers', $data);

				if ($Response->status == 200) {
				  return "success";
				} else {
				  return $Response->message;
				}
			}


			function ssm_send_to_aweber_formBuilder($listUrl,$name,$submitterEmail,$formName,$customs){
					$authCode = get_option( 'ulpb_aweber_auth_code' );
					if ($authCode != '' && $authCode != null) {
						try{

					    	require(ULPB_PLUGIN_PATH.'/integrations/aweber_api/aweber_api.php');
							$authCode = get_option( 'ulpb_aweber_auth_code' );

							list( $auth_key, $auth_token, $req_key, $req_token, $oauth ) = explode( '|', $authCode );

							$aweber = new AWeberAPI( $auth_key, $auth_token );


						    $aweber->user->tokenSecret = $req_token;
						    $aweber->user->requestToken = $req_key;
						    $aweber->user->verifier = $oauth;

					      $account = $aweber->getAccount( get_option( 'ulpb_aweber_accessToken'), get_option( 'ulpb_aweber_accessTokenSecret') );

					      if ( empty($customs) ) {
					      	$customs = array('key' => 'value' );
					      }

					      try {
					      	$list = $account->loadFromUrl($listUrl);

						      $params = array(
							        'email' => "$submitterEmail",
							        'name' => "$name",
							        'custom_fields' => $customs,
							        'tags' => array("$formName"),
							    );
						      $subscribers = $list->subscribers;
						      $new_subscriber = $subscribers->create($params);

						     return "success";
					      }catch(AWeberAPIException $exc){
						      if ($exc->message == 'email: Subscriber already subscribed.') {
						      	return "Subscriber Already Exists";
						      }else{
						      	return $exc->message;
						      }
					      }
					      
					    } catch(Exception $e){
					      return "$e";
					    }
					}else{
						return "Inactive";
					}

			} // aweber function 

			function ssm_send_to_convertkit_formBuilder($apikey,$listID,$name,$email,$customs){

				$url = "https://api.convertkit.com/v3/courses/$listID/subscribe?api_key=$apikey";
				$response = wp_remote_post( $url, array(
				  'method' => 'POST',
				  'timeout' => 45,
				  'redirection' => 5,
				  'httpversion' => '1.0',
				  'blocking' => true,
				  'headers' => array(),
				  'body' => array('email' => "$email" , 'name'=>"$name", 'fields' => $customs ),
				  )
				);


				if ( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   return "Something went wrong: $error_message";
				} else {

					if ($response['response']['message'] == 'OK' || $response['response']['code'] == '200') {
						return 'success';
					}else{
						return $response['response']['message'];
					}
				   
				}
			}


			function mc_checkuserstatus($email, $debug = false, $apikey, $listid, $server) {
			    $userid = md5($email);
			    $auth = base64_encode( 'user:'. $apikey );
			    $data = array(
			        'apikey'        => $apikey,
			        'email_address' => $email
			        );
			    $json_data = json_encode($data);

			    $response = wp_remote_post( 'https://'.$server.'.api.mailchimp.com/3.0/lists/'.$listid.'/members/' . $userid, array(
			    	'headers' => array('Content-Type' => 'application/json; charset=utf-8' , 'Authorization' => 'Basic '.$auth),
					'method' => 'GET',
					'timeout' => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'body' => $json_data,
					)
				);

				
				if ( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   return "Something went wrong: $error_message";
				}
				if (!is_array($response)) {
					$response = array();
					$response['body'] = 'Nothing Returned';
				}

				$result = $response['body']; 

			    $json = json_decode($result);
			    return $json->{'status'};
			    exit();
			}

			function ssm_send_to_mailchimp_formBuilder($apikey, $listId,$name, $email,$customs,$dblOptin,$formBuilderMCGroups, $formBuilderMCTags ) {
			    list(, $dataCenter) = explode('-', $apikey);
	            $auth = base64_encode( 'user:'.$apikey );


	            $tagsExploded = array();
	            if ($formBuilderMCTags != 'false' && $formBuilderMCTags != '' ) {
					$tagsExploded = explode(',', $formBuilderMCTags);
	            }

	            $data = array(
	                'apikey'        => $apikey,
	                'email_address' => $email,
	                'status'        => $dblOptin,
	                'merge_fields'  => $customs,
	                'tags'			=> $tagsExploded,
	            );

	            if ($formBuilderMCGroups != 'false' && $formBuilderMCGroups != '') {
	            	$data = array(
		                'apikey'        => $apikey,
		                'email_address' => $email,
		                'status'        => $dblOptin,
		                'merge_fields'  => $customs,
		                'interests'		=> array(
		            						$formBuilderMCGroups 	=> true,
		            					), 
		                'tags'			=> $tagsExploded,
		            );
	            }

	            
	            $json_data = json_encode($data);
	            $checkmcStatus = mc_checkuserstatus($email, false, $apikey, $listId, $dataCenter);
	            if ($checkmcStatus == 'pending' || $checkmcStatus == 'subscribed') {
	            	$requestMethod = 'PUT';
	            }else{
	            	$requestMethod = 'PUT';
	            }
	            $lowerCaseEmail = strtolower($email);
	            $lowerCaseEmail = md5($lowerCaseEmail);
	            
		        $response = wp_remote_post( 'https://'.$dataCenter.'.api.mailchimp.com/3.0/lists/'.$listId.'/members/'.$lowerCaseEmail, array(
		           	'headers'     => array('Content-Type' => 'application/json; charset=utf-8' , 'Authorization' => 'Basic '.$auth),
					'method' => $requestMethod,
					'timeout' => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'body' => $json_data,
					)
				);

				if ( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   return "Something went wrong: $error_message";
				}
				if (!is_array($response)) {
					$response = array();
					$response['body'] = 'Nothing Returned';
				}
				$result = $response['body'];
	            

	            $returnResult = "$result";
	            if ($result != false && $result != '') {
	            	$exists = "Member Exists";
	            	$Wlist = "Resource Not Found";
	            	$Wapi = "API Key Invalid";
	            	$Rsuccess = 'subscribed';
	            	$RsuccessTwo = 'pending';

	            	if (strpos($result, $exists) !== false) {
					    $returnResult = 'Subscriber Already Exists';
					}
					if (strpos($result, $Wlist) !== false) {
					    $returnResult = 'Invalid List ID';
					}
					if (strpos($result, $Wapi) !== false) {
					    $returnResult = 'Invalid API Key';
					}
					if (strpos($result, $Rsuccess) !== false) {
					    $returnResult = 'success';
					}
					if (strpos($result, $RsuccessTwo) !== false) {
					    $returnResult = 'success';
					}
	            }

	            return $returnResult;
			}

			function customWebHookDataPostRequestTransfer($customs, $wfb_cWebHookURL) {

				$json_data = json_encode($customs);

				
				$response = wp_remote_post( $wfb_cWebHookURL, array(
					'method' => $requestMethod,
					'timeout' => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'body' => $json_data,
					)
				);

				if ( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   return "Something went wrong: $error_message";
				}
				if (!is_array($response)) {
					$response = array();
					$response['body'] = 'Nothing Returned';
				}
				$result = $response['body'];
				
				/*
				if ( !function_exists('curl_init') ) return('CURL not supported. (introduced in PHP 4.0.2)');
			    $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $wfb_cWebHookURL);
		        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',  'Authorization: Basic '.$auth));
		        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		        curl_setopt($ch, CURLOPT_POST, true);
		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                         
		        $result = curl_exec($ch);
		        curl_close ($ch);
		        */
		        
		        return $result;
			}

			function customWebHookDataGetRequestTransfer($customs, $wfb_cWebHookURL) {

				$json_data = json_encode($customs);

				$createParamString = '';
				$customsSize = sizeof($customs);
				$loopCount = 1;
				foreach ($customs as $key => $value) {
					if ($loopCount == $customsSize) {
						$NewParamString = $key."=".$value;
					}else{
						$NewParamString = $key."=".$value."&";
					}
					$createParamString = $createParamString.$NewParamString;
					$loopCount++;
				}
				
				$wfb_cWebHookURL = $wfb_cWebHookURL.'?'.$createParamString;

				$response = wp_remote_get( $wfb_cWebHookURL, array(
					'method' => $requestMethod,
					'timeout' => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'body' => $json_data,
					)
				);

				if ( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   return "Something went wrong: $error_message";
				}
				if (!is_array($response)) {
					$response = array();
					$response['body'] = 'Nothing Returned';
				}
				$result = $response['body'];
				
				/*
				if ( !function_exists('curl_init') ) return('CURL not supported. (introduced in PHP 4.0.2)');
			    $ch = curl_init();
		        curl_setopt($ch, CURLOPT_URL, $wfb_cWebHookURL);
		        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',  'Authorization: Basic '.$auth));
		        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		        curl_setopt($ch, CURLOPT_POST, true);
		        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);                                         
		        $result = curl_exec($ch);
		        curl_close ($ch);
		        */
		        
		        return $result;
			}

			function marketHeroSendFormBuilderData( $name,$email, $customs, $wfbMHApiKey,$formNameText) {

				$post_data_body = array(
					'apiKey' => $wfbMHApiKey,
					'firstName' => $name,
					'email' => $email,
					'tags' => array(
						"$formNameText"
					),
				);
				$post_data_body = json_encode($post_data_body);
				$response = wp_remote_post( 
					'https://api.markethero.io/v1/api/tag-lead', 
					array(
		           	'headers'     => false,
					'method' => 'post',
					'timeout' => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
					'body' => $post_data_body,
					)
				);


				if ( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   return "Something went wrong: $error_message";
				}
				if (!is_array($response)) {
					$response = array();
					$response['body'] = 'Nothing Returned';
				}
				
				if ( $response['response']['message'] == 'OK') {
					$result = 'success';
				}else{
					$result = 'Some Error Occurred - '.$response['response']['message'];
				}
		        
		        return $result;
			}

			function sendInBlueSendFormBuilderData( $name,$email, $customs, $wfbMHApiKey,$wfbSibListIds,$formNameText) {

				if (!isset($customs['NAME'])) {
					$customs['NAME'] = $name;
				}

				if ($wfbSibListIds == NULL || $wfbSibListIds == '') {
					$wfbSibListIds = '2';
				}

				$sibListIdsExploded = array();
				$sibListIdsExploded = array_map('intval', explode(',', $wfbSibListIds));

				$post_data_body = array(
					'email' => $email,
					'emailBlacklisted' => false,
					'smsBlacklisted' => false,
					'attributes' => $customs,
					'listIds' => $sibListIdsExploded,
					'updateEnabled' => true
				);

				$post_data_body = json_encode($post_data_body);


				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://api.sendinblue.com/v3/contacts",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => $post_data_body,
				  CURLOPT_HTTPHEADER => array(
				    "accept: application/json",
				    "api-key:".$wfbMHApiKey,
				    "content-type: application/json"
				  ),
				));


				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);

				$response = json_decode($response);

				if ($err) {
				  echo "cURL Error #:" . $err;
				}else{

					$result = 'Unkown error occurred - '.$response->message . '| Code :'. $response->code;
					if ($response->id) {
						$result = 'success';
					}
					if ($response->message == 'Key not found'||  $response->code == 'unauthorized') {
						$result = 'Invalid API Key';
					}
					if ($response->message == 'Contact already exist') {
						$result = 'Subscriber Already Exists';
					}
				}

		        return $result;
			}


			function syncFormBuilderDataWithMailPoet($submitterName,$mc_submitterEmail, $merge_vars, $wfbMPList , $wfbMPConfEmail, $wfbMPWelcEmail, $formNameText ){
				if (class_exists(\MailPoet\API\API::class)) {
				  // Get MailPoet API instance
				  $mailpoet_api = \MailPoet\API\API::MP('v1');
				  $subscriber_form_fields = $mailpoet_api->getSubscriberFields();


				  $wfbMPConfEmail = $wfbMPConfEmail === 'true'? true: false;
				  $wfbMPWelcEmail = $wfbMPWelcEmail === 'true'? true: false;


				  $subscriberFieldsData = array();

				  if (isset($merge_vars['EMAIL'])) {
				  	$subscriberFieldsData['email'] = $merge_vars['EMAIL'];
				  }

				  if (isset($merge_vars['FIRSTNAME'])) {
				  	$subscriberFieldsData['first_name'] = $merge_vars['FIRSTNAME'];
				  	unset($merge_vars['FIRSTNAME']);
				  }

				  if (isset($merge_vars['LASTNAME'])) {
				  	$subscriberFieldsData['last_name'] = $merge_vars['LASTNAME'];
				  }
				  
				  $merge_vars = array_change_key_case($merge_vars, CASE_LOWER );

				  foreach ($subscriber_form_fields as $field) {
				  	$field['name'] = strtolower( $field['name'] );
				    if (!isset($merge_vars[ $field['name'] ]) || $field['name'] == 'email' ) {
				      continue;
				    }
				    $subscriberFieldsData[$field['id']] = $merge_vars[$field['name'] ];

				  }


				  $list_ids = array($wfbMPList);
				  $options = array(
				  	'send_confirmation_email' => $wfbMPConfEmail,
				  	'schedule_welcome_email' => $wfbMPWelcEmail,
				  );

				  try {
				  	 $Checksubscriber = $mailpoet_api->getSubscriber($subscriberFieldsData['email']);
				  } catch (\Exception $e) {}

				  try {
				    if (!$Checksubscriber) {
				      // Subscriber doesn't exist let's create one
				      $result = $mailpoet_api->addSubscriber($subscriberFieldsData, $list_ids , $options);
				    } else {
				      // In case subscriber exists just add him to new lists
				      $result = $mailpoet_api->subscribeToLists($subscriberFieldsData['email'], $list_ids , $options);
				    }
				  } catch (\Exception $e) {
				    $error_message = $e->getMessage(); 
				  }

				  if ($result) {
				  	//return $result;
				  	return 'success';
				  }

				  if ($error_message) {
				  	return "This error occurred - $error_message";
				  }

				}else{
					return "MailPoet Plugin is not active.";
				}
			}


			function constactContactSyncFormBuilderDataV3($name,$email,$merge_vars,$listID){


				$savedRefreshToken = get_option( 'popb_constant_contact_refresh_token', false );


				include ULPB_PLUGIN_PATH.'/integrations/constantContact/api-class.php';

				$constantContactSync = new ConstantContact_Popb_sync();


				$names = '
					"first_name": "'.$name.'",';
				if (isset($merge_vars['FIRSTNAME'])) {
					$names = '
					"first_name": "'.$merge_vars['FIRSTNAME'].'",';
				}

				if (isset($merge_vars['LASTNAME'])) {
					$names = $names.'
					"first_name": "'.$merge_vars['LASTNAME'].'",';
				}
				  
				$merge_vars = array_change_key_case($merge_vars, CASE_LOWER );


				$subscriber_form_fields = $constantContactSync->getConstantContactCustomFields($savedRefreshToken);


				$subscriber_form_fieldsArray = array();

				if ($subscriber_form_fields->custom_fields) {
					$subscriber_form_fields = $subscriber_form_fields->custom_fields;

					foreach ($subscriber_form_fields as $value) {
						$thisField['id'] = $value->custom_field_id;
						$thisField['name'] = $value->name;

						array_push($subscriber_form_fieldsArray, $thisField);
					}

				}

				$customFieldsData = array();

				foreach ($subscriber_form_fieldsArray as $field) {

				  	$field['name'] = strtolower( $field['name'] );

				    if (!isset($merge_vars[ $field['name'] ]) || $field['name'] == 'email' ) {
				      continue;
				    }

					$customFieldsData[$field['id']] = $merge_vars[$field['name'] ];
				}


				$allCustomFields = '';
				foreach ($customFieldsData as $key => $value) {
					$thisCustomField = '{
					      "custom_field_id":"'.$key.'",
					      "value": "'.$value.'"
					    },';
					$allCustomFields = $allCustomFields. $thisCustomField;
				}

				$customFieldsString = '';
				if ($allCustomFields != '' || $allCustomFields != ' ') {
					$customFieldsString = '"custom_fields": [
						    '.$allCustomFields.'
						  ],';
				}
					

				$body = '{
					  "email_address": {
					    "address": "'.$email.'",
					    "permission_to_send": "implicit"
					  },
					  '.$names.'
					  "create_source": "Account",
					  '.$customFieldsString.'
					  "list_memberships": [
					    "'.$listID.'"
					  ]
				}';


				$savedRefreshToken = get_option( 'popb_constant_contact_refresh_token', false );

				$res = $constantContactSync->constactContactSyncFormBuilderData($body,$savedRefreshToken);
				$result = json_decode($res);

				if ( is_array($result )) {

					if ($result[0]->error_key) {
						if ($result[0]->error_key == 'contacts.api.conflict') {
							$response = "Subscriber Already Exists";
						}
					}
				}else{
					if ($result->error_key) {
						$response = $result->error_key;

						if ($result->error_key == 'contacts.api.conflict'){
							$response = "Subscriber Already Exists";
						}
						if ($result->error_key == 'unauthorized'){
							$response = "Connection Problem, Please connect with your ConstantContact account. ";
						}
						
					}
				}

					
				if ($result->contact_id) {
					$response = 'success';
				}


				return $response;

			}

			


			$prevFBallSubmissions = get_post_meta( $ssm_post_id, 'ulpb_formBuilder_data_submission', true );

			$ssm_conversion_count = get_post_meta($ssm_post_id,'ssm_conversion_count',true);
			$ssm_conversion_count++;

			$userTimeZone = get_option('timezone_string');
			date_default_timezone_set($userTimeZone);
			$todaysDate = date("d-m-Y");

			$submissionPostMetaValue = array('Form_Name'=> $formNameText, 'Form_Fields' => $merge_vars, 'date' => $todaysDate);

			if (!is_array($prevFBallSubmissions)) {
				$prevFBallSubmissions = array();
			}


				
			if ($widgetPbFbFormAllowDuplicates == 'false') {

				$subExists = false;
				foreach ($prevFBallSubmissions as $key => $value) {

					if ($value["Form_Fields"]['EMAIL'] == $mc_submitterEmail) {
						$subExists = true;
					}
				}
				if ($subExists == true) {
					$updateSubmissions = 'Subscriber Already Exists';
				}else{
					array_push($prevFBallSubmissions, $submissionPostMetaValue);

					$updateSubmissions = update_post_meta( $ssm_post_id, 'ulpb_formBuilder_data_submission', $prevFBallSubmissions, $unique = false );
				}
			}else{
				array_push($prevFBallSubmissions, $submissionPostMetaValue);

				$updateSubmissions = update_post_meta( $ssm_post_id, 'ulpb_formBuilder_data_submission', $prevFBallSubmissions, $unique = false );
			}
				
				
			$updateResultConversionCount = update_post_meta( $ssm_post_id, 'ssm_conversion_count', $ssm_conversion_count, $unique = false);


			if ($pb_sendMail == true) {
				$returnSuccessArray['email'] = 'success';

				if ($subExists == true) {
					$returnSuccessArray['email'] = 'Subscriber Already Exists';
				}
			} else{
				$returnSuccessArray['email'] = 'Some Error Occured while sending the request!';
			}


			$isPremExtensionActive = false;
			if (is_plugin_active( 'PluginOps-Extensions-Pack/extension-pack.php' )  ) {
                if (function_exists('ulpb_available_pro_widgets') ) {
                	$isPremExtensionActive = true;
                }
            }


            $MCreturnVal = 'Inactive';
			$getResponseResult = 'Inactive';
			$CMResult = 'Inactive';
			$ACResult = 'Inactive';
			$DripResult = 'Inactive';
			$AweberResult = 'Inactive';
			$ConvertKitResult = 'Inactive';
			$ConvertKitResult = 'Inactive';
			$MHreturnVal = 'Inactive';
			$SIBreturnVal = 'Inactive';
			$MailPoetreturnVal = 'Inactive';
			$ConstantContactreturnVal = 'Inactive';
			$customHookResult = 'Inactive';

			if ( $isPremExtensionActive == true ) {

				if ($formBuilderEnableMailChimp == 'true') {
					$MCreturnVal = ssm_send_to_mailchimp_formBuilder($formBuilderMCApiKey, $formBuilderMCAccountName,$submitterName, $mc_submitterEmail,$merge_vars, $dblOptin, $formBuilderMCGroups, $formBuilderMCTags);
				}


				if ($formBuilderEnableGetResponse == 'true') {
					$getResponseResult = send_data_to_gr_api($formBuilderGRApiKey,$formBuilderGRAccountName, $submitterName, $submitterEmail, $GR_customs_array_new);
				}

				if ($formBuilderEnableCM == 'true') {
					$CMResult = ssm_send_to_campaign_monitor_formBuilder($formBuilderCMApiKey,$formBuilderCMAccountName, $submitterName, $submitterEmail, $CM_customs_array);
				}


				if ($formBuilderEnableAC == 'true') {
					$ACResult = ssm_send_to_active_campaign_formBuilder($formBuilderACApiKey,$formBuilderACApiUrl, $formBuilderACAccountName, $submitterName,$submitterEmail,$GR_customs_array);
				}


				if ($formBuilderEnableDrip == 'true') {
					$DripResult = ssm_send_to_drip_formBuilder($formBuilderDripApiKey,$formBuilderDripAccountName, $submitterName,$submitterEmail,$formNameText,$drip_customs_array);
				}

				if ($formBuilderEnableAweber == 'true') {
					$AweberResult = ssm_send_to_aweber_formBuilder($formBuilderAweberList,$submitterName,$submitterEmail,$formName,$aweber_customs_array);
				}

				if ($formBuilderEnableConvertKit == 'true') {
					$ConvertKitResult =  ssm_send_to_convertkit_formBuilder($formBuilderConvertKitApiKey,$formBuilderConvertKitAccountName,$submitterName, $submitterEmail,$aweber_customs_array );
				}else{
					$ConvertKitResult = 'Inactive';
				}


				if ($wfb_cWebHook == 'true') {

					if ($wfb_cWebHookType == 'POST') {
						$customHookResult = customWebHookDataPostRequestTransfer($default_customs_array, $wfb_cWebHookURL);
					}

					if ($wfb_cWebHookType == 'GET') {
						$customHookResult = customWebHookDataGetRequestTransfer($default_customs_array, $wfb_cWebHookURL);
					}
					
				}

				if ($wfb_zapWebHook == 'true') {

					if ($wfb_zapWebHookType == 'POST') {
						$zapierWebHookResult = customWebHookDataPostRequestTransfer($default_customs_array, $wfb_zapWebHookURL);
					}

					if ($wfb_zapWebHookType == 'GET') {
						$zapierWebHookResult = customWebHookDataGetRequestTransfer($default_customs_array, $wfb_zapWebHookURL);
					}
					
				}

				if ($wfbMHEnable == 'true') {
					$MHreturnVal = marketHeroSendFormBuilderData( $submitterName,$mc_submitterEmail, $merge_vars, $wfbMHApiKey,$formNameText);
				}

				if ($wfbSibEnable == 'true') {
					$SIBreturnVal = sendInBlueSendFormBuilderData($submitterName,$mc_submitterEmail, $merge_vars, $wfbSibApiKey,$wfbSibListIds,$formNameText);
				}


				if ($wfbMPEnable == 'true') {
					$MailPoetreturnVal = syncFormBuilderDataWithMailPoet($submitterName,$mc_submitterEmail, $merge_vars, $wfbMPList , $wfbMPConfEmail, $wfbMPWelcEmail, $formNameText);
				}


				if ($wfbCCEnable == 'true') {
					$ConstantContactreturnVal = constactContactSyncFormBuilderDataV3($submitterName,$mc_submitterEmail, $merge_vars, $wfbCCLists);
				}

			}



			$returnSuccessArray['mailchimp'] = $MCreturnVal;
				
			$returnSuccessArray['convertkit'] = $ConvertKitResult;

			$returnSuccessArray['drip'] = $DripResult;

			$returnSuccessArray['activeCampaign'] = $ACResult;

			$returnSuccessArray['campaignMonitor'] = $CMResult;
				
			$returnSuccessArray['getResponse'] = $getResponseResult;

			$returnSuccessArray['aweber'] = $AweberResult;

			$returnSuccessArray['markethero'] = $MHreturnVal;

			$returnSuccessArray['SendinBlue'] = $SIBreturnVal; 

			$returnSuccessArray['MailPoet'] = $MailPoetreturnVal;

			$returnSuccessArray['ConstantContact'] = $ConstantContactreturnVal;

			$returnSuccessArray['WebHook'] = $customHookResult;



			if ($wfb_zapWebHook == 'true' && $isPremExtensionActive == true) {
				$zapierResult = json_decode($zapierWebHookResult);
				$returnSuccessArray['Zapier'] = $zapierResult->status;
			} else{
				$returnSuccessArray['Zapier'] = 'Inactive';
			}


			$returnSuccessArray['database'] = $updateSubmissions;
			echo json_encode($returnSuccessArray);

			
			exit();


		} // honeypot field check.

		   	
	}// nonce check.

		
}// funtion end.


function ulpb_getMCGroupIds(){
	//mc_checkuserstatus

	$POPB_data_Nonce = $_REQUEST['POPB_nonce'];
	if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
		die( 'Security check' );
	}else{

		$apikey = sanitize_text_field( $_REQUEST['apiKey'] );
		$listid = sanitize_text_field($_REQUEST['listID']);

		$auth = base64_encode( 'user:'. $apikey );
		list(, $server) = explode('-', $apikey);

		if ($apikey != '' && $listid != '') {
				$response = wp_remote_get( 'https://'.$server.'.api.mailchimp.com/3.0/lists/'.$listid.'/interest-categories/', array(
				'headers' => array('Content-Type' => 'application/json; charset=utf-8' , 'Authorization' => 'Basic '.$auth),
				'method' => 'GET',
				'timeout' => 5,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				)
			);
		}else{
			$result['error'] = 'list id or api key is empty';
			$resultJson = json_decode($result);
			echo $result;
			return;
			exit();
		}
		



		$responseBody =  json_decode( $response['body'] );
		$responseBodyCats = $responseBody->categories;

		$mcGroupsCombined = array();
		foreach ($responseBodyCats as $value) {
			$responseThree = wp_remote_get( 'https://'.$server.'.api.mailchimp.com/3.0/lists/'.$listid.'/interest-categories/'.$value->id.'/interests/', 
				array(
					'headers' => array('Content-Type' => 'application/json; charset=utf-8' , 'Authorization' => 'Basic '.$auth),
					'method' => 'GET',
					'timeout' => 5,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking' => true,
				)
			);

			$responseThreeDecoded = json_decode( $responseThree['body'] );

			$multiValueGroups = array();
			foreach ($responseThreeDecoded->interests as $valueTwo) {
				$SingleValueGroup = array($valueTwo->name => $valueTwo->id);
				array_push($multiValueGroups, $SingleValueGroup);
			}

			$mcGroupsCombined[$value->title] = $multiValueGroups;
		}


		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$result['error'] = "Something went wrong: $error_message";
			$resultJson = json_decode($result);
			echo $result;
			exit();
		}

		$result['success'] = $mcGroupsCombined; 

		$resultJson = json_encode($result);
		echo $resultJson;
		exit();
		
	}

			
}


function ulpb_getCkSequenceIds(){

	$POPB_data_Nonce = $_REQUEST['POPB_nonce'];
	if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
		die( 'Security check' );
	}else{

		$apikey = sanitize_text_field( $_REQUEST['apiKey'] );

		$url = "https://api.convertkit.com/v3/sequences?api_key=$apikey";
				$response = wp_remote_post( $url, array(
				  'method' => 'GET',
				  'timeout' => 45,
				  'redirection' => 5,
				  'httpversion' => '1.0',
				  'blocking' => true,
				  'headers' => array(),
				  )
				);

		if ( is_wp_error( $response ) ) {
		   $error_message = $response->get_error_message();
			return "Something went wrong: $error_message";
		} else {

			if ($response['response']['message'] == 'OK' || $response['response']['code'] == '200') {

				$sequences = json_decode($response['body']);
				$courses = $sequences->courses;
				$sequenceOptions = array();


				foreach ($courses as $key => $value) {
					$SingleValueGroup = array( 'name' => $value->name, 'id' => $value->id);
					array_push($sequenceOptions, $SingleValueGroup);
					
				}


				$result['success'] = $sequenceOptions;
				$resultJson = json_encode($result);
				echo $resultJson;
				exit();
			}else{
				echo $response['response']['message'];
			}
				   
		}

		
	}

			
}


function ulpb_cta_click_conversion_record(){
	if ( 
	    ! isset( $_GET['POPB_CTA_Nonce'] ) 
	    || ! wp_verify_nonce( $_GET['POPB_CTA_Nonce'], 'POPB_verify_clicked_el' ) 
	) {
	   echo 'Sorry, Security error.';
	   exit;
	}else{
		$ssm_post_id = sanitize_text_field( $_GET['pID'] );

		$ssm_clickThrough_count = get_post_meta($ssm_post_id,'ssm_clickThrough_count',true);
		$ssm_clickThrough_count++;

		$updateResultConversionCount = update_post_meta( $ssm_post_id, 'ssm_clickThrough_count', $ssm_clickThrough_count, $unique = false);

		echo 'success';
	   	exit;
	}
}

function ulpb_loadShortcode_content(){

	function check_input($data){
	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	}

	
	if( current_user_can('editor') || current_user_can('administrator') ) {
		$shortCodeRenderWidgetNO = $_REQUEST['POPB_Shortcode_nonce'];
		if ( ! wp_verify_nonce( $shortCodeRenderWidgetNO, 'POPB_data_nonce' ) ){
			die( 'Security check' ); 
		}else{

			$ulpb_entered_shortcode = check_input($_REQUEST['ulpb_shortcode']);
			if ( function_exists( 'load_smuzform_public_classes' ) ) {

				smuzform_public( 'core/class/class-smuzform-public.php' );
			

				$Public = new SmuzForm_Public();

				$Public->loadClasses();

				$Public->createUI();

			}
			
			echo do_shortcode( $ulpb_entered_shortcode );
		}
	}
	
	exit();
}




function ulpb_get_global_row_content(){

	if( current_user_can('editor') || current_user_can('administrator') ) {

		 $POPB_GRG_Nonce = $_REQUEST['POPB_GRG_Nonce'];
		 if ( ! wp_verify_nonce( $POPB_GRG_Nonce, 'POPB_data_nonce' ) ) {
		 	die( 'Security check' ); 
		 }else{
			function check_input($data){
			    $data = trim($data);
			    $data = stripslashes($data);
			    $data = htmlspecialchars($data);
			    return $data;
			}

			$GlobalRowPId = check_input($_POST['globalRowID']);

			$globalRowPostData = get_post_meta( $GlobalRowPId, ULPB_DATA, true );

			echo json_encode( $globalRowPostData['Rows'][0]);
		} 
	}

exit();
}




function ulpb_insert_template(){

	function check_input($data){

	    $data = trim($data);
	    $data = stripslashes($data);
	    $data = htmlspecialchars($data);
	    return $data;
	}
	if( current_user_can('editor') || current_user_can('administrator') ) {

		$insertTemplateNonce = $_REQUEST['insertTemplateNonce'];
		if ( ! wp_verify_nonce( $insertTemplateNonce, 'POPB_data_nonce' ) ) {
			die( 'Security check' ); 
		}else{
			$selectPostToInsert = check_input($_REQUEST['selectPostToInsert']);
			$pageToUpdate = check_input($_REQUEST['pageToUpdate']);
			$pageToUpdatePostType = check_input($_REQUEST['pageToUpdatePostType']);

			$dataToInsert = get_post_meta( $selectPostToInsert, 'ULPB_DATA', true );

		 	$returnSuccessArray  = array();

		 	if ($dataToInsert['Rows']) {
		 		$returnSuccessArray['Rows'] = $dataToInsert['Rows'];
		 		$returnSuccessArray['Message'] = 'Success';
		 	}else{
		 		$returnSuccessArray['Rows'] = 'Empty';
		 		$returnSuccessArray['Message'] = 'Failed';
		 	}
			//$dataToInsert["pageID"] = $pageToUpdate;
			//$dataToInsert["postType"] = $pageToUpdatePostType;

			//update_post_meta( $pageToUpdate, 'ULPB_DATA', $dataToInsert );
		 	echo json_encode($returnSuccessArray);
		}
	}
	exit();
}




function ulpb_subscribe_list_empty_wp_ajax(){

	if (current_user_can('activate_plugins' )) {

		$subsListEmpty = $_REQUEST['subsListEmpty'];
		if ( ! wp_verify_nonce( $subsListEmpty, 'POPB_data_nonce' ) ) {
			die( 'Security check' ); 
		}else{
			$post_ID = $_REQUEST['ps_ID'];

			 update_post_meta( $post_ID, 'ssm_subscribers_list', '', $unique = false);

			if ($result === 0) {
				echo "No records found!";
			}else if($result === false){
				echo "Some error occurred";
			}
			else{
				echo 'Success';
				exit();
			}
		}

	}

}





function ulpb_update_pagebuilder_active_option(){
	if( current_user_can('editor') || current_user_can('administrator') ) {

		$POPB_Switch_Nonce = $_REQUEST['POPB_Switch_Nonce'];
		if ( ! wp_verify_nonce( $POPB_Switch_Nonce, 'POPB_switch_nonce' ) ) {
			die( 'Security check' ); 
		}else{
			$page_id = intval($_GET['page_id']);
			$sentData = sanitize_text_field($_GET['ulpbActivate']);

			if ($sentData == 'ActivatePB') {
				update_post_meta($page_id, 'ulpb_page_builder_active','true');
			}else{
				update_post_meta($page_id, 'ulpb_page_builder_active','false');
			}

			echo "Switched";
		}
	}
	exit();
}


function ulpb_empty_form_builder_data(){
	$POPB_data_Nonce = $_REQUEST['submitNonce'];
		if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
			die( 'Security check' ); 
		}else{
			$this_postID = sanitize_text_field($_POST['ps_ID']);

			$updateData = update_post_meta( $this_postID, 'ulpb_formBuilder_data_submission', '', $unique = false );

			if ($updateData == true) {
				echo 'Success';
			} else{
				echo "Already empty";
			}
		}

		exit();
}

function ulpb_delete_form_builder_entry(){

	$POPB_data_Nonce = $_REQUEST['submitNonce'];
		if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
			die( 'Security check' ); 
		}else{
			
			$postID = sanitize_text_field($_POST['ps_ID']);
			$dataEntryIndex = sanitize_text_field($_GET['dataEntryIndex']);
			$allFormData = get_post_meta( $postID, 'ulpb_formBuilder_data_submission');

			unset($allFormData[0][$dataEntryIndex]);

			$allFormDataAfter = array_values($allFormData[0]);
			$updateAfterDeleting = update_post_meta( $postID, 'ulpb_formBuilder_data_submission', $allFormDataAfter, $unique = false );

			if ($updateAfterDeleting == true) {
				echo "success";
			}else{
				echo "Some error occurred!";
			}



		}
		exit();
}



function ulpb_delete_optin_analytics(){

		$POPB_data_Nonce = $_REQUEST['submitNonce'];
		if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
			die( 'Security check' ); 
		}else{
			
			$postID = sanitize_text_field($_GET['postID']);
			$actionConfirmed = sanitize_text_field($_GET['actionConfirmed']);

			if ($actionConfirmed == 'true') {
				update_post_meta( $postID, 'ulpb_page_hit_counter', '0' );
				update_post_meta( $postID, 'ulpb_page_views_counter', '0' );
				update_post_meta( $postID, 'ssm_conversion_count', '0' );
				update_post_meta( $postID, 'popb_closed_popup_count', '0' );
				update_post_meta($postID,'ctnTotal','0');
				update_post_meta($postID,'ctrTpLinks', '' );
				for ($i=0; $i <=300 ; $i++) {
				    $thisDate = date('d-m-Y',strtotime("-$i days"));
				    delete_post_meta($postID,"ulpb_page_views_counter_$thisDate");
				}
				
				echo 'success';
			}

		}
		exit();
}

function ulpb_get_new_analytics(){

		$POPB_data_Nonce = $_REQUEST['submitNonce'];
		if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
			die( 'Security check' ); 
		}else{
			$result['message'] = false;
			$postID = sanitize_text_field($_GET['postID']);
			$actionConfirmed = sanitize_text_field($_GET['actionConfirmed']);
			$dateRange = sanitize_text_field($_GET['dateRange']);
			if ($actionConfirmed == 'true') {
				$result['message'] = 'success';				
				$result['analytics'] = $this->ulpb_RenderAnalytics($postID, true, $uniqID = 'default', $dateRange);
			}else{
				$result['message'] = false;
			}

		}

		echo json_encode($result);
		exit();
}



function  ulpb_loadAnaytics($postID, $dateRange = 7){

    $pluginOpsUserTimeZone = get_option('timezone_string');
    if ($pluginOpsUserTimeZone != '' && $pluginOpsUserTimeZone != null) {
    date_default_timezone_set($pluginOpsUserTimeZone);
    }
    $todaysDate =  date('d-m-Y');

    $uniqueImpressions = get_post_meta($postID,'ulpb_page_hit_counter',true);
    $allImpressions = get_post_meta($postID,'ulpb_page_views_counter',true);
    $totalConversions = get_post_meta($postID,'ssm_conversion_count',true);

    $uniqueImpressionsToday = get_post_meta($postID,"ulpb_page_hit_counter_$todaysDate",true);
    $allImpressionsToday = get_post_meta($postID,"ulpb_page_views_counter_$todaysDate",true);

    $totalClicks = get_post_meta($postID,'ctnTotal',true);

    if ($uniqueImpressions == '') {
        $uniqueImpressions = 0;
    }
    if ($allImpressions == '') {
        $allImpressions = 0;
    }
    if ($allImpressions > 0) {
        $allImpressions = $allImpressions/2;
    }

    if ($totalConversions == '') {
        $totalConversions = 0;
    }

    if ($totalConversions > 0 && $allImpressions > 0) {
        $conversionRate = ((int)$totalConversions / $allImpressions)*100;
    } else{
        $conversionRate = 0;
    }
    $conversionRate =  round( $conversionRate, 1, PHP_ROUND_HALF_UP);

    if ($totalClicks == '') {
        $totalClicks = 0;
    }
    if ($totalClicks > 0 && $allImpressions > 0) {
        $ctrRate = ((int)$totalClicks / $allImpressions)*100;
    } else{
        $ctrRate = 0;
    }
    $ctrRate =  round( $ctrRate, 1, PHP_ROUND_HALF_UP);



    // Divide conversions by date.
    $ssm_subscribers_list = get_post_meta($postID,'ssm_subscribers_list',true);
    $smfb_formBuilder_data_list = get_post_meta($postID,'ulpb_formBuilder_data_submission',true);


    $numberOfConversions = array();
    $lastThirtyDates = array();
    $lastThirtyDatesForChart = array();
    $lastThirtyDaysImpressions = array();
    for ($i=0; $i <=$dateRange ; $i++) {
        $numberOfConversions[$i] = 0;
        $lastThirtyDates[$i] = date('d-m-Y',strtotime("-$i days"));
        $lastThirtyDatesForChart[$i] = date('d-M',strtotime("-$i days"));

        $thisDate = date('d-m-Y',strtotime("-$i days"));
        $lastThirtyDaysImpressions[$i] = get_post_meta($postID,"ulpb_page_views_counter_$thisDate",true);
        if ($lastThirtyDaysImpressions[$i] > 0) {
           $lastThirtyDaysImpressions[$i] = $lastThirtyDaysImpressions[$i] / 2;
        }
    }

    if (is_array($ssm_subscribers_list)) {
        foreach ($ssm_subscribers_list as $ssm_result) {
            if (isset($ssm_result['date']) ) {
              $dateOfssm = $ssm_result['date'];
            }else{
              $dateOfssm = 'Not Set';
            }

            for ($i=0; $i <=$dateRange ; $i++) {
                if ($dateOfssm == $lastThirtyDates[$i]) {
                    $numberOfConversions[$i]++;
                }
            }
                
        }
    }
        

    if (is_array($smfb_formBuilder_data_list)) {
        foreach ($smfb_formBuilder_data_list as $smfb_formBuilder_each_data) {
            if (isset($smfb_formBuilder_each_data['date']) ) {
              $dateOfssm = $smfb_formBuilder_each_data['date'];
            }else{
              $dateOfssm = 'Not Set';
            }

            for ($i=0; $i <=$dateRange ; $i++) {
                if ($dateOfssm == $lastThirtyDates[$i]) {
                    $numberOfConversions[$i]++;
                }
            }
            
        }
    }




    $returnArray = array();
    $returnArray['uniqueImpressions'] = $uniqueImpressions;
    $returnArray['allImpressions'] = $allImpressions;
    $returnArray['conversionRate'] = $conversionRate;
    $returnArray['totalConversions'] = $totalConversions;
    $returnArray['totalClicks'] = $totalClicks;
    $returnArray['ctrRate'] = $ctrRate;
    $returnArray['lastThirtyDatesForChart'] = $lastThirtyDatesForChart;
    $returnArray['numberOfConversions'] = $numberOfConversions;
    $returnArray['lastThirtyDaysImpressions'] = $lastThirtyDaysImpressions;
    
    return $returnArray;
}


function ulpb_RenderAnalytics($postID, $loadGraphs, $uniqID = 'default', $dateRange = 7){

    $defaultPageAnalytics = $this->ulpb_loadAnaytics($postID, $dateRange);
    $lastThirtyDatesForChart = $defaultPageAnalytics['lastThirtyDatesForChart'];
    $numberOfConversions = $defaultPageAnalytics['numberOfConversions'];
    $lastThirtyDaysImpressions = $defaultPageAnalytics['lastThirtyDaysImpressions'];
    ob_start();
    ?>

    <div id="pluginops_analytics" style="margin:0 auto; padding:1% 12.5%; background: #E7E7E7;">
        <div class="analytics-card">
            <h3> Unique Impressions </h3>
            <p> <?php echo $defaultPageAnalytics['uniqueImpressions']; ?> </p>    
        </div>
        <div class="analytics-card">
            <h3> All Impressions </h3>
            <p> <?php echo $defaultPageAnalytics['allImpressions']; ?> </p>
        </div>
        <div class="analytics-card">
            <h3> Conversion Rate </h3>
            <p> <?php  echo $defaultPageAnalytics['conversionRate'] ?> % </p>
        </div>
        <div class="analytics-card" >
            <h3> Total Conversions </h3>
            <p> <?php  echo $defaultPageAnalytics['totalConversions'] ?> </p>
        </div>
        <div class="analytics-card" >
            <h3> ClickThrough Rate (CTR) </h3>
            <p> <?php  echo $defaultPageAnalytics['ctrRate'] ?>  % </p>
        </div>
        <div class="analytics-card" >
            <h3> Total Clicks </h3>
            <p> <?php  echo $defaultPageAnalytics['totalClicks'] ?> </p>
        </div>
        <?php 
        if ($loadGraphs == true) { ?>
        <div class="analytics-card" style="">
            <canvas id="sevenDayConversionImpressions_<?php echo $uniqID; ?>" width="250" height="250"></canvas>      
        </div>
        <div class="analytics-card" style="">
            <canvas id="sevenDayConversionRate_<?php echo $uniqID; ?>" width="250" height="250"></canvas>      
        </div>
        <div class="analytics-card" >
            <h3> Links Analytics </h3>
            <div class="link_analytics_accordion">
            <?php
            	$ctrLinks = get_post_meta($postID,'ctrTpLinks',true);

            	if (is_array($ctrLinks)) {
            	
	            	foreach ($ctrLinks as $key => $value) {
	            		?>
	            		
	            		<h4> <?php echo "$key"; ?> </h4>
	            		<div>
		            		<table class="w3-table w3-bordered w3-striped linksTrackingdataTable">
		            			<tr class="ltdt-heading">
		            				<th>Date</th>
		            				<th>Clicks</th>
		            			</tr>
			            		<?php
						        foreach ($value as $date => $clickCount) {
						        	?>
						        		<tr>
						        			<td> <?php echo "$date"; ?> </td>
						        			<td> <?php echo "$clickCount"; ?> </td>
						        		</tr>
						        	<?php
						        }
					        ?>
					        </table>
				        </div>
				        <?php
			      	}
			      	
			    }
            ?>
        	</div>
        </div>
    <?php } ?>
    </div>
    
    <?php 
    if ($loadGraphs == true) { ?>
    	<script type="text/javascript">
    		jQuery( ".link_analytics_accordion" ).accordion({
		        collapsible: true,
		        heightStyle: "content"
	      	});
    	</script>
    <script>

    var lastSevenDates_<?php echo $uniqID; ?> = [<?php for ($i=0; $i <$dateRange ; $i++) { echo "'".$lastThirtyDatesForChart[$i]."',";} ?>];
    lastSevenDates_<?php echo $uniqID; ?>.reverse();
    var lastSevenData_<?php echo $uniqID; ?> = [<?php for ($i=0; $i <$dateRange ; $i++) { echo "'".$numberOfConversions[$i]."',";} ?>];
    lastSevenData_<?php echo $uniqID; ?>.reverse();

    var lastSevenDataImpressions_<?php echo $uniqID; ?> = [<?php for ($i=0; $i <$dateRange ; $i++) { 
        if ($lastThirtyDaysImpressions[$i] > 0) {
            $impression = $lastThirtyDaysImpressions[$i];
        }else{
            $impression = 0;
        }
        echo "'".$impression."',";} ?>];
    lastSevenDataImpressions_<?php echo $uniqID; ?>.reverse();

    var lastSevenDataConversionRate_<?php echo $uniqID; ?> = [ <?php for ($i=0; $i <$dateRange ; $i++) {
        $Noc = $numberOfConversions[$i];
        $Ltdi = $lastThirtyDaysImpressions[$i];
        if ($numberOfConversions[$i] > 0 && $lastThirtyDaysImpressions[$i] > 0) {
        $conversionRate = ((int)$numberOfConversions[$i] / $lastThirtyDaysImpressions[$i])*100;
        $conversionRate =  round( $conversionRate, 1, PHP_ROUND_HALF_UP);
        } else{
            $conversionRate = 0;
        }
        echo "'".$conversionRate."',";} ?>];
    lastSevenDataConversionRate_<?php echo $uniqID; ?>.reverse();


    var lineChartData_<?php echo $uniqID; ?> = {
        labels: lastSevenDates_<?php echo $uniqID; ?>,
        datasets: [{
            label: "Conversions",
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1.5,
            backgroundColor: 'rgba(54, 162, 235, 0.4)',
            fill: true,
            data:lastSevenData_<?php echo $uniqID; ?>,
            yAxisID: "y-axis-1",
        }, {
            label: "Impressions",
            borderColor: '#ee2c47',
            borderWidth: 1.5,
            backgroundColor: 'rgba(232, 81, 101, 0.4)',
            fill: true,
            data: lastSevenDataImpressions_<?php echo $uniqID; ?>,
            yAxisID: "y-axis-2"
        }]
    };
        var ctx_<?php echo $uniqID; ?> = document.getElementById("sevenDayConversionImpressions_<?php echo $uniqID; ?>").getContext("2d");
        var chartOne_<?php echo $uniqID; ?> = Chart.Line(ctx_<?php echo $uniqID; ?>, {
            type: 'line',
            data: lineChartData_<?php echo $uniqID; ?>,
            options: {
                responsive: true,
                hoverMode: 'index',
                stacked: false,
                title: {
                    display: true,
                    text: 'Last <?php echo "$dateRange"; ?> Days Conversions & Impressions'
                },
                scales: {
                    yAxes: [{
                        type: "linear", 
                        display: true,
                        position: "left",
                        id: "y-axis-1",
                    }, {
                        type: "linear", 
                        display: true,
                        position: "right",
                        id: "y-axis-2",
                        gridLines: {
                            drawOnChartArea: false,
                        },
                    }],
                }
            }
        });


        var ctxtwo_<?php echo $uniqID; ?> = document.getElementById("sevenDayConversionRate_<?php echo $uniqID; ?>").getContext('2d');
        var chartTwo_<?php echo $uniqID; ?> = new Chart(ctxtwo_<?php echo $uniqID; ?>, {
            type: 'line',
            data: {
                labels: lastSevenDates_<?php echo $uniqID; ?>,
                datasets: [{
                    label: 'Conversion Rate %',
                    data: lastSevenDataConversionRate_<?php echo $uniqID; ?>,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    </script>
    <?php
    }

    $rendderredAnalytics = ob_get_contents();
    ob_end_clean();

    return $rendderredAnalytics;

}


function popb_send_user_feedback(){

	if ( ! check_ajax_referer() ) {
		die( 'security error' );
	}

	$other_input = '';

	$installDateTime  =  gmdate("Y-m-d". " -|- "."h:i:sa", get_option('plugOps_activation_date')) ."Install Date";
	$deActivationTime =  date("Y-m-d"). " -|- ".date("h:i:sa") ."Deactiv Date  : ";

	if (isset($_POST['other_input']) ) {
		$other_input = sanitize_text_field( $_POST['other_input']);
	}
	$postedFeedbackReason = sanitize_text_field( $_POST['reason'] ) . "  :  ". $other_input."\n". $installDateTime ."\n".$deActivationTime."\n";

	if (isset($_POST['followUpEmail'])) {
		$followUpEmail  = sanitize_text_field($_POST['followUpEmail']);
		$postedFeedbackReason = $postedFeedbackReason."\n".$followUpEmail;
	}

	$feedbackType = 'PPB Uninstall FeedBack';

	$emailContents = $postedFeedbackReason;
	if ( isset( $_POST['support'] ) ) {
		$postedFeedbackSupportEmail = sanitize_email( $_POST['support']['email'] );
		$postedFeedbackSupportTitle = sanitize_text_field( $_POST['support']['title'] );
		$postedFeedbackSupportMessage = sanitize_textarea_field( $_POST['support']['text'] );

		$emailContents = "Support Email : $postedFeedbackSupportEmail \n". 
						 "Support Title : $postedFeedbackSupportTitle \n".
						 "Support Message : $postedFeedbackSupportMessage \n";

		$feedbackType = 'Support Request';
	}

			$fromEmailAddress = home_url();
			$fromEmailAddress = trim($fromEmailAddress, '/');

			if (!preg_match('#^http(s)?://#', $fromEmailAddress)) {
			    $fromEmailAddress = 'http://' . $fromEmailAddress;
			}

			$urlParts = parse_url($fromEmailAddress);

			$OnlyDomain = preg_replace('/^www\./', '', $urlParts['host']);

			$fromEmailAddress =  "pluginopsfeedbackform@".$OnlyDomain;

			
			$headers[]= "From: "."PPB Feedback Form"." <".$fromEmailAddress.">";


	$sendFeedback =  wp_mail( 'feedback@pluginops.com', $feedbackType, $emailContents, $headers);

	
	if ($sendFeedback  == true) {
		echo "success";
	}
	exit();

}



function ulpb_aweber_connect(){

	$POPB_data_Nonce = $_REQUEST['POPB_nonce'];
	if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
		die( 'Security check' ); 
	}else{
		$authCode = sanitize_text_field( $_POST['aweberAuthCode'] );

		update_option( 'ulpb_aweber_auth_code', $authCode );

		$returnArray = array();

		try{
			$authCode = get_option( 'ulpb_aweber_auth_code' );

			require(ULPB_PLUGIN_PATH.'/integrations/aweber_api/aweber_api.php');
			list( $auth_key, $auth_token, $req_key, $req_token, $oauth ) = explode( '|', $authCode );

			$aweber = new AWeberAPI( $auth_key, $auth_token );

			$aweber->user->tokenSecret = $req_token;
			$aweber->user->requestToken = $req_key;
			$aweber->user->verifier = $oauth;
		    list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();   //to get access token & token secret.
		    update_option( 'ulpb_aweber_accessToken', $accessToken );
		    update_option( 'ulpb_aweber_accessTokenSecret', $accessTokenSecret );

		    $account = $aweber->getAccount(get_option( 'ulpb_aweber_accessToken'), get_option( 'ulpb_aweber_accessTokenSecret') );
		    
		    $returnArray['allLists'] = '';
		    foreach ($account->lists as $list) {
		    	$returnArray['allLists'] = $returnArray['allLists'].'<option value="'.$list->url.'">'.$list->name.'</option>';
			}

			$returnArray['allLists'] = ' '.$returnArray['allLists'].' ';
			$returnArray['queryMessage'] = 'success';

			echo json_encode($returnArray);

		}catch(exception $e){
			$returnArray['queryMessage'] = $e;
	    	echo json_encode($returnArray);
		}


		exit();


	}

}


function ulpb_aweber_connection_check(){

	$POPB_data_Nonce = $_REQUEST['POPB_nonce'];
	if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
		die( 'Security check' );
	}else{

		$returnArray = array();

		try{
			$authCode = get_option( 'ulpb_aweber_auth_code' );
			require(ULPB_PLUGIN_PATH.'/integrations/aweber_api/aweber_api.php');
			list( $auth_key, $auth_token, $req_key, $req_token, $oauth ) = explode( '|', $authCode );

			$aweber = new AWeberAPI( $auth_key, $auth_token );

			$aweber->user->tokenSecret = $req_token;
			$aweber->user->requestToken = $req_key;
			$aweber->user->verifier = $oauth;

		    $account = $aweber->getAccount(get_option( 'ulpb_aweber_accessToken'), get_option( 'ulpb_aweber_accessTokenSecret') );
		    
		    $returnArray['allLists'] = '<option value="">Select List</option>';
		    foreach ($account->lists as $list) {
		    	$returnArray['allLists'] = $returnArray['allLists'].'<option value="'.$list->url.'">'.$list->name.'</option>';
			}

			$returnArray['allLists'] = ' '.$returnArray['allLists'].' ';
			$returnArray['queryMessage'] = 'success';

			echo json_encode($returnArray);

		}catch(exception $e){
			$returnArray['queryMessage'] = $e;
	    	echo json_encode($returnArray);
		}

		exit();


	}

}




function get_sample_permalink_for_landingpages() {

	$POPB_data_Nonce = $_REQUEST['POPB_nonce'];
	if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
		die( 'Security check' );
	}else{
		$postID = sanitize_text_field( $_POST['post_id'] );
		$newSlug = sanitize_text_field( $_POST['new_slug'] );
		$post = get_post( $postID );
		$globalPostUniquenessCheck = wp_unique_post_slug($newSlug, $post->ID, $post->post_status, 'page', $post->post_parent);

		echo $globalPostUniquenessCheck;
		exit();
	}
	
}

function popb_update_data_collection_option() {

	$POPB_data_Nonce = $_REQUEST['submitNonce'];
	if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
		die( 'Security check' );
	}else{
		
		$clickAction = sanitize_text_field( $_REQUEST['request_click_action'] );

		if ($clickAction == true || $clickAction == 'true') {
			update_option( 'pluginops_tracking_consent', true );
		}

		if ($clickAction == false || $clickAction == 'false') {
			update_option( 'pluginops_tracking_consent', 'false' );
		}

		echo 'true';
		exit();
	}
	
}


function ulpb_getConstantContactLists(){

	$POPB_data_Nonce = $_REQUEST['POPB_nonce'];
	if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
		die( 'Security check' );
	}else{
		$savedAccessToken = get_option( 'popb_constant_contact_access_token', false );

		$savedRefreshToken = get_option( 'popb_constant_contact_refresh_token', false );

		$savedOAuthToken = sanitize_text_field( $_REQUEST['ccToken'] );

		$savedAccessToken = false;


		if ($savedOAuthToken != '' && $savedOAuthToken != false ) {
			$savedOAuthTokenOption = get_option( 'popb_constant_contact_o_auth_token', false );
			if ($savedOAuthToken != $savedOAuthTokenOption) {
				$savedRefreshToken = false;
			}
	 		update_option( $option = 'popb_constant_contact_o_auth_token', $savedOAuthToken, null );
	 	}else{
	 		$savedOAuthToken = get_option( 'popb_constant_contact_o_auth_token', false );
	 	}


	 	include ULPB_PLUGIN_PATH.'/integrations/constantContact/api-class.php';

		$constantContactSync = new ConstantContact_Popb_sync();


		$constant_contactlists = $constantContactSync->getConstantContactLists($savedOAuthToken,$savedAccessToken, $savedRefreshToken);

		
		if ($constant_contactlists->error_key) {
			$retListsValue['error'] = $constant_contactlists->error_message;
		echo json_encode($retListsValue);
			exit();	
			return;
		}

		$AllLists = $constant_contactlists->lists;


		$returnLists = array();
		foreach ($AllLists as $key => $value) {
			$returnLists[$value->list_id] = $value->name;
		}

		$retListsValue['success'] = $returnLists;
		
		echo json_encode($retListsValue);

		exit();
	}

}


function popb_enable_safe_mode(){

	$POPB_data_Nonce = $_REQUEST['POPB_nonce'];
	if ( ! wp_verify_nonce( $POPB_data_Nonce, 'POPB_data_nonce' ) ) {
		die( 'Security check' );
	}else{

		$landingPageSafeModeFeature = get_option( 'landingPageSafeModeFeature', false );

		$landingPageSafeModeFeature = sanitize_text_field( $landingPageSafeModeFeature );

		if ($landingPageSafeModeFeature == 'enabled') {
			update_option( 'landingPageSafeModeFeature', 'disabled' );
		}else{
			update_option( 'landingPageSafeModeFeature', 'enabled' );
		}


		$other_input = '';

		$installDateTime  =  gmdate("Y-m-d". " -|- "."h:i:sa", get_option('plugOps_activation_date')) ." - Install Date";

		if (!isset($_POST['errorLine'])) {
			$_POST['errorLine'] = ' ';
		}
		
		$errorMessage = sanitize_text_field( $_POST['errorMsg'] );
		$errorURL = sanitize_text_field( $_POST['errorURL'] );
		$errorLine = sanitize_text_field( $_POST['errorLine'] );

		$postedFeedbackReason = 

			"
			$installDateTime \n
			----------------------------------- \n
			Error Message : $errorMessage  \n  
			----------------------------------- \n
			Error File URL : $errorURL  \n 
			----------------------------------- \n
			Error Line : $errorLine  \n 
			----------------------------------- \n
			SafeMode : $landingPageSafeModeFeature  \n 
			----------------------------------- \n
			"
		;

		$feedbackType = 'PPB Error Report';

		$emailContents = $postedFeedbackReason;
		

		$fromEmailAddress = home_url();
		$fromEmailAddress = trim($fromEmailAddress, '/');

		if (!preg_match('#^http(s)?://#', $fromEmailAddress)) {
		    $fromEmailAddress = 'http://' . $fromEmailAddress;
		}

		$urlParts = parse_url($fromEmailAddress);

		$OnlyDomain = preg_replace('/^www\./', '', $urlParts['host']);

		$fromEmailAddress =  "pluginopserrorform@".$OnlyDomain;

		
		$headers[]= "From: "."PPB Error Form"." <".$fromEmailAddress.">";


		$sendFeedback =  wp_mail( 'error.reports@pluginops.com', $feedbackType, $emailContents, $headers);


		if ($sendFeedback  == true) {
			echo "success";
		}
		exit();





	}


}





}