<?php

if (!class_exists('MPCEJsonLang')) {

class MPCEJsonLang {

	private static $_instance = null;

	private function __construct() {}

	private function __clone() {}

	public static function getInstance() {

		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __get($name) {
		return $name;
	}

	public function __set($name, $value) {}

}

function motopressCEGetLanguageDict() {
	return MPCEJsonLang::getInstance();
}

}