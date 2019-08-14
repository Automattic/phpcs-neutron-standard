<?php
class MyClass {
	public function doSomething() {
		$all = ['foo' => 'bar' ];
		$rest = array('x' => 'y');
		extract($all);
		$define = 'foo';
		$define;
		$rest;
	}

	public function getActual() {
		return 'hello';
	}

	// Next line should report no seralize allowed
	public function __serialize() {
	}

	// Next line should report no get allowed
	public function __get($var) {
		$var;
	}

	// Next line should report no set allowed
	public function __set($var, $val) {
		$var;
		$val;
	}

	// Next line should report risky method
	public function __invoke() {
	}

	// Next line should report risky method
	public function __call($name, $args) {
		$name;
		$args;
	}

	// Next line should report risky method
	public static function __callStatic($name, $args) {
		$name;
		$args;
	}
}
