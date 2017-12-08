<?php

namespace NeutronStandard\Sniffs\Functions;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class TypeHintSniff implements Sniff {
	public function register() {
		return [T_FUNCTION];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$this->checkForMissingArgumentHints($phpcsFile, $stackPtr);
		$this->checkForMissingReturnHints($phpcsFile, $stackPtr);
	}

	private function checkForMissingArgumentHints(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$openParenPtr = $tokens[$stackPtr]['parenthesis_opener'];
		$closeParenPtr = $tokens[$stackPtr]['parenthesis_closer'];
		$hintTypes = [
			T_STRING,
			T_ARRAY_HINT,
		];

		for ($i = ($openParenPtr + 1); $i < $closeParenPtr; $i++) {
			if ($tokens[$i]['code'] === T_VARIABLE) {
				$tokenBeforePtr = $phpcsFile->findPrevious(T_WHITESPACE, $i - 1, $openParenPtr, true);
				$tokenBefore = $tokens[$tokenBeforePtr];
				if (! $tokenBeforePtr || ! in_array($tokenBefore['code'], $hintTypes, true)) {
					$error = 'Argument type is missing';
					$phpcsFile->addWarning($error, $stackPtr, 'NoArgumentType');
				}
			}
		}
	}

	private function checkForMissingReturnHints(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$closeParenPtr = $tokens[$stackPtr]['parenthesis_closer'];
		$nextBracketPtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr + 1);
		$endOfFunctionPtr = $tokens[$nextBracketPtr]['bracket_closer'];
		$nextReturnPtr = $phpcsFile->findNext(T_RETURN, $nextBracketPtr + 1, $endOfFunctionPtr);
		$tokenAfterPtr = $phpcsFile->findNext(T_RETURN_TYPE, $closeParenPtr + 1, $nextBracketPtr);
		if ($nextReturnPtr && ! $tokenAfterPtr) {
			$error = 'Return type is missing';
			$phpcsFile->addWarning($error, $stackPtr, 'NoReturnType');
		}
	}
}
