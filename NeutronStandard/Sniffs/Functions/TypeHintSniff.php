<?php

namespace NeutronStandard\Sniffs\Functions;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class TypeHintSniff implements Sniff {
	public function register() {
		return [T_FUNCTION, T_CLOSURE];
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
			T_CALLABLE,
			T_SELF,
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
		$returnTypePtr = $phpcsFile->findNext(T_RETURN_TYPE, $closeParenPtr + 1, $nextBracketPtr);
		$returnType = $tokens[$returnTypePtr];

		$foundReturn = false;
		$scopeClosers = [];
		for ($ptr = $nextBracketPtr + 1; $ptr < $endOfFunctionPtr; $ptr++) {
			$token = $tokens[$ptr];
			if (! empty($scopeClosers) && $ptr === $scopeClosers[0]) {
				array_shift($scopeClosers);
			}
			if ($token['code'] === T_CLOSURE) {
				array_unshift($scopeClosers, $token['scope_closer']);
			}
			if (empty($scopeClosers) && $token['code'] === T_RETURN && ! $this->isReturnValueVoid($phpcsFile, $ptr)) {
				$foundReturn = true;
			}
		}

		if (! $foundReturn && $returnTypePtr && $returnType['content'] !== 'void') {
			$error = 'Return type with no return';
			$phpcsFile->addWarning($error, $stackPtr, 'UnusedReturnType');
		}
		if ($foundReturn && ! $returnTypePtr) {
			$error = 'Return type is missing';
			$phpcsFile->addWarning($error, $stackPtr, 'NoReturnType');
		}
		if ($foundReturn && $returnTypePtr && $returnType['content'] === 'void') {
			$error = 'Void return type when returning non-void';
			$phpcsFile->addWarning($error, $stackPtr, 'IncorrectVoidReturnType');
		}
	}

	private function getNextNonWhitespace(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextNonWhitespacePtr = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true, null, true);
		return $nextNonWhitespacePtr ? $tokens[$nextNonWhitespacePtr] : null;
	}

	private function isReturnValueVoid(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		if ($tokens[$stackPtr]['code'] !== T_RETURN) {
			return false;
		}
		$returnValue = $this->getNextNonWhitespace($phpcsFile, $stackPtr);
		return ! ($returnValue && $returnValue['content'] && $returnValue['code'] !== 'PHPCS_T_SEMICOLON');
	}
}
