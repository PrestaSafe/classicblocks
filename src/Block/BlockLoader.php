<?php

namespace ClassicBlocks\Block;

use ClassicBlocks\Exception\BlockMethodNotExistsException;
use ClassicBlocks\Exception\BlockNotFoundException;
use Exception;
use PrestaShopLogger;

final class BlockLoader
{
    /**
     * @return array
     * @throws BlockMethodNotExistsException
     * @throws BlockNotFoundException
     */
	public static function getBlocks(): array
	{
		$blockToReturn = [];
        $dir           = __DIR__ . '/Custom';

        if (!is_dir($dir)) {
            throw new BlockNotFoundException('Impossible de charger les blocks');
        }

        $blocks = array_diff(scandir(__DIR__ . '/Custom'), ['.', '..']);
		foreach ($blocks as $block) {
			$blockNamespace = "ClassicBlocks\Block\Custom\\";
			$blockNamespace .= str_replace('.php', '', $block);

			if (!method_exists($blockNamespace, 'getContent')) {
				throw new BlockMethodNotExistsException('La méthode getContent n\'existe pas dans le block ' . $block);
			}

			$blockToReturn[] = call_user_func([$blockNamespace, 'getContent']);
		}

		return $blockToReturn;
	}

    /**
     * @param string $blockName
     * @param array|null $params
     * @return array
     */
	public static function getBlockBeforeRendering(string $blockName, array $params = null): array
	{
		$blockToReturn = [];

		$blockNamespace = "ClassicBlocks\Block\Custom\\";
		$blockNamespace .= str_replace('.php', '', str_replace('_', '', $blockName));

		if (!method_exists($blockNamespace, 'beforeRendering')) {
			return $blockToReturn;
		}

		return call_user_func([$blockNamespace, 'beforeRendering'], $params);
	}
}
