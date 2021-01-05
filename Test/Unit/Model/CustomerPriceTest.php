<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model;

use Jeysmook\CustomerPrices\Model\CustomerPrice;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPrice
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class CustomerPriceTest extends TestCase
{
    /**
     * @var CustomerPrice
     */
    private $customerPrice;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->customerPrice = $objectManager->getObject(CustomerPrice::class);
    }

    /**
     * @see CustomerPrice::setItemId()
     */
    public function testSetItemId(): void
    {
        $expectedValue = 1;
        $this->customerPrice->setItemId($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData(CustomerPrice::ID));
    }

    /**
     * @see CustomerPrice::getItemId()
     */
    public function testGetItemId(): void
    {
        $expectedValue = 2;
        $this->customerPrice->setData(CustomerPrice::ID, $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getItemId());
    }

    /**
     * @see CustomerPrice::setCustomerId()
     */
    public function testSetCustomerId(): void
    {
        $expectedValue = 3;
        $this->customerPrice->setCustomerId($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData(CustomerPrice::CUSTOMER_ID));
    }

    /**
     * @see CustomerPrice::setCustomerId()
     */
    public function testGetCustomerId(): void
    {
        $expectedValue = 4;
        $this->customerPrice->setData(CustomerPrice::CUSTOMER_ID, $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getCustomerId());
    }

    /**
     * @see CustomerPrice::setProductId()
     */
    public function testSetProductId(): void
    {
        $expectedValue = 5;
        $this->customerPrice->setProductId($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData(CustomerPrice::PRODUCT_ID));
    }

    /**
     * @see CustomerPrice::setProductId()
     */
    public function testGetProductId(): void
    {
        $expectedValue = 6;
        $this->customerPrice->setData(CustomerPrice::PRODUCT_ID, $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getProductId());
    }

    /**
     * @see CustomerPrice::setQty()
     */
    public function testSetQty(): void
    {
        $expectedValue = 7.1;
        $this->customerPrice->setQty($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData(CustomerPrice::QTY));
    }

    /**
     * @see CustomerPrice::setQty()
     */
    public function testGetQty(): void
    {
        $expectedValue = 8.2;
        $this->customerPrice->setData(CustomerPrice::QTY, $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getQty());
    }

    /**
     * @see CustomerPrice::setPrice()
     */
    public function testSetPrice(): void
    {
        $expectedValue = 9.1;
        $this->customerPrice->setPrice($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData(CustomerPrice::PRICE));
    }

    /**
     * @see CustomerPrice::setPrice()
     */
    public function testGetPrice(): void
    {
        $expectedValue = 10.2;
        $this->customerPrice->setData(CustomerPrice::PRICE, $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getPrice());
    }

    /**
     * @see CustomerPrice::setWebsiteId()
     */
    public function testSetWebsiteId(): void
    {
        $expectedValue = 11;
        $this->customerPrice->setWebsiteId($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData(CustomerPrice::WEBSITE_ID));
    }

    /**
     * @see CustomerPrice::setWebsiteId()
     */
    public function testGetWebsiteId(): void
    {
        $expectedValue = 12;
        $this->customerPrice->setData(CustomerPrice::WEBSITE_ID, $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getWebsiteId());
    }

    /**
     * @see CustomerPrice::setCreatedAt()
     */
    public function testSetCreatedAt(): void
    {
        $expectedValue = 'test13';
        $this->customerPrice->setCreatedAt($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData(CustomerPrice::CREATED_AT));
    }

    /**
     * @see CustomerPrice::setCreatedAt()
     */
    public function testGetCreatedAt(): void
    {
        $expectedValue = 'test14';
        $this->customerPrice->setData(CustomerPrice::CREATED_AT, $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getCreatedAt());
    }

    /**
     * @see CustomerPrice::getUpdatedAt()
     */
    public function testGetUpdatedAt(): void
    {
        $expectedValue = 'test15';
        $this->customerPrice->setUpdatedAt($expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getData(CustomerPrice::UPDATED_AT));
    }

    /**
     * @see CustomerPrice::setUpdatedAt()
     */
    public function testSetUpdatedAt(): void
    {
        $expectedValue = 'test16';
        $this->customerPrice->setData(CustomerPrice::UPDATED_AT, $expectedValue);
        $this->assertEquals($expectedValue, $this->customerPrice->getUpdatedAt());
    }
}
