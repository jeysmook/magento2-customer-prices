<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command\CustomerPrice;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete the customer price by ID
 */
class DeleteById
{
    /**
     * @var GetById
     */
    private $getById;

    /**
     * @var Delete
     */
    private $delete;

    /**
     * DeleteById constructor
     *
     * @param GetById $getById
     * @param Delete $delete
     */
    public function __construct(
        GetById $getById,
        Delete $delete
    ) {
        $this->getById = $getById;
        $this->delete = $delete;
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
            $this->delete->execute(
                $this->getById->execute($itemId)
            );
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the customer price: %error', ['error' => $exception->getMessage()]),
                $exception
            );
        }
    }
}
