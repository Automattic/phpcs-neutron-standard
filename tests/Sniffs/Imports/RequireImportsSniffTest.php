<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class RequireImportsSniffTest extends TestCase {
	public function testRequireImportsSniff() {
		$fixtureFile = __DIR__ . '/RequireImportsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Imports/RequireImportsSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([
			22,
			25,
			29,
			32,
			34,
			42,
			47,
		], $lines);
	}
}
