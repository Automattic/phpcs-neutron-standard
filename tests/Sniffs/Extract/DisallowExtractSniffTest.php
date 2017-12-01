<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowExtractSniffTest extends TestCase {
	public function testDisallowExtractSniff() {
		$fixtureFile = __DIR__ . '/fixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Extract/DisallowExtractSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->getTestLocalFile($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$foundErrors = $phpcsFile->getErrors();
		$lines = $helper->getLineNumbersFromMessages($foundErrors);
		$this->assertEquals([7], $lines);
	}
}
