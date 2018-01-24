<?php
declare(strict_types=1);

class MyClass {
	public function saveWordsWithType(string ...$words) {
		$this->words = $words;
	}

	// The following line should report a missing type
	public function saveWordsWithoutType(...$words) {
		$this->words = $words;
	}

	public function variadicFunctionWithRequiredArgAndTypes(int $key, string ...$words) {
		$this->words = $words;
	}

	// The following line should report a missing type
	public function variadicFunctionWithRequiredArgAndNoTypes($key, ...$words) {
		$this->words = $words;
	}

	// The following line should report a missing type
	public function variadicFunctionWithRequiredArgAndRequiredTypes(int $key, ...$words) {
		$this->words = $words;
	}

	// The following line should report a missing type
	public function variadicFunctionWithRequiredArgAndVariadicTypes($key, string ...$words) {
		$this->words = $words;
	}
}
