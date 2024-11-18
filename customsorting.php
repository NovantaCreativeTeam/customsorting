<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

use Novanta\Customsorting\Install\InstallerFactory;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

$autoloadPath = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

class CustomSorting extends Module
{
    public function __construct()
    {
        $this->name = 'customsorting';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'novanta';
        $this->need_instance = 0;

        /*
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Custom Sorting', [], 'Modules.Customsorting.Admin');
        $this->description = $this->trans('Adding feature to customize product sorting', [], 'Modules.Customsorting.Admin');

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall module', [], 'Modules.Customsorting.Admin');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => '8.0'];
    }

    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    public function install(): bool
    {
        $installer = InstallerFactory::create();

        return parent::install()
            && $installer->install($this);
    }

    public function uninstall(): bool
    {
        $installer = InstallerFactory::create();

        return parent::uninstall()
            && $installer->uninstall($this);
    }

    public function hookActionProductPreferencesPagePaginationForm($params): void
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];

        $formBuilder->remove('default_order_by');
        $formBuilder->add('default_order_by', ChoiceType::class, [
            'choices' => [
                'Product name' => 0,
                'Product price' => 1,
                'Product add date' => 2,
                'Product modified date' => 3,
                'Position inside category' => 4,
                'Brand' => 5,
                'Product quantity' => 6,
                'Product reference' => 7,
                $this->trans('Sales', [], 'Modules.Customsorting.Admin') => 8,
            ],
            'required' => true,
        ]);
    }
}
