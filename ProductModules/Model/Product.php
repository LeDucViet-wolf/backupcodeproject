<?php


namespace Gssi\ProductModules\Model;

class Product extends \Magento\Catalog\Model\Product
{
    const LINK_TYPE_PRODUCT_LINKED = 17;

    public function getProductModuleProducts()
    {
        if (!$this->hasProductModuleProducts()) {

            $products = [];
            $collection = $this->getProductModuleProductCollection()->addAttributeToSelect('*');
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->setProductModuleProducts($products);
        }
        return $this->getData('product_module_products');
    }

    public function getProductModuleProductIds()
    {
        if (!$this->hasProductModuleProductIds()) {
            $ids = [];
            foreach ($this->getProductModuleProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setProductModuleProductIds($ids);
        }
        return [$this->getData('product_module_product_ids')];
    }

    public function getProductModuleProductCollection()
    {
        $collection = $this->getLinkInstance()->setLinkTypeId(static::LINK_TYPE_PRODUCT_LINKED)->getProductCollection()->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }

}
