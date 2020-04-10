<?php

class MPCEObjectTemplatesLibrary {

	private static $instance = null;

	/**
	 *
	 * @return MPCEObjectTemplatesLibrary
	 */
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	const WIDGET_OPTION = 'motopress-ce-widget-templates';
	const ROW_OPTION = 'motopress-ce-row-templates';
	const PAGE_OPTION = 'motopress-ce-page-templates';

	/**
	 * @param {array[]} $library
	 */
	public function save( $library ) {
		if ( !empty( $library['widgets'] ) ) {
			$widgets = $library['widgets'];
			// Prevent save Uncategorized category
			unset( $widgets['categories'][0] );
		} else {
			$widgets = array(
				'list'       => array(),
				'categories' => array(),
			);
		}

		if ( !empty( $library['rows'] ) ) {
			$rows = $library['rows'];
			// Prevent save Uncategorized category
			unset( $rows['categories'][0] );
		} else {
			$rows = array(
				'list'       => array(),
				'categories' => array(),
			);
		}

		if ( !empty( $library['pages'] ) ) {
			$pages = $library['pages'];
			// Prevent save Uncategorized category
			unset( $pages['categories'][0] );
		} else {
			$pages = array(
				'list'       => array(),
				'categories' => array(),
			);
		}

		update_option( self::WIDGET_OPTION, $widgets );
		update_option( self::ROW_OPTION, $rows );
		update_option( self::PAGE_OPTION, $pages );
	}

	/**
	 * @param $objectLib
	 *
	 * @return array [categories: array, list: array]
	 */
	public function normalizeLibStructure( $objectLib ) {
		if ( !is_array( $objectLib ) ) {
			$objectLib = array();
		}
		if ( !array_key_exists( 'categories', $objectLib ) || !is_array( $objectLib['categories'] ) ) {
			$objectLib['categories'] = array();
		}
		if ( !array_key_exists( 'list', $objectLib ) || !is_array( $objectLib['list'] ) ) {
			$objectLib['list'] = array();
		}
		return $objectLib;
	}

	/**
	 * @return array [widgets: array, rows: array, pages: array]
	 */
	public function get() {
		$widgets = $this->normalizeLibStructure(get_option( self::WIDGET_OPTION));
		$rows    = $this->normalizeLibStructure(get_option( self::ROW_OPTION));
		$pages   = $this->normalizeLibStructure(get_option( self::PAGE_OPTION)) ;

		$defaultCategories = array( __( 'Uncategorized', '%mpce_textdomain%' ) );

		$widgets['categories'] = array_merge( $defaultCategories, $widgets['categories'] );
		$rows['categories']    = array_merge( $defaultCategories, $rows['categories'] );
		$pages['categories']   = array_merge( $defaultCategories, $pages['categories'] );

		return array(
			'widgets' => $widgets,
			'rows'    => $rows,
			'pages'   => $pages,
		);
	}

	/**
	 * Update widgets structure - add atts.
	 *
	 * @since 3.0.3
	 */
	public function _addWidgetsAtts(){
		$widgets = get_option( self::WIDGET_OPTION);

		if (!$widgets) {
			return;
		}

		$widgets = $this->normalizeLibStructure($widgets);
		foreach ( $widgets['list'] as &$widget ) {
			$atts = MPCERenderContent::getDataAtts($widget['content']);
			$widget['atts'] = array(
				'content' => $atts['content'],
				'parameters' => $atts['parameters'],
				'styles' => $atts['styles']
			);
		}

		update_option( self::WIDGET_OPTION, $widgets );
	}
}