<?php

namespace ClassicBlocks\Block\Custom;

use ClassicBlocks\Block\AbstractBlock;
use Category;
use Context;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
use Configuration;
use ProductAssembler;
use ProductPresenterFactory;

final class ClassicFeaturedProduct extends AbstractBlock
{
	/**
	 * @return array
	 */
	public static function getContent(): array
	{
		$translator = Context::getContext()->getTranslator();

		return [
			'name' => $translator->trans('Featured products blocks'),
			'description' => $translator->trans('Render featured block'),
			'code' => 'classic_featured_product',
			'tab' => 'general',
			'icon' => 'GiftIcon',
			'need_reload' => true,
			'templates' => [
				'default' => 'module:classicblocks/views/templates/blocks/featured_home.tpl'
			],
			'config' => [
				'fields' => [
					'category' => [
						'type' => 'selector',
						'label' => 'Category',
						'collection' => 'Category',
						'default' =>  \HelperBuilder::getRandomCategory(Context::getContext()->language->id, Context::getContext()->shop->id),
						'selector' => '{id} - {name}'
					],
					'title' => [
						'type' => 'text',
						'default' => 'Our products',
						'label' => 'Title to display'
					],
					'display_link' => [
						'type' => 'checkbox',
						'default' => true,
						'label' => 'Display links to category'
					]
				],
			],
		];
	}

	public static function beforeRendering(array $params = null): array
	{
		$settings = $params['block']['settings'];

		if ($settings) {
			if (isset($settings['category']['id'])) {
				$id_category = (int)$settings['category']['id'];
				return ['products' => self::getProducts($id_category)];
			}
		}
		return ['products' => false];
	}



	protected static function getProducts($id_category)
	{
		$context = Context::getContext();
		$category = new Category((int) $id_category);

		$searchProvider = new CategoryProductSearchProvider(
			$context->getTranslator(),
			$category
		);

		$searchContext = new ProductSearchContext($context);

		$query = new ProductSearchQuery();

		$nProducts = ((int)Configuration::get('HOME_FEATURED_NBR')) ? (int)Configuration::get('HOME_FEATURED_NBR') : 6;

		$query
			->setResultsPerPage($nProducts)
			->setPage(1);


		$query->setSortOrder(new SortOrder('product', 'position', 'asc'));


		$result = $searchProvider->runQuery(
			$searchContext,
			$query
		);

		$assembler            = new ProductAssembler($context);
		$presenterFactory     = new ProductPresenterFactory($context);
		$presentationSettings = $presenterFactory->getPresentationSettings();
		$presenter            = $presenterFactory->getPresenter();

		$products_for_template = [];

		foreach ($result->getProducts() as $rawProduct) {
			$products_for_template[] = $presenter->present(
				$presentationSettings,
				$assembler->assembleProduct($rawProduct),
				$context->language
			);
		}

		return $products_for_template;
	}
}

