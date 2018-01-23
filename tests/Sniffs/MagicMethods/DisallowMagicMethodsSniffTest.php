<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowMagicMethodsSniffTest extends TestCase {
	public function testDisallowMagicMethodsSniffs() {
		$fixtureFile = __DIR__ . '/MagicMethodsFixture.php';
		$sniffFiles = [
			__DIR__ . '/../../../NeutronStandard/Sniffs/MagicMethods/DisallowMagicGetSniff.php',
			__DIR__ . '/../../../NeutronStandard/Sniffs/MagicMethods/DisallowMagicSetSniff.php',
			__DIR__ . '/../../../NeutronStandard/Sniffs/MagicMethods/DisallowMagicSerializeSniff.php',
		];
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFiles, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([17, 21, 26], $lines);
	}
}
