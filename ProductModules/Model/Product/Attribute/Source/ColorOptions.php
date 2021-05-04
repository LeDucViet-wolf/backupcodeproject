<?php

namespace Gssi\ProductModules\Model\Product\Attribute\Source;

class ColorOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const SINGLE_COLOR_VALUE = 1;
    const DOU_COLOR_VALUE = 2;
    const TRI_COLOR_VALUE = 3;

    public function getAllOptions()
    {
        return [
            ['value' => self::SINGLE_COLOR_VALUE, 'label' => __('Singer Color')],
            ['value' => self::DOU_COLOR_VALUE, 'label' => __('Dou Color')],
            ['value' => self::TRI_COLOR_VALUE, 'label' => __('Tri Color')],
        ];
    }
}
