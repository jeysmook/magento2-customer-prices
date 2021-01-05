<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command;

use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\PriceResolver;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see PriceResolver
 */
class PriceResolverTest extends TestCase
{
    /**
     * @var AdapterInterface|MockObject
     */
    private $connection;

    /**
     * @var PriceResolver
     */
    private $resolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $select = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'order', 'from', 'limit'])
            ->getMock();
        $select->expects($this->any())
            ->method('from')
            ->with(CustomerPriceResource::TABLE_NAME, 'price')
            ->willReturnSelf();
        $select->expects($this->any())
            ->method('where')
            ->willReturnSelf();
        $select->expects($this->any())
            ->method('order')
            ->with('qty DESC')
            ->willReturnSelf();
        $select->expects($this->any())
            ->method('limit')
            ->with(1)
            ->willReturnSelf();
        $this->connection = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection->expects($this->any())
            ->method('select')
            ->willReturn($select);
        $resource = $this->getMockBuilder(CustomerPriceResource::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resource->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->connection);
        $resource->expects($this->any())
            ->method('getMainTable')
            ->willReturn(CustomerPriceResource::TABLE_NAME);
        $this->resolver = new PriceResolver($resource);
    }

    /**
     * @param int $customerId
     * @param int $websiteId
     * @param int $productId
     * @param float $qty
     * @param float $expectedValue
     * @dataProvider dataProvider
     *
     * @see PriceResolver::resolve()
     */
    public function testResolve(
        int $customerId,
        int $websiteId,
        int $productId,
        float $qty,
        float $expectedValue
    ): void {
        $this->connection->expects($this->once())
            ->method('fetchOne')
            ->willReturn($expectedValue);
        $this->assertEquals(
            $expectedValue,
            $this->resolver->resolve(
                $customerId,
                $websiteId,
                $productId,
                $qty
            )
        );
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [1, 1, 1, 1, 10.1],
            [1, 2, 1, 2, 11.2],
            [1, 3, 1, 3, 12.3],
        ];
    }
}
