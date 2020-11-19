<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Api;

use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Getting information about the current customer
 */
interface CustomerProviderInterface
{
    /**
     * Get the current customer ID
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Get the current customer
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface;
}
