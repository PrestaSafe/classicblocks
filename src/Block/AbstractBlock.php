<?php

namespace ClassicBlocks\Block;

use Exception;

 class AbstractBlock implements BlockInterface
{
	public static function getContent(): array {
		return [];
	}

	public static function beforeRendering(array $params = null): array {
		return [];
	}
}
