<?php

namespace NeutronStandard\MagicMethods;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowMagicGetSniff implements Sniff {
	public function register() {
		return [T_FUNCTION];
	}

	public function process( File $phpcsFile, $stackPtr ) {
		$tokens = $phpcsFile->getTokens();
		if (! isset($tokens[$stackPtr+2])) {
			return;
		}
		$functionNameToken = $tokens[$stackPtr+2];
		$functionName = $functionNameToken['content'];
		if ($functionName === '__get') {
			$error = 'Magic getters are not allowed';
			$phpcsFile->addError($error, $stackPtr, 'MagicGet');
		}
	}
}
