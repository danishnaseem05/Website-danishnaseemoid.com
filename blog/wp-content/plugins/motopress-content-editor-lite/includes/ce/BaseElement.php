<?php
/**
 * Class MPCEBaseElement
 *
 * @abstract
 */
abstract class MPCEBaseElement {
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $icon;

    private static $mimeTypes = array('image/jpeg', 'image/png');
    private static $extensions = array('jpg', 'jpeg', 'png');

    const ID_REGEXP = '/^[a-z_\-.0-9]{1,50}$/is';
    const NAME_REGEXP = '/^[^\\\\"]{1,100}$/is';

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id) {
        if (is_string($id)) {
            $id = trim($id);
            if (!empty($id)) {
                $id = filter_var($id, FILTER_SANITIZE_STRING);
                if (preg_match(self::ID_REGEXP, $id)) {
                    $this->id = $id;
                } else {
                    $this->addError('id', __("Value should not be longer than 50 characters and consist of letters from a-z (LowerCase, UpperCase), characters: _ - .", 'motopress-content-editor-lite'));
                }
            } else {
                $this->addError('id', __("Value should not be empty", 'motopress-content-editor-lite'));
            }
        } else {
            $this->addError('id', sprintf(__("Invalid argument type - %s", 'motopress-content-editor-lite'), gettype($id)));
        }
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        if (is_string($name)) {
            $name = trim($name);
            if (!empty($name)) {
                $name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                if (preg_match(self::NAME_REGEXP, $name)) {
                    $this->name = $name;
                } else {
                    $this->addError('name', __("Value should not be longer than 100 characters and not contain characters: \\ \"", 'motopress-content-editor-lite'));
                }
            } else {
                $this->addError('name', __("Value should not be empty", 'motopress-content-editor-lite'));
            }
        } else {
            $this->addError('name', sprintf(__("Invalid argument type - %s", 'motopress-content-editor-lite'), gettype($name)));
        }
    }

    /**
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @abstract
     * @param string $icon
     */
    abstract public function setIcon($icon);

    /**
     * @final
     * @global MPCERequirements $motopressCERequirements
     * @param string $icon
     * @param string $iconDir
     */
    final protected function icon($icon, $iconDir) {
        global $motopressCERequirements;

        if (is_string($icon)) {
            $icon = trim($icon);
            if (!empty($icon)) {
                $icon = filter_var($icon, FILTER_SANITIZE_STRING);

                if (dirname($icon) === '.') {
                    $iconPath = mpceSettings()['plugin_dir_path'] . 'images/ce/' . $iconDir . '/' . $icon;
                    $iconUrl = mpceSettings()['plugin_dir_url'] . 'images/ce/' . $iconDir . '/' . $icon;
                } else {
                    $iconPath = WP_CONTENT_DIR . '/' . $icon;
                    $iconUrl = WP_CONTENT_URL . '/' . $icon;
                }
                $iconUrl .=  '?ver=' . mpceSettings()['plugin_version'];

                if (file_exists($iconPath)) {
                    $mimeType = null;
                    if ($motopressCERequirements->getGd()) {
                        $info = getimagesize($iconPath);
                        if ($info) {
                            $mimeType = $info['mime'];
                        }
                    }
                    if (is_null($mimeType) && $motopressCERequirements->getFileinfo() && version_compare(PHP_VERSION, '5.3.0', '>=')) {
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $finfoMimeType = $finfo->file($iconPath);
                        if ($finfoMimeType) {
                            $mimeType = $finfoMimeType;
                        }
                    }
                    if (is_null($mimeType) && $motopressCERequirements->getExif()) {
                        $exifImageType = exif_imagetype($iconPath);
                        if ($exifImageType) {
                            $mimeType = image_type_to_mime_type($exifImageType);
                        }
                    }

                    $extension = null;
                    if (is_null($mimeType) && $motopressCERequirements->getImagick()) {
                        try {
                            $imagick = new Imagick($iconPath);
                            $extension = strtolower($imagick->getImageFormat());
                        } catch(ImagickException $e) {
                            if (mpceSettings()['debug']) var_dump($e);
                        }
                    }
                    if (is_null($extension) && $motopressCERequirements->getGmagick()) {
                        try {
                            $gmagick = new Gmagick($iconPath);
                            $extension = strtolower($gmagick->getimageformat());
                        } catch(GmagickException $e) {
                            if (mpceSettings()['debug']) var_dump($e);
                        }
                    }
                    if (is_null($extension)) {
                        $extension = pathinfo($iconPath, PATHINFO_EXTENSION);
                    }

                    if (!is_null($mimeType) || !is_null($extension)) {
                        if (in_array($mimeType, self::$mimeTypes) || in_array($extension, self::$extensions)) {
                            $this->icon = $iconUrl;
                        } else {
                            $this->addError('icon', sprintf(__("Invalid MIME type %s. Allowed MIME types: image/jpeg, image/png", 'motopress-content-editor-lite'), $mimeType));
                        }
                    } else {
                        $this->addError('icon', __("Unknown MIME type", 'motopress-content-editor-lite'));
                    }
                } else {
                    $this->addError('icon', sprintf(__("File %s does not exist", 'motopress-content-editor-lite'), $iconPath));
                }
            } else {
                $this->addError('icon', __("Value should not be empty", 'motopress-content-editor-lite'));
            }
        } else {
            $this->addError('icon', sprintf(__("Invalid argument type - %s", 'motopress-content-editor-lite'), gettype($icon)));
        }
    }

    /**
     * @abstract
     * @return boolean
     */
    abstract public function isValid();

    /**
     * @return string[]
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @final
     * @param string $key
     * @param string $message
     * @return boolean
     */
    final protected function addError($key, $message) {
        if (array_key_exists($key, $this->errors)) {
            $this->errors[$key][] = $message;
        } else {
            return false;
        }
    }

    /**
     * @final
     */
    final public function showErrors() {
        echo '<div class="alert">';
        echo '<span class="object">' . $this . '</span>';
        echo '<ul class="property">';
        foreach ($this->errors as $property => $errors) {
            if (!empty($errors)) {
                echo '<li>' . $property . ':';
                echo '<ul class="errors">';
                foreach ($errors as $error) {
                    echo '<li>' . $error . '</li>';
                }
                echo '</ul>';
                echo '</li>';
            }
        }
        echo '</ul>';
        echo '</div>';
    }

    /**
     * @abstract
     * @return string
     */
    abstract public function __toString();
}