<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command;

use Jeysmook\CustomerPrices\Api\CustomerResolverInterface;
use Jeysmook\CustomerPrices\Model\Command\CustomerProvider;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerProvider
 */
class CustomerProviderTest extends TestCase
{
    /**
     * @var CustomerResolverInterface|MockObject
     */
    private $customerResolver;

    /**
     * @var CustomerRepositoryInterface|MockObject
     */
    private $customerRepository;

    /**
     * @var CustomerProvider
     */
    private $customerProvider;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerResolver = $this->getMockBuilder(CustomerResolverInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerRepository = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerProvider = new CustomerProvider($this->customerResolver, $this->customerRepository);
    }

    /**
     * @see CustomerProvider::getCustomerId()
     */
    public function testGetCustomerId(): void
    {
        $expectedValue = 1;
        $this->customerResolver->expects($this->once())
            ->method('resolve')
            ->willReturn($expectedValue);
        $this->assertEquals($expectedValue, $this->customerProvider->getCustomerId());
    }

    /**
     * @see CustomerProvider::getCustomer()
     */
    public function testGetCustomer(): void
    {
        $customerId = 2;
        $this->customerResolver->expects($this->once())
            ->method('resolve')
            ->willReturn($customerId);
        $expectedValue = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerRepository->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($expectedValue);
        $this->assertEquals($expectedValue, $this->customerProvider->getCustomer());
    }

    /**
     * @see CustomerProvider::getWebsiteId()
     */
    public function testGetWebsiteId(): void
    {
        $customerId = 3;
        $this->customerResolver->expects($this->once())
            ->method('resolve')
            ->willReturn($customerId);
        $customer = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $expectedValue = 4;
        $customer->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($expectedValue);
        $this->customerRepository->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);
        $this->assertEquals($expectedValue, $this->customerProvider->getWebsiteId());
    }
}
