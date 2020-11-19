<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Api\Data;

/**
 * The customer price entity
 */
interface CustomerPriceInterface
{
    /**
     * Get customer price ID
     *
     * @return int|null
     */
    public function getItemId(): ?int;

    /**
     * Set customer price ID
     *
     * @param int $value
     */
    public function setCustomerPriceId(int $value): void;
}
