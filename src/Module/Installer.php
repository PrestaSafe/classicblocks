<?php

namespace ClassicBlocks\Module;

use ClassicBlocks;

class Installer
{
	/**
	 * @var ClassicBlocks
	 * $module, Instance of \ClassicBlocks
	 */
	protected $module;

	/**
	 * Installer constructor.
	 * @param ClassicBlocks $module
	 */
	public function __construct(ClassicBlocks $module)
	{
		$this->module = $module;
	}

	/**
	 * Main function Installer
	 *
	 * @return bool
	 */
	public function run()
	{
		return $this->installHooks();
	}

	/**
	 * Install Prestashop hooks
	 *
	 * @return bool
	 */
	public function installHooks(): bool
	{
		return
			// $this->module->registerHook('ActionRegisterThemeSettings') &&
			$this->module->registerHook('ActionRegisterBlock') &&
			$this->module->registerHook('displayHeader') &&
			// $this->module->registerHook('ActionQueueSassCompile') &&
			$this->module->registerHook('beforeRenderingClassicFeaturedProduct')
		;
	}
}
