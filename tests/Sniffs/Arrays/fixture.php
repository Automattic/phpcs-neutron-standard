<?php
class MyClass {
	public function doSomething() {
		// Next line should report no long arrays allowed
		$rest = array('x' => 'y');
		$rest;
	}
}
