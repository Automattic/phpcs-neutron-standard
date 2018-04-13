<?php

namespace NeutronStandard\Sniffs\Imports;

use NeutronStandard\SniffHelpers;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class RequireImportsSniff implements Sniff {
	private $importedFunctions = [];
	private $importedConsts = [];
	private $importedClasses = [];

	public function register() {
		return [T_USE, T_STRING];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];
		if ($token['type'] === 'T_USE') {
			return $this->processUse($phpcsFile, $stackPtr);
		}
		if ($helper->isStaticFunctionCall($phpcsFile, $stackPtr)) {
			if ($this->hasNamespace($phpcsFile, $stackPtr)) {
				return $this->markAbsoluteViolation($phpcsFile, $stackPtr);
			}
			return $this->processStaticFunctionCall($phpcsFile, $stackPtr);
		}
		if ($helper->isFunctionCall($phpcsFile, $stackPtr) &&
			! $helper->isMethodCall($phpcsFile, $stackPtr) &&
			! $helper->isBuiltInFunction($phpcsFile, $stackPtr) &&
			! $helper->isObjectInstantiation($phpcsFile, $stackPtr)
		) {
			if ($this->hasNamespace($phpcsFile, $stackPtr)) {
				return $this->markAbsoluteViolation($phpcsFile, $stackPtr);
			}
			return $this->processFunctionCall($phpcsFile, $stackPtr);
		}
		if ($helper->isConstant($phpcsFile, $stackPtr) &&
			! $helper->isConstantDefinition($phpcsFile, $stackPtr) &&
			! $helper->isPredefinedConstant($phpcsFile, $stackPtr)
		) {
			if ($this->hasNamespace($phpcsFile, $stackPtr)) {
				return $this->markAbsoluteViolation($phpcsFile, $stackPtr);
			}
			$this->processConstant($phpcsFile, $stackPtr);
		}
		if ($helper->isClass($phpcsFile, $stackPtr) &&
			! $helper->isPredefinedClass($phpcsFile, $stackPtr) &&
			! $helper->isPredefinedConstant($phpcsFile, $stackPtr)
		) {
			if ($this->hasNamespace($phpcsFile, $stackPtr)) {
				return $this->markAbsoluteViolation($phpcsFile, $stackPtr);
			}
			$this->processClass($phpcsFile, $stackPtr);
		}
	}

	private function markAbsoluteViolation(File $phpcsFile, int $stackPtr) {
		$error = "Absolute symbols are not allowed; Import the symbol instead.";
		$phpcsFile->addWarning($error, $stackPtr, 'Absolute');
	}

	private function processClass($phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];
		$className = $token['content'];
		if (! $this->isClassImported($className) && ! $this->isClassDefined($phpcsFile, $className)) {
			$error = "Classes must be explicitly imported. Found '{$className}'.";
			$phpcsFile->addWarning($error, $stackPtr, 'Class');
		}
	}

	private function processConstant($phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];
		$constantName = $token['content'];
		if (! $this->isConstImported($constantName) && ! $this->isConstDefined($phpcsFile, $constantName)) {
			$error = "Constants must be explicitly imported. Found '{$constantName}'.";
			$phpcsFile->addWarning($error, $stackPtr, 'Constant');
		}
	}

	private function hasNamespace(File $phpcsFile, int $stackPtr): bool {
		$startOfStatementPtr = $phpcsFile->findPrevious([T_SEMICOLON], $stackPtr);
		return !! $phpcsFile->findPrevious([T_NS_SEPARATOR], $stackPtr, $startOfStatementPtr);
	}

	private function processStaticFunctionCall(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$className = $helper->getStaticPropertyClass($phpcsFile, $stackPtr);
		if (! $this->isClassImported($className)) {
			$error = "Classes must be explicitly imported. Found '{$className}'.";
			$phpcsFile->addWarning($error, $stackPtr, 'Class');
		}
	}

	private function processFunctionCall(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$token = $tokens[$stackPtr];
		$functionName = $token['content'];
		if (! $this->isFunctionImported($functionName) && ! $this->isFunctionDefined($phpcsFile, $functionName)) {
			$error = "Functions must be explicitly imported. Found '{$functionName}'.";
			$phpcsFile->addWarning($error, $stackPtr, 'Function');
		}
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

	private function saveFunctionImport(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$importNames = $helper->getImportNames($phpcsFile, $stackPtr);
		$this->importedFunctions = array_merge($this->importedFunctions, $importNames);
	}

	private function saveConstImport(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$importNames = $helper->getImportNames($phpcsFile, $stackPtr);
		$this->importedConsts = array_merge($this->importedConsts, $importNames);
	}

	private function saveClassImport(File $phpcsFile, $stackPtr) {
		$helper = new SniffHelpers();
		$importNames = $helper->getImportNames($phpcsFile, $stackPtr);
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
		$functionPtr = $phpcsFile->findNext([T_FUNCTION], 0);
		while ($functionPtr) {
			$thisFunctionName = $phpcsFile->getDeclarationName($functionPtr);
			if ($functionName === $thisFunctionName) {
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
}
