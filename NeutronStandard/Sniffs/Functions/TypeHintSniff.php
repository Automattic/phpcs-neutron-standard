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
		$this->helper = new SniffHelpers();
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

		for ($ptr = ($openParenPtr + 1); $ptr < $closeParenPtr; $ptr++) {
			if ($tokens[$ptr]['code'] === T_VARIABLE) {
				$tokenBeforePtr = $this->helper->getArgumentTypePtr($phpcsFile, $ptr);
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
		$endOfFunctionPtr = $this->helper->getEndOfFunctionPtr($phpcsFile, $stackPtr);
		$startOfFunctionPtr = $this->helper->getStartOfFunctionPtr($phpcsFile, $stackPtr);
		$returnTypePtr = $this->helper->getNextReturnTypePtr($phpcsFile, $stackPtr);
		$returnType = $tokens[$returnTypePtr];

		$foundReturn = false;
		$scopeClosers = [];
		for ($ptr = $startOfFunctionPtr; $ptr < $endOfFunctionPtr; $ptr++) {
			$token = $tokens[$ptr];
			if (! empty($scopeClosers) && $ptr === $scopeClosers[0]) {
				array_shift($scopeClosers);
			}
			if ($token['code'] === T_CLOSURE) {
				array_unshift($scopeClosers, $token['scope_closer']);
			}
			if (empty($scopeClosers) && $token['code'] === T_RETURN
				&& ! $this->helper->isReturnValueVoid($phpcsFile, $ptr)) {
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
}
