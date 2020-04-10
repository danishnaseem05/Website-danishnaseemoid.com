<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="pbp_form" style="background: #fff; padding:20px 5px 20px 5px; width: 99%;">
    <label> <h2>Contents </h2> </label>
    <br><br>
    <?php 
    	$settings = array('media_buttons'=> true,'wltc','tinymce' => true, 'editor_height' => 425);
    	wp_editor(" ","wltc",$settings); 
    ?>
</div>