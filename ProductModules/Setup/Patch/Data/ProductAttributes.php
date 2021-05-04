<?php

namespace Gssi\ProductModules\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class ProductAttributes implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var \Gssi\ProductModules\Helper\AddOption
     */
    private $addOption;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        \Gssi\ProductModules\Helper\AddOption $addOption
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->addOption = $addOption;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'modules_product_enable',
            [
                'label' => 'Modules Product Enable',
                'type' => 'int',
                'input' => 'boolean',
                'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => false,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'apply_to' => 'configurable',
                'visible_in_advanced_search' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY,'module');
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'module',
            [
                'label' => 'Module',
                'type' => 'int',
                'input' => 'select',
                'source' => \Gssi\ProductModules\Model\Product\Attribute\Source\ModuleOptions::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true  ,
                'searchable' => false,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'apply_to' => '',
                'visible_in_advanced_search' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'color_count',
            [
                'label' => 'Color',
                'type' => 'int',
                'input' => 'select',
                'source' => \Gssi\ProductModules\Model\Product\Attribute\Source\ColorOptions::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'searchable' => false,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'apply_to' => '',
                'visible_in_advanced_search' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'single_color',
            [
                'label' => 'Single Color',
                'type' => 'varchar',
                'input' => 'multiselect',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => false,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'apply_to' => 'configurable',
                'visible_in_advanced_search' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'dual_color',
            [
                'label' => 'Dual Color',
                'type' => 'varchar',
                'input' => 'multiselect',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'apply_to' => 'configurable',
                'visible_in_advanced_search' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'tri_color',
            [
                'label' => 'Tri Color',
                'type' => 'varchar',
                'input' => 'multiselect',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'searchable' => true,
                'filterable' => true,
                'comparable' => true,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'apply_to' => 'configurable',
                'visible_in_advanced_search' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );
        $this->addOption->createOrGetId('single_color', 'Amber');
        $this->addOption->createOrGetId('single_color', 'Red');
        $this->addOption->createOrGetId('single_color', 'Blue');
        $this->addOption->createOrGetId('single_color', 'White');
        $this->addOption->createOrGetId('dual_color', 'Red - Amber');
        $this->addOption->createOrGetId('dual_color', 'Red - Blue');
        $this->addOption->createOrGetId('dual_color', 'Red - White');
        $this->addOption->createOrGetId('dual_color', 'Blue - Amber');
        $this->addOption->createOrGetId('dual_color', 'Blue - White');
        $this->addOption->createOrGetId('tri_color', 'Red - Blue - White');
        $this->addOption->createOrGetId('tri_color', 'Red - Blue - Amber');
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
