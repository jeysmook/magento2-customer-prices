<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command;

use Jeysmook\CustomerPrices\Model\Command\GetCustomerByCustomerPriceId;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see GetCustomerByCustomerPriceId
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class GetCustomerByCustomerPriceIdTest extends TestCase
{
    /**
     * @var AdapterInterface|MockObject
     */
    private $connection;

    /**
     * @var CustomerRepositoryInterface|MockObject
     */
    private $customerRepository;

    /**
     * @var GetCustomerByCustomerPriceId
     */
    private $getCustomerByCustomerPriceId;

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
        $resource = $this->getMockBuilder(CustomerPrice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resource->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->connection);
        $this->customerRepository = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->getCustomerByCustomerPriceId = new GetCustomerByCustomerPriceId(
            $resource,
            $this->customerRepository
        );
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @see GetCustomerByCustomerPriceId::execute()
     */
    public function testExecute(): void
    {
        $customerId = 1;
        $customerPriceId = 2;
        $customer = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection->expects($this->once())
            ->method('fetchOne')
            ->willReturn($customerId);
        $this->customerRepository->expects($this->once())
            ->method('getById')
            ->willReturn($customer);
        $this->assertEquals($customer, $this->getCustomerByCustomerPriceId->execute($customerPriceId));
    }

    /**
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @see GetCustomerByCustomerPriceId::execute()
     */
    public function testExecuteWithException(): void
    {
        $customerPriceId = 3;
        $this->connection->expects($this->once())
            ->method('fetchOne')
            ->willReturn(0);
        $this->expectException(NoSuchEntityException::class);
        $this->getCustomerByCustomerPriceId->execute($customerPriceId);
    }
}
