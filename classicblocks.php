<?php


if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;
class ClassicBlocks extends Module
{
    public function __construct()
    {
        $this->name = 'classicblocks';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'PrestaSafe';

        parent::__construct();

        $this->displayName = $this->l('Classic blocks');
        $this->description = $this->l('All default blocks for Classic theme');
        
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
        // $this->registerHook('ActionRegisterThemeSettings') &&
        $this->registerHook('ActionRegisterBlock') &&
        // $this->registerHook('ActionQueueSassCompile') &&
        $this->registerHook('beforeRenderingClassicFeaturedProduct');
    }

    public function uninstall()
    {
        return parent::uninstall() &&
        // $this->unregisterHook('ActionRegisterThemeSettings') &&
        $this->unregisterHook('ActionRegisterBlock') &&
        // $this->unregisterHook('ActionQueueSassCompile') &&
        $this->unregisterHook('beforeRenderingClassicFeaturedProduct');
    }


    public function hookActionRegisterThemeSettings()
    {
        // register settings
        return [];
    }

    public function hookActionRegisterBlock()
    {
        $blocks = [];
        
        // render module
        $blocks[] =  [
            'name' => $this->l('Featured products blocks'),
            'description' => $this->l('Render featured block'),
            'code' => 'classic_featured_product',
            'tab' => 'general',
            'icon' => 'GiftIcon',
            'need_reload' => true,
            'templates' => [
                'default' => 'module:'.$this->name.'/views/templates/blocks/featured_home.tpl'
            ],
            'config' => [
                'fields' => [
                    'category' => [
                        'type' => 'selector',
                        'label' => 'Category',
                        'collection' => 'Category',
                        'default' => 'default value',
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


        return $blocks;
    }

    public function hookbeforeRenderingClassicFeaturedProduct($params)
    {
        // die('test');
        $settings = $params['block']['settings'];

        if($settings)
        {
            if(isset($settings['category']['id']))
            {
                $id_category = (int)$settings['category']['id'];
                return ['products' => $this->getProducts($id_category)];
            }
        }
        return ['products' => false];

    }
    
    protected function getProducts($id_category)
    {
        $this->context = Context::getContext();
        $category = new Category((int) $id_category);

        $searchProvider = new CategoryProductSearchProvider(
            $this->context->getTranslator(),
            $category
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $nProducts = ((int)Configuration::get('CZ_CATEGORYHOME_PRODUCT_LIMIT')) ? (int)Configuration::get('CZ_CATEGORYHOME_PRODUCT_LIMIT') : 6;

        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1);


        $query->setSortOrder(new SortOrder('product', 'position', 'asc'));


        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = $presenterFactory->getPresenter();

        $products_for_template = [];

        foreach ($result->getProducts() as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $products_for_template;
    }

    // public function hookActionQueueSassCompile()
    // {
    //     $vars = [
    //         'import_path' => [
    //             '$/themes/cartzilla/_dev/css/'
    //         ],
    //         'entries' => [
    //             '$/modules/'.$this->name.'/views/css/vars.scss'
    //         ],
    //         'out' => '$/themes/cartzilla/_dev/css/helpers/_custom_vars.scss'
    //     ];

    //     $theme = [
    //         'import_path' => [
    //             '$/themes/cartzilla/_dev/css/'
    //         ],
    //         'entries' => [
    //             '$/themes/cartzilla/_dev/css/theme.scss'
    //         ],
    //         'out' => '$/themes/cartzilla/assets/css/theme.css'
    //     ];


    //     return [$vars, $theme];
    // }


}