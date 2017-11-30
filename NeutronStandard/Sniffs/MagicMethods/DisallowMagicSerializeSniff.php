<?php

namespace NeutronStandard\Sniffs\MagicMethods;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowMagicSerializeSniff implements Sniff {
	public function register() {
		return [T_FUNCTION];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$functionName = $phpcsFile->getDeclarationName($stackPtr);
		if ($functionName === '__serialize') {
			$error = 'Magic serialize is not allowed';
			$phpcsFile->addError($error, $stackPtr, 'MagicSerialize');
		}
	}
}
