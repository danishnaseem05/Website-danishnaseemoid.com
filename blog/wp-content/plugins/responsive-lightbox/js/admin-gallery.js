( function ( $ ) {

	ResponsiveLightboxGallery = {
		modal: null,
		lastGalleryID: 0,
		lastGalleryImage: '',
		selectGalleryEventInitialized: false,
		resetFilters: false,
		galleries: {},
		gutenberg: false,
		primaryButtonClass: '',
		secondaryButtonClass: '',
		init: function() {
			this.gutenberg = typeof rlBlockEditor !== 'undefined';
			this.searchGalleries = _.debounce( this.getGalleries, 500 ),
			this.bindEvents();
			this.setButtons();
		},
		searchGalleries: function() {},
		setButtons: function() {
			if ( this.gutenberg ) {
				this.primaryButtonClass = '.rl-media-button-select-gallery';
				this.secondaryButtonClass = '.rl-media-button-insert-gallery';
			} else {
				this.primaryButtonClass = '.rl-media-button-insert-gallery';
				this.secondaryButtonClass = '.rl-media-button-select-gallery';
			}
		},
		getModal: function() {
			return this.modal[0];
		},
		getModalButton: function() {
			return this.modal[0].getElementsByClassName( 'rl-media-button-select-gallery' )[0];
		},
		open: function( galleryID ) {
			if ( typeof galleryID === 'undefined' )
				var galleryID = 0;

			var phrase = '';

			$( this.primaryButtonClass ).show();
			$( this.secondaryButtonClass ).hide();

			// resetFilters
			if ( this.resetFilters ) {
				phrase = '';

				// clear searh input
				$( '#rl-media-search-input' ).val( '' );

				// reset categories
				this.modal.find( '#rl-media-attachment-categories' ).val( 0 );
			} else
				phrase = $( '#rl-media-search-input' ).val();

			// display modal
			this.modal.show();

			// fix columns
			this.setColumns();

			// get galleries
			this.getGalleries( phrase, galleryID );
		},
		close: function( event ) {
			event.preventDefault();

			this.modal.hide();
		},
		setColumns: function() {
			var list = this.modal.find( '.rl-galleries-list' ),
				list_width = list.width(),
				content = this.modal.find( '.media-frame-content' ),
				columns = parseInt( content.attr( 'data-columns' ) ),
				old_columns = new_columns = columns;

			if ( list_width ) {
				var width = this.modal.find( '.media-sidebar' ).outerWidth() + 'px';

				list.css( 'right', width );
				this.modal.find( '.attachments-browser .media-toolbar' ).css( 'right', width );
				new_columns = Math.min( Math.round( list_width / 170 ), 12 ) || 1;

				if ( ! old_columns || old_columns !== new_columns )
					content.attr( 'data-columns', new_columns );
			}
		},
		handleClickGallery: function( event ) {
			event.preventDefault();

			var gallery = $( event.target ).closest( 'li' ),
				currentGalleryID = parseInt( gallery.data( 'id' ) );

			if ( this.lastGalleryID !== currentGalleryID ) {
				gallery.parent().find( 'li' ).removeClass( 'selected details' );

				this.lastGalleryID = currentGalleryID;

				// get full source image
				var fullSource = gallery.find( '.centered' ).data( 'full-src' );

				// invalid full source image?
				if ( fullSource === '' )
					this.lastGalleryImage = gallery.find( 'img' ).first().attr( 'src' );
				else
					this.lastGalleryImage = fullSource;

				gallery.addClass( 'selected details' );

				this.clickGallery( currentGalleryID, false );
			} else {
				if ( gallery.hasClass( 'selected details' ) ) {
					gallery.removeClass( 'selected details' );

					this.clickGallery( currentGalleryID, true );
				} else {
					gallery.addClass( 'selected details' );

					this.clickGallery( currentGalleryID, false );
				}
			}
		},
		clickGallery: function( gallery_id, toggle ) {
			var _this = this;

			_this.modal.find( '.media-selection' ).toggleClass( 'empty', toggle );
			_this.modal.find( this.primaryButtonClass ).prop( 'disabled', toggle );

			// load gallery preview images?
			if ( ! toggle ) {
				// clear images
				_this.modal.find( '.rl-attachments-list' ).empty();

				// load cached images
				if ( typeof _this.galleries[gallery_id] !== 'undefined' ) {
					// update images
					this.updateGalleryPreview( _this.galleries[gallery_id], false );
				// get images for the first time
				} else {
					var spinner = _this.modal.find( '.rl-gallery-images-spinner' ),
						info = _this.modal.find( '.selection-info' );

					// display spinner
					spinner.fadeIn( 'fast' ).css( 'visibility', 'visible' );

					// turn off info
					info.addClass( 'rl-loading-content' );

					$.post( ajaxurl, {
						action: 'rl-post-gallery-preview',
						post_id: rlArgsGallery.post_id,
						gallery_id: gallery_id,
						nonce: rlArgsGallery.nonce
					} ).done( function ( response ) {
						try {
							if ( response.success ) {
								// store gallery data
								_this.galleries[gallery_id] = response.data;

								// update gallery data
								_this.updateGalleryPreview( _this.galleries[gallery_id], true );
							} else {
								//@TODO
							}
						} catch( e ) {
							//@TODO
						}
					} ).always( function () {
						// hide spinner
						spinner.fadeOut( 'fast' );

						// turn on info
						info.removeClass( 'rl-loading-content' );
					} );
				}
			}
		},
		selectGallery: function( event ) {
			event.preventDefault();

			if ( $( this ).attr( 'disabled' ) )
				return;

			this.modal.hide();
		},
		insertGallery: function( event ) {
			event.preventDefault();

			if ( $( this ).attr( 'disabled' ) )
				return;

			var shortcode = '[rl_gallery id="' + this.lastGalleryID + '"]';
				editor = tinyMCE.get( 'content' );

			if ( editor )
				editor.execCommand( 'mceInsertContent', false, shortcode );
			else
				wp.media.editor.insert( shortcode );

			this.modal.hide();
		},
		getGalleries: function( search, galleryID ) {
			var modal = this.modal,
				spinner = $( '.rl-gallery-reload-spinner' ),
				galleries = modal.find( '.rl-galleries-list' ),
				_this = this;

			// clear galleries
			galleries.empty();

			// hide gallery info
			modal.find( '.media-selection' ).addClass( 'empty' );

			// clear images
			modal.find( '.rl-attachments-list' ).empty();

			// display spinner
			spinner.fadeIn( 'fast' );

			// get galleries
			$.post( ajaxurl, {
				action: 'rl-post-get-galleries',
				post_id: rlArgsGallery.post_id,
				search: search,
				nonce: rlArgsGallery.nonce,
				category: _this.resetFilters ? 0 : modal.find( '#rl-media-attachment-categories' ).val()
			} ).done( function ( response ) {
				try {
					if ( response.success ) {
						if ( response.data !== '' ) {
							modal.find( '.rl-no-galleries' ).hide();
							modal.find( '.rl-galleries-list' ).empty().append( response.data );

							// select gallery	
							if ( galleryID !== 0 )
								galleries.find( 'li[data-id="' + galleryID + '"] .js--select-attachment' ).click();
						} else
							modal.find( '.rl-no-galleries' ).show();
					} else {
						//
					}
				} catch( e ) {
					//
				}
			} ).always( function () {
				// hide spinner
				spinner.fadeOut( 'fast' );
			} );
		},
		updateGalleryPreview: function( gallery, animate ) {
			// update gallery attachments
			this.modal.find( '.rl-attachments-list' ).append( gallery.attachments ).fadeOut( 0 ).delay( animate? 'fast' : 0 ).fadeIn( 0 );

			// update number of images in gallery
			this.modal.find( '.rl-gallery-count' ).text( gallery.count );

			// update gallery edit link
			if ( gallery.edit_url !== '' )
				this.modal.find( '.rl-edit-gallery-link' ).removeClass( 'hidden' ).attr( 'href', gallery.edit_url );
			else
				this.modal.find( '.rl-edit-gallery-link' ).addClass( 'hidden' ).attr( 'href', '' );
		},
		reloadGalleries: function( event ) {
			event.preventDefault();

			// hide "no galleries" box
			this.modal.find( '.rl-no-galleries' ).hide();

			// reset galleries
			this.galleries = {};

			// reset filters
			this.resetFilters = false;

			// load galleries
			this.getGalleries( $( '#rl-media-search-input' ).val(), 0 );
		},
		bindEvents: function() {
			var _this = this;

			// add gallery
			$( document ).on( 'click', '#rl-insert-modal-gallery-button', function( e ) { _this.open( 0 ); } );

			// ready?
			$( document ).on( 'ready', function() {
				_this.modal = $( '#rl-modal-gallery' );

				// search galleries
				_this.modal.on( 'keyup', '#rl-media-search-input', function () {
					_this.searchGalleries( $( this ).val() );
				} );

				// reload galleries
				_this.modal.on( 'click', '.rl-reload-galleries', function ( e ) {
					_this.reloadGalleries( e );
				} );

				// change category
				_this.modal.on( 'change', '#rl-media-attachment-categories', function( e ) {
					_this.reloadGalleries( e );
				} );

				// close gallery
				_this.modal.on( 'click', '.media-modal-close, .media-modal-backdrop, .rl-media-button-cancel-gallery', function ( e ) {
					_this.close( e );
				} );

				// click gallery
				_this.modal.on( 'click', '.rl-galleries-list li .js--select-attachment, .rl-galleries-list li button', function ( e ) {
					_this.handleClickGallery( e );
				} );

				// insert gallery (classic editor)
				_this.modal.on( 'click', '.rl-media-button-insert-gallery', function ( e ) {
					_this.insertGallery( e );
				} );

				// select gallery (block editor)
				_this.modal.on( 'click', '.rl-media-button-select-gallery', function ( e ) {
					_this.selectGallery( e );
				} );

				// resize window
				$( window ).on( 'resize', function () {
					_this.setColumns();
				} );
			} );
		}
    }

	ResponsiveLightboxGallery.init();

} )( jQuery );