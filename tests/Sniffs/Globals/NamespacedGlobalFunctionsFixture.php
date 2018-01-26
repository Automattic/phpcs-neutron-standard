<?php
namespace Foo\Bar;

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
