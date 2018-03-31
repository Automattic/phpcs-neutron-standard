<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class RequireImportsSniffTest extends TestCase {
	public function testRequireImportsSniff() {
		$fixtureFile = __DIR__ . '/RequireImportsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Imports/RequireImportsSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->ruleset->setSniffProperty(
			'NeutronStandard\Sniffs\Imports\RequireImportsSniff',
			'ignoreUnimportedSymbols',
			'/^(something_to_ignore|whitelisted_function|allowed_funcs_\w+)$/'
		);
		$phpcsFile->process();
		$lines = $helper->getWarningLineNumbersFromFile($phpcsFile);
		$expectedLines = [
			10,
			15,
			19,
			27,
			30,
			34,
			37,
			39,
			47,
			52,
			57,
			62,
			69,
			71,
			73,
			79,
			87,
			89,
			95,
		];
		$this->assertEquals($expectedLines, $lines);
		$this->assertSame(count($expectedLines), $phpcsFile->getWarningCount());
	}
}
