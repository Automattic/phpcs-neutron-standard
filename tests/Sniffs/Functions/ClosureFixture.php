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
		$myFunc = function () : bool {
			return true;
		};
	}

	public function hasClosureWithOneVoidReturnAndBoolHint() {
		// The next line should report an invalid void return
		$myFunc = function () : bool {
			if (rand(1, 10) > 5) {
				return true;
			}
			return;
		};
	}
}
