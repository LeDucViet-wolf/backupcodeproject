<?php

namespace Gssi\ProductModules\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Gssi\ProductModules\Model\Product\Attribute\Source\ColorOptions;

class SalesQuoteAddObserver implements ObserverInterface
{
    /**
     * @var \Gssi\ProductModules\Helper\Data
     */
    private $dataHelper;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    public function __construct(
        \Gssi\ProductModules\Helper\Data $dataHelper,
        SerializerInterface $serializer,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->dataHelper = $dataHelper;
        $this->serializer = $serializer;
        $this->productRepository = $productRepository;
        $this->request = $request;
    }

    public function execute(Observer $observer)
    {
        $quoteItems = $observer->getItems();
        if ($quoteItems) {
            foreach ($quoteItems as $quoteItem) {
                $product = $this->productRepository->getById($quoteItem->getProduct()->getId());
                if ($product->getModulesProductEnable() && $product->getModulesProductEnable() == 1) {
                    if ($this->request->getParam('super_attribute')) {
                        $buyRequest = $this->request->getParams();
                    } else {
                        $buyRequest = $this->serializer->unserialize($quoteItem->getOptionByCode('info_buyRequest')->getValue());
                    }
                    $additionalOptions = $customOptions = [];
                    if (array_key_exists($colorCountAttributeId = $this->dataHelper->getAttributeId($product, 'color_count'), $buyRequest)) {
                        $customOptions = $buyRequest[$colorCountAttributeId];
                    }

                    if ($additionalOption = $quoteItem->getOptionByCode('additional_options')) {
                        $additionalOptions = $this->serializer->unserialize($additionalOption->getValue());
                    }

                    if (!$additionalOptions) {
                        $superAttribute = $buyRequest['super_attribute'];
                        if(!empty($customOptions)) {
                            if ($superAttribute && isset($superAttribute[$colorCountAttributeId])) {
                                foreach ($customOptions as $key => $value) {
                                    switch ((int)$superAttribute[$colorCountAttributeId]) {
                                        case ColorOptions::SINGLE_COLOR_VALUE:
                                            $valueText = $this->dataHelper->getOptionText($product, $value, 'single_color');
                                            break;
                                        case ColorOptions::DOU_COLOR_VALUE:
                                            $valueText = $this->dataHelper->getOptionText($product, $value, 'dual_color');
                                            break;
                                        case ColorOptions::TRI_COLOR_VALUE:
                                            $valueText = $this->dataHelper->getOptionText($product, $value, 'tri_color');
                                            break;
                                    }
                                    $additionalOptions[] = [
                                        'label' => ucfirst(str_replace('_', ' ', $key)),
                                        'value' => $valueText,
                                        'value_number' => $value,
                                        'label_default' => $key
                                    ];
                                }
                            }
                        }
                    }

                    if (count($additionalOptions) > 0) {
                        $quoteItem->addOption(array(
                            'product_id' => $quoteItem->getProductId(),
                            'code' => 'additional_options',
                            'value' => $this->serializer->serialize($additionalOptions)
                        ));
                    }
                    if ($mountingProduct = $buyRequest['mounting_products']) {
                        try {
                            $qty = $buyRequest['qty'];
                            $mountingProduct = $this->productRepository->getById((int)$mountingProduct);
                            $quoteItem->getQuote()->addProduct($mountingProduct, $qty);
                            $quoteItem->getQuote()->save();
                        } catch (\Exception $exception){
                            return;
                        }

                    }
                }
            }
        }
    }
}
