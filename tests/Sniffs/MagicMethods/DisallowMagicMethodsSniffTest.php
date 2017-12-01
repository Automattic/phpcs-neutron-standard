<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowMagicMethodsSniffTest extends TestCase {
	public function testDisallowMagicMethodsSniffs() {
		$fixtureFile = __DIR__ . '/fixture.php';
		$sniffFiles = [
			__DIR__ . '/../../../NeutronStandard/Sniffs/MagicMethods/DisallowMagicGetSniff.php',
			__DIR__ . '/../../../NeutronStandard/Sniffs/MagicMethods/DisallowMagicSetSniff.php',
			__DIR__ . '/../../../NeutronStandard/Sniffs/MagicMethods/DisallowMagicSerializeSniff.php',
		];

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->getTestLocalFile($sniffFiles, $fixtureFile);
		$phpcsFile->process();
		$foundErrors = $phpcsFile->getErrors();
		$lines = $helper->getLineNumbersFromMessages($foundErrors);
		$this->assertEquals([17, 21, 26], $lines);
	}
}
