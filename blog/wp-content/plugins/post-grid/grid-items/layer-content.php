<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

	$class_post_grid_functions = new class_post_grid_functions();
	
	$post_grid_layout_content = get_option( 'post_grid_layout_content' );
	
	if(empty($post_grid_layout_content)){
		$layout = $class_post_grid_functions->layout_content($layout_content);
		}
	else{
		
		if(!empty($post_grid_layout_content[$layout_content])){
			$layout = $post_grid_layout_content[$layout_content];
			
			}
		else{
			$layout = array();
			}
		}
		
	
	//var_dump(get_the_ID());


	$html.='<div class="layer-content">';
	
	$active_plugins = get_option('active_plugins');

	foreach($layout as $item_id=>$item_info){
		
		$item_key = isset($item_info['key']) ? $item_info['key'] : '';
		//$layout_items_html = $class_post_grid_functions->layout_items_html($item_id, $item_info );

		
		
		//var_dump($item_info);

		
		$item_key = $item_info['key'];
		
		if(!empty($item_info['char_limit'])){
			$char_limit = $item_info['char_limit'];	
			}
		else{
			
			$char_limit = 10;
			
			}
			

		if(!empty($item_info['taxonomy'])){
			$taxonomy = $item_info['taxonomy'];	
			}
		else{
			$taxonomy = '';
			}

		if(!empty($item_info['taxonomy_term_count'])){
			$taxonomy_term_count = $item_info['taxonomy_term_count'];	
			}
		else{
			$taxonomy_term_count = '';
			}
			
		//var_dump($taxonomy_term_count);



		if(!empty($item_info['five_star_count'])){
			$five_star_count = $item_info['five_star_count'];	
			}
		else{
			$five_star_count = 0;
			}			
			
		if(!empty($item_info['field_id'])){
			$field_id = $item_info['field_id'];	
			}
		else{
			$field_id = '';
			}			
			
			//var_dump($field_id);
			
			
			
			
			
			
			
			
			
		if(!empty($item_info['link_target'])){
			$link_target = $item_info['link_target'];	
			}			
		else{
			$link_target = '';
			}	
		
			if(!empty($item_info['read_more_text'])){
				$read_more_text = $item_info['read_more_text'];	
				
				//var_dump($read_more_text);
				}
			else{

				$read_more_text = apply_filters('post_grid_filter_grid_item_read_more', __('Read more.', 'post-grid'));
				
				
				}		
		
		
		
		
		$item['title'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.apply_filters('post_grid_filter_grid_item_title',wp_trim_words(get_the_title($item_post_id), $char_limit,'')).'</div>';
		

		//$item['title'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.get_the_ID().'</div>';	
		
		$item['title_link'] = '<a target="'.$link_target.'" class="element element_'.$item_id.' '.$item_key.'" href="'.get_permalink($item_post_id).'">'.apply_filters('post_grid_filter_grid_item_title',wp_trim_words(get_the_title($item_post_id), $char_limit,'')).'</a>';	

		$post_content = apply_filters('the_content', get_the_content($item_post_id));
		
		$item['content'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.apply_filters('post_grid_filter_grid_item_content', $post_content).'</div>';
		
		
		$excerpt_removed_shortcode = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', strip_shortcodes(get_the_excerpt($item_post_id)));

		$item['excerpt'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.apply_filters('post_grid_filter_grid_item_excerpt',wp_trim_words($excerpt_removed_shortcode, $char_limit,'')).'</div>';
		

			
		
		

		$read_more_text = $read_more_text;

		
		$item['excerpt_read_more'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.wp_trim_words(strip_shortcodes(get_the_excerpt($item_post_id)), $char_limit,'').' <a target="'.$link_target.'" class="read-more" href="'.get_permalink($item_post_id).'">'.$read_more_text.'</a></div>';
		
		
		
		
		
		
		
		
		
		
		$item['read_more'] = '<a target="'.$link_target.'" class="element element_'.$item_id.' '.$item_key.'"  href="'.get_permalink($item_post_id).'">'.$read_more_text.'</a>';
		
		
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($item_post_id), 'full' );
		
		if(!empty($thumb['0'])){
			$thumb_url = $thumb['0'];
			
			}
		else{
			$thumb_url = post_grid_plugin_url.'assets/frontend/css/images/placeholder.png';
			}
		
		$item['thumb'] = '<div class="element element_'.$item_id.' '.$item_key.'"  ><img src="'.$thumb_url.'" /></div>';		
		
		
		
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($item_post_id), 'full' );

		
		if(!empty($thumb['0'])){
			$thumb_url = $thumb['0'];

			}
		else{
			$thumb_url = post_grid_plugin_url.'assets/frontend/css/images/placeholder.png';
			}

		
		
		$item['thumb_link'] = '<div class="element element_'.$item_id.' '.$item_key.'"  ><a href="'.get_permalink($item_post_id).'"><img src="'.$thumb_url.'" /></a></div>';
		
		
		$item['post_date'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.apply_filters('post_grid_filter_grid_item_post_date', get_the_date()).'</div>';
		
		

		$item['author'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.apply_filters('post_grid_filter_grid_item_author', get_the_author()).'</div>';		
		

		$item['author_link'] = '<a class="element element_'.$item_id.' '.$item_key.'" href="'.get_author_posts_url(get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' )).'">'.get_the_author().'</a>';		
		
				$html_categories = '';
				$categories = get_the_category();
				$separator = ' ';
				$output = '';
				if ( ! empty( $categories ) ) {
					foreach( $categories as $category ) {
						$html_categories .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'post-grid' ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
						
	
					}
					$html_categories.= trim( $output, $separator );
				}
		
		
		$item['categories'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.$html_categories.'</div>';		
		
		
		$html_tags = '';

		$posttags = get_the_tags();
		if ($posttags) {
		  foreach($posttags as $tag){
			$html_tags.= '<a href="#">'.$tag->name . '</a> ';
			}
		}
		
		$item['tags'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.$html_tags.'</div>';		
		
		
		if(in_array( 'rating-widget/rating-widget.php', (array) $active_plugins )){

			$item['rating_widget'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.do_shortcode('[ratingwidget post_id="'.$item_post_id.'"]').'</div>';
			}
		else{
			$item['rating_widget'] = '<div class="element element_'.$item_id.' '.$item_key.'"  >'.__('Please activate Rating widget Plugin','post-grid').'</div>';
			}
				
		
		
		

		
		
	

					
					
					
			$comments_html = '';
			$comments_html.= '<h3 class="comment-content ">'.__('Comments', 'post-grid').'</h3>';
			
			
			$comments_count =  wp_count_comments($item_post_id);
			$total_comments = $comments_count->approved;
			
			//var_dump($item_post_id);
	
			
			if($total_comments <= 0)
				{
	
					$comments_html.= '<div class="comment no-comment">';
					$comments_html.= '<p class="comment-content ">'.__('No comments yet', 'post-grid').'</p>';
					
					$comments_html.= '</div>';
					
				}
			else
				{
	
					
					$comments = get_comments(array(
						'post_id' => $item_post_id,
						'status' => 'approve',
						'number' => 5,				
						'order' => 'ASC',
					));
			
	
	
	
					if(empty($comments))
						{
	
							$comments_html.= '<div class="comment no-more-comment">';
							$comments_html.= '<p class="comment-content ">'.__('No more comments', 'post-grid').'';
							$comments_html.= '</p>';							
							$comments_html.= '</div>';
	
						}
					else
						{
							
							$comments_html.= '<div id="comments" class="comments-area">';							
							$comments_html.= '<ol class="commentlist">';
							
							foreach($comments as $comment) :
							
							
								$comment_ID = $comment->comment_ID;
								$comment_author = $comment->comment_author;							
								$comment_author_email = $comment->comment_author_email;
								$comment_content = $comment->comment_content;						
								$comment_date = $comment->comment_date;						
							
							
							
							
								$comments_html.= '<li class="comment">';
								$comments_html.= '<article id="" class="comment">';	
								$comments_html.= '<header class="comment-meta comment-author vcard">';
								
								$comments_html.= get_avatar($comment_author_email, 50);	
								
								$comments_html.= '<cite><b class="fn">'.$comment_author.'</b></cite>';								
								$comments_html.= '<time >'.$comment_date.'</time>';								
																									
								$comments_html.= '</header>';								
								$comments_html.= '<section class="comment-content comment">';
								$comments_html.= '<p>'.$comment_content.'</p>';									
								$comments_html.= '</section>';															

								$comments_html.= '</article>';								
													
								$comments_html.= '</li>';
								
							endforeach;
							
							$comments_html.= '</ol>';
							$comments_html.= '</div>';							
							
						}
	
	
	
				
				}

		

		$item['comments'] = '<div class="element element_'.$item_id.' '.$item_key.'">'.$comments_html.'</div>';					
		
		
		
		
		

			
				$comments_number = get_comments_number( $item_post_id );
				
				$comments_count_html = '';
				
				if(comments_open()){
					
					
					if ( $comments_number == 0 ) {
							$comments_count_html.= __('No Comments','post-grid');
						} elseif ( $comments_number > 1 ) {
							$comments_count_html.= $comments_number . __(' Comments', 'post-grid');
						} else {
							$comments_count_html.= __('1 Comment', 'post-grid');
						}
		
						$item['comments_count'] = '<div class="element element_'.$item_id.' '.$item_key.'">'.$comments_count_html.'</div>';	
		
					}
				else{
					
					$item['comments_count'] = '';
					}
		
		
	
		
		//$item = apply_filters('post_grid_filter_layout_items_html', $item_id, $item, $item_info);
		
		$html.= isset($item[$item_key]) ? $item[$item_key] : '';
		
		
		
		
		
			
			
			
			
			
			
			
			//$html.= $layout_items_html;
			
			//var_dump($item_post_id);

		}
	
	$html.='</div>'; // .layer-content