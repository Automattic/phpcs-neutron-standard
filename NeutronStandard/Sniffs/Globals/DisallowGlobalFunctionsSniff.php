<?php

namespace NeutronStandard\Sniffs\Globals;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowGlobalFunctionsSniff implements Sniff {
	public function register() {
		return [T_FUNCTION];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$currentToken = $tokens[$stackPtr];
		if (! empty($currentToken['conditions'])) {
			return;
		}
		$functionName = $phpcsFile->getDeclarationName($stackPtr);
		$error = 'Global functions are not allowed';
		$phpcsFile->addError($error, $stackPtr, 'GlobalFunctions');
	}
}


