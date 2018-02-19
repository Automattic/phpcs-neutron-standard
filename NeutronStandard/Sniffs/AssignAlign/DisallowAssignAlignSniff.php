<?php

namespace NeutronStandard\Sniffs\AssignAlign;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowAssignAlignSniff implements Sniff {
	public function register() {
		return [];
	}

	public function process(File $phpcsFile, $stackPtr) {
	}
}
