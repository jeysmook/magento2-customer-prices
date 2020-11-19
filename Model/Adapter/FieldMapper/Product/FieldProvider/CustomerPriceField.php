<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Adapter\FieldMapper\Product\FieldProvider;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldType\ConverterInterface
    as FieldTypeConverterInterface;
use Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\FieldProviderInterface;

/**
 * Provide customer price fields for product
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceField implements FieldProviderInterface
{
    /**
     * @var FieldTypeConverterInterface
     */
    private FieldTypeConverterInterface $fieldTypeConverter;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $customerCollectionFactory;

    /**
     * CustomerPriceField constructor
     *
     * @param FieldTypeConverterInterface $fieldTypeConverter
     * @param CollectionFactory $customerCollectionFactory
     */
    public function __construct(
        FieldTypeConverterInterface $fieldTypeConverter,
        CollectionFactory $customerCollectionFactory
    ) {
        $this->fieldTypeConverter = $fieldTypeConverter;
        $this->customerCollectionFactory = $customerCollectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function getFields(array $context = []): array
    {
        $fields = [];
        foreach ($this->customerCollectionFactory->create()->getAllIds() as $customerId) {
            $fields['customer_price_'. $customerId] = [
                'type' => $this->fieldTypeConverter->convert(
                    FieldTypeConverterInterface::INTERNAL_DATA_TYPE_FLOAT
                ),
                'store' => true
            ];
        }
        return $fields;
    }
}
