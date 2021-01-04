<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Plugin\Framework\Pricing\Render;

use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;
use Jeysmook\CustomerPrices\Model\Command\ExistingCustomerPriceByStrategy;
use Magento\Framework\Pricing\Render\PriceBox;

/**
 * Adding additional cache tags to the price box
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class PriceBoxPlugin
{
    /**
     * @var CustomerProviderInterface
     */
    private $customerProvider;

    /**
     * @var ExistingCustomerPriceByStrategy
     */
    private $existingCustomerPriceByStrategy;

    /**
     * PriceBoxPlugin constructor
     *
     * @param CustomerProviderInterface $customerProvider
     * @param ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy
     */
    public function __construct(
        CustomerProviderInterface $customerProvider,
        ExistingCustomerPriceByStrategy $existingCustomerPriceByStrategy
    ) {
        $this->customerProvider = $customerProvider;
        $this->existingCustomerPriceByStrategy = $existingCustomerPriceByStrategy;
    }

    /**
     * Adding additional cache tags to the price box
     *
     * @param PriceBox $priceBox
     * @param string $cacheKey
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCacheKey(PriceBox $priceBox, string $cacheKey): string
    {
        if (($customerId = $this->customerProvider->getCustomerId())
            && $this->existingCustomerPriceByStrategy->execute(
                (int)$customerId,
                ExistingCustomerPriceByStrategy::STRATEGY_CUSTOMER
            )
        ) {
            return implode('-', [$cacheKey, $customerId]);
        }
        return $cacheKey;
    }
}
