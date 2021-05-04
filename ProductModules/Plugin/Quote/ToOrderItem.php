<?php

namespace Gssi\ProductModules\Plugin\Quote;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Model\Quote\Item\ToOrderItem as QuoteToOrderItem;

class ToOrderItem
{
    /**
     * @var Json|mixed
     */
    private $serializer;

    public function __construct(Json $serializer = null)
    {
        $this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\Serialize\Serializer\Json::class);
    }

    public function aroundConvert(
        QuoteToOrderItem $subject,
        \Closure $proceed,
        $item,
        $data = []
    )
    {
        $orderItem = $proceed($item, $data);

        $additionalOptions = $item->getOptionByCode('additional_options');
        if (!empty($additionalOptions)) {
            $options = $orderItem->getProductOptions();
            $options['additional_options'] = $this->serializer->unserialize($additionalOptions->getValue());
            $orderItem->setProductOptions($options);
        }

        return $orderItem;
    }
}
