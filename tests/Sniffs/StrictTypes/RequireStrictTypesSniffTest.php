<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class RequireStrictTypesSniffTest extends TestCase {
	public function testRequireStrictTypes() {
		$fixtureFile = __DIR__ . '/fixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->getTestLocalFile($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$foundErrors = $phpcsFile->getErrors();
		$lines = $helper->getLineNumbersFromMessages($foundErrors);
		$this->assertEquals([1], $lines);
	}
}
