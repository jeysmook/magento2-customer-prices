<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;

/**
 * Delete the customer price
 */
class DeleteCustomerPrice
{
    /**
     * @var CustomerPrice
     */
    private $resource;

    /**
     * DeleteCustomerPrice constructor
     *
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPrice $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * Delete the customer price
     *
     * @param CustomerPriceInterface $customerPrice
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(CustomerPriceInterface $customerPrice): void
    {
        try {
            $this->resource->delete($customerPrice);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the customer price: %error', ['error' => $exception->getMessage()]),
                $exception
            );
        }
    }
}
