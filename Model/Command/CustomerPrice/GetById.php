<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command\CustomerPrice;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterfaceFactory;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Get the customer price by ID
 */
class GetById
{
    /**
     * @var CustomerPriceInterfaceFactory
     */
    private $entityFactory;

    /**
     * @var CustomerPrice
     */
    private $resource;

    /**
     * GetById constructor
     *
     * @param CustomerPriceInterfaceFactory $entityFactory
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPriceInterfaceFactory $entityFactory,
        CustomerPrice $resource
    ) {
        $this->entityFactory = $entityFactory;
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
        $entity = $this->entityFactory->create();
        $this->resource->load($entity, $itemId);
        if (!$entity->getId()) {
            throw new NoSuchEntityException();
        }
        return $entity;
    }
}
