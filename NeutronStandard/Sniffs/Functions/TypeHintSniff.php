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
			T_ARRAY_HINT,
			T_CALLABLE,
			T_SELF,
		];

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

		$nonVoidReturn = 0;
		$voidReturn = 0;
		$scopeClosers = [];
		for ($ptr = $startOfFunctionPtr; $ptr < $endOfFunctionPtr; $ptr++) {
			$token = $tokens[$ptr];
			if (!empty($scopeClosers) && $ptr === $scopeClosers[0]) {
				array_shift($scopeClosers);
			}
			if ($token['code'] === T_CLOSURE) {
				array_unshift($scopeClosers, $token['scope_closer']);
			}
			if (empty($scopeClosers) && $token['code'] === T_RETURN) {
				$helper->isReturnValueVoid($phpcsFile, $ptr) ? $voidReturn++ : $nonVoidReturn++;
			}
		}

		if ($returnTypePtr && $returnType['content'] !== 'void') {
			if (!$nonVoidReturn) {
				$error = 'Return type with no return';
				$phpcsFile->addError($error, $stackPtr, 'UnusedReturnType');

				return;
			}
			if ($voidReturn) {
				$error = 'Return type with void return';
				$phpcsFile->addError($error, $stackPtr, 'IncorrectVoidReturn');
			}
		}
		if ($nonVoidReturn && !$returnTypePtr) {
			$error = 'Return type is missing';
			$phpcsFile->addWarning($error, $stackPtr, 'NoReturnType');
		}
		if ($nonVoidReturn && $returnTypePtr && $returnType['content'] === 'void') {
			$error = 'Void return type when returning non-void';
			$phpcsFile->addError($error, $stackPtr, 'IncorrectVoidReturnType');
		}
	}
}
