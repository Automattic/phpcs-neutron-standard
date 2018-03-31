<?php

namespace NeutronStandard\Sniffs\Imports;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireImportsSniff implements Sniff {
	public function register() {
		return [];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$error = 'Function imports must be explicitly imported';
		$phpcsFile->addError($error, $stackPtr, 'Function');
	}
}
