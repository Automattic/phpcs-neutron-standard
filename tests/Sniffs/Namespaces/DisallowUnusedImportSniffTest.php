<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowUnusedImportSniffTest extends TestCase {
	public function testDisallowUnusedImports() {
		$fixtureFile = __DIR__ . '/ImportFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Namespaces/DisallowUnusedImportSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([5, 7, 11, 13, 16, 19], $lines);
	}
}
