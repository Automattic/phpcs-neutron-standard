<?php
declare(strict_types=1);

namespace NeutronStandard;

class Symbol {
	private $tokens;

	public function __construct(array $tokens) {
		if (empty($tokens)) {
			throw new \Exception('Symbols cannot be empty');
		}
		$this->tokens = $tokens;
	}

	public static function getTokenWithPosition(array $token, int $stackPtr): array {
		$token['tokenPosition'] = $stackPtr;
		return $token;
	}

	public function getTokens(): array {
		return $this->tokens;
	}

	public function getName(): string {
		return $this->joinSymbolParts($this->tokens);
	}

	public function isAbsoluteNamespace(): bool {
		$type = $this->tokens[0]['type'] ?? '';
		return $type === 'T_NS_SEPARATOR';
	}

	/**
	 * @return string|null
	 */
	public function getTopLevelNamespace() {
		return $this->tokens[0]['content'] ?? null;
	}

	public function getSymbolPosition(): int {
		return $this->tokens[0]['tokenPosition'] ?? 1;
	}

	private function joinSymbolParts(array $tokens): string {
		$symbolStrings = array_map(function (array $token): string {
			return $token['content'] ?? '';
		}, $tokens);
		return implode('', $symbolStrings);
	}
}
