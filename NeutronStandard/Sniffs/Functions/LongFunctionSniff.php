<?php

namespace NeutronStandard\Sniffs\Functions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class LongFunctionSniff implements Sniff {
	public function register() {
		return [T_FUNCTION];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextBracketPtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr + 1);
		$startLineNumber = $tokens[$nextBracketPtr]['line'] + 1;
		$endOfFunctionPtr = $tokens[$nextBracketPtr]['bracket_closer'];
		$endLineNumber = $tokens[$endOfFunctionPtr]['line'];
		$newlineCount = $endLineNumber - $startLineNumber;
		if ($newlineCount > 20) {
			$error = 'Function is longer than 20 lines';
			$phpcsFile->addWarning($error, $stackPtr, 'LongFunction');
		}
	}
}

