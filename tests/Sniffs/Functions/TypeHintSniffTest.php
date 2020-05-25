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
		$errorLines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$warningLines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([138, 194, 198, 202], $errorLines);
		$this->assertEquals([118, 123, 128, 133], $warningLines);
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
		$warningLines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$errorLines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([5, 11], $warningLines);
		$this->assertEquals([26, 33, 40], $errorLines);
	}

	public function testTypeHintSniffWithInterface() {
		$fixtureFile = __DIR__ . '/InterfaceFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Functions/TypeHintSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}

	public function testTypeHintSniffWithAbstractClass() {
		$fixtureFile = __DIR__ . '/AbstractClassFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Functions/TypeHintSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([12], $lines);
	}
}
