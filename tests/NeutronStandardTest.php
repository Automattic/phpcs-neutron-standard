<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;
use PHPUnitJustSnaps\SnapshotAsserter;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Config;

class NeutronStandardTest extends TestCase {
	use SnapshotAsserter;

	public function testExtractNotAllowed() {
		$filePath = __DIR__ . '/fixture.php';
		// $standardName = 'NeutronStandard';
		// $sniffCode = 'NeutronStandard.Extract.DisallowExtract.Extract';
		$sniffFile = __DIR__ . '/../NeutronStandard/Sniffs/Extract/DisallowExtractSniff.php';

		$config = new Config();
		$config->cache = false;
		// $config->standards = [$standardName];
		// $config->sniffs = [$sniffCode];
		// $config->ignored = [];
		$ruleset = new Ruleset($config);
		$ruleset->registerSniffs([$sniffFile], [], []);
		$ruleset->populateTokenListeners();

		$phpcsFile = new LocalFile($filePath, $ruleset, $config);
		$phpcsFile->process();
		$foundErrors = $phpcsFile->getErrors();
		$this->assertMatchesSnapshot($foundErrors);
	}
}
