<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Config;

class SniffTestHelper {
	public function getTestLocalFile(string $sniffFile, string $fixtureFile) : LocalFile {
		$config = new Config();
		$config->cache = false;
		$ruleset = new Ruleset($config);
		$ruleset->registerSniffs([$sniffFile], [], []);
		$ruleset->populateTokenListeners();
		return new LocalFile($fixtureFile, $ruleset, $config);
	}
}
