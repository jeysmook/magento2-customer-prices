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
    private const PRODUCT_ID = 'product_id';
    private const CUSTOMER_ID = 'customer_id';
    private const PRICE = 'price';
    private const QTY = 'qty';
    private const WEBSITE_ID = 'website_id';
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
    public function setItemId(int $value): void
    {
        $this->setData(self::ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): ?int
    {
        $value = (string)$this->getData(self::PRODUCT_ID);
        return $value !== '' ? (int)$value : null;
    }

    /**
     * @inheritDoc
     */
    public function setProductId(int $value): void
    {
        $this->setData(self::PRODUCT_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): ?int
    {
        $value = (string)$this->getData(self::CUSTOMER_ID);
        return $value !== '' ? (int)$value : null;
    }

    /**
     * @inheritDoc
     */
    public function setCustomerId(int $value): void
    {
        $this->setData(self::CUSTOMER_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function getPrice(): ?float
    {
        $value = (string)$this->getData(self::PRICE);
        return $value !== '' ? (float)$value : null;
    }

    /**
     * @inheritDoc
     */
    public function setPrice(float $value): void
    {
        $this->setData(self::PRICE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getQty(): ?float
    {
        $value = (string)$this->getData(self::QTY);
        return $value !== '' ? (float)$value : null;
    }

    /**
     * @inheritDoc
     */
    public function setQty(float $value): void
    {
        $this->setData(self::QTY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getWebsiteId(): ?int
    {
        $value = (string)$this->getData(self::WEBSITE_ID);
        return $value !== '' ? (int)$value : null;
    }

    /**
     * @inheritDoc
     */
    public function setWebsiteId(int $value): void
    {
        $this->setData(self::WEBSITE_ID, $value);
    }
}
