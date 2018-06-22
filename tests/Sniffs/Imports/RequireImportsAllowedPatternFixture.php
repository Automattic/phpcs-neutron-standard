<?php
declare(strict_types=1);

namespace CAR_APP\Vehicles\Greatness;

use function whitelisted_function;

class GreatClass {
	private function activate() {
		whitelisted_function();
		another_whitelisted_function();
		non_whitelisted_function(); // this should report an unimported function
	}
}
