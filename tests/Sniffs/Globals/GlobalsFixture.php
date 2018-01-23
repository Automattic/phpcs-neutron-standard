<?php
// Next line should report no global functions allowed
function test() {
	define("FOO", "bar");
}

class MyClass {
	public function notTooLong() {
		$foo = 'bar';
		$foo;
		$foo; // Hello
	}
}
