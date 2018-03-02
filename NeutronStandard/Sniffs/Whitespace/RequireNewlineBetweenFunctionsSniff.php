<?php

namespace NeutronStandard\Sniffs\Whitespace;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireNewlineBetweenFunctionsSniff implements Sniff {
	public function register() {
		return [T_WHITESPACE];
	}

	public function process(File $phpcsFile, $stackPtr) {
	}

	private function fixTokens(File $phpcsFile, $stackPtr) {
	}
}
