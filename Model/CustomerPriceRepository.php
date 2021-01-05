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
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\Delete;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\DeleteById;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\GetById;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\GetList;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\Save;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * The repository of the customer price entity
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceRepository implements CustomerPriceRepositoryInterface
{
    /**
     * @var GetById
     */
    private $getById;

    /**
     * @var Save
     */
    private $save;

    /**
     * @var DeleteById
     */
    private $deleteById;

    /**
     * @var Delete
     */
    private $delete;

    /**
     * @var GetList
     */
    private $getList;

    /**
     * CustomerPriceRepository constructor
     *
     * @param GetById $getById
     * @param Save $save
     * @param DeleteById $deleteById
     * @param Delete $delete
     * @param GetList $getList
     */
    public function __construct(
        GetById $getById,
        Save $save,
        DeleteById $deleteById,
        Delete $delete,
        GetList $getList
    ) {
        $this->getById = $getById;
        $this->save = $save;
        $this->deleteById = $deleteById;
        $this->delete = $delete;
        $this->getList = $getList;
    }

    /**
     * @inheritDoc
     */
    public function get(int $itemId): Data\CustomerPriceInterface
    {
        return $this->getById->execute($itemId);
    }

    /**
     * @inheritDoc
     */
    public function save(Data\CustomerPriceInterface $customerPrice): int
    {
        return $this->save->execute($customerPrice);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $itemId): void
    {
        $this->deleteById->execute($itemId);
    }

    /**
     * @inheritDoc
     */
    public function delete(Data\CustomerPriceInterface $customerPrice): void
    {
        $this->delete->execute($customerPrice);
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Data\CustomerPriceSearchResultsInterface
    {
        return $this->getList->execute($searchCriteria);
    }
}
