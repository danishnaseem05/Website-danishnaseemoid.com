<?php if ( ! defined( 'ABSPATH' ) ) exit; 

$landingPageLinkTrackingFeature = get_option( 'landingPageLinkTrackingFeature', false );

$landingPageLinkTrackingFeatureEnabled = '';
$landingPageLinkTrackingFeatureDisabled = '';

if ($landingPageLinkTrackingFeature != 'disabled' || $landingPageLinkTrackingFeature == false) {
	$landingPageLinkTrackingFeatureEnabled = 'selected';
}else{
	$landingPageLinkTrackingFeatureDisabled = 'selected';
}


$landingPageSafeModeFeature = get_option( 'landingPageSafeModeFeature', false );

$landingPageSafeModeFeatureEnabled = '';
$landingPageSafeModeFeatureDisabled = '';

if ($landingPageSafeModeFeature != 'disabled' || $landingPageSafeModeFeature == false) {
	$landingPageSafeModeFeatureEnabled = 'selected';
}else{
	$landingPageSafeModeFeatureDisabled = 'selected';
}

$popbLandingpageUrlKeyword = get_option( 'popbLandingpageUrlKeyword', false );
$perm_structure = get_option( 'permalink_structure' ); 

$popbLandingpageUrlKeywordCondition = false;
if ($perm_structure != "/%postname%/") {
	$popbLandingpageUrlKeywordCondition = true;
}


?>

<h2> PluginOps Settings </h2>

<div class="settings-page-wrapper">
	<div class="pbp_form">
		<form id="ulpb_settings_form">
			<div style="display: none;">
				<label>Set a Landing Page as Home Page (Front Page)</label>
				<select name="landingPageAsHomePage">
					<option value="none"> None </option>
					<?php
						$args = array(
					        'offset'           => 0,
					        'posts_per_page'   => 100,
					        'orderby'          => 'date',
					        'order'            => 'DESC',
					        'post_type'        => 'ulpb_post',
					        'post_status'      => 'publish',
					    );
					    
					    $ulpb_pages = get_posts( $args );
					    if (!empty($ulpb_pages)) {
	        				foreach ($ulpb_pages as $ulpb_single_post) {

	        					echo " <option value='". $ulpb_single_post->ID. "'> ". get_the_title($ulpb_single_post) ." </option> ";
	        				}
        				}
					?>
				</select>
			</div>
			<div style="display: block; margin-bottom: 15px; margin-top: 25px;">
				<?php
					if ($popbLandingpageUrlKeywordCondition == true) {
						?>
						<label for=""> <b> Change Landing Page URL "landingpage" keyword</b></label>
						<input type="text" name="popbLandingpageUrlKeyword" value="<?php echo  $popbLandingpageUrlKeyword; ?>" style="margin-left: 70px; ">
						<br><br><br>
						<?php
					}
				?>
				<label> <b> Landing Page Link Tracking (Analytics) </b> </label>
				<select name="landingPageLinkTrackingFeature" style="margin-left: 140px; ">
					<option value="enabled" <?php echo $landingPageLinkTrackingFeatureEnabled; ?> > Enable </option>
					<option value="disabled" <?php echo $landingPageLinkTrackingFeatureDisabled; ?> > Disable </option>
				</select>
				<br><br><br>
				<label> <b> Enable Safe Mode </b> </label>
				<select name="landingPageSafeModeFeature" style="margin-left: 260px; ">
					<option value="disabled" <?php echo $landingPageSafeModeFeatureDisabled; ?> > Disable </option>
					<option value="enabled" <?php echo $landingPageSafeModeFeatureEnabled; ?> > Enable </option>
				</select>
				<br><br><br>
				<label> <b> Set a Landing Page <br> as ComingSoon / Maintenance Mode </b> </label>
				<select name="landingPageAsComingSoonPage" style="margin-left: 145px; ">
					<option value="none"> None </option>
					<?php
						$args = array(
					        'offset'           => 0,
					        'posts_per_page'   => 200,
					        'orderby'          => 'date',
					        'order'            => 'DESC',
					        'post_type'        => 'ulpb_post',
					        'post_status'      => 'publish',
					    );
					    $landingPageAsComingSoonPage = get_option( 'landingPageAsComingSoonPage', false );
					    $ulpb_pages = get_posts( $args );
					    if (!empty($ulpb_pages)) {
	        				foreach ($ulpb_pages as $ulpb_single_post) {
	        					$ops1selected = '';
	        					if ($ulpb_single_post->ID == $landingPageAsComingSoonPage) {
	        						$ops1selected = 'selected';
	        					}
	        					echo " <option value='". $ulpb_single_post->ID. "' $ops1selected> ". get_the_title($ulpb_single_post) ." </option> ";
	        				}
        				}
					?>
				</select>
			</div>
			<div style="display: inline-block; width:20%;">
				<h4>Select Supported Post Types </h4>
				<br>
			</div>
			<div style="display: inline-block; width:45%;">
			<br>
			<?php
				$selectedPostTypes = get_option( 'page_builder_SupportedPostTypes' );
				if (!is_array($selectedPostTypes)) {
					$selectedPostTypes = array();
				}
				$isChecked = ' ';
				foreach ( get_post_types( '', 'names' ) as $post_type ) {
					if ($post_type == 'ulpb_post' || $post_type == 'ulpb_global_rows' || $post_type == 'attachment' || $post_type == 'revision' || $post_type == 'nav_menu_item' || $post_type == 'customize_changeset' || $post_type == 'custom_css') {
					}else{
						if (in_array($post_type, $selectedPostTypes)) {
							$isChecked = 'checked';
						}
						echo '<label>  <input type="checkbox" name="selectedPostTypes[]" value="'.$post_type.'"  '.$isChecked.' class="checkboxInput  '.$isChecked.'">  '.$post_type .'<br>';

						$isChecked = ' ';
					}
				}

				$plugOps_pageBuilder_settings_nonce = wp_create_nonce( 'POPB_settings_nonce' );
			?>
			</div>
			<br>
			<br>
			<br>
			<br>
		</form>
		<button id="ulpb_settings_form_submit"  style="margin-left: 20%;">Save Changes</button>

		<p id="response"></p>
	</div>
	
</div>

<script type="text/javascript">
	(function($){

    $('#ulpb_settings_form_submit').on('click',function(){
         
        $('#response').text('Processing'); 
         
        var form = $('#ulpb_settings_form');
        $.ajax({
            url: "<?php echo admin_url('admin-ajax.php?action=ulpb_settings_data&POPB_settings_page_nonce='.$plugOps_pageBuilder_settings_nonce ); ?>",
            method: 'post',
            data: form.serialize(),
            success: function(result){
                if (result == 'Success'){
                    $('#response').text(result);  
                }else {
                    $('#response').text(result);
                }
            }
        });
         
        return false;   
    });

})(jQuery);
</script>