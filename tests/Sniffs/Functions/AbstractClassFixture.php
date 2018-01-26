<?php

abstract class MyClass {

	abstract public function abstractFunctionWithReturn(): int;

	abstract public function abstractFunctionWithoutReturn();

	abstract public function abstractFunctionWithArguments(float $arg1, string $arg2);

	// Next line should warn about no type hint
	abstract public function abstractFunctionWithArgumentsMissingType(float $arg1, $arg2);

	public function hasHintsWithFloat(float $arg1): float {
		return $arg1;
	}
}
