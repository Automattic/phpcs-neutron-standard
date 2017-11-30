<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;
use PHPUnitJustSnaps\SnapshotAsserter;

class DisallowDefineSniffTest extends TestCase {
	use SnapshotAsserter;

	public function testDisallowDefineSniff() {
		$fixtureFile = __DIR__ . '/fixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Constants/DisallowDefineSniff.php';

		$helper = new SniffTestHelper();
		$phpcsFile = $helper->getTestLocalFile($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$foundErrors = $phpcsFile->getErrors();
		$this->assertMatchesSnapshot($foundErrors);
	}
}
