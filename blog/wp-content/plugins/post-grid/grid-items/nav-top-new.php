<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

//var_dump($grid_type);

	if($grid_type=='grid'){
		
		if($nav_top_search=='yes'){
			
			if(isset($_GET['keyword'])){
				
				$keyword = sanitize_text_field($_GET['keyword']);
				}
			
			$html.= '<div class="nav-search">';
			$html.= '<input grid_id="'.$post_id.'" class="search" type="text"  placeholder="'.__('Start typing...', 'post-grid').'" value="'.$keyword.'">';
			//$html.= '<span class="submit-search">'.__('Submit', 'post-grid').'</span>';
			$html.= '</div>';			
			
			}
		

			//include post_grid_plugin_dir.'/grid-items/nav-search.php';	

		
		
		}
	elseif($grid_type=='filterable'){
		
		
		$html.= '<div class="nav-filter">';
		
		
		if(!empty($categories)){

			foreach($categories as $category){
				
				$tax_cat = explode(',',$category);
				
				$categories_info[] = array($tax_cat[1],$tax_cat[0]);
				
				}
			
			$html.= '<div class="filter filter-'.$post_id.'" data-filter="all">'.$filterable_filter_all_text.'</div>';
		
			foreach($categories_info as $term_info)
				{
					
					$term = get_term( $term_info[0], $term_info[1] );
					$term_slug = $term->slug;
					$term_name = $term->name;
					$html .= '<div class="filter filter-'.$post_id.'" terms-id="'.$term_info[0].'" data-filter=".'.$term_slug.'" >'.$term_name.'</div>';
				}
			
		}
		
		$html.= '</div>';
		
		
	
		$html .= '<script>
			jQuery(document).ready(function($) {
				
					$(function(){
					
						$("#post-grid-'.$post_id.'").mixItUp({

				pagination: {
					limit: '.$filterable_post_per_page.',
					prevButtonHTML: "'.$pagination_prev_text.'",
					nextButtonHTML: "'.$pagination_next_text.'",

					

				},
				

				
				selectors: {
					pagersWrapper: ".pager-list-'.$post_id.'",
					filter: ".filter-'.$post_id.'",
					
				},';
				
		if(!empty($active_filter) && $active_filter!= 'all')
			{
			

			$html .= '
			load: {
				
					filter: document.location.hash == "" ? ".'.$active_filter.'" :
					  ("." + document.location.hash.substring(1))
				
			}, ';

			}

				$html .= 'controls: {
					enable: true
				}
				
						});
					
					});
					
			
					
					
			});		
		</script>';	

		
		
		$html .= '<style type="text/css">
		
				#post-grid-'.$post_id.' .grid-items .mix{
				  display: none;
				}
				</style>
				';
		
		
		}
	elseif($grid_type=='slider'){
		
	
	//var_dump('Hello');
		
		
	$html.= '<script>
	jQuery(document).ready(function($)
	{
		$("#post-grid-'.$post_id.' .grid-items").owlCarousel({
			
			items : 3, //10 items above 1000px browser width


			responsiveClass:true,
			responsive:{

				320:{
					items:'.$slider_column_mobile.',
					
				},
				
				768:{
					items:'.$slider_column_tablet.',
					
				},				
				
				1024:{
					items:'.$slider_column_desktop.',
					
					
				}
			},



			
			';
		

		$html.= 'loop: '.$slider_loop.',';
		$html.= 'rewind: '.$slider_rewind.',';
		$html.= 'center: '.$slider_center.',';
		
		$html.= 'autoplay: '.$slider_auto_play.',';
		$html.= 'autoplayTimeout: 3000,';
		$html.= 'autoplayHoverPause: '.$slider_autoplayHoverPause.',';	
		
		$html.= 'nav: '.$slider_navs.',';
		$html.= 'navText : ["",""],';
		
		$html.= 'dots: '.$slider_dots.',';
		$html.= 'navSpeed: '.$slider_navSpeed.',';
		$html.= 'dotsSpeed: '.$slider_dotsSpeed.',';
		$html.= 'touchDrag : '.$slider_touchDrag.',';
		$html.= 'mouseDrag  : '.$slider_mouseDrag.',';
		
		$html.= 'autoHeight: true,';







	$html.= '});';

	$html.= '$("#post-grid-'.$post_id.' .owl-nav").addClass("'.$slider_navs_positon.'");';
	$html.= '$("#post-grid-'.$post_id.' .owl-nav").addClass("'.$slider_navs_style.'");';
	$html.= '$("#post-grid-'.$post_id.' .owl-dots").addClass("'.$slider_dots_style.'");';
	


	$html.= '});';


	$html.=  '</script>';
		
		
		
		
		
		}











	if($nav_top_filter=='yes'){
		

		
		
		
	/*	
		
			$html .= '<script>
				jQuery(document).ready(function($) {

// init Isotope
var $grid = $(".grid-items").isotope({
	layoutMode: "masonry",
	masonry: { 
		isFitWidth: true 
	  },
	filter: ".'.$active_filter.'" 
  
  });


// filter items on button click
$(".nav-filter").on( "click", ".filter", function() {
	
	var filterValue = $(this).attr("data-filter");
	$grid.isotope({ filter: filterValue });
});			

				});		
			</script>';	
		
*/

//var_dump($active_filter);

		
		
		
		}
		
		



