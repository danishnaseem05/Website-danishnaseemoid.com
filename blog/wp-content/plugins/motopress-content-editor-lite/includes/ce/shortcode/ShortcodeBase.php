<?php

if (!class_exists('MPCEShortcodeBase')) {
	require_once dirname(__FILE__) . '/Interfaces.php';
	require_once dirname(__FILE__) . '/ShortcodeAtts.php';

	abstract class MPCEShortcodeBase implements iMPCEShortcode {
		const PREFIX = 'mp_';

		/** Basic shortcodes */
		protected static $scBasic = array();

		/** Shortcodes are registered after `the_content` filter */
		protected static $scTheContent = array();

		/** Combined shortcodes (all) */
		protected static $sc = array();

		public static $attributes = array(
	        'closeType'     => 'data-motopress-close-type',
	        'shortcode'     => 'data-motopress-shortcode',
	        'group'         => 'data-motopress-group',
	        'parameters'    => 'data-motopress-parameters',
	        'styles'        => 'data-motopress-styles',
	        'content'       => 'data-motopress-content',
	        'unwrap'        => 'data-motopress-unwrap'
	    );


		/**
		 * Static init
		 */
	    static function init() {
	        self::initCallbacks();
	    }

		/**
		 * Register shortcodes
		 * @return void
		 */
	    abstract function register();

		/**
		 * Return view dir name for shortcodes
		 * @return string
		 */
	    abstract protected function getViewDirName();

		/**
		 * Get callback list from implemented interfaces
		 * @return void
		 */
	    private static function initCallbacks() {

	        // Get shortcode list from interfaces
	        $basic      = get_class_methods('iMPCEShortcodeBasic');
	        $theContent = get_class_methods('iMPCEShortcodeTheContent');
	        $all        = array_merge($basic, $theContent);

	        // Process (make assoc array [[$tag => $callback], ...]) and assign
			self::$scBasic      = array_combine($basic, $basic);
			self::$scTheContent = array_combine($theContent, $theContent);
			self::$sc           = array_combine($all, $all);
	    }

		/**
		 * Register shortcode list in wp
		 * @param array $list
		 * @return void
		 */
	    protected function addShortcodes($list) {
			foreach ($list as $tag => $callback) {
	            add_shortcode($tag, array($this, $callback));
	        }
	    }

	    static function addStyleAtts($atts = array()) {
			return MPCEShortcodeAtts::addStyle($atts);
		}

		protected function render($tag, $data = array(), $content = null) {
			extract($data);
			ob_start();
			$path = $this->getViewPath($tag);
			if (file_exists($path)) {
				include $path;
			}
			return ob_get_clean();
		}

		protected function getViewPath($tag) {
			$dirName = $this->getViewDirName();
			$fileName = $tag;

			return mpceSettings()['plugin_dir_path'] . "includes/ce/shortcode/views/{$dirName}/{$fileName}.php";
		}

		public static function getMustAnuatopShortcodes() {
	        return array_keys(self::$scBasic);
	    }

	    /**
		 * (unused)
	     * @param string $content
	     * @return string
	     */
	    public function cleanupShortcode($content) {
	        return strtr($content, array(
	            '<p>[' => '[',
	            '</p>[' => '[',
	            ']<p>' => ']',
	            ']</p>' => ']',
	            ']<br />' => ']'
	        ));
	    }

	    /**
	     * @param string $closeType
	     * @param string $shortcode
	     * @param stdClass $parameters
	     * @param stdClass $styles
	     * @param string $content
	     * @return string
	     */
	    public static function toShortcode($closeType, $shortcode, $parameters, $styles, $content) {
	        $str = '[' . $shortcode;
	        if (!is_null($parameters)) {
	            foreach ($parameters as $attr => $values) {
	                if (isset($values->value)) {
	                    $str .= ' ' . $attr . '="' . $values->value . '"';
	                }
	            }
	        }
	        if (!is_null($styles)) {
	            foreach ($styles as $attr => $values) {
	                if (isset($values->value)) {
	                    $str .= ' ' . $attr . '="' . $values->value . '"';
	                }
	            }
	        }
	        $str .= ']';
	        if ($closeType === MPCEObject::ENCLOSED) {
	            if (!is_null($content)) {
	                $str .= $content;
	            }
	            $str .= '[/' . $shortcode . ']';
	        }
	        return $str;
	    }

		public static function unautopBuilderShortcodes($content) {
//	        $shortcodeNames = self::getMustAnuatopShortcodes();
			$shortcodeNames = MPCELibrary::getInstance()->getObjectsNames();

			$shortcodeNames = apply_filters( 'mpce_unautop_shortcodes', $shortcodeNames );

	        if (!empty($shortcodeNames)) {
	            $shortcodeNames = implode('|', $shortcodeNames);
	            $regexp = '/(?:<p>)?'
	                    . '('                               // 1 : Shortcode
	                    . '\\['                             // Opening bracket
	                    . '(?:\\[?)'                        // Optional second opening bracket for escaping shortcodes: [[tag]]
	                    . '(?:\\/)?'
	                    . '(?:' . $shortcodeNames . ')'     // Shortcode name
	                    . '\\b'                             // Word boundary
	                    . '(?:'                             // Unroll the loop: Inside the opening shortcode tag
	                    .     '[^\\]\\/]*'                  // Not a closing bracket or forward slash
	                    .     '(?:'
	                    .         '\\/(?!\\])'              // A forward slash not followed by a closing bracket
	                    .         '[^\\]\\/]*'              // Not a closing bracket or forward slash
	                    .     ')*?'
	                    . ')'
	                    . '(?:'
	                    .     '(?:\\/)'                     // Self closing tag ...
	                    .     '\\]'                         // ... and closing bracket
	                    . '|'
	                    .     '\\]'
	                    . ')'
	                    . ')'
	                    . '(?:<br \\/>)?'
	                    . '(?:<\\/p>)?/s';
	            $content = preg_replace($regexp, '${1}', $content);
	        }
	        return $content;
	    }

		/**
		 * Filter content intended for list structure
		 * Used in `list`, `table`, `google_chart` shortcodes
		 * @param string $content
		 * @return string
		 */
	    protected static function filterListContent($content) {
	    	$content = trim($content);
	        $content = preg_replace('/^<p>|<\/p>$/', '', $content);
	        $content = preg_replace('/<br[^>]*>\s*\r*\n*/is', PHP_EOL, $content);

	        return $content;
	    }

	    protected function tableUniversal($content, $tableAttsStr = '') {
			$result = '';

		    $i = 0;
		    $tableAttsStr = $tableAttsStr ? " {$tableAttsStr}" : '';
		    $result .= "<table{$tableAttsStr}>";

            if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
                $rows = explode("\n", $content);
                $rowsCount = count($rows);
                foreach ($rows as $row) {
                    $row = str_getcsv($row);
                    $isLast = ($i === $rowsCount - 1) ? true : false;
                    self::addTableRow($row, $i, $isLast, $result);
                    $i++;
                }
            }
            else {
                $tmpFile = new SplTempFileObject();
                $tmpFile->setFlags(SplFileObject::SKIP_EMPTY);
                $tmpFile->setFlags(SplFileObject::DROP_NEW_LINE);
                $write = $tmpFile->fwrite($content);
                if (!is_null($write)) {
                    $tmpFile->rewind();
                    while (!$tmpFile->eof()) {
                        $row = $tmpFile->fgetcsv();
                        $isLast = $tmpFile->eof();
                        self::addTableRow($row, $i, $isLast, $result);
                        $i++;
                    }
                }
            }

            $result .= '</table>';

	        return $result;
		}

	    /**
	     * @param array $row
	     * @param int $i
	     * @param boolean $isLast
	     * @param string $result
	     */
	    private static function addTableRow($row, $i, $isLast, &$result) {
	        if ($i === 0) {
	            $result .= '<thead>';
	            $result .= '<tr>';
	            foreach ($row as $col) {
	                $result .= '<th>' . trim($col) . '</th>';
	            }
	            $result .= '</tr>';
	            $result .= '</thead>';
	        } else {
	            if ($i === 1) {
	                $result .= '<tbody>';
	            }
	            if (($i - 1) % 2 !== 0) {
	                $result .= '<tr class="odd-row">';
	            } else {
	                $result .= '<tr>';
	            }
	            foreach ($row as $col) {
	                $result .= '<td>'. trim($col) .'</td>';
	            }
	            $result .= '</tr>';
	            if ($isLast) {
	                $result .= '</tbody>';
	            }
	        }
	    }

	    protected static function getAtts($tag) {
			return MPCEShortcodeAtts::get($tag);
		}

	}
	MPCEShortcodeBase::init();
}