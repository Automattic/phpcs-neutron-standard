<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHPUnit\Framework\TestCase;

class DisallowConditionAssignWithoutConditionalSniffTest extends TestCase {
	public function testDisallowConditionAssignWithoutConditionalSniff() {
		$fixtureFile = __DIR__ . '/ConditionsFixture.php';
		$sniffFile = __DIR__ . '/../../../NeutronStandard/Sniffs/Conditions/DisallowConditionAssignWithoutConditionalSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$lines = $helper->getErrorLineNumbersFromFile($phpcsFile);
		$this->assertEquals([5, 9], $lines);
	}
}
