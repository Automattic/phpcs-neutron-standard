<?php
class MyClass {
	public function comparisonTest() {
		// Next line should report no condition assignment without comparison
		if ($actual = $this->getActual()) {
			echo $actual;
		}
		// Next line should report no condition assignment without comparison
		if (
			$actual = $this->getActual()
		) {
			echo $actual;
		}
		if ($actual = $this->getActual() == true) {
			echo $actual;
		}
		if ($actual = $this->getActual() > 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() < 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() <= 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() >= 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() === 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() != 3) {
			echo $actual;
		}
		if ($actual = $this->getActual() !== 3) {
			echo $actual;
		}
		if (true == $actual = $this->getActual()) {
			echo $actual;
		}
		if ($actual) {
			return 'yo';
		}
	}

	public function getActual() {
		return 'hello';
	}
}

