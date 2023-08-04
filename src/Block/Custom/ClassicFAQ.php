<?php

namespace ClassicBlocks\Block\Custom;

use ClassicBlocks\Block\AbstractBlock;
use Context;

final class ClassicFAQ extends AbstractBlock
{
	/**
	 * @return array
	 */
	public static function getContent(): array
	{
		$translator = Context::getContext()->getTranslator();

		return [
			'name' => $translator->trans('FAQ'),
			'description' => $translator->trans('Display FAQ where you want'),
			'code' => 'classic_faq',
			'tab' => 'general',
			'icon' => 'PhotoIcon',
			'need_reload' => false,
			'insert_default_values' => true,
			'templates' => [
				'default' => 'module:classicblocks/views/templates/blocks/faq.tpl'
			],
			'config' => [
				'fields' => [
					'title' => [
						'type' => 'title',
						'label' => 'Title image',
						'force_default_value' => true,
						'default' => [
                            'tag' => 'h2',
                            'value' => "FAQ",
                            'focus' => false,
							'inside' => true,
                            'bold' => false,
                            'italic' => false,
                            'underline' => false,
                            'size' => 18,
                        ],
					],
				],
			],
            'repeater' => [
				'name' => 'title',
				'nameFrom' => 'title',
				'groups' => [
					'title' => [
						'type' => 'text',
						'label' => 'Titre à modifier',
						'default' => 'default value',
					],
					'hello' => [
						'type' => 'title',
						'label' => 'Title Hello',
						'force_default_value' => true,
						'default' => [
                            'tag' => 'h2',
                            'value' => "FAQ",
                            'focus' => false,
							'inside' => true,
                            'bold' => false,
                            'italic' => false,
                            'underline' => false,
                            'size' => 18,
                        ],
					],
                    'content' => [
                        'type' => 'editor',
                        'label' => 'HTML content',
                        'default' => 'Votre contenu'
                    ]
			
				]
			]
		];
	}
}

