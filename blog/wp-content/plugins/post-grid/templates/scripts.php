<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access


?>

<script>
    <?php
    if($masonry_enable=='yes' && ($grid_type != 'filterable' && $grid_type != 'glossary')):
        ?>
        jQuery('#post-grid-lazy-<?php echo $grid_id; ?>').ready(function($){
            var $container = $('#post-grid-<?php echo $grid_id; ?> .grid-items');
            $container.masonry({
                itemSelector: '.item',
                columnWidth: '.item', //as you wish , you can use numeric
                isAnimated: true,
                isFitWidth: true,
                horizontalOrder: true,
            });
            $container.imagesLoaded().done( function() {
                $container.masonry('layout');
            });
        })
        <?php
    endif;
    ?>
</script>