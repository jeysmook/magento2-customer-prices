<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * The repository of the customer price entity
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceSearchResults extends SearchResults implements CustomerPriceSearchResultsInterface
{
}
