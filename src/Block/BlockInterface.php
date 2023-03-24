<?php

namespace ClassicBlocks\Block;

interface BlockInterface
{
    public static function getContent(): array;

	public static function beforeRendering(array $params = null): array;
}
