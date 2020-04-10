<?php
require_once dirname(__FILE__) . '/BaseElement.php';
require_once dirname(__FILE__) . '/Element.php';
require_once dirname(__FILE__) . '/Group.php';
require_once dirname(__FILE__) . '/Object.php';
require_once dirname(__FILE__) . '/Template.php';

/**
 * Description of MPCELibrary
 *
 */
class MPCELibrary {
	/** @var MPCEGroup[] */
    private $library = array();
    public $globalPredefinedClasses = array();
    public $tinyMCEStyleFormats = array();
    private $templates = array();
    private $gridObjects = array();
    public static $isAjaxRequest;
    private static $defaultGroup;
	private static $instance = null;
    public $deprecatedParameters = array(
        'mp_button' => array(
            'color' => array(
                'prefix' => 'motopress-btn-color-'
            ),
            'size' => array(
                'prefix' => 'motopress-btn-size-'
            )
        ),
        'mp_accordion' => array(
            'style' => array(
                'prefix' => 'motopress-accordion-'
            )
        ),
        'mp_social_buttons' => array(
            'size' => array(
                'prefix' => ''
            ),
            'style' => array(
                'prefix' => ''
            )
        ),
        'mp_table' => array(
            'style' => array(
                'prefix' => 'motopress-table-style-'
            )
        )
    );

	/**
	 * 
	 * @return MPCELibrary
	 */
	public static function getInstance(){
		if (is_null(self::$instance)) {
			self::$instance = new MPCELibrary();
		}
		return self::$instance;
	}

    private function __construct() {
        self::$isAjaxRequest = $this->isAjaxRequest();

        $backgroundColor = array(
            'label' => 'Background Color',
            'values' => array(
                'blue' => array(
                    'class' => 'motopress-bg-color-blue',
                    'label' => 'Blue'
                ),
                'dark' => array(
                    'class' => 'motopress-bg-color-dark',
                    'label' => 'Dark'
                ),
                'gray' => array(
                    'class' => 'motopress-bg-color-gray',
                    'label' => 'Gray'
                ),
                'green' => array(
                    'class' => 'motopress-bg-color-green',
                    'label' => 'Green'
                ),
                'red' => array(
                    'class' => 'motopress-bg-color-red',
                    'label' => 'Red'
                ),
                'silver' => array(
                    'class' => 'motopress-bg-color-silver',
                    'label' => 'Silver'
                ),
                'white' => array(
                    'class' => 'motopress-bg-color-white',
                    'label' => 'White'
                ),
                'yellow' => array(
                    'class' => 'motopress-bg-color-yellow',
                    'label' => 'Yellow'
                )
            )
        );

        $style = array(
            'label' => 'Style',
            'allowMultiple' => true,
            'values' => array(
                'bg-alpha-75' => array(
                    'class' => 'motopress-bg-alpha-75',
                    'label' => 'Semi Transparent'
                ),
                'border' => array(
                    'class' => 'motopress-border',
                    'label' => 'Bordered'
                ),
                'border-radius' => array(
                    'class' => 'motopress-border-radius',
                    'label' => 'Rounded'
                ),
                'shadow' => array(
                    'class' => 'motopress-shadow',
                    'label' => 'Shadow'
                ),
                'shadow-bottom' => array(
                    'class' => 'motopress-shadow-bottom',
                    'label' => 'Bottom Shadow'
                ),
                'text-shadow' => array(
                    'class' => 'motopress-text-shadow',
                    'label' => 'Text Shadow'
                )
            )
        );

        $border = array(
            'label' => 'Border Side',
            'allowMultiple' => true,
            'values' => array(
                'border-top' => array(
                    'class' => 'motopress-border-top',
                    'label' => 'Border Top'
                ),
                'border-right' => array(
                    'class' => 'motopress-border-right',
                    'label' => 'Border Right'
                ),
                'border-bottom' => array(
                    'class' => 'motopress-border-bottom',
                    'label' => 'Border Bottom'
                ),
                'border-left' => array(
                    'class' => 'motopress-border-left',
                    'label' => 'Border Left'
                )
            )
        );

        $textColor = array(
            'label' => 'Text Color',
            'values' => array(
                'color-light' => array(
                    'class' => 'motopress-color-light',
                    'label' => 'Light Text'
                ),
                'color-dark' => array(
                    'class' => 'motopress-color-dark',
                    'label' => 'Dark Text'
                )
            )
        );

		$visiblePredefinedGroup = array(
			'label' => 'Visibility',
			'allowMultiple' => true,
			'values' => array(
				'hide-on-desktop' => array(
					'class' => 'motopress-hide-on-desktop',
					'label' => 'Hide On Desktop'
				),
				'hide-on-tablet' => array(
					'class' => 'motopress-hide-on-tablet',
					'label' => 'Hide On Tablet'
				),
				'hide-on-phone' => array(
					'class' => 'motopress-hide-on-phone',
					'label' => 'Hide On Phone'
				),
			)
		);

        $rowPredefinedStyles = array(
            'background-color' => $backgroundColor,
            'style' => $style,
            'border' => $border,
            'color' => $textColor,
			'visible' => $visiblePredefinedGroup
        );

        $spanPredefinedStyles = array(
            'background-color' => $backgroundColor,
            'style' => $style,
            'border' => $border,
            'color' => $textColor,
			'visible' => $visiblePredefinedGroup
        );

        $spacePredefinedStyles = array(
            'type' => array(
                'label' => 'Type',
                'values' => array(
                    'light' => array(
                        'class' => 'motopress-space-light',
                        'label' => 'Light'
                    ),
                    'normal' => array(
                        'class' => 'motopress-space-normal',
                        'label' => 'Normal'
                    ),
                    'dotted' => array(
                        'class' => 'motopress-space-dotted',
                        'label' => 'Dotted'
                    ),
                    'dashed' => array(
                        'class' => 'motopress-space-dashed',
                        'label' => 'Dashed'
                    ),
                    'double' => array(
                        'class' => 'motopress-space-double',
                        'label' => 'Double'
                    ),
                    'groove' => array(
                        'class' => 'motopress-space-groove',
                        'label' => 'Grouve'
                    ),
                    'ridge' => array(
                        'class' => 'motopress-space-ridge',
                        'label' => 'Ridge'
                    ),
                    'heavy' => array(
                        'class' => 'motopress-space-heavy',
                        'label' => 'Heavy'
                    )
                )
            )
        );
        /* Objects */
        //grid
        $rowParameters = array(
			'stretch' => array(
				'type' => 'select',
				'label' => __("Container Width:", 'motopress-content-editor-lite'),
				'description' => sprintf(__("Set fixed width in <a href='%s' target='_blank'>plugin settings</a>", 'motopress-content-editor-lite'), admin_url('admin.php?page=motopress_options')),
				'default' => '',
				'list' => array(
					'' => __("Auto", 'motopress-content-editor-lite'),
					'full' => __("Full", 'motopress-content-editor-lite'),
					'fixed' => __("Fixed", 'motopress-content-editor-lite')
				),
			),
			'width_content' => array(
				'type' => 'select',
				'label' => 'Content Width',
				'default' => '',
				'list' => array(
					'' => __("Auto", 'motopress-content-editor-lite'),
					'full' => __("Full", 'motopress-content-editor-lite'),
					'fixed' => __("Fixed", 'motopress-content-editor-lite')
				),
				'dependency' => array(
					'parameter' => 'stretch',
					'value' => 'full'
				)
			),
			'full_height' => array(
				'type' => 'checkbox',
				'label' => __("Fill the height of the window", 'motopress-content-editor-lite'),
				'default' => 'false'
			),
            'bg_media_type' => array(
                'type' => 'radio-buttons',
                'label' => __("Background:", 'motopress-content-editor-lite'),
                'description' => __("Full preview is available at the website only. Select 'Full-Width' style to stretch media to the website width.", 'motopress-content-editor-lite'),
//                'default' => 'disabled',
                'list' => array(
                    'disabled' => __("None", 'motopress-content-editor-lite'),
                    'video' => __("Video", 'motopress-content-editor-lite'),
                    'youtube' => __("YouTube", 'motopress-content-editor-lite'),
                    'parallax' => __("Parallax", 'motopress-content-editor-lite')
                )
            ),
            'bg_video_youtube' => array(
                'type' => 'video',
                'label' => __("YouTube video URL:", 'motopress-content-editor-lite'),
                'default' => MPCEShortcode::DEFAULT_YOUTUBE_BG,
                'description' => __("Paste the URL of YouTube video you'd like to embed", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'youtube'
                )
            ),
            'bg_video_youtube_cover' => array(
                'type' => 'image',
                'label' => __("Select image to cover video", 'motopress-content-editor-lite'),
                'description' => __("Cover image will be extended automatically", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'youtube'
                )
            ),
            'bg_video_youtube_repeat' => array(
                'type' => 'checkbox',
                'label' => __("Repeat", 'motopress-content-editor-lite'),
                'default' => 'true',
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'youtube'
                )
            ),
            'bg_video_youtube_mute' => array(
                'type' => 'checkbox',
                'label' => __("Mute", 'motopress-content-editor-lite'),
                'default' => 'true',
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'youtube'
                )
            ),
            'bg_video_webm' => array(
                'type' => 'media-video',
                'legend' => __("Select video from Media Library in webm, mp4 and ogg formats for cross-browser compatibility. Use <a href='http://www.mirovideoconverter.com/' target='_blank'>video converter</a> to convert your video", 'motopress-content-editor-lite'),
                'label' => sprintf(__("Video source in %s format:", 'motopress-content-editor-lite'), 'WEBM'),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_mp4' => array(
                'type' => 'media-video',
                'label' => sprintf(__("Video source in %s format:", 'motopress-content-editor-lite'), 'MP4'),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_ogg' => array(
                'type' => 'media-video',
                'label' => sprintf(__("Video source in %s format:", 'motopress-content-editor-lite'), 'OGV'),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_cover' => array(
                'type' => 'image',
                'label' => __("Select image to cover video", 'motopress-content-editor-lite'),
                'description' => __("Cover image will be extended automatically", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_repeat' => array(
                'type' => 'checkbox',
                'label' => __("Repeat", 'motopress-content-editor-lite'),
                'default' => 'true',
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'bg_video_mute' => array(
                'type' => 'checkbox',
                'label' => __("Mute", 'motopress-content-editor-lite'),
                'default' => 'true',
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'video'
                )
            ),
            'parallax_image' => array(
                'type' => 'image',
                'label' => __("Select image for parallax effect", 'motopress-content-editor-lite'),
                'description' => __("Background image moves slower than the foreground content", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'parallax'
                )
            ),
            'parallax_bg_size' => array(
                'type' => 'select',
                'label' => __("Background size", 'motopress-content-editor-lite'),
	            'default' => 'normal',
                'list' => array(
	                'normal' => __("Normal", 'motopress-content-editor-lite'),
	                'cover' => __("Cover", 'motopress-content-editor-lite'),
	                'contain' => __("Contain", 'motopress-content-editor-lite'),
                ),
                'dependency' => array(
                    'parameter' => 'bg_media_type',
                    'value' => 'parallax'
                )
            ),
//            'parallax_speed' => array(
//                'type' => 'spinner',
//                'label' => '',
//                'description' => '',
//                'default' => 0.5,
//                'min' => -5,
//                'max' => 5,
//                'step' => 0.1,
//                'dependency' => array(
//                    'parameter' => 'bg_media_type',
//                    'value' => 'parallax'
//                )
//            ),
			'id' => array(
				'type' => 'text',
				'label' => __("Element unique ID", 'motopress-content-editor-lite'),
				'description' => __("Must start with a letter and contain dashes, underscores, letters or numbers", 'motopress-content-editor-lite')
			)
        );
		
        $rowObj = new MPCEObject(MPCEShortcode::PREFIX . 'row', __("Row", 'motopress-content-editor-lite'), null, $rowParameters, null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE);
		$rowObjStyle = array(
			'mp_style_classes' => array(
				'predefined' => $rowPredefinedStyles,
				'additional_description' => __("Note: some styles may work at a live site only.", 'motopress-content-editor-lite')
			),
			'mp_custom_style' => array(
				'limitation' => 'margin-horizontal'
			)
		);
        $rowObj->addStyle($rowObjStyle);

        $rowInnerObj = new MPCEObject(MPCEShortcode::PREFIX . 'row_inner', __("Inner Row", 'motopress-content-editor-lite'), null, $rowParameters, null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE);
        $rowInnerObj->addStyle($rowObjStyle);

        $spanObj = new MPCEObject(MPCEShortcode::PREFIX . 'span', __("Column", 'motopress-content-editor-lite'), null, null, null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE);
		$spanObjStyle = array(
			'mp_style_classes' => array(
				'predefined' => $spanPredefinedStyles
			),
			'mp_custom_style' => array(
				'limitation' => array('margin-horizontal')
			)
		);
        $spanObj->addStyle($spanObjStyle);

        $spanInnerObj = new MPCEObject(MPCEShortcode::PREFIX . 'span_inner', __("Inner Column", 'motopress-content-editor-lite'), 'column.png', null, null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE);
        $spanInnerObj->addStyle($spanObjStyle);

		$this->setGrid(array(
            'row' => array(
                'shortcode' => 'mp_row',
                'inner' => 'mp_row_inner',
                'class' => 'mp-row-fluid',
                'edgeclass' => 'mp-row-fluid',
                'col' => '12'
            ),
            'span' => array(
                'type' => 'single',
                'shortcode' => 'mp_span',
                'inner' => 'mp_span_inner',
                'class' => 'mp-span',
                'attr' => 'col',
                'custom_class_attr' => 'classes'
            )
        ));

/* TEXT */
        $textObj = new MPCEObject(MPCEShortcode::PREFIX . 'text', __("Paragraph", 'motopress-content-editor-lite'), 'text.png', array(
            'button' => array(
                'type' => 'editor-button',
                'label' => '',
                'default' => '',
                'description' => __("Click the text box to add and edit your content or click Edit", 'motopress-content-editor-lite') . ' ' . __("Paragraph", 'motopress-content-editor-lite'),
                'text' => __("Edit", 'motopress-content-editor-lite') . ' ' . __("Paragraph", 'motopress-content-editor-lite')
            )
        ), 20, MPCEObject::ENCLOSED);
        $textPredefinedStyles = array();
        $this->extendPredefinedWithGoogleFonts($textPredefinedStyles);
        $textObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => $textPredefinedStyles,
                'additional_description' => sprintf(__("Note: go to Dashboard - %s Settings to add Google Fonts.", 'motopress-content-editor-lite'), mpceSettings('brand_name'))
            )
        ));

/* HEADER */
        $headingObj = new MPCEObject(MPCEShortcode::PREFIX . 'heading', __("Title", 'motopress-content-editor-lite'), 'heading.png', array(
            'button' => array(
                'type' => 'editor-button',
                'label' => '',
                'default' => '',
                'description' => __("Click the text box to add and edit your content or click Edit", 'motopress-content-editor-lite') . ' ' . __("Title", 'motopress-content-editor-lite'),
                'text' => __("Edit", 'motopress-content-editor-lite') . ' ' . __("Title", 'motopress-content-editor-lite')
            )
        ), 10, MPCEObject::ENCLOSED);
        $headingPredefinedStyles = array();
        $this->extendPredefinedWithGoogleFonts($headingPredefinedStyles);
        $headingObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => $headingPredefinedStyles,
                'additional_description' => sprintf(__("Note: go to Dashboard - %s Settings to add Google Fonts.", 'motopress-content-editor-lite'), mpceSettings('brand_name'))
            )
        ));

/* CODE */        
        $codeObj = new MPCEObject(MPCEShortcode::PREFIX . 'code', __("WordPress Text", 'motopress-content-editor-lite'), 'wordpress.png', array(
            'button' => array(
                'type' => 'editor-button',
                'label' => '',
                'default' => '',
                'description' => __("Click the text box to add and edit your content or click Edit", 'motopress-content-editor-lite') . ' ' . __("WordPress Text", 'motopress-content-editor-lite'),
                'text' => __("Edit", 'motopress-content-editor-lite') . ' ' . __("WordPress Text", 'motopress-content-editor-lite')
            )
        ), 30, MPCEObject::ENCLOSED);
        $codePredefinedStyles = array();
        $this->extendPredefinedWithGoogleFonts($codePredefinedStyles);
        $codeObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => $codePredefinedStyles,
                'additional_description' => sprintf(__("Note: go to Dashboard - %s Settings to add Google Fonts.", 'motopress-content-editor-lite'),  mpceSettings('brand_name'))
            )
        ));

/* IMAGE */
        $imageObj = new MPCEObject(MPCEShortcode::PREFIX . 'image', __("Image", 'motopress-content-editor-lite'), 'image.png', array(
            'id' => array(
                'type' => 'image',
                'label' => __("Select Image", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => __("Choose an image from Media Library", 'motopress-content-editor-lite'),
                'autoOpen' => 'true'
            ),
            'size' => array(
                'type' => 'radio-buttons',
                'label' => __("Image size", 'motopress-content-editor-lite'),
                'default' => 'full',
                'list' => array(
                    'full' => __("Full", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite'),
                    'medium' => __("Medium", 'motopress-content-editor-lite'),
                    'thumbnail' => __("Thumbnail", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite')
                )
            ),
            'custom_size' => array(
                'type' => 'text',
                'description' => __("Image size in pixels, ex. 200x100 or theme-registered image size. Note: the closest-sized image will be used if original one does not exist.", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'size',
                    'value' => 'custom'
                ),
            ),
            'link_type' => array(
                'type' => 'radio-buttons',
                'label' => __("Link to", 'motopress-content-editor-lite'),
                'default' => 'custom_url',
                'list' => array(
                    'custom_url' => __("Custom URL", 'motopress-content-editor-lite'),
                    'media_file' => __("Media File", 'motopress-content-editor-lite'),
                    'lightbox' => __("Lightbox", 'motopress-content-editor-lite')
                )
            ),
            'link' => array(
                'type' => 'link',
                'label' => __("Link to", 'motopress-content-editor-lite'),
                'default' => '#',
                'description' => __("Click on image to open the link. (ex. http://yoursite.com/)", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'link_type',
                    'value' => 'custom_url'
                )
            ),
            'rel' => array(
                'type' => 'text',
                'label' => __("Link 'rel' value for your custom lightbox", 'motopress-content-editor-lite'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'link_type',
                    'value' => 'media_file'
                )
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => __("Open link in new window (tab)", 'motopress-content-editor-lite'),
                'default' => 'false'
            ),
            'caption' => array(
                'type' => 'checkbox',
                'label' => __("Show image caption", 'motopress-content-editor-lite'),
                'description' => __("You can set caption in media library", 'motopress-content-editor-lite'),
                'default' => 'false'
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => __("Alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                )
            )
        ), 10);
        $imageObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-image-obj-basic',
                    'label' => 'Image'
                ),
                'selector' => 'img'
            ),
			'mp_custom_style' => array(
				'selector' => 'img',
				'limitation' => array(
					'margin'
				)
			)
        ));

/* GRID GALLERY */
        $gridGalleryObj = new MPCEObject(MPCEShortcode::PREFIX . 'grid_gallery', __("Grid Gallery", 'motopress-content-editor-lite'),  'grid-gallery.png', array(
            'ids' => array(
                'type' => 'multi-images',
                'default' => '',
                'description' => __("Select images from Media Library", 'motopress-content-editor-lite'),
                'text' => __("Organize Images", 'motopress-content-editor-lite'),
                'autoOpen' => 'true'
            ),
            'columns' => array(
                'type' => 'radio-buttons',
                'label' => __("Columns count", 'motopress-content-editor-lite'),
                'default' => 3,
                'list' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    6 => 6
                )
            ),
            'size' => array(
                'type' => 'radio-buttons',
                'label' => __("Image size", 'motopress-content-editor-lite'),
                'default' => 'thumbnail',
                'list' => array(
                    'full' => __("Full", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite'),
                    'medium' => __("Medium", 'motopress-content-editor-lite'),
                    'thumbnail' => __("Thumbnail", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite')
                )
            ),
            'custom_size' => array(
                'type' => 'text',
                'description' => __("Image size in pixels, ex. 200x100 or theme-registered image size. Note: the closest-sized image will be used if original one does not exist.", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'size',
                    'value' => 'custom'
                ),
            ),
            'link_type' => array(
                'type' => 'radio-buttons',
                'label' => __("Link to", 'motopress-content-editor-lite'),
                'default' => 'lightbox',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'media_file' => __("Media File", 'motopress-content-editor-lite'),
                    'attachment' => __("Attachment Page", 'motopress-content-editor-lite'),
                    'lightbox' => __("Lightbox", 'motopress-content-editor-lite'),
                )
            ),
            'rel' => array(
                'type' => 'text',
                'label' => __("Link 'rel' value for your custom lightbox", 'motopress-content-editor-lite'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'link_type',
                    'value' => 'media_file'
                )
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => __("Open link in new window (tab)", 'motopress-content-editor-lite'),
                'default' => 'false',
            ),
            'caption' => array(
                'type' => 'checkbox',
                'label' => __("Show image caption", 'motopress-content-editor-lite'),
                'description' => __("You can set caption in media library", 'motopress-content-editor-lite'),
                'default' => 'false',
            )
        ), 30);
        $gridGalleryObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-grid-gallery-obj-basic',
                    'label' => 'Grid Gallery'
                )
            )
        ));

/* POSTS SLIDER */
        $postsSliderObj = new MPCEObject(MPCEShortcode::PREFIX . 'posts_slider', __("Posts Slider", 'motopress-content-editor-lite'), 'post-slider.png', array(
            'post_type' => array(
                'type' => 'select',
                'label' => __("Post types", 'motopress-content-editor-lite'),
                'default' => 'post',
                'list' =>MPCEShortcode::getPostTypes(true), // true to get pages
            ),
            'category' => array(
                'type' => 'text',
                'label' => __("Display posts by category slug", 'motopress-content-editor-lite'),
                'description' => __("Separate with ',' to display posts that have either of these categories or with '+' to display posts that have all of these categories.", 'motopress-content-editor-lite'),
				'dependency' => array(
                    'parameter' => 'post_type',
                    'value' => 'post'
                ),
            ),
            'tag' => array(
                'type' => 'text',
                'label' => __("Display posts by tag slug", 'motopress-content-editor-lite'),
                'description' => __("Separate with ',' to display posts that have either of these tags or with '+' to display posts that have all of these tags.", 'motopress-content-editor-lite'),
				'dependency' => array(
                    'parameter' => 'post_type',
                    'value' => 'post'
                ),
            ),
            'custom_tax' => array(
                'type' => 'text',
                'label' => __("Custom Taxonomy", 'motopress-content-editor-lite'),
                'default' => ''
            ),
            'custom_tax_field' => array(
                'type' => 'select',
                'label' => __("Taxonomy field", 'motopress-content-editor-lite'),
                'default' => 'slug',
                'list' => array(
                    'term_id' => __("Term ID", 'motopress-content-editor-lite'),
                    'slug' => __("Slug", 'motopress-content-editor-lite'),
                    'name' => __("Name", 'motopress-content-editor-lite')
                )
            ),
            'custom_tax_terms' => array(
                'type' => 'text',
                'label' => __("Taxonomy term(s)", 'motopress-content-editor-lite'),
                'default' => '',
                'description' =>__("Separate with ',' to display posts that have either of these terms or with '+' to display posts that have all of these tags.", 'motopress-content-editor-lite')
            ),
            'posts_count' => array(
                'type' => 'spinner',
                'label' => __("Posts count", 'motopress-content-editor-lite'),
                'default' => 3,
				'min' => 1,
                'max' => 100,
                'step' => 1
            ),
            'order_by' => array(
                'type' => 'select',
                'label' => __("Order by", 'motopress-content-editor-lite'),
                'default' => 'date',
                'list' => array(
                    'ID' => __("ID", 'motopress-content-editor-lite'),
                    'date' => __("Date", 'motopress-content-editor-lite'),
                    'author' => __("Author", 'motopress-content-editor-lite'),
                    'modified' => __("Modified", 'motopress-content-editor-lite'),
                    'rand' => __("Random", 'motopress-content-editor-lite'),
                    'comment_count' => __("Comment count", 'motopress-content-editor-lite'),
                    'menu_order' => __("Menu order", 'motopress-content-editor-lite'),
                ),
            ),
            'sort_order' => array(
                'type' => 'radio-buttons',
                'label' => __("Sort order", 'motopress-content-editor-lite'),
                'default' => 'DESC',
                'list' => array(
                    'ASC' => __("Ascending", 'motopress-content-editor-lite'),
                    'DESC' => __("Descending", 'motopress-content-editor-lite')
                ),
            ),
            'title_tag' => array(
                'type' => 'radio-buttons',
                'label' => __("Post title", 'motopress-content-editor-lite'),
                'default' => 'h2',
                'list' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'hide' => __("Hide", 'motopress-content-editor-lite'),
                )
            ),
            'show_content' => array(
                'type' => 'radio-buttons',
                'label' => __("Post description", 'motopress-content-editor-lite'),
                'default' => 'short',
                'list' => array(
                    'short' => __("Short", 'motopress-content-editor-lite'),
                    'full' => __("Full", 'motopress-content-editor-lite'),
                    'excerpt' => __("Excerpt", 'motopress-content-editor-lite'),
                    'hide' => __("None", 'motopress-content-editor-lite'),
                ),
            ),
            'short_content_length' => array(
                'type' => 'slider',
                'label' => __("Length of the Text", 'motopress-content-editor-lite'),
                'default' => 200,
                'min' => 0,
                'max' => 1000,
                'step' => 20,
                'dependency' => array(
                    'parameter' => 'show_content',
                    'value' => 'short'
                ),
            ),
            'image_size' => array(
                'type' => 'radio-buttons',
                'label' => __("Image size", 'motopress-content-editor-lite'),
                'default' => 'thumbnail',
                'list' => array(
                    'full' => __("Full", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite'),
                    'medium' => __("Medium", 'motopress-content-editor-lite'),
                    'thumbnail' => __("Thumbnail", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite')
                ),
				'dependency' => array(
					'parameter' => 'layout',
					'except' => 'title_text'
				)
            ),
            'custom_size' => array(
                'type' => 'text',
                'description' => __("Image size in pixels, ex. 200x100 or theme-registered image size. Note: the closest-sized image will be used if original one does not exist.", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'image_size',
                    'value' => 'custom'
                ),
            ),
            'layout' => array(
                'type' => 'select',
                'label' => __("Content style", 'motopress-content-editor-lite'),
                'default' => 'title_img_text_wrap',
                'list' => array(
                    //'title_img_text' => $motopressCELang->CEPostsSliderLayoutTitleImageText,
                    'img_title_text' => __("Image, Title, Text", 'motopress-content-editor-lite'),
                    //'title_img_inline' => $motopressCELang->CEPostsSliderLayoutImageTitleInline,
                    'title_img_text_wrap' => __("Title, Image and Text", 'motopress-content-editor-lite'),
					'title_text'=> __("Title, Text", 'motopress-content-editor-lite')
                ),
            ),
            'img_position' => array(
                'type' => 'radio-buttons',
                'label' => __("Image position", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite'),
                ),
				'dependency' => array(
					'parameter' => 'layout',
					'except' => 'title_text'
				)
            ),
            'post_link' => array(
                'type' => 'select',
                'label' => __("Link to", 'motopress-content-editor-lite'),
                'default' => 'link_to_post',
                'list' => array(
                    'link_to_post' => __("Original post", 'motopress-content-editor-lite'),
                    'custom_link' => __("Custom links", 'motopress-content-editor-lite'),
                    'no_link' => __("None", 'motopress-content-editor-lite'),
                ),
            ),
            'custom_links' => array(
                'type' => 'longtext',
                'label' => __("Custom links", 'motopress-content-editor-lite'),
                'default' => site_url(),
                'description' => __("Enter links for each slide here. Divide links with linebreaks (Enter).", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'post_link',
                    'value' => 'custom_link'
                ),
            ),
            'slideshow_speed' => array(
                'type' => 'radio-buttons',
                'label' => __("Auto rotate (s)", 'motopress-content-editor-lite'),
                'default' => '15000',
                'list' => array(
                    '3000' => '3',
                    '5000' => '5',
                    '10000' => '10',
                    '15000' => '15',
                    '25000' => '25',
                    'disable' => __("Disable", 'motopress-content-editor-lite'),
                ),
            ),
            'animation' => array(
                'type' => 'select',
                'label' => __("Animation type", 'motopress-content-editor-lite'),
                'default' => 'fade',
                'list' => array(
                    'slide' => __("Slide", 'motopress-content-editor-lite'),
                    'fade' => __("Fade", 'motopress-content-editor-lite'),
                ),
            ),
            'smooth_height' => array(
                'type' => 'checkbox',
                'label' => __("Smooth height", 'motopress-content-editor-lite'),
                'default' => 'true',
                'description' => __("Animate the height of the slider smoothly for slides of varying height", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'animation',
                    'value' => 'slide'
                )
            ),
            'show_nav' => array(
                'type' => 'checkbox',
                'label' => __("Show bullets", 'motopress-content-editor-lite'),
                'default' => 'true',
            ),
            'pause_on_hover' => array(
                'type' => 'checkbox',
                'label' => __("Pause on hover", 'motopress-content-editor-lite'),
                'default' => 'true',
            ),
        ), 35);

/* IMAGE SLIDER */
        $imageSlider = new MPCEObject(MPCEShortcode::PREFIX . 'image_slider', __("Slider", 'motopress-content-editor-lite'), 'image-slider.png', array(
            'ids' => array(
                'type' => 'multi-images',
                'label' => __("Edit Slides", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => __("Select images from Media Library", 'motopress-content-editor-lite'),
                'text' => __("Organize Images", 'motopress-content-editor-lite'),
                'autoOpen' => 'true'
            ),
            'size' => array(
                'type' => 'radio-buttons',
                'label' => __("Image size", 'motopress-content-editor-lite'),
                'default' => 'full',
                'list' => array(
                    'full' => __("Full", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite'),
                    'medium' => __("Medium", 'motopress-content-editor-lite'),
                    'thumbnail' => __("Thumbnail", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite')
                )
            ),
            'custom_size' => array(
                'type' => 'text',
                'description' => __("Image size in pixels, ex. 200x100 or theme-registered image size. Note: the closest-sized image will be used if original one does not exist.", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'size',
                    'value' => 'custom'
                ),
            ),
            'animation' => array(
                'type' => 'radio-buttons',
                'label' => __("Animation type", 'motopress-content-editor-lite'),
                'default' => 'fade',
                'description' => __("Preview the page to view the animation", 'motopress-content-editor-lite'),
                'list' => array(
                    'fade' => __("Fade", 'motopress-content-editor-lite'),
                    'slide' => __("Slide", 'motopress-content-editor-lite')
                )
            ),
            'smooth_height' => array(
                'type' => 'checkbox',
                'label' => __("Smooth height", 'motopress-content-editor-lite'),
                'default' => 'false',
                'description' => __("Animate the height of the slider smoothly for slides of varying height", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'animation',
                    'value' => 'slide'
                )
            ),
            'slideshow' => array(
                'type' => 'checkbox',
                'label' => __("Enable slideshow", 'motopress-content-editor-lite'),
                'default' => 'true',
                'description' => __("The slideshow will start automatically when the page is loaded", 'motopress-content-editor-lite')
            ),
            'slideshow_speed' => array(
                'type' => 'slider',
                'label' => __("Slideshow speed in seconds", 'motopress-content-editor-lite'),
                'default' => 7,
                'min' => 1,
                'max' => 20,
                'dependency' => array(
                    'parameter' => 'slideshow',
                    'value' => 'true'
                )
            ),
            'animation_speed' => array(
                'type' => 'slider',
                'label' => __("Animation speed in milliseconds", 'motopress-content-editor-lite'),
                'default' => 600,
                'min' => 200,
                'max' => 10000,
                'step' => 200
            ),
            'control_nav' => array(
                'type' => 'checkbox',
                'label' => __("Show bullets", 'motopress-content-editor-lite'),
                'default' => 'true'
            )
        ), 20);
        $imageSlider->addStyle(array(
            'mp_style_classes' => array(
                'selector' => '> ul.slides'
            ),
//			'mp_custom_style' => array(
//				'selector' => '> ul.slides'
//			)
        ));

/* BUTTON */
	    $buttonParameters = array(
            'text' => array(
                'type' => 'text',
                'label' => __("Text on the button", 'motopress-content-editor-lite'),
                'default' => __("Button", 'motopress-content-editor-lite')
            ),
            'link' => array(
                'type' => 'link',
                'label' => __("Link", 'motopress-content-editor-lite'),
                'default' => '#',
                'description' => __("ex. http://yoursite.com/ or /blog", 'motopress-content-editor-lite')
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => __("Open link in new window (tab)", 'motopress-content-editor-lite'),
                'default' => 'false'
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => __("Icon", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => $this->getIconClassList(true)
            ),
            'icon_position' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                ),
            ),
            'full_width' => array(
                'type' => 'checkbox',
                'label' => __("Stretch", 'motopress-content-editor-lite'),
                'default' => 'false'
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => __("Alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'full_width',
                    'value' => 'false'
                )
            ),
        );
		
        $buttonStyles = array(			
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-btn',
                    'label' => __("Button", 'motopress-content-editor-lite')
                ),
                'predefined' => array(
                    'color' => array(
                        'label' => __("Button color", 'motopress-content-editor-lite'),
                        'values' => array(
                            'silver' => array(
                                'class' => 'motopress-btn-color-silver',
                                'label' => __("Silver", 'motopress-content-editor-lite')
                            ),
                            'red' => array(
                                'class' => 'motopress-btn-color-red',
                                'label' => __("Red", 'motopress-content-editor-lite')
                            ),
                            'pink-dreams' => array(
                                'class' => 'motopress-btn-color-pink-dreams',
                                'label' => __("Pink Dreams", 'motopress-content-editor-lite')
                            ),
                            'warm' => array(
                                'class' => 'motopress-btn-color-warm',
                                'label' => __("Warm", 'motopress-content-editor-lite')
                            ),
                            'hot-summer' => array(
                                'class' => 'motopress-btn-color-hot-summer',
                                'label' => __("Hot Summer", 'motopress-content-editor-lite')
                            ),
                            'olive-garden' => array(
                                'class' => 'motopress-btn-color-olive-garden',
                                'label' => __("Olive Garden", 'motopress-content-editor-lite')
                            ),
                            'green-grass' => array(
                                'class' => 'motopress-btn-color-green-grass',
                                'label' => __("Green Grass", 'motopress-content-editor-lite')
                            ),
                            'skyline' => array(
                                'class' => 'motopress-btn-color-skyline',
                                'label' => __("Skyline", 'motopress-content-editor-lite')
                            ),
                            'aqua-blue' => array(
                                'class' => 'motopress-btn-color-aqua-blue',
                                'label' => __("Aqua Blue", 'motopress-content-editor-lite')
                            ),
                            'violet' => array(
                                'class' => 'motopress-btn-color-violet',
                                'label' => __("Violet", 'motopress-content-editor-lite')
                            ),
                            'dark-grey' => array(
                                'class' => 'motopress-btn-color-dark-grey',
                                'label' => __("Dark Grey", 'motopress-content-editor-lite')
                            ),
                            'black' => array(
                                'class' => 'motopress-btn-color-black',
                                'label' => __("Black", 'motopress-content-editor-lite')
                            )
                        )
                    ),
                    'size' => array(
                        'label' => __("Size", 'motopress-content-editor-lite'),
                        'values' => array(
                            'mini' => array(
                                'class' => 'motopress-btn-size-mini',
                                'label' => __("Mini", 'motopress-content-editor-lite')
                            ),
                            'small' => array(
                                'class' => 'motopress-btn-size-small',
                                'label' => __("Small", 'motopress-content-editor-lite')
                            ),
                            'middle' => array(
                                'class' => 'motopress-btn-size-middle',
                                'label' => __("Middle", 'motopress-content-editor-lite')
                            ),
                            'large' => array(
                                'class' => 'motopress-btn-size-large',
                                'label' => __("Large", 'motopress-content-editor-lite')
                            )
                        )
                    ),
                    'icon indent' => array(
                        'label' => __("Icon indent", 'motopress-content-editor-lite'),
                        'values' => array(
                            'mini' => array(
                                'class' => 'motopress-btn-icon-indent-mini',
                                'label' => __("Mini", 'motopress-content-editor-lite') . ' ' . __("Icon indent", 'motopress-content-editor-lite')
                            ),
                            'small' => array(
                                'class' => 'motopress-btn-icon-indent-small',
	                            'label' => __("Small", 'motopress-content-editor-lite') . ' ' . __("Icon indent", 'motopress-content-editor-lite')
                            ),
                            'middle' => array(
                                'class' => 'motopress-btn-icon-indent-middle',
	                            'label' => __("Middle", 'motopress-content-editor-lite') . ' ' . __("Icon indent", 'motopress-content-editor-lite')
                            ),
                            'large' => array(
                                'class' => 'motopress-btn-icon-indent-large',
	                            'label' => __("Large", 'motopress-content-editor-lite') . ' ' . __("Icon indent", 'motopress-content-editor-lite')
                            )
                        ),
                    ),
                    'rounded' => array(
                        'class' => 'motopress-btn-rounded',
                        'label' => __("Rounded", 'motopress-content-editor-lite')
                    )
                ),
                'default' => array('motopress-btn-color-silver', 'motopress-btn-size-middle', 'motopress-btn-rounded', 'motopress-btn-icon-indent-small'),
                'selector' => '> a'
            ),
			'mp_custom_style' => array(
				'selector' => '> a'
			)
        );

        $buttonObj = new MPCEObject(MPCEShortcode::PREFIX . 'button', __("Button", 'motopress-content-editor-lite'), 'button.png', $buttonParameters, 10);
        $buttonObj->addStyle($buttonStyles);

	    
	   
/* DOWNLOAD BUTTON*/        
        $downloadButtonObj = new MPCEObject(MPCEShortcode::PREFIX . 'download_button', __("Download Button", 'motopress-content-editor-lite'), 'download-button.png', array(
			'attachment' => array(
                'type' => 'media',
                'returnMode' => 'id', // url or id
                'label' => __("Media File", 'motopress-content-editor-lite'),
                'description' => __("Select file from Media Library", 'motopress-content-editor-lite'),
                'default' => '',
            ),
			'text' => array(
                'type' => 'text',
                'label' => __("Text on the button", 'motopress-content-editor-lite'),
                'default' => 'Download'
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => __("Icon", 'motopress-content-editor-lite'),
                'default' => 'fa fa-download',
                'list' => $this->getIconClassList(true)
            ),
            'icon_position' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
            ),
            'full_width' => array(
                'type' => 'checkbox',
                'label' => __("Stretch", 'motopress-content-editor-lite'),
                'default' => 'false'
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => __("Alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'full_width',
                    'value' => 'false'
                )
            )
		), 30);
		
	    $downloadButtonObj->addStyle($buttonStyles);


/* ICON */
        $iconObj = new MPCEObject(MPCEShortcode::PREFIX . 'icon', __("Icon", 'motopress-content-editor-lite'), 'icon.png', array(
            'icon' => array(
                'type' => 'icon-picker',
                'label' => __("Icon", 'motopress-content-editor-lite'),
                'default' => 'fa fa-star',
                'list' => $this->getIconClassList()
            ),
            'icon_color' => array(
                'type' => 'color-picker',
                'label' => __("Icon color", 'motopress-content-editor-lite'),
                'default' => '#e6cf03',
            ),
            'icon_size' => array(
                'type' => 'select',
                'label' => __("Size", 'motopress-content-editor-lite'),
                'default' => 'large',
                'list' => array(
                    'mini' => __("Mini", 'motopress-content-editor-lite'),
                    'small' => __("Small", 'motopress-content-editor-lite'),
                    'middle' => __("Middle", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite'),
                    'extra-large' => __("Extra Large", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite'),
                ),
            ),
            'icon_size_custom' => array(
		        'type' => 'spinner',
		        'label' => __("Icon custom size", 'motopress-content-editor-lite'),
		        'description' => __("Font size in px", 'motopress-content-editor-lite'),
		        'min' => 1,
		        'step' => 1,
		        'max' => 500,
		        'default' => 26,
		        'dependency' => array(
			        'parameter' => 'icon_size',
			        'value' => 'custom'
		        )
	        ),   
            'icon_alignment' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon alignment", 'motopress-content-editor-lite'),
                'default' => 'center',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite'),
                ),
            ),
            'bg_shape' => array(
                'type' => 'select',
                'label' => __("Background shape", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'circle' => __("Circle", 'motopress-content-editor-lite'),
                    'square' => __("Square", 'motopress-content-editor-lite'),
                    'rounded' => __("Rounded", 'motopress-content-editor-lite'),
                    'outline-circle' => __("Outline Circle", 'motopress-content-editor-lite'),
                    'outline-square' => __("Outline Square", 'motopress-content-editor-lite'),
                    'outline-rounded' => __("Outline Rounded", 'motopress-content-editor-lite'),
                ),
            ),
             'icon_background_size' => array(
                'type' => 'spinner',
                'label' => __("Icon background size", 'motopress-content-editor-lite'),
                'default' => 1.5,
                'min' => 1,
                'max' => 3,
                'step' => 0.1,
                'dependency' => array(
                    'parameter' => 'bg_shape',
                    'except' => 'none'
                )
            ),
            'bg_color' => array(
                'type' => 'color-picker',
                'label' => __("Icon background color", 'motopress-content-editor-lite'),
                'default' => '#42414f',
                'dependency' => array(
                    'parameter' => 'bg_shape',
                    'except' => 'none'
                ),
            ),
            'animation' => array(
                'type' => 'select',
                'label' => __("Appearance effect", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'top-to-bottom' => __("Top to bottom", 'motopress-content-editor-lite'),
                    'bottom-to-top' => __("Bottom to top", 'motopress-content-editor-lite'),
                    'left-to-right' => __("Left to right", 'motopress-content-editor-lite'),
                    'right-to-left' => __("Right to left", 'motopress-content-editor-lite'),
                    'appear' => __("Appear", 'motopress-content-editor-lite'),
                ),
            ),
            'link' => array(
                'type' => 'link',
                'label' => __("Link", 'motopress-content-editor-lite'),
                'default' => ''
            ),
        ), 70);
		$iconObj->addStyle(array(
			'mp_custom_style' => array(
				'limitation' => 'padding'
			)
		));
           
/* COUNTDOWN TIMER */
       $countdownTimerObj = new MPCEObject(MPCEShortcode::PREFIX . 'countdown_timer', __("Countdown Timer", 'motopress-content-editor-lite'), 'countdown-timer.png', array(

             'date' => array(
                'type' => 'datetime-picker',
                'displayMode' => 'datetime', // date | datetime (default)
                'returnMode' => 'YYYY-MM-DD H:m:s', // mysql format uses here (default: Y-m-d H:i:s )
                'label' => __("Expiration Date", 'motopress-content-editor-lite'),
                'default' => '',
             ),
             'time_zone' => array(
                 'type' => 'select',
                 'label' => __("Time zone", 'motopress-content-editor-lite'),
                'default' => 'server_time',
                'list' => array(
                    'server_time' => __("Server time", 'motopress-content-editor-lite'),
                    'user_local' => __("User's local time", 'motopress-content-editor-lite')
                ),
             ),
            'format' => array(
                'type' => 'text',
                'label' => __("Format", 'motopress-content-editor-lite'),
                'default' => 'yowdHMS',
                'description' => __("Use 'Y' for years, 'O' for months, 'W' for weeks, 'D' for days, 'H' for hours, 'M' for minutes, 'S' for seconds. Upper-case characters for required fields and lower-case characters for display only if non-zero.", 'motopress-content-editor-lite')
            ),
            'block_color' => array(
                'type' => 'color-picker',
                'label' => __("Background Color", 'motopress-content-editor-lite'),
                'default' => '#333333',
            ),
            'font_color' => array(
                'type' => 'color-picker',
                'label' => __("Text Color", 'motopress-content-editor-lite'),
                'default' => '#ffffff',
            ),
            'blocks_size' => array(
		        'type' => 'spinner',
		        'label' => __("Block size", 'motopress-content-editor-lite'),
		        'min' => 1,
		        'step' => 1,
		        'max' => 480,
		        'default' => 60,
	        ),
            'digits_font_size' => array(
		        'type' => 'slider',
		        'label' => __("Digits size", 'motopress-content-editor-lite'),
		        'min' => 8,
		        'step' => 1,
		        'max' => 300,
		        'default' => 36
	        ),
            'labels_font_size' => array(
		        'type' => 'slider',
		        'label' => __("Text size", 'motopress-content-editor-lite'),
		        'min' => 6,
		        'step' => 1,
		        'max' => 96,
		        'default' => 12
	        ),
            'blocks_space' => array(
		        'type' => 'spinner',
		        'label' => __("Spacing", 'motopress-content-editor-lite'),
		        'min' => 0,
		        'step' => 1,
		        'max' => 160,
		        'default' => 10,
	        ),
         ), 70);
       

/* ACCORDION */
        $accordionObj = new MPCEObject(MPCEShortcode::PREFIX . 'accordion', __("Accordion", 'motopress-content-editor-lite'), 'accordion.png', array(
            'elements' => array(
                'type' => 'group',
                'contains' => MPCEShortcode::PREFIX . 'accordion_item',
                'items' => array(
                    'label' => array(
                        'default' => __("Section title", 'motopress-content-editor-lite'),
                        'parameter' => 'title'
                    ),
                    'count' => 2
                ),
                'text' => sprintf(__("Add new %s item", 'motopress-content-editor-lite'), __("Accordion", 'motopress-content-editor-lite')),
                'activeParameter' => 'active',
                'rules' => array(
                    'rootSelector' => '.motopress-accordion-item',
                    'activeSelector' => '> h3',
                    'activeClass' => 'ui-state-active'
                ),
                'events' => array(
                    'onActive' => array(
                        'selector' => '> h3',
                        'event' => 'click'
                    ),
                    'onInactive' => array(
                        'selector' => '> h3',
                        'event' => 'click'
                    )
                )
            ),
        ), 25, MPCEObject::ENCLOSED);
        $accordionObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-accordion',
                    'label' => __("Accordion", 'motopress-content-editor-lite')
                ),
                'predefined' => array(
                    'style' => array(
                        'label' => __("Style", 'motopress-content-editor-lite'),
                        'values' => array(
                            'light' => array(
                                'class' => 'motopress-accordion-light',
                                'label' => __("Light", 'motopress-content-editor-lite')
                            ),
                            'dark' => array(
                                'class' => 'motopress-accordion-dark',
                                'label' => __("Dark", 'motopress-content-editor-lite')
                            )
                        )
                    )
                ),
                'default' => array('motopress-accordion-light')
            )
        ));

        $accordionItemObj = new MPCEObject(MPCEShortcode::PREFIX . 'accordion_item', __("Accordion Section", 'motopress-content-editor-lite'), null, array(
            'title' => array(
                'type' => 'text',
                'label' => __("Section title", 'motopress-content-editor-lite'),
                'default' => __("Section title", 'motopress-content-editor-lite')
            ),
            'content' => array(
                'type' => 'longtext-tinymce',
                'label' => __("Section content", 'motopress-content-editor-lite'),
                'default' => __("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam eu hendrerit nunc. Proin tempus pulvinar augue, quis ultrices urna consectetur non.", 'motopress-content-editor-lite'),
                'text' => __("Open in WordPress Editor", 'motopress-content-editor-lite'),
                'saveInContent' => 'true'
            ),
            'active' => array(
                'type' => 'group-checkbox',
                'label' => __("Active", 'motopress-content-editor-lite'),
                'default' => 'false',
                'description' => sprintf(__("Only one %s can be active at a time", 'motopress-content-editor-lite'), __("Accordion Section", 'motopress-content-editor-lite'))
            )
        ), null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE, false);

/* TABS */
        $tabsObj = new MPCEObject(MPCEShortcode::PREFIX . 'tabs', __("Tabs", 'motopress-content-editor-lite'), 'tabs.png', array(
            'elements' => array(
                'type' => 'group',
                'contains' => MPCEShortcode::PREFIX . 'tab',
                'items' => array(
                    'label' => array(
                        'default' => __("Tab title", 'motopress-content-editor-lite'),
                        'parameter' => 'title'
                    ),
                    'count' => 2
                ),
                'text' => sprintf(__("Add new %s item", 'motopress-content-editor-lite'), __("Tab", 'motopress-content-editor-lite')),
	            'activeParameter' => 'active',
                'rules' => array(
                    'rootSelector' => '.ui-tabs-nav > li',
                    'activeSelector' => '',
                    'activeClass' => 'ui-state-active'
                ),
                'events' => array(
                    'onActive' => array(
                        'selector' => '> a',
                        'event' => 'click'
                    )
                ),
            ),
            'padding' => array(
                'type' => 'slider',
                'label' => __("Space between borders and tab content", 'motopress-content-editor-lite'),
                'default' => 20,
                'min' => 0,
                'max' => 50,
                'step' => 10
            ),
            'vertical' => array(
                'type' => 'checkbox',
                'label' => __("Vertical Tabs", 'motopress-content-editor-lite'),
                'default' => 'false'
            ),
            'rotate' => array(
                'type' => 'radio-buttons',
                'label' => __("Auto rotate (s)", 'motopress-content-editor-lite'),
                'default' => 'disable',
                'list' => array(
                    'disable' => __("Disable", 'motopress-content-editor-lite'),
                    '3000' => '3',
                    '5000' => '5',
                    '10000' => '10',
                    '15000' => '15',
                )
            ),
        ), 20, MPCEObject::ENCLOSED);
        $tabsObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-tabs-basic',
                    'label' => __("Tabs", 'motopress-content-editor-lite')
                ),
                'predefined' => array(
                    'style' => array(
                        'label' => __("Navigation", 'motopress-content-editor-lite'),
                        'values' => array(
                            'full-width' => array(
                                'class' => 'motopress-tabs-fullwidth',
                                'label' => __("Full width navigation", 'motopress-content-editor-lite')
                            )
                        )
                    ),
                ),
                'selector' => ''
            )
        ));

        $tabObj = new MPCEObject(MPCEShortcode::PREFIX . 'tab', __("Tab", 'motopress-content-editor-lite'), null, array(
            'id' => array(
                'type' => 'text-hidden',
	            'unique' => true
            ),
            'title' => array(
                'type' => 'text',
                'label' => __("Tab title", 'motopress-content-editor-lite'),
                'default' => __("Tab title", 'motopress-content-editor-lite')
            ),
            'content' => array(
                'type' => 'longtext-tinymce',
                'label' => __("Tab content", 'motopress-content-editor-lite'),
                'default' => __("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam eu hendrerit nunc. Proin tempus pulvinar augue, quis ultrices urna consectetur non.", 'motopress-content-editor-lite'),
                'text' => __("Open in WordPress Editor", 'motopress-content-editor-lite'),
                'saveInContent' => 'true'
            ),
	        'icon' => array(
		        'type' => 'icon-picker',
		        'label' => __("Icon", 'motopress-content-editor-lite'),
		        'default' => 'none',
		        'list' => $this->getIconClassList(true)
	        ),
	        'icon_size' => array(
		        'type' => 'radio-buttons',
		        'label' => __("Icon size", 'motopress-content-editor-lite'),
		        'default' => 'normal',
		        'list' => array(
			        'normal' => __("Normal", 'motopress-content-editor-lite'),
			        'custom' => __("Custom", 'motopress-content-editor-lite'),
		        ),
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_custom_size' => array(
		        'type' => 'spinner',
		        'label' => __("Icon custom size", 'motopress-content-editor-lite'),
		        'description' => __("Font size in px", 'motopress-content-editor-lite'),
		        'min' => 1,
		        'step' => 1,
		        'max' => 500,
		        'default' => 26,
		        'dependency' => array(
			        'parameter' => 'icon_size',
			        'value' => 'custom'
		        )
	        ),
	        'icon_color' => array(
		        'type' => 'color-select',
		        'label' => __("Icon color", 'motopress-content-editor-lite'),
		        'default' => 'inherit',
		        'list' => array(
					'inherit' => __("Inherit", 'motopress-content-editor-lite'),
			        'mp-text-color-black' => __("Black", 'motopress-content-editor-lite'),
			        'mp-text-color-red' => __("Red", 'motopress-content-editor-lite'),
			        'mp-text-color-pink-dreams' => __("Pink Dreams", 'motopress-content-editor-lite'),
			        'mp-text-color-warm' => __("Warm", 'motopress-content-editor-lite'),
			        'mp-text-color-hot-summer' => __("Hot Summer", 'motopress-content-editor-lite'),
			        'mp-text-color-olive-garden' => __("Olive Garden", 'motopress-content-editor-lite'),
			        'mp-text-color-green-grass' => __("Green Grass", 'motopress-content-editor-lite'),
			        'mp-text-color-skyline' => __("Skyline", 'motopress-content-editor-lite'),
			        'mp-text-color-aqua-blue' => __("Aqua Blue", 'motopress-content-editor-lite'),
			        'mp-text-color-violet' => __("Violet", 'motopress-content-editor-lite'),
			        'mp-text-color-dark-grey' => __("Dark Grey", 'motopress-content-editor-lite'),
			        'mp-text-color-default' => __("Silver", 'motopress-content-editor-lite'),
			        'custom' => __("Custom", 'motopress-content-editor-lite'),
		        ),
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_custom_color' => array(
		        'type' => 'color-picker',
		        'label' => __("Icon custom color", 'motopress-content-editor-lite'),
		        'default' => '#000000',
		        'dependency' => array(
			        'parameter' => 'icon_color',
			        'value' => 'custom'
		        )
	        ),
	        'icon_margin_left' => array(
		        'type' => 'spinner',
		        'label' => __("Icon margin Left", 'motopress-content-editor-lite'),
		        'min' => 0,
		        'max' => 500,
		        'step' => 1,
		        'default' => '5',
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_margin_right' => array(
		        'type' => 'spinner',
		        'label' => __("Icon margin Right", 'motopress-content-editor-lite'),
		        'min' => 0,
		        'max' => 500,
		        'step' => 1,
		        'default' => '5',
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_margin_top' => array(
		        'type' => 'spinner',
		        'label' => __("Icon margin Top", 'motopress-content-editor-lite'),
		        'min' => 0,
		        'max' => 500,
		        'step' => 1,
		        'default' => '0',
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'icon_margin_bottom' => array(
		        'type' => 'spinner',
		        'label' => __("Icon margin Bottom", 'motopress-content-editor-lite'),
		        'min' => 0,
		        'max' => 500,
		        'step' => 1,
		        'default' => '0',
		        'dependency' => array(
                    'parameter' => 'icon',
                    'except' => 'none'
                )
	        ),
	        'active' => array(
		        'type' => 'group-checkbox',
		        'label' => __("Active", 'motopress-content-editor-lite'),
		        'default' => 'false',
		        'description' => sprintf(__("Only one %s can be active at a time", 'motopress-content-editor-lite'), __("Tab", 'motopress-content-editor-lite'))
	        )
        ), null, MPCEObject::ENCLOSED, MPCEObject::RESIZE_NONE, false);

/* SOCIAL BUTTONS */
        $socialsObj = new MPCEObject(MPCEShortcode::PREFIX . 'social_buttons', __("Social Share Buttons", 'motopress-content-editor-lite'), 'social-buttons.png', array(
            'align' => array(
                'type' => 'radio-buttons',
                'label' => __("Alignment", 'motopress-content-editor-lite'),
                'default' => 'motopress-text-align-left',
                'list' => array(
                    'motopress-text-align-left' => __("Left", 'motopress-content-editor-lite'),
                    'motopress-text-align-center' => __("Center", 'motopress-content-editor-lite'),
                    'motopress-text-align-right' => __("Right", 'motopress-content-editor-lite')
                )
            )
        ), 40, MPCEObject::ENCLOSED);
        $socialsObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => array(
                    'size' => array(
                        'label' => __("Size", 'motopress-content-editor-lite'),
                        'values' => array(
                            'normal' => array(
                                'class' => 'motopress-buttons-32x32',
                                'label' => __("Middle", 'motopress-content-editor-lite')
                            ),
                            'large' => array(
                                'class' => 'motopress-buttons-64x64',
                                'label' => __("Large", 'motopress-content-editor-lite')
                            )
                        )
                    ),
                    'style' => array(
                        'label' => __("Style", 'motopress-content-editor-lite'),
                        'values' => array(
                            'plain' => array(
                                'class' => 'motopress-buttons-square',
                                'label' => __("Plain", 'motopress-content-editor-lite')
                            ),
                            'rounded' => array(
                                'class' => 'motopress-buttons-rounded',
                                'label' => __("Rounded", 'motopress-content-editor-lite')
                            ),
                            'circular' => array(
                                'class' => 'motopress-buttons-circular',
                                'label' => __("Circular", 'motopress-content-editor-lite')
                            ),
                            'volume' => array(
                                'class' => 'motopress-buttons-volume',
                                'label' => __("Volume", 'motopress-content-editor-lite')
                            )
                        )
                    )
                ),
                'default' => array('motopress-buttons-32x32', 'motopress-buttons-square')
            )
        ));

/* SOCIAL PROFILE/LINKS */
        $socialProfileObj = new MPCEObject(MPCEShortcode::PREFIX . 'social_profile', __("Social Buttons", 'motopress-content-editor-lite'), 'social-profile.png', array(
            'facebook' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'Facebook'),
                'default' => 'https://www.facebook.com/motopressapp'
            ),
            'google' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'Google+'),
                'default' => 'https://plus.google.com/+Getmotopress/posts'
            ),
            'twitter' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'Twitter'),
                'default' => 'https://twitter.com/motopressapp'
            ),
            'pinterest' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'Pinterest'),
                'default' => 'http://www.pinterest.com/motopress/'
            ),
            'linkedin' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'LinkedIn'),
            ),
            'flickr' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'Flickr'),
            ),
            'vk' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'VK'),
            ),
            'delicious' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'Delicious'),
            ),
            'youtube' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'YouTube'),
                'default' => 'https://www.youtube.com/channel/UCtkDYmIQ5Lv_z8KbjJ2lpFQ'
            ),
            'rss' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'RSS'),
                'default' => 'https://motopress.com/feed/'
            ),
            'instagram' => array(
                'type' => 'text',
                'label' => sprintf(__("%s URL", 'motopress-content-editor-lite'),  'Instagram'),
                'default' => ''
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => __("Alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                )
            )
        ), 50);
        $socialProfileObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => array(
                    'size' => array(
                        'label' => __("Size", 'motopress-content-editor-lite'),
                        'values' => array(
                            'normal' => array(
                                'class' => 'motopress-buttons-32x32',
                                'label' => __("Middle", 'motopress-content-editor-lite')
                            ),
                            'large' => array(
                                'class' => 'motopress-buttons-64x64',
                                'label' => __("Large", 'motopress-content-editor-lite')
                            )
                        )
                    ),
                    'style' => array(
                        'label' => __("Style", 'motopress-content-editor-lite'),
                        'values' => array(
                            'plain' => array(
                                'class' => 'motopress-buttons-square',
                                'label' => __("Plain", 'motopress-content-editor-lite')
                            ),
                            'rounded' => array(
                                'class' => 'motopress-buttons-rounded',
                                'label' => __("Rounded", 'motopress-content-editor-lite')
                            ),
                            'circular' => array(
                                'class' => 'motopress-buttons-circular',
                                'label' => __("Circular", 'motopress-content-editor-lite')
                            ),
                            'volume' => array(
                                'class' => 'motopress-buttons-volume',
                                'label' => __("Volume", 'motopress-content-editor-lite')
                            )
                        )
                    )
                ),
                'default' => array('motopress-buttons-32x32', 'motopress-buttons-square')
            )
        ));


/* VIDEO */
        $videoObj = new MPCEObject(MPCEShortcode::PREFIX . 'video', __("Video", 'motopress-content-editor-lite'), 'video.png', array(
            'src' => array(
                'type' => 'video',
                'label' => __("Video URL", 'motopress-content-editor-lite'),
                'default' => MPCEShortcode::DEFAULT_VIDEO,
                'description' => __("Paste the URL of a video (Vimeo or YouTube) you'd like to embed", 'motopress-content-editor-lite')
            )
        ), 10);
        $videoObj->addStyle(array(
            'mp_style_classes' => array(
                'selector' => '> iframe'
            ),
			'mp_custom_style' => array(
				'selector' => '> iframe',
				'limitation' => array(
					'margin'
				)
			)
        ));

/* AUDIO */
         $wpAudioObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_audio', __("Audio", 'motopress-content-editor-lite'), 'player.png', array(
            'source' => array(
                'type' => 'select',
                'label' => __("Audio source", 'motopress-content-editor-lite'),
                'description' => __("If your current browser does not support HTML5 audio or Flash Player is not installed, a direct download link will be displayed instead of the player", 'motopress-content-editor-lite'),
                'list' => array(
                    'library' => __("Media Library", 'motopress-content-editor-lite'),
                    'external' => __("Audio file URL", 'motopress-content-editor-lite'),
                ),
                'default' => 'external'
            ),
            'id' => array(
                'type' => 'audio',
                'label' => __("Audio File", 'motopress-content-editor-lite'),
                'description' => __("Select audio file from Media Library", 'motopress-content-editor-lite'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'source',
                    'value' => 'library'
                )
                ),
            'url' => array(
                'type' => 'text',
                'label' => __("Audio file URL", 'motopress-content-editor-lite'),
                'description' => __("Supported formats: .mp3, .m4a, .ogg, .wav", 'motopress-content-editor-lite'),
                'default' => '//wpcom.files.wordpress.com/2007/01/mattmullenweg-interview.mp3',
                'dependency' => array(
                    'parameter' => 'source',
                    'value' => 'external'
                )
            ),
            'autoplay' => array(
                'type' => 'checkbox',
                'label' => __("Autoplay", 'motopress-content-editor-lite'),
                'description' => __("Play file automatically when page is loaded", 'motopress-content-editor-lite'),
                'default' => '',
            ),
            'loop' => array(
                'type' => 'checkbox',
                'label' => __("Repeat", 'motopress-content-editor-lite'),
                'description' => __("Repeat when playback is ended", 'motopress-content-editor-lite'),
                'default' => '',
            )
        ), 20, MPCEObject::ENCLOSED);

/* GOOGLE MAPS */
        $gMapObj = new MPCEObject(MPCEShortcode::PREFIX.'gmap', __("Google Maps", 'motopress-content-editor-lite'), 'map.png', array(
            'address' => array(
                'type' => 'text',
                'label' => __("Address", 'motopress-content-editor-lite'),
                'default' => 'Sydney, New South Wales, Australia',
                'description' => __("To find a specific address or location, just enter what you're looking for and press Enter", 'motopress-content-editor-lite')
            ),
            'zoom' => array(
                'type' => 'slider',
                'label' => __("Zoom", 'motopress-content-editor-lite'),
                'default' => 13,
                'min' => 0,
                'max' => 20
            ),
	        'min_height' => array(
		        'type' => 'spinner',
		        'label' => __("Min Height", 'motopress-content-editor-lite'),
		        'min' => 0,
		        'max' => 10000,
		        'step' => 1,
		        'default' => '0',
	        )
        ), 65, null, MPCEObject::RESIZE_ALL);
        $gMapObj->addStyle(array(
            'mp_style_classes' => array(
                'selector' => '> iframe'
            ),
			'mp_custom_style' => array(
				'selector' => '> iframe'
			)
        ));

/* SPACE */
        $spaceObj = new MPCEObject(MPCEShortcode::PREFIX . 'space', __("Space", 'motopress-content-editor-lite'), 'space.png', array(
	        'min_height' => array(
		        'type' => 'spinner',
		        'label' => __("Min Height", 'motopress-content-editor-lite'),
		        'min' => 0,
		        'max' => 10000,
		        'step' => 1,
		        'default' => '0',
	        )
        ), 60, null, MPCEObject::RESIZE_ALL);
        $spaceObj->addStyle(array(
            'mp_style_classes' => array(
                'predefined' => $spacePredefinedStyles
            ),
			'mp_custom_style' => array(
				'limitation' => array('background', 'border', 'padding', 'margin-horizontal', 'color')
			)
        ));

/* EMBED */
        $embedObj = new MPCEObject(MPCEShortcode::PREFIX . 'embed', __("Embed", 'motopress-content-editor-lite'), 'code.png', array(
            'data' => array(
                'type' => 'longtext64',
                'label' => __("Paste HTML code", 'motopress-content-editor-lite'),
                'default' => 'PGk+UGFzdGUgeW91ciBjb2RlIGhlcmUuPC9pPg==',
                'description' => __("Note: Most &lt;script&gt; embeds will only appear on the published site. We recommend using &lt;iframe&gt; based embed code", 'motopress-content-editor-lite')
            ),
            'fill_space' => array(
                'type' => 'checkbox',
                'label' => __("Fill available space", 'motopress-content-editor-lite'),
                'default' => 'true',
                'description' => __("Expand object to fill available width and height", 'motopress-content-editor-lite')
            )
        ), 75);

/* QUOTE */
        $quotesObj = new MPCEObject(MPCEShortcode::PREFIX . 'quote', __("Quote", 'motopress-content-editor-lite'), 'quotes.png', array(
            'quote_content' => array(
                'type' => 'longtext',
                'label' => __("Quote", 'motopress-content-editor-lite'),
                'default' => 'Lorem ipsum dolor sit amet.'
            ),
			'cite' => array(
                'type' => 'text',
                'label' => __("Cite", 'motopress-content-editor-lite'),
                'default' => 'John Smith',
                'description' => __("Text representation of the source", 'motopress-content-editor-lite'),
            ),
            'cite_url' => array(
                'type' => 'link',
                'label' => __("Cite URL", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => __("URL for the source of the quotation", 'motopress-content-editor-lite'),
            )
            
        ), 40, MPCEObject::ENCLOSED);

/* MEMBERS CONTENT */
        $membersObj = new MPCEObject(MPCEShortcode::PREFIX . 'members_content', __("Members Content", 'motopress-content-editor-lite'), 'members.png', array(
            'message' => array(
                'type' => 'text',
                'label' => __("Message for not logged in users", 'motopress-content-editor-lite'),
                'default' => __("This content is for registered users only. Please %login%.", 'motopress-content-editor-lite'),
                'description' => __("This message will see not logged in users", 'motopress-content-editor-lite'),
            ),
            'login_text' => array(
                'type' => 'text',
                'label' => __("Login link text", 'motopress-content-editor-lite'),
                'default' => __("login", 'motopress-content-editor-lite'),
                'description' => __("Text for the login link", 'motopress-content-editor-lite'),
            ),
            'members_content' => array(
                'type' => 'longtext-tinymce',
                'label' => __("Content for logged in users", 'motopress-content-editor-lite'),
                'default' => __("Only registered users will see this content.", 'motopress-content-editor-lite'),
	            'text' => __("Open in WordPress Editor", 'motopress-content-editor-lite'),
	            'saveInContent' => 'true'
            ),
        ), 50, MPCEObject::ENCLOSED);

/* CHARTS */
        $googleChartsObj = new MPCEObject(MPCEShortcode::PREFIX . 'google_chart', __("Chart", 'motopress-content-editor-lite'), 'chart.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => 'Company Performance'
            ),
            'type' => array(
                'type' => 'select',
                'label' => __("Chart type", 'motopress-content-editor-lite'),
                'description' => __("Find out more about chart types at <a href='https://developers.google.com/chart/' target='_blank'>Google Charts</a>", 'motopress-content-editor-lite'),
                'default' => 'ColumnChart',
                'list' => array(
                    'ColumnChart' => __("Column Chart", 'motopress-content-editor-lite'),
                    'BarChart' => __("Bar Chart", 'motopress-content-editor-lite'),
                    'AreaChart' => __("Area Chart", 'motopress-content-editor-lite'),
                    'SteppedAreaChart' => __("Stepped Area Chart", 'motopress-content-editor-lite'),
                    'PieChart' => __("Pie Chart", 'motopress-content-editor-lite'),
                    'PieChart3D' => __("3D Pie Chart", 'motopress-content-editor-lite'),
                    'LineChart' => __("Line Chart", 'motopress-content-editor-lite'),
                    'Histogram' => __("Histogram", 'motopress-content-editor-lite')
                )
            ),
            'donut' => array(
                'type' => 'checkbox',
                'label' => __("Donut Hole", 'motopress-content-editor-lite'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'type',
                    'value' =>'PieChart'
                )
            ),
            'colors' => array(
                'type' => 'text',
                'label' => __("Chart colors", 'motopress-content-editor-lite'),
                'description' => __("Comma separated HEX color values. Ex: #e0440e, #e6693e", 'motopress-content-editor-lite'),
            ),
            'transparency' => array(
                'type' => 'checkbox',
                'label' => __("Transparent background", 'motopress-content-editor-lite'),
                'default' => 'false',
            ),
            'table' => array(
                'type' => 'longtext-table',
                'label' => __("Data", 'motopress-content-editor-lite'),
                'description' => __("Data in each row separated by comma", 'motopress-content-editor-lite'),
                'default' => 'Year,Sales,Expenses<br />2004,1000,400<br />2005,1170,460<br />2006,660,1120<br />2007,1030,540',
                'saveInContent' => 'true'
            ),
            'min_height' => array(
	            'type' => 'spinner',
	            'label' => __("Min Height", 'motopress-content-editor-lite'),
	            'min' => 0,
	            'max' => 10000,
	            'step' => 1,
	            'default' => '0',
            )
        ), 80, MPCEObject::ENCLOSED, MPCEObject::RESIZE_ALL);

/* TABLE */
        $tableObj = new MPCEObject(MPCEShortcode::PREFIX . 'table', __("Table", 'motopress-content-editor-lite'), 'table.png', array(
            'table' => array(
                'type' => 'longtext-table',
                'label' => __("Data", 'motopress-content-editor-lite'),
                'default' => 'Year,Sales,Expenses<br />2004,1000,400<br />2005,1170,460<br />2006,660,1120<br />2007,1030,540',
                'description' => __("Data in each row separated by comma. Find out more about <a href='http://en.wikipedia.org/wiki/Comma-separated_values' target='_blank'>CSV format</a>.", 'motopress-content-editor-lite'),
                'saveInContent' => 'true'
            )
        ), 30, MPCEObject::ENCLOSED);
        $tableObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-table',
                    'label' => __("Table", 'motopress-content-editor-lite')
                ),
                'predefined' => array(
                    'style' => array(
                        'label' => __("Style", 'motopress-content-editor-lite'),
                        'allowMultiple' => true,
                        'values' => array(
                            'silver' => array(
                                'class' => 'motopress-table-style-silver',
                                'label' => __("Light", 'motopress-content-editor-lite')
                            ),
                            'left' => array(
                                'class' => 'motopress-table-first-col-left',
                                'label' => __("First column left", 'motopress-content-editor-lite')
                            )
                        )
                    )
                ),
                'default' => array('motopress-table-style-silver', 'motopress-table-first-col-left'),
                'selector' => '> table'
            ),
			'mp_custom_style' => array(
				'selector' => '> table',
				'limitation' => array('padding')
			)
        ));

/* POSTS GRID */
        $postsGridObj = new MPCEObject(MPCEShortcode::PREFIX . 'posts_grid', __("Posts Grid", 'motopress-content-editor-lite'), 'posts-grid.png', array(
            'query_type' => array(
                'type' => 'radio-buttons',
                'label' => __("Query Type", 'motopress-content-editor-lite'),
                'description' => __("Choose query type", 'motopress-content-editor-lite'),
                'default' => 'simple',
                'list' => array(
                    'simple' => __("Simple", 'motopress-content-editor-lite'),
                    'custom' => __("Custom query", 'motopress-content-editor-lite'),
                    'ids' => __("IDs", 'motopress-content-editor-lite'),
                )
            ),
            'post_type' => array(
                'type' => 'select',
                'label' => __("Post Type to show", 'motopress-content-editor-lite'),
                'description' => __("Select post type to populate posts from", 'motopress-content-editor-lite'),
                'list' =>MPCEShortcode::getPostTypes(false),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'category' => array(
                'type' => 'text',
                'label' => __("Display posts by category slug", 'motopress-content-editor-lite'),
                'description' => __("Separate with ',' to display posts that have either of these categories or with '+' to display posts that have all of these categories.", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'post_type',
                    'value' => 'post'
                )
            ),
            'tag' => array(
                'type' => 'text',
                'label' => __("Display posts by tag slug", 'motopress-content-editor-lite'),
                'description' => __("Separate with ',' to display posts that have either of these tags or with '+' to display posts that have all of these tags.", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'post_type',
                    'value' => 'post'
                )
            ),
            'custom_tax' => array(
                'type' => 'text',
                'label' => __("Custom Taxonomy", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'custom_tax_field' => array(
                'type' => 'select',
                'label' => __("Taxonomy field", 'motopress-content-editor-lite'),
                'default' => 'slug',
                'list' => array(
                    'term_id' => __("Term ID", 'motopress-content-editor-lite'),
                    'slug' => __("Slug", 'motopress-content-editor-lite'),
                    'name' => __("Name", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'custom_tax_terms' => array(
                'type' => 'text',
                'label' => __("Taxonomy term(s)", 'motopress-content-editor-lite'),
                'description' => __("Separate with ',' to display posts that have either of these terms or with '+' to display posts that have all of these tags.", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'posts_per_page' => array(
                'type' => 'spinner',
                'label' => __("Posts count", 'motopress-content-editor-lite'),
                'default' => 4,
                'min' => 1,
                'max' => 40,
                'step' => 1,
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
//			'show_sticky_posts' => array(
//				'type' => 'checkbox',
//				'label' => 'Show sticky posts',
//				'default' => 'false',
//				'dependency' => array(
//					'parameter' => 'query_type',
//					'value' => 'simple'
//				)
//			), 
            'posts_order' => array(
                'type' => 'radio-buttons',
                'label' => __("Sort order", 'motopress-content-editor-lite'),
                'default' => 'DESC',
                'list' => array(
                    'ASC' => __("Ascending", 'motopress-content-editor-lite'),
                    'DESC' => __("Descending", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'simple'
                )
            ),
            'custom_query' => array(
                'type' => 'longtext64',
                'label' => __("Custom query", 'motopress-content-editor-lite'),
                'description' => __("Build custom query according to <a href=\"http://codex.wordpress.org/Function_Reference/query_posts\">WordPress Codex</a>. Example: post_type=portfolio&posts_per_page=5&orderby=title", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'custom'
                )
            ),
            'ids' => array(
                'type' => 'text',
                'label' => __("IDs of posts", 'motopress-content-editor-lite'),
                'description' => __("Separate with ','", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'query_type',
                    'value' => 'ids'
                )
            ),
            'columns' => array(
                'type' => 'radio-buttons',
                'label' => __("Columns count", 'motopress-content-editor-lite'),
                'default' => 2,
                'list' => array( 
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    6 => 6
                )
            ),
            'template' => array(
                'type' => 'select',
                'label' => __("Post Style", 'motopress-content-editor-lite'),
                'list' => MPCEShortcode::getPostsGridTemplatesList(),
            ),
            'posts_gap' => array(
                'type' => 'slider',
                'label' => __("Vertical gap between posts", 'motopress-content-editor-lite'),
                'default' => 30,
                'min' => 0,
                'max' => 100,
                'step' => 10,
            ),
            'show_featured_image' => array(
                'type' => 'checkbox',
                'label' => __("Show Featured Image", 'motopress-content-editor-lite'),
                'default' => 'true',
            ),
            'image_size' => array(
                'type' => 'radio-buttons',
                'label' => __("Image size", 'motopress-content-editor-lite'),
                'default' => 'large',
                'list' => array(
                    'full' => __("Full", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite'),
                    'medium' => __("Medium", 'motopress-content-editor-lite'),
                    'thumbnail' => __("Thumbnail", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'show_featured_image',
                    'value' => 'true'
                ),
            ),
            'image_custom_size' => array(
                'type' => 'text',
                'description' => __("Image size in pixels, ex. 200x100 or theme-registered image size. Note: the closest-sized image will be used if original one does not exist.", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'image_size',
                    'value' => 'custom'
                ),
            ),
            'title_tag' => array(
                'type' => 'radio-buttons',
                'label' => __("Title style", 'motopress-content-editor-lite'),
                'default' => 'h2',
                'list' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'hide' => __("Hide", 'motopress-content-editor-lite'),
                )
            ),
            'show_date_comments' => array(
                'type' => 'checkbox',
                'label' => __("Show Date and Comments", 'motopress-content-editor-lite'),
                'default' => 'true',
            ),
            'show_content' => array(
                'type' => 'radio-buttons',
                'label' => __("Post description", 'motopress-content-editor-lite'),
                'default' => 'short',
                'list' => array(
                    'short' => __("Short", 'motopress-content-editor-lite'),
                    'full' => __("Full", 'motopress-content-editor-lite'),
                    'excerpt' => __("Excerpt", 'motopress-content-editor-lite'),
                    'hide' => __("None", 'motopress-content-editor-lite'),
                )
            ),
            'short_content_length' => array(
                'type' => 'slider',
                'label' => __("Length of the Text", 'motopress-content-editor-lite'),
                'default' => 200,
                'min' => 0,
                'max' => 1000,
                'step' => 20,
                'dependency' => array(
                    'parameter' => 'show_content',
                    'value' => 'short'
                ),
            ),
            'read_more_text' => array(
                'type' => 'text',
                'label' => __("Post Read More link text", 'motopress-content-editor-lite'),
                'default' => __("Read more", 'motopress-content-editor-lite')
            ),
            'display_style' => array(
                'type' => 'radio-buttons',
                'label' => __("Display Style", 'motopress-content-editor-lite'),
                'default' => 'show_all',
                'list' => array(
                    'show_all' => __("Show All", 'motopress-content-editor-lite'),
                    'load_more' => __("Load More Button", 'motopress-content-editor-lite'),
                    'pagination' => __("Pagination", 'motopress-content-editor-lite')
                )
            ),
            'load_more_text' => array(
                'type' => 'text',
                'label' => __("Load More button text", 'motopress-content-editor-lite'),
                'default' => __("Load More", 'motopress-content-editor-lite'), // "Load More"
                'dependency' => array(
                    'parameter' => 'display_style',
                    'value' => 'load_more'
                )
            ),
            'filter' => array(
                'type' => 'radio-buttons',
                'label' => __("Filter", 'motopress-content-editor-lite'),
                'description' => __("Add taxonomy filter.", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'cats' => __("First Taxonomy", 'motopress-content-editor-lite'),
                    'tags' => __("Second Taxonomy", 'motopress-content-editor-lite'),
                    'both' => __("Both", 'motopress-content-editor-lite')
                ),
				'dependency' => array(
					'parameter' => 'query_type',
					'value' => 'simple'
				)
			),
			'filter_tax_1' => array(
				'type' => 'select',
				'label' => __("Select First Taxonomy", 'motopress-content-editor-lite'),
				'description' => '',
				'default' => 'category',
				'list' => MPCEShortcode::getTaxonomiesList('category', false),
				'dependency' => array(
					'parameter' => 'filter',
					'value' => array( 'cats', 'both' )
				)
			),
			'filter_tax_2' => array(
				'type' => 'select',
				'label' => __("Select Second Taxonomy", 'motopress-content-editor-lite'),
				'description' => '',
				'default' => 'post_tag',
				'list' => MPCEShortcode::getTaxonomiesList('post_tag', false),
				'dependency' => array(
					'parameter' => 'filter',
					'value' => array( 'tags', 'both' )
				)
			),
            'filter_btn_color' => array(
                'type' => 'color-select',
                'label' => __("Button color", 'motopress-content-editor-lite'),
                'default' => 'motopress-btn-color-silver',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'motopress-btn-color-silver' => __("Silver", 'motopress-content-editor-lite'),
                    'motopress-btn-color-red' => __("Red", 'motopress-content-editor-lite'),
                    'motopress-btn-color-pink-dreams' => __("Pink Dreams", 'motopress-content-editor-lite'),
                    'motopress-btn-color-warm' => __("Warm", 'motopress-content-editor-lite'),
                    'motopress-btn-color-hot-summer' => __("Hot Summer", 'motopress-content-editor-lite'),
                    'motopress-btn-color-olive-garden' => __("Olive Garden", 'motopress-content-editor-lite'),
                    'motopress-btn-color-green-grass' => __("Green Grass", 'motopress-content-editor-lite'),
                    'motopress-btn-color-skyline' => __("Skyline", 'motopress-content-editor-lite'),
                    'motopress-btn-color-aqua-blue' => __("Aqua Blue", 'motopress-content-editor-lite'),
                    'motopress-btn-color-violet' => __("Violet", 'motopress-content-editor-lite'),
                    'motopress-btn-color-dark-grey' => __("Dark Grey", 'motopress-content-editor-lite'),
                    'motopress-btn-color-black' => __("Black", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'filter',
                    'except' => 'none'
                )
            ),
			'filter_btn_divider' => array(
				'type' => 'text',
				'label' => __("Divider", 'motopress-content-editor-lite'),
				'default' => '/',
				'dependency' => array(
					'parameter' => 'filter_btn_color',
					'value' => 'none'
				)
			),
            'filter_cats_text' => array(
                'type' => 'text',
                'label' => __("First Filter Title", 'motopress-content-editor-lite'),
                'default' => __('Categories') . ':',
                'dependency' => array(
                    'parameter' => 'filter',
                    'value' => array('cats', 'both')
                )
            ),
            'filter_tags_text' => array(
                'type' => 'text',
                'label' => __("Second Filter Title", 'motopress-content-editor-lite'),
                'default' => __('Tags') . ':',
                'dependency' => array(
                    'parameter' => 'filter',
                    'value' => array('tags', 'both')
                )
            ),
            'filter_all_text' => array(
                'type' => 'text',
                'label' => __("\"View All\" text", 'motopress-content-editor-lite'),
                'default' => __('All'),
                'dependency' => array(
                    'parameter' => 'filter',
                    'except' => 'none'
                )
            ),
        ), 10);
        $postsGridObj->addStyle(array(
            'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-posts-grid-basic',
                    'label' => __("Posts Grid", 'motopress-content-editor-lite')
                )
            )
        ));

/* MODAL */
        $modalObj = new MPCEObject(MPCEShortcode::PREFIX . 'modal', __("Modal", 'motopress-content-editor-lite'), "modal.png", array(
            'content' => array(
				'type' => 'longtext-tinymce',
                'label' => __("Content", 'motopress-content-editor-lite'),
				'text' => __("Edit", 'motopress-content-editor-lite') . ' ' . __("Modal", 'motopress-content-editor-lite'),
                'default' => __("<h1>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h1><p>Integer ac leo ut arcu dictum viverra at eu magna. Integer ut eros varius, ornare magna non, malesuada nunc. Nulla elementum fringilla libero vitae luctus. Phasellus tincidunt nulla erat, in consectetur ante ornare tempor. Curabitur egestas purus ac gravida malesuada. Vestibulum sit amet rhoncus nisi. Quisque porta enim eget nisi luctus accumsan. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>", 'motopress-content-editor-lite'),
				'saveInContent' => 'true'
            ),
			'modal_style' => array(
				'type' => 'radio-buttons',
				'label' => __("Style", 'motopress-content-editor-lite'),
				'default' => 'dark',
				'list' => array(
					'dark' => __("Dark", 'motopress-content-editor-lite'),
					'light' => __("Light", 'motopress-content-editor-lite'),
					'custom' => __("Custom", 'motopress-content-editor-lite')
				)
			),
			'modal_shadow_color' => array(
				'type' => 'color-picker',
                'label' => __("Background color", 'motopress-content-editor-lite'),
                'default' => '#0b0b0b',
				'dependency' => array(
					'parameter' => 'modal_style',
					'value' => 'custom'
				)
			),
			'modal_content_color' => array(
				'type' => 'color-picker',
                'label' => __("Box color", 'motopress-content-editor-lite'),
                'default' => '#ffffff',
				'dependency' => array(
					'parameter' => 'modal_style',
					'value' => 'custom'
				)
			),
			'button_text' => array(
                'type' => 'text',
                'label' => __("Text on the button", 'motopress-content-editor-lite'),
                'default' => 'Open Modal Box'
            ),
            'button_full_width' => array(
                'type' => 'checkbox',
                'label' => __("Stretch", 'motopress-content-editor-lite') . ' ' . __("Button", 'motopress-content-editor-lite'),
                'default' => 'false'
            ),
            'button_align' => array(
                'type' => 'radio-buttons',
                'label' => __("Button", 'motopress-content-editor-lite') . ' ' . __("Alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'button_full_width',
                    'value' => 'false'
                )
            ),
            'button_icon' => array(
                'type' => 'icon-picker',
                'label' => __("Button", 'motopress-content-editor-lite') . ' ' . __("Icon", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => $this->getIconClassList(true)
            ),
            'button_icon_position' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon", 'motopress-content-editor-lite') . ' ' . __("Icon alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'button_icon',
                    'except' => 'none'
                )
            ),
			'show_animation' => array(
				'type' => 'select',
				'label' => __("Show animation", 'motopress-content-editor-lite'),
				'default' => '',
				'list' => array(
					'' => 'None',
					'bounce' => 'Bounce',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
					'bounceIn' => 'Bounce In',
					'bounceInDown' => 'Bounce In Down',
					'bounceInLeft' => 'Bounce In Left',
					'bounceInRight' => 'Bounce In Right',
					'bounceInUp' => 'Bounce In Up',
					'fadeIn' => 'Fade In',
					'fadeInDown' => 'Fade In Down',
					'fadeInDownBig' => 'Fade In Down Big',
					'fadeInLeft' => 'Fade In Left',
					'fadeInLeftBig' => 'Fade In Left Big',
					'fadeInRight' => 'Fade In Right',
					'fadeInRightBig' => 'Fade In Right Big',
					'fadeInUp' => 'Fade In Up',
					'fadeInUpBig' => 'Fade In Up Big',
					'flip' => 'Flip',
					'flipInX' => 'Flip In X',
					'flipInY' => 'Flip In Y',
					'lightSpeedIn' => 'Light Speed In',
					'rotateIn' => 'Rotate In',
					'rotateInDownLeft' => 'Rotate In Down Left',
					'rotateInDownRight' => 'Rotate In Down Right',
					'rotateInUpLeft' => 'Rotate In Up Left',
					'rotateInUpRight' => 'Rotate In Up Right',
					'rollIn' => 'Roll In',
					'zoomIn' => 'Zoom In',
					'zoomInDown' => 'Zoom In Down',
					'zoomInLeft' => 'Zoom In Left',
					'zoomInRight' => 'Zoom In Right',
					'zoomInUp' => 'Zoom In Up',
					'slideInDown' => 'Slide In Down',
					'slideInLeft' => 'Slide In Left',
					'slideInRight' => 'Slide In Right',
					'slideInUp' => 'Slide In Up',
				)
			),
			'hide_animation' => array(
				'type' => 'select',
				'label' => __("Hide animation", 'motopress-content-editor-lite'),
				'default' => '',
				'list' => array(
					'' => 'None',
					'auto' => 'Auto',
					'bounce' => 'Bounce',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
					'bounceOut' => 'Bounce Out',
					'bounceOutDown' => 'Bounce Out Down',
					'bounceOutLeft' => 'Bounce Out Left',
					'bounceOutRight' => 'Bounce Out Right',
					'bounceOutUp' => 'Bounce Out Up',
					'fadeOut' => 'Fade Out',
					'fadeOutDown' => 'Fade Out Down',
					'fadeOutDownBig' => 'Fade Out Down Big',
					'fadeOutLeft' => 'Fade Out Left',
					'fadeOutLeftBig' => 'Fade Out Left Big',
					'fadeOutRight' => 'Fade Out Right',
					'fadeOutRightBig' => 'Fade Out Right Big',
					'fadeOutUp' => 'Fade Out Up',
					'fadeOutUpBig' => 'Fade Out Up Big',
					'flip' => 'Flip',
					'flipOutX' => 'Flip Out X',
					'flipOutY' => 'Flip Out Y',
					'lightSpeedOut' => 'Light Speed Out',
					'rotateOut' => 'Rotate Out',
					'rotateOutDownLeft' => 'Rotate Out Down Left',
					'rotateOutDownRight' => 'Rotate Out Down Right',
					'rotateOutUpLeft' => 'Rotate Out Up Left',
					'rotateOutUpRight' => 'Rotate Out Up Right',
					'rollOut' => 'Roll Out',
					'zoomOut' => 'Zoom Out',
					'zoomOutDown' => 'Zoom Out Down',
					'zoomOutLeft' => 'Zoom Out Left',
					'zoomOutRight' => 'Zoom Out Right',
					'zoomOutUp' => 'Zoom Out Up',
					'slideOutDown' => 'Slide Out Down',
					'slideOutLeft' => 'Slide Out Left',
					'slideOutRight' => 'Slide Out Right',
					'slideOutUp' => 'Slide Out Up',
				)
			)
        ), 50, MPCEObject::ENCLOSED);
		$modalStyles = $buttonStyles;
		$modalStyles['mp_style_classes']['selector'] = '> button';
		$modalStyles['mp_custom_style']['selector'] = '> button';
		$modalObj->addStyle($modalStyles);

/* SPLASH SCREEN */
		$popupObj = new MPCEObject(MPCEShortcode::PREFIX . 'popup', __("Splash Screen", 'motopress-content-editor-lite'), "popup.png", array(
            'content' => array(
				'type' => 'longtext-tinymce',
                'label' => __("Content", 'motopress-content-editor-lite'),
				'text' => __("Edit", 'motopress-content-editor-lite') . ' ' . __("Splash Screen", 'motopress-content-editor-lite'),
                'default' => __("<h1>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h1><p>Integer ac leo ut arcu dictum viverra at eu magna. Integer ut eros varius, ornare magna non, malesuada nunc. Nulla elementum fringilla libero vitae luctus. Phasellus tincidunt nulla erat, in consectetur ante ornare tempor. Curabitur egestas purus ac gravida malesuada. Vestibulum sit amet rhoncus nisi. Quisque porta enim eget nisi luctus accumsan. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>", 'motopress-content-editor-lite'),
				'saveInContent' => 'true'
            ),
			'delay' => array(
				'type' => 'text',
				'label' => __("Delay in milliseconds", 'motopress-content-editor-lite'),
				'default' => '1000'
			),
			'display' => array(
				'type' => 'select',
				'label' => __("Display", 'motopress-content-editor-lite'),
				'list' => array(
					'' => __("Always", 'motopress-content-editor-lite'),
					'once' => __("Once", 'motopress-content-editor-lite')
				),
				'default' => 'false'				
			),
			'modal_style' => array(
				'type' => 'radio-buttons',
				'label' => __("Style", 'motopress-content-editor-lite'),
				'default' => 'dark',
				'list' => array(
					'dark' => __("Dark", 'motopress-content-editor-lite'),
					'light' => __("Light", 'motopress-content-editor-lite'),
					'custom' => __("Custom", 'motopress-content-editor-lite')
				)
			),
			'modal_shadow_color' => array(
				'type' => 'color-picker',
                'label' => __("Background color", 'motopress-content-editor-lite'),
                'default' => '#0b0b0b',
				'dependency' => array(
					'parameter' => 'modal_style',
					'value' => 'custom'
				)
			),
			'modal_content_color' => array(
				'type' => 'color-picker',
                'label' => __("Box color", 'motopress-content-editor-lite'),
                'default' => '#ffffff',
				'dependency' => array(
					'parameter' => 'modal_style',
					'value' => 'custom'
				)
			),
			'show_animation' => array(
				'type' => 'select',
				'label' => __("Show animation", 'motopress-content-editor-lite'),
				'default' => 'slideInDown',
				'list' => array(
					'' => 'None',
					'bounce' => 'Bounce',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
					'bounceIn' => 'Bounce In',
					'bounceInDown' => 'Bounce In Down',
					'bounceInLeft' => 'Bounce In Left',
					'bounceInRight' => 'Bounce In Right',
					'bounceInUp' => 'Bounce In Up',
					'fadeIn' => 'Fade In',
					'fadeInDown' => 'Fade In Down',
					'fadeInDownBig' => 'Fade In Down Big',
					'fadeInLeft' => 'Fade In Left',
					'fadeInLeftBig' => 'Fade In Left Big',
					'fadeInRight' => 'Fade In Right',
					'fadeInRightBig' => 'Fade In Right Big',
					'fadeInUp' => 'Fade In Up',
					'fadeInUpBig' => 'Fade In Up Big',
					'flip' => 'Flip',
					'flipInX' => 'Flip In X',
					'flipInY' => 'Flip In Y',
					'lightSpeedIn' => 'Light Speed In',
					'rotateIn' => 'Rotate In',
					'rotateInDownLeft' => 'Rotate In Down Left',
					'rotateInDownRight' => 'Rotate In Down Right',
					'rotateInUpLeft' => 'Rotate In Up Left',
					'rotateInUpRight' => 'Rotate In Up Right',
					'rollIn' => 'Roll In',
					'zoomIn' => 'Zoom In',
					'zoomInDown' => 'Zoom In Down',
					'zoomInLeft' => 'Zoom In Left',
					'zoomInRight' => 'Zoom In Right',
					'zoomInUp' => 'Zoom In Up',
					'slideInDown' => 'Slide In Down',
					'slideInLeft' => 'Slide In Left',
					'slideInRight' => 'Slide In Right',
					'slideInUp' => 'Slide In Up',
				)
			),
			'hide_animation' => array(
				'type' => 'select',
				'label' => __("Hide animation", 'motopress-content-editor-lite'),
				'default' => 'slideOutUp',
				'list' => array(
					'' => 'None',
					'auto' => 'Auto',
					'bounce' => 'Bounce',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
					'bounceOut' => 'Bounce Out',
					'bounceOutDown' => 'Bounce Out Down',
					'bounceOutLeft' => 'Bounce Out Left',
					'bounceOutRight' => 'Bounce Out Right',
					'bounceOutUp' => 'Bounce Out Up',
					'fadeOut' => 'Fade Out',
					'fadeOutDown' => 'Fade Out Down',
					'fadeOutDownBig' => 'Fade Out Down Big',
					'fadeOutLeft' => 'Fade Out Left',
					'fadeOutLeftBig' => 'Fade Out Left Big',
					'fadeOutRight' => 'Fade Out Right',
					'fadeOutRightBig' => 'Fade Out Right Big',
					'fadeOutUp' => 'Fade Out Up',
					'fadeOutUpBig' => 'Fade Out Up Big',
					'flip' => 'Flip',
					'flipOutX' => 'Flip Out X',
					'flipOutY' => 'Flip Out Y',
					'lightSpeedOut' => 'Light Speed Out',
					'rotateOut' => 'Rotate Out',
					'rotateOutDownLeft' => 'Rotate Out Down Left',
					'rotateOutDownRight' => 'Rotate Out Down Right',
					'rotateOutUpLeft' => 'Rotate Out Up Left',
					'rotateOutUpRight' => 'Rotate Out Up Right',
					'rollOut' => 'Roll Out',
					'zoomOut' => 'Zoom Out',
					'zoomOutDown' => 'Zoom Out Down',
					'zoomOutLeft' => 'Zoom Out Left',
					'zoomOutRight' => 'Zoom Out Right',
					'zoomOutUp' => 'Zoom Out Up',
					'slideOutDown' => 'Slide Out Down',
					'slideOutLeft' => 'Slide Out Left',
					'slideOutRight' => 'Slide Out Right',
					'slideOutUp' => 'Slide Out Up',
				)
			)
        ), 55, MPCEObject::ENCLOSED);
		$popupObj->addStyle(array(
			'mp_style_classes' => array(
				'basic' => array(
					'class' => 'motopress-popup-basic',
					'label' => __("Splash Screen", 'motopress-content-editor-lite')
				),
				'selector' => false
			),
			'mp_custom_style' => array(
				'selector' => false
			)
		));

/* SERVICE BOX */
		$serviceBoxObj = new MPCEObject(MPCEShortcode::PREFIX . 'service_box', __("Service Box", 'motopress-content-editor-lite'), 'service-box.png', array(
            'layout' => array(
                'type' => 'select',
                'label' => __("Content style", 'motopress-content-editor-lite'),
                'default' => 'centered',
                'list' => array(
                    'centered' => __("Centered", 'motopress-content-editor-lite'),
                    'heading-float' => __("Title align right", 'motopress-content-editor-lite'),
                    'text-heading-float' => __("Title & text align right", 'motopress-content-editor-lite'),
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'except' => 'big_image'
                )
            ),
            'icon_type' => array(
                'type' => 'radio-buttons',
                'label' => __("Media type", 'motopress-content-editor-lite'),
                'default' => 'font',
                'list' => array(
                    'font' => __("Font Icon", 'motopress-content-editor-lite'),
                    'image' => __("Image Icon", 'motopress-content-editor-lite'),
                    'big_image' => __("Wide Image", 'motopress-content-editor-lite')
                )
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => __("Icon", 'motopress-content-editor-lite'),
                'default' => 'fa fa-star-o',
                'list' => $this->getIconClassList(),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'font'
                ),
            ),
            'icon_size' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon size", 'motopress-content-editor-lite'),
                'default' => 'normal',
                'list' => array(
                    'mini' => __("Mini", 'motopress-content-editor-lite'),
                    'small' => __("Small", 'motopress-content-editor-lite'),
                    'normal' => __("Normal", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite'),
                    'extra-large' => __("Extra Large", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite'),
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'font'
                )
            ),
            'icon_custom_size' => array(
                'type' => 'spinner',
                'label' => __("Icon custom size", 'motopress-content-editor-lite'),
                'description' => __("Font size in px", 'motopress-content-editor-lite'),
                'min' => 1,
                'step' => 1,
                'max' => 500,
                'default' => 26,
                'dependency' => array(
                    'parameter' => 'icon_size',
                    'value' => 'custom'
                )
            ),
            'icon_color' => array(
                'type' => 'color-select',
                'label' => __("Icon color", 'motopress-content-editor-lite'),
                'default' => 'mp-text-color-default',
                'list' => array(
                    'mp-text-color-default' => __("Silver", 'motopress-content-editor-lite'),
                    'mp-text-color-red' => __("Red", 'motopress-content-editor-lite'),
                    'mp-text-color-pink-dreams' => __("Pink Dreams", 'motopress-content-editor-lite'),
                    'mp-text-color-warm' => __("Warm", 'motopress-content-editor-lite'),
                    'mp-text-color-hot-summer' => __("Hot Summer", 'motopress-content-editor-lite'),
                    'mp-text-color-olive-garden' => __("Olive Garden", 'motopress-content-editor-lite'),
                    'mp-text-color-green-grass' => __("Green Grass", 'motopress-content-editor-lite'),
                    'mp-text-color-skyline' => __("Skyline", 'motopress-content-editor-lite'),
                    'mp-text-color-aqua-blue' => __("Aqua Blue", 'motopress-content-editor-lite'),
                    'mp-text-color-violet' => __("Violet", 'motopress-content-editor-lite'),
                    'mp-text-color-dark-grey' => __("Dark Grey", 'motopress-content-editor-lite'),
                    'mp-text-color-black' => __("Black", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite'),
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'font'
                )
            ),
            'icon_custom_color' => array(
                'type' => 'color-picker',
                'label' => __("Icon custom color", 'motopress-content-editor-lite'),
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'icon_color',
                    'value' => 'custom'
                )
            ),
            'image_id' => array(
                'type' => 'image',
                'label' => __("Icon image", 'motopress-content-editor-lite'),
                'default' => '',
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => array('image', 'big_image')
                )
            ),
            'image_size' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon image size", 'motopress-content-editor-lite'),
                'default' => 'thumbnail',
                'list' => array(
                    'thumbnail' => __("Thumbnail", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite'),
                    'full' => __("Full", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'image'
                )
            ),
            'image_custom_size' => array(
                'type' => 'text',
                'label' => __("Icon image size", 'motopress-content-editor-lite'),
                'description' => __("Image size in pixels, ex. 50x50 or theme-registered image size. Note: the closest-sized image will be used if original one does not exist.", 'motopress-content-editor-lite'),
                'default' => '50x50',
                'dependency' => array(
                    'parameter' => 'image_size',
                    'value' => 'custom'
                )
            ),
            'big_image_height' => array(
                'type' => 'spinner',
                'label' => __("Image height", 'motopress-content-editor-lite'),
                'default' => 150,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'value' => 'big_image'
                )
            ),
            'icon_background_type' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon background", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'square' => __("Square", 'motopress-content-editor-lite'),
                    'rounded' => __("Rounded", 'motopress-content-editor-lite'),
                    'circle' => __("Circle", 'motopress-content-editor-lite'),
                ),
                'dependency' => array(
                    'parameter' => 'icon_type',
                    'except' => 'big_image'
                )
            ),
            'icon_background_size' => array(
                'type' => 'spinner',
                'label' => __("Icon background size", 'motopress-content-editor-lite'),
                'default' => 1.5,
                'min' => 1,
                'max' => 3,
                'step' => 0.1,
                'dependency' => array(
                    'parameter' => 'icon_background_type',
                    'except' => 'none'
                )
            ),
            'icon_background_color' => array(
                'type' => 'color-picker',
                'label' => __("Icon background color", 'motopress-content-editor-lite'),
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'icon_background_type',
                    'except' => 'none'
                )
            ),
            'icon_margin_left' => array(
                'type' => 'spinner',
                'label' => __("Icon margin Left", 'motopress-content-editor-lite'),
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => '0'
            ),
            'icon_margin_right' => array(
                'type' => 'spinner',
                'label' => __("Icon margin Right", 'motopress-content-editor-lite'),
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => '0'
            ),
            'icon_margin_top' => array(
                'type' => 'spinner',
                'label' => __("Icon margin Top", 'motopress-content-editor-lite'),
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => '0'
            ),
            'icon_margin_bottom' => array(
                'type' => 'spinner',
                'label' => __("Icon margin Bottom", 'motopress-content-editor-lite'),
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => '0'
            ),
            'icon_effect' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon effect", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'grayscale' => __("Grayscale", 'motopress-content-editor-lite'),
                    'zoom' => __("Zoom", 'motopress-content-editor-lite'),
                    'rotate' => __("Rotate", 'motopress-content-editor-lite')
                )
            ),
            'heading' => array(
                'type' => 'longtext',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'text' => __("Open in WordPress Editor", 'motopress-content-editor-lite'),
                'saveInContent' => 'false'
            ),
            'heading_tag' => array(
                'type' => 'radio-buttons',
                'label' => __("Title style", 'motopress-content-editor-lite'),
                'default' => 'h2',
                'list' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6'
                )
            ),
            'text' => array(
                'type' => 'longtext-tinymce',
                'label' => __("Content", 'motopress-content-editor-lite'),
                'default' => '<p>Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>',
                'text' => __("Open in WordPress Editor", 'motopress-content-editor-lite'),
                'saveInContent' => 'true'
            ),
            'button_show' => array(
                'type' => 'checkbox',
                'label' => __("Show button", 'motopress-content-editor-lite'),
                'default' => 'true'
            ),
            'button_text' => array(
                'type' => 'text',
                'label' => __("Button text", 'motopress-content-editor-lite'),
                'default' => 'Button',
                'dependency' => array(
                    'parameter' => 'button_show',
                    'value' => 'true'
                )
            ),
            'button_link' => array(
                'type' => 'link',
                'label' => __("Button link", 'motopress-content-editor-lite'),
                'default' => '#',
                'dependency' => array(
                    'parameter' => 'button_show',
                    'value' => 'true'
                )
            ),
            'button_color' => array(
                'type' => 'color-select',
                'label' => __("Button color", 'motopress-content-editor-lite'),
                'default' => 'motopress-btn-color-silver',
                'list' => array(
                    'motopress-btn-color-silver' => __("Silver", 'motopress-content-editor-lite'),
                    'motopress-btn-color-red' => __("Red", 'motopress-content-editor-lite'),
                    'motopress-btn-color-pink-dreams' => __("Pink Dreams", 'motopress-content-editor-lite'),
                    'motopress-btn-color-warm' => __("Warm", 'motopress-content-editor-lite'),
                    'motopress-btn-color-hot-summer' => __("Hot Summer", 'motopress-content-editor-lite'),
                    'motopress-btn-color-olive-garden' => __("Olive Garden", 'motopress-content-editor-lite'),
                    'motopress-btn-color-green-grass' => __("Green Grass", 'motopress-content-editor-lite'),
                    'motopress-btn-color-skyline' => __("Skyline", 'motopress-content-editor-lite'),
                    'motopress-btn-color-aqua-blue' => __("Aqua Blue", 'motopress-content-editor-lite'),
                    'motopress-btn-color-violet' => __("Violet", 'motopress-content-editor-lite'),
                    'motopress-btn-color-dark-grey' => __("Dark Grey", 'motopress-content-editor-lite'),
                    'motopress-btn-color-black' => __("Black", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite'),
                ),
                'dependency' => array(
                    'parameter' => 'button_show',
                    'value' => 'true'
                )
            ),
            'button_custom_bg_color' => array(
                'type' => 'color-picker',
                'label' => __("Button background color", 'motopress-content-editor-lite'),
                'default' => '#ffffff',
                'dependency' => array(
                    'parameter' => 'button_color',
                    'value' => 'custom'
                )
            ),
            'button_custom_text_color' => array(
                'type' => 'color-picker',
                'label' => __("Button text color", 'motopress-content-editor-lite'),
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'button_color',
                    'value' => 'custom'
                )
            ),
            'button_align' => array(
                'type' => 'radio-buttons',
                'label' => __("Alignment", 'motopress-content-editor-lite'),
                'default' => 'center',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'button_show',
                    'value' => 'true'
                )
            )
        ), 15, MPCEObject::ENCLOSED);

        $serviceBoxObj->addStyle(array(
           'mp_style_classes' => array(
               'basic' => array(
                   'class' => 'motopress-service-box-basic',
                   'label' => __("Service Box", 'motopress-content-editor-lite')
               )
           )
        ));

/* LIST */
        $listObj = new MPCEObject(MPCEShortcode::PREFIX . 'list', __("List", 'motopress-content-editor-lite'), 'list.png', array(
            'list_type' => array(
                'type' => 'select',
                'label' => __("Style", 'motopress-content-editor-lite'),
                'default' => 'icon',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'icon' => __("Icon", 'motopress-content-editor-lite'),
                    'circle' => __("Circle", 'motopress-content-editor-lite'),
                    'disc' => __("Disc", 'motopress-content-editor-lite'),
                    'square' => __("Square", 'motopress-content-editor-lite'),
                    'armenian' => __("Armenian", 'motopress-content-editor-lite'),
                    'georgian' => __("Georgian", 'motopress-content-editor-lite'),
                    'decimal' => '1, 2, 3, 4',
                    'decimal-leading-zero' => '01, 02, 03, 04',
                    'lower-latin' => 'a, b, c, d',
                    'lower-roman' => 'i, ii, iii, iv',
                    'lower-greek' => 'α, β, γ, δ',
                    'upper-latin' => 'A, B, C, D',
                    'upper-roman' => 'I, II, III, IV'
                )
            ),			
            'items' => array(
                'type' => 'longtext-table',
                'label' => __("List elements", 'motopress-content-editor-lite'),
                'default' => 'Lorem<br />Ipsum<br />Dolor',
                'saveInContent' => 'true'
            ),
			'use_custom_text_color' => array(
				'type' => 'checkbox',
				'label' => __("Custom text color", 'motopress-content-editor-lite'),
				'default' => 'false'
			), 
            'text_color' => array(
                'type' => 'color-picker',
                'label' => __("Text color", 'motopress-content-editor-lite'),
                'default' => '#000000',
				'dependency' => array(
                    'parameter' => 'use_custom_text_color',
                    'except' => 'false'
                )
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => __("Icon", 'motopress-content-editor-lite'),
                'default' => 'fa fa-star',
                'list' => $this->getIconClassList(),
                'dependency' => array(
                    'parameter' => 'list_type',
                    'value' => 'icon'
                )
            ),
			'use_custom_icon_color' => array(
				'type' => 'checkbox',
				'label' => __("Custom icon color", 'motopress-content-editor-lite'),
				'default' => 'false',
                'dependency' => array(
                    'parameter' => 'list_type',
                    'value' => 'icon'
                )
			),
			'icon_color' => array(
                'type' => 'color-picker',
                'label' => __("Icon color", 'motopress-content-editor-lite'),
                'default' => '#000000',
				'dependency' => array(
                    'parameter' => 'use_custom_icon_color',
                    'except' => 'false'
                )
            ),
        ), 60, MPCEObject::ENCLOSED);
		$listObj->addStyle(array(
			'mp_style_classes' => array(
				'basic' => array(
					'class' => 'motopress-list-obj-basic',
					'label' => __("List", 'motopress-content-editor-lite')
				)
           )
		));

        $buttonInnerObj = new MPCEObject(MPCEShortcode::PREFIX . 'button_inner', __("Button", 'motopress-content-editor-lite'), null, array(
			'text' => array(
                'type' => 'text',
                'label' => __("Text on the button", 'motopress-content-editor-lite'),
                'default' => __("Button", 'motopress-content-editor-lite')
            ),
            'link' => array(
                'type' => 'link',
                'label' => __("Link", 'motopress-content-editor-lite'),
                'default' => '#',
                'description' => __("ex. http://yoursite.com/ or /blog", 'motopress-content-editor-lite')
            ),
            'target' => array(
                'type' => 'checkbox',
                'label' => __("Open link in new window (tab)", 'motopress-content-editor-lite'),
                'default' => 'false'
            ),
            'color' => array(
                'type' => 'color-select',
                'label' => __("Button color", 'motopress-content-editor-lite'),
                'default' => 'motopress-btn-color-silver',
                'list' => array(
                    'custom' => __("Custom", 'motopress-content-editor-lite'),
                    'motopress-btn-color-silver' => __("Silver", 'motopress-content-editor-lite'),
                    'motopress-btn-color-red' => __("Red", 'motopress-content-editor-lite'),
                    'motopress-btn-color-pink-dreams' => __("Pink Dreams", 'motopress-content-editor-lite'),
                    'motopress-btn-color-warm' => __("Warm", 'motopress-content-editor-lite'),
                    'motopress-btn-color-hot-summer' => __("Hot Summer", 'motopress-content-editor-lite'),
                    'motopress-btn-color-olive-garden' => __("Olive Garden", 'motopress-content-editor-lite'),
                    'motopress-btn-color-green-grass' => __("Green Grass", 'motopress-content-editor-lite'),
                    'motopress-btn-color-skyline' => __("Skyline", 'motopress-content-editor-lite'),
                    'motopress-btn-color-aqua-blue' => __("Aqua Blue", 'motopress-content-editor-lite'),
                    'motopress-btn-color-violet' => __("Violet", 'motopress-content-editor-lite'),
                    'motopress-btn-color-dark-grey' => __("Dark Grey", 'motopress-content-editor-lite'),
                    'motopress-btn-color-black' => __("Black", 'motopress-content-editor-lite')
                )
            ),
            'custom_color' => array(
                'type' => 'color-picker',
                'label' => __("Custom button color", 'motopress-content-editor-lite'),
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'color',
                    'value' => 'custom'
                )
            ),
            'icon' => array(
                'type' => 'icon-picker',
                'label' => __("Icon", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => $this->getIconClassList(true),
            )
		), null, MPCEObject::SELF_CLOSED, MPCEObject::RESIZE_NONE, false);
		
		$buttonInnerObj->addStyle(array(
			'mp_style_classes' => array(
				'basic' => array(
					'class' => 'motopress-btn',
					'label' => __("Button", 'motopress-content-editor-lite')
				)
			)
		));		
				
/* BUTTON GROUP */        
        $buttonGroupObj = new MPCEObject(MPCEShortcode::PREFIX . 'button_group', __("Button Group", 'motopress-content-editor-lite'), 'button-group.png', array(
            'elements' => array(
                'type' => 'group',
                'contains' => MPCEShortcode::PREFIX . 'button_inner',
                'items' => array(
                    'label' => array(
                        'default' => __("Text on the button", 'motopress-content-editor-lite'),
                        'parameter' => 'text'
                    ),
                    'count' => 2
                ),
                'text' => sprintf(__("Add new %s item", 'motopress-content-editor-lite'), __("Button", 'motopress-content-editor-lite')),
                /*'activeParameter' => 'active',
                'rules' => array(
                    'rootSelector' => '.motopress-button-obj > .motopress-btn',
                    'activeSelector' => '',
                    'activeClass' => 'ui-state-active'
                ),
                'events' => array(
                    'onActive' => array(
                        'selector' => '> a',
                        'event' => 'click'
                    )
                )*/
            ),
            'align' => array(
                'type' => 'radio-buttons',
                'label' => __("Alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                )
            ),
			'group_layout' => array(
                'type' => 'radio-buttons',
                'label' => __("Layout", 'motopress-content-editor-lite'),
                'default' => 'horizontal',
                'list' => array(
                    'horizontal' => __("Horizontal", 'motopress-content-editor-lite'),
                    'vertical' => __("Vertical", 'motopress-content-editor-lite')
                )
            ),
            'indent' => array(
                'type' => 'radio-buttons',
                'label' => __("Indent", 'motopress-content-editor-lite'),
                'default' => '5',
				'list' => array(
                    '0' => '0',
                    '2' => '2',
                    '5' => '5',
                    '10' => '10',
                    '15' => '15',
                    '25' => '25',
                )
            ),
            'size' => array(
                'type' => 'radio-buttons',
                'label' => __("Buttons size", 'motopress-content-editor-lite'),
                'default' => 'middle',
                'list' => array(
                    'mini' => __("Mini", 'motopress-content-editor-lite'),
                    'small' => __("Small", 'motopress-content-editor-lite'),
                    'middle' => __("Middle", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite')
                )
            ),
            'icon_position' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                )
            ),
            'icon_indent' => array(
                'type' => 'select',
                'label' => __("Icon indent", 'motopress-content-editor-lite'),
                'default' => 'small',
                'list' => array(
                    'mini' => __("Mini", 'motopress-content-editor-lite') . ' ' . __("Icon indent", 'motopress-content-editor-lite'),
                    'small' => __("Small", 'motopress-content-editor-lite') . ' ' . __("Icon indent", 'motopress-content-editor-lite'),
                    'middle' => __("Middle", 'motopress-content-editor-lite') . ' ' . __("Icon indent", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite') . ' ' . __("Icon indent", 'motopress-content-editor-lite')
                )
            )
        ), 20, MPCEObject::ENCLOSED);

/* CALL TO ACTION */
        $ctaObj = new MPCEObject(MPCEShortcode::PREFIX . 'cta', __("Call To Action", 'motopress-content-editor-lite'), 'call-to-action.png', array(
            'heading' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => 'Lorem ipsum dolor'
            ),
            'subheading' => array(
                'type' => 'text',
                'label' => __("Subtitle", 'motopress-content-editor-lite'),
                'default' => 'Lorem ipsum dolor sit amet'
            ),
			'content_text' => array(
                'type' => 'longtext',
                'label' => __("Text", 'motopress-content-editor-lite'),
                'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
            ),
            'text_align' => array(
                'type' => 'radio-buttons',
                'label' => __("Text alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite'),
                    'justify' => __("Justify", 'motopress-content-editor-lite')
                )
            ),
            'shape' => array(
                'type' => 'radio-buttons',
                'label' => __("Shape", 'motopress-content-editor-lite'),
                'default' => 'rounded',
                'list' => array(
                    'square' => __("Square", 'motopress-content-editor-lite'),
                    'rounded' => __("Rounded", 'motopress-content-editor-lite'),
                    'round' => __("Round", 'motopress-content-editor-lite')
                )
            ),
            'style' => array(
                'type' => 'select',
                'label' => __("Style", 'motopress-content-editor-lite'),
                'default' => '3d',
                'list' => array(
                    'classic' => __("Classic", 'motopress-content-editor-lite'),
                    'flat' => __("Flat", 'motopress-content-editor-lite'),
                    'outline' => __("Outline", 'motopress-content-editor-lite'),
                    '3d' => __("3D", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite')
                )
            ),
            'style_bg_color' => array(
                'type' => 'color-picker',
                'label' => __("Background Color", 'motopress-content-editor-lite'),
                'default' => '#ffffff',
                'dependency' => array(
                    'parameter' => 'style',
                    'value' => 'custom'
                )
            ),
            'style_text_color' => array(
                'type' => 'color-picker',
                'label' => __("Text Color", 'motopress-content-editor-lite'),
                'default' => '#000000',
                'dependency' => array(
                    'parameter' => 'style',
                    'value' => 'custom'
                )
            ),
            'width' => array(
                'type' => 'spinner',
                'label' => __("Width (%)", 'motopress-content-editor-lite'),
                'min' => 50,
                'max' => 100,
                'step' => 1,
                'default' => 100
            ),
            'button_pos' => array(
                'type' => 'select',
                'label' => __("Button position", 'motopress-content-editor-lite'),
                'default' => 'right',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'top' => __("Top", 'motopress-content-editor-lite'),
                    'bottom' => __("Bottom", 'motopress-content-editor-lite'),
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                )
            ),
			'button_text' => array(
                'type' => 'text',
                'label' => __("Text on the button", 'motopress-content-editor-lite'),
                'default' => __("Button", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_link' => array(
                'type' => 'link',
                'label' => __("Button link", 'motopress-content-editor-lite'),
                'default' => '#',
                'description' => __("ex. http://yoursite.com/ or /blog", 'motopress-content-editor-lite'),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_target' => array(
                'type' => 'checkbox',
                'label' => __("Open link in new window (tab)", 'motopress-content-editor-lite'),
                'default' => 'false',
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_align' => array(
                'type' => 'radio-buttons',
                'label' => __("Button alignment", 'motopress-content-editor-lite'),
                'default' => 'center',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'center' => __("Center", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                ),
				'dependency' => array(
					'parameter' => 'button_pos',
                    'value' => array('top', 'bottom')
				)
            ),
            'button_shape' => array(
                'type' => 'radio-buttons',
                'label' => __("Button shape", 'motopress-content-editor-lite'),
                'default' => 'rounded',
                'list' => array(
                    'square' => __("Square", 'motopress-content-editor-lite'),
                    'rounded' => __("Rounded", 'motopress-content-editor-lite'),
                    'round' => __("Round", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_color' => array(
                'type' => 'color-select',
                'label' => __("Button color", 'motopress-content-editor-lite'),
                'default' => 'motopress-btn-color-silver',
                'list' => array(
                    'motopress-btn-color-silver' => __("Silver", 'motopress-content-editor-lite'),
                    'motopress-btn-color-red' => __("Red", 'motopress-content-editor-lite'),
                    'motopress-btn-color-pink-dreams' => __("Pink Dreams", 'motopress-content-editor-lite'),
                    'motopress-btn-color-warm' => __("Warm", 'motopress-content-editor-lite'),
                    'motopress-btn-color-hot-summer' => __("Hot Summer", 'motopress-content-editor-lite'),
                    'motopress-btn-color-olive-garden' => __("Olive Garden", 'motopress-content-editor-lite'),
                    'motopress-btn-color-green-grass' => __("Green Grass", 'motopress-content-editor-lite'),
                    'motopress-btn-color-skyline' => __("Skyline", 'motopress-content-editor-lite'),
                    'motopress-btn-color-aqua-blue' => __("Aqua Blue", 'motopress-content-editor-lite'),
                    'motopress-btn-color-violet' => __("Violet", 'motopress-content-editor-lite'),
                    'motopress-btn-color-dark-grey' => __("Dark Grey", 'motopress-content-editor-lite'),
                    'motopress-btn-color-black' => __("Black", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_size' => array(
                'type' => 'radio-buttons',
                'label' => __("Buttons size", 'motopress-content-editor-lite'),
                'default' => 'large',
                'list' => array(
                    'mini' => __("Mini", 'motopress-content-editor-lite'),
                    'small' => __("Small", 'motopress-content-editor-lite'),
                    'middle' => __("Middle", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_icon' => array(
                'type' => 'icon-picker',
                'label' => __("Button icon", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => $this->getIconClassList(true),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'button_icon_position' => array(
                'type' => 'radio-buttons',
                'label' => __("Button icon alignment", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'button_icon',
                    'except' => 'none'
                )
            ),
            'button_animation' => array(
                'type' => 'select',
                'label' => __("Button animation", 'motopress-content-editor-lite'),
                'default' => 'right-to-left',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'top-to-bottom' => __("Top to bottom", 'motopress-content-editor-lite'),
                    'bottom-to-top' => __("Bottom to top", 'motopress-content-editor-lite'),
                    'left-to-right' => __("Left to right", 'motopress-content-editor-lite'),
                    'right-to-left' => __("Right to left", 'motopress-content-editor-lite'),
                    'appear' => __("Appear", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'button_pos',
                    'except' => 'none'
                )
            ),
            'icon_pos' => array(
                'type' => 'select',
                'label' => __("Icon position", 'motopress-content-editor-lite'),
                'default' => 'left',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'top' => __("Top", 'motopress-content-editor-lite'),
                    'bottom' => __("Bottom", 'motopress-content-editor-lite'),
                    'left' => __("Left", 'motopress-content-editor-lite'),
                    'right' => __("Right", 'motopress-content-editor-lite')
                )
            ),
			'icon_type' => array(
                'type' => 'icon-picker',
                'label' => __("Icon", 'motopress-content-editor-lite'),
                'default' => 'fa fa-info-circle',
                'list' => $this->getIconClassList(),
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'icon_color' => array(
                'type' => 'color-select',
                'label' => __("Icon color", 'motopress-content-editor-lite'),
                'default' => 'custom',
                'list' => array(
                    'mp-text-color-default' => __("Silver", 'motopress-content-editor-lite'),
                    'mp-text-color-red' => __("Red", 'motopress-content-editor-lite'),
                    'mp-text-color-pink-dreams' => __("Pink Dreams", 'motopress-content-editor-lite'),
                    'mp-text-color-warm' => __("Warm", 'motopress-content-editor-lite'),
                    'mp-text-color-hot-summer' => __("Hot Summer", 'motopress-content-editor-lite'),
                    'mp-text-color-olive-garden' => __("Olive Garden", 'motopress-content-editor-lite'),
                    'mp-text-color-green-grass' => __("Green Grass", 'motopress-content-editor-lite'),
                    'mp-text-color-skyline' => __("Skyline", 'motopress-content-editor-lite'),
                    'mp-text-color-aqua-blue' => __("Aqua Blue", 'motopress-content-editor-lite'),
                    'mp-text-color-violet' => __("Violet", 'motopress-content-editor-lite'),
                    'mp-text-color-dark-grey' => __("Dark Grey", 'motopress-content-editor-lite'),
                    'mp-text-color-black' => __("Black", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'icon_custom_color' => array(
                'type' => 'color-picker',
                'label' => __("Icon custom color", 'motopress-content-editor-lite'),
                'default' => '#ffffff',
                'dependency' => array(
                    'parameter' => 'icon_color',
                    'value' => 'custom'
                )
            ),
            'icon_size' => array(
                'type' => 'radio-buttons',
                'label' => __("Icon size", 'motopress-content-editor-lite'),
                'default' => 'extra-large',
                'list' => array(
                    'mini' => __("Mini", 'motopress-content-editor-lite'),
                    'small' => __("Small", 'motopress-content-editor-lite'),
                    'normal' => __("Normal", 'motopress-content-editor-lite'),
                    'large' => __("Large", 'motopress-content-editor-lite'),
                    'extra-large' => __("Extra Large", 'motopress-content-editor-lite'),
                    'custom' => __("Custom", 'motopress-content-editor-lite'),
                ),
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'icon_custom_size' => array(
                'type' => 'spinner',
                'label' => __("Icon custom size", 'motopress-content-editor-lite'),
                'description' => __("Font size in px", 'motopress-content-editor-lite'),
                'min' => 1,
                'step' => 1,
                'max' => 500,
                'default' => 26,
                'dependency' => array(
                    'parameter' => 'icon_size',
                    'value' => 'custom'
                )
            ),
            'icon_on_border' => array(
                'type' => 'checkbox',
                'label' => __("Place icon on border", 'motopress-content-editor-lite'),
                'default' => 'false',
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'icon_animation' => array(
                'type' => 'select',
                'label' => __("Icon animation", 'motopress-content-editor-lite'),
                'default' => 'left-to-right',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'top-to-bottom' => __("Top to bottom", 'motopress-content-editor-lite'),
                    'bottom-to-top' => __("Bottom to top", 'motopress-content-editor-lite'),
                    'left-to-right' => __("Left to right", 'motopress-content-editor-lite'),
                    'right-to-left' => __("Right to left", 'motopress-content-editor-lite'),
                    'appear' => __("Appear", 'motopress-content-editor-lite')
                ),
                'dependency' => array(
                    'parameter' => 'icon_pos',
                    'except' => 'none'
                )
            ),
            'animation' => array(
                'type' => 'select',
                'label' => __("Effect of appearance", 'motopress-content-editor-lite'),
                'default' => 'none',
                'list' => array(
                    'none' => __("None", 'motopress-content-editor-lite'),
                    'top-to-bottom' => __("Top to bottom", 'motopress-content-editor-lite'),
                    'bottom-to-top' => __("Bottom to top", 'motopress-content-editor-lite'),
                    'left-to-right' => __("Left to right", 'motopress-content-editor-lite'),
                    'right-to-left' => __("Right to left", 'motopress-content-editor-lite'),
                    'appear' => __("Appear", 'motopress-content-editor-lite')
                )
            )            
        ), 45);
		$ctaObj->addStyle(array(
			'mp_style_classes' => array(
                'basic' => array(
                    'class' => 'motopress-cta-obj-basic',
                    'label' => 'Call To Action'
                ),
            )
		));

/* SLIDER PLUGIN */
        $mpSliderObj = null;
        if (is_plugin_active('motopress-slider/motopress-slider.php') || is_plugin_active('motopress-slider-lite/motopress-slider.php')) {
            global $mpsl_settings;
            if (version_compare($mpsl_settings['plugin_version'], '1.1.2', '>=')) {
	            /** @var MPSlider $mpSlider */
                global $mpSlider;
                $mpSliderObj = new MPCEObject('mpsl', apply_filters('mpsl_product_name', __("MotoPress Slider", 'motopress-content-editor-lite')), 'layer-slider.png', array(
                    'alias' => array(
                        'type' => 'select',
                        'label' => __("Select slider", 'motopress-content-editor-lite'),
                        'description' => __("Select slider from the list of sliders you created for this website.", 'motopress-content-editor-lite'),
                        'list' => array_merge(
                            array('' => __("- select -", 'motopress-content-editor-lite')),
                            $mpSlider->getSliderList('title', 'alias')
                        )
                    )
                ), 40);
            }
        }

// WORDPRESS
        // WP Widgets Area
        global $wp_registered_sidebars;
        $wpWidgetsArea_array = array();
        $wpWidgetsArea_default = '';
        if ( $wp_registered_sidebars ){
            foreach ( $wp_registered_sidebars as $sidebar ) {
                if (empty($wpWidgetsArea_default))
                        $wpWidgetsArea_default = $sidebar['id'];
                $wpWidgetsArea_array[$sidebar['id']] = $sidebar['name'];
            }
        }else {
            $wpWidgetsArea_array['no'] = __("There are no sidebars", 'motopress-content-editor-lite');
        }
        $wpWidgetsAreaObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_widgets_area', __("Widgets Area", 'motopress-content-editor-lite'), 'sidebar.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => __("Use this widget to add one of your Widget Areas.", 'motopress-content-editor-lite')
            ),
            'sidebar' => array(
                'type' => 'select',
                'label' => __("Select Area", 'motopress-content-editor-lite'),
                'default' => $wpWidgetsArea_default,
                'description' => '',
                'list' => $wpWidgetsArea_array
            )
        ), 5);

        // archives
        $wpArchiveObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_archives', __("Archives", 'motopress-content-editor-lite'), 'archives.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => __("Archives", 'motopress-content-editor-lite'),
                'description' => __("A monthly archive of your site posts", 'motopress-content-editor-lite')
            ),
            'dropdown' => array(
                'type' => 'checkbox',
                'label' => __("Display as dropdown", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => ''
            ),
            'count' => array(
                'type' => 'checkbox',
                'label' => __("Show post counts", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => ''
            )
        ), 45);

        // calendar
        $wpCalendarObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_calendar', __("Calendar", 'motopress-content-editor-lite'), 'calendar.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => __("Calendar", 'motopress-content-editor-lite'),
                'description' => __("A calendar of your site posts", 'motopress-content-editor-lite')
            )
        ), 30);

        // wp_categories
        $wpCategoriesObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_categories', __("Categories", 'motopress-content-editor-lite'), 'categories.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => __("Categories", 'motopress-content-editor-lite'),
                'description' => __("A list or dropdown of categories", 'motopress-content-editor-lite')
            ),
            'dropdown' => array(
                'type' => 'checkbox',
                'label' => __("Display as dropdown", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => ''
            ),
            'count' => array(
                'type' => 'checkbox',
                'label' => __("Show post counts", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => ''
            ),
            'hierarchy' => array(
                'type' => 'checkbox',
                'label' => __("Show hierarchy", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => ''
            )
        ), 40);

        // wp_navmenu
        $wpCustomMenu_menus = get_terms('nav_menu');
        $wpCustomMenu_array = array();
        $wpCustomMenu_default = '';
        if ($wpCustomMenu_menus){
            foreach($wpCustomMenu_menus as $menu){
                if (empty($wpCustomMenu_default))
                    $wpCustomMenu_default = $menu->slug;
                $wpCustomMenu_array[$menu->slug] = $menu->name;
            }
        }else{
            $wpCustomMenu_array['no'] = __("There are no menus", 'motopress-content-editor-lite');
        }
        $wpCustomMenuObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_navmenu', __("Custom Menu", 'motopress-content-editor-lite'), 'custom-menu.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => __("Custom Menu", 'motopress-content-editor-lite'),
                'description' => __("Use this widget to add one of your custom menus as a widget.", 'motopress-content-editor-lite')
            ),
            'nav_menu' => array(
                'type' => 'select',
                'label' => __("Select Menu", 'motopress-content-editor-lite'),
                'default' => $wpCustomMenu_default,
                'description' => '',
                'list' => $wpCustomMenu_array
            )
        ), 10);

        // wp_meta
        $wpMetaObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_meta', __("Meta", 'motopress-content-editor-lite'), 'meta.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => __("Meta", 'motopress-content-editor-lite'),
                'description' => __("Log in/out, admin, feed and WordPress links", 'motopress-content-editor-lite')
            )
        ), 55);

        // wp_pages
        $wpPagesObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_pages', __("Pages", 'motopress-content-editor-lite'), 'pages.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => __("Pages", 'motopress-content-editor-lite'),
                'description' => __("Your site WordPress Pages", 'motopress-content-editor-lite')
            ),
            'sortby' => array(
                'type' => 'select',
                'label' => __("Sort by", 'motopress-content-editor-lite'),
                'default' => 'menu_order',
                'description' => '',
                'list' => array(
                    'post_title' => __("Page title", 'motopress-content-editor-lite'),
                    'menu_order' => __("Page order", 'motopress-content-editor-lite'),
                    'ID' => __("Page ID", 'motopress-content-editor-lite')
                ),
            ),
            'exclude' => array(
                'type' => 'text',
                'label' => __("Exclude", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => __("Page IDs, separated by commas.", 'motopress-content-editor-lite')
            )
        ), 15);

        // wp_posts
        $wpPostsObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_posts', __("Recent Posts", 'motopress-content-editor-lite'), 'recent-posts.png', array(
            'title' => array(
                    'type' => 'text',
                    'label' => __("Title", 'motopress-content-editor-lite'),
                    'default' => __("Recent Posts", 'motopress-content-editor-lite'),
                    'description' => __("The most recent posts on your site", 'motopress-content-editor-lite')
            ),
            'number' => array(
                    'type' => 'text',
                    'label' => __("Number of Posts to show", 'motopress-content-editor-lite'),
                    'default' => '5',
                    'description' => ''
            ),
            'show_date' => array(
                    'type' => 'checkbox',
                    'label' => __("Display post date?", 'motopress-content-editor-lite'),
                    'default' => '',
                    'description' => ''
            )
        ), 20);

        // wp_comments
        $wpRecentCommentsObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_comments', __("Recent Comments", 'motopress-content-editor-lite'), 'recent-comments.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => __("Recent Comments", 'motopress-content-editor-lite'),
                'description' => __("The most recent comments", 'motopress-content-editor-lite')
            ),
            'number' => array(
                'type' => 'text',
                'label' => __("Number of Comments to show", 'motopress-content-editor-lite'),
                'default' => '5',
                'description' => ''
            )
        ), 25);

        // wp_rss
        $wpRSSObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_rss', __("RSS", 'motopress-content-editor-lite'), 'rss.png', array(
            'url' => array(
                'type' => 'text',
                'label' => __("RSS feed URL", 'motopress-content-editor-lite'),
                'default' => 'https://motopress.com/feed/',
                'description' => __("Enter the RSS feed URL here", 'motopress-content-editor-lite')
            ),
            'title' => array(
                'type' => 'text',
                'label' => __("Feed title", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => __("Give the feed a title (optional)", 'motopress-content-editor-lite')
            ),
            'items' => array(
                'type' => 'select',
                'label' => __("Items quantity", 'motopress-content-editor-lite'),
                'default' => 9,
                'description' => __("How many items would you like to display?", 'motopress-content-editor-lite'),
                'list' => range(1, 20),
            ),
            'show_summary' => array(
                'type' => 'checkbox',
                'label' => __("Display item content?", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => ''
            ),
            'show_author' => array(
                'type' => 'checkbox',
                'label' => __("Display item author if available?", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => ''
            ),
            'show_date' => array(
                'type' => 'checkbox',
                'label' => __("Display item date?", 'motopress-content-editor-lite'),
                'default' => '',
                'description' => ''
            )
        ), 50);

        // search
        $wpSearchObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_search', __("Search", 'motopress-content-editor-lite'), 'search.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => __("Search", 'motopress-content-editor-lite'),
                'description' => __("A search form for your site", 'motopress-content-editor-lite')
            )
        ), 35);

        // tag cloud
        $wpTagCloudObj = new MPCEObject(MPCEShortcode::PREFIX . 'wp_tagcloud', __("Tag Cloud", 'motopress-content-editor-lite'), 'tag-cloud.png', array(
            'title' => array(
                'type' => 'text',
                'label' => __("Title", 'motopress-content-editor-lite'),
                'default' => __("Tags", 'motopress-content-editor-lite'),
                'description' => __("Your most used tags in cloud format", 'motopress-content-editor-lite')
            ),
            'taxonomy' => array(
                'type' => 'select',
                'label' => __("Taxonomy", 'motopress-content-editor-lite'),
                'default' => 10,
                'description' => '',
                'list' => array(
                    'post_tag' => __("Tags", 'motopress-content-editor-lite'),
                    'category' => __("Categories", 'motopress-content-editor-lite'),
                )
            )
        ), 60);
        /* wp widgets END */

        /* Groups */
        $gridGroup = new MPCEGroup();
        $gridGroup->setId(MPCEShortcode::PREFIX . 'grid');
        $gridGroup->setName(__("Grid", 'motopress-content-editor-lite'));
        $gridGroup->setShow(false);
        $gridGroup->addObject(array($rowObj, $rowInnerObj, $spanObj, $spanInnerObj));

        $textGroup = new MPCEGroup();
        $textGroup->setId(MPCEShortcode::PREFIX . 'text');
        $textGroup->setName(__("Text", 'motopress-content-editor-lite'));
        $textGroup->setIcon('text.png');
        $textGroup->setPosition(0);
        $textGroup->addObject(array($textObj /*20*/, $headingObj /*10*/, $codeObj /*30*/, $quotesObj /*40*/, $membersObj /*50*/, $listObj /*60*/, $iconObj /*70*/));

        $imageGroup = new MPCEGroup();
        $imageGroup->setId(MPCEShortcode::PREFIX . 'image');
        $imageGroup->setName(__("Image", 'motopress-content-editor-lite'));
        $imageGroup->setIcon('image.png');
        $imageGroup->setPosition(10);
        $imageGroup->addObject(array($imageObj, $imageSlider, $gridGalleryObj, $mpSliderObj));

        $buttonGroup = new MPCEGroup();
        $buttonGroup->setId(MPCEShortcode::PREFIX . 'button');
        $buttonGroup->setName(__("Button", 'motopress-content-editor-lite'));
        $buttonGroup->setIcon('button.png');
        $buttonGroup->setPosition(20);
        $buttonGroup->addObject(array($buttonObj, $downloadButtonObj, $buttonInnerObj, $buttonGroupObj, $socialsObj, $socialProfileObj));

        $mediaGroup = new MPCEGroup();
        $mediaGroup->setId(MPCEShortcode::PREFIX . 'media');
        $mediaGroup->setName(__("Media", 'motopress-content-editor-lite'));
        $mediaGroup->setIcon('media.png');
        $mediaGroup->setPosition(30);
        $mediaGroup->addObject(array($videoObj, $wpAudioObj));

        $otherGroup = new MPCEGroup();
        $otherGroup->setId(MPCEShortcode::PREFIX . 'other');
        $otherGroup->setName(__("Other", 'motopress-content-editor-lite'));
        $otherGroup->setIcon('other.png');
        $otherGroup->setPosition(40);
        $otherGroup->addObject(array(
            $postsGridObj,          /* 10 */
            $serviceBoxObj,         /* 15 */
            $tabsObj,               /* 20 */
            $accordionObj,          /* 25 */
            $tableObj,              /* 30 */
            $postsSliderObj,        /* 35 */
            $ctaObj,                /* 45 */
            $modalObj,              /* 50 */
            $popupObj,              /* 55 */
            $spaceObj,              /* 60 */
            $gMapObj,               /* 65 */
            $countdownTimerObj,     /* 70 */
            $embedObj,              /* 75 */
            $googleChartsObj,       /* 80 */
            $tabObj,
            $accordionItemObj,
        ));

        $wordpressGroup = new MPCEGroup();
        $wordpressGroup->setId(MPCEShortcode::PREFIX . 'wordpress');
        $wordpressGroup->setName(__("WordPress", 'motopress-content-editor-lite'));
        $wordpressGroup->setIcon('wordpress.png');
        $wordpressGroup->setPosition(50);
        $wordpressGroup->addObject(array($wpArchiveObj, $wpCalendarObj, $wpCategoriesObj, $wpCustomMenuObj, $wpMetaObj, $wpPagesObj, $wpPostsObj, $wpRecentCommentsObj, $wpRSSObj, $wpSearchObj, $wpTagCloudObj, $wpWidgetsAreaObj));

        self::$defaultGroup = $otherGroup->getId();

        $this->addGroup(array($gridGroup, $textGroup, $imageGroup, $buttonGroup, $mediaGroup, $otherGroup, $wordpressGroup));

        $this->updateDeprecatedParams();

        do_action_ref_array('mp_library', array(&$this));
    }

    /**
     * @return MPCEGroup[]
     */
    public function getLibrary() {
        return $this->library;
    }

    /**
     * @param string $id
     * @return MPCEGroup|boolean
     */
    public function &getGroup($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->library)) {
                        return $this->library[$id];
                    }
                }
            }
        }
        $group = false;
        return $group;
    }

    /**
     * @param MPCEGroup|MPCEGroup[] $group
     */
    public function addGroup($group) {
        if ($group instanceof MPCEGroup) {
            if ($group->isValid()) {
                if (!array_key_exists($group->getId(), $this->library)) {
                    if (count($group->getObjects()) > 0) {
                        $this->library[$group->getId()] = $group;
                    }
                }
            } else {
                if (!self::$isAjaxRequest) {
                    $group->showErrors();
                }
            }
        } elseif (is_array($group)) {
            if (!empty($group)) {
                foreach ($group as $g) {
                    if ($g instanceof MPCEGroup) {
                        if ($g->isValid()) {
                            if (!array_key_exists($g->getId(), $this->library)) {
                                if (count($g->getObjects()) > 0) {
                                    $this->library[$g->getId()] = $g;
                                }
                            }
                        } else {
                            if (!self::$isAjaxRequest) {
                                $g->showErrors();
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $id
     * @return boolean
     */
    public function removeGroup($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(MPCEBaseElement::ID_REGEXP, $id)) {
                    if (array_key_exists($id, $this->library)) {
                        unset($this->library[$id]);
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param string $id
     * @return MPCEObject|boolean
     */
    public function &getObject($id) {
        foreach ($this->library as $group) {
            $object = &$group->getObject($id);
            if ($object) return $object;
        }
        $object = false;
        return $object;
    }

    /**
     * @param MPCEObject|MPCEObject[] $object
     * @param string $group [optional]
     */
    public function addObject($object, $group = 'mp_other') {
        $groupObj = &$this->getGroup($group);
        if (!$groupObj) { //for support versions less than 1.5 where group id without MPCEShortcode::PREFIX
            $groupObj = &$this->getGroup(MPCEShortcode::PREFIX . $group);
        }
        if (!$groupObj) {
            $groupObj = &$this->getGroup(self::$defaultGroup);
        }
        if ($groupObj) {
            $groupObj->addObject($object);
        }
    }

    /**
     * @param string $id
     */
    public function removeObject($id) {
        foreach ($this->library as $group) {
            if ($group->removeObject($id)) break;
        }
    }

    /**
     * @return array
     * @deprecated 3.0.0
     */
    public function getTemplates() {
        return array();
    }

    /**
     * @param string $id
     * @return boolean
     *
     * @deprecated
     */
    public function &getTemplate($id) {
    	return false;
    }

    /**
     * @param $template
     * @deprecated 3.0.0
     */
    public function addTemplate($template) {}

    /**
     * @param string $id
     * @return boolean
     * @deprecated 3.0.0
     */
    public function removeTemplate($id) {
        return false;
    }

    /**
     * @return array
     */
    public function getData() {
        $library = array(
            'groups' => array(),
            'globalPredefinedClasses' => array(),
            'tinyMCEStyleFormats' => array(),
            'templates' => array(),
            'grid' => array()
        );
        foreach ($this->library as $group) {
            if (count($group->getObjects()) > 0) {
                uasort($group->objects, array(__CLASS__, 'positionCmp'));
                $library['groups'][$group->getId()] = $group;
            }
        }
        uasort($library['groups'], array(__CLASS__, 'positionCmp'));
        $library['globalPredefinedClasses'] = $this->globalPredefinedClasses;
        $library['tinyMCEStyleFormats'] = $this->tinyMCEStyleFormats;
        $library['templates'] = $this->templates;
        $library['grid'] = $this->gridObjects;
        return $library;
    }

    /**
     * @return array
     */
    public function getObjectsList() {
        $list = array();
        foreach ($this->library as $group){
            foreach ($group->getObjects() as $object) {
                $parameters = $object->getParameters();
                if (!empty($parameters)) {
                    foreach ($parameters as $key => $value) {
                        unset($parameters[$key]);
                        $parameters[$key] = array();
                    }
                }

                $list[$object->getId()] = array(
                    'parameters' => $parameters,
                    'group' => $group->getId()
                );
            }
        }
        return $list;
    }

    /**
     * @return array
     */
    public function getObjectsNames() {
        $names = array();
        foreach ($this->library as $group){
            foreach ($group->getObjects() as $object){
                $names[] = $object->getId();
            }
        }
        return $names;
    }

    /**
     * @static
     * @param MPCEObject $a
     * @param MPCEObject $b
     * @return int
     */
    /*
    public static function nameCmp(MPCEObject $a, MPCEObject $b) {
        return strcmp($a->getName(), $b->getName());
    }
    */

    /**
     * @param MPCEElement $a
     * @param MPCEElement $b
     * @return int
     */
    public function positionCmp(MPCEElement $a, MPCEElement $b) {
        $aPosition = $a->getPosition();
        $bPosition = $b->getPosition();
        if ($aPosition == $bPosition) {
            return 0;
        }
        return ($aPosition < $bPosition) ? -1 : 1;
    }

    /**
     * @return boolean
     */
    private function isAjaxRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ? true : false;
    }

    private function extendPredefinedWithGoogleFonts(&$predefined){
        $fontClasses = get_option('motopress_google_font_classes', array());
        if (!empty($fontClasses)) {
            $items = array();
            foreach ($fontClasses as $fontClassName => $fontClass) {
                $items[$fontClass['fullname']] = array(
                    'class' => $fontClass['fullname'],
                    'label' => $fontClassName,
                    'external' => mpceSettings()['google_font_classes_dir_url'] . $fontClass['file']
                );
                if (!empty($fontClass['variants'])){
                    foreach($fontClass['variants'] as $variant){
                        $items[$fontClass['fullname'] . '-' . $variant] = array(
                            'class' => $fontClass['fullname'] . '-' . $variant,
                            'label' => $fontClassName . ' ' . $variant,
                            'external' => mpceSettings()['google_font_classes_dir_url'] . $fontClass['file']
                        );
                    }
                }
            }
            $googleFontClasses = array(
                'label' => __("Google Fonts", 'motopress-content-editor-lite'),
                'values' => $items
            );
            $predefined['google-font-classes'] = $googleFontClasses;
        }
    }

    public function getGridObjects(){
        return $this->gridObjects;
    }

    public function getGroupObjects(){
        $groupObjects = array();
        foreach($this->library as $group) {
            if (isset($group->objects)) {
                foreach ($group->objects as $objectName=>$object){
                    if (isset($object->parameters)) {
                        foreach($object->parameters as $parameter){
                            if ($parameter['type'] === 'group') {
                                $groupObjects[] = $objectName;
                            }
                        }
                    }
                }
            }
        }
        return $groupObjects;
    }

    public function setGrid($grid){

        if (is_array($grid)
            && isset($grid['row'])
            && isset($grid['span'])
        ){
            if (!isset($grid['row']['edgeclass'])) {
                $grid['row']['edgeclass'] = $grid['row']['class'];
            }
            // Backward compatibility
            if (!isset($grid['span']['custom_class_attr'])) {
                $grid['span']['custom_class_attr'] = 'mp_style_classes';
            }
            $grid['span']['minclass'] = $grid['span']['class'] . 1;
            $grid['span']['fullclass'] = $grid['span']['class'] . $grid['row']['col'];

            $this->gridObjects = $grid;
        }
    }
    public function setRow($rowArgs){
        $this->gridObjects['row'] = $rowArgs;
    }

    public function setSpan($spanArgs){
        $this->gridObjects['span'] =$spanArgs;
    }

    private function updateDeprecatedParams() {
        foreach ($this->library as $grp) {
            foreach ($grp->objects as $objName => $obj) {
                if (isset($obj->styles) && array_key_exists('mp_style_classes', $obj->styles)) {
                    if (!array_key_exists($objName, $this->deprecatedParameters)) {
                        $this->deprecatedParameters[$objName] = array();
                    }
                    if (!array_key_exists('custom_class', $this->deprecatedParameters[$objName])) {
                        $this->deprecatedParameters[$objName]['custom_class'] = array('prefix' => '');
                    }
                }
            }
        }
    }

	/**
	 * @param bool $useEmptyIcon
	 * @return array
	 */
    public function getIconClassList($useEmptyIcon = false) {

    	$IconList = include(mpceSettings()['plugin_dir_path'] . 'includes/ce/icon_list.php');

        if ($useEmptyIcon) {
	        $empty = array(
		        'none' => array(
			        'class' => 'fa',
			        'label' => 'None'
		        )
	        );
	        $IconList = array_merge($empty, $IconList);
        }

        return $IconList;
    }
}