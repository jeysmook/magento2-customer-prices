<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName;

use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;

/**
 * Resolving field name for the customer price
 */
class CustomerPriceFieldNameResolver
{
    /**
     * @var CustomerProviderInterface
     */
    private $customerProvider;

    /**
     * CustomerPriceFieldNameResolver constructor
     *
     * @param CustomerProviderInterface $customerProvider
     */
    public function __construct(
        CustomerProviderInterface $customerProvider
    ) {
        $this->customerProvider = $customerProvider;
    }

    /**
     * Resolving field name for the customer price
     *
     * @param array $context
     * @return string
     */
    public function resolve($context = []): string
    {
        $websiteId = $context['websiteId'] ?? $this->customerProvider->getWebsiteId();
        $customerId = $context['customerId'] ?? $this->customerProvider->getCustomerId();
        return 'customer_price_' . $websiteId . '_' . $customerId;
    }
}
