<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command;

use Magento\Framework\App\ResourceConnection;

/**
 * Is exist the customer price by the strategy? The strategy is the column name
 */
class ExistingCustomerPriceByStrategy
{
    public const STRATEGY_CUSTOMER = 'customer_id';
    public const STRATEGY_PRODUCT = 'product_id';

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var bool[]
     */
    private $cache = [];

    /**
     * ExistingCustomerPriceByStrategy constructor
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Is exist the customer price by the strategy? The strategy is the column name
     *
     * @param int $value
     * @param string $strategy
     * @return bool
     */
    public function execute(int $value, string $strategy): bool
    {
        if (!in_array($strategy, [self::STRATEGY_CUSTOMER, self::STRATEGY_PRODUCT])) {
            $strategy = self::STRATEGY_CUSTOMER;
        }

        $cacheKey = $strategy . $value;
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        $select = $this->resourceConnection->getConnection()->select();
        $select->from($this->resourceConnection->getTableName('jeysmook_customer_price'), 'COUNT(*)');
        $select->where($strategy . ' = ?', $value);
        $size = $this->resourceConnection->getConnection()->fetchOne($select);
        return $this->cache[$cacheKey] = $size > 0;
    }
}
