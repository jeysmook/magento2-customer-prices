<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * The model of the customer price entity
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class CustomerPrice extends AbstractModel implements CustomerPriceInterface
{
    protected const CACHE_TAG = 'jeysmook_customer_price';

    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    private const ID = ResourceModel\CustomerPrice::PK;
    /**#@-*/

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @var string
     */
    protected $_eventPrefix = 'jeysmook_customer_price';

    /**
     * @inheritDoc
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init(ResourceModel\CustomerPrice::class);
        $this->setIdFieldName(self::ID);
    }

    /**
     * @inheritDoc
     */
    public function getItemId(): ?int
    {
        $value = (string)$this->getData(self::ID);
        return $value !== '' ? (int)$value : null;
    }

    /**
     * @inheritDoc
     */
    public function setCustomerPriceId(int $value): void
    {
        $this->setData(self::ID, $value);
    }
}
