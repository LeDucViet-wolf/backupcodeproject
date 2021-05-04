<?php

namespace Gssi\ProductModules\Plugin\Magento\ConfigurableProduct\Block\Product\View\Type;

use Magento\Catalog\Model\Product;

class Configurable
{
    /**
     * @var \Gssi\ProductModules\Helper\Data
     */
    private $dataHelper;


    public function __construct(
        \Gssi\ProductModules\Helper\Data $dataHelper
    )
    {
        $this->dataHelper = $dataHelper;
    }

    public function afterGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        $result
    )
    {
        $jsonResult = json_decode($result, true);
        $jsonResult['skus'] = [];
        $jsonResult['names'] = [];
        $jsonResult['mounting_products'] = [];
        $jsonResult['mounting_products_by_module'] = [];
        foreach ($subject->getAllowProducts() as $simpleProduct) {
            $mountingProducts = $this->dataHelper->getProductModuleProductCollectionByProduct($simpleProduct);
            /* @var Product $mountingProduct */
            if ($mountingProducts) {
                foreach ($mountingProducts as $mountingProduct) {
                    $jsonResult['mounting_products'][$simpleProduct->getId()][$mountingProduct->getId()] = $mountingProduct->getName()
                        . " + " . $this->dataHelper->formatPrice($mountingProduct->getFinalPrice());
                    $jsonResult['mounting_products_by_module'][$simpleProduct->getModule()][$mountingProduct->getId()] = $mountingProduct->getName()
                        . " + " . $this->dataHelper->formatPrice($mountingProduct->getFinalPrice());
                }
            }
        }
        if ($moduleAttributeId = $this->dataHelper->getAttributeId($subject->getProduct(),'module')) {
            sort($jsonResult['attributes'][$moduleAttributeId]['options']);
        }
        return json_encode($jsonResult);
    }
}
