<?php
/**
 * Parent class for MPCEGroup and MPCEObject
 *
 * @abstract
 */
abstract class MPCEElement extends MPCEBaseElement {
    /**
     * @var int
     */
    public $position = 0;
    /**
     * @var bool
     */
    public $show = true;

    /**
     * @return int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position) {
        if (is_int($position)) {
            $min = 0;
            $max = 200;
            $position = filter_var($position, FILTER_VALIDATE_INT, array('options' => array('min_range' => $min, 'max_range' => $max)));
            if ($position !== false) {
                $this->position = $position;
            } else {
                $this->addError('position', sprintf(__("Value should be between %d and %d", 'motopress-content-editor-lite'), $min, $max));
            }
        } else {
            $this->addError('position', sprintf(__("Invalid argument type - %s", 'motopress-content-editor-lite'), gettype($position)));
        }
    }

    /**
     * @return boolean
     */
    public function getShow() {
        return $this->show;
    }

    /**
     * @param boolean $show
     */
    public function setShow($show) {
        if (is_bool($show)) {
            $this->show = $show;
        } else {
            $this->addError('show', sprintf(__("Invalid argument type - %s", 'motopress-content-editor-lite'), gettype($show)));
        }
    }
}
