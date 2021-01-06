<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command\Frontend;

use Jeysmook\CustomerPrices\Model\Command\Frontend\CustomerResolver;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\SessionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerResolver
 */
class CustomerResolverTest extends TestCase
{
    /**
     * @var Session|MockObject
     */
    private $customerSession;

    /**
     * @var CustomerResolver
     */
    private $customerResolver;

    /**
     * @inheritDoc
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    protected function setUp(): void
    {
        $this->customerSession = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCustomerId'])
            ->getMock();
        $customerSessionFactory = $this->getMockBuilder(SessionFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $customerSessionFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->customerSession);
        $this->customerResolver = new CustomerResolver($customerSessionFactory);
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param int $expectedValue
     * @see CustomerResolver::resolve()
     */
    public function testResolve(int $customerId, int $expectedValue): void
    {
        $this->customerSession->expects($this->once())
            ->method('getCustomerId')
            ->willReturn($customerId);
        $this->assertEquals($expectedValue, $this->customerResolver->resolve());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [customerId, expectedValue]
            [1, 1],
            [2, 2],
            [3, 3],
        ];
    }
}
