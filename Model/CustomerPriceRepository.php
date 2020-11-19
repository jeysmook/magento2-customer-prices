<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model;

use Jeysmook\CustomerPrices\Api\CustomerPriceRepositoryInterface;
use Jeysmook\CustomerPrices\Api\Data;
use Jeysmook\CustomerPrices\Model\Command\DeleteCustomerPrice;
use Jeysmook\CustomerPrices\Model\Command\DeleteCustomerPriceById;
use Jeysmook\CustomerPrices\Model\Command\GetCustomerPriceById;
use Jeysmook\CustomerPrices\Model\Command\SaveCustomerPrice;

/**
 * The repository of the customer price entity
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceRepository implements CustomerPriceRepositoryInterface
{
    /**
     * @var GetCustomerPriceById
     */
    private $getCustomerPriceById;

    /**
     * @var SaveCustomerPrice
     */
    private $saveCustomerPrice;

    /**
     * @var DeleteCustomerPriceById
     */
    private $deleteCustomerPriceById;

    /**
     * @var DeleteCustomerPrice
     */
    private $deleteCustomerPrice;

    /**
     * CustomerPriceRepository constructor
     *
     * @param GetCustomerPriceById $getCustomerPriceById
     * @param SaveCustomerPrice $saveCustomerPrice
     * @param DeleteCustomerPriceById $deleteCustomerPriceById
     * @param DeleteCustomerPrice $deleteCustomerPrice
     */
    public function __construct(
        GetCustomerPriceById $getCustomerPriceById,
        SaveCustomerPrice $saveCustomerPrice,
        DeleteCustomerPriceById $deleteCustomerPriceById,
        DeleteCustomerPrice $deleteCustomerPrice
    ) {
        $this->getCustomerPriceById = $getCustomerPriceById;
        $this->saveCustomerPrice = $saveCustomerPrice;
        $this->deleteCustomerPriceById = $deleteCustomerPriceById;
        $this->deleteCustomerPrice = $deleteCustomerPrice;
    }

    /**
     * @inheritDoc
     */
    public function get(int $itemId): Data\CustomerPriceInterface
    {
        return $this->getCustomerPriceById->execute($itemId);
    }

    /**
     * @inheritDoc
     */
    public function save(Data\CustomerPriceInterface $customerPrice): int
    {
        return $this->saveCustomerPrice->execute($customerPrice);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $itemId): void
    {
        $this->deleteCustomerPriceById->execute($itemId);
    }

    /**
     * @inheritDoc
     */
    public function delete(Data\CustomerPriceInterface $customerPrice): void
    {
        $this->deleteCustomerPrice->execute($customerPrice);
    }
}
