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
use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;

/**
 * Updating the price field to the customer price field
 */
class FieldMapperPlugin
{
    /**
     * @var CustomerProviderInterface
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * FieldMapperPlugin constructor
     *
     * @param CustomerProviderInterface $customerProvider
     */
    public function __construct(
        CustomerProviderInterface $customerProvider
    ) {
        $this->customerProvider = $customerProvider;
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
            return 'customer_price_'
                . $this->customerProvider->getWebsiteId()
                . '_'
                . $this->customerProvider->getCustomerId();
        }
        return $fieldName;
    }
}
