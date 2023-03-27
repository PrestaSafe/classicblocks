<?php

namespace ClassicBlocks\Block\Custom;

use ClassicBlocks\Block\AbstractBlock;
use Context;

final class ClassicBanner extends AbstractBlock
{
	/**
	 * @return array
	 */
	public static function getContent(): array
	{
		$translator = Context::getContext()->getTranslator();

		return [
			'name' => $translator->trans('Banner'),
			'description' => $translator->trans('Display banners where you want'),
			'code' => 'classic_banner',
			'tab' => 'general',
			'icon' => 'PhotoIcon',
			'need_reload' => true,
			'templates' => [
				'default' => 'module:classicblocks/views/templates/blocks/ps_banner.tpl'
			],
			'config' => [
				'fields' => [
					'title' => [
						'type' => 'text',
						'label' => 'Title image',
						'default' => 'Home banner'
					],
					'desc' => [
						'type' => 'editor',
						'label' => 'Add a small description',
						'default' => 'Lorem ispum...',
					],
					'url' => [
						'type' => 'text',
						'label' => 'URL',
						'default' => '#'
					],
					'banner' => [
						'type' => 'fileupload',
						'label' => 'Images',
						'path' => '$/modules/classicblocks/views/images/',
						'default' => [
							'url' => 'https://via.placeholder.com/1100x213',
						],
					]
				],
			],
		];
	}
}

