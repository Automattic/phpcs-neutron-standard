<?php

namespace NeutronStandard\Sniffs\Functions;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class LongFunctionSniff implements Sniff {
	public function register() {
		return [T_FUNCTION];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextBracketPtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr + 1);
		$startOfFunctionPtr = $tokens[$nextBracketPtr]['bracket_opener'];
		$endOfFunctionPtr = $tokens[$nextBracketPtr]['bracket_closer'];
		$newlineCount = 0;
		$commentTokens = [
			T_DOC_COMMENT_OPEN_TAG,
			T_DOC_COMMENT_CLOSE_TAG,
			T_DOC_COMMENT_STRING,
			T_COMMENT,
			T_DOC_COMMENT_STAR,
			T_DOC_COMMENT_WHITESPACE
		];
		$newlineContainingTokens = [
			T_WHITESPACE,
			T_COMMENT,
		];
		$helper = new SniffHelpers();
		$currentLinePtr = $phpcsFile->findNext(T_WHITESPACE, $startOfFunctionPtr, $endOfFunctionPtr, false, "\n") + 2;
		$foundNonComment = false;
		for ($index = $currentLinePtr; $index < $endOfFunctionPtr; $index++) {
			$token = $tokens[$index];
			if (! in_array($token['code'], $commentTokens)) {
				if ($token['code'] !== T_WHITESPACE || $token['content'] !== "\n") {
					$foundNonComment = true;
				}
			}
			if (in_array($token['code'], $newlineContainingTokens) &&
				$helper->doesStringEndWith($token['content'], "\n")) {
				if ($foundNonComment) {
					$newlineCount ++;
				}
				$foundNonComment = false;
			}
		}
		// $functionName = $phpcsFile->getDeclarationName($stackPtr);
		// echo "\nFunction $functionName is $newlineCount lines long.\n";
		if ($newlineCount > 20) {
			$error = 'Function is longer than 20 lines';
			$phpcsFile->addWarning($error, $stackPtr, 'LongFunction');
		}
	}
}
