<?php

namespace ClassicBlocks\Block\Custom;

use ClassicBlocks\Block\AbstractBlock;
use Context;

final class ClassicSlides extends AbstractBlock
{
	/**
	 * @return array
	 */
	public static function getContent(): array
	{
		$translator = Context::getContext()->getTranslator();

		return [
			'name' => $translator->trans('Classic slide'),
			'description' => $translator->trans('Display slides where you want'),
			'code' => 'classic_slides',
			'tab' => 'general',
			'icon' => 'RectangleStackIcon',
			'need_reload' => true,
			'templates' => [
				'default' => 'module:classicblocks/views/templates/blocks/slides.tpl'
			],
			'config' => [
				'fields' => [
					'speed' => [
						'type' => 'text',
						'label' => 'Slide speed',
						'default' => '5000'
					],
					'pause' => [
						'type' => 'checkbox',
						'label' => 'Pause on hover',
						'default' => true,
					],
					'wrap' => [
						'type' => 'checkbox',
						'label' => 'Repeat images',
						'default' => true,
					],
				],
			],

			'repeater' => [
				'name' => 'Slides',
				'nameFrom' => 'title',
				'groups' => [
					'title' => [
						'type' => 'text',
						'label' => 'Titre à modifier',
						'default' => 'default value',
					],
					'description' => [
						'type' => 'editor',
						'label' => 'HTML content',
						'default' => '<h3>EXCEPTEUR OCCAECAT</h3><br>
                                    <p>Lorem ipsum dolor sit amet, 
                                    consectetur adipiscing elit. Proin tristique in tortor et dignissim. 
                                    Quisque non tempor leo. Maecenas egestas sem elit</p>',
					],
					'url' => [
						'type' => 'text',
						'label' => 'Url',
						'default' => '#',
					],
					'color' => [
						'type' => 'color',
						'label' => 'Background color of slide',
						'default' => '#ffffff',
					],
					'upload' => [
						'type' => 'fileupload',
						'label' => 'Images',
						'path' => '$/modules/classicblocks/views/images/',
						'default' => [
							'url' => 'https://via.placeholder.com/1110x340',
						],
					]
				]
			]
		];
	}
}

