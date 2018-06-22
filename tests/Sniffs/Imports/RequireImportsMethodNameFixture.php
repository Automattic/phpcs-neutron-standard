<?php
declare(strict_types=1);

namespace CAR_APP\Vehicles\Greatness;

class GreatClass {
	private function activate() {
	}

	public function run() {
		activate(); // should report unimported function
	}
}
