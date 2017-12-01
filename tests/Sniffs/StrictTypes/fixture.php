<?php
// Previous line should report missing strict types

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
