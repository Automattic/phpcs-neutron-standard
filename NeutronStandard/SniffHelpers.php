<?php
declare(strict_types=1);

namespace NeutronStandard;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class SniffHelpers {
	public function isFunctionCall(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextNonWhitespacePtr = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true, null, false);
		// if the next non-whitespace token is not a paren, then this is not a function call
		if ($tokens[$nextNonWhitespacePtr]['type'] !== 'T_OPEN_PARENTHESIS') {
			return false;
		}
		// if the previous non-whitespace token is a function, then this is not a function call
		$prevNonWhitespacePtr = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true, null, false);
		if ($tokens[$prevNonWhitespacePtr]['type'] === 'T_FUNCTION') {
			return false;
		}
		return true;
	}
}
