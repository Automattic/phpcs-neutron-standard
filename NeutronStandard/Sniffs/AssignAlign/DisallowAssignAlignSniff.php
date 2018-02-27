<?php

namespace NeutronStandard\Sniffs\AssignAlign;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowAssignAlignSniff implements Sniff {
	public function register() {
		return [T_WHITESPACE];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		// If the next non-whitespace after multiples spaces is an equal sign or double arrow, mark a warning
		if (strlen($tokens[$stackPtr]['content']) <= 1) {
			return;
		}
		$nextNonWhitespacePtr = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true, null, false);
		if ($nextNonWhitespacePtr !== false && $this->isTokenAnAssignment($tokens[$nextNonWhitespacePtr])) {
			$error = 'Assignment alignment is not allowed';
			$shouldFix = $phpcsFile->addFixableWarning($error, $stackPtr, 'Aligned');
			if ($shouldFix) {
				$this->fixTokens($phpcsFile, $stackPtr);
			}
		}
	}

	private function isTokenAnAssignment($token) {
		$assignOperators = [
			T_EQUAL,
			T_DOUBLE_ARROW,
		];
		return in_array($token['code'], $assignOperators, true);
	}

	private function fixTokens(File $phpcsFile, $stackPtr) {
		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($stackPtr, ' ');
		$phpcsFile->fixer->endChangeset();
	}
}
