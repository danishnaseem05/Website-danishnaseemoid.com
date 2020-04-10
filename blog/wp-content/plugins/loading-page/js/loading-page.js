var cp_loadingpage = cp_loadingpage || {};

(function(){
	var lp = cp_loadingpage, // cp_loadingpage shortcut
		default_options = { // Default options

			// Options for lazy load
			threshold: 100,
			effect: "show",
			effectspeed: 0,

			// Options for loading page
			codeblock : '',
			loadingScreen: true,
			removeInOnLoad: false,
			graphic : 'bar',
			backgroundColor: "#000",
			foregroundColor: "#fff",
			text: true,
			deepSearch: false,
			modifyDisplayRule: 0,
			pageEffect: "none",

			// callback for loading page complete
			onComplete: function () {
				if(typeof window['afterCompleteLoadingScreen'] != 'undefined') window['afterCompleteLoadingScreen']();
			}
		},
		images = [],
		done = 0,
        destroyed = false,
        imageCounter = 0;

	// Auxiliary methods
	lp['extend'] = function(a, b)
	{
		for(var key in b)
			if(b.hasOwnProperty(key))
				a[key] = b[key];
		return a;
	};

	lp['validateScreenSize'] = function()
	{
		var o = lp.options;
		if(!('screen_size' in o) || o['screen_size'] == 'all' || !('screen_width' in o)) return true;
		var screen_width = parseFloat(o['screen_width']);
		if( isNaN(screen_width) || !isFinite(screen_width)) return true;
		var w  = window,
			d  = document,
			e  = d.documentElement,
			sw = w.innerWidth || e.clientWidth;
		return ((o['screen_size'] == 'greater' && screen_width <= sw) || (o['screen_size'] == 'lesser' && sw <= screen_width));
	};

	lp['imgLoaded'] = function(img)
	{
		return img.complete && img.naturalHeight !== 0;
	};

	lp['displayBody'] = function()
	{
		jQuery('html').append('<style>body{'+((lp.options['modifyDisplayRule']*1) ? 'display:block' : 'visibility:visible')+';}</style>');
	};

	// Methods used in lazy load
    lp['loadOriginalImg'] = function()
	{
		jQuery( 'body' ).find('.lp-lazy-load').each(function (){
			var e = jQuery(this),
				src = e.attr('data-src'),
				srcset = e.attr('data-srcset');

			e.removeClass('lp-lazy-load');
			if( typeof src != 'undefined' ) e.attr( 'src', src );
			if( typeof srcset != 'undefined' ) e.attr( 'srcset', src );
		});
    };

	// Methods used in loading page
    lp['graphicAction'] = function(action, params)
	{
        if(
			'graphic' in lp.options &&
			'graphics' in lp &&
			lp.options.graphic in lp.graphics &&
			lp.graphics[lp.options.graphic].created
		)
        {
            lp.graphics[lp.options.graphic][action](params);
        }
    };

	lp['ApplyAnimationToElement'] = function(animName)
	{
		jQuery('body').addClass('lp-'+animName);
    };

    lp['onLoadComplete'] = function()
	{
		var time = (typeof lp_close_btn == 'undefined' && 'additionalSeconds' in lp.options && jQuery.isNumeric( lp.options[ 'additionalSeconds' ] ) ) ? parseInt(lp.options[ 'additionalSeconds' ]) : 0,
			complete = function(){
				jQuery('#loading_page_codeBlock,.lp-close-screen').remove();
				lp.graphicAction( 'complete', function(){
					lp.ApplyAnimationToElement(lp.options.pageEffect);
					lp.options.onComplete();
				} );
			};

		if(time) setTimeout(function(){complete();}, time*1000);
		else complete();
    };

	lp['setLoadImgEvents'] = function()
	{
		var wh = jQuery(window).height();
        for (var i = 0, h = images.length; i<h; i++)
		{
			if(!lp.imgLoaded(images[i]))
			{
				var img = jQuery(images[i]);
				if(lp.options['deepSearch'] || img.offset().top < wh)
				{
					imageCounter++;
					img.on('load', function(){lp.completeImageLoading();});
				}
			}
        }
		if(imageCounter == 0) this.completeImageLoading();
    };

    lp['completeImageLoading'] = function ()
	{
        done++;
        var percentage = (imageCounter) ? Math.min(done / imageCounter * 100, 100) : 100;
        lp.graphicAction( 'set', percentage );

        if (imageCounter <= done  && !(lp.options['removeInOnLoad']*1))
        {
            lp.destroyLoader();
        }
    };

    lp['destroyLoader'] = function ()
	{
		if( lp.options['loadingScreen'] && !destroyed )
		{
			lp.displayBody();
			var percentage = Math.min( (imageCounter) ? done / imageCounter * 100 : 0, 100),
				increaser = function()
				{
					lp.graphicAction( 'set', percentage );
					if(percentage == 100)
					{
						lp.onLoadComplete();
					}
					else
					{
						percentage = Math.min(percentage+5, 100)
						setTimeout(increaser, 0);
					}
				};
			destroyed = true;
			lp.loadOriginalImg();
			increaser();
		}
    };

	lp['loadingPage'] = function()
	{
		lp.displayBody();
		images = jQuery('body').find('img:not(.lp-lazy-applied)');
		if( images.length ) lp.setLoadImgEvents();
	};

	// Main Code
	if( typeof loading_page_settings != 'undefined' )
	{
		lp.options = lp.extend(default_options, loading_page_settings);
	}
	else
	{
		lp.options = default_options;
	}
	lp.options[ 'text' ] *= 1;
	lp.options['loadingScreen'] *= 1;

	if(lp.options['loadingScreen'] && lp.validateScreenSize())
	{
		if(lp.options['closeBtn'])
		{
			var close_btn = jQuery('<span class="lp-close-screen">X</span>');
			close_btn.click(function(){lp_close_btn = true; if(!destroyed) lp.destroyLoader(); else lp.onLoadComplete();});
			jQuery('html').append(close_btn);
		}
		if(lp.options['codeblock'] && lp.options['codeblock'].length) jQuery('html').append(lp.options['codeblock']);
		if( ( typeof lp.graphics != 'undefined' ) && ( typeof lp.graphics[lp.options.graphic] != 'undefined' ) )
		{
			lp.graphics[lp.options.graphic].create(lp.options);
			var lpCodeBlock = document.getElementById('loading_page_codeBlock');
			if(lpCodeBlock) lpCodeBlock.style.display='block';
		}
	}
	else{
		lp.displayBody();
		lp.options['loadingScreen'] = 0;
	}
})();

// Ready and Load event
jQuery(function () { if(cp_loadingpage.options['loadingScreen']) cp_loadingpage.loadingPage();});
jQuery(window).on('load', function(){ cp_loadingpage.loadOriginalImg(); cp_loadingpage.destroyLoader(); });