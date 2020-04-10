<?php

/**
 *
 * Class MPCERenderContent
 */
class MPCERenderContent {
	/**
	 * @param string|string[] $matches
	 *
	 * @return null|string|string[]
	 *
	 */
	public static function motopressCEParseObjectsRecursive($matches) {
		$regex = '/' . motopressCEGetMPShortcodeRegex() . '/s';

		if ( !is_array( $matches ) ) {
			return preg_replace_callback($regex, array( 'MPCERenderContent', 'motopressCEParseObjectsRecursive'), $matches);
		} else {

			$shortcodeStr = $matches[0];
			$shortcodeName = $matches[2];
			$attsStr = $matches[3];
			$content = trim($matches[5]);

			$_atts = self::_getAtts( $content, $attsStr, $shortcodeName, $shortcodeStr );

			$endstr = ( $_atts['closeType'] === MPCEObject::ENCLOSED ) ? '[/' . $shortcodeName . ']' : '';
			// Rebuild string of attributes
			$attsStr = '';
			foreach ($_atts['atts'] as $name => $value) {
				$attsStr .= ' ' . $name . '="' . $value . '"';
			}

			$list         = MPCELibrary::getInstance()->getObjectsList();
			$group        = $list[ $shortcodeName ]['group'];
			$groupObjects = MPCELibrary::getInstance()->getGroupObjects();
			$grid = MPCELibrary::getInstance()->getGridObjects();
			$gridShortcodes = self::_getGridShortcodes( $grid );
			if (in_array($shortcodeName, $gridShortcodes) || in_array($shortcodeName, $groupObjects)) {

				return '<div ' . MPCEShortcode::$attributes['closeType'] . '="' . $_atts['closeType'] . '" ' .
				       MPCEShortcode::$attributes['shortcode'] . '="' . $shortcodeName . '" ' .
				       MPCEShortcode::$attributes['group'] . '="' . $group . '" ' .
				       MPCEShortcode::$attributes['parameters'] . '=\'' . $_atts['parameters'] . '\' ' .
				       MPCEShortcode::$attributes['styles'] . '=\'' . $_atts['styles'] . '\' ' .
				       MPCEShortcode::$attributes['content'] . '="' . htmlentities( $_atts['dataContent'], ENT_QUOTES, 'UTF-8' ) . '" ' .
				       $_atts['unwrap'] . '>' .
				       '[' . $shortcodeName . $attsStr . ']' .
				       preg_replace_callback( $regex, array( 'MPCERenderContent', 'motopressCEParseObjectsRecursive'), $_atts['content'] ) .
				       $endstr . '</div>';

			} else {

				// Encode content of shortcodes temporary till applying `the_content` filter
				// .. to avoid rendering [caption] shortcode and avoid making <img> responsive (srcset and sizes)
				if (in_array($shortcodeName, array('mp_text', 'mp_heading'))) {
					$content = '[mp_tmp_base64]' . base64_encode($_atts['content']) . '[/mp_tmp_base64]';
				}

				return '<div ' . MPCEShortcode::$attributes['closeType'] . '="' . $_atts['closeType'] . '" ' .
				       MPCEShortcode::$attributes['shortcode'] . '="' . $shortcodeName . '" ' .
				       MPCEShortcode::$attributes['group'] . '="' . $group . '"' .
				       MPCEShortcode::$attributes['parameters'] . '=\'' . $_atts['parameters'] . '\' ' .
				       MPCEShortcode::$attributes['styles'] . '=\'' . $_atts['styles'] . '\' ' .
				       MPCEShortcode::$attributes['content'] . '="' . htmlentities( $_atts['dataContent'], ENT_QUOTES, 'UTF-8' ) . '" ' .
				       $_atts['unwrap'] . '>' .
				       '[' . $shortcodeName . $attsStr . ']' . $content .
				       $endstr . '</div>';

			}
		}
	}


	/**
	 * @param string $attsStr
	 * @param string $shortcodeName
	 *
	 * @return string
	 */
	private static function _fixClmnAtts4CherryFramework( $attsStr, $shortcodeName ) {
		$grid = MPCELibrary::getInstance()->getGridObjects();

		// Fix for cherry column shortcode with attr col_md="none"
		if ( is_plugin_active( 'motopress-cherryframework4/motopress-cherryframework4.php' ) &&
		     in_array( $shortcodeName, array( $grid['span']['shortcode'], $grid['span']['inner'] ) ) ) {
			$regexp = '/(?:^' . $grid['span']['attr'] . '|\s' . $grid['span']['attr'] . ')\s*=\s*"([^"]*)"(?:\s|$)|(?:^' .
			          $grid['span']['attr'] . '|\s' . $grid['span']['attr'] . ')\s*=\s*\'([^\']*)\'(?:\s|$)|(?:' .
			          $grid['span']['attr'] . ')\s*=\s*([^\s\'"]+)(?:\s|$)/';

			$replacement = ' ' . $grid['span']['attr'] . '="' . $grid['row']['col'] . '" ';

			if ( preg_match( $regexp, $attsStr, $cherry_col ) ) {
				// $cherry_col must be in range 1 .. max col size
				$col = intval( $cherry_col[1] );
				if ( $col < 1 || $col > (int) $grid['row']['col'] ) {
					$attsStr = preg_replace( $regexp, $replacement, $attsStr );
				}
			} else {
				$attsStr .= $replacement;
			}
		}

		return $attsStr;
	}


	/**
	 * @param $atts
	 * @param $shortcodeName
	 *
	 * @return array
	 */
	private static function _prepareStyles( $atts, $shortcodeName ) {
		//set styles
		$styles  = array();
		$_styles = MPCEShortcodeAtts::getStyle();
		if ( !empty( $_styles ) ) {
			foreach ( $_styles as $name => $value ) {
				if ( array_key_exists( $name, $atts ) ) {
					$value                    = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $atts[ $name ] );
					$styles[ $name ]['value'] = htmlentities( $value, ENT_QUOTES, 'UTF-8' );
				} else {
					$styles[ $name ] = new stdClass();
				}
			}

			if ( !is_array( $styles['mp_style_classes'] ) ) {
				if ( array_key_exists( $shortcodeName, MPCELibrary::getInstance()->deprecatedParameters ) ) {
					foreach ( MPCELibrary::getInstance()->deprecatedParameters[ $shortcodeName ] as $key => $val ) {
						if ( array_key_exists( $key, $atts ) ) {
							if ( !is_array( $styles['mp_style_classes'] ) ) {
								$styles['mp_style_classes']          = array();
								$styles['mp_style_classes']['value'] = '';
							}
							if ( $shortcodeName === MPCEShortcode::PREFIX . 'button' ) {
								if ( $key === 'color' && $atts[ $key ] === 'default' ) {
									$className = $val['prefix'] . 'silver';
								} elseif ( $key === 'size' ) {
									$className = ( $atts[ $key ] === 'default' ) ? $val['prefix'] .
									                                               'middle' : $val['prefix'] .
									                                                          $atts[ $key ];
									$className .= ' motopress-btn-rounded';
								} else {
									$className = $val['prefix'] . $atts[ $key ];
								}
							} else {
								$className = $val['prefix'] . $atts[ $key ];
							}
							$styles['mp_style_classes']['value'] .= $styles['mp_style_classes']['value'] ===
							                                        '' ? $className : ' ' . $className;
						}
					}
				}
			}

			$jsonStyles = motopressCEJsonEncodeUnescapedUnicode( $styles );
		}

		return $jsonStyles;
	}


	/**
	 * @param $shortcodeName
	 * @param $atts
	 *
	 * @return array
	 */
	private static function _prepareParameters( $shortcodeName, $atts ) {
		$list = MPCELibrary::getInstance()->getObjectsList();
		$parameters = $list[ $shortcodeName ]['parameters'];

		if ( !empty( $parameters ) ) {
			foreach ( $parameters as $name => $param ) {
				if ( array_key_exists( $name, $atts ) ) {
					$value                        = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $atts[ $name ] );
					$parameters[ $name ]['value'] = htmlentities( $value, ENT_QUOTES, 'UTF-8' );
				} else {
					$parameters[ $name ] = new stdClass();
				}
			}
		}

		return motopressCEJsonEncodeUnescapedUnicode( $parameters );
	}


	/**
	 * @param array $atts
	 * @param string $shortcodeName
	 *
	 */
	private static function _storeAttachmentDetails( $atts, $shortcodeName ) {
		$obj = MPCELibrary::getInstance()->getObject($shortcodeName);
		$motopressCEWPAttachmentDetails = mpceGetWPAttachmentDetails();
		foreach ( $atts as $name => $value ) {
			if ( key_exists( $name, $obj->parameters ) ) {
				if ( $obj->parameters[ $name ]['type'] === 'media' && isset( $obj->parameters[ $name ]['returnMode'] ) &&
				     $obj->parameters[ $name ]['returnMode'] === 'id' && !empty( $value ) &&
				     !isset( $motopressCEWPAttachmentDetails[ $value ] ) ) {
					$url = wp_get_attachment_url( $value );
					if ( $url ) {
						$motopressCEWPAttachmentDetails[ $value ] = $url;
					}
				}
			}
		}
		mpceSetWPAttachmentDetails( $motopressCEWPAttachmentDetails );
	}

	/**
	 * @param $atts
	 *
	 * @return mixed
	 */
	private static function _fixPostsGridTemplatePath( $atts ) {
		if ( isset( $atts['template'] ) ) {
			$atts['template'] = MPCEShortcodePostsGrid::fixTemplatePath( $atts['template'] );
		}

		return $atts;
	}

	/**
	 * @param $atts
	 *
	 * @return array
	 */
	private static function _convertRowStretchClassesToParam( $atts ) {
		if ( isset( $atts['mp_style_classes'] ) && !empty( $atts['mp_style_classes'] ) ) {
			$mpStyleClassesArr = explode( ' ', $atts['mp_style_classes'] );
			if ( ( $key = array_search( 'mp-row-fullwidth', $mpStyleClassesArr ) ) !== false ) {
				unset( $mpStyleClassesArr[ $key ] );
				$atts['stretch']       = 'full';
				$atts['width_content'] = '';
			}
			$atts['mp_style_classes'] = implode( ' ', $mpStyleClassesArr );
		}

		// Row parallax_bg_size fix (in case default='cover')
		/*if (isset($atts['bg_media_type']) && $atts['bg_media_type'] === 'parallax') {
			if (!isset($atts['parallax_bg_size'])) {
				$atts['parallax_bg_size'] = 'normal';
			}
		}*/

		return $atts;
	}

	/**
	 * @param string $shortcodeName
	 * @param array $atts
	 *
	 * @return string
	 */
	private static function _detectUnwrap( $shortcodeName, $atts ) {
		$unwrap = '';
		// set system marking for "must-unwrap" code
		if ( $shortcodeName == 'mp_code' ) {
			if ( !empty( $atts ) ) {
				if ( isset( $atts['unwrap'] ) && $atts['unwrap'] === 'true' ) {
					$unwrap = MPCEShortcode::$attributes['unwrap'] . '="true"';
				}
			}
		}

		return $unwrap;
	}

	/**
	 * Fix for extra [row][span][/span][/row] in code
	 * Mark span empty if it doesn't contain any content
	 *
	 * @param $content
	 * @param $atts
	 *
	 * @return array
	 */
	private static function _markSpanWithoutContentAsEmpty( $content, $atts ) {
		$grid = MPCELibrary::getInstance()->getGridObjects();

		if ( trim( $content ) === '' && ( // Span is not already motopress-empty
				!isset( $atts[ $grid['span']['custom_class_attr'] ] ) ||
				!preg_match( '/\bmotopress-empty\b/', $atts[ $grid['span']['custom_class_attr'] ] ) ) ) {
			if ( !isset( $atts[ $grid['span']['custom_class_attr'] ] ) ) {
				$atts[ $grid['span']['custom_class_attr'] ] = '';
			}
			$atts[ $grid['span']['custom_class_attr'] ] .= ' motopress-empty';
		}

		return $atts;
	}

	/**
	 * @param $atts
	 *
	 * @return array
	 */
	private static function _fixMembersWidgetContent( $atts ) {
		if ( !empty( $atts ) ) {
			if ( isset( $atts['members_content'] ) ) {
				$content = $atts['members_content'];
				unset( $atts['members_content'] );
			}
		}

		return array( $atts, $content );
	}

	/**
	 *
	 * @return array
	 */
	private static function _getSpanShortcodes() {
		$grid = MPCELibrary::getInstance()->getGridObjects();
		if ( isset( $grid['span']['type'] ) && $grid['span']['type'] === 'multiple' ) {
			$spanShortcodes = array_merge( $grid['span']['shortcode'], $grid['span']['inner'] );
		} else {
			$spanShortcodes = array( $grid['span']['shortcode'], $grid['span']['inner'] );
		}

		return $spanShortcodes;
	}

	/**
	 *
	 * @return array
	 */
	private static function _getGridShortcodes() {
		$grid = MPCELibrary::getInstance()->getGridObjects();
		if ( isset( $grid['span']['type'] ) && $grid['span']['type'] === 'multiple' ) {
			$gridShortcodes = array_merge( array(
				$grid['row']['shortcode'],
				$grid['row']['inner']
			), $grid['span']['shortcode'], $grid['span']['inner'] );
		} else {
			$gridShortcodes = array(
				$grid['row']['shortcode'],
				$grid['row']['inner'],
				$grid['span']['shortcode'],
				$grid['span']['inner']
			);
		}

		return $gridShortcodes;
	}

	/**
	 * Add filler div to empty spans
	 *
	 * @param string $content
	 * @param array $atts
	 * @param array $grid
	 *
	 * @return string
	 */
	private static function _addFillerContentInEmtySpan( $content, $atts, $grid ) {

		if ( isset( $atts[ $grid['span']['custom_class_attr'] ] ) &&
		     preg_match( '/\bmotopress-empty\b/', $atts[ $grid['span']['custom_class_attr'] ] ) ) {
			$content = '<div class="motopress-filler-content"><i class="fa fa-plus"></i></div>';
		}

		return $content;
	}

	/**
	 *
	 * @param $content
	 * @param $attsStr
	 * @param $shortcodeName
	 * @param $shortcodeStr
	 *
	 * @return array [ content: string, atts: array, parameters: string, styles: string, closeType: string, unwrap: string, dataContent: string ]
	 */
	private static function _getAtts( $content, $attsStr, $shortcodeName, $shortcodeStr ) {
		if ( !empty( $content ) ) {
			$content = preg_replace( '/^<\\/p>(.*)/', '${1}', $content );
			$content = preg_replace( '/(.*)<p>$/', '${1}', $content );
		}

		$attsStr = self::_fixClmnAtts4CherryFramework( $attsStr, $shortcodeName );
		$atts    = (array) shortcode_parse_atts( $attsStr );

		if ( $shortcodeName === MPCEShortcode::PREFIX . 'posts_grid' ) {
			$atts = self::_fixPostsGridTemplatePath( $atts );
		}

		if ( $shortcodeName === MPCEShortcode::PREFIX . 'row' ||
		     $shortcodeName === MPCEShortcode::PREFIX . 'row_inner'
		) {
			$atts = self::_convertRowStretchClassesToParam( $atts );
		}

		self::_storeAttachmentDetails( $atts, $shortcodeName );

		$list       = MPCELibrary::getInstance()->getObjectsList();
		$parameters = self::_prepareParameters( $shortcodeName, $atts );
		$styles     = self::_prepareStyles( $atts, $shortcodeName );

		// set close-type of shortcode
		if ( preg_match( '/\[\/' . $shortcodeName . '\](?:<br \\/>)?(?:<\\/p>)?$/', $shortcodeStr ) === 1 ) {
			$closeType = MPCEObject::ENCLOSED;
		} else {
			$closeType = MPCEObject::SELF_CLOSED;
		}

		//wrap custom code
		$wrapCustomCodeRegex = '/\A(?:' . motopressCEGetMPShortcodeRegex() . ')+\Z/s';

		$grid           = MPCELibrary::getInstance()->getGridObjects();
		$spanShortcodes = self::_getSpanShortcodes( $grid );

		if ( ( $content !== '' ) && ( $content !== '&nbsp;' ) && ( in_array( $shortcodeName, $spanShortcodes ) ) &&
		     ( !preg_match( $wrapCustomCodeRegex, $content ) ) //$regex
		) {
			$content = motopressCEWrapCustomCode( $content );
		}

		$unwrap = self::_detectUnwrap( $shortcodeName, $atts );

		// Members Widget fix
		if ( $shortcodeName == 'mp_members_content' ) {
			list( $atts, $content ) = self::_fixMembersWidgetContent( $atts );
		}

		if ( in_array( $shortcodeName, $spanShortcodes ) ) {
			$atts    = self::_markSpanWithoutContentAsEmpty( $content, $atts );
			$content = self::_addFillerContentInEmtySpan( $content, $atts, $grid );
		}

		//setting data-motopress-content for all objects except layout
		$gridShortcodes = self::_getGridShortcodes( $grid );
		$dataContent    = '';
		if ( !in_array( $shortcodeName, $gridShortcodes ) ) {
			$dataContent = motopressCEScreeningDataAttrShortcodes( $content );
//	        $dataContent = motopressCERemoveMoreTag($dataContent);
		}

		return array(
			'content'     => $content,
			'atts'        => $atts,
			'parameters'  => $parameters,
			'styles'       => $styles,
			'closeType'   => $closeType,
			'unwrap'      => $unwrap,
			'dataContent' => $dataContent,
		);
	}

	/**
	 * @param $matches
	 *
	 * @return null|string|string[]
	 */
	public static function getDataAtts($content){
		$motopressCELibrary = MPCELibrary::getInstance();
		$regex = '/' . motopressCEGetMPShortcodeRegex() . '/s';

		preg_match($regex, $content, $matches);

		$shortcodeStr = $matches[0];
		$shortcodeName = $matches[2];
		$attsStr = $matches[3];
		$content = trim($matches[5]);

		$list         = MPCELibrary::getInstance()->getObjectsList();
		$group        = $list[ $shortcodeName ]['group'];

		$_atts = self::_getAtts( $content, $attsStr, $shortcodeName, $shortcodeStr );

		return array(
			'closeType'  => $_atts['closeType'],
			'shortcode'  => $shortcodeName,
			'group'      => $group,
			'parameters' => $_atts['parameters'],
			'styles'     => $_atts['styles'],
			'content'    => $_atts['dataContent']
		);
	}


}