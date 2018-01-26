<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowGlobalFunctionsSniffTest extends TestCase {
	public function testDisallowGlobalFunctionsSniff() {
		$fixtureFile = __DIR__ . '/GlobalsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Globals/DisallowGlobalFunctionsSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([3], $lines);
	}

	public function testDisallowGlobalFunctionsSniffAllowsNamespacedGlobals() {
		$fixtureFile = __DIR__ . '/NamespacedGlobalFunctionsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Globals/DisallowGlobalFunctionsSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([], $lines);
	}
}
