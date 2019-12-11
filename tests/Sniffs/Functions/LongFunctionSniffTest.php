<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class LongFunctionSniffTest extends TestCase {
	public function testLongFunctionSniff() {
		$fixtureFile = __DIR__ . '/LongFunctionsFixture.php';
		$sniffFile =
			__DIR__ .
			'/../../../NeutronStandard/Sniffs/Functions/LongFunctionSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs(
			$sniffFile,
			$fixtureFile
		);
		$phpcsFile->process();
		$warnings = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$this->assertEquals([61], $warnings);
	}

	public function testLongFunctionWithDifferentLength() {
		$fixtureFile = __DIR__ . '/LongFunctionsFixture.php';
		$sniffFile =
			__DIR__ .
			'/../../../NeutronStandard/Sniffs/Functions/LongFunctionSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs(
			$sniffFile,
			$fixtureFile
		);
		$phpcsFile->ruleset->setSniffProperty(
			'NeutronStandard\Sniffs\Functions\LongFunctionSniff',
			'maxFunctionLines',
			'50'
		);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$expectedLines = [];
		$this->assertEquals($expectedLines, $lines);
	}

	public function testLongFunctionWithShorterLength() {
		$fixtureFile = __DIR__ . '/LongFunctionsFixture.php';
		$sniffFile =
			__DIR__ .
			'/../../../NeutronStandard/Sniffs/Functions/LongFunctionSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs(
			$sniffFile,
			$fixtureFile
		);
		$phpcsFile->ruleset->setSniffProperty(
			'NeutronStandard\Sniffs\Functions\LongFunctionSniff',
			'maxFunctionLines',
			'2'
		);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$expectedLines = [3, 61];
		$this->assertEquals($expectedLines, $lines);
	}
}
