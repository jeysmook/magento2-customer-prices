<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * The customer price search results
 */
interface CustomerPriceSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get customer prices list
     *
     * @return \Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface[]
     */
    public function getItems();

    /**
     * Set customer prices list
     *
     * @param \Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface[] $items
     * @return CustomerPriceSearchResultsInterface
     */
    public function setItems(array $items);
}
