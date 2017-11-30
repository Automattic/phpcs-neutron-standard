<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;
use PHPUnitJustSnaps\SnapshotAsserter;

class DisallowLongformArraySniffTest extends TestCase {
	use SnapshotAsserter;

	public function testDisallowLongformArraySniff() {
		$fixtureFile = __DIR__ . '/fixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Arrays/DisallowLongformArraySniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->getTestLocalFile($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$foundErrors = $phpcsFile->getErrors();
		$this->assertMatchesSnapshot($foundErrors);
	}
}
