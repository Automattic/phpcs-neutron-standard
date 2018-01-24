<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class RequireStrictTypesSniffTest extends TestCase {
	public function testRequireStrictTypesIfMissing() {
		$fixtureFile = __DIR__ . '/StrictTypesFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([1], $lines);
	}

	public function testRequireStrictTypesPassesIfPresentOnNewLine() {
		$fixtureFile = __DIR__ . '/HasStrictTypesFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}

	public function testRequireStrictTypesPassesIfPresentOnSameLine() {
		$fixtureFile = __DIR__ . '/OneLineStrictTypesFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}

	public function testRequireStrictTypesOnlyIncludesFirstOpenTag() {
		$fixtureFile = __DIR__ . '/MultipleOpenTagsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}

	public function testRequireStrictTypesIgnoresInterfaceFiles() {
		$fixtureFile = __DIR__ . '/InterfaceOnlyFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}

	public function testRequireStrictTypesDoesNotIgnoreInterfaceAndClass() {
		$fixtureFile = __DIR__ . '/InterfaceAndClassFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/StrictTypes/RequireStrictTypesSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([1], $lines);
	}
}
