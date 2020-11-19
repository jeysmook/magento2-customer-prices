<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;

use Jeysmook\CustomerPrices\Model\CustomerPrice as RequestModel;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * The collection of customer price entities
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class Collection extends AbstractCollection
{
    protected const EVENT_PREFIX = 'jeysmook_customer_price_collection';
    protected const EVENT_OBJECT = 'customer_price_collection';

    /**
     * @var string
     */
    protected $_idFieldName = CustomerPriceResource::PK;

    /**
     * @var string
     */
    protected $_eventPrefix = self::EVENT_PREFIX;

    /**
     * @var string
     */
    protected $_eventObject = self::EVENT_OBJECT;

    /**
     * @inheritDoc
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init(RequestModel::class, CustomerPriceResource::class);
    }
}
