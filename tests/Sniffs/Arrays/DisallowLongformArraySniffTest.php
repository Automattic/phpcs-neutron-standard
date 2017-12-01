<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowLongformArraySniffTest extends TestCase {
	public function testDisallowLongformArraySniff() {
		$fixtureFile = __DIR__ . '/fixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Arrays/DisallowLongformArraySniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->getTestLocalFile($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$foundErrors = $phpcsFile->getErrors();
		$lines = $helper->getLineNumbersFromMessages($foundErrors);
		$this->assertEquals([5], $lines);
	}
}
