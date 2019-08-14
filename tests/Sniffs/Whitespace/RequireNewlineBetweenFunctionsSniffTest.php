<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class RequireNewlineBetweenFunctionsSniffTest extends TestCase {
	public function testRequireNewlineBetweenFunctionsSniff() {
		$fixtureFile = __DIR__ . '/NewlinesBetweenFunctionsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Whitespace/RequireNewlineBetweenFunctionsSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([5, 14, 30, 52, 61, 70, 76], $lines);
	}

	public function testFixRequireNewlineBetweenFunctionsSniff() {
		$fixtureFile = __DIR__ . '/NewlinesBetweenFunctionsFixture.php';
		$fixedFixtureFile = __DIR__ . '/FixedNewlinesBetweenFunctionsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Whitespace/RequireNewlineBetweenFunctionsSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$actualContents = $helper->getFixedFileContents($phpcsFile);
		$fixedContents = file_get_contents($fixedFixtureFile);
		$this->assertEquals($fixedContents, $actualContents);
	}
}
