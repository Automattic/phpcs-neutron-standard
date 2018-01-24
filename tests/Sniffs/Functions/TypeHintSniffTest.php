<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class TypeHintSniffTest extends TestCase {
	public function testTypeHintSniff() {
		$fixtureFile = __DIR__ . '/FunctionsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Functions/TypeHintSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([118, 123, 128, 133, 138, 195, 200], $lines);
	}

	public function testTypeHintSniffWithVariadicArgs() {
		$fixtureFile = __DIR__ . '/VariadicArgumentsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Functions/TypeHintSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([10, 19, 24, 29], $lines);
	}

	public function testTypeHintSniffWithClosures() {
		$fixtureFile = __DIR__ . '/ClosureFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Functions/TypeHintSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([5, 11], $lines);
	}
}
