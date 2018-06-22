<?php
declare(strict_types=1);

namespace NeutronStandard;

class ImportedSymbol {
	private $ptr;
	private $symbolName;
	private $used;

	public function __construct(int $stackPtr, string $symbolName) {
		$this->ptr = $stackPtr;
		$this->symbolName = $symbolName;
		$this->used = false;
	}

	public function markUsed() {
		$this->used = true;
	}

	public function isUsed(): bool {
		return $this->used;
	}

	public function getName(): string {
		return $this->symbolName;
	}

	public function getPtr(): int {
		return $this->ptr;
	}
}
