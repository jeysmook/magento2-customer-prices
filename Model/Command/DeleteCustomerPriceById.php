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

/**
 * Delete the customer price by ID
 */
class DeleteCustomerPriceById
{
    /**
     * @var GetCustomerPriceById
     */
    private $getCustomerPriceById;

    /**
     * @var DeleteCustomerPrice
     */
    private $deleteCustomerPrice;

    /**
     * DeleteCustomerPriceById constructor
     *
     * @param GetCustomerPriceById $getCustomerPriceById
     * @param DeleteCustomerPrice $deleteCustomerPrice
     */
    public function __construct(
        GetCustomerPriceById $getCustomerPriceById,
        DeleteCustomerPrice $deleteCustomerPrice
    ) {
        $this->getCustomerPriceById = $getCustomerPriceById;
        $this->deleteCustomerPrice = $deleteCustomerPrice;
    }

    /**
     * Delete the customer price by ID
     *
     * @param int $itemId
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $itemId): void
    {
        try {
            $this->deleteCustomerPrice->execute(
                $this->getCustomerPriceById->execute($itemId)
            );
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the customer price: %error', ['error' => $exception->getMessage()]),
                $exception
            );
        }
    }
}
