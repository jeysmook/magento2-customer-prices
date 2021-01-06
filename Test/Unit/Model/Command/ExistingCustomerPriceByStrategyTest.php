<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command;

use Jeysmook\CustomerPrices\Model\Command\CustomerProvider;
use Jeysmook\CustomerPrices\Model\Command\ExistingCustomerPriceByStrategy;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see ExistingCustomerPriceByStrategy
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ExistingCustomerPriceByStrategyTest extends TestCase
{
    /**
     * @var AdapterInterface|MockObject
     */
    private $connection;

    /**
     * @var CustomerProvider
     */
    private $existingCustomerPriceByStrategy;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $select = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'from'])
            ->getMock();
        $this->connection = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection->expects($this->any())
            ->method('select')
            ->willReturn($select);
        $resourceConnection = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resourceConnection->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->connection);
        $this->existingCustomerPriceByStrategy = new ExistingCustomerPriceByStrategy($resourceConnection);
    }

    /**
     * @dataProvider dataProvider
     * @param int $value
     * @param string $strategy
     * @param int $size
     * @param bool $expectedValue
     * @see ExistingCustomerPriceByStrategy::execute()
     */
    public function testExecute(int $value, string $strategy, int $size, bool $expectedValue): void
    {
        $this->connection->expects($this->once())
            ->method('fetchOne')
            ->willReturn($size);
        $this->assertEquals($expectedValue, $this->existingCustomerPriceByStrategy->execute($value, $strategy));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [value, strategy, size, expectedValue]
            [1, ExistingCustomerPriceByStrategy::STRATEGY_CUSTOMER, 1, true],
            [2, ExistingCustomerPriceByStrategy::STRATEGY_CUSTOMER, 0, false],
            [1, ExistingCustomerPriceByStrategy::STRATEGY_PRODUCT, 1, true],
            [2, ExistingCustomerPriceByStrategy::STRATEGY_PRODUCT, 0, false]
        ];
    }
}
