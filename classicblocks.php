<?php

use ClassicBlocks\Block\BlockLoader;
use ClassicBlocks\Module\Hook as ClassicBlocksHook;
use ClassicBlocks\Module\Installer;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class ClassicBlocks extends Module
{
    public function __construct()
    {
        $this->name         = 'classicblocks';
        $this->tab          = 'administration';
        $this->version      = '1.0.3';
        $this->author       = 'PrestaSafe';
        $this->dependencies = ['prettyblocks'];

        parent::__construct();

        $this->displayName = $this->trans('Classic blocks');
        $this->description = $this->trans('All default blocks for Classic theme');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

	/**
	 * Magic method PHP.
	 *
	 * @param string $name
	 * @param array $arguments
	 */
    public function __call(string $name, array $arguments)
    {
        try {
            // Déporter les hooks dans une classe custom (sauf le hookBeforeRendering)
            if (method_exists(ClassicBlocksHook::class, $name) && !strstr($name, 'hookBeforeRendering')) {
                if (substr($name, 0, 4) === 'hook') {
                    return ClassicBlocksHook::execute($name, $this, $arguments[0] ?? null);
                }
            } else {

                // HookBeforeRendering
                return BlockLoader::getBlockBeforeRendering(
                    str_replace('hookBeforeRendering', '', $name),
                    $arguments[0] ?? null
                );
            }
        } catch (Exception $e) {
            PrestaShopLogger::addLog($e->getMessage());
        }
    }

	/**
	 * @return bool
	 */
	public function install(): bool
	{
		return
            parent::install() &&
            (new Installer($this))->run()
        ;
	}

    /**
     * @return bool
     */
    public function uninstall(): bool
    {
        return parent::uninstall() &&
        // $this->unregisterHook('ActionRegisterThemeSettings') &&
        $this->unregisterHook('ActionRegisterBlock') &&
        $this->unregisterHook('displayHeader') &&
        // $this->unregisterHook('ActionQueueSassCompile') &&
        $this->unregisterHook('beforeRenderingClassicFeaturedProduct');
    }
}
