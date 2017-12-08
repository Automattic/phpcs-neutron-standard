<?php

namespace NeutronStandard\Sniffs\Functions;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class VariableFunctionsSniff implements Sniff {
	public function register() {
		return [T_VARIABLE];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextNonWhitespacePtr = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true, null, false);
		if (! $nextNonWhitespacePtr) {
			return;
		}
		if ($tokens[$nextNonWhitespacePtr]['code'] !== T_OPEN_PARENTHESIS) {
			return;
		}
		$message = 'Variable functions are discouraged';
		$phpcsFile->addWarning($message, $stackPtr, 'VariableFunction');
	}
}
