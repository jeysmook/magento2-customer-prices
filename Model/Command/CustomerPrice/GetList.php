<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command\CustomerPrice;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceSearchResultsInterface;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceSearchResultsInterfaceFactory;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Get a list of customer prices
 */
class GetList
{
    /**
     * @var CustomerPrice\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var CustomerPriceSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * GetList constructor
     *
     * @param CustomerPrice\CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CustomerPriceSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        CustomerPrice\CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        CustomerPriceSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Get a list of customer prices
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return CustomerPriceSearchResultsInterface
     */
    public function execute(SearchCriteriaInterface $searchCriteria): CustomerPriceSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var CustomerPriceSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
