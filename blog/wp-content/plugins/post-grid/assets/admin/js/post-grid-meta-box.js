jQuery(document).ready(function($){

	$(document).on('click', '.post-grid-meta-box .grid-type-wrap input[name="post_grid_meta_options[grid_type]"]', function(){
		var val = $(this).val();

		$('.post-grid-meta-box .tab-navs li').each(function( index ) {
			data_visible = $( this ).attr('data_visible');

			if(typeof data_visible != 'undefined'){
				//console.log('undefined '+ data_visible );

				n = data_visible.indexOf(val);
				if(n<0){
					$( this ).hide();
				}else{
					$( this ).show();
				}
			}else{
				//console.log('Not undefined '+ data_visible );


			}
		});


	})


		



});







