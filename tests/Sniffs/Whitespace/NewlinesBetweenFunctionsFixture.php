<?php

function foobar() {
	runIt();
}
function barfoo() {
	runIt();
}
runIt();

class SomeThing {
	public function foobar() {
		runIt();
	}
	private function barfoo() {
		runIt();
	}

	public function baz() {
		runIt();
	}
}

function foo2() {
	runIt();
}

function foo3() {
	runIt();
}
function foo4() {
	runIt();
}

class Thing2 {
	public function hasInnerFunctions() {
		$innerFuncA = function () {
			runIt();
		};
		$innerFuncB = function () {
			runIt();
		};
		function totallyInnerA() {
			runIt();
		}
		$innerFuncA();
	}

	public function hasInnerFunctions2() {
		function totallyInnerA() {
			runIt();
		}
		function totallyInnerB() {
			runIt();
		}
		totallyInnerA();
	}
}

trait Thing3 {
	public function traitFuncA() {}
	public function traitFuncB() {}

	public function traitFuncC() {}
}

class Thing4 {
	public function funcA() {
		runIt();
	}
	/*
	 * This is a comment
	 */
	public function funcB() {
		runIt();
	}
	// This is a comment
	public function funcC() {
		runIt();
	}
}

interface Thing4 {
	public function traitFuncA();
	public function traitFuncB();

	public function traitFuncC();
}
