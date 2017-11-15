<?php

namespace NeutronStandard\Sniffs\Extract;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowExtractSniff implements Sniff {
	public function register() {
		return [T_STRING];
	}

	public function process(File $phpcsFile, $stackPtr) {
		if (! $this->isFunctionCall($phpcsFile, $stackPtr)) {
			return;
		}
		$tokens = $phpcsFile->getTokens();
		$functionName = $tokens[$stackPtr]['content'];
		if ($functionName === 'extract') {
			$error = 'Extract is not allowed';
			$phpcsFile->addError($error, $stackPtr, 'Extract');
		}
	}

	private function isFunctionCall(File $phpcsFile, $stackPtr) {
		$parenPtr = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr, null, false, null, true);
		if (! $parenPtr) {
			return false;
		}
		$functionPtr = $phpcsFile->findPrevious(T_FUNCTION, $stackPtr, null, false, null, true);
		if ($functionPtr) {
			return false;
		}
		return true;
	}
}
