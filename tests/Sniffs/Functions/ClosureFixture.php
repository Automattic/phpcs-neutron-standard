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

	// Next line should warn about type hint spacing
	public function hasClosureWithReturnAndWrongSpacing() {
		$myFunc = function () : bool {
			return true;
		};
	}

	// Next line should warn about type hint spacing
	public function hasClosureWithReturnAndWrongSpacing2() {
		$myFunc = function () :bool{
			return true;
		};
	}

	// Next line should be okay
	public function hasClosureWithReturnAndOptionalReturnType() {
		$myFunc = function (): ?bool{
			return true;
		};
	}
}
