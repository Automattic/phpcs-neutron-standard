<?php

namespace NeutronStandard\Sniffs\StrictTypes;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireStrictTypesSniff implements Sniff {
	public function register() {
		return [T_OPEN_TAG];
	}

	public function process(File $phpcsFile, $stackPtr) {
		if (! $this->hasInitialDeclare($phpcsFile, $stackPtr) || ! $this->isInitialDeclareStrictTypes($phpcsFile, $stackPtr) || ! $this->isInitialDeclareStrictTypesOn($phpcsFile, $stackPtr)) {
			$this->addStrictTypeError($phpcsFile, $stackPtr);
		}
	}

	private function hasInitialDeclare($phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$declarePtr = $phpcsFile->findNext(T_DECLARE, $stackPtr, null, false, null, true);
		return ($declarePtr && isset($tokens[$declarePtr]));
	}

	private function isInitialDeclareStrictTypes($phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$declarePtr = $phpcsFile->findNext(T_DECLARE, $stackPtr, null, false, null, true);
		$declareStringPtr = $phpcsFile->findNext(T_STRING, $declarePtr, null, false, null, true);
		$declareStringToken = $tokens[$declareStringPtr];
		return ($declareStringToken && $declareStringToken['content'] === 'strict_types');
	}

	private function isInitialDeclareStrictTypesOn($phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$declarePtr = $phpcsFile->findNext(T_DECLARE, $stackPtr, null, false, null, true);
		$declareNumPtr = $phpcsFile->findNext(T_LNUMBER, ($declarePtr + 1), null, false, null, true);
		$declareNumToken = $tokens[$declareNumPtr];
		return ($declareNumToken && $declareNumToken['content'] === '1');
	}

	private function addStrictTypeError(File $phpcsFile, $stackPtr) {
		$error = 'File must start with a strict types declaration';
		$phpcsFile->addError($error, $stackPtr, 'StrictTypes');
	}
}

