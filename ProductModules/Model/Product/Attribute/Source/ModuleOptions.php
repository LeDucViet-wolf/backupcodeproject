<?php

namespace Gssi\ProductModules\Model\Product\Attribute\Source;

class ModuleOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const SINGLE_MODULE_VALUE = 1;
    const TWO_MODULE_VALUE = 2;
    const THREE_MODULE_VALUE = 3;
    const FOUR_MODULE_VALUE = 4;
    const FIVE_MODULE_VALUE = 5;
    const SIX_MODULE_VALUE = 6;

    public function getAllOptions()
    {
        return [
            ['value' => self::SINGLE_MODULE_VALUE, 'label' => __('Singer Module')],
            ['value' => self::TWO_MODULE_VALUE, 'label' => __('Two Module')],
            ['value' => self::THREE_MODULE_VALUE, 'label' => __('Three Module')],
            ['value' => self::FOUR_MODULE_VALUE, 'label' => __('Four Module')],
            ['value' => self::FIVE_MODULE_VALUE, 'label' => __('Five Module')],
            ['value' => self::SIX_MODULE_VALUE, 'label' => __('Six Module')],
        ];
    }
}
