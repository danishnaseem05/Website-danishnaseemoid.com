<?php

if (!defined('ABSPATH')){
    exit;
}

function wpc_file_actions(){
	global $error, $l;

	$return = array();

	$action = wpc_optREQ('request');

	if(empty($action)){
		$return['error'] = $l['no_req_post'];
		echo json_encode($return);
		die();
	}

	if($action == 'put'){
		$filename = urldecode(wpc_optREQ('filename'));
		$putdata = base64_decode(wpc_optREQ('putdata'));

		$func_response = wpc_put($filename, $putdata);

		if($func_response){
			$return['done'] = 'done';
		}else{
			$return['error'] = $l['err_exec'];
		}

		echo json_encode($return);
		die();
	}

	$str_args = urldecode(wpc_optREQ('args'));
	$args = explode(',', $str_args);

	if(function_exists('wpc_'.$action)){
		if(!empty($args)){
			if(count($args) > 1){
				$func_response = call_user_func_array('wpc_'.$action, $args);
			}else{
				$func_response = call_user_func('wpc_'.$action, $str_args);
			}
		}else{
			$func_response = call_user_func('wpc_'.$action);
		}
		$return['func_response'] = $func_response;

		if($func_response){
			$return['done'] = $l['done'];
		}else{
			$return['error'] = $l['err_exec'];
		}

	}else{
		$return['error'] = $l['func_not_found'];
	}

	echo json_encode($return);

}
