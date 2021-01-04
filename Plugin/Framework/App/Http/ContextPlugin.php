<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Plugin\Framework\App\Http;

use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;
use Jeysmook\CustomerPrices\Model\Command\ExistingCustomerPriceByStrategy;
use Magento\Framework\App\Http\Context;

/**
 * Adding the customer price variable to the context
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ContextPlugin
{
    private const KEY = 'JC_CUSTOMER_ID';

    /**
     * @var CustomerProviderInterface
     */
    private $customerProvider;

    /**
     * @var ExistingCustomerPriceByStrategy
     */
    private $existingCustomerPriceByStrategy;

    /**
     * ContextPlugin constructor
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
     * Adding the customer price variable to the context
     *
     * @param Context $context
     * @return void
     */
    public function beforeGetVaryString(Context $context): void
    {
        if (($customerId = $this->customerProvider->getCustomerId())
            && $this->existingCustomerPriceByStrategy->execute(
                (int)$customerId,
                ExistingCustomerPriceByStrategy::STRATEGY_CUSTOMER
            )
        ) {
            $context->setValue(self::KEY, $customerId, false);
        }
    }
}
