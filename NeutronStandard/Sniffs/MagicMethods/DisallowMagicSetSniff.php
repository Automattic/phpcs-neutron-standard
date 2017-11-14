<?php

namespace NeutronStandard\Sniffs\MagicMethods;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowMagicSetSniff implements Sniff {
	public function register() {
		return [T_FUNCTION];
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		$functionName = $phpcsFile->getDeclarationName($stackPtr);
		if ($functionName === '__set') {
			$error = 'Magic setters are not allowed';
			$phpcsFile->addError($error, $stackPtr, 'MagicSet');
		}
	}
}

