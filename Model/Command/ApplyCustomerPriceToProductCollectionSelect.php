<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command;

use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Framework\DB\Select;
use Zend_Db_Select_Exception;

/**
 * Applying customer prices to the product collection select
 *
 * @SuppressWarnings(PHPMD.LongClassName)
 */
class ApplyCustomerPriceToProductCollectionSelect
{
    private const COLUMN_NAME = 0;
    private const COLUMN_EXPR = 1;
    private const COLUMN_ALIAS = 2;

    /**
     * @var CustomerPrice
     */
    private $resource;

    /**
     * @var array
     */
    private $flags = [];

    /**
     * ApplyCustomerPriceToProductCollectionSelect constructor
     *
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPrice $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * Applying customer prices to the product collection select
     *
     * @param Select $select
     * @param int $customerId
     * @param int $websiteId
     * @param string $mainAlias
     * @return void
     * @throws Zend_Db_Select_Exception
     */
    public function execute(
        Select $select,
        int $customerId,
        int $websiteId,
        string $mainAlias = 'e'
    ): void {
        $flagKey = md5($select . $customerId . $mainAlias);
        if (!isset($this->flags[$flagKey])) {
            $fromPart = $select->getPart(Select::FROM);
            $columnsPart = $select->getPart(Select::COLUMNS);

            $customerPriceAlias = 'customer_price';
            if (!isset($fromPart[$customerPriceAlias])) {
                $select->joinLeft(
                    [$customerPriceAlias => $this->resource->getMainTable()],
                    $mainAlias . '.entity_id = ' . $customerPriceAlias . '.product_id'
                        . ' AND ' . $customerPriceAlias . '.customer_id = ' . $customerId
                        . ' AND ' . $customerPriceAlias . '.qty = 1'
                        . ' AND ' . $customerPriceAlias . '.website_id = ' . $websiteId,
                    []
                );
            }

            foreach ($columnsPart as &$column) {
                if (($column[self::COLUMN_NAME] ?? null) === 'price_index' && isset($column[self::COLUMN_EXPR])) {
                    $columnName = (string)($column[self::COLUMN_ALIAS] ?? $column[self::COLUMN_EXPR]);
                    if ('minimal_price' === $columnName) {
                        $column[self::COLUMN_EXPR] = $this->resource->getConnection()
                            ->getLeastSql(
                                [
                                    (string)$column[self::COLUMN_EXPR],
                                    $customerPriceAlias . '.price'
                                ]
                            );
                        $column[self::COLUMN_ALIAS] = $columnName;
                    }
                }
            }
            $select->setPart(Select::COLUMNS, $columnsPart);

            $this->flags[$flagKey] = true;
        }
    }
}
