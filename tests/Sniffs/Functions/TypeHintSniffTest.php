<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class TypeHintSniffTest extends TestCase {
	public function testTypeHintSniff() {
		$fixtureFile = __DIR__ . '/fixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Functions/TypeHintSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([118, 123, 128, 133, 139, 145, 159, 216, 221], $lines);
	}

	public function testTypeHintSniffWithVariadicArgs() {
		$fixtureFile = __DIR__ . '/variadicArgumentsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Functions/TypeHintSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([10], $lines);
	}
}
