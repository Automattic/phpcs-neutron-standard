<?php

namespace NeutronStandard\Sniffs\Functions;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DisallowCallUserFuncSniff implements Sniff {
	public function register() {
		return [T_STRING];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$functionName = $tokens[$stackPtr]['content'];
		$helper = new SniffHelpers();
		$disallowedFunctions = [
			'call_user_func',
			'call_user_func_array',
		];
		if (in_array($functionName, $disallowedFunctions) && $helper->isFunctionCall($phpcsFile, $stackPtr)) {
			$error = 'call_user_func and call_user_func_array are not allowed';
			$phpcsFile->addError($error, $stackPtr, 'CallUserFunc');
		}
	}
}
