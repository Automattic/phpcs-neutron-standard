<?php

namespace NeutronStandard\Sniffs\StrictTypes;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireStrictTypesSniff implements Sniff {
	public function register() {
		return [T_OPEN_TAG];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextPtr = $phpcsFile->findNext(T_DECLARE, $stackPtr, null, false, null, true);
		if (! $nextPtr || ! isset($tokens[$nextPtr])) {
			$error = 'File must start with a strict types declaration';
			$phpcsFile->addError($error, $stackPtr, 'StrictTypes');
		}
	}
}

