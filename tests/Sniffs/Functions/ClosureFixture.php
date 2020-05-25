<?php
class MyClass {
	public function hasClosureWithReturnAndNoHint() {
		// Next line should warn about no type hint
		$myFunc = function () {
			return true;
		};
	}

	// Next line should warn about no type hint
	public function hasNoReturnHintAndClosure() {
		$myFunc = function () {
			'foobar';
		};
		return true;
	}

	public function hasClosureWithReturn() {
		$myFunc = function (): bool {
			return true;
		};
	}

	public function hasClosureWithReturnAndWrongSpacing() {
		// Next line should warn about type hint spacing
		$myFunc = function () : bool {
			return true;
		};
	}

	public function hasClosureWithReturnAndWrongSpacing2() {
		// Next line should warn about type hint spacing
		$myFunc = function () :bool {
			return true;
		};
	}

	public function hasClosureWithReturnAndWrongSpacing3() {
		// Next line should warn about type hint spacing
		$myFunc = function (): bool{
			return true;
		};
	}

	public function hasClosureWithReturnAndOptionalReturnType() {
		// Next line should be okay
		$myFunc = function (): ?bool {
			return true;
		};
	}
}
