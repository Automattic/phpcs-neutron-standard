<?php

namespace NeutronStandard\Sniffs\Whitespace;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowMultipleNewlinesSniff implements Sniff {
	public function register() {
		return [T_WHITESPACE];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		if ($tokens[$stackPtr]['content'] !== "\n") {
			return;
		}
		if ($stackPtr < 3) {
			return;
		}
		if ($tokens[$stackPtr - 1]['content'] !== "\n") {
			return;
		}
		if ($tokens[$stackPtr - 2]['content'] !== "\n") {
			return;
		}
		$error = 'Multiple adjacent blank lines are not allowed';
		$shouldFix = $phpcsFile->addFixableError($error, $stackPtr, 'MultipleNewlines');
		if ($shouldFix) {
			$this->fixTokens($phpcsFile, $stackPtr);
		}
	}

	private function fixTokens(File $phpcsFile, $stackPtr) {
		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->replaceToken($stackPtr, '');
		$phpcsFile->fixer->endChangeset();
	}
}
