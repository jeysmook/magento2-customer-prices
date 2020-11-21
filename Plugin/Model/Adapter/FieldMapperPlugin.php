<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Plugin\Model\Adapter;

use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;
use Jeysmook\CustomerPrices\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;

/**
 * Updating the price field to the customer price field
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class FieldMapperPlugin
{
    /**
     * @var CustomerProviderInterface
     */
    private $customerProvider;

    /**
     * @var CustomerPriceFieldNameResolver
     */
    private $customerPriceFieldNameResolver;

    /**
     * FieldMapperPlugin constructor
     *
     * @param CustomerProviderInterface $customerProvider
     * @param CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
     */
    public function __construct(
        CustomerProviderInterface $customerProvider,
        CustomerPriceFieldNameResolver $customerPriceFieldNameResolver
    ) {
        $this->customerProvider = $customerProvider;
        $this->customerPriceFieldNameResolver = $customerPriceFieldNameResolver;
    }

    /**
     * Updating the price field to the customer price field
     *
     * @param FieldMapperInterface $fieldMapper
     * @param string $fieldName
     * @param string $attributeCode
     * @return string
     * @see FieldMapperInterface::getFieldName()
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetFieldName(
        FieldMapperInterface $fieldMapper,
        string $fieldName,
        string $attributeCode
    ): string {
        if ('price' === $attributeCode && $this->customerProvider->getCustomerId()) {
            return $this->customerPriceFieldNameResolver->resolve(
                [
                    'websiteId' => $this->customerProvider->getWebsiteId(),
                    'customerId' => $this->customerProvider->getCustomerId()
                ]
            );
        }
        return $fieldName;
    }
}
