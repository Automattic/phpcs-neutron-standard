<?php

namespace NeutronStandard\Sniffs\Whitespace;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireNewlineBetweenFunctionsSniff implements Sniff {
	public function register() {
		return [T_FUNCTION];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$helper = new SniffHelpers();
		if ($helper->isFunctionJustSignature($phpcsFile, $stackPtr)) {
			return;
		}
		$endofFuncPtr = $helper->getEndOfFunctionPtr($phpcsFile, $stackPtr);
		if (! $endofFuncPtr) {
			return;
		}
		$endOfLinePtr = $helper->getNextNewlinePtr($phpcsFile, $endofFuncPtr);
		if (! $endOfLinePtr || ! isset($tokens[$endOfLinePtr + 1])) {
			return;
		}
		$nextToken = $tokens[$endOfLinePtr + 1];
		if (! isset($nextToken['content']) || $nextToken['content'] === "\n") {
			return;
		}
		// Only trigger if the next line contains a function definition
		// or a comment
		$nextEndOfLinePtr = $helper->getNextNewlinePtr($phpcsFile, $endofFuncPtr + 1);
		$forbiddenTypes = [
			T_FUNCTION,
			T_COMMENT,
		];
		$nextForbiddenToken = $phpcsFile->findNext($forbiddenTypes, $endOfLinePtr + 1, null, false, null, true);
		if (! $nextForbiddenToken || $nextForbiddenToken > $nextEndOfLinePtr) {
			return;
		}

		$error = 'Functions must be separated by a blank line';
		$shouldFix = $phpcsFile->addFixableError($error, $endofFuncPtr, 'MissingNewline');
		if ($shouldFix) {
			$this->fixTokens($phpcsFile, $stackPtr);
		}
	}

	private function fixTokens(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$endofFuncPtr = $helper->getEndOfFunctionPtr($phpcsFile, $stackPtr);
		$phpcsFile->fixer->beginChangeset();
		$phpcsFile->fixer->addNewline($endofFuncPtr);
		$phpcsFile->fixer->endChangeset();
	}
}
