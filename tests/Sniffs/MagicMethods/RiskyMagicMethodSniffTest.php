<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class RiskyMagicMethodSniffTest extends TestCase {
	public function testRiskyMagicMethodsSniff() {
		$fixtureFile = __DIR__ . '/MagicMethodsFixture.php';
		$sniffFiles = [
			__DIR__ . '/../../../NeutronStandard/Sniffs/MagicMethods/RiskyMagicMethodSniff.php',
		];
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFiles, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([32, 36, 42], $lines);
	}
}
