<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Config;

class SniffTestHelper {
	public function getTestLocalFile($sniffFiles, string $fixtureFile): LocalFile {
		$config = new Config();
		$config->cache = false;
		$ruleset = new Ruleset($config);
		if (! is_array($sniffFiles)) {
			$sniffFiles = [$sniffFiles];
		}
		$ruleset->registerSniffs($sniffFiles, [], []);
		$ruleset->populateTokenListeners();
		return new LocalFile($fixtureFile, $ruleset, $config);
	}

	public function getLineNumbersFromMessages(array $messages): array {
		return array_keys($messages);
	}
}
