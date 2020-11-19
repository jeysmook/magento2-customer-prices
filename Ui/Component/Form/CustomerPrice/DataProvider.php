<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Ui\Component\Form\CustomerPrice;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice\Collection;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

/**
 * Data provider of the customer price entity
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var array
     */
    private $loadedData;

    /**
     * DataProvider constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $requestCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $requestCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $requestCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data,
            $pool
        );
    }

    /**
     * Get data of the entity
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        /** @var CustomerPriceInterface $customerPrice */
        foreach ($this->collection->getItems() as $customerPrice) {
            $this->loadedData[$customerPrice->getItemId()] = $customerPrice->getData();
        }

        $data = $this->dataPersistor->get('jeysmook_customer_price');
        if (!empty($data)) {
            /** @var CustomerPriceInterface $customerPrice */
            $customerPrice = $this->collection->getNewEmptyItem();
            $customerPrice->setData($data);
            $this->loadedData[$customerPrice->getItemId()] = $customerPrice->getData();
            $this->dataPersistor->clear('jeysmook_customer_price');
        }
        return $this->loadedData;
    }
}
