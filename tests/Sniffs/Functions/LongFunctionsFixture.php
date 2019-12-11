<?php
abstract class MyClass {
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
}
