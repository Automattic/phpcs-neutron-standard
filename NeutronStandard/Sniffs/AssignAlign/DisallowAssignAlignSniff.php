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
		if (strlen($tokens[$stackPtr]['content']) > 1) {
			$assignOperators = [
				T_EQUAL,
				T_DOUBLE_ARROW,
			];
			$nextNonWhitespacePtr = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true, null, false);
			if ($nextNonWhitespacePtr !== false && in_array($tokens[$nextNonWhitespacePtr]['code'], $assignOperators, true)) {
				$error = 'Assignment alignment is not allowed';
				$phpcsFile->addWarning($error, $stackPtr, 'Aligned');
			}
		}
	}
}
