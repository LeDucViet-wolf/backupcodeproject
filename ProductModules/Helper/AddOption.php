<?php

namespace Gssi\ProductModules\Helper;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Eav\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory;
use Magento\Eav\Model\Entity\Attribute\OptionLabel;
use Magento\Eav\Model\Entity\Attribute\Source\Table;
use Magento\Eav\Model\Entity\Attribute\Source\TableFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;

class AddOption extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var ProductAttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var array
     */
    protected $attributeValues;

    /**
     * @var TableFactory
     */
    protected $tableFactory;

    /**
     * @var AttributeOptionManagementInterface
     */
    protected $attributeOptionManagement;

    /**
     * @var AttributeOptionLabelInterfaceFactory
     */
    protected $optionLabelFactory;

    /**
     * @var AttributeOptionInterfaceFactory
     */
    protected $optionFactory;

    public function __construct(
        Context $context,
        ProductAttributeRepositoryInterface $attributeRepository,
        TableFactory $tableFactory,
        AttributeOptionManagementInterface $attributeOptionManagement,
        AttributeOptionLabelInterfaceFactory $optionLabelFactory,
        AttributeOptionInterfaceFactory $optionFactory
    )
    {
        parent::__construct($context);

        $this->attributeRepository = $attributeRepository;
        $this->tableFactory = $tableFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->optionLabelFactory = $optionLabelFactory;
        $this->optionFactory = $optionFactory;
    }

    public function createOrGetId($attributeCode, $label)
    {
        if (strlen($label) < 1) {
            throw new LocalizedException(
                __('Label for %1 must not be empty.', $attributeCode)
            );
        }

        $optionId = $this->getOptionId($attributeCode, $label);

        if (!$optionId) {

            /** @var OptionLabel $optionLabel */
            $optionLabel = $this->optionLabelFactory->create();
            $optionLabel->setStoreId(0);
            $optionLabel->setLabel($label);

            $option = $this->optionFactory->create();
            $option->setLabel($label);
            $option->setStoreLabels([$optionLabel]);
            $option->setSortOrder(0);
            $option->setIsDefault(false);

            $this->attributeOptionManagement->add(
                Product::ENTITY,
                $this->getAttribute($attributeCode)->getAttributeId(),
                $option
            );

            $optionId = $this->getOptionId($attributeCode, $label, true);
        }

        return $optionId;
    }

    public function getOptionId($attributeCode, $label, $force = false)
    {
        /** @var Attribute $attribute */
        $attribute = $this->getAttribute($attributeCode);

        if ($force === true || !isset($this->attributeValues[$attribute->getAttributeId()])) {
            $this->attributeValues[$attribute->getAttributeId()] = [];

            /** @var Table $sourceModel */
            $sourceModel = $this->tableFactory->create();
            $sourceModel->setAttribute($attribute);

            foreach ($sourceModel->getAllOptions() as $option) {
                $this->attributeValues[$attribute->getAttributeId()][$option['label']] = $option['value'];
            }
        }

        if (isset($this->attributeValues[$attribute->getAttributeId()][$label])) {
            return $this->attributeValues[$attribute->getAttributeId()][$label];
        }

        return false;
    }

    public function getAttribute($attributeCode)
    {
        return $this->attributeRepository->get($attributeCode);
    }
}
