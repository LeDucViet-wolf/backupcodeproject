<?php

namespace Gssi\ProductModules\Plugin;

use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Related as RelatedParent;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Form\Fieldset;

class Related extends RelatedParent
{
    const GROUP_RELATED = 'related';
    const DATA_SCOPE_PRODUCT_LINKED = 'product_module';
    private $priceModifier;
    protected $product;
    /**
     * @var \Gssi\ProductModules\Model\ProductFactory
     */
    private $productFactory;

    public function __construct(
        \Gssi\ProductModules\Model\ProductFactory $productFactory,
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        ProductLinkRepositoryInterface $productLinkRepository,
        ProductRepositoryInterface $productRepository,
        ImageHelper $imageHelper, Status $status,
        AttributeSetRepositoryInterface $attributeSetRepository,
        $scopeName = '',
        $scopePrefix = ''
    )
    {
        parent::__construct($locator, $urlBuilder, $productLinkRepository, $productRepository, $imageHelper, $status, $attributeSetRepository, $scopeName, $scopePrefix);
        $this->productFactory = $productFactory;
    }

    public function afterModifyMeta($modify, $result)
    {
        if (isset($result[static::GROUP_RELATED]['children'])) {
            $result[static::GROUP_RELATED]['children'][$modify->scopePrefix . static::DATA_SCOPE_PRODUCT_LINKED] = $this->getProductModuleFieldset($modify);
        }
        return $result;
    }

    private function getPriceModifier($modify)
    {
        if (!$this->priceModifier) {
            $this->priceModifier = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Catalog\Ui\Component\Listing\Columns\Price::class
            );
        }
        return $this->priceModifier;
    }

    protected function getProductModuleFieldset($modify)
    {
        $content = __(
            'Mounting Products.'
        );
        return [
            'children' => [
                'button_set' => $modify->getButtonSet(
                    $content, __('Add Mounting Product'), $modify->scopePrefix . static::DATA_SCOPE_PRODUCT_LINKED
                ),
                'modal' => $this->getGenericModal(
                    __('Add Mounting Product'), $modify->scopePrefix . static::DATA_SCOPE_PRODUCT_LINKED
                ),
                static::DATA_SCOPE_PRODUCT_LINKED => $this->getGrid($modify->scopePrefix . static::DATA_SCOPE_PRODUCT_LINKED),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Mounting Products'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 11,
                    ],
                ],
            ]
        ];
    }

    public function afterModifyData($modify, $data)
    {
        $product = $modify->locator->getProduct();
        $productId = $product->getId();

        if (!$productId) {
            return $data;
        }
        $priceModifier = $this->getPriceModifier($modify);
        $priceModifier->setData('name', 'price');
        $dataScopes = $this->getDataScopes();
        $dataScopes[] = static::DATA_SCOPE_PRODUCT_LINKED;
        foreach ($dataScopes as $dataScope) {
            if ($dataScope == static::DATA_SCOPE_PRODUCT_LINKED) {
                $data[$productId]['links'][$dataScope] = [];
                foreach ($modify->productLinkRepository->getList($product) as $linkItem) {
                    if ($linkItem->getLinkType() !== $dataScope) {
                        continue;
                    }

                    /** @var \Magento\Catalog\Model\Product $linkedProduct */
                    $linkedProduct = $modify->productRepository->get(
                        $linkItem->getLinkedProductSku(),
                        false,
                        $modify->locator->getStore()->getId()
                    );
                    $data[$productId]['links'][$dataScope][] = $this->fillData($linkedProduct, $linkItem);
                }
                if (!empty($data[$productId]['links'][$dataScope])) {
                    $dataMap = $priceModifier->prepareDataSource([
                        'data' => [
                            'items' => $data[$productId]['links'][$dataScope]
                        ]
                    ]);
                    $data[$productId]['links'][$dataScope] = $dataMap['data']['items'];
                }
            }
        }

        return $data;
    }

    public function beforeGetLinkedProducts($provider, $product)
    {
        $this->product = $this->productFactory->create();
        $currentProduct = $this->product->load($product->getId());
        return [$currentProduct];
    }
}
