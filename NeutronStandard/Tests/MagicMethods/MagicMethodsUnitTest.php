<?php

namespace NeutronStandard\MagicMethods;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

class MagicMethodsUnitTest extends AbstractSniffUnitTest {
	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		return [
			7 => 1,
			11 => 1,
		];
	}
	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [];
	}
}
