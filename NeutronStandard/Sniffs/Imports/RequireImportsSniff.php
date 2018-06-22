<?php

namespace NeutronStandard\Sniffs\Imports;

use NeutronStandard\Symbol;
use NeutronStandard\ImportedSymbol;
use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireImportsSniff implements Sniff {
	public $ignoreUnimportedSymbols = null;

	private $importedFunctions = [];
	private $importedConsts = [];
	private $importedClasses = [];
	private $importedSymbolRecords = [];
	private $seenSymbols = [];

	public function register() {
		return [T_USE, T_STRING, T_RETURN_TYPE, T_WHITESPACE];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];
		if ($token['type'] === 'T_WHITESPACE') {
			return $this->processEndOfFile($phpcsFile, $stackPtr);
		}
		if ($token['type'] === 'T_USE') {
			return $this->processUse($phpcsFile, $stackPtr);
		}
		$symbol = $helper->getFullSymbol($phpcsFile, $stackPtr);
		// If the symbol has been seen before (if this is a duplicate), ignore it
		if (in_array($symbol, $this->seenSymbols)) {
			return;
		}
		$this->seenSymbols[] = $symbol;
		// If the symbol is in the ignore list, ignore it
		if ($this->isSymbolIgnored($symbol)) {
			$this->markSymbolUsed($symbol);
			return;
		}
		// If the symbol is a fully-qualified namespace, ignore it
		if ($symbol->isAbsoluteNamespace()) {
			return;
		}
		// If this symbol is a definition, ignore it
		if ($helper->isSymbolADefinition($phpcsFile, $symbol)) {
			return;
		}
		// If this symbol is a static reference or an object reference, ignore it
		if ($helper->isStaticReference($phpcsFile, $stackPtr) || $helper->isObjectReference($phpcsFile, $stackPtr)) {
			return;
		}
		// If this symbol is a namespace definition, ignore it
		if ($helper->isWithinNamespaceStatement($phpcsFile, $symbol->getSymbolPosition())) {
			return;
		}
		// If this symbol is an import, ignore it
		if ($helper->isWithinUseStatement($phpcsFile, $symbol->getSymbolPosition())) {
			return;
		}
		// If the symbol is predefined, ignore it
		if ($helper->isPredefinedConstant($phpcsFile, $stackPtr) || $helper->isBuiltInFunction($phpcsFile, $stackPtr)) {
			return;
		}
		// If this symbol is a predefined typehint, ignore it
		if ($helper->isPredefinedTypehint($phpcsFile, $stackPtr)) {
			return;
		}
		// If the symbol's namespace is imported or defined, ignore it
		// If the symbol has no namespace and is itself is imported or defined, ignore it
		if ($this->isSymbolDefined($phpcsFile, $symbol)) {
			$this->markSymbolUsed($symbol);
			return;
		}
		$error = "Found unimported symbol '{$symbol->getName()}'.";
		$phpcsFile->addWarning($error, $stackPtr, 'Symbol');
	}

	private function isSymbolIgnored(Symbol $symbol): bool {
		$symbolName = $symbol->getName();
		$pattern = $this->getIgnoredSymbolPattern();
		if (empty($pattern)) {
			return false;
		}
		try {
			return (1 === preg_match($pattern, $symbolName));
		} catch (\Exception $err) {
			throw new \Exception("ignoreUnimportedSymbols contains an invalid pattern: '{$pattern}'");
		}
	}

	private function getIgnoredSymbolPattern() {
		return $this->ignoreUnimportedSymbols ?? '';
	}

	private function isSymbolDefined(File $phpcsFile, Symbol $symbol): bool {
		$namespace = $symbol->getTopLevelNamespace();
		// If the symbol's namespace is imported or defined, ignore it
		if ($namespace) {
			return $this->isNamespaceImportedOrDefined($phpcsFile, $namespace);
		}
		// If the symbol has no namespace and is itself is imported or defined, ignore it
		return $this->isNamespaceImportedOrDefined($phpcsFile, $symbol->getName());
	}

	private function isNamespaceImportedOrDefined(File $phpcsFile, string $namespace): bool {
		return (
			$this->isClassImported($namespace)
			|| $this->isClassDefined($phpcsFile, $namespace)
			|| $this->isFunctionImported($namespace)
			|| $this->isFunctionDefined($phpcsFile, $namespace)
			|| $this->isConstImported($namespace)
			|| $this->isConstDefined($phpcsFile, $namespace)
		);
	}

	private function processUse(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$importType = $helper->getImportType($phpcsFile, $stackPtr);
		switch ($importType) {
			case 'function':
				return $this->saveFunctionImport($phpcsFile, $stackPtr);
			case 'const':
				return $this->saveConstImport($phpcsFile, $stackPtr);
			case 'class':
				return $this->saveClassImport($phpcsFile, $stackPtr);
		}
	}

	private function recordImportedSymbols(int $stackPtr, array $importNames) {
		foreach ($importNames as $symbol) {
			$this->importedSymbolRecords[] = new ImportedSymbol($stackPtr, $symbol);
		}
	}

	private function saveFunctionImport(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$importNames = $helper->getImportNames($phpcsFile, $stackPtr);
		$this->recordImportedSymbols($stackPtr, $importNames);
		$this->importedFunctions = array_merge($this->importedFunctions, $importNames);
	}

	private function saveConstImport(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$importNames = $helper->getImportNames($phpcsFile, $stackPtr);
		$this->recordImportedSymbols($stackPtr, $importNames);
		$this->importedConsts = array_merge($this->importedConsts, $importNames);
	}

	private function saveClassImport(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$importNames = $helper->getImportNames($phpcsFile, $stackPtr);
		$this->recordImportedSymbols($stackPtr, $importNames);
		$this->importedClasses = array_merge($this->importedClasses, $importNames);
	}

	private function isFunctionImported(string $functionName): bool {
		return in_array($functionName, $this->importedFunctions);
	}

	private function isConstImported(string $constName): bool {
		return in_array($constName, $this->importedConsts);
	}

	private function isClassImported(string $name): bool {
		return in_array($name, $this->importedClasses);
	}

	private function isClassDefined(File $phpcsFile, string $className): bool {
		$classPtr = $phpcsFile->findNext([T_CLASS], 0);
		while ($classPtr) {
			$thisClassName = $phpcsFile->getDeclarationName($classPtr);
			if ($className === $thisClassName) {
				return true;
			}
			$classPtr = $phpcsFile->findNext([T_CLASS], $classPtr + 1);
		}
		return false;
	}

	private function isFunctionDefined(File $phpcsFile, string $functionName): bool {
		$helper = new SniffHelpers();
		$functionPtr = $phpcsFile->findNext([T_FUNCTION], 0);
		while ($functionPtr) {
			$thisFunctionName = $phpcsFile->getDeclarationName($functionPtr);
			if ($functionName === $thisFunctionName && ! $helper->isFunctionAMethod($phpcsFile, $functionPtr)) {
				return true;
			}
			$functionPtr = $phpcsFile->findNext([T_FUNCTION], $functionPtr + 1);
		}
		return false;
	}

	private function isConstDefined(File $phpcsFile, string $functionName): bool {
		$helper = new SniffHelpers();
		$functionPtr = $phpcsFile->findNext([T_CONST], 0);
		while ($functionPtr) {
			$thisFunctionName = $helper->getConstantName($phpcsFile, $functionPtr);
			if ($functionName === $thisFunctionName) {
				return true;
			}
			$functionPtr = $phpcsFile->findNext([T_CONST], $functionPtr + 1);
		}
		return false;
	}

	private function markSymbolUsed(Symbol $symbol) {
		$record = $this->getSymbolRecord($symbol);
		if (! $record) {
			return;
		}
		$record->markUsed();
	}

	private function getSymbolRecord(Symbol $symbol) {
		foreach ($this->importedSymbolRecords as $record) {
			if ($record->getName() === $symbol->getTopLevelNamespace()) {
				return $record;
			}
		}
		return null;
	}

	private function processEndOfFile(File $phpcsFile, int $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		// If this is not the end of the file, ignore it
		if (isset($tokens[$stackPtr + 1])) {
			return;
		}
		// For each import, if the Symbol was not used, mark a warning
		foreach ($this->importedSymbolRecords as $record) {
			if (! $record->isUsed()) {
				$error = "Found unused symbol '{$record->getName()}'.";
				$phpcsFile->addWarning($error, $record->getPtr(), 'Import');
			}
		}
	}
}
