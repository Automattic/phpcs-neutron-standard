<?php

namespace NeutronStandard\Sniffs\Conditions;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowConditionAssignWithoutConditionalSniff implements Sniff {
	public function register() {
		return [T_OPEN_PARENTHESIS];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		// if previous non-whitespace token is `T_IF`
		$prevNonWhitespacePtr = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true, null, false);
		if ($tokens[$prevNonWhitespacePtr]['type'] !== 'T_IF') {
			return;
		}
		// if there is a T_EQUAL after this before the end of statement
		$endOfStatementPtr = $phpcsFile->findEndOfStatement($stackPtr + 1);
		$nextAssignPtr = $phpcsFile->findNext(T_EQUAL, $stackPtr + 1, $endOfStatementPtr, false, null, false);
		if (! $nextAssignPtr) {
			return;
		}
		// if there is not a T_IS_EQUAL (or any other comparator!) before the end of statement
		$comparators = [
			T_IS_EQUAL,
			T_IS_NOT_EQUAL,
			T_IS_IDENTICAL,
			T_IS_NOT_IDENTICAL,
			T_IS_SMALLER_OR_EQUAL,
			T_IS_GREATER_OR_EQUAL,
			T_LESS_THAN,
			T_GREATER_THAN,
			T_SPACESHIP,
		];
		$nextEqualPtr = $phpcsFile->findNext($comparators, $stackPtr + 1, $endOfStatementPtr, false, null, false);
		if ($nextEqualPtr) {
			return;
		}
		// mark an error
		$error = 'Conditions that contain assignments must have explicit comparators';
		$phpcsFile->addError($error, $stackPtr, 'ConditionAssignWithoutConditional');
	}
}
