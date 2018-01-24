<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowDefineSniffTest extends TestCase {
	public function testDisallowDefineSniff() {
		$fixtureFile = __DIR__ . '/ConstantsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Constants/DisallowDefineSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([4, 8], $lines);
	}
}
