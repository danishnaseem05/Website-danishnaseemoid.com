( function ( $ ) {

	$( document ).on( 'ready', function () {
		var gallery_frame = null,
			attachment_frame = null,
			gallery_container = $( '.rl-gallery-images' ),
			gallery_ids = $( '.rl-gallery-ids' ),
			wikimedia = { '1': '' };

		media_gallery_sortable( gallery_container, gallery_ids, $( 'input[name="rl_gallery[images][menu_item]"]:checked' ).val() );

		// color picker
		$( '.rl-gallery-tab-content .color-picker' ).wpColorPicker();

		// make sure HTML5 validation is turned off
		$( 'form#post' ).attr( 'novalidate', 'novalidate' );

		// make sure to dispay images metabox at start
		$( '#responsive-gallery-images' ).show();

		// move navigation tabs and metaboxes to second postbox container to fix mobile devices problem
		$( '.rl-display-metabox, .rl-hide-metabox, h2.nav-tab-wrapper' ).prependTo( '#postbox-container-2' );

		// change navigation menu
		$( document ).on( 'change', '.rl-gallery-tab-menu-item', function () {
			var tab = $( this ).closest( '.postbox' ).attr( 'id' ).replace( 'responsive-gallery-', '' ),
				source = $( this ).closest( '.rl-gallery-tab-menu' ),
				container = $( this ).closest( '.inside' ).find( '.rl-gallery-tab-content' ),
				spinner = source.find( '.spinner' ),
				menu_item = $( this ).val();

			// disable nav on ajax
			container.addClass( 'rl-loading-content' );
			source.addClass( 'rl-loading-content' );

			// display spinner
			spinner.fadeIn( 'fast' ).css( 'visibility', 'visible' );

			// post ajax request
			$.post( ajaxurl, {
				action: 'rl-get-menu-content',
				post_id: rlArgsGalleries.post_id,
				tab: tab,
				menu_item: menu_item,
				nonce: rlArgsGalleries.nonce
			} ).done( function ( response ) {
				try {
					if ( response.success ) {
						// replace HTML
						container.html( response.data );

						// enable nav after ajax
						container.removeClass( 'rl-loading-content' );
						source.removeClass( 'rl-loading-content' );

						// update gallery data
						gallery_frame = null;
						gallery_container = $( '.rl-gallery-images' );
						gallery_ids = $( '.rl-gallery-ids' );

						// refresh sortable only for media library
						media_gallery_sortable( gallery_container, gallery_ids, menu_item );

						// color picker
						container.find( '.color-picker' ).wpColorPicker();

						/*
						var click = $( this ),
							type = click.hasClass( 'rl-gallery-update-preview' ) ? 'update' : 'page',
							menu_item = $( '.rl-gallery-tab-menu-images input:checked' ).val(),
							container = $( '.rl-gallery-tab-inside-images-' + menu_item ),
							spinner = click.closest( 'td' ).find( '.rl-gallery-preview-inside .spinner' ),
							query_args = {},
							inside = $( this ).closest( '.inside' ).find( '.rl-gallery-tab-content' );

						// disable nav on ajax
						inside.addClass( 'rl-loading-content' );
						*/

						container.find( 'tr[data-field_type]' ).each( function() {
							var el = $( this ),
								field_name = el.data( 'field_name' ),
								change = false;

							switch ( el.data( 'field_type' ) ) {
								case 'text':
									el.find( 'input' ).on( 'change', function() {
										console.log( 'text' );
									} );
									break;

								case 'number':
									el.find( 'input' ).on( 'change', function() {
										console.log( 'number' );
									} );
									break;

								case 'taxonomy':
									// value = {
										// 'id': parseInt( el.find( 'select option:selected' ).val() ),
										// 'children': el.find( 'input[type="checkbox"]' ).prop( 'checked' )
									// };
									break;

								case 'select':
									el.find( 'select' ).on( 'change', function() {
										console.log( 'select' );
									} );
										value = '';
									break;

								case 'radio':
									// value = el.find( 'input:checked' ).val();
									break;

								case 'multiselect':
									// value = el.find( 'select' ).val();
									break;
							}
						} );
					} else {
						// @todo
					}
				} catch ( e ) {
					// @todo
				}

				// hide spinner
				spinner.fadeOut( 'fast' );
			} ).fail( function () {
				// hide spinner
				spinner.fadeOut( 'fast' );
			} );
		} );

		// change navigation menu
		$( document ).on( 'click', '.nav-tab', function ( e ) {
			e.preventDefault();

			var anchor = $( this ).attr( 'href' ).substr( 1 );

			// remove active class
			$( '.nav-tab' ).removeClass( 'nav-tab-active' );

			// add active class
			$( this ).addClass( 'nav-tab-active' );

			// hide all normal metaboxes
			$( '#postbox-container-2 div[id^="responsive-gallery-"]' ).removeClass( 'rl-display-metabox' ).addClass( 'rl-hide-metabox' );

			// display needed metabox
			if ( anchor === '' ) {
				$( '#responsive-gallery-images' ).addClass( 'rl-display-metabox' ).removeClass( 'rl-hide-metabox' );
			} else {
				$( '#responsive-gallery-' + anchor ).addClass( 'rl-display-metabox' ).removeClass( 'rl-hide-metabox' );
			}

			$( 'input[name="rl_active_tab"]' ).val( anchor );
		} );

		$( '.rl-shortcode' ).on( 'click', function () {
			$( this ).select();
		} );

		// remove image
		$( document ).on( 'click', '.rl-gallery-image-remove', function ( e ) {
			e.preventDefault();

			// prevent featured images being removed
			if ( $( this ).closest( '.rl-gallery-images-featured' ).length === 1 ) {
				return false;
			}

			var li = $( this ).closest( 'li.rl-gallery-image' ),
				attachment_ids = get_current_attachments( gallery_ids );

			// remove id
			attachment_ids = _.without( attachment_ids, parseInt( li.data( 'attachment_id' ) ) );

			// remove attachment
			li.remove();

			// update attachment ids
			gallery_ids.val( $.unique( attachment_ids ).join( ',' ) );

			return false;
		} );

		// edit image
		$( document ).on( 'click', '.rl-gallery-image-edit', function ( e ) {
			e.preventDefault();

			var li = $( this ).closest( 'li.rl-gallery-image' ),
				attachment_id = parseInt( li.data( 'attachment_id' ) ),
				attachment_changed = false;

			// frame already exists?
			if ( attachment_frame !== null ) {
				attachment_frame.detach();
				attachment_frame.dispose();
				attachment_frame = null;
			}

			// create new frame
			attachment_frame = wp.media( {
				id: 'rl-edit-attachment-modal',
				frame: 'select',
				uploader: false,
				multiple: false,
				title: rlArgsGalleries.editTitle,
				library: {
					post__in: attachment_id
				},
				button: {
					text: rlArgsGalleries.buttonEditFile
				}
			} ).on( 'open', function () {
				var attachment = wp.media.attachment( attachment_id ),
					selection = attachment_frame.state().get( 'selection' );

				attachment_frame.$el.closest( '.media-modal' ).addClass( 'rl-edit-modal' );

				// get attachment
				attachment.fetch();

				// reset selection
				//selection.reset();

				// add attachment
				selection.add( attachment );
			} );

			attachment_frame.open();

			return false;
		} );

		// change image status
		$( document ).on( 'click', '.rl-gallery-image-status', function ( e ) {
			e.preventDefault();

			var li = $( this ).closest( 'li.rl-gallery-image' ),
				input = li.find( '.rl-gallery-exclude' ),
				status = li.hasClass( 'rl-status-active' ),
				id = parseInt( li.data( 'attachment_id' ) ),
				item = '';

			if ( id > 0 )
				item = id;
			else
				item = li.find( '.rl-gallery-inner img' ).attr( 'src' );

			// exclude?
			if ( status ) {
				li.addClass( 'rl-status-inactive' ).removeClass( 'rl-status-active' );

				// add item
				input.val( item );
			} else {
				li.addClass( 'rl-status-active' ).removeClass( 'rl-status-inactive' );

				// remove item
				input.val( '' );
			}

			return false;
		} );

		// open the modal on click
		$( document ).on( 'click', '.rl-gallery-select', function ( e ) {
			e.preventDefault();

			// open media frame if already exists
			if ( gallery_frame !== null ) {
				gallery_frame.open();

				return;
			}

			// create the media frame
			gallery_frame = wp.media( {
				title: rlArgsGalleries.textSelectImages,
				multiple: 'add',
				autoSelect: true,
				library: {
					type: 'image'
				},
				button: {
					text: rlArgsGalleries.textUseImages
				}
			} ).on( 'open', function () {
				var selection = gallery_frame.state().get( 'selection' ),
					attachment_ids = get_current_attachments( gallery_ids );

				// deselect all attachments
				selection.reset();

				$.each( attachment_ids, function () {
					// prepare attachment
					attachment = wp.media.attachment( this );

					// select attachment
					selection.add( attachment ? [ attachment ] : [ ] );
				} );
			} ).on( 'select', function () {
				var selection = gallery_frame.state().get( 'selection' ),
					attachment_ids = get_current_attachments( gallery_ids ),
					selected_ids = [ ];

				if ( selection ) {
					selection.map( function ( attachment ) {
						if ( attachment.id ) {
							// add attachment
							selected_ids.push( attachment.id );

							// is image already in gallery?
							if ( $.inArray( attachment.id, attachment_ids ) !== -1 ) {
								return;
							}

							// add attachment
							attachment_ids.push( attachment.id );
							attachment = attachment.toJSON();

							// is preview size available?
							if ( attachment.sizes && attachment.sizes['thumbnail'] ) {
								attachment.url = attachment.sizes['thumbnail'].url;
							}

							// append new image
							gallery_container.append( rlArgsGalleries.mediaItemTemplate.replace( /__IMAGE_ID__/g, attachment.id ).replace( /__IMAGE__/g, '<img width="150" height="150" src="' + attachment.url + '" class="attachment-thumbnail size-thumbnail" alt="" sizes="(max-width: 150px) 100vw, 150px" />' ).replace( /__IMAGE_STATUS__/g, 'rl-status-active' ) );
						}
					} );
				}

				// assign copy of attachment ids
				var copy = attachment_ids;

				for ( var i = 0; i < attachment_ids.length; i++ ) {
					// unselected image?
					if ( $.inArray( attachment_ids[i], selected_ids ) === -1 ) {
						gallery_container.find( 'li.rl-gallery-image[data-attachment_id="' + attachment_ids[i] + '"]' ).remove();

						copy = _.without( copy, attachment_ids[i] );
					}
				}

				gallery_ids.val( $.unique( copy ).join( ',' ) );
			} );

			// open media frame
			gallery_frame.open();
		} );

		// preview pagination
		$( document ).on( 'click', '.rl-gallery-update-preview, .rl-gallery-preview-pagination a', function ( e ) {
			e.preventDefault();

			var click = $( this ),
				type = click.hasClass( 'rl-gallery-update-preview' ) ? 'update' : 'page',
				menu_item = $( '.rl-gallery-tab-menu-images input:checked' ).val(),
				container = $( '.rl-gallery-tab-inside-images-' + menu_item ),
				spinner = click.closest( 'td' ).find( '.rl-gallery-preview-inside .spinner' ),
				query_args = {},
				inside = $( this ).closest( '.inside' ).find( '.rl-gallery-tab-content' );

			// disable nav on ajax
			inside.addClass( 'rl-loading-content' );

			// pagination?
			if ( type === 'page' ) {
				var content = click.attr( 'href' ).match( 'preview_page/\\d+' ),
					page = 1;

				// get valid page number
				if ( content !== null )
					page = content[0].split( '/' )[1];

				query_args['preview_page'] = page;
			}

			container.find( 'tr[data-field_type]' ).each( function() {
				var el = $( this ),
					field_name = el.data( 'field_name' ),
					value = null;

				switch ( el.data( 'field_type' ) ) {
					case 'text':
						value = el.find( 'input' ).val();

						if ( ! value )
							value = '';
						break;

					case 'number':
						value = parseInt( el.find( 'input' ).val() );

						if ( ! value )
							value = 0;
						break;

					case 'taxonomy':
						value = {
							'id': parseInt( el.find( 'select option:selected' ).val() ),
							'children': el.find( 'input[type="checkbox"]' ).prop( 'checked' )
						};

						if ( ! value )
							value = {
								'id': 0,
								'children': false
							};
						break;

					case 'select':
						value = el.find( 'select option:selected' ).val();

						if ( ! value )
							value = '';
						break;

					case 'radio':
						value = el.find( 'input:checked' ).val();

						if ( ! value )
							value = '';
						break;

					case 'multiselect':
						value = el.find( 'select' ).val();

						if ( ! value )
							value = [];
						break;

					case 'hidden':
						var subel = el.find( 'input[type="hidden"]' ),
							nofa = parseInt( subel.data( 'subarg' ) ),
							val = el.find( 'input[type="hidden"]' ).val();

						if ( nofa > 0 ) {
							var atts = subel.attr( 'name' ).slice( 0, -1 ).split( '][' ),
								new_value = {},
								last;

							for ( var i = atts.length; i > atts.length - nofa; i-- ) {
								var number = i - 1;

								// first element?
								if ( i === atts.length )
									new_value[atts[number]] = val;
								else
									new_value[atts[number]] = last;

								// remember last array
								last = new_value;

								// do not reset for last element
								if ( number > atts.length - nofa )
									new_value = {};
							}

							// get new array
							value = new_value;

							// save wikimedia aicontinue for pages
							if ( ! ( query_args['preview_page'] in wikimedia ) )
								wikimedia[query_args['preview_page']] = value.wikimedia.continue;

							value.wikimedia.continue = wikimedia[query_args['preview_page']];
						} else
							value = val;
						break;
				}

				query_args[field_name] = value;
			} );

			// display spinner
			spinner.fadeIn( 'fast' ).css( 'visibility', 'visible' );

			// post ajax request
			$.post( ajaxurl, {
				action: 'rl-get-preview-content',
				post_id: rlArgsGalleries.post_id,
				menu_item: menu_item,
				query: query_args,
				preview_type: type,
				excluded: $( '.rl-gallery-exclude' ).map( function( i, elem ) { return $( elem ).val(); } ).get(),
				nonce: rlArgsGalleries.nonce
			} ).done( function ( response ) {
				try {
					if ( response.success ) {
						container.find( 'tr[data-field_type]' ).each( function() {
							var el = $( this ),
								field_name = el.data( 'field_name' ),
								value = null;

							switch ( el.data( 'field_type' ) ) {
								case 'hidden':
									$( '#rl_images_remote_library_response_data_wikimedia_continue' ).val( response.data.data.wikimedia.continue );

									var next_page = parseInt( query_args['preview_page'] ) + 1;

									// save wikimedia aicontinue for pages
									if ( ! ( next_page in wikimedia ) )
										wikimedia[next_page] = response.data.data.wikimedia.continue;
/*
									var subel = el.find( 'input[type="hidden"]' ),
										nofa = parseInt( subel.data( 'subarg' ) ),
										val = el.find( 'input[type="hidden"]' ).val();

									if ( nofa > 0 ) {
										var atts = subel.attr( 'name' ).slice( 0, -1 ).split( '][' ),
											new_value = {},
											last;

										for ( var i = atts.length - nofa; i <= atts.length; i++ ) {
											var number = i - 1;

											// first element?
											if ( i === atts.length ) {
												// new_value2 .= '[' + atts[number] + ']';
												new_value = response.data.data[atts[number]];
											} else {
												last = new_value[atts[number]];
											}

											// remember last array
											new_value = last;

											// do not reset for last element
											// if ( number > atts.length - nofa )
												// new_value = {};
										}

										// get new array
										value = new_value;
									} else
										subel.val( response.data.data );
									break;
*/
							}
						} );

						$( '.rl-gallery-images' ).empty().append( response.data.images );

						if ( type === 'page' )
							$( '.rl-gallery-preview-pagination' ).replaceWith( response.data.pagination );
					} else {
						// @todo
					}
				} catch ( e ) {
					// @todo
				}
			} ).always( function () {
				// hide spinner
				spinner.fadeOut( 'fast' );

				// enable content
				inside.removeClass( 'rl-loading-content' );
			} );

			return false;
		} );

		// load values for specified rule
		$( document ).on( 'change', '.rl-rule-type', function () {
			var _this = $( this ),
				td = _this.closest( 'tr' ).find( 'td.value' ),
				select = td.find( 'select' ),
				spinner = td.find( '.spinner' );

			select.hide();
			spinner.fadeIn( 'fast' ).css( 'visibility', 'visible' );

			$.post( ajaxurl, {
				action: 'rl-get-group-rules-values',
				type: _this.val(),
				nonce: rlArgsGalleries.nonce
			} ).done( function ( data ) {
				spinner.hide();

				try {
					var response = JSON.parse( data );

					// remove old select options and adds new ones
					select.fadeIn( 'fast' ).find( 'option, optgroup' ).remove().end().append( response.select );
				} catch ( e ) {
					//
				}
			} ).fail( function () {
				//
			} );
		} );
	} );

	// listen for insert/remove media library thumbnail
	$( document ).on( 'DOMNodeInserted', '#postimagediv .inside', function ( e ) {
		var value = $( '#postimagediv .inside' ).attr( 'data-featured-type' );

		if ( $( '#rl-gallery-featured-' + value ).length > 0 ) {
			$( '#rl-gallery-featured-' + value ).prop( 'checked', true );

			$( '#postimagediv .inside' ).trigger( 'change' );
		}
	} );

	// handle featured image change
	$( document ).on( 'change', '#postimagediv .inside', function () {
		var el = $( this ).find( 'input[name="rl_gallery_featured_image"]:checked' ),
			value = $( el ).val();

		$( '#postimagediv .inside' ).attr( 'data-featured-type', value );
		$( '.rl-gallery-featured-image-select' ).children( 'div' ).hide();
		$( '.rl-gallery-featured-image-select-' + value ).show();

		// media library
		if ( value === 'id' ) {
			var thumbnail_id = parseInt( $( '#_thumbnail_id' ).attr( 'data-featured-id' ) );
			if ( thumbnail_id > 0 ) {
				$( '#_thumbnail_id' ).val( thumbnail_id ).attr( 'data-featured-id', -1 );
			}
			// custom URL
		} else if ( value === 'url' ) {
			var thumbnail_id = parseInt( $( '#_thumbnail_id' ).val() );
			if ( thumbnail_id > 0 ) {
				$( '#_thumbnail_id' ).attr( 'data-featured-id', thumbnail_id ).val( -1 );
			}
			// first gallery image
		} else {
			var thumbnail_id = parseInt( $( '#_thumbnail_id' ).val() );
			if ( thumbnail_id > 0 ) {
				$( '#_thumbnail_id' ).attr( 'data-featured-id', thumbnail_id ).val( -1 );
			}
		}
	} );

	$( document ).on( 'ready ajaxComplete', function () {
		// init select2
		$( '.rl-gallery-tab-inside select.select2' ).select2( {
			closeOnSelect: true,
			multiple: true,
			width: 300,
			minimumInputLength: 0
		} );
	} );

	function createSubValue( value, new_value, arg ) {
		value = new_value;
		// value = value[arg];

		return value
	}

	// get attachment ids
	function get_current_attachments( gallery_ids ) {
		var attachments = gallery_ids.val();

		// return integer image ids or empty array
		return attachments !== '' ? attachments.split( ',' ).map( function ( i ) {
			return parseInt( i )
		} ) : [];
	}

	// 
	function media_gallery_sortable( gallery, ids, type ) {
		if ( type === 'media' ) {
			// images order
			gallery.sortable( {
				items: 'li.rl-gallery-image',
				cursor: 'move',
				scrollSensitivity: 40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'rl-gallery-sortable-placeholder',
				start: function ( event, ui ) {
					ui.item.css( 'border-color', '#f6f6f6' );
				},
				stop: function ( event, ui ) {
					ui.item.removeAttr( 'style' );
				},
				update: function ( event, ui ) {
					var attachment_ids = [ ];

					gallery.find( 'li.rl-gallery-image' ).each( function () {
						attachment_ids.push( parseInt( $( this ).attr( 'data-attachment_id' ) ) );
					} );

					ids.val( $.unique( attachment_ids ).join( ',' ) );
				}
			} );
		}
	}

} )( jQuery );