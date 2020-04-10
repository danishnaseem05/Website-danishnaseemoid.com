<?php

if (!defined('ABSPATH')){
    exit;
}

function wpcentral_page_settings($title = 'wpCentral Settings'){
	global $l, $error, $msg;
	
	if(!empty($error)){
		echo '<div id="message" class="error"><p>'.$error.'</p></div>';
	}
	
	if(!empty($msg)){
		echo '<div id="message" class="updated"><p>'.$msg.'</p></div>';
	}
	echo '<div style="margin: 10px 20px 0 2px;">	
			<div class="metabox-holder columns-2">
			<div class="postbox-container">	
			<div class="wrap">
				<h1><!--This is to fix promo--></h1>
				<table cellpadding="2" cellspacing="1" width="100%" class="fixed" border="0">
					<tr>
						<td valign="top"><h1>'.$title.'</h1>
						</td>
						<td align="right" width="40"><a target="_blank" href="https://twitter.com/the_wpcentral"><img src="'.plugins_url('', __FILE__).'/images/twitter.png" /></a></td>
						<td align="right" width="40"><a target="_blank" href="https://www.facebook.com/wordpresscentral"><img src="'.plugins_url('', __FILE__).'/images/facebook.png" /></a></td>
					</tr>
				</table>
				<hr/>
				<br/>
				<!--Main Table-->
				<table cellpadding="8" cellspacing="1" width="100%">
					<form action="" method="post">
						<tr>
							<td valign="top"><b>Allowed IP\'s</b></td>
							<td>
								<input type="text" name="allowed_ips" value="'.implode(',' , wpc_get_allowed_ips()).'">
								<span>Please enter comma separated IP address(s) of panels which will make API calls</span>
								<br/>
								</br/>
								<input type="submit" name="save_ips" class="button button-primary action" value="Save Settings" >
							</td>
						<tr/>
					</form>
				</table>
					<br/>
					<hr/>
					<br/>
				<table cellpadding="8" cellspacing="1" width="100%">
					<form action="" method="post">
						<tr>
							<td valign="top"><b/>wpCentral Connection Key:</b></td>
							<td>
								<b/>'.wpc_get_connection_key().'</b> 
								<br/><br/>
								<button type="submit" class="button button-primary action" name="reset_connectionkey" value="resetconnectionkey" style="line-height:1.154;"><span class="dashicons-before dashicons-image-rotate"></span><span style="margin-left:4px">Reset</span></button>
								<br/>
							</td>
						<tr/>
					</form>
				</table>
			
				<br/>
				<br/>
				<div style="width:45%;background:#FFF;padding:15px; margin:auto">
					<b>Let your followers know that you use Pagelayer to build your website :</b>
					<form method="get" action="https://twitter.com/intent/tweet" id="tweet" onsubmit="return dotweet(this);">
						<textarea name="text" cols="45" row="3" style="resize:none;">I easily manage my #WordPress #site using @the_wpcentral</textarea>
						&nbsp; &nbsp; <input type="submit" value="Tweet!" class="button button-primary" onsubmit="return false;" id="twitter-btn" style="margin-top:20px;"/>
					</form>
			
				</div>
				<br />
			<script>
				function dotweet(ele){
					window.open(jQuery("#"+ele.id).attr("action")+"?"+jQuery("#"+ele.id).serialize(), "_blank", "scrollbars=no, menubar=no, height=400, width=500, resizable=yes, toolbar=no, status=no");
					return false;
				}
			</script>
			<hr />
			<a href="'.WPCENTRAL_WWW_URL.'" target="_blank">wpCentral</a> v'.WPCENTRAL_VERSION.' You can report any bugs <a href="https://wordpress.org/plugins/wp-central/" target="_blank">here</a>.
		</div>
		</div>
		</div>
	</div>';
}

if(isset($_POST['save_ips'])){
	global $l, $error, $msg;
	
	$allowed_ips = wpc_optPOST('allowed_ips');
	if(empty($allowed_ips)){
		$error = $l['empty_allowed_ips'];
	}else{
		update_option('wpcentral_allowed_ips', explode(',', $allowed_ips));
		$msg = $l['successfully_added_ips'];
	}
}

if(isset($_POST['reset_connectionkey'])){
	global $l, $error, $msg;
	
	$reset_conn_key = wpc_reset_conn_key();
	
	if(!empty($reset_conn_key)){
		$msg = "Successfully changed connection key";
	}else{
		$msg = "Failed to change connection key";
	}
		
}

wpcentral_page_settings();
