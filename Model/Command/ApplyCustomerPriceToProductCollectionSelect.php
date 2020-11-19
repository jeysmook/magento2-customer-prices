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

class ApplyCustomerPriceToProductCollectionSelect
{
    /**
     * @var CustomerPrice
     */
    private CustomerPrice $resource;

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
     * @param Select $select
     * @param int $customerId
     * @param string $mainAlias
     * @return void
     * @throws Zend_Db_Select_Exception
     */
    public function execute(
        Select $select,
        int $customerId,
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
                    . ' AND ' . $customerPriceAlias . '.qty = 1',
                    []
                );
            }

            foreach ($columnsPart as &$column) {
                if (($column[0] ?? null) === 'price_index' && isset($column[1])) {
                    $columnName = (string)($column[2] ?? $column[1]);
                    if ('minimal_price' === $columnName) {
                        $column[1] = $this->resource->getConnection()
                            ->getLeastSql(
                                [
                                    (string)$column[1],
                                    $customerPriceAlias . '.price'
                                ]
                            );
                        $column[2] = $columnName;
                    }
                }
            }
            $select->setPart(Select::COLUMNS, $columnsPart);

            $this->flags[$flagKey] = true;
        }
    }
}
