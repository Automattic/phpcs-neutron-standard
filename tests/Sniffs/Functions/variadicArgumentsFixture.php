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
}
