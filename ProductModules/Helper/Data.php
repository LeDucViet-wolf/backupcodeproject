<?php


namespace Gssi\ProductModules\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Gssi\ProductModules\Model\ProductFactory
     */
    private $productFactory;
    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    private $priceHelper;

    public function __construct(
        Context $context,
        \Gssi\ProductModules\Model\ProductFactory $productFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelper
    )
    {
        parent::__construct($context);
        $this->productFactory = $productFactory;
        $this->priceHelper = $priceHelper;
    }

    public function getProductModuleProductCollectionByProduct($product)
    {
        $productFactory = $this->productFactory->create();
        $currentProduct = $productFactory->load($product->getId());
        return $currentProduct->getProductModuleProducts();
    }

    public function getAttributeId($product, $attributeCode) {
        return $product->getAttributes()[$attributeCode]->getAttributeId();
    }

    public function getColor($product, $attributeCode) {
        foreach (explode(',',$product->getData($attributeCode)) as $option) {
            $colorOptions[] = [
                'value' => $option,
                'label' => $this->getOptionText($product, $option, $attributeCode)
            ];
        }
        return json_encode($colorOptions);
    }

    public function getOptionText($product, $value, $attributeCode) {
        return $product->getResource()->getAttribute($attributeCode)->getSource()->getOptionText($value);
    }

    public function formatPrice($value){
        return $this->priceHelper->currency($value, true, false);
    }
}
