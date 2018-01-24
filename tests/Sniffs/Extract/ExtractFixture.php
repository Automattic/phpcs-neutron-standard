<?php

class MyClass {
	public function doSomething() {
		$all = ['foo' => 'bar' ];
		// Next line should report no extract allowed
		extract($all);
	}

	public function extract() {
		$extract = 'foo';
		$extract;
	}
}
