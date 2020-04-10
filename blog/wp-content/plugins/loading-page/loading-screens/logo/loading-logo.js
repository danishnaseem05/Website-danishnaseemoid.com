var cp_loadingpage = cp_loadingpage || {};
cp_loadingpage.graphics = cp_loadingpage.graphics || {};

cp_loadingpage.graphics['logo'] = {
	created: false,
	attr   : {percentage:0},
	create : function(opt){
		opt.backgroundColor = opt.backgroundColor || "#000000";
		opt.foregroundColor = opt.foregroundColor || "#FFFFFF";

		this.attr['foreground'] = opt.foregroundColor;
		var css_o = {
			width: "100%",
			height: "100%",
			backgroundColor: opt.backgroundColor,
			position: "fixed",
			zIndex: 666999,
			top: 0,
			left: 0
		};

		if( opt[ 'backgroundImage' ] ){
			css_o['backgroundImage']  = 'url('+opt[ 'backgroundImage' ]+')';
			css_o['background-repeat'] = opt[ 'backgroundRepeat' ];
			css_o['background-position'] = 'center center';

			if(
				css_o['background-repeat'].toLowerCase() == 'no-repeat' &&
				typeof opt['fullscreen'] !== 'undefined' &&
				opt['fullscreen']*1 == 1
			)
			{
				css_o[ "background-attachment" ] = "fixed";
				css_o[ "-webkit-background-size" ] = "contain";
				css_o[ "-moz-background-size" ] = "contain";
				css_o[ "-o-background-size" ] = "contain";
				css_o[ "background-size" ] = "contain";
			}
		}

		this.attr['overlay'] = jQuery("<div class='lp-screen'></div>").css(css_o).appendTo("html");

		if (opt.text) {
			this.attr['text'] = jQuery("<div class='lp-screen-text'></div>").text("0%").css({
				lineHeight: "40px",
				height: "40px",
				width: "100px",
				position: "absolute",
				fontSize: "30px",
				top: this.attr['overlay'].height()/2,
				left: this.attr['overlay'].width()/2-50,
				textAlign: "center",
				color: opt.foregroundColor
			}).appendTo(this.attr['overlay']);
		}

		if(
			typeof opt[ 'lp_ls' ]  != 'undefined'
		)
		{
			if(
				typeof opt[ 'lp_ls' ][ 'logo' ]  != 'undefined'  &&
				typeof opt[ 'lp_ls' ][ 'logo' ][ 'image' ]  != 'undefined'  &&
				!/^\s*$/.test( opt[ 'lp_ls' ][ 'logo' ][ 'image' ]  )
			)
			{
				var me 	= this,
					wrapper = jQuery('<span style="width:120px;position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);display: inline-block;"></span>'),
					img_url = jQuery.trim( opt[ 'lp_ls' ][ 'logo' ][ 'image' ] ),
					img = jQuery('<img src="'+img_url+'"  style="cursor:pointer;width:120px;" />');
				img.on('click',cp_loadingpage.destroyLoader);
				wrapper.append(img).appendTo( me.attr[ 'overlay' ] );
				if(me.attr[ 'text' ])
					wrapper.append(me.attr[ 'text' ].css({'position':'relative', 'top':'auto', 'left':'auto', 'width':'100%', 'margin-top':'20px'}));
			}
		}
		this.set(0);
		this.created = true;
	},

	set : function(percentage){
		this.attr['percentage'] = percentage;
		if (this.attr['text']) {
			this.attr['text'].text(Math.ceil(percentage) + "%");
		}
		jQuery( '#lp_ls_cover' ).css( { 'width': (100-percentage)+'%', 'right':0 } );
	},

	complete : function(callback){
		callback();
		var me = this;
		this.attr['overlay'].fadeOut(200, function () {
			me.attr['overlay'].remove();
		});
	}
};
