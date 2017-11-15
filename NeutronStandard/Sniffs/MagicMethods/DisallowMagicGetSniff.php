<?php

namespace NeutronStandard\Sniffs\MagicMethods;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowMagicGetSniff implements Sniff {
	public function register() {
		return [T_FUNCTION];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$functionName = $phpcsFile->getDeclarationName($stackPtr);
		if ($functionName === '__get') {
			$error = 'Magic getters are not allowed';
			$phpcsFile->addError($error, $stackPtr, 'MagicGet');
		}
	}
}
