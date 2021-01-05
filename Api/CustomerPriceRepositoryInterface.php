<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * The repository of the customer price entity
 */
interface CustomerPriceRepositoryInterface
{
    /**
     * Get the customer price by ID
     *
     * @param int $itemId
     * @return \Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $itemId): Data\CustomerPriceInterface;

    /**
     * Save the customer price
     *
     * @param \Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface $customerPrice
     * @return int
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\CustomerPriceInterface $customerPrice): int;

    /**
     * Delete the customer price by ID
     *
     * @param int $itemId
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $itemId): void;

    /**
     * Delete the customer price
     *
     * @param Data\CustomerPriceInterface $customerPrice
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\CustomerPriceInterface $customerPrice): void;

    /**
     * Get a list of customer prices
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Jeysmook\CustomerPrices\Api\Data\CustomerPriceSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Data\CustomerPriceSearchResultsInterface;
}
