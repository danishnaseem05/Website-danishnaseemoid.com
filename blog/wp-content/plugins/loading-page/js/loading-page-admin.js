jQuery(window).on( 'load', function($){
	var $ = jQuery;

    // Main application
    window['loading_page_selected_image'] = function(fieldName){
        var img_field = $('input[name="'+fieldName+'"]');
        var media = wp.media({
				title: 'Select Media File',
				library:{
					type: 'image'
				},
				button: {
				text: 'Select Item'
				},
				multiple: false
		}).on('select', 
			(function( field ){
				return function() {
					var attachment = media.state().get('selection').first().toJSON();
					var url = attachment.url;
					field.val( url );
				};
			})( img_field )	
		).open();
		return false;
    };
    
    function setPicker(field, colorPicker){
        $(colorPicker).hide();
        $(colorPicker).farbtastic(field);
        $(field).click(function(){$(colorPicker).slideToggle()});
    };
    
	$( document ).on( 
		'change', 
		'[name="lp_loading_screen"]',  
		function( evt, mssg ){
			if( typeof mssg == 'undefined' || mssg == true )
			{	
				var	t = $(evt.target.options[evt.target.selectedIndex]).attr('title');
				if( t && t.length ){ alert(t); }
			}	
		} 
	);
	
    $(function(){
        setPicker("#lp_backgroundColor", "#lp_backgroundColor_picker");
        setPicker("#lp_foregroundColor", "#lp_foregroundColor_picker");
    });
	
	$( '[name="lp_loading_screen"]' ).trigger( 'change', [ false ] );
});