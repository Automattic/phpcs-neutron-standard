<?php
declare(strict_types=1);

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
