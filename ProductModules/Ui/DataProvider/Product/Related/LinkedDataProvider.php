<?php

namespace Gssi\ProductModules\Ui\DataProvider\Product\Related;

use Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider;

class LinkedDataProvider extends AbstractDataProvider
{
    protected function getLinkType()
    {
        return 'product_module';
    }
}
