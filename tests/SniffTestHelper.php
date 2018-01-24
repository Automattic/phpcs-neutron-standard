<?php
declare(strict_types=1);

namespace NeutronStandardTest;

use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Config;

class SniffTestHelper {
	public function prepareLocalFileForSniffs($sniffFiles, string $fixtureFile): LocalFile {
		$config = new Config();
		$ruleset = new Ruleset($config);
		if (! is_array($sniffFiles)) {
			$sniffFiles = [$sniffFiles];
		}
		$ruleset->registerSniffs($sniffFiles, [], []);
		$ruleset->populateTokenListeners();
		if (! file_exists($fixtureFile)) {
			throw new \Exception('Fixture file does not exist! ' . $fixtureFile);
		}
		return new LocalFile($fixtureFile, $ruleset, $config);
	}

	public function getLineNumbersFromMessages(array $messages): array {
		return array_keys($messages);
	}

	public function getWarningLineNumbersFromFile(LocalFile $phpcsFile): array {
		return $this->getLineNumbersFromMessages($phpcsFile->getWarnings());
	}

	public function getErrorLineNumbersFromFile(LocalFile $phpcsFile): array {
		return $this->getLineNumbersFromMessages($phpcsFile->getErrors());
	}

	public function getFixedFileContents(LocalFile $phpcsFile) {
		$phpcsFile->fixer->startFile($phpcsFile);
		$phpcsFile->fixer->fixFile();
		return $phpcsFile->fixer->getContents();
	}
}
