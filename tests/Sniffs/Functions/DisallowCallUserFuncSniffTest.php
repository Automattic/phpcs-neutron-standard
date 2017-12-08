<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowCallUserFuncSniffTest extends TestCase {
	public function testDisallowCallUserFuncSniff() {
		$fixtureFile = __DIR__ . '/CallUserFuncFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Functions/DisallowCallUserFuncSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([7, 10], $lines);
	}
}
