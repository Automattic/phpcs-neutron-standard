<?php

namespace NeutronStandard\Sniffs\StrictTypes;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireStrictTypesSniff implements Sniff {
	public function register() {
		return [T_OPEN_TAG];
	}

	public function process(File $phpcsFile, $stackPtr) {
		if (! $this->isFirstOpenTag($phpcsFile, $stackPtr)) {
			return;
		}
		$declarePtr = $this->getNextDeclarePtr($phpcsFile, $stackPtr);
		if (! $declarePtr
			|| ! $this->isDeclareStrictTypes($phpcsFile, $declarePtr)
			|| ! $this->isDeclareTurnedOn($phpcsFile, $declarePtr)
		) {
			$this->addStrictTypeError($phpcsFile, $stackPtr);
		}
	}

	private function isFirstOpenTag(File $phpcsFile, $stackPtr): bool {
		$previousOpenTagPtr = $phpcsFile->findPrevious(T_OPEN_TAG, $stackPtr);
		return ! $previousOpenTagPtr;
	}

	private function isDeclareStrictTypes(File $phpcsFile, $declarePtr): bool {
		$tokens = $phpcsFile->getTokens();
		$declareStringPtr = $phpcsFile->findNext(T_STRING, $declarePtr, null, false, null, true);
		$declareStringToken = $tokens[$declareStringPtr];
		return ($declareStringToken && $declareStringToken['content'] === 'strict_types');
	}

	private function isDeclareTurnedOn(File $phpcsFile, $declarePtr): bool {
		$tokens = $phpcsFile->getTokens();
		$declareNumPtr = $phpcsFile->findNext(T_LNUMBER, ($declarePtr + 1), null, false, null, true);
		$declareNumToken = $tokens[$declareNumPtr];
		return ($declareNumToken && $declareNumToken['content'] === '1');
	}

	private function addStrictTypeError(File $phpcsFile, $stackPtr) {
		$error = 'File must start with a strict types declaration';
		$phpcsFile->addError($error, $stackPtr, 'StrictTypes');
	}

	private function getNextDeclarePtr(File $phpcsFile, $stackPtr) {
		return $phpcsFile->findNext(T_DECLARE, $stackPtr, null, false, null, true);
	}
}
