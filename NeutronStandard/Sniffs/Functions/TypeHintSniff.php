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
		$helper = new SniffHelpers();
		$this->checkForMissingArgumentHints($phpcsFile, $stackPtr, $helper);
		$this->checkForMissingReturnHints($phpcsFile, $stackPtr, $helper);
	}

	private function checkForMissingArgumentHints(File $phpcsFile, $stackPtr, SniffHelpers $helper) {
		$tokens = $phpcsFile->getTokens();
		$openParenPtr = $tokens[$stackPtr]['parenthesis_opener'];
		$closeParenPtr = $tokens[$stackPtr]['parenthesis_closer'];
		$hintTypes = [
			T_STRING,
			T_CALLABLE,
			T_SELF,
		];
		// Support for phpcs < 3.3; see https://github.com/Automattic/phpcs-neutron-standard/issues/62
		if (defined('T_ARRAY_HINT')) {
			$hintTypes[] = T_ARRAY_HINT;
		}

		for ($ptr = ($openParenPtr + 1); $ptr < $closeParenPtr; $ptr++) {
			if ($tokens[$ptr]['code'] === T_VARIABLE) {
				$tokenBeforePtr = $helper->getArgumentTypePtr($phpcsFile, $ptr);
				$tokenBefore = $tokens[$tokenBeforePtr];
				if (!$tokenBeforePtr || !in_array($tokenBefore['code'], $hintTypes, true)) {
					$error = 'Argument type is missing';
					$phpcsFile->addWarning($error, $stackPtr, 'NoArgumentType');
				}
			}
		}
	}

	private function checkForMissingReturnHints(File $phpcsFile, $stackPtr, SniffHelpers $helper) {
		$tokens = $phpcsFile->getTokens();
		if ($helper->isFunctionJustSignature($phpcsFile, $stackPtr)) {
			return;
		}
		$endOfFunctionPtr = $helper->getEndOfFunctionPtr($phpcsFile, $stackPtr);
		$startOfFunctionPtr = $helper->getStartOfFunctionPtr($phpcsFile, $stackPtr);
		$returnTypePtr = $helper->getNextReturnTypePtr($phpcsFile, $stackPtr);
		$returnType = $tokens[$returnTypePtr];

		$colonPtr = $phpcsFile->findNext(T_COLON, $stackPtr, $startOfFunctionPtr);
		if ($colonPtr) {
			if ($tokens[$colonPtr - 1]['type'] !== 'T_CLOSE_PARENTHESIS') {
				$phpcsFile->addError(
					'Return type colon should be right after closing function parenthesis',
					$colonPtr,
					'ExtraSpace'
				);
			}
			if ($tokens[$colonPtr + 1]['type'] !== 'T_WHITESPACE') {
				$phpcsFile->addError('Missing space before return type', $colonPtr, 'MissingSpace');
			}
			if ($tokens[$returnTypePtr+1]['type'] !== 'T_WHITESPACE') {
				$phpcsFile->addError('Missing space after return type', $colonPtr, 'MissingSpace');
			}
		}

		$nonVoidReturnCount = 0;
		$voidReturnCount = 0;
		$scopeClosers = [];
		for ($ptr = $startOfFunctionPtr; $ptr < $endOfFunctionPtr; $ptr++) {
			$token = $tokens[$ptr];
			if (!empty($scopeClosers) && $ptr === $scopeClosers[0]) {
				array_shift($scopeClosers);
			}
			if ($token['code'] === T_CLOSURE) {
				array_unshift($scopeClosers, $token['scope_closer']);
			}
			if (empty($scopeClosers) && in_array($token['code'], [T_RETURN, T_YIELD], true)) {
				$helper->isReturnValueVoid($phpcsFile, $ptr) ? $voidReturnCount++ : $nonVoidReturnCount++;
			}
		}

		$hasNonVoidReturnType = $returnTypePtr && $returnType['content'] !== 'void';
		$hasVoidReturnType = $returnTypePtr && $returnType['content'] === 'void';
		$hasNoReturnType = ! $returnTypePtr;

		if ($hasNonVoidReturnType
			&& ($nonVoidReturnCount === 0 || $voidReturnCount > 0)
		) {
			$errorMessage = $voidReturnCount > 0
				? 'Return type with void return'
				: 'Return type with no return';

			$errorType = $voidReturnCount > 0
				? 'IncorrectVoidReturn'
				: 'UnusedReturnType';

			$phpcsFile->addError($errorMessage, $stackPtr, $errorType);
		}
		if ($hasNoReturnType && $nonVoidReturnCount > 0) {
			$error = 'Return type is missing';
			$phpcsFile->addWarning($error, $stackPtr, 'NoReturnType');
		}
		if ($hasVoidReturnType && $nonVoidReturnCount > 0) {
			$error = 'Void return type when returning non-void';
			$phpcsFile->addError($error, $stackPtr, 'IncorrectVoidReturnType');
		}
	}
}
