<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Api;

/**
 * Resolving the current customer ID
 */
interface CustomerResolverInterface
{
    /**
     * Resolve the current customer ID
     *
     * @return int|null
     */
    public function resolve(): ?int;
}
