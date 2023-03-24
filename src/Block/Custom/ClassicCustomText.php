<?php

namespace ClassicBlocks\Block\Custom;

use ClassicBlocks\Block\AbstractBlock;
use Context;

final class ClassicCustomText extends AbstractBlock
{
	/**
	 * @return array
	 */
	public static function getContent(): array
	{
		$translator = Context::getContext()->getTranslator();

		return [
			'name' => $translator->trans('Custom text'),
			'description' => $translator->trans('Display banners where you want'),
			'code' => 'classic_custom_text',
			'tab' => 'general',
			'icon' => 'DocumentTextIcon',
			'need_reload' => true,
			'templates' => [
				'default' => 'module:classicblocks/views/templates/blocks/ps_customtext.tpl'
			],
			'config' => [
				'fields' => [
					'text' => [
						'type' => 'editor',
						'label' => 'Text HTML',
						'default' => '<p>Hello world</p>'
					]
				],
			],
		];
	}
}

