<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Adapter\FieldMapper\Product\FieldProvider;

use Jeysmook\CustomerPrices\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use Magento\Customer\Api\Data\CustomerInterface;
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
    private $fieldTypeConverter;

    /**
     * @var CollectionFactory
     */
    private $customerCollectionFactory;

    /**
     * @var CustomerPriceFieldNameResolver
     */
    private $customerPriceFieldNameResolver;

    /**
     * CustomerPriceField constructor
     *
     * @param FieldTypeConverterInterface $fieldTypeConverter
     * @param CollectionFactory $customerCollectionFactory
     * @param CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
     */
    public function __construct(
        FieldTypeConverterInterface $fieldTypeConverter,
        CollectionFactory $customerCollectionFactory,
        CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
    ) {
        $this->fieldTypeConverter = $fieldTypeConverter;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->customerPriceFieldNameResolver = $customerPriceFieldNameResolver;
    }

    /**
     * @inheritdoc
     */
    public function getFields(array $context = []): array
    {
        $fields = [];

        $collection = $this->customerCollectionFactory->create();
        $collection->addFieldToSelect(['entity_id', 'website_id']);

        /** @var CustomerInterface $customer */
        foreach ($collection->getItems() as $customer) {
            $fieldName = $this->customerPriceFieldNameResolver->resolve(
                ['websiteId' => $customer->getWebsiteId(), 'customerId' => $customer->getId()]
            );
            $fields[$fieldName] = [
                'type' => $this->fieldTypeConverter->convert(
                    FieldTypeConverterInterface::INTERNAL_DATA_TYPE_FLOAT
                ),
                'store' => true
            ];
        }
        return $fields;
    }
}
