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
        $this->registerHook('displayHeader') &&
        // $this->registerHook('ActionQueueSassCompile') &&
        $this->registerHook('beforeRenderingClassicFeaturedProduct');
    }

    public function uninstall()
    {
        return parent::uninstall() &&
        // $this->unregisterHook('ActionRegisterThemeSettings') &&
        $this->unregisterHook('ActionRegisterBlock') &&
        $this->unregisterHook('displayHeader') &&
        // $this->unregisterHook('ActionQueueSassCompile') &&
        $this->unregisterHook('beforeRenderingClassicFeaturedProduct');
    }

    public function hookdisplayHeader($params)
    {
        $this->context->controller->registerStylesheet('modules-homeslider', 'modules/' . $this->name . '/css/homeslider.css', ['media' => 'all', 'priority' => 150]);
        $this->context->controller->registerJavascript('modules-responsiveslides', 'modules/' . $this->name . '/js/responsiveslides.min.js', ['position' => 'bottom', 'priority' => 150]);
        $this->context->controller->registerJavascript('modules-homeslider', 'modules/' . $this->name . '/js/homeslider.js', ['position' => 'bottom', 'priority' => 150]);
    }

    public function hookActionRegisterThemeSettings()
    {
        // register settings
        return [];
    }

    public function hookActionRegisterBlock()
    {
        $blocks = [];
        
        // featured products
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

        // slider
        $blocks[] =  [
            'name' => $this->l('Classic slide'),
            'description' => $this->l('Display slides where you want'),
            'code' => 'classic_slides',
            'tab' => 'general',
            'icon' => 'RectangleStackIcon',
            'need_reload' => true,
            'templates' => [
                'default' => 'module:'.$this->name.'/views/templates/blocks/slides.tpl'
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
                        'path' => '$/modules/'.$this->name.'/views/images/',
                        'default' => [
                            'url' => 'https://via.placeholder.com/1110x340',
                        ],
                    ]
                ]
            ]
           

        ];

        // ps_banner

        $blocks[] =  [
            'name' => $this->l('Banner'),
            'description' => $this->l('Display banners where you want'),
            'code' => 'classic_banner',
            'tab' => 'general',
            'icon' => 'PhotoIcon',
            'need_reload' => true,
            'templates' => [
                'default' => 'module:'.$this->name.'/views/templates/blocks/ps_banner.tpl'
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
                        'path' => '$/modules/'.$this->name.'/views/images/',
                        'default' => [
                            'url' => 'https://via.placeholder.com/1100x213',
                        ],
                    ]
                ],
            ],
        ];

        // custom text
        $blocks[] =  [
            'name' => $this->l('Custom text'),
            'description' => $this->l('Display banners where you want'),
            'code' => 'classic_custom_text',
            'tab' => 'general',
            'icon' => 'DocumentTextIcon',
            'need_reload' => true,
            'templates' => [
                'default' => 'module:'.$this->name.'/views/templates/blocks/ps_customtext.tpl'
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