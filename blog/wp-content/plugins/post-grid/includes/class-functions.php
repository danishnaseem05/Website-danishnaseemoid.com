<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access

class class_post_grid_functions{
	
	public function __construct(){
		
		
		}


	
	public function load_more_text(){
											
			$load_more_text = apply_filters('post_grid_filter_load_more_text', __('Load more', 'post-grid'));
								
			return $load_more_text;

		}	
	
	
	
	
	
	
	
	public function items_bg_color_values(){
		
						$color_values = array(	'#ff398a',
												'#f992fb',
												'#eaca93',
												'#8ed379',
												'#8b67a5',
												'#f6b8ad',
												'#73d4b4',
												'#00c5cd',
												'#ff8247',
												'#ff6a6a',
												'#00ced1',
												'#ff7256',
												'#777777',
												'#067668',												
												);
											
						$color_values = apply_filters('post_grid_filter_items_bg_color_values', $color_values);				
											
						return $color_values;
											
		
		}	
	
	
	
	
	
	public function media_source(){
		
						$media_source = array(

						    'featured_image' =>array('id'=>'featured_image','title'=>__('Featured Image', 'post-grid'),'checked'=>'yes'),
                            'first_image'=>array('id'=>'first_image','title'=>__('First images from content', 'post-grid'),'checked'=>'yes'),
                            'empty_thumb'=>array('id'=>'empty_thumb','title'=>__('Empty thumbnail', 'post-grid'),'checked'=>'yes'),


						);
											
						$media_source = apply_filters('post_grid_filter_media_source', $media_source);				
											
						return $media_source;
											
		
		}
	
	
	public function layout_items(){



        $layout_items['general'] = array(

            'name'=>'General',
            'description'=>'Default WordPress items for post.',
            'items'=>array(

                'title'=>array(
                    'name'=>'Title',
                    'dummy_html'=>'Lorem Ipsum is simply.',
                    'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: left;',
                    ),

                'title_link'=>array(
                                'name'=>'Title with Link',
                                'dummy_html'=>'<a href="#">Lorem Ipsum is simply</a>',
                                'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'content'=>array(
                                'name'=>'Content',
                                'dummy_html'=>'Lorem',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'read_more'=>array(
                                'name'=>'Read more',
                                'dummy_html'=>'<a href="#">Read more</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'thumb'=>array(
                                'name'=>'Thumbnail',
                                'dummy_html'=>'<img style="width:100%;" src="'.post_grid_plugin_url.'assets/admin/images/thumb.png" />',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'thumb_link'=>array(
                                'name'=>'Thumbnail with Link',
                                'dummy_html'=>'<a href="#"><img style="width:100%;" src="'.post_grid_plugin_url.'assets/admin/images/thumb.png" /></a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'excerpt'=>array(
                                'name'=>'Excerpt',
                                'dummy_html'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'excerpt_read_more'=>array(
                                'name'=>'Excerpt with Read more',
                                'dummy_html'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text <a href="#">Read more</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'post_date'=>array(
                                'name'=>'Post date',
                                'dummy_html'=>'18/06/2015',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'author'=>array(
                                'name'=>'Author',
                                'dummy_html'=>'PickPlugins',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'author_link'=>array(
                                'name'=>'Author with Link',
                                'dummy_html'=>'Lorem',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'categories'=>array(
                                'name'=>'Categories',
                                'dummy_html'=>'<a hidden="#">Category 1</a> <a hidden="#">Category 2</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'tags'=>array(
                                'name'=>'Tags',
                                'dummy_html'=>'<a hidden="#">Tags 1</a> <a hidden="#">Tags 2</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'comments_count'=>array(
                                'name'=>'Comments Count',
                                'dummy_html'=>'3 Comments',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'comments'=>array(
                                'name'=>'Comments',
                                'dummy_html'=>'Lorem',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'rating_widget'=>array(
                                'name'=>'Rating-Widget: Star Review System',
                                'dummy_html'=>'Lorem',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'share_button'=>array(
                    'name'=>'Share button',
                    'dummy_html'=>'<i class="fa fa-facebook-square"></i> <i class="fa fa-twitter-square"></i> <i class="fa fa-google-plus-square"></i>',
                    'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                ),

                'hr'=>array(
                    'name'=>'Horizontal line',
                    'dummy_html'=>'<hr />',
                    'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                ),

            ),

        );











        $layout_items['woocommerce'] = array(

            'name'=>'WooCommerce (In Pro)',
            'description'=>'WooCommerce items.',
            'items'=>array(


                'wc_gallery'=>array(
                                'name'=>'WC Gallery',
                                'dummy_html'=>'Lorem',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'wc_full_price'=>array(
                                'name'=>'WC Full Price',
                                'dummy_html'=>'<del>$45</del> - <ins>$40</ins>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'wc_sale_price'=>array(
                                'name'=>'WC Sale Price',
                                'dummy_html'=>'$45',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'wc_regular_price'=>array(
                                'name'=>'WC Regular Price',
                                'dummy_html'=>'$45',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'wc_add_to_cart'=>array(
                                'name'=>'WC Add to Cart',
                                'dummy_html'=>'Add to Cart',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'wc_rating_star'=>array(
                                'name'=>'WC Star Rating',
                                'dummy_html'=>'<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>',
                                'css'=>'display: block;font-size:20px;color:#facd32;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'wc_rating_text'=>array(
                                'name'=>'WC Text Rating',
                                'dummy_html'=>'2 Reviews',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'wc_categories'=>array(
                                'name'=>'WC Categories',
                                'dummy_html'=>'<a href="#">Category 1</a> <a href="#">Category 2</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'wc_tags'=>array(
                                'name'=>'WC tags',
                                'dummy_html'=>'<a href="#" >Tags 1</a> <a href="#">Tags 2</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'wc_sku'=>array(
                                'name'=>'WC SKU',
                                'dummy_html'=>'WC SKU',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),


            ),

        );

        $layout_items['edd'] = array(

            'name'=>'Easy Digital Downloads (In Pro)',
            'description'=>'Easy Digital Downloads items.',
            'items'=>array(

                'edd_price'=>array(
                                'name'=>'EDD Price',
                                'dummy_html'=>'$56',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'edd_variable_prices'=>array(
                                'name'=>'EDD Variable Prices',
                                'dummy_html'=>'EDD Variable Prices',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'edd_sales_stats'=>array(
                                'name'=>'EDD Sales Stats',
                                'dummy_html'=>'EDD Sales Stats',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'edd_earnings_stats'=>array(
                                'name'=>'EDD Earnings Stats',
                                'dummy_html'=>'EDD Earnings Stats',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'edd_add_to_cart'=>array(
                                'name'=>'EDD Add to Cart',
                                'dummy_html'=>'Add to Cart',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'edd_rating_star'=>array(
                                'name'=>'EDD Star Rating',
                                'dummy_html'=>'<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'edd_rating_text'=>array(
                                'name'=>'EDD Text Rating',
                                'dummy_html'=>'2 Reviews',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'edd_categories'=>array(
                                'name'=>'EDD Categories',
                                'dummy_html'=>'<a href="#">Category 1</a> <a href="#">Category 2</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'edd_tags'=>array(
                                'name'=>'EDD tags',
                                'dummy_html'=>'<a href="#" >Tags 1</a> <a href="#">Tags 2</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),

            )

        );






        $layout_items['WPeC'] = array(

            'name'=>'WP eCommerce (In Pro)',
            'description'=>'WP eCommerce items.',
            'items'=>array(


                'WPeC_old_price'=>array(
                                'name'=>'WPeC Old Price',
                                'dummy_html'=>'$45',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'WPeC_sale_price'=>array(
                                'name'=>'WPeC Sale Price',
                                'dummy_html'=>'$40',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'WPeC_add_to_cart'=>array(
                                'name'=>'WPeC Add to Cart',
                                'dummy_html'=>'Add to Cart',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'WPeC_rating_star'=>array(
                                'name'=>'WPeC Star Rating',
                                'dummy_html'=>'<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'WPeC_categories'=>array(
                                'name'=>'WPeC Categories',
                                'dummy_html'=>'<a href="#">Category 1</a> <a href="#">Category 2</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'WPeC_tags'=>array(
                                'name'=>'WPeC tags',
                                'dummy_html'=>'<a href="#" >Tags 1</a> <a href="#">Tags 2</a>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
            )

        );


        $layout_items['others'] = array(

            'name'=>'Others (In Pro)',
            'description'=>'Others items.',
            'items'=>array(


                'meta_key'=>array(
                                'name'=>'Meta Key',
                                'dummy_html'=>'Meta Key',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),

                //'zoom'=>'Zoom button',

                'five_star'=>array(
                                'name'=>'Five Star',
                                'dummy_html'=>'<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'up_arrow'=>array(
                                'name'=>'Up Arrow',
                                'dummy_html'=>'<i class="fa fa-caret-up"></i>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),
                'down_arrow'=>array(
                                'name'=>'Down Arrow',
                                'dummy_html'=>'<i class="fa fa-caret-down"></i>',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),

                'html'=>array(
                                'name'=>'HTML',
                                'dummy_html'=>'HTML <b>goes</b> here.',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),

                'yith_add_to_wishlist'=>array(
                                'name'=>'YITH - Add to Wishlist',
                                'dummy_html'=>'Add to Wishlist',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),

                'custom_taxonomy'=>array(
                                'name'=>'Custom taxonomy',
                                'dummy_html'=>'Custom taxonomy',
                                'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
                                ),

            )

        );







//
//        $layout_items = array(
//
//							/*Default Post Stuff*/
//							'title'=>array(
//											'name'=>'Title',
//											'dummy_html'=>'Lorem Ipsum is simply.',
//											'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//
//							'title_link'=>array(
//											'name'=>'Title with Link',
//											'dummy_html'=>'<a href="#">Lorem Ipsum is simply</a>',
//											'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'content'=>array(
//											'name'=>'Content',
//											'dummy_html'=>'Lorem',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'read_more'=>array(
//											'name'=>'Read more',
//											'dummy_html'=>'<a href="#">Read more</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'thumb'=>array(
//											'name'=>'Thumbnail',
//											'dummy_html'=>'<img style="width:100%;" src="'.post_grid_plugin_url.'assets/admin/images/thumb.png" />',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'thumb_link'=>array(
//											'name'=>'Thumbnail with Link',
//											'dummy_html'=>'<a href="#"><img style="width:100%;" src="'.post_grid_plugin_url.'assets/admin/images/thumb.png" /></a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'excerpt'=>array(
//											'name'=>'Excerpt',
//											'dummy_html'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'excerpt_read_more'=>array(
//											'name'=>'Excerpt with Read more',
//											'dummy_html'=>'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text <a href="#">Read more</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'post_date'=>array(
//											'name'=>'Post date',
//											'dummy_html'=>'18/06/2015',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'author'=>array(
//											'name'=>'Author',
//											'dummy_html'=>'PickPlugins',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'author_link'=>array(
//											'name'=>'Author with Link',
//											'dummy_html'=>'Lorem',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'categories'=>array(
//											'name'=>'Categories',
//											'dummy_html'=>'<a hidden="#">Category 1</a> <a hidden="#">Category 2</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'tags'=>array(
//											'name'=>'Tags',
//											'dummy_html'=>'<a hidden="#">Tags 1</a> <a hidden="#">Tags 2</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'comments_count'=>array(
//											'name'=>'Comments Count',
//											'dummy_html'=>'3 Comments',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'comments'=>array(
//											'name'=>'Comments',
//											'dummy_html'=>'Lorem',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'rating_widget'=>array(
//											'name'=>'Rating-Widget: Star Review System',
//											'dummy_html'=>'Lorem',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//
//
//
//							/* WooCommerce Stuff*/
//
//
//
//							'wc_gallery'=>array(
//											'name'=>'WC Gallery',
//											'dummy_html'=>'Lorem',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'wc_full_price'=>array(
//											'name'=>'WC Full Price',
//											'dummy_html'=>'<del>$45</del> - <ins>$40</ins>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'wc_sale_price'=>array(
//											'name'=>'WC Sale Price',
//											'dummy_html'=>'$45',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'wc_regular_price'=>array(
//											'name'=>'WC Regular Price',
//											'dummy_html'=>'$45',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'wc_add_to_cart'=>array(
//											'name'=>'WC Add to Cart',
//											'dummy_html'=>'Add to Cart',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'wc_rating_star'=>array(
//											'name'=>'WC Star Rating',
//											'dummy_html'=>'<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>',
//											'css'=>'display: block;font-size:20px;color:#facd32;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'wc_rating_text'=>array(
//											'name'=>'WC Text Rating',
//											'dummy_html'=>'2 Reviews',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'wc_categories'=>array(
//											'name'=>'WC Categories',
//											'dummy_html'=>'<a href="#">Category 1</a> <a href="#">Category 2</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'wc_tags'=>array(
//											'name'=>'WC tags',
//											'dummy_html'=>'<a href="#" >Tags 1</a> <a href="#">Tags 2</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'wc_sku'=>array(
//											'name'=>'WC SKU',
//											'dummy_html'=>'WC SKU',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//
//
//							/* Easy Digital Downloads Stuff*/
//							//'edd_gallery'=>'EDD Gallery',

//							'edd_price'=>array(
//											'name'=>'EDD Price',
//											'dummy_html'=>'$56',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'edd_variable_prices'=>array(
//											'name'=>'EDD Variable Prices',
//											'dummy_html'=>'EDD Variable Prices',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'edd_sales_stats'=>array(
//											'name'=>'EDD Sales Stats',
//											'dummy_html'=>'EDD Sales Stats',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'edd_earnings_stats'=>array(
//											'name'=>'EDD Earnings Stats',
//											'dummy_html'=>'EDD Earnings Stats',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'edd_add_to_cart'=>array(
//											'name'=>'EDD Add to Cart',
//											'dummy_html'=>'Add to Cart',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'edd_rating_star'=>array(
//											'name'=>'EDD Star Rating',
//											'dummy_html'=>'<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'edd_rating_text'=>array(
//											'name'=>'EDD Text Rating',
//											'dummy_html'=>'2 Reviews',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'edd_categories'=>array(
//											'name'=>'EDD Categories',
//											'dummy_html'=>'<a href="#">Category 1</a> <a href="#">Category 2</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'edd_tags'=>array(
//											'name'=>'EDD tags',
//											'dummy_html'=>'<a href="#" >Tags 1</a> <a href="#">Tags 2</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							//'edd_sku'=>'EDD SKU',
//
//
//							/* WP eCommerce Stuff*/
//							//'WPeC_gallery'=>'WPeC Gallery',
//							//'WPeC_full_price'=>'WPeC Full Price',

//							'WPeC_old_price'=>array(
//											'name'=>'WPeC Old Price',
//											'dummy_html'=>'$45',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'WPeC_sale_price'=>array(
//											'name'=>'WPeC Sale Price',
//											'dummy_html'=>'$40',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'WPeC_add_to_cart'=>array(
//											'name'=>'WPeC Add to Cart',
//											'dummy_html'=>'Add to Cart',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'WPeC_rating_star'=>array(
//											'name'=>'WPeC Star Rating',
//											'dummy_html'=>'<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//						//	'WPeC_rating_text'=>'WPeC Text Rating',
//							'WPeC_categories'=>array(
//											'name'=>'WPeC Categories',
//											'dummy_html'=>'<a href="#">Category 1</a> <a href="#">Category 2</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'WPeC_tags'=>array(
//											'name'=>'WPeC tags',
//											'dummy_html'=>'<a href="#" >Tags 1</a> <a href="#">Tags 2</a>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							//'WPeC_sku'=>'WPeC SKU',
//
//
//
//
//
//
//
//							'meta_key'=>array(
//											'name'=>'Meta Key',
//											'dummy_html'=>'Meta Key',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//
//							//'zoom'=>'Zoom button',
//							'share_button'=>array(
//											'name'=>'Share button',
//											'dummy_html'=>'<i class="fa fa-facebook-square"></i> <i class="fa fa-twitter-square"></i> <i class="fa fa-google-plus-square"></i>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'five_star'=>array(
//											'name'=>'Five Star',
//											'dummy_html'=>'<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'up_arrow'=>array(
//											'name'=>'Up Arrow',
//											'dummy_html'=>'<i class="fa fa-caret-up"></i>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'down_arrow'=>array(
//											'name'=>'Down Arrow',
//											'dummy_html'=>'<i class="fa fa-caret-down"></i>',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'hr'=>array(
//											'name'=>'Horizontal line',
//											'dummy_html'=>'<hr />',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//							'html'=>array(
//											'name'=>'HTML',
//											'dummy_html'=>'HTML <b>goes</b> here.',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//
//							'yith_add_to_wishlist'=>array(
//											'name'=>'YITH - Add to Wishlist',
//											'dummy_html'=>'Add to Wishlist',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//
//							'custom_taxonomy'=>array(
//											'name'=>'Custom taxonomy',
//											'dummy_html'=>'Custom taxonomy',
//											'css'=>'display: block;font-size: 13px;line-height: normal;padding: 5px 10px;text-align: left;',
//											),
//
//
//
//
//
//							);
		
		$layout_items = apply_filters('post_grid_filter_layout_items', $layout_items);
		
		return $layout_items;
		}
	
	
	public function layout_content_list(){
		
		$layout_content_list = array(
		
						'flat'=>array(
								'0'=>array('key'=>'title_link', 'char_limit'=>'20', 'name'=>'Title with linked', 'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: left; text-decoration: none;', 'css_hover'=>'', ),
								'1'=>array('key'=>'excerpt', 'char_limit'=>'20', 'name'=>'Excerpt', 'css'=>'display: block;font-size: 14px;padding: 5px 10px;text-align: left;', 'css_hover'=>''),
								'2'=>array('key'=>'read_more', 'name'=>'Read more', 'css'=>'display: block;font-size: 12px;font-weight: bold;padding: 0 10px;text-align: left;text-decoration: none;', 'css_hover'=>''),

                            ),
									
						'flat-center'=>array(												
								'0'=>array('key'=>'title_link', 'char_limit'=>'20', 'name'=>'Title with linked', 'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: center;text-decoration: none;', 'css_hover'=>''),
								'1'=>array('key'=>'excerpt', 'char_limit'=>'20', 'name'=>'Excerpt', 'css'=>'display: block;font-size: 14px;padding: 5px 10px;text-align: center;', 'css_hover'=>''),
								'2'=>array('key'=>'read_more', 'name'=>'Read more', 'css'=>'display: block;font-size: 12px;font-weight: bold;padding: 0 10px;text-align: center;', 'css_hover'=>''),

									),
									
						'flat-right'=>array(												
								'0'=>array('key'=>'title_link', 'char_limit'=>'20', 'name'=>'Title with linked', 'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: right;text-decoration: none;', 'css_hover'=>''),
								'1'=>array('key'=>'excerpt', 'char_limit'=>'20', 'name'=>'Excerpt', 'css'=>'display: block;font-size: 14px;padding: 5px 10px;text-align: right;', 'css_hover'=>''),
								'2'=>array('key'=>'read_more', 'name'=>'Read more', 'css'=>'display: block;font-size: 12px;font-weight: bold;padding: 0 10px;text-align: right;', 'css_hover'=>''),					
									),
									
						'flat-left'=>array(												
								'0'=>array('key'=>'title_link', 'char_limit'=>'20', 'name'=>'Title with linked', 'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: left;text-decoration: none;', 'css_hover'=>''),
								
								'1'=>array('key'=>'excerpt', 'char_limit'=>'20', 'name'=>'Excerpt', 'css'=>'display: block;font-size: 14px;padding: 5px 10px;text-align: left;', 'css_hover'=>''),
								'2'=>array('key'=>'read_more', 'name'=>'Read more', 'css'=>'display: block;font-size: 12px;font-weight: bold;padding: 0 10px;text-align: left;', 'css_hover'=>'')
									),
									
						'wc-center-price'=>array(													
								'0'=>array('key'=>'title_link', 'char_limit'=>'20', 'name'=>'Title with linked', 'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: center;text-decoration: none;', 'css_hover'=>''),
								'1'=>array('key'=>'wc_full_price', 'name'=>'Price', 'css'=>'background:#f9b013;color:#fff;display: inline-block;font-size: 20px;line-height:normal;padding: 0 17px;text-align: center;', 'css_hover'=>''),
								'2'=>array('key'=>'excerpt', 'char_limit'=>'20', 'name'=>'Excerpt', 'css'=>'display: block;font-size: 14px;padding: 5px 10px;text-align: center;', 'css_hover'=>''),
									),								
									
						'wc-center-cart'=>array(													
								'0'=>array('key'=>'title_link', 'char_limit'=>'20', 'name'=>'Title with linked', 'css'=>'display: block;font-size: 21px;line-height: normal;padding: 5px 10px;text-align: center;text-decoration: none;', 'css_hover'=>''),
								'1'=>array('key'=>'wc_gallery', 'name'=>'Add to Cart', 'css'=>'color:#555;display: inline-block;font-size: 13px;line-height:normal;padding: 0 17px;text-align: center;', 'css_hover'=>''),
								
								'2'=>array('key'=>'excerpt', 'char_limit'=>'20', 'name'=>'Excerpt', 'css'=>'display: block;font-size: 14px;padding: 5px 10px;text-align: center;', 'css_hover'=>''),
									),										

						);
		
		$layout_content_list = apply_filters('post_grid_filter_layout_content_list', $layout_content_list);
		
		
		return $layout_content_list;
		}	
	

	
	public function layout_content($layout){
		
		$layout_content = $this->layout_content_list();
		
		return $layout_content[$layout];
		}	
		
	
	
	public function layout_hover_list(){
		
		$layout_hover_list = array(
									
									
						'flat'=>array(												

								'read_more'=>array('name'=>'Read more', 'css'=>'display: block;font-size: 12px;font-weight: bold;padding: 0 10px;text-align: center;')
									),										
						'flat-center'=>array(												

								'read_more'=>array('name'=>'Read more', 'css'=>'display: block;font-size: 12px;font-weight: bold;padding: 0 10px;text-align: center;')
									),
										
		
						);
		
		$layout_hover_list = apply_filters('post_grid_filter_layout_hover_list', $layout_hover_list);
		
		
		return $layout_hover_list;
		}	
	

	
	public function layout_hover($layout){
		
		$layout_hover = $this->layout_hover_list();
		
		return $layout_hover[$layout];
		}	
	
	
	
	
	public function skins(){
		
		$skins = array(


		
            'flat'=> array(
                'slug'=>'flat',
                'name'=>'Flat',
                'thumb_url'=>'',
                ),
            'flip-x'=> array(
                'slug'=>'flip-x',
                'name'=>'Flip-x',
                'thumb_url'=>'',
                ),
            'spinright'=>array(
                'slug'=>'spinright',
                'name'=>'SpinRight',
                'thumb_url'=>'',
            ),
            'thumbgoleft'=>array(
                'slug'=>'thumbgoleft',
                'name'=>'ThumbGoLeft',
                'thumb_url'=>'',
            ),
            'thumbrounded'=>array(
                'slug'=>'thumbrounded',
                'name'=>'ThumbRounded',
                'thumb_url'=>'',
            ),
            'contentbottom'=>array(
                'slug'=>'contentbottom',
                'name'=>'ContentBottom',
                'thumb_url'=>'',
            ),

										



            );
		
		$skins = apply_filters('post_grid_filter_skins', $skins);	
		
		return $skins;
		
		}





    public function skin_elements(){


        $layout_items['general'] = array(

            'name'=>'General',
            'description'=>'Default WordPress items for post.',
            'items'=>array(

                'post_title' => array(
                    'name' => 'Post title',
                    'dummy_html'=>'Post title goes here.',
                ),

                'post_excerpt' => array(
                    'name' => 'Post excerpt',
                    'dummy_html'=>'Dummy post excerpt goes here.',
                ),

                'post_content' => array(
                    'name' => 'Post content',
                    'dummy_html'=>'Dummy post content goes here. this text will take extra length.',
                ),

                'read_more'=>array(
                    'name'=>'Read more',
                    'dummy_html'=>'<a href="">Read more</a>',
                ),

                'featured_image'=>array(
                    'name'=>'Featured image',
                    'dummy_html'=>'<img style="width:100%" src="'.post_grid_plugin_url.'assets/admin/images/thumb.png" />',
                ),

                'media'=>array(
                    'name'=>'Media',
                    'dummy_html'=>'<img style="width:100%" src="'.post_grid_plugin_url.'assets/admin/images/thumb.png" />',
                ),


                'post_date'=>array(
                    'name'=>'Post date',
                    'dummy_html'=>'18/06/2015',

                ),
                'post_author'=>array(
                    'name'=>'Author',
                    'dummy_html'=>'Author name',
                ),

                'post_author_avatar'=>array(
                    'name'=>'Author avatar',
                    'dummy_html'=>'Author avatar',
                ),

                'category'=>array(
                    'name'=>'Post categories',
                    'dummy_html'=>'<a href="#">Category 1</a> <a href="#">Category 2</a>',
                ),
                'post_tag'=>array(
                    'name'=>'Post tags',
                    'dummy_html'=>'<a href="#">Tag 1</a> <a href="#">tag 2</a>',
                ),
                'comments_count'=>array(
                    'name'=>'Comments count',
                    'dummy_html'=>'2 Comments',
                ),
                'comments'=>array(
                    'name'=>'Post comments',
                    'dummy_html'=>'List of comments',
                ),

                'meta_key'=>array(
                    'name'=>'Meta Key',
                    'dummy_html'=>'Meta value goes here',

                ),

                'custom_taxonomy'=>array(
                    'name'=>'Custom taxonomy',
                    'dummy_html'=>'<a href="#">Terms 1</a> <a href="#">Terms 2</a>',

                ),

                'hr'=>array(
                    'name'=>'Horizontal line',
                    'dummy_html'=>'<hr/>',

                ),
                'html'=>array(
                    'name'=>'HTML',
                    'dummy_html'=>'Custom HTML goes here',
                ),

            ),

        );











        $layout_items['woocommerce'] = array(

            'name'=>'WooCommerce (In Pro)',
            'description'=>'WooCommerce items.',
            'items'=>array(

                'wc_full_price'=>array(
                    'name'=>'Product full price',
                    'dummy_html'=>'$45 <del>$50</del>',
                ),

                'wc_regular_price'=>array(
                    'name'=>'Product regular price',
                    'dummy_html'=>'$45',
                ),

                'wc_sale_price'=>array(
                    'name'=>'Product sale price',
                    'dummy_html'=>'$45',
                ),

                'wc_gallery'=>array(
                    'name'=>'Product gallery',
                    'dummy_html'=>'',
                ),


                'wc_add_to_cart'=>array(
                    'name'=>'Add to cart',
                    'dummy_html'=>'Add to cart',
                ),
                'wc_rating_star'=>array(
                    'name'=>'Product star rating',
                    'dummy_html'=>'<i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i>',
                ),
                'wc_rating_text'=>array(
                    'name'=>'Product text rating',
                    'dummy_html'=>'4.5 out of 5',
                ),
                'wc_categories'=>array(
                    'name'=>'Product categories',
                    'dummy_html'=>'<a href="#">Category 1</a> <a href="#">Category 2</a>',
                ),
                'wc_tags'=>array(
                    'name'=>'Product tags',
                    'dummy_html'=>'<a href="#">Tag 1</a> <a href="#">Tag 2</a>',
                ),
                'wc_sku'=>array(
                    'name'=>'Product SKU',
                    'dummy_html'=>'product_sku',
                ),

                'wc_cat_thumbnail'=>array(
                    'name'=>'Category thumbnail',
                    'dummy_html'=>'<img style="width:50px; height:50px" src="'.post_grid_plugin_url.'assets/admin/images/thumb.png" />',
                ),
                'wc_cat_description'=>array(
                    'name'=>'Category description',
                    'dummy_html'=>'This is simple dummy text for category description.',
                ),

            ),

        );

        $layout_items['edd'] = array(

            'name'=>'Easy Digital Downloads (in Pro)',
            'description'=>'Easy Digital Downloads items.',
            'items'=>array(

                'edd_price'=>array(
                    'name'=>'Download Price',
                    'dummy_html'=>'$45 <del>$50</del>',
                ),
                'edd_variable_prices'=>array(
                    'name'=>'Download variable prices',
                    'dummy_html'=>'$45 <del>$50</del>',
                ),
                'edd_sales_stats'=>array(
                    'name'=>'Download sales stats',
                    'dummy_html'=>'Sale stats',

                ),
                'edd_earnings_stats'=>array(
                    'name'=>'Download earnings stats',
                    'dummy_html'=>'earnings stats',
                ),
                'edd_add_to_cart'=>array(
                    'name'=>'EDD Add to Cart',
                    'dummy_html'=>'Add to Cart',
                ),
                'edd_rating_star'=>array(
                    'name'=>'Download star rating',
                    'dummy_html'=>'<i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i>',
                ),
                'edd_rating_text'=>array(
                    'name'=>'Download text rating',
                    'dummy_html'=>'3.5 out of 5',
                ),
                'edd_categories'=>array(
                    'name'=>'Download Categories',
                    'dummy_html'=>'<a href="#">Category 1</a> <a href="#">Category 2</a>',
                ),
                'edd_tags'=>array(
                    'name'=>'Download tags',
                    'dummy_html'=>'<a href="#">Tag 1</a> <a href="#">Tag 2</a>',
                ),

            )

        );






        $layout_items['WPeC'] = array(

            'name'=>'WP eCommerce',
            'description'=>'WP eCommerce items.',
            'items'=>array(

                'WPeC_old_price'=>array(
                    'name'=>'WPeC Old Price',
                    'dummy_html'=>'<del>$50</del>',

                ),
                'WPeC_sale_price'=>array(
                    'name'=>'WPeC Sale Price',
                    'dummy_html'=>'$45',
                ),
                'WPeC_add_to_cart'=>array(
                    'name'=>'WPeC Add to Cart',
                    'dummy_html'=>'Add to Cart',
                ),
                'WPeC_rating_star'=>array(
                    'name'=>'WPeC Star Rating',
                    'dummy_html'=>'<i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i>',
                ),
            )

        );





        $layout_items['the_events_calendar'] = array(

            'name'=>'The Events Calendar',
            'description'=>'The Events Calendar items.',
            'items'=>array(

                'tec_event_cat'=>array(
                    'name'=>'Event categories',
                    'dummy_html'=>'',
                ),

                'tec_event_tag'=>array(
                    'name'=>'Event tags',
                    'dummy_html'=>'',
                ),

                'tec_EventStartDate'=>array(
                    'name'=>'Event start date',
                    'dummy_html'=>'',
                ),

                'tec_EventEndDate'=>array(
                    'name'=>'Event end date',
                    'dummy_html'=>'',
                ),

                'tec_EventURL'=>array(
                    'name'=>'Event URL',
                    'dummy_html'=>'',
                ),


                'tec_EventCost'=>array(
                    'name'=>'Event cost',
                    'dummy_html'=>'',
                ),

                'tec_venue_address'=>array(
                    'name'=>'Venue address',
                    'dummy_html'=>'',
                ),

                'tec_venue_city'=>array(
                    'name'=>'Venue city',
                    'dummy_html'=>'',
                ),

                'tec_venue_country'=>array(
                    'name'=>'Venue country',
                    'dummy_html'=>'',
                ),
                'tec_venue_province'=>array(
                    'name'=>'Venue province',
                    'dummy_html'=>'',
                ),
                'tec_venue_zip'=>array(
                    'name'=>'Venue zip',
                    'dummy_html'=>'',
                ),

                'tec_venue_phone'=>array(
                    'name'=>'Venue phone',
                    'dummy_html'=>'',
                ),
                'tec_venue_url'=>array(
                    'name'=>'Venue URL',
                    'dummy_html'=>'',
                ),


                'tec_venue_map'=>array(
                    'name'=>'Venue Map',
                    'dummy_html'=>'',
                ),

                'tec_organizer_phone'=>array(
                    'name'=>'Organizer Phone',
                    'dummy_html'=>'',
                ),
                'tec_organizer_website'=>array(
                    'name'=>'Organizer Website',
                    'dummy_html'=>'',
                ),

                'tec_organizer_email'=>array(
                    'name'=>'Organizer Email',
                    'dummy_html'=>'',
                ),







            )
        );





        $layout_items['events_manager'] = array(

            'name'=>'Events Manager',
            'description'=>'Events Manager items.',
            'items'=>array(

                'em_event_cat'=>array(
                    'name'=>'Event categories',
                    'dummy_html'=>'',
                ),

                'em_event_tag'=>array(
                    'name'=>'Event tags',
                    'dummy_html'=>'',
                ),


                'em_event_start_date'=>array(
                    'name'=>'Event start date',
                    'dummy_html'=>'2018-01-24',
                ),
                'em_event_end_date'=>array(
                    'name'=>'Event end date',
                    'dummy_html'=>'2018-01-24',
                ),

                'em_event_start_time'=>array(
                    'name'=>'Event start time',
                    'dummy_html'=>'01:45:00',
                ),

                'em_event_end_time'=>array(
                    'name'=>'Event end time',
                    'dummy_html'=>'01:45:00',
                ),




                'em_event_spaces'=>array(
                    'name'=>'Event spaces',
                    'dummy_html'=>'',
                ),


                /*
                 * 				'em_event_rsvp'=>array(
                                    'name'=>'Max spaces per booking',
                                    'dummy_html'=>'',
                                ),
                 * */

                'em_event_rsvp_spaces'=>array(
                    'name'=>'Max Spaces',
                    'dummy_html'=>'',
                ),


                'em_event_rsvp_date'=>array(
                    'name'=>'Cut-Off Date',
                    'dummy_html'=>'',
                ),

                'em_event_rsvp_time'=>array(
                    'name'=>'Cut-Off Time',
                    'dummy_html'=>'',
                ),
                /*
                 *
                 *
                                'em_location_name'=>array(
                                    'name'=>'Location name',
                                    'dummy_html'=>'',
                                ),

                                'em_location_address'=>array(
                                    'name'=>'Location address',
                                    'dummy_html'=>'',
                                ),

                                'location_town'=>array(
                                    'name'=>'Location town',
                                    'dummy_html'=>'',
                                ),

                                'location_state'=>array(
                                    'name'=>'Location state',
                                    'dummy_html'=>'',
                                ),

                                'location_postcode'=>array(
                                    'name'=>'Location postcode',
                                    'dummy_html'=>'',
                                ),

                                'location_region'=>array(
                                    'name'=>'Location region',
                                    'dummy_html'=>'',
                                ),

                                'location_country'=>array(
                                    'name'=>'Location country',
                                    'dummy_html'=>'',
                                ),
                 *
                 * */




            )

        );




        $layout_items['acf'] = array(

            'name'=>'Advanced Custom Fields',
            'description'=>'Advanced Custom Fields items.',
            'items'=>array(

                //'zoom'=>'Zoom button',
                'acf_text'=>array(
                    'name'=>'ACF - Text',
                    'dummy_html'=>'',
                ),

                'acf_textarea'=>array(
                    'name'=>'ACF - Textarea',
                    'dummy_html'=>'',
                ),

                'acf_number'=>array(
                    'name'=>'ACF - Number',
                    'dummy_html'=>'',
                ),

                'acf_email'=>array(
                    'name'=>'ACF - Email',
                    'dummy_html'=>'',
                ),


                'acf_wysiwyg'=>array(
                    'name'=>'Acf - Wysiwyg',
                    'dummy_html'=>'',
                ),

                'acf_image'=>array(
                    'name'=>'Acf - Image',
                    'dummy_html'=>'',
                ),

                'acf_file'=>array(
                    'name'=>'Acf - File',
                    'dummy_html'=>'',
                ),

                'acf_select'=>array(
                    'name'=>'Acf - Select',
                    'dummy_html'=>'',
                ),
                'acf_checkbox'=>array(
                    'name'=>'Acf - Checkbox',
                    'dummy_html'=>'',
                ),



                'acf_page_link'=>array(
                    'name'=>'Acf - Page link',
                    'dummy_html'=>'',
                ),

                /*
                 *
                 * 				'acf_post_object'=>array(
                                    'name'=>'Acf - Post object',
                                    'dummy_html'=>'',
                                ),
                 *
                 * */

                'acf_taxonomy'=>array(
                    'name'=>'Acf - Taxonomy',
                    'dummy_html'=>'',
                ),


                'acf_user'=>array(
                    'name'=>'Acf - User',
                    'dummy_html'=>'',
                ),

                /*
                 *
                 * 				'acf_google_map'=>array(
                                    'name'=>'Acf - Google map',
                                    'dummy_html'=>'',
                                ),
                 * */


                'acf_date_picker'=>array(
                    'name'=>'Acf - Date Picker',
                    'dummy_html'=>'',
                ),










            )

        );





        $layout_items['others'] = array(

            'name'=>'Others',
            'description'=>'Others items.',
            'items'=>array(

                //'zoom'=>'Zoom button',
                'share_button'=>array(
                    'name'=>'Share button',
                    'dummy_html'=>'<a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-twitter-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a>',
                ),





                'five_star'=>array(
                    'name'=>'Five Star',
                    'dummy_html'=>'<i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i>',
                ),

                'yith_add_to_wishlist'=>array(
                    'name'=>'YITH - Add to Wishlist',
                    'dummy_html'=>'Add to Wishlist',
                ),

            )

        );



        return $layout_items;
    }


    public function media_source2(){

        $media_source = array(

            'featured_image' =>array(
                'title'=>__('Featured Image', 'post-grid'),
                'options'=>array(
                    'size'=> array('title'=>'Featured image size',  'type'=>'select', 'args'=>post_grid_intermediate_image_sizes(), 'value'=>'medium'),
                    'link_to'=> array('title'=>'Link to', 'type'=>'select', 'args'=>array('none'=>'None','post_link'=>'Post link','post_meta'=>'Post meta'), 'value'=>'post_link'),
                    'meta_key'=>array('title'=>'Meta key','placeholder'=>'my_meta_key', 'type'=>'text','value'=>''),
                ),
            ),
            'first_image'=>array(
                'title'=>__('First images from content', 'post-grid'),
                'options'=>array(
                    'link_to'=> array('title'=>'Link to', 'type'=>'select', 'args'=>array('none'=>'None','post_link'=>'Post link','post_meta'=>'Post meta'), 'value'=>'post_link'),
                    'meta_key'=>array('title'=>'Meta key', 'placeholder'=>'my_meta_key',  'type'=>'text','value'=>''),
                ),
            ),
            'first_gallery'=>array(
                'title'=>__('First gallery from content', 'post-grid'),
                'options'=>array(

                ),
            ),
            'first_youtube'=>array(
                'title'=>__('First youtube video from content', 'post-grid'),
                'options'=>array(

                ),
            ),
            'custom_youtube'=>array(
                'title'=>__('Custom youtube video', 'post-grid'),
                'options'=>array(

                ),
            ),
            'first_vimeo'=>array(
                'title'=>__('First vimeo video from content', 'post-grid'),
                'options'=>array(

                ),
            ),
            'custom_vimeo'=>array(
                'title'=>__('Custom vimeo video', 'post-grid'),
                'options'=>array(

                ),
            ),
            'first_dailymotion'=>array(
                'title'=>__('First dailymotion video from content', 'post-grid'),
                'options'=>array(

                ),
            ),
            'custom_dailymotion'=>array(
                'title'=>__('Custom dailymotion video', 'post-grid'),
                'options'=>array(

                ),
            ),
            'first_mp3'=>array(
                'title'=>__('First MP3 from content', 'post-grid'),
                'options'=>array(

                ),
            ),
            'custom_mp3'=>array(
                'title'=>__('Custom MP3', 'post-grid'),
                'options'=>array(

                ),
            ),
            'first_soundcloud'=>array(
                'title'=>__('First SoundCloud from content', 'post-grid'),
                'options'=>array(

                ),
            ),
            'custom_soundcloud'=>array(
                'title'=>__('Custom SoundCloud', 'post-grid'),
                'options'=>array(

                ),
            ),
            'empty_thumb'=>array(
                'title'=>__('Empty thumbnail', 'post-grid'),
                'options'=>array(
                    'thumb_src'=>array('title'=>'Thumbnail src', 'placeholder'=>'Thumbnail source url',  'type'=>'text','value'=>''),
                    'link_to'=> array('title'=>'Link to', 'type'=>'select', 'args'=>array('none'=>'None','post_link'=>'Post link','post_meta'=>'Post meta'), 'value'=>'post_link'),
                    'meta_key'=>array('title'=>'Meta key', 'placeholder'=>'my_meta_key',  'type'=>'text','value'=>''),

                ),

            ),
            'custom_thumb'=>array(
                'title'=>__('Custom Thumbnail', 'post-grid'),
                'options'=>array(
                    'link_to'=> array('title'=>'Link to', 'type'=>'select', 'args'=>array('none'=>'None','post_link'=>'Post link','post_meta'=>'Post meta'), 'value'=>'post_link'),
                    'meta_key'=>array('title'=>'Meta key', 'placeholder'=>'my_meta_key',  'type'=>'text','value'=>''),

                ),

            ),
            'font_awesome'=>array(
                'title'=>__('Font Awesome', 'post-grid'),
                'options'=>array(

                    'font_size'=>array( 'placeholder'=>'15px',  'type'=>'text','value'=>'40px'),
                    'color'=>array('title'=>'Color', 'placeholder'=>'#ffffff',  'type'=>'color','value'=>'#dddddd'),
                    'link_to'=> array('title'=>'Link to', 'type'=>'select', 'args'=>array('none'=>'None','post_link'=>'Post link','post_meta'=>'Post meta'), 'value'=>'post_link'),
                    'meta_key'=>array('title'=>'Meta key', 'placeholder'=>'my_meta_key',  'type'=>'text','value'=>''),
                ),

            ),


        );

        $media_source = apply_filters('post_grid_filter_media_source', $media_source);

        return $media_source;


    }




    public function skin_elements_data(){



        $elements_data['post_title'] = array(
            'key' => 'post_title',

            'options'=>array(
                'wrapper_tag'=>'',
                'custom_class'=>'',
                'limit_by'=>'',
                'limit_count'=>'',
                'is_linked'=>'',
                'linked_to'=>'',
                'linked_to_post_meta'=>'',
                'linked_to_taxonomy'=>'',
            ),

            'style_idle'=>array(
                'fontSize'=>'13px',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#ffffff'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'cursor'=> '',
                'textAlign'=> '',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',
            ),
            'style_hover'=>array(
                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'textAlign'=> '',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',
            ),



        );


        $elements_data['post_excerpt'] = array(

            'key' => 'post_excerpt',
            'options'=>array(
                'wrapper_tag'=>'',
                'custom_class'=>'',
                'limit_by'=>'',
                'limit_count'=>'',
                'end_text_display'=>'',
                'end_text'=>'',
                'keep_html'=>'',
                'shortcode_enable'=>'',
            ),
            'style_idle'=>array(

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',



            ),
            'style_hover'=>array(

                'enable'=> 'no',
                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',
            ),


        );



        $elements_data['post_content'] = array(

            'key' => 'post_content',
            'options'=>array(
                'wrapper_tag'=>'',
                'custom_class'=>'',


            ),
            'style_idle'=>array(
                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',
            ),

            'style_hover'=>array(

                'enable'=> 'no',
                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',
            ),



        );


        $elements_data['read_more'] = array(

            'key' => 'read_more',

            'options'=>array(
                'read_more_text'=>'',
                'custom_class'=>'',
                'is_linked'=>'',
                'linked_to'=>'',
                'linked_to_post_meta'=>'',
                'linked_to_taxonomy'=>'',
            ),

            'style_idle'=>array(
                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',


            ),

            'style_hover'=>array(
                'enable'=> 'no',
                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',
            ),



        );



        $elements_data['featured_image'] = array(

            'key' => 'featured_image',
            'options'=>array(
                'custom_class'=>'',
                'image_size'=>'',
                'featured_image_src'=>'',
                'image_src_meta_key'=>'',
                'is_linked'=>'',
                'linked_to'=>'',
                'linked_to_post_meta'=>'',
                'linked_to_taxonomy'=>'',

            ),

            'style_idle'=>array(

                'filter'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),

            'style_hover'=>array(

                'enable'=> 'no',

                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),





        );



        $elements_data['post_author_avatar'] = array(

            'key' => 'post_author_avatar',
            'options'=>array(
                'image_size'=>'',
                'custom_class'=>'',
                'is_linked'=>'',
                'linked_to'=>'',
            ),

            'style_idle'=>array(

                'filter'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),

            'style_hover'=>array(

                'enable'=> 'no',

                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),

        );


        $elements_data['post_date'] = array(

            'key' => 'post_date',
            'options'=>array(
                'date_format'=>'',
                'custom_class'=>'',
                'is_linked'=>'',
                'linked_to'=>'',
                'linked_to_post_meta'=>'',
                'linked_to_taxonomy'=>'',

            ),

            'style_idle'=>array(

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),


            'style_hover'=>array(

                'enable'=> 'no',

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),


        );



        $elements_data['post_author'] = array(

            'key' => 'post_author',
            'options'=>array(
                'author_name'=>'',
                'custom_class'=>'',
                'is_linked'=>'',
                'linked_to'=>'',
                'linked_to_post_meta'=>'',
                'linked_to_taxonomy'=>'',

            ),

            'style_idle'=>array(

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),

            'style_hover'=>array(

                'enable'=> 'no',

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),


        );



        $elements_data['category'] = array(

            'key' => 'category',
            'options'=>array(

                'custom_class'=>'',
                'is_linked'=>'',
                'max_limit'=>'',
                'separator'=>'',
            ),


            'style_idle'=>array(

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),
            'style_hover'=>array(

                'enable'=> 'no',

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),







        );



        $elements_data['post_tag'] = array(
            'key' => 'post_tag',
            'options'=>array(

                'custom_class'=>'',
                'is_linked'=>'',
                'max_limit'=>'',
                'separator'=>'',
            ),

            'style_idle'=>array(

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',



            ),
            'style_hover'=>array(

                'enable'=> 'no',

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),


        );



        $elements_data['comments_count'] = array(
            'key' => 'comments_count',
            'options'=>array(

                'custom_class'=>'',
                'no_comment_text'=>'',
                'singular_text'=>'',
                'plural_text'=>'',

            ),

            'style_idle'=>array(


                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',

                'custom_css'=>'',

            ),
            'style_hover'=>array(

                'enable'=> 'no',

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),




        );

        $elements_data['comments'] = array(
            'key' => 'comments',
            'options'=>array(

                'custom_class'=>'',
                'template'=> '',
            ),

            'style_idle'=>array(


                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',

                'custom_css'=>'',


            ),
            'style_hover'=>array(

                'enable'=> 'no',
                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),


        );


        $elements_data['meta_key'] = array(
            'key' => 'meta_key',
            'options'=>array(

                'custom_class'=>'',
                'meta_key'=>'',
                'wrapper_text'=>'',

            ),

            'style_idle'=>array(

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',

                'custom_css'=>'',



            ),
            'style_hover'=>array(

                'enable'=> 'no',

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),




        );




        $elements_data['custom_taxonomy'] = array(

            'key' => 'custom_taxonomy',
            'options'=>array(

                'custom_class'=>'',
                'taxonomy'=>'',
                'max_limit'=>'',
                'separator'=>'',
                'is_linked'=>'',

            ),

            'style_idle'=>array(

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),
            'style_hover'=>array(

                'enable'=> 'no',

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),



        );


        $elements_data['hr'] = array(
            'key' => 'hr',
            'options'=>array(

                'custom_class'=>'',

            ),

            'style_idle'=>array(

                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),
            'style_hover'=>array(

                'enable'=> 'no',

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),




        );



        $elements_data['html'] = array(
            'key' => 'html',

            'options'=>array(

                'custom_class'=>'',
                'do_shortcode'=> '',
                'wpautop'=> '',
                'html_content'=>'',


            ),

            'style_idle'=>array(

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',


            ),
            'style_hover'=>array(

                'enable'=> 'no',
                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),





        );


        $elements_data['wc_full_price'] = array(

            'key' => 'wc_full_price',
            'options'=>array(
                'wrapper_tag'=>'',
                'custom_class'=>'',
                'wrapper_text'=>'',


            ),

            'style_idle'=>array(


                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',



            ),
            'style_hover'=>array(

                'enable'=> 'no',

                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),



        );



        $elements_data['wc_regular_price'] = array(
            'key' => 'wc_regular_price',
            'options'=>array(
                'wrapper_tag'=>'',
                'custom_class'=>'',
                'wrapper_text'=>'',


            ),

            'style_idle'=>array(


                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',

                'custom_css'=>'',


            ),
            'style_hover'=>array(


                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),


        );


        $elements_data['wc_sale_price'] = array(
            'key' => 'wc_sale_price',
            'options'=>array(
                'wrapper_tag'=>'',
                'custom_class'=>'',
                'wrapper_text'=>'',


            ),

            'style_idle'=>array(


                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',



            ),
            'style_hover'=>array(


                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'textAlign'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),



        );



        $elements_data['wc_gallery'] = array(
            'key' => 'wc_gallery',
            'options'=>array(
                'wrapper_tag'=>'',
                'custom_class'=>'',
                'thumb_size'=>'',


            ),

            'style_idle'=>array(


                'filter'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',


            ),
            'style_hover'=>array(


                'filter'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),




        );



        $elements_data['wc_add_to_cart'] = array(
            'key' => 'wc_add_to_cart',
            'options'=>array(
                'wrapper_tag'=>'',
                'custom_class'=>'',
                'cart_text'=>'',
                'show_price'=> '',
                'is_default'=> '',


            ),
            'style_idle'=>array(


                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',



            ),
            'style_hover'=>array(


                'fontSize'=>'',
                'lineHeight'=>'',
                'color'=>array('hex8'=>'#000000'),
                'backgroundColor'=>array('hex8'=>'#000000'),
                'fontFamily'=> '',
                'fontWeight'=> '',
                'textDecoration'=> '',
                'fontStyle'=> '',
                'textTransform'=> '',
                'position'=>'',
                'display'=> '',
                'float'=> '',
                'clear'=> '',
                'margin'=>'',
                'padding'=>'',
                'top'=>'',
                'bottom'=>'',
                'left'=>'',
                'right'=>'',
                'height'=>'',
                'width'=>'',
                'zIndex'=>'',
                'transform'=>'',
                'transition'=>'',
                'opacity'=>'',
                'custom_css'=>'',

            ),





        );



//
//        $elements_data['wc_rating_star'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//                        'wrapper_tag'=>'',
//                        'custom_class'=>'',
//
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//
//        $elements_data['wc_rating_text'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//                        'wrapper_tag'=>'',
//                        'custom_class'=>'',
//
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['wc_categories'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'is_linked'=>'',
//                        'max_limit'=>'',
//                        'separator'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['wc_tags'] =  array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'is_linked'=>'',
//                        'max_limit'=>'',
//                        'separator'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//        $elements_data['wc_sku'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'wrapper_text'=>array('title'=>'Wrapper text', 'type'=>'text', 'value'=>'SKU goes %s here'),
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//
//
//
//        $elements_data['wc_cat_thumbnail']=array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'linked_to'=>'',
//                        'image_size'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'filter'=> '',
//                        'width'=>'',
//                        'height'=>'',
//                        'overflow'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'filter'=> '',
//                        'width'=>'',
//                        'height'=>'',
//                        'overflow'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//
//        $elements_data['wc_cat_description'] =array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'limit_by'=>'',
//                        'limit_count'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//        $elements_data['edd_price'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'wrapper_text'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['edd_variable_prices'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'wrapper_text'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['edd_sales_stats'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'wrapper_text'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['edd_earnings_stats'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'wrapper_text'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['edd_add_to_cart'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//                        'custom_class'=>'',
//                        'cart_text'=>'',
//
//
//
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['edd_rating_star'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//                        'wrapper_tag'=>'',
//                        'custom_class'=>'',
//                        'is_default'=> array('title'=>'Is default star ratings', 'type'=>'select', 'args'=>array('default','custom'), 'value'=>'default'),
//
//
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['edd_rating_text'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//                        'wrapper_tag'=>'',
//                        'custom_class'=>'',
//
//
//
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//        $elements_data['edd_categories'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'is_linked'=>'',
//                        'max_limit'=>'',
//                        'separator'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['edd_tags'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'is_linked'=>'',
//                        'max_limit'=>'',
//                        'separator'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//        $elements_data['WPeC_old_price'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'wrapper_text'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//
//        $elements_data['WPeC_sale_price'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'wrapper_text'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['WPeC_rating_star'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'wrapper_text'=>'',
//                        //'is_default'=> array('title'=>'Is default star ratings', 'type'=>'select', 'args'=>array('default','custom'), 'value'=>'default'),
//
//
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['WPeC_categories'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'is_linked'=>'',
//                        'max_limit'=>'',
//                        'separator'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//        $elements_data['WPeC_tags'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                        'is_linked'=>'',
//                        'max_limit'=>'',
//                        'separator'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'textAlign'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//
//
//
//
//
//        $elements_data['share_button'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//
//                        'custom_class'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//
//        $elements_data['five_star'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//                        'wrapper_tag'=>'',
//                        'custom_class'=>'',
//
//
//
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//
//
//            ),
//
//
//        );
//
//
//
//
//        $elements_data['yith_add_to_wishlist'] = array(
//
//            'options_group'=>array(
//                'settings'=>array(
//
//                    'options'=>array(
//                        'wrapper_tag'=>'',
//                        'custom_class'=>'',
//                    ),
//                ),
//                'style_idle'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//
//
//                    ),
//                ),
//                'style_hover'=>array(
//
//                    'options'=>array(
//                        'fontSize'=>'',
//                        'lineHeight'=>'',
//                        'color'=>array('hex8'=>'#000000'),
//                        'backgroundColor'=>array('hex8'=>'#000000'),
//                        'fontFamily'=> '',
//                        'fontWeight'=> '',
//                        'textDecoration'=> '',
//                        'fontStyle'=> '',
//                        'textTransform'=> '',
//                        'position'=>'',
//                        'display'=> '',
//                        'float'=> '',
//                        'clear'=> '',
//                        'margin'=>'',
//                        'padding'=>'',
//                        'top'=>'',
//                        'bottom'=>'',
//                        'left'=>'',
//                        'right'=>'',
//                        'height'=>'',
//                        'width'=>'',
//                        'zIndex'=>'',
//                        'transform'=>'',
//                        'transition'=>'',
//                        'opacity'=>'',
//                        'custom_css'=>'',
//                    ),
//                ),
//            ),
//
//
//        );





        return apply_filters('post_grid_elements_data', $elements_data);
        //return $layout_items;
    }






}
	
//new class_post_grid_functions();