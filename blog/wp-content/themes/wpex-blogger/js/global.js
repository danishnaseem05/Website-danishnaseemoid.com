jQuery( function($) {
	
	$(document).ready(function(){
		
		// Main menu superfish
		$('ul.sf-menu').superfish({
			delay: 200,
			animation: {opacity:'show', height:'show'},
			speed: 'fast',
			cssArrows: false,
			disableHI: true
		});
		
		// Mobile Menu
		$('#navigation-toggle').sidr({
			name: 'sidr-main',
			source: '#sidr-close, #site-navigation, #mobile-search',
			side: 'left'
		});
		$(".sidr-class-toggle-sidr-close").click( function() {
			$.sidr('close', 'sidr-main');
			return false;
		});
		
		// Close the menu on window change
		$(window).resize(function() {
			$.sidr('close', 'sidr-main');
		});

		
	}); // End doc ready

	$(window).load(function() {

		// Infinite scroll
		var $container = $('#blog-wrap');
		$container.infinitescroll({
			loading: {
				msg: null,
				finishedMsg : null,
				msgText : null,
				msgText: '<div class="infinite-scroll-loader"><i class="fa fa-spinner fa-spin"></i></div>',
			},
			navSelector  : 'div.page-jump',
			nextSelector : 'div.page-jump div.older-posts a',
			itemSelector : '.loop-entry',
		}, function( newElements ) {
			var $newElems = $( newElements ).css({ opacity: 0 });
			$newElems.imagesLoaded(function() {
				$newElems.animate({ opacity: 1 });
			});
		});

	}); // End window.load
	
});