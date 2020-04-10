jQuery( function( $ ){
	$( document ).on( 'change',  '[ name="lp_loading_screen" ]' , function(){
		if( this.value == 'logo' )
		{	
			$( '.lp_ls_section' ).hide();
			$( '#loading_screen_logo' ).show();
		}
		else
		{
			$( '#loading_screen_logo' ).hide();
		}	
	});
} );