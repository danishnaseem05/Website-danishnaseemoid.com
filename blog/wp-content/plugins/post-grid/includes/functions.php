<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access






function post_grid_intermediate_image_sizes(){


    $get_intermediate_image_sizes =  get_intermediate_image_sizes();
    $get_intermediate_image_sizes = array_merge($get_intermediate_image_sizes,array('full'));

    $all_sizes = array();

    foreach($get_intermediate_image_sizes as $size_key){

        $size_key_title = str_replace('_', ' ',$size_key);
        $size_key_title = str_replace('-', ' ',$size_key_title);
        $all_sizes[$size_key] = ucfirst($size_key_title);
    }

    return $all_sizes;


}




function post_grid_filter_layout_items_html($item_id, $item, $item_info){

        
        $item_key = $item_info['key'];

        $item['dummy'] = '<div class="element element_'.$item_id.' '.$item_key.'"></div>';    
        
        return $item;
    
    }

add_filter('post_grid_filter_layout_items_html','post_grid_filter_layout_items_html', 10, 3);





function post_grid_add_shortcode_column( $columns ) {
    return array_merge( $columns, 
        array( 'shortcode' => __( 'Shortcode', 'post-grid' ) ) );
}
add_filter( 'manage_post_grid_posts_columns' , 'post_grid_add_shortcode_column' );


function post_grid_posts_shortcode_display( $column, $post_id ) {
    if ($column == 'shortcode'){
		?>
        <input style="background:#bfefff" type="text" onClick="this.select();" value="[post_grid <?php echo 'id=&quot;'.$post_id.'&quot;';?>]" /><br />
      <textarea cols="50" rows="1" style="background:#bfefff" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[post_grid id='; echo "'".$post_id."']"; echo '"); ?>'; ?></textarea>
        <?php		
		
    }
}
add_action( 'manage_post_grid_posts_custom_column' , 'post_grid_posts_shortcode_display', 10, 2 );







function post_grid_get_media($item_post_id, $media_source, $featured_img_size, $thumb_linked){

    $item_post_permalink = apply_filters('post_grid_item_post_permalink', get_permalink($item_post_id));

    $post_grid_post_settings = get_post_meta($item_post_id, 'post_grid_post_settings');
    $item_thumb_placeholder = apply_filters('post_grid_item_thumb_placeholder', post_grid_plugin_url.'assets/frontend/css/images/placeholder.png');

    $custom_thumb_source = isset($post_grid_post_settings[0]['custom_thumb_source']) ? $post_grid_post_settings[0]['custom_thumb_source'] : $item_thumb_placeholder;
    $thumb_custom_url = isset($post_grid_post_settings[0]['thumb_custom_url']) ? $post_grid_post_settings[0]['thumb_custom_url'] : '';
    $font_awesome_icon = isset($post_grid_post_settings[0]['font_awesome_icon']) ? $post_grid_post_settings[0]['font_awesome_icon'] : '';
    $font_awesome_icon_color = isset($post_grid_post_settings[0]['font_awesome_icon_color']) ? $post_grid_post_settings[0]['font_awesome_icon_color'] : '#737272';
    $font_awesome_icon_size = isset($post_grid_post_settings[0]['font_awesome_icon_size']) ? $post_grid_post_settings[0]['font_awesome_icon_size'] : '50px';
    $custom_youtube_id = isset($post_grid_post_settings[0]['custom_youtube_id']) ? $post_grid_post_settings[0]['custom_youtube_id'] : '';
    $custom_vimeo_id = isset($post_grid_post_settings[0]['custom_vimeo_id']) ? $post_grid_post_settings[0]['custom_vimeo_id'] : '';
    $custom_dailymotion_id = isset($post_grid_post_settings[0]['custom_dailymotion_id']) ? $post_grid_post_settings[0]['custom_dailymotion_id'] : '';
    $custom_mp3_url = isset($post_grid_post_settings[0]['custom_mp3_url']) ? $post_grid_post_settings[0]['custom_mp3_url'] : '';
    $custom_soundcloud_id = isset($post_grid_post_settings[0]['custom_soundcloud_id']) ? $post_grid_post_settings[0]['custom_soundcloud_id'] : '';

		//echo '<pre>'.var_export($post_grid_post_settings).'</pre>';
		
    $html_thumb = '';
		

    if($media_source == 'featured_image'){
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($item_post_id), $featured_img_size );
        $alt_text = get_post_meta(get_post_thumbnail_id($item_post_id), '_wp_attachment_image_alt', true);
        $thumb_url = $thumb['0'];

        if(!empty($thumb_url)){
            if($thumb_linked=='yes'){
                if(!empty($thumb_custom_url)){
                    $html_thumb.= '<a href="'.$thumb_custom_url.'"><img alt="'.$alt_text.'" src="'.$thumb_url.'" /></a>';
                    }
                else{
                    $html_thumb.= '<a href="'.$item_post_permalink.'"><img alt="'.$alt_text.'" src="'.$thumb_url.'" /></a>';
                    }
            }
            else{
                $html_thumb.= '<img alt="'.$alt_text.'" src="'.$thumb_url.'" />';
            }
        }
        else{
            $html_thumb.= '';
        }
    }
    elseif($media_source == 'empty_thumb'){

        if($thumb_linked=='yes'){
            $html_thumb.= '<a class="custom" href="'.$item_post_permalink.'"><img src="'.post_grid_plugin_url.'assets/frontend/css/images/placeholder.png" /></a>';
        }
        else{
            $html_thumb.= '<img class="custom" src="'.post_grid_plugin_url.'assets/frontend/css/images/placeholder.png" />';
        }
    }
    elseif($media_source == 'custom_thumb'){
        if(!empty($custom_thumb_source)){
            if($thumb_linked=='yes'){
                $html_thumb.= '<a href="'.$item_post_permalink.'"><img src="'.$custom_thumb_source.'" /></a>';
            }
            else{
                $html_thumb.= '<img src="'.$custom_thumb_source.'" />';
            }
        }
    }
    elseif($media_source == 'font_awesome'){
        if(!empty($custom_thumb_source)){
            if($thumb_linked=='yes'){
                $html_thumb.= '<a href="'.$item_post_permalink.'"><i style="color:'.$font_awesome_icon_color.';font-size:'.$font_awesome_icon_size.'" class="fa '.$font_awesome_icon.'"></i></a>';
            }
            else{
                $html_thumb.= '<i style="color:'.$font_awesome_icon_color.';font-size:'.$font_awesome_icon_size.'" class="fa '.$font_awesome_icon.'"></i>';
            }
        }
    }
    elseif($media_source == 'first_image'){
        //global $post, $posts;
        $post = get_post($item_post_id);
        $post_content = $post->post_content;
        $first_img = '';
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post_content, $matches);

        if(!empty($matches[1][0]))
        $first_img = $matches[1][0];

        if(empty($first_img)) {
            $html_thumb.= '';
            }
        else{

            if($thumb_linked=='yes'){
                $html_thumb.= '<a href="'.$item_post_permalink.'"><img src="'.$first_img.'" /></a>';
            }
            else{
                $html_thumb.= '<img src="'.$first_img.'" />';
            }
        }
    }
    elseif($media_source == 'first_gallery'){

        $gallery = get_post_gallery( $item_post_id, false );

        if(!empty($gallery)){
        $html_thumb.= '<div class="gallery owl-carousel">';

        if(!empty($gallery['ids'])){
            $ids = $gallery['ids'];
            $ids = explode(',',$ids);


        }
        else{
            $ids = array();
        }


        foreach($ids as $id ){

            $src = wp_get_attachment_url( $id);
            $alt_text = get_post_meta($id, '_wp_attachment_image_alt', true);
            $html_thumb .= '<img src="'.$src.'" class="gallery-item" alt="'.$alt_text.'" />';
        }

        $html_thumb.= '</div>';
        }
    }
    elseif($media_source == 'first_youtube'){
        $post = get_post($item_post_id);
        $post_type = $post->post_type;

        if($post_type=='page'){
            $content = '';
            $html_thumb.= '';
        }
        else{
            $content = do_shortcode( $post->post_content );
        }

        $content = apply_filters('the_content', $content);
        $embeds = get_media_embedded_in_content( $content );


        foreach($embeds as $key=>$embed){

            if(strchr($embed,'youtube')){
                $embed_youtube = $embed;
                }
        }

        if(!empty($embed_youtube) ){
            $html_thumb.= $embed_youtube;
            }
        else{
            $html_thumb.= '';
            }

        }

    elseif($media_source == 'first_vimeo'){

        $post = get_post($item_post_id);
        $post_type = $post->post_type;
        //var_dump($post_type);

        if($post_type=='page'){
            $content = '';
            $html_thumb.= '';
            }
        else{

            $content = do_shortcode( $post->post_content );
            }
        $embeds = get_media_embedded_in_content( $content );

        foreach($embeds as $key=>$embed){

            if(strchr($embed,'vimeo')){

                $embed_youtube = $embed;
                }

            }

        if(!empty($embed_youtube) ){
            $html_thumb.= $embed_youtube;
            }
        else{
            $html_thumb.= '';
            }


    }
    elseif($media_source == 'first_dailymotion'){

        $post = get_post($item_post_id);
        $post_type = $post->post_type;
        //var_dump($post_type);

        if($post_type=='page'){
            $content = '';
            $html_thumb.= '';
            }
        else{

            $content = do_shortcode( $post->post_content );
            }

        $content = apply_filters('the_content', $content);
        $embeds = get_media_embedded_in_content( $content );

        foreach($embeds as $key=>$embed){

            if(strchr($embed,'dailymotion')){

                $embed_youtube = $embed;
                }

            }

        if(!empty($embed_youtube) ){
            $html_thumb.= $embed_youtube;
            }
        else{
            $html_thumb.= '';
            }

        }




    elseif($media_source == 'first_mp3'){

        $post = get_post($item_post_id);
        $post_type = $post->post_type;
        //var_dump($post_type);

        if($post_type=='page'){
            $content = '';
            $html_thumb.= '';
            }
        else{

            $content = do_shortcode( $post->post_content );
            }

        $content = apply_filters('the_content', $content);
        $embeds = get_media_embedded_in_content( $content );

        foreach($embeds as $key=>$embed){

            if(strchr($embed,'mp3')){

                $embed_youtube = $embed;
                }

            }

        if(!empty($embed_youtube) ){
            $html_thumb.= $embed_youtube;
            }
        else{
            $html_thumb.= '';
            }

        }

    elseif($media_source == 'first_soundcloud'){

        $post = get_post($item_post_id);
        $post_type = $post->post_type;
        //var_dump($post_type);

        if($post_type=='page'){
            $content = '';
            $html_thumb.= '';
            }
        else{

            $content = do_shortcode( $post->post_content );
            }

        $content = apply_filters('the_content', $content);
        $embeds = get_media_embedded_in_content( $content );

        foreach($embeds as $key=>$embed){

            if(strchr($embed,'soundcloud')){

                $embed_youtube = $embed;
                }

            }

        if(!empty($embed_youtube) ){
            $html_thumb.= $embed_youtube;
            }
        else{
            $html_thumb.= '';
            }

        }


    elseif($media_source == 'custom_youtube'){

            if(!empty($custom_youtube_id)){
                $html_thumb.= '<iframe frameborder="0" allowfullscreen="" src="http://www.youtube.com/embed/'.$custom_youtube_id.'?feature=oembed"></iframe>';

                }


        }



    elseif($media_source == 'custom_vimeo'){

            if(!empty($custom_vimeo_id)){
                $html_thumb.= '<iframe frameborder="0" allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen="" src="https://player.vimeo.com/video/'.$custom_vimeo_id.'"></iframe>';

                }


        }


    elseif($media_source == 'custom_dailymotion'){

            if(!empty($custom_dailymotion_id)){
                $html_thumb.= '<iframe frameborder="0" allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen="" src="//www.dailymotion.com/embed/video/'.$custom_dailymotion_id.'"></iframe>';

                }


        }



    elseif($media_source == 'custom_mp3'){

            if(!empty($custom_mp3_url)){
                $html_thumb.= do_shortcode('[audio src="'.$custom_mp3_url.'"]');

                }

        }



    elseif($media_source == 'custom_video'){

//var_dump($post_grid_post_settings);

        $video_html = '';


        if(!empty($post_grid_post_settings[0]['custom_video_MP4'])):

            $video_html .= 'mp4="'.$post_grid_post_settings[0]['custom_video_MP4'].'"';

        elseif (!empty($post_grid_post_settings[0]['custom_video_WEBM'])):

            $video_html .= 'webm="'.$post_grid_post_settings[0]['custom_video_WEBM'].'"';

        elseif (!empty($post_grid_post_settings[0]['custom_video_OGV'])):

            $video_html .= 'ogv="'.$post_grid_post_settings[0]['custom_video_OGV'].'"';

        endif;

            $html_thumb.= do_shortcode('[video '.$video_html.'][/video]');



    }


    elseif($media_source == 'custom_soundcloud'){

            if(!empty($custom_soundcloud_id)){
                $html_thumb.= '<iframe width="100%" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$custom_soundcloud_id.'&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>';

                }

        }




    return $html_thumb;


	
	
	}


















function post_grid_term_slug_list($post_id){
	
	
	$term_slug_list = '';
	
	$post_taxonomies = get_post_taxonomies($post_id);
	
	foreach($post_taxonomies as $taxonomy){
		
		$term_list[] = wp_get_post_terms(get_the_ID(), $taxonomy, array("fields" => "all"));
		
		}

	if(!empty($term_list)){
		foreach($term_list as $term_key=>$term) 
			{
				foreach($term as $term_id=>$term){
					$term_slug_list .= $term->slug.' ';
					}
			}
		
		}


	return $term_slug_list;

	}






function post_grid_meta_query_args($meta_query){

	foreach($meta_query as $key=>$meta_info){

		?>
		<div class="item">
			<div class="header">
            <span class="remove"><i class="fa fa-times"></i></span>
			<span class="move " title="<?php echo __('Move', 'post-grid'); ?>"><i class="fas fa-bars"></i></span>
            <span class="expand-collapse " title="<?php echo __('Expand or collapse', 'post-grid'); ?>">
                <i class="fas fa-expand"></i>
                <i class="fa fa-collapse"></i>
            </span>

			<?php echo $key; ?>

            </div>
			<div class="options">

			<?php echo __('Key', 'post-grid'); ?><br />
			<input type="text" name="post_grid_meta_options[meta_query][<?php echo $key; ?>][key]" value="<?php echo $meta_info['key']; ?>" /><br>
			<?php echo __('Value', 'post-grid'); ?><br />
			<input type="text" name="post_grid_meta_options[meta_query][<?php echo $key; ?>][value]" value="<?php echo $meta_info['value']; ?>" /><br>
			<?php echo __('Compare', 'post-grid'); ?><br />
			<input type="text" name="post_grid_meta_options[meta_query][<?php echo $key; ?>][compare]" value="<?php echo $meta_info['compare']; ?>" /><br>
			<?php echo __('Type', 'post-grid'); ?><br />
			<input type="text" name="post_grid_meta_options[meta_query][<?php echo $key; ?>][type]" value="<?php echo $meta_info['type']; ?>" /><br>

			</div>
		</div>
		<?php

		}


	}






function post_grid_posttypes($post_types){

	$html = '';
	$html .= '<select post_id="'.get_the_ID().'" class="post_types select2" multiple="multiple" size="6" name="post_grid_meta_options[post_types][]">';
	
		$post_types_all = get_post_types( '', 'names' ); 
		foreach ( $post_types_all as $post_type ) {

			global $wp_post_types;
			$obj = $wp_post_types[$post_type];
			
			if(in_array($post_type,$post_types)){
				$selected = 'selected';
				}
			else{
				$selected = '';
				}

			$html .= '<option '.$selected.' value="'.$post_type.'" >'.$obj->labels->singular_name.'</option>';
		}
		
	$html .= '</select>';
	return $html;
	}









function post_grid_layout_content_ajax(){
	
	if(current_user_can('manage_options')){
		
		
		$layout_key = sanitize_text_field($_POST['layout']);
		
		$class_post_grid_functions = new class_post_grid_functions();
		$post_grid_layout_content = get_option( 'post_grid_layout_content' );
		
		if(empty($post_grid_layout_content)){
				$layout = $class_post_grid_functions->layout_content($layout_key);
			}
		else{
				$layout = $post_grid_layout_content[$layout_key];
			
			}
		
		//$layout = $class_post_grid_functions->layout_content($layout_key);
		
		
	
		?>
		<div class="<?php echo $layout_key; ?>">
		<?php
		
			foreach($layout as $item_key=>$item_info){
				$item_key = $item_info['key'];
				?>
				
	
					<div class="item <?php echo $item_key; ?>" style=" <?php echo $item_info['css']; ?> ">
					
					<?php
					
					if($item_key=='thumb'){
						
						?>
						<img src="<?php echo post_grid_plugin_url; ?>assets/admin/images/thumb.png" />
						<?php
						}
						
					elseif($item_key=='title'){
						
						?>
						Lorem Ipsum is simply
						
						<?php
						}								
						
					elseif($item_key=='excerpt'){
						
						?>
						Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text
						<?php
						}	
						
					elseif($item_key=='excerpt_read_more'){
						
						?>
						Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text <a href="#">Read more</a>
						<?php
						}					
						
					elseif($item_key=='read_more'){
						
						?>
						<a href="#">Read more</a>
						<?php
						}												
						
					elseif($item_key=='post_date'){
						
						?>
						18/06/2015
						<?php
						}	
						
					elseif($item_key=='author'){
						
						?>
						PickPlugins
						<?php
						}					
						
					elseif($item_key=='categories'){
						
						?>
						<a hidden="#">Category 1</a> <a hidden="#">Category 2</a>
						<?php
						}
						
					elseif($item_key=='tags'){
						
						?>
						<a hidden="#">Tags 1</a> <a hidden="#">Tags 2</a>
						<?php
						}	
						
					elseif($item_key=='comments_count'){
						
						?>
						3 Comments
						<?php
						}
						
						// WooCommerce
					elseif($item_key=='wc_full_price'){
						
						?>
						<del>$45</del> - <ins>$40</ins>
						<?php
						}											
					elseif($item_key=='wc_sale_price'){
						
						?>
						$45
						<?php
						}					
										
					elseif($item_key=='wc_regular_price'){
						
						?>
						$45
						<?php
						}	
						
					elseif($item_key=='wc_add_to_cart'){
						
						?>
						Add to Cart
						<?php
						}	
						
					elseif($item_key=='wc_rating_star'){
						
						?>
						*****
						<?php
						}					
											
					elseif($item_key=='wc_rating_text'){
						
						?>
						2 Reviews
						<?php
						}	
					elseif($item_key=='wc_categories'){
						
						?>
						<a hidden="#">Category 1</a> <a hidden="#">Category 2</a>
						<?php
						}					
						
					elseif($item_key=='wc_tags'){
						
						?>
						<a hidden="#">Tags 1</a> <a hidden="#">Tags 2</a>
						<?php
						}
						
					elseif($item_key=='edd_price'){
						
						?>
						$45
						<?php
						}					
																											
						
					else{
						
						echo $item_info['name'];
						
						}
					
					?>
					
					
					
					</div>
					<?php
				}
		
		?>
		</div>
		<?php
		
		}
	
	

	
	die();
	
	}
	
add_action('wp_ajax_post_grid_layout_content_ajax', 'post_grid_layout_content_ajax');








function post_grid_layout_add_elements(){
	
	if(current_user_can('manage_options')){
		
		
		$item_key = sanitize_text_field($_POST['item_key']);
        $item_group = sanitize_text_field($_POST['item_group']);
		$layout = sanitize_text_field($_POST['layout']);	
		$unique_id = sanitize_text_field($_POST['unique_id']);	
	
		$class_post_grid_functions = new class_post_grid_functions();
        $layout_items_group = $class_post_grid_functions->layout_items();

//
//        foreach($layout_items_group as $group_key=>$group_data) {
//
//            $group_name = $group_data['name'];
//            $group_items = $group_data['items'];
//
//            $layout_items_list = array();
//
//            foreach($group_items as $element_key=>$item_info){
//
//
//                $layout_items_list[$element_key]['name'] = $item_info['name'];
//                $layout_items_list[$element_key]['dummy_html'] = $item_info['dummy_html'];
//                $layout_items_list[$element_key]['css'] = $item_info['css'];
//
//            }
//        }
//
//
//        update_option('hellO_option', $layout_items_list);


		$item_name = $layout_items_group[$item_group]['items'][$item_key]['name'];
		$item_html = $layout_items_group[$item_group]['items'][$item_key]['dummy_html'];
		$item_css = $layout_items_group[$item_group]['items'][$item_key]['css'];
	
		$html = array();
		
		
		
		$html['item'] = '';
		$html['item'].= '<div class="item '.$item_key.'" id="item-'.$unique_id.'" >';
		$html['item'].= $item_html;
		$html['item'].= '</div>';
	
		$html['options'] = '';
		$html['options'].= '<div class="item" id="'.$unique_id.'">';
		$html['options'].= '<div class="header">
		<span class="remove " title="'.__('Remove', 'post-grid').'"><i class="fa fa-times"></i></span>
		<span class="move " title="'.__('Move', 'post-grid').'"><i class="fas fa-bars"></i></span>
		<span class="expand " title="'.__('Expand or collapse', 'post-grid').'">
			<i class="fas fa-expand"></i>
			<i class="fas fa-compress"></i>
		</span>
		<span class="name">'.$item_name.'</span>
		</div>';
		$html['options'].= '<div class="options">';


        $html['options'].= ''.__('Custom class:', 'post-grid').' <br /><input type="text" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][custom_class]" /><br /><br />';


        if($item_key=='meta_key'){
			
			$html['options'].= ''.__('Meta Key:', 'post-grid').' <br /><input type="text" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][field_id]" /><br /><br />';
			$html['options'].= ''.__('Wrapper:', 'post-grid').' <br />use %s where you want to repalce the meta value. Example<pre>&lt;div&gt;%s&lt;/div&gt;</pre> <br /><input type="text" value="%s" name="post_grid_layout_content['.$layout.']['.$unique_id.'][wrapper]" /><br /><br />';
			
			
			}
			
		if($item_key=='html'){
			
			$html['options'].= ''.__('Custom HTML:', 'post-grid').' <br /><input type="text" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][html]" /><br /><br />';
	
			}		
			
			
			
		if($item_key=='read_more' || $item_key=='excerpt_read_more'){
			
			$html['options'].= ''.__('Read more text:', 'post-grid').' <br /><input type="text" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][read_more_text]" /><br /><br />';
			}		
			
		if($item_key=='five_star'){
			
			$html['options'].= ''.__('Five star count:', 'post-grid').' <br /><input type="text" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][five_star_count]" /><br /><br />';
			}		
			
		if($item_key=='custom_taxonomy'){
			
			$html['options'].= ''.__('Taxonomy:', 'post-grid').' <br /><input type="text" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][taxonomy]" /><br /><br />';
			$html['options'].= ''.__('Term count:', 'post-grid').' <br /><input type="text" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][taxonomy_term_count]" /><br /><br />';
			}		
			
			
			
		if($item_key=='up_arrow' || $item_key=='down_arrow' ){
			
			$html['options'].= ''.__('Arrow size(px):', 'post-grid').' <br /><input type="text" placeholder="10px" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][arrow_size]" /><br /><br />';
			$html['options'].= ''.__('Background color:', 'post-grid').' <br /><input class="color" type="text" value="" name="post_grid_layout_content['.$layout.']['.$unique_id.'][arrow_bg_color]" /><br /><br />';
			}		
			
			
			
			
		if($item_key=='title'  || $item_key=='title_link'  || $item_key=='excerpt' || $item_key=='excerpt_read_more' ){
			
			$html['options'].= ''.__('Character limit:', 'post-grid').' <br /><input type="text" value="20" name="post_grid_layout_content['.$layout.']['.$unique_id.'][char_limit]" /><br /><br />';
			}
			
			
			
			
		if($item_key=='title_link' || $item_key=='read_more' || $item_key=='excerpt_read_more'  ){
			
			$html['options'].= ''.__('Link target:', 'post-grid').' <br />
			<select name="post_grid_layout_content['.$layout.']['.$unique_id.'][link_target]" >
			<option value="_blank">_blank</option>
			<option value="_parent">_parent</option>
			<option value="_self">_self</option>
			<option value="_top">_top</option>
			<option value="new">new</option>
			 </select><br /><br />';
			}		
			
			
			
			
			
			
			
			
	
		$html['options'].= '
		<input type="hidden" value="'.$item_key.'" name="post_grid_layout_content['.$layout.']['.$unique_id.'][key]" />
		<input type="hidden" value="'.$item_name.'" name="post_grid_layout_content['.$layout.']['.$unique_id.'][name]" />
		CSS: <br />
		<a target="_blank" href="http://www.pickplugins.com/demo/post-grid/sample-css-for-layout-editor/">Sample css</a><br />
		<textarea class="custom_css" item_id="'.$unique_id.'" name="post_grid_layout_content['.$layout.']['.$unique_id.'][css]"  style="width:100%" spellcheck="false" autocapitalize="off" autocorrect="off">'.$item_css.'</textarea><br /><br />
		
		CSS Hover: <br />
		<textarea class="custom_css" item_id="'.$item_key.'" name="post_grid_layout_content['.$layout.']['.$unique_id.'][css_hover]"  style="width:100%" spellcheck="false" autocapitalize="off" autocorrect="off"></textarea>';
		
		
		
		
		
		
		$html['options'].= '</div>';
		$html['options'].= '</div>';	
	
	
	
		echo json_encode($html);

		
		}
	
	die();
	
	}
	
add_action('wp_ajax_post_grid_layout_add_elements', 'post_grid_layout_add_elements');














function post_grid_ajax_search(){
		
    $html = '';
    $grid_id = sanitize_text_field($_POST['grid_id']);

    include post_grid_plugin_dir.'/templates/variables.php';

    $keyword = sanitize_text_field($_POST['keyword']);

    include post_grid_plugin_dir.'/templates/query.php';
    $odd_even = 0;
		if ( $post_grid_wp_query->have_posts() ) :
			while ( $post_grid_wp_query->have_posts() ) : $post_grid_wp_query->the_post();

		    ob_start();


                $item_post_id = get_the_ID();
                //var_dump($item_post_id);


                $post_grid_post_settings = get_post_meta( get_the_ID(), 'post_grid_post_settings', true );


                //var_dump($post_grid_post_settings);

                if($enable_multi_skin=='yes'){

                    if(!empty($post_grid_post_settings['post_skin'])){

                        $skin = $post_grid_post_settings['post_skin'];

                    }
                    else{

                        $skin = $skin_main;
                    }

                }

                if($odd_even%2==0){
                    $odd_even_calss = 'even';
                }
                else{
                    $odd_even_calss = 'odd';
                }
                $odd_even++;

                if($grid_type=='glossary'){
                    $glossary_str = get_the_title($item_post_id);
                    $glossary_cha = isset($glossary_str[0]) ? $glossary_str[0] : '';
                }


                $item_css_class = array();

                $item_css_class['item'] = 'item';
                $item_css_class['item_id'] = 'item-'.$item_post_id;

                $item_css_class['skin'] = 'skin '.$skin;
                $item_css_class['odd_even'] = $odd_even_calss;

                if($grid_type=='filterable' || $grid_type=='glossary'){
                    $item_css_class['mix'] = 'mix';
                    $item_css_class['post_term_slug'] = post_grid_term_slug_list($item_post_id);
                }


                if($grid_type=='glossary'){
                    $item_css_class['glossary'] = $glossary_cha;
                }


                $item_css_class = apply_filters('post_grid_item_classes', $item_css_class);
                $item_css_class = implode(' ', $item_css_class);




                ?><div class="<?php echo $item_css_class; ?>">

                <div class="layer-wrapper">
                    <?php
                    include post_grid_plugin_dir.'/templates/layer-media.php';
                    include post_grid_plugin_dir.'/templates/layer-content.php';

                    ?>
                </div>

                <?php

                if($grid_type == 'timeline'){
                    ?>
                    <span class="timeline-arrow">
                                    <i class="timeline-bubble"></i>
                                </span>
                    <?php
                }
                ?>




                </div><!-- End .item --><?php

                $post_grid_ads_loop_meta_options = get_post_meta($grid_id, 'post_grid_ads_loop_meta_options', true);

                if(!empty($post_grid_ads_loop_meta_options['ads_positions'])){

                    $ads_positions = $post_grid_ads_loop_meta_options['ads_positions'];
                    $ads_positions = explode(',',$ads_positions);

                    $ads_positions_html = $post_grid_ads_loop_meta_options['ads_positions_html'];

                    $post_grid_ads_positions = apply_filters('post_grid_filter_ads_positions', $ads_positions);

                    foreach($post_grid_ads_positions as $position){

                        if( $post_grid_wp_query->current_post == $position ){

                            if(!empty($ads_positions_html[$position]))
                                echo apply_filters('post_grid_nth_item_html',$ads_positions_html[$position]);

                        }
                    }

                }





                $html.= ob_get_clean();


	
			endwhile;
			wp_reset_query();
		else:
		
			$html.='<div class="item">';
			$html.=__('No post found', 'post-grid');  // .item
			$html.='</div>';  // .item	
				
		endif;
		
		echo $html;
		
		die();
		
	}

add_action('wp_ajax_post_grid_ajax_search', 'post_grid_ajax_search');
add_action('wp_ajax_nopriv_post_grid_ajax_search', 'post_grid_ajax_search');