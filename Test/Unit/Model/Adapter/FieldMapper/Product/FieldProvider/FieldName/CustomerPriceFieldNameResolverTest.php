<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Adapter\FieldMapper\Product\Product\FieldName;

use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;
use Jeysmook\CustomerPrices\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\CustomerPriceFieldNameResolver;
use Jeysmook\CustomerPrices\Model\CustomerPriceSearchResults;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPriceSearchResults
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceFieldNameResolverTest extends TestCase
{
    /**
     * @var CustomerPriceFieldNameResolver
     */
    private $customerPriceFieldNameResolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $customerProvider = $this->getMockBuilder(CustomerProviderInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCustomerId', 'getWebsiteId', 'getCustomer'])
            ->getMock();
        $customerProvider->expects($this->any())
            ->method('getCustomerId')
            ->willReturn(1);
        $customerProvider->expects($this->any())
            ->method('getWebsiteId')
            ->willReturn(1);
        $this->customerPriceFieldNameResolver = new CustomerPriceFieldNameResolver($customerProvider);
    }

    /**
     * @dataProvider dataProvider
     * @param array $context
     * @param string $expectedValue
     * @return void
     * @see CustomerPriceFieldNameResolver::resolve()
     */
    public function testResolve(array $context, string $expectedValue): void
    {
        $this->assertEquals($expectedValue, $this->customerPriceFieldNameResolver->resolve($context));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [context, expectedValue]
            [['websiteId' => 1, 'customerId' => 1], 'customer_price_1_1'],
            [['websiteId' => 1, 'customerId' => 2], 'customer_price_1_2'],
            [['websiteId' => 1, 'customerId' => 3], 'customer_price_1_3'],
            [[], 'customer_price_1_1'],
        ];
    }
}
