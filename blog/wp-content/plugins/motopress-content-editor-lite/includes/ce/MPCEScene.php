<?php

class MPCEScene {
	private static $_instance;

	private function __clone(){}
	private function __wakeup(){}

	protected function __construct() {
		add_action('template_redirect', array($this, 'init'));
	}

	public static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function init() {

		if (!mpceIsEditorScene()) {
			return;
		}

		add_filter('show_admin_bar', '__return_false');
	}

	/**
	 * @return bool Is use migrate
	 */
	private function fixJQuery(){
		global $wp_scripts;
		$migrate = false;
		if ( version_compare( $wp_scripts->registered['jquery']->ver, MPCERequirements::MIN_JQUERY_VER, '<' ) ) {
			$wpjQueryVer = motopressCEGetWPScriptVer( 'jQuery' );
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', includes_url( 'js/jquery/jquery.js' ), array(), $wpjQueryVer );
			wp_enqueue_script( 'jquery' );

			if ( version_compare( $wpjQueryVer, '1.9.0', '>' ) ) {
				if ( wp_script_is( 'jquery-migrate', 'registered' ) ) {
					wp_enqueue_script( 'jquery-migrate', array( 'jquery' ) );
					$migrate = true;
				}
			}
		}
		return $migrate;
	}

	/**
	 * @param bool $migrate
	 */
	public function fixNoConflict( $migrate ){
		global $wp_scripts;
		$jQueryOffset      = array_search( 'jquery', $wp_scripts->queue ) + 1;
		$index             = ( $migrate ) ? array_search( 'jquery-migrate', $wp_scripts->queue ) : array_search( 'mpce-no-conflict', $wp_scripts->queue );
		$length            = $index - $jQueryOffset;
		$slice             = array_splice( $wp_scripts->queue, $jQueryOffset, $length );
		$wp_scripts->queue = array_merge( $wp_scripts->queue, $slice );
	}

	public function fixMPSliderScripts(){
		/**
		 * @var MPSlider $mpSlider
		 */
		global $mpSlider;
		global $mpsl_settings;

		if ( is_plugin_active( 'motopress-slider/motopress-slider.php' ) ||
		     is_plugin_active( 'motopress-slider-lite/motopress-slider.php' )
		) {
			if ( version_compare( $mpsl_settings['plugin_version'], '1.1.2', '>=' ) ) {
				$mpSlider->enqueueScriptsStyles();
			}
		}
	}

	public function enqueueScripts(){
		$this->enqueueStyles();

		$migrate = $this->fixJQuery();

		$suffix         = mpceSettings()['script_suffix'];
		$pUrl           = mpceSettings()['plugin_dir_url'];
		$pVer           = mpceSettings()['plugin_version'];
		$vendorInFooter = $editorInFooter = $noConflictInFooter = true;

		// Fix jQueryUI (must be enqueued before jQueryUI)
		wp_enqueue_script( 'mpce-pre-bootstrap', $pUrl . 'mp/ce/iframeProd/pre-bootstrap' . $suffix . '.js', array( 'jquery' ), $pVer, $editorInFooter );

		// jQueryUI components
		wp_enqueue_script( 'mpce-jquery-ui', mpceSettings()['load_scripts_url'], array( 'jquery' ), $pVer, true );

		// Load CanJS
		wp_register_script( 'mpce-canjs', $pUrl . 'vendors/canjs/can.custom.min.js', array( 'jquery' ), mpceSettings()['canjs_version'], $vendorInFooter );
		wp_enqueue_script( 'mpce-canjs' );

		if ( mpceSettings()['lang']['select2'] !== 'en' ) {
			wp_enqueue_script( 'mpce-select2-locale', $pUrl . 'vendors/select2/select2_locale_' . mpceSettings()['lang']['select2'] . '.js', array(
				'jquery',
				'mpce-select2',
			), $pVer, $vendorInFooter );
		}

		wp_register_script( 'mpce-tinymce', $pUrl . 'vendors/tinymce/tinymce.min.js', array(), $pVer, $vendorInFooter );
		wp_enqueue_script( 'mpce-tinymce' );

		wp_enqueue_script( 'mpce-editor', $pUrl . 'mp/ce/iframeProd/editor' . $suffix . '.js', array( 'jquery', 'mpce-tinymce' ), $pVer, $editorInFooter );
		wp_localize_script( 'mpce-editor', 'MP', array() );
		wp_localize_script( 'mpce-editor', 'CE', array() );
		wp_localize_script('mpce-editor', 'motopressCE', array(
			'styleEditor'         => MPCECustomStyleManager::getLocalizeJSData(),
		));

		wp_register_script( 'mpce-no-conflict', $pUrl . 'mp/core/noConflict/noConflict' . $suffix . '.js', array( 'jquery' ), $pVer, $noConflictInFooter );
		wp_enqueue_script( 'mpce-no-conflict' );
		$this->fixNoConflict( $migrate );

		if ( wp_script_is( 'jquery-ui.min' ) ) {
			wp_dequeue_script( 'jquery-ui.min' );
		} //fix for theme1530

		wp_enqueue_style( 'mpce-flexslider' );
		wp_enqueue_script( 'mpce-flexslider' );

		wp_enqueue_script( 'mpce-magnific-popup' );

		wp_enqueue_script( 'google-charts-api' );

		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'wp-mediaelement' );

		wp_enqueue_script( 'stellar' );
		wp_enqueue_script( 'mp-youtube-api' );

		wp_enqueue_script( 'mpce-countdown-plugin' );
		wp_enqueue_script( 'mpce-countdown-timer' );
		if ( wp_script_is( 'keith-wood-countdown-language', 'registered' ) ) {
			wp_enqueue_script( 'keith-wood-countdown-language' );
		}

		// TODO: Is it needed in editor ?
		wp_enqueue_script( 'mpce-waypoints' );

		wp_enqueue_style( 'mpce-font-awesome' );

		wp_enqueue_script( 'mp-frontend' );

		$this->fixMPSliderScripts();

		do_action( 'mpce_add_custom_scripts' );
	}

	public function enqueueStyles(){
		wp_enqueue_style( 'mpce-jquery-ui-dialog', includes_url( 'css/jquery-ui-dialog.css' ), array(), mpceSettings()['plugin_version'] );
		wp_enqueue_style( 'mpce-iframe', mpceSettings()['plugin_dir_url'] . 'mp/ce/css/ceIframe.css', array('mpce-jquery-ui-dialog', 'mpce-font-awesome'), mpceSettings()['plugin_version'] );
		do_action( 'mpce_add_custom_styles' );
	}
}