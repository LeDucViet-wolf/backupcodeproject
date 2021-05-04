<?php

namespace Gssi\ProductModules\Block\Catalog\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\Context;

class View extends \Magento\Catalog\Block\Product\View
{
    /**
     * @var \Magento\Quote\Model\Quote\ItemFactory
     */
    private $quoteItemFactory;
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession, ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    )
    {
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
        $this->quoteItemFactory = $quoteItemFactory;
        $this->quoteRepository = $quoteRepository;
    }

    public function getIdentities()
    {
        $product = $this->getProduct();
        return $product ? $product->getIdentities() : [];
    }

    public function getSelectedOptions()
    {
        $option = null;
        $quoteItemId = $this->getRequest()->getParam('id');
        $productId = $this->getRequest()->getParam('product_id');
        if ($quoteItemId && $productId) {
            $quoteId = $this->quoteItemFactory->create()->load($quoteItemId)->getQuoteId();
            $quote = $this->quoteRepository->get($quoteId);
            if ($quote->getItemById($quoteItemId)) {
                $item = $quote->getItemById($quoteItemId);
                if($item){
                    $option = $item->getOptionByCode('additional_options')->getValue();
                }
            }
        }
        return $option;
    }
}
