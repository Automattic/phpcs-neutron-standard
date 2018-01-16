<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class RequireStrictTypesSniffTest extends TestCase {
	public function testRequireStrictTypesIfMissing() {
		$fixtureFile = __DIR__ . '/fixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([1], $lines);
	}

	public function testRequireStrictTypesPassesIfPresentOnNewLine() {
		$fixtureFile = __DIR__ . '/fixture2.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}

	public function testRequireStrictTypesPassesIfPresentOnSameLine() {
		$fixtureFile = __DIR__ . '/fixture3.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}

	public function testRequireStrictTypesOnlyIncludesFirstOpenTag() {
		$fixtureFile = __DIR__ . '/multipleOpenTagsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}

	public function testRequireStrictTypesIgnoresInterfaceFiles() {
		$fixtureFile = __DIR__ . '/interfaceOnlyFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}

	public function testRequireStrictTypesDoesNotIgnoreInterfaceAndClass() {
		$fixtureFile = __DIR__ . '/interfaceAndClassFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([1], $lines);
	}
}
