( function ( $ ) {

	$( document ).on( 'ready', function() {
		var gutenberg_active = typeof rlBlockEditor !== 'undefined';

		if ( gutenberg_active ) {
			RLWPMediaViewMediaFramePostTrigger = wp.media.view.MediaFrame.Post.prototype.trigger;

			// extend MediaFrame.Post
			wp.media.view.MediaFrame.Post.prototype.trigger = function( action ) {
				RLWPMediaViewMediaFramePostTrigger.apply( this, Array.prototype.slice.call( arguments ) );

				if ( action === 'open' ) {
					if ( $( this.modal.clickedOpenerEl ).hasClass( 'rl-remote-library-media-button' ) )
						this.setState( 'rl-remote-library' );
					else if ( $( this.modal.clickedOpenerEl ).hasClass( 'rl-gallery-media-button' ) )
						this.setState( 'rl-gallery' );
				}
			}
		}

		// add new media folder filter
		var RLWPMediaViewAttachmentFilters = wp.media.view.AttachmentFilters.extend( {
			id: 'media-attachment-rl-remote-library-filters',
			className: 'attachment-filters attachment-rl-remote-library-filter',
			createFilters: function() {
				var filters = {
					all: {
						text: rlRemoteLibraryMedia.allProviders,
						priority: 1,
						props: {
							media_provider: 'all'
						}
					}
				};

				// add active providers
				for ( var i = 0; i < rlRemoteLibraryMedia.providersActive.length; i++ ) {
					var provider = rlRemoteLibraryMedia.providersActive[i];

					filters[provider] = {
						text: rlRemoteLibraryMedia.providers[provider].name,
						priority: i + 2,
						props: {
							media_provider: provider
						}
					}
				}

				this.filters = filters;
			}
		} );

		var RLWPMediaViewAttachmentsBrowser = wp.media.view.AttachmentsBrowser;

		wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend( {
			createToolbar: function() {
				// load the original toolbar
				RLWPMediaViewAttachmentsBrowser.prototype.createToolbar.call( this );

				if ( this.model.get( 'id' ) === 'rl-remote-library' ) {
					this.toolbar.set( 'RLremoteLibraryFilterLabel', new wp.media.view.Label( {
						value: rlRemoteLibraryMedia.filterByremoteLibrary,
						attributes: {
							'for': 'media-attachment-rl-remote-library-filters'
						},
						priority: -75
					} ).render() );

					this.toolbar.set( 'RLremoteLibraryAttachmentFilters', new RLWPMediaViewAttachmentFilters( {
						controller: this.controller,
						model: this.collection.props,
						priority: -75
					} ).render() );
				}
			}
		} );

		var RLRemoteLibraryCollection,
			RLRemoteLibraryContentView,
			RLWPMediaViewMediaFramePost = wp.media.view.MediaFrame.Post,
			attachment_defaults = {
				width: 0,
				height: 0
			};

		// add new attributes
		RLWPMediaViewMediaFramePost.currentAttachment = attachment_defaults;
		RLWPMediaViewMediaFramePost.remoteLibraryImage = false;
		RLWPMediaViewMediaFramePost.requestHash = '';

		// extend media frame
		wp.media.view.MediaFrame.Post = RLWPMediaViewMediaFramePost.extend( {
			initialize: function() {
				// calling the initalize method from the current frame before adding new functionality
				RLWPMediaViewMediaFramePost.prototype.initialize.apply( this, arguments );

				// adding new state for remote library image
				this.states.add( [
					new wp.media.controller.Library( {
						id: 'rl-remote-library',
						title: 'Remote Library',
						priority: 99,
						toolbar: gutenberg_active ? 'select' : 'main-insert',
						multiple: false,
						editable: true,
						allowLocalEdits: true,
						library: new wp.media.model.Attachments(),
						displaySettings: true,
						displayUserSettings: true,
						filterable: true,
						searchable: true,
						content: 'browse',
						router: false,
						date: false,
						sortable: false,
						type: 'image',
						dragInfo: false,
						menu: gutenberg_active ? false : 'default'
					} )
				] );

				var RLWPMediaEditorSendAttachment = wp.media.editor.send.attachment;

				// replace send attachment
				wp.media.editor.send.attachment = function( props, attachment ) {
					// remote library simulated attachment?
					if ( typeof attachment.remote_library_image !== 'undefined' && attachment.remote_library_image === true ) {
						RLWPMediaViewMediaFramePost.remoteLibraryImage = true;

						if ( props.size === 'thumbnail' ) {
							RLWPMediaViewMediaFramePost.currentAttachment.width = attachment.thumbnail_width;
							RLWPMediaViewMediaFramePost.currentAttachment.height = attachment.thumbnail_height;
						} else {
							RLWPMediaViewMediaFramePost.currentAttachment.width = attachment.width;
							RLWPMediaViewMediaFramePost.currentAttachment.height = attachment.height;
						}
					}

					// return original function
					return RLWPMediaEditorSendAttachment( props, attachment );
				}

				var RLWPMediaPost = wp.media.post;

				// replace ajax request
				wp.media.post = function( action, data ) {
					// send attachment to editor action?
					if ( action === 'send-attachment-to-editor' && RLWPMediaViewMediaFramePost.remoteLibraryImage === true ) {
						var attachmentID = data.attachment.id;

						// replace pseudo ID with generated thumbnial ID
						data.attachment.id = parseInt( rlRemoteLibraryMedia.thumbnailID );

						// set pseudo ID too
						data.attachment.att_id = attachmentID;

						// select this image as remote library one
						data.attachment.remote_library_image = true;

						// set new dimensions
						data.attachment.width = RLWPMediaViewMediaFramePost.currentAttachment.width;
						data.attachment.height = RLWPMediaViewMediaFramePost.currentAttachment.height;

						// back to defaults
						RLWPMediaViewMediaFramePost.currentAttachment = attachment_defaults;

						// restore defaulkt behavior
						RLWPMediaViewMediaFramePost.remoteLibraryImage = false;
					}

					// return original function
					return RLWPMediaPost( action, data );
				}

				// events
				this.on( 'activate', this.activateContent, this );
			},
			activateContent: function() {
				// get view content
				var view = this.content.get();

				// valid remote library view?
				if ( view !== null && 'model' in view && view.model.id === 'rl-remote-library' ) {
					var contentState = this.state(),
						contentSelection = contentState.get( 'selection' );

					// clear selection
					contentSelection.reset();

					var toolbar = this.toolbar.get(),
						controller = this,
						spinner = view.toolbar.get( 'spinner' );

					// display spinner
					spinner.$el.css( 'marginLeft', '6px' );
					spinner.show();

					// hide uploader view
					view.$el.find( '.uploader-inline' ).addClass( 'hidden' );

					this.selectionStatusToolbar( toolbar );

					// add upload button
					toolbar.set( 'rl-upload-insert', {
						style: 'primary',
						priority: 20,
						text: gutenberg_active ? rlRemoteLibraryMedia.uploadAndSelect : rlRemoteLibraryMedia.uploadAndInsert,
						requires: { selection: true },
						click: function() {
							var state = controller.state(),
								selection = state.get( 'selection' ),
								image = selection.single(),
								content = controller.content.get(),
								attachment = content.attachments.$el.find( 'li[data-id="' + image.attributes.id + '"] .thumbnail' ),
								attachment_image = attachment.find( '.centered' );

							attachment_image.css( { opacity: 0.1, transition: 'opacity 500ms' } );
							attachment_image.after( '<div class="media-progress-bar"><div style="width: 20%"></div></div>' );

							var progress = attachment.find( '.media-progress-bar div' ),
								transition = progress.css( 'transition' );

							progress.css( 'transition', 'width 10s' ).animate( { width: "100%" }, 0 );

							$.post( ajaxurl, {
								action: 'rl_upload_image',
								image: image.attributes,
								post_id: rlRemoteLibraryMedia.postID,
								rlnonce: rlRemoteLibraryMedia.getUploadNonce
							} ).done( function( response ) {
								progress.css( 'transition', 'width 0.5s' ).animate( { width: "100%" }, 0, function() {
									attachment_image.css( { opacity: 1, transition: '' } );

									$( this ).css( 'transition', transition );

									// update attachment data
									selection.models[0].attributes.id = parseInt( response.id );
									selection.models[0].attributes.url = response.full[0];
									selection.models[0].attributes.sizes.full.url = response.full[0];

									// remove progress bar
									progress.remove();

									// close modal
									controller.close();

									// trigger insert event
									state.trigger( ( gutenberg_active ? 'select' : 'insert' ), selection ).reset();
								} );
							} ).always( function( data ) {
								//
							} );
						}
					} );

					RLRemoteLibraryContentView = view;
					RLRemoteLibraryContentView.blockScrolling = false;
					RLRemoteLibraryContentView.responseData = [];

					var model = view.model.collection.get( 'rl-remote-library' );

					// set scroll event
					this.handleScroll = _.chain( this.handleScroll ).bind( view ).throttle( wp.media.isTouchDevice ? 300 : 200 ).value();

					// bind scroll event
					view.attachments.$el.on( 'scroll', this.handleScroll );

					// assign model
					RLRemoteLibraryCollection = model;

					// run ajax calls for all providers
					var promise = remoteQuery( 'all', '', 1, [] );

					promise.then(
						result => {
							// any results?
							if ( result.images.length ) {
								// add images to library
								model.attributes.library.push( result.images );

								// increase page number
								RLWPMediaModelAttachments.media_page++;

								RLRemoteLibraryContentView.blockScrolling = false;
								RLRemoteLibraryContentView.responseData = result.data;

								// last page?
								if ( result.last === false )
									this.handleScroll();
							}

							// hide spinner
							view.toolbar.get( 'spinner' ).hide();
						},
						error => {
							RLRemoteLibraryContentView.blockScrolling = false;
							RLRemoteLibraryContentView.responseData = [];
						}
					);
				}
			},
			handleScroll: function() {
				// is another scrolling pending?
				if ( RLRemoteLibraryContentView.blockScrolling )
					return;

				var view = this.views.parent,
					el = this.attachments.el,
					scrollTop = el.scrollTop;

				// the scroll event occurs on the document, but the element that should be checked is the document body
				if ( el === document ) {
					el = document.body;
					scrollTop = $( document ).scrollTop();
				}

				if ( ! $( el ).is( ':visible' ) )
					return;

				// get content view
				var content = view.content.get();

				// show the spinner only if we are close to the bottom.
				if ( el.scrollHeight - ( scrollTop + el.clientHeight ) < el.clientHeight / 3 )
					content.toolbar.get( 'spinner' ).show();

				if ( el.scrollHeight < scrollTop + ( el.clientHeight * 3 ) ) {
					RLRemoteLibraryContentView.blockScrolling = true;

					// display spinner
					content.toolbar.get( 'spinner' ).show();

					var promise = remoteQuery( RLWPMediaModelAttachments.media_provider, RLWPMediaModelAttachments.media_search, RLWPMediaModelAttachments.media_page, RLRemoteLibraryContentView.responseData );

					promise.then(
						result => {
							// any results?
							if ( result.images.length ) {
								// add images to library
								RLRemoteLibraryCollection.attributes.library.push( result.images );

								// increase page number
								RLWPMediaModelAttachments.media_page++;

								RLRemoteLibraryContentView.blockScrolling = false;
								RLRemoteLibraryContentView.responseData = result.data;

								// last page?
								if ( result.last === false )
									view.handleScroll( result.data );
							}

							// hide spinner
							content.toolbar.get( 'spinner' ).hide();
						},
						error => {
							RLRemoteLibraryContentView.blockScrolling = false;
							RLRemoteLibraryContentView.responseData = [];
						}
					);
				}
			}
		} );

		var RLWPMediaViewSettingsAttachmentDisplay = wp.media.view.Settings.AttachmentDisplay;

		wp.media.view.Settings.AttachmentDisplay = wp.media.view.Settings.AttachmentDisplay.extend( {
			render: function() {
				// remove medium size
				if ( typeof this.options.attachment.attributes.remote_library_image !== 'undefined' && this.options.attachment.attributes.remote_library_image )
					delete this.options.attachment.attributes.sizes.medium;

				// load the original render function
				RLWPMediaViewSettingsAttachmentDisplay.prototype.render.call( this );

				return this;
			}
		} );

		var RLWPMediaModelAttachments = wp.media.model.Attachments;

		// add new attributes
		RLWPMediaModelAttachments.media_page = 1;
		RLWPMediaModelAttachments.media_provider = 'all';
		RLWPMediaModelAttachments.media_search = '';

		// extend media frame
		wp.media.model.Attachments = RLWPMediaModelAttachments.extend( {
			initialize: function() {
				// calling the initalize method from the current frame before adding new functionality
				RLWPMediaModelAttachments.prototype.initialize.apply( this, arguments );

				// events
				this.props.on( 'change', this.handleFilters );
			},
			handleFilters: function() {
				// clear current collection
				RLRemoteLibraryCollection.attributes.library.reset();

				// clear current selection
				RLRemoteLibraryCollection.get( 'selection' ).reset();

				// hide uploader view
				RLRemoteLibraryContentView.$el.find( '.uploader-inline' ).addClass( 'hidden' );

				// reset page to first
				RLWPMediaModelAttachments.media_page = 1;

				// clear response data
				RLRemoteLibraryContentView.responseData = [];

				// make sure media provider is set
				if ( typeof this.attributes.media_provider === 'undefined' )
					RLWPMediaModelAttachments.media_provider = this.attributes.media_provider = 'all';
				else
					RLWPMediaModelAttachments.media_provider = this.attributes.media_provider;

				// make sure search phrase is set
				if ( typeof this.attributes.search === 'undefined' )
					RLWPMediaModelAttachments.media_search = this.attributes.search = '';
				else
					RLWPMediaModelAttachments.media_search = this.attributes.search;

				// disable scroll event
				RLRemoteLibraryContentView.blockScrolling = true;

				// display spinner
				RLRemoteLibraryContentView.toolbar.get( 'spinner' ).show();

				var promise = remoteQuery( this.attributes.media_provider, this.attributes.search, RLWPMediaModelAttachments.media_page, RLRemoteLibraryContentView.responseData );

				promise.then(
					result => {
						// any results?
						if ( result.images.length ) {
							// add images to library
							RLRemoteLibraryCollection.attributes.library.push( result.images );

							// increase page number
							RLWPMediaModelAttachments.media_page++;

							// allow scrolling
							RLRemoteLibraryContentView.blockScrolling = false;
							RLRemoteLibraryContentView.responseData = result.data;

							// last page?
							if ( result.last === false )
								RLRemoteLibraryContentView.views.parent.handleScroll();
						}

						// hide spinner
						RLRemoteLibraryContentView.toolbar.get( 'spinner' ).hide();
					},
					error => {
						RLRemoteLibraryContentView.blockScrolling = false;
						RLRemoteLibraryContentView.responseData = [];
					}
				);
			}
		} );

		function remoteQuery( provider, phrase, page, response_data ) {
			var promise = new Promise( ( resolve, reject ) => {
				// set current request phrase
				RLWPMediaViewMediaFramePost.requestHash = 'provider:' + provider + '|phrase:' + phrase;

				$.post( ajaxurl, {
					action: 'rl_remote_library_query',
					media_provider: provider,
					media_search: phrase,
					media_page: page,
					response_data: response_data
				} ).done( function( response ) {
					// valid request hash?
					if ( RLWPMediaViewMediaFramePost.requestHash === 'provider:' + provider + '|phrase:' + phrase )
						resolve( response );
					else
						reject( [] );
				} ).fail( function() {
					reject( [] );
				} );
			} );

			return promise;
		}
	} );

} )( jQuery );