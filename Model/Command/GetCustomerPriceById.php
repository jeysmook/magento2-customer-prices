<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterfaceFactory;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Get the customer price by ID
 */
class GetCustomerPriceById
{
    /**
     * @var CustomerPriceInterfaceFactory
     */
    private CustomerPriceInterfaceFactory $customerPriceFactory;

    /**
     * @var CustomerPrice
     */
    private CustomerPrice $resource;

    /**
     * GetCustomerPriceById constructor
     *
     * @param CustomerPriceInterfaceFactory $customerPriceFactory
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPriceInterfaceFactory $customerPriceFactory,
        CustomerPrice $resource
    ) {
        $this->customerPriceFactory = $customerPriceFactory;
        $this->resource = $resource;
    }

    /**
     * Get the customer price by ID
     *
     * @param int $itemId
     * @return CustomerPriceInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $itemId): CustomerPriceInterface
    {
        $customerPrice = $this->customerPriceFactory->create();
        $this->resource->load($customerPrice, $itemId);
        if (!$customerPrice->getId()) {
            throw new NoSuchEntityException();
        }
        return $customerPrice;
    }
}
