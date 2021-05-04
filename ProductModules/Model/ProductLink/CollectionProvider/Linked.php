<?php

namespace Gssi\ProductModules\Model\ProductLink\CollectionProvider;

class Linked
{
    public function getLinkedProducts($product)
    {
        return $product->getProductModuleProducts();
    }
}
