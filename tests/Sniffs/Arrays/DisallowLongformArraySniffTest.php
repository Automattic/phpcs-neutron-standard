<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowLongformArraySniffTest extends TestCase {
	public function testDisallowLongformArraySniff() {
		$fixtureFile = __DIR__ . '/ArraysFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Arrays/DisallowLongformArraySniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([5], $lines);
	}

	public function testFixDisallowLongFormArraySniff() {
		$fixtureFile = __DIR__ . '/ArraysFixture.php';
		$fixedFixtureFile = __DIR__ . '/FixedLongFormArrayFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Arrays/DisallowLongformArraySniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$actualContents = $helper->getFixedFileContents($phpcsFile);
		$fixedContents = file_get_contents($fixedFixtureFile);
		$this->assertEquals($fixedContents, $actualContents);
	}
}
