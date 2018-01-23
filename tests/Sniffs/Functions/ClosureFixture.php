<?php
class MyClass {
	public function hasClosureWithReturnAndNoHint() {
		// Next line should warn about no type hint
		$myFunc = function() {
			return true;
		};
	}

	// Next line should warn about no type hint
	public function hasNoReturnHintAndClosure() {
		$myFunc = function() {
			'foobar';
		};
		return true;
	}

	public function hasClosureWithReturn() {
		$myFunc = function() : bool {
			return true;
		};
	}
}
