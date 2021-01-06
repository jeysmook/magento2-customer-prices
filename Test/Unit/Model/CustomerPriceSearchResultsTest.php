<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterfaceFactory;
use Jeysmook\CustomerPrices\Model\CustomerPrice;
use Jeysmook\CustomerPrices\Model\CustomerPriceSearchResults;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPriceSearchResults
 */
class CustomerPriceSearchResultsTest extends TestCase
{
    /**
     * @var CustomerPriceInterfaceFactory|MockObject
     */
    private $entityFactory;

    /**
     * @var CustomerPriceSearchResults
     */
    private $searchResults;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $entity = $this->getMockBuilder(CustomerPrice::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setItemId'])
            ->getMock();
        $this->entityFactory = $this->getMockBuilder(CustomerPriceInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->entityFactory->expects($this->any())
            ->method('create')
            ->willReturn($entity);
        $this->searchResults = new CustomerPriceSearchResults();
    }

    /**
     * @see CustomerPriceSearchResults::getItems()
     */
    public function testGetItems()
    {
        $expectedValue = [
            $this->entityFactory->create(),
            $this->entityFactory->create(),
            $this->entityFactory->create()
        ];
        $this->searchResults->setItems($expectedValue);
        $this->assertEquals($expectedValue, $this->searchResults->getItems());
    }
}
