<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
		
if($lazy_load_enable=='yes'){

    if( $grid_type=='filterable'){
        }
    else{
        ?>
        <div id="post-grid-lazy-<?php echo $grid_id; ?>" class="post-grid-lazy"><img src="<?php echo $lazy_load_image_src; ?>"/></div>
        <script>
            jQuery('#post-grid-lazy-<?php echo $grid_id; ?>').ready(function($){
                jQuery('#post-grid-lazy-<?php echo $grid_id; ?>').fadeOut();
                jQuery('#post-grid-<?php echo $grid_id; ?>').fadeIn();
            })
        </script>
        <style type="text/css">#post-grid-<?php echo $grid_id; ?>{display: none;}</style>
        <?php
        }
}