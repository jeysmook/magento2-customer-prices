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
    public function setItemId(int $value): void;

    /**
     * Get product ID
     *
     * @return int|null
     */
    public function getProductId(): ?int;

    /**
     * Set product ID
     *
     * @param int $value
     */
    public function setProductId(int $value): void;

    /**
     * Get customer ID
     *
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * Set customer ID
     *
     * @param int $value
     */
    public function setCustomerId(int $value): void;

    /**
     * Get price
     *
     * @return float |null
     */
    public function getPrice(): ?float;

    /**
     * Set price
     *
     * @param float $value
     */
    public function setPrice(float $value): void;

    /**
     * Get quantity
     *
     * @return float |null
     */
    public function getQty(): ?float;

    /**
     * Set quantity
     *
     * @param float $value
     */
    public function setQty(float $value): void;

    /**
     * Get website ID
     *
     * @return int|null
     */
    public function getWebsiteId(): ?int;

    /**
     * Set website ID
     *
     * @param int $value
     */
    public function setWebsiteId(int $value): void;
}
