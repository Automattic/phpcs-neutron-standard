<?php
class MyClass {
	public function doSomething() {
		// Next line should report no long arrays allowed
		$rest = ['x' => 'y'];
		$rest;
	}
}
