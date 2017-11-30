<?php
// Previous line should report missing strict types

// Next line should report no define allowed
define("FOO", "bar");

// Next line should report no global functions allowed
function test() {
	// Next line should report no define allowed
	define("FOO", "bar");
}

class MyClass {
	public function notTooLong() {
		/**
		 * Lorem ipsum
		 * Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ullamcorper
		 * turpis vel lacus tincidunt accumsan. Pellentesque varius tristique tortor, non
		 * tincidunt elit porta ac. Praesent eget interdum turpis. Donec sodales ultrices
		 * metus at cursus. Phasellus vehicula augue eu elit semper mollis vitae aliquam
		 * lacus. Proin a egestas dui. Nam aliquet ultricies ipsum, eget bibendum lacus.
		 * Donec ut neque ultricies, mattis urna non, ullamcorper risus. Mauris efficitur
		 * tortor justo, a commodo justo tempus non. Nullam commodo vehicula magna ac
		 * malesuada. Nulla suscipit vulputate feugiat. Donec quis dignissim mauris.
		 * Integer volutpat mi ut urna molestie, sit amet placerat felis vestibulum.
		 * Quisque vulputate, metus et viverra condimentum, lacus purus ultricies odio,
		 * non placerat justo erat vitae nulla. Duis viverra mauris mi, ac hendrerit metus
		 * dapibus iaculis.
		 * Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ullamcorper
		 * turpis vel lacus tincidunt accumsan. Pellentesque varius tristique tortor, non
		 * tincidunt elit porta ac. Praesent eget interdum turpis. Donec sodales ultrices
		 * metus at cursus. Phasellus vehicula augue eu elit semper mollis vitae aliquam
		 * lacus. Proin a egestas dui. Nam aliquet ultricies ipsum, eget bibendum lacus.
		 * Donec ut neque ultricies, mattis urna non, ullamcorper risus. Mauris efficitur
		 * tortor justo, a commodo justo tempus non. Nullam commodo vehicula magna ac
		 * malesuada. Nulla suscipit vulputate feugiat. Donec quis dignissim mauris.
		 * Integer volutpat mi ut urna molestie, sit amet placerat felis vestibulum.
		 * Quisque vulputate, metus et viverra condimentum, lacus purus ultricies odio,
		 * non placerat justo erat vitae nulla. Duis viverra mauris mi, ac hendrerit metus
		 * dapibus iaculis.
		 **/
		$foo = 'bar';
		$foo;
		$foo; // Hello
	}

	// Next line should report function too long
	public function tooLongWithComments() {
		$foo = 'bar';
		$foo;
		$foo;
		$foo;
		$foo;
		$foo;
		$foo;
		$foo;
		$foo; // Hello
		$foo; // Hello
		$foo; // Hello
		$foo; // Hello
		$foo; // Hello
		$foo; // Hello
		$foo;
		$foo;
		$foo; // Hello
		$foo; // Hello
		$foo; // Hello
		$foo; // Hello
		$foo; // Hello
		$foo; // Hello
	}

	public function doSomething() {
		$all = ['foo' => 'bar' ];
		// Next line should report no long arrays allowed
		$rest = array('x' => 'y');
		// Next line should report no extract allowed
		extract($all);
		$define = 'foo';
		$define;
		$rest;
	}

	// Next line should report function too long
	public function comparisonTest() {
		// Next line should report no condition assignment without comparison
		if ($actual = $this->getActual()) {
			echo $actual;
		}
		// Next line should report no condition assignment without comparison
		if (
			$actual = $this->getActual()
		) {
			echo $actual;
		}
		if ($actual = $this->getActual() == true) {
			echo $actual;
		}
		if ($actual = $this->getActual() > 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() < 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() <= 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() >= 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() === 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() != 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() !== 3) {
			echo $actual;
		}
		if (true == $actual = $this->getActual()) {
			echo $actual;
		}
		if ($actual) {
			return 'yo';
		}
	}

	public function getActual() {
		return 'hello';
	}

	public function extract() {
		$extract = 'foo';
		$extract;
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
	public function __callStatic($name, $args) {
		$name;
		$args;
	}
}
