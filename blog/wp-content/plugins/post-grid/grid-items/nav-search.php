<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

//var_dump($grid_type);


			echo '<pre>'.var_export($_GET, true).'</pre>';



			$html.= '<form action="" method="GET">';
			
			$html.= '<div class="item">';
			$html.= 'Keyword: <input class="" type="text" name="keyword"  placeholder="" value="">';				
						
			$html.= '</div>';				
			
			$html.= '<div class="item">';
			$html.= 'Order: <select name="order" >
							<option value="ASC">ASC</option>
							<option value="DESC">DESC</option>				
							
							</select>';				
					
			$html.= '</div>';			
			
			
			$html.= '<div class="item">';
			$html.= 'Orderby: <select name="orderby" >
							<option value="none">none</option>			
							<option value="ID">ID</option>
							<option value="author">author</option>
							<option value="title">title</option>							
							<option value="name">name</option>							
							<option value="date">date</option>											
							<option value="modified">modified</option>	
							<option value="parent">parent</option>							
							<option value="rand">rand</option>							
							<option value="comment_count">comment count</option>
							<option value="meta_value">meta_value</option>							
							<option value="meta_value_num">meta_value_num</option>	
							
							
							
																										
							</select>';				
					
			$html.= '</div>';			
			
			
			
			
			
			$html.= wp_nonce_field( 'nonce_layout_content' );
			$html.= '<input class="button button-primary" type="submit" name="submit" value="'.__('Search', 'post-grid').'" />';
			$html.= '</form>';				
			
			//$html.= '<span class="submit-search">'.__('Submit', 'post-grid').'</span>';
