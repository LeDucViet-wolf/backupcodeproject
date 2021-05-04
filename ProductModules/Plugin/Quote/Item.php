<?php


namespace Gssi\ProductModules\Plugin\Quote;

use Magento\Catalog\Model\Product;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;

class Item
{
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\App\RequestInterface $request,
        SerializerInterface $serializer
    )
    {
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->serializer = $serializer;
    }

    public function aroundRepresentProduct(QuoteItem $subject, \Closure $proceed, Product $productS1): bool
    {
        /* @var Product $productS2 */
        if ($subject->getQuote()->getItems()) {
            $productS2 = $subject->getProduct();
            if ($this->productRepository->getById($productS2->getId())->getData('modules_product_enable') == 1
                && $productS1->getData('modules_product_enable') == 1
                && count($subject->getQuote()->getItems()) >= 1
            ) {
                return $this->compareOptions($productS2, $productS1);
            }
        }
        return $proceed($productS1);
    }

    private function compareOptions($productS2, $productS1)
    {
        if ($productOptions2 = $productS2->getCustomOption('info_buyRequest')->getValue()) {
            if (!is_array($productOptions2)) {
                $productOptions2 = $this->serializer->unserialize($productOptions2);
                $productOptions1 = $productS1->getTypeInstance(true)->getOrderOptions($productS1)['info_buyRequest'];
                unset($productOptions1['mounting_products']);
                unset($productOptions2['mounting_products']);
                if ($productOptions1 == $productOptions2) {
                    return true;
                }
            }
        }
        return false;
    }
}
