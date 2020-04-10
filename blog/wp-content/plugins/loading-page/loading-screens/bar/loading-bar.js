var cp_loadingpage = cp_loadingpage || {};
cp_loadingpage.graphics = cp_loadingpage.graphics || {};

cp_loadingpage.graphics['bar'] = {
	created: false,
	attr   : {},
	create : function(opt){
		opt.backgroundColor = opt.backgroundColor || "#000000";
		opt.height          = opt.height || 1;
		opt.foregroundColor = opt.foregroundColor || "#FFFFFF";

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

		this.attr['bar'] = jQuery("<div class='lp-screen-graphic'></div>").css({
			height: opt.height+"px",
			marginTop: "-" + (opt.height / 2) + "px",
			backgroundColor: opt.foregroundColor,
			width: "0%",
			position: "absolute",
			top: "50%"
		}).appendTo(this.attr['overlay']);

		if (opt.text) {
			this.attr['text'] = jQuery("<div class='lp-screen-text'></div>").text("0%").css({
				height: "40px",
				width: "100px",
				position: "absolute",
				fontSize: "3em",
				top: "50%",
				left: "50%",
				marginTop: "-" + (59 + opt.height) + "px",
				textAlign: "center",
				marginLeft: "-50px",
				color: opt.foregroundColor
			}).appendTo(this.attr['overlay']);
		}

		this.created = true;
	},

	set : function(percentage){
		this.attr['bar'].stop().animate({
			width: percentage + "%",
			minWidth: percentage + "%"
		}, 200);

		if (this.attr['text']) {
			this.attr['text'].text(Math.ceil(percentage) + "%");
		}
	},

	complete : function(callback){
		callback();
		var me = this;
		this.attr['overlay'].fadeOut(500, function () {
			me.attr['overlay'].remove();
		});
	}
};