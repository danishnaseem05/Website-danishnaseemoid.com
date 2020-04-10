<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_post_grid_support{
	
	public function __construct(){

	}

    public function our_plugins(){

        $our_plugins = array(
            array(
                'title'=>'Post Grid',
                'link'=>'http://www.pickplugins.com/item/post-grid-create-awesome-grid-from-any-post-type-for-wordpress/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2015/12/3814-post-grid-thumb-500x262.jpg',
            ),

            array(
                'title'=>'Woocommerce Products Slider',
                'link'=>'http://www.pickplugins.com/item/woocommerce-products-slider-for-wordpress/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2016/03/4357-woocommerce-products-slider-thumb-500x250.jpg',
            ),

            array(
                'title'=>'Team Showcase',
                'link'=>'http://www.pickplugins.com/item/team-responsive-meet-the-team-grid-for-wordpress/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2016/06/5145-team-thumb-500x250.jpg',
            ),

            array(
                'title'=>'Job Board Manager',
                'link'=>'https://wordpress.org/plugins/job-board-manager/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2015/08/3466-job-board-manager-thumb-500x250.png',
            ),

            array(
                'title'=>'Wishlist for WooCommerce',
                'link'=>'https://www.pickplugins.com/item/woocommerce-wishlist/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2017/10/12047-woocommerce-wishlist.png',
            ),

            array(
                'title'=>'Breadcrumb',
                'link'=>'https://www.pickplugins.com/item/breadcrumb-awesome-breadcrumbs-style-navigation-for-wordpress/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2016/03/4242-breadcrumb-500x252.png',
            ),

            array(
                'title'=>'Pricing Table',
                'link'=>'https://www.pickplugins.com/item/pricing-table/',
                'thumb'=>'https://www.pickplugins.com/wp-content/uploads/2016/10/7042-pricing-table-thumbnail-500x250.png',
            ),

        );

        return apply_filters('post_grid_our_plugins', $our_plugins);


    }


    public function video_tutorials(){


        $tutorials = array(

            array(
                'question'=>__('How to create post grid', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=g5kxtJIopXs',
            ),
            array(
                'question'=>__('Custom read more text', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=LY7IjS7SFNk',
            ),
            array(
                'question'=>__('Remove read more text', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=ZcS2vRcTe4A',
            ),
            array(
                'question'=>__('Excerpt word count', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=gZ6E3UiKQqk',
            ),

            array(
                'question'=>__('Custom media height', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=TupF2TpHHFA',
            ),
            array(
                'question'=>__('Item custom padding margin', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=HRZpoib1VvI',
            ),
            array(
                'question'=>__('Grid item height', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=ydqlgzfsboQ',
            ),
            array(
                'question'=>__('Column Width or column number', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=ZV8hd1ij5Wo',
            ),
            array(
                'question'=>__('Post title linked', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=oUVZB9F5d4U',
            ),
            array(
                'question'=>__('Featured image linked to post', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=stGOJLwUF-k',
            ),
            array(
                'question'=>__('Query post by categories or terms', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=xYzqtWRg8W4',
            ),
            array(
                'question'=>__('Query post by tags or terms', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=RKb-B_Q72Ak',
            ),
            array(
                'question'=>__('Display search input', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=psJR65Fmc_s',
            ),
            array(
                'question'=>__('Work with layout editor', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=9bQc7q40jMc',
            ),
            array(
                'question'=>__('[ Pro ] Create filterable grid', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=Zg2r7idmEm0',
            ),
            array(
                'question'=>__('[ Pro ] Filterable custom filter type data logic', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=5Dueav6Yoyc',
            ),

            array(
                'question'=>__('[ Pro ] Filterable custom all text', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=JvVkAyoXC3g',
            ),
            array(
                'question'=>__('[ Pro ] Filterable default active filter', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=h2rbyZNhMhU',
            ),

            array(
                'question'=>__('[ Pro ] Filterable custom filter', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=e8phxNKIRsU',
            ),

            array(
                'question'=>__('[ Pro ] Filterable dropdown single filter', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=ZHY8qf-z3H0',
            ),

            array(
                'question'=>__('[ Pro ] Filterable display sort filter', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=21TYNsp2OPI',
            ),

            array(
                'question'=>__('[ Pro ] Filterable multi filter', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=uRcfd_R9YCM',
            ),



            array(
                'question'=>__('[ Pro ] Post grid on archive tags', 'post-grid'),
                'answer_url'=>'https://youtu.be/lNyAjva_UXo',
            ),




            array(
                'question'=>__('[ Pro ] Query post by meta field', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=0AIDNJvZGR0',
            ),


            array(
                'question'=>__('[ Pro ] Multi skin', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=YzUs_P3cFCo',
            ),
            array(
                'question'=>__('[ Pro ] Sticky post query', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=nVIOUbVjML4',
            ),
            array(
                'question'=>__('[ Pro ] Masonry layout', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=qYjbv2euNpE',
            ),
            array(
                'question'=>__('[ Pro ] Post query by author', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=KtoGa8NB3ig',
            ),
            array(
                'question'=>__('[ Pro ] Create glossary grid', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=MKL4EZ-WYTs',
            ),
            array(
                'question'=>__('[ Pro ] Post carousel slider', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=A0bZ_luBtQQ',
            ),

            array(
                'question'=>__('[ Pro ] Grid layout type', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=58piQVkDZN4',
            ),
            array(
                'question'=>__('[ Pro ] Thumbnail youtube', 'post-grid'),
                'answer_url'=>'https://www.youtube.com/watch?v=Zm5vD15yvNM',
            ),


        );


        return apply_filters('post_grid_video_tutorials', $tutorials);


    }



    public function faq(){
        $faq = array(
            array(
                'question'=>__('How to Create a Post Grid?', 'post-grid'),
                'answer_url'=>'https://www.pickplugins.com/documentation/post-grid/faq/how-to-create-a-post-grid/',
            ),
            array(
                'question'=>__('How to upgrade to premium?', 'post-grid'),
                'answer_url'=>'https://www.pickplugins.com/documentation/post-grid/upgrade-to-premium/',
            ),

//            array(
//                'question'=>__('How to activate license?', 'post-grid'),
//                'answer_url'=>'https://www.pickplugins.com/documentation/woocommerce-products-slider/faq/activate-license/',
//            ),

            array(
                'question'=>__('Post grid on archive page?', 'post-grid'),
                'answer_url'=>'https://www.pickplugins.com/documentation/post-grid/faq/post-grid-for-archive-page/',
            ),


            array(
                'question'=>__('How to display HTML/Shortcode via layout editor ?', 'post-grid'),
                'answer_url'=>'https://www.pickplugins.com/documentation/post-grid/faq/layout-editor-how-at-add-htmlshortcode/',
            ),
        );


        return apply_filters('post_grid_faq', $faq);



    }





	
	
}

