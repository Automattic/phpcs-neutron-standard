<?php
declare(strict_types=1);

namespace NeutronStandard;

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

	// From https://stackoverflow.com/questions/619610/whats-the-most-efficient-test-of-whether-a-php-string-ends-with-another-string
	public function doesStringEndWith(string $string, string $test): bool {
		$strlen = strlen($string);
		$testlen = strlen($test);
		if ($testlen > $strlen) {
			return false;
		}
		return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
	}

	public function getNextNonWhitespace(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextNonWhitespacePtr = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true, null, true);
		return $nextNonWhitespacePtr ? $tokens[$nextNonWhitespacePtr] : null;
	}

	public function getNextNewlinePtr(File $phpcsFile, $stackPtr) {
		return $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, false, "\n");
	}

	public function getArgumentTypePtr(File $phpcsFile, $stackPtr) {
		$ignoredTypes = [
			T_WHITESPACE,
			T_ELLIPSIS,
		];
		$openParenPtr = $phpcsFile->findPrevious(T_OPEN_PARENTHESIS, $stackPtr - 1, null, false);
		if (! $openParenPtr) {
			return false;
		}
		return $phpcsFile->findPrevious($ignoredTypes, $stackPtr - 1, $openParenPtr, true);
	}

	public function isReturnValueVoid(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		if ($tokens[$stackPtr]['code'] !== T_RETURN) {
			return false;
		}
		$returnValue = $this->getNextNonWhitespace($phpcsFile, $stackPtr);
		return ! $returnValue || $returnValue['code'] === 'PHPCS_T_SEMICOLON';
	}

	public function getNextReturnTypePtr(File $phpcsFile, $stackPtr) {
		$startOfFunctionPtr = $this->getStartOfFunctionPtr($phpcsFile, $stackPtr);
		$colonPtr = $phpcsFile->findNext(T_COLON, $stackPtr, $startOfFunctionPtr);
		if (! $colonPtr) {
			return false;
		}
		return $phpcsFile->findNext(T_WHITESPACE, $colonPtr + 1, null, true, null, true);
	}

	public function getNextSemicolonPtr(File $phpcsFile, $stackPtr) {
		return $phpcsFile->findNext(T_SEMICOLON, $stackPtr + 1);
	}

	public function getEndOfFunctionPtr(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		if ($this->isFunctionJustSignature($phpcsFile, $stackPtr)) {
			return $this->getNextSemicolonPtr($phpcsFile, $stackPtr);
		}
		$openFunctionBracketPtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr + 1);
		return $openFunctionBracketPtr && isset($tokens[$openFunctionBracketPtr]['bracket_closer'])
			? $tokens[$openFunctionBracketPtr]['bracket_closer']
			: $this->getNextSemicolonPtr($phpcsFile, $stackPtr);
	}

	public function getStartOfFunctionPtr(File $phpcsFile, $stackPtr) {
		$openFunctionBracketPtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr + 1);
		$nextSemicolonPtr = $this->getNextSemicolonPtr($phpcsFile, $stackPtr);
		if ($openFunctionBracketPtr && $nextSemicolonPtr && $openFunctionBracketPtr > $nextSemicolonPtr) {
			return $nextSemicolonPtr;
		}
		return $openFunctionBracketPtr
			? $openFunctionBracketPtr + 1
			: $this->getEndOfFunctionPtr($phpcsFile, $stackPtr);
	}

	public function isFunctionJustSignature(File $phpcsFile, $stackPtr) {
		$openFunctionBracketPtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr + 1);
		$nextSemicolonPtr = $this->getNextSemicolonPtr($phpcsFile, $stackPtr);
		if ($openFunctionBracketPtr && $nextSemicolonPtr && $openFunctionBracketPtr > $nextSemicolonPtr) {
			return true;
		}
		return ! $openFunctionBracketPtr;
	}
}
