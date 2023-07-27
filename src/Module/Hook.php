<?php

namespace ClassicBlocks\Module;

use ClassicBlocks;
use ClassicBlocks\Block\BlockLoader;
use Context;
use Exception;
use PrestaShopLogger;

class Hook
{
    private string $hook_name;
    private Context $context;
    private array $params;
    private ClassicBlocks $module;
    private static $instance = null;


    private function __construct()
    {
    }

    /**
     * @param string $hook_name
     * @param ClassicBlocks $module
     * @param array $params
     * @return mixed
     */
    public static function execute(string $hook_name, ClassicBlocks $module, array $params)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        self::$instance->hook_name = $hook_name;
        self::$instance->module = $module;
        self::$instance->params = $params;
        self::$instance->context = Context::getContext();
        return self::$instance->$hook_name($params);
    }


    /**
     * @param $params
     * @return void
     */
    public function hookdisplayHeader($params): void
    {
        $this->context->controller->registerStylesheet(
            'modules-homeslider',
            'modules/' . $this->module->name . '/css/homeslider.css',
            ['media' => 'all', 'priority' => 150]
        );
        $this->context->controller->registerStylesheet(
            'modules-custom',
            'modules/' . $this->module->name . '/views/css/custom.css',
            ['media' => 'all', 'priority' => 150]
        );
        $this->context->controller->registerJavascript(
            'modules-responsiveslides',
            'modules/' . $this->module->name . '/js/responsiveslides.min.js',
            ['position' => 'bottom', 'priority' => 150]
        );
        $this->context->controller->registerJavascript(
            'modules-homeslider',
            'modules/' . $this->module->name . '/js/homeslider.js',
            ['position' => 'bottom', 'priority' => 150]
        );
    }

    public function hookActionQueueSassCompile()
    {
        $vars = [
            'entries' => [
                '$/modules/' . $this->module->name . '/views/css/custom.scss'
            ],
            'out' =>  '$/modules/' . $this->module->name . '/views/css/custom.css'
        ];
        return [$vars];
    }


    /**
     * @return array
     */
    public function hookActionRegisterThemeSettings(): array
    {
        // register settings
        return [
            'wrapper_bg' => [
                'tab' => 'wrapper',
                'type' => 'color',
                'default' => '#f6f6f6',
                'label' => 'Change wrapper color'
            ],
            'text_color' => [
                'tab' => 'wrapper',
                'type' => 'color',
                'default' => '#232323',
                'label' => 'Change text color'
            ],
        ];
    }

    /**
     * @param $params
     * @return array
     */
    public function hookActionRegisterBlock($params): array
    {
        try {
            return BlockLoader::getBlocks();
        } catch (Exception $e) {
            PrestaShopLogger::addLog($e->getMessage());
        }
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
