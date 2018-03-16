<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class RequireParenthesisSpacingSniffTest extends TestCase {
	public function testRequireNewlineBetweenFunctionsSniff() {
		$fixtureFile = __DIR__ . '/ParenthesisSpacingFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Whitespace/RequireParenthesisSpacingSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([5, 6, 7, 11, 14, 17, 20, 24, 25, 26, 28, 29, 30], $lines);
	}

	public function testFixRequireNewlineBetweenFunctionsSniff() {
		$fixtureFile = __DIR__ . '/ParenthesisSpacingFixture.php';
		$fixedFixtureFile = __DIR__ . '/FixedParenthesisSpacingFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Whitespace/RequireParenthesisSpacingSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$actualContents = $helper->getFixedFileContents($phpcsFile);
		$fixedContents = file_get_contents($fixedFixtureFile);
		$this->assertEquals($fixedContents, $actualContents);
	}
}
