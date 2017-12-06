<?php

namespace NeutronStandard\Sniffs\Namespaces;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowUnusedImportSniff implements Sniff {
	public function register() {
		return [T_USE];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$semiPtr = $phpcsFile->findNext([T_SEMICOLON], $stackPtr + 1);
		if (! $semiPtr) {
			return;
		}
		$importNameToken = $tokens[$semiPtr - 1];
		$importName = $importNameToken['content'];
		// Check rest of file for importName
		$secondImportNamePtr = $phpcsFile->findNext([T_STRING], $semiPtr + 1, null, false, $importName);
		if (! $secondImportNamePtr) {
			$error = 'Unused imports are not allowed';
			$phpcsFile->addError($error, $stackPtr, 'UnusedImport');
		}
	}
}
