<?php
declare(strict_types=1);

namespace NeutronStandard;

use NeutronStandard\Symbol;
use PHP_CodeSniffer\Files\File;

class SniffHelpers {
	public function isFunctionCall(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextNonWhitespacePtr = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true, null, false);
		// if the next non-whitespace token is not a paren, then this is not a function call
		if ($tokens[$nextNonWhitespacePtr]['type'] !== 'T_OPEN_PARENTHESIS') {
			return false;
		}
		// if the previous non-whitespace token is a function, then this is not a function call
		$prevNonWhitespacePtr = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true, null, false);
		if ($tokens[$prevNonWhitespacePtr]['type'] === 'T_FUNCTION') {
			return false;
		}
		return true;
	}

	public function isObjectReference(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$prevPtr = $phpcsFile->findPrevious([T_OBJECT_OPERATOR], $stackPtr - 1, $stackPtr - 2);
		return ($prevPtr && isset($tokens[$prevPtr]));
	}

	public function isStaticReference(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$prevPtr = $phpcsFile->findPrevious([T_DOUBLE_COLON], $stackPtr - 1, $stackPtr - 2);
		return ($prevPtr && isset($tokens[$prevPtr]));
	}

	public function isMethodCall(File $phpcsFile, $stackPtr) {
		if (! $this->isFunctionCall($phpcsFile, $stackPtr)) {
			return false;
		}
		$tokens = $phpcsFile->getTokens();
		$prevPtr = $phpcsFile->findPrevious([T_OBJECT_OPERATOR], $stackPtr - 1, $stackPtr - 2);
		if ($prevPtr && isset($tokens[$prevPtr])) {
			return true;
		}
		return false;
	}

	public function isPropertyAccess(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$prevPtr = $phpcsFile->findPrevious([T_OBJECT_OPERATOR], $stackPtr - 1, $stackPtr - 2);
		if ($prevPtr && isset($tokens[$prevPtr])) {
			return true;
		}
		return false;
	}

	public function isObjectInstantiation(File $phpcsFile, $stackPtr) {
		if (! $this->isFunctionCall($phpcsFile, $stackPtr)) {
			return false;
		}
		$tokens = $phpcsFile->getTokens();
		$prevPtr = $phpcsFile->findPrevious([T_NEW], $stackPtr - 1, $stackPtr - 2);
		if ($prevPtr && isset($tokens[$prevPtr])) {
			return true;
		}
		return false;
	}

	// Borrowed this idea from https://pear.php.net/reference/PHP_CodeSniffer-3.1.1/apidoc/PHP_CodeSniffer/LowercasePHPFunctionsSniff.html
	public function isBuiltInFunction(File $phpcsFile, $stackPtr) {
		$allFunctions = get_defined_functions();
		$builtInFunctions = array_flip($allFunctions['internal']);
		$tokens = $phpcsFile->getTokens();
		$functionName = $tokens[$stackPtr]['content'];
		return isset($builtInFunctions[strtolower($functionName)]);
	}

	public function isPredefinedTypehint(File $phpcsFile, $stackPtr) {
		$allTypehints = [
			'bool',
			'string',
			'int',
			'float',
			'void',
			'self',
			'array',
			'callable',
			'iterable',
		];
		$tokens = $phpcsFile->getTokens();
		$tokenContent = $tokens[$stackPtr]['content'];
		return in_array($tokenContent, $allTypehints, true);
	}

	public function isPredefinedConstant(File $phpcsFile, $stackPtr) {
		$allConstants = get_defined_constants();
		$tokens = $phpcsFile->getTokens();
		$constantName = $tokens[$stackPtr]['content'];
		return isset($allConstants[$constantName]);
	}

	public function isPredefinedClass(File $phpcsFile, $stackPtr) {
		$allClasses = get_declared_classes();
		$tokens = $phpcsFile->getTokens();
		$className = $tokens[$stackPtr]['content'];
		return in_array($className, $allClasses);
	}

	// From https://stackoverflow.com/questions/619610/whats-the-most-efficient-test-of-whether-a-php-string-ends-with-another-string
	public function doesStringEndWith(string $string, string $test): bool {
		$strlen = strlen($string);
		$testlen = strlen($test);
		if ($testlen > $strlen) {
			return false;
		}
		return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
	}

	public function getNextNonWhitespace(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextNonWhitespacePtr = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, true, null, true);
		return $nextNonWhitespacePtr ? $tokens[$nextNonWhitespacePtr] : null;
	}

	public function getNextNewlinePtr(File $phpcsFile, $stackPtr) {
		return $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, false, "\n");
	}

	public function getArgumentTypePtr(File $phpcsFile, $stackPtr) {
		$ignoredTypes = [
			T_WHITESPACE,
			T_ELLIPSIS,
		];
		$openParenPtr = $phpcsFile->findPrevious(T_OPEN_PARENTHESIS, $stackPtr - 1, null, false);
		if (! $openParenPtr) {
			return false;
		}
		return $phpcsFile->findPrevious($ignoredTypes, $stackPtr - 1, $openParenPtr, true);
	}

	public function isReturnValueVoid(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		if ($tokens[$stackPtr]['code'] !== T_RETURN) {
			return false;
		}
		$returnValue = $this->getNextNonWhitespace($phpcsFile, $stackPtr);
		return ! $returnValue || $returnValue['code'] === 'PHPCS_T_SEMICOLON';
	}

	public function getNextReturnTypePtr(File $phpcsFile, $stackPtr) {
		$startOfFunctionPtr = $this->getStartOfFunctionPtr($phpcsFile, $stackPtr);
		$colonPtr = $phpcsFile->findNext(T_COLON, $stackPtr, $startOfFunctionPtr);
		if (! $colonPtr) {
			return false;
		}
		return $phpcsFile->findNext(T_WHITESPACE, $colonPtr + 1, null, true, null, true);
	}

	public function getNextSemicolonPtr(File $phpcsFile, $stackPtr) {
		return $phpcsFile->findNext(T_SEMICOLON, $stackPtr + 1);
	}

	public function getEndOfFunctionPtr(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		if ($this->isFunctionJustSignature($phpcsFile, $stackPtr)) {
			return $this->getNextSemicolonPtr($phpcsFile, $stackPtr);
		}
		$openFunctionBracketPtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr + 1);
		return $openFunctionBracketPtr && isset($tokens[$openFunctionBracketPtr]['bracket_closer'])
			? $tokens[$openFunctionBracketPtr]['bracket_closer']
			: $this->getNextSemicolonPtr($phpcsFile, $stackPtr);
	}

	public function getStartOfFunctionPtr(File $phpcsFile, $stackPtr) {
		$openFunctionBracketPtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr + 1);
		$nextSemicolonPtr = $this->getNextSemicolonPtr($phpcsFile, $stackPtr);
		if ($openFunctionBracketPtr && $nextSemicolonPtr && $openFunctionBracketPtr > $nextSemicolonPtr) {
			return $nextSemicolonPtr;
		}
		return $openFunctionBracketPtr
			? $openFunctionBracketPtr + 1
			: $this->getEndOfFunctionPtr($phpcsFile, $stackPtr);
	}

	public function isFunctionJustSignature(File $phpcsFile, $stackPtr) {
		$openFunctionBracketPtr = $phpcsFile->findNext(T_OPEN_CURLY_BRACKET, $stackPtr + 1);
		$nextSemicolonPtr = $this->getNextSemicolonPtr($phpcsFile, $stackPtr);
		if ($openFunctionBracketPtr && $nextSemicolonPtr && $openFunctionBracketPtr > $nextSemicolonPtr) {
			return true;
		}
		return ! $openFunctionBracketPtr;
	}

	public function getImportType(File $phpcsFile, $stackPtr): string {
		$tokens = $phpcsFile->getTokens();
		$nextStringPtr = $phpcsFile->findNext([T_STRING], $stackPtr + 1);
		if (! $nextStringPtr) {
			return 'unknown';
		}
		$isClosureImport = $phpcsFile->findNext([T_OPEN_PARENTHESIS], $stackPtr + 1, $nextStringPtr);
		if ($isClosureImport) {
			return 'closure';
		}
		$nextString = $tokens[$nextStringPtr];
		if ($nextString['content'] === 'function') {
			return 'function';
		}
		if ($nextString['content'] === 'const') {
			return 'const';
		}
		return 'class';
	}

	private function getImportNamesFromGroup(File $phpcsFile, int $stackPtr): array {
		$tokens = $phpcsFile->getTokens();
		$endBracketPtr = $phpcsFile->findNext([T_CLOSE_USE_GROUP], $stackPtr + 1);
		if (! $endBracketPtr) {
			return [];
		}
		$lastImportPtr = $stackPtr;
		$collectedSymbols = [];
		$isLastImport = false;
		while (! $isLastImport) {
			$nextEndOfImportPtr = $phpcsFile->findNext([T_COMMA], $lastImportPtr + 1, $endBracketPtr);
			if (! $nextEndOfImportPtr) {
				$isLastImport = true;
				$nextEndOfImportPtr = $endBracketPtr;
			}
			$lastStringPtr = $phpcsFile->findPrevious([T_STRING], $nextEndOfImportPtr - 1, $stackPtr);
			if (! $lastStringPtr || ! isset($tokens[$lastStringPtr])) {
				break;
			}
			$collectedSymbols[] = $tokens[$lastStringPtr]['content'];
			$lastImportPtr = $nextEndOfImportPtr;
		}
		return $collectedSymbols;
	}

	public function getImportNames(File $phpcsFile, $stackPtr): array {
		$tokens = $phpcsFile->getTokens();

		$endOfStatementPtr = $phpcsFile->findNext([T_SEMICOLON], $stackPtr + 1);
		if (! $endOfStatementPtr) {
			return [];
		}

		// Process grouped imports differently
		$nextBracketPtr = $phpcsFile->findNext([T_OPEN_USE_GROUP], $stackPtr + 1, $endOfStatementPtr);
		if ($nextBracketPtr) {
			return $this->getImportNamesFromGroup($phpcsFile, $nextBracketPtr);
		}

		// Get the last string before the last semicolon, comma, or closing curly bracket
		$endOfImportPtr = $phpcsFile->findPrevious(
			[T_COMMA, T_CLOSE_USE_GROUP],
			$stackPtr + 1,
			$endOfStatementPtr
		);
		if (! $endOfImportPtr) {
			$endOfImportPtr = $endOfStatementPtr;
		}
		$lastStringPtr = $phpcsFile->findPrevious([T_STRING], $endOfImportPtr - 1, $stackPtr);
		if (! $lastStringPtr || ! isset($tokens[$lastStringPtr])) {
			return [];
		}
		return [$tokens[$lastStringPtr]['content']];
	}

	public function getAllStringsBefore(File $phpcsFile, int $startPtr, int $endPtr): array {
		$tokens = $phpcsFile->getTokens();
		$strings = [];
		$nextStringPtr = $phpcsFile->findNext([T_STRING], $startPtr);
		while ($nextStringPtr < $endPtr) {
			if (! $nextStringPtr || ! isset($tokens[$nextStringPtr])) {
				break;
			}
			$nextString = $tokens[$nextStringPtr];
			$strings[] = $nextString['content'];
			$nextStringPtr = $phpcsFile->findNext([T_STRING], $nextStringPtr + 1);
		}
		return $strings;
	}

	public function getPreviousStatementPtr(File $phpcsFile, int $stackPtr): int {
		return $phpcsFile->findPrevious([T_SEMICOLON, T_CLOSE_CURLY_BRACKET], $stackPtr - 1) ?: 1;
	}

	public function isWithinDeclareCall(File $phpcsFile, $stackPtr): bool {
		$previousStatementPtr = $this->getPreviousStatementPtr($phpcsFile, $stackPtr);
		return !! $phpcsFile->findPrevious([T_DECLARE], $stackPtr - 1, $previousStatementPtr);
	}

	public function isWithinDefineCall(File $phpcsFile, $stackPtr): bool {
		$previousStatementPtr = $this->getPreviousStatementPtr($phpcsFile, $stackPtr);
		return !! $phpcsFile->findPrevious([T_STRING], $stackPtr - 1, $previousStatementPtr, false, 'define');
	}

	public function isWithinNamespaceStatement(File $phpcsFile, $stackPtr): bool {
		$previousStatementPtr = $this->getPreviousStatementPtr($phpcsFile, $stackPtr);
		return !! $phpcsFile->findPrevious([T_NAMESPACE], $stackPtr - 1, $previousStatementPtr);
	}

	public function isWithinUseStatement(File $phpcsFile, $stackPtr): bool {
		$previousStatementPtr = $this->getPreviousStatementPtr($phpcsFile, $stackPtr);
		return !! $phpcsFile->findPrevious([T_USE], $stackPtr - 1, $previousStatementPtr);
	}

	public function isClass(File $phpcsFile, $stackPtr): bool {
		$nextSeparatorPtr = $phpcsFile->findNext([T_NS_SEPARATOR], $stackPtr + 1, $stackPtr + 2);
		if ($nextSeparatorPtr) {
			return false;
		}
		$previousStatementPtr = $phpcsFile->findPrevious([T_SEMICOLON, T_CLOSE_CURLY_BRACKET], $stackPtr - 1);
		if (! $previousStatementPtr) {
			$previousStatementPtr = 1;
		}
		$isUseOrNamespace = $phpcsFile->findPrevious([T_USE, T_NAMESPACE], $stackPtr - 1, $previousStatementPtr);
		if ($isUseOrNamespace) {
			return false;
		}
		if ($this->isConstant($phpcsFile, $stackPtr)) {
			return false;
		}
		if ($this->isMethodCall($phpcsFile, $stackPtr)) {
			return false;
		}
		if ($this->isPropertyAccess($phpcsFile, $stackPtr)) {
			return false;
		}
		if ($this->isBuiltInFunction($phpcsFile, $stackPtr)) {
			return false;
		}
		$prevUsePtr = $phpcsFile->findPrevious([T_USE, T_CONST, T_FUNCTION], $stackPtr - 1, $previousStatementPtr);
		if ($prevUsePtr) {
			return false;
		}
		return true;
	}

	public function isConstant(File $phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];
		$stringName = $token['content'];
		if (strtoupper($stringName) !== $stringName) {
			return false;
		}
		$nextSeparatorPtr = $phpcsFile->findNext([T_NS_SEPARATOR], $stackPtr + 1, $stackPtr + 2);
		if ($nextSeparatorPtr) {
			return false;
		}
		$previousStatementPtr = $phpcsFile->findPrevious([T_SEMICOLON, T_CLOSE_CURLY_BRACKET], $stackPtr - 1);
		if (! $previousStatementPtr) {
			$previousStatementPtr = 1;
		}
		$isUseOrNamespace = $phpcsFile->findPrevious([T_USE, T_NAMESPACE], $stackPtr - 1, $previousStatementPtr);
		if ($isUseOrNamespace) {
			return false;
		}
		$prevUsePtr = $phpcsFile->findPrevious([T_USE], $stackPtr - 1, $previousStatementPtr);
		if ($prevUsePtr) {
			return false;
		}
		return true;
	}

	/**
	 * @return array|null
	 */
	public function getPreviousNonWhitespaceToken(File $phpcsFile, int $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$prevNonWhitespacePtr = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, $stackPtr - 3, true, null, false);
		if (! $prevNonWhitespacePtr || ! isset($tokens[$prevNonWhitespacePtr])) {
			return null;
		}
		return $tokens[$prevNonWhitespacePtr];
	}

	public function isConstantDefinition(File $phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$prevNonWhitespacePtr = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, $stackPtr - 3, true, null, false);
		if (! $prevNonWhitespacePtr || ! isset($tokens[$prevNonWhitespacePtr])) {
			return false;
		}
		$prevToken = $tokens[$prevNonWhitespacePtr];
		if ($prevToken['content'] === 'const') {
			return true;
		}
		return false;
	}

	public function getConstantName(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$nextStringPtr = $phpcsFile->findNext([T_STRING], $stackPtr + 1, $stackPtr + 3);
		if (! $nextStringPtr || ! isset($tokens[$nextStringPtr])) {
			return null;
		}
		return $tokens[$nextStringPtr]['content'];
	}

	public function isStaticFunctionCall(File $phpcsFile, $stackPtr): bool {
		return (bool) $this->getStaticPropertyClass($phpcsFile, $stackPtr);
	}

	public function getStaticPropertyClass(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		if (isset($tokens[$stackPtr - 1]['type']) && $tokens[$stackPtr - 1]['type'] === 'T_DOUBLE_COLON' && isset($tokens[$stackPtr - 2]['content'])) {
			return $tokens[$stackPtr - 2]['content'];
		}
		return null;
	}

	public function isFunctionAMethod(File $phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$currentToken = $tokens[$stackPtr];
		return ! empty($currentToken['conditions']);
	}

	public function isSymbolADefinition(File $phpcsFile, Symbol $symbol): bool {
		// if the previous non-whitespace token is const, function, class, or trait, it is a definition
		// Note: this does not handle use statements, for that use isWithinUseStatement
		$stackPtr = $symbol->getSymbolPosition();
		$prevToken = $this->getPreviousNonWhitespaceToken($phpcsFile, $stackPtr) ?? [];
		return $this->isTokenADefinition($prevToken) || $this->isWithinDefineCall($phpcsFile, $stackPtr) || $this->isWithinDeclareCall($phpcsFile, $stackPtr);
	}

	public function isTokenADefinition(array $token): bool {
		// Note: this does not handle use or define
		$type = $token['type'] ?? '';
		$definitionTypes = ['T_CLASS', 'T_FUNCTION', 'T_CONST'];
		return in_array($type, $definitionTypes, true);
	}

	public function getFullSymbol($phpcsFile, $stackPtr): Symbol {
		$originalPtr = $stackPtr;
		$tokens = $phpcsFile->getTokens();
		// go backwards and forward and collect all the tokens until we encounter
		// anything other than a backslash or a string
		$currentToken = Symbol::getTokenWithPosition($tokens[$stackPtr], $stackPtr);
		$fullSymbolParts = [];
		while ($this->isTokenASymbolPart($currentToken)) {
			$fullSymbolParts[] = $currentToken;
			$stackPtr--;
			$currentToken = Symbol::getTokenWithPosition($tokens[$stackPtr] ?? [], $stackPtr);
		}
		$fullSymbolParts = array_reverse($fullSymbolParts);
		$stackPtr = $originalPtr + 1;
		$currentToken = Symbol::getTokenWithPosition($tokens[$stackPtr] ?? [], $stackPtr);
		while ($this->isTokenASymbolPart($currentToken)) {
			$fullSymbolParts[] = $currentToken;
			$stackPtr++;
			$currentToken = Symbol::getTokenWithPosition($tokens[$stackPtr] ?? [], $stackPtr);
		}
		return new Symbol($fullSymbolParts);
	}

	public function isTokenASymbolPart(array $token): bool {
		$type = $token['type'] ?? '';
		$symbolParts = ['T_NS_SEPARATOR', 'T_STRING', 'T_RETURN_TYPE'];
		return in_array($type, $symbolParts, true);
	}
}
