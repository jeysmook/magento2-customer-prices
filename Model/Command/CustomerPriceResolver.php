<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;

/**
 * Resolving the customer price for the product
 */
class CustomerPriceResolver
{
    /**
     * @var CustomerPrice
     */
    private $resource;

    /**
     * @var float|null[]
     */
    private $cache = [];

    /**
     * CustomerPriceFieldNameResolver constructor
     *
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPrice $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * Resolving the customer price for the product
     *
     * @param int $customerId
     * @param int $websiteId
     * @param int $productId
     * @param float $qty
     * @return float|null
     */
    public function resolve(
        int $customerId,
        int $websiteId,
        int $productId,
        float $qty
    ): ?float {
        $cacheKey = $customerId . $productId . $qty;
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $select = $this->resource->getConnection()->select();
        $select->from($this->resource->getMainTable(), 'price');
        $select->where('customer_id = ?', $customerId);
        $select->where('product_id = ?', $productId);
        $select->where('website_id = ?', $websiteId);
        $select->where('qty <= ?', max($qty, 1));
        $select->order('qty DESC');
        $select->limit(1);
        $price = $this->resource->getConnection()->fetchOne($select);
        return $this->cache[$cacheKey] = $price > 0 ? (float)$price : null;
    }
}
