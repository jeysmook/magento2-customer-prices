<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command\Backend;

use Jeysmook\CustomerPrices\Model\Command\Backend\CustomerResolver;
use Magento\Backend\Model\Session\Quote;
use Magento\Backend\Model\Session\QuoteFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerResolver
 */
class CustomerResolverTest extends TestCase
{
    /**
     * @var Quote|MockObject
     */
    private $quote;

    /**
     * @var CustomerResolver
     */
    private $customerResolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->quote = $this->getMockBuilder(Quote::class)
            ->disableOriginalConstructor()
            ->addMethods(['getCustomerId'])
            ->getMock();
        $quoteFactory = $this->getMockBuilder(QuoteFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $quoteFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->quote);
        $this->customerResolver = new CustomerResolver($quoteFactory);
    }

    /**
     * @dataProvider dataProvider
     * @param int $customerId
     * @param int $expectedValue
     * @see CustomerResolver::resolve()
     */
    public function testResolve(int $customerId, int $expectedValue): void
    {
        $this->quote->expects($this->once())
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
