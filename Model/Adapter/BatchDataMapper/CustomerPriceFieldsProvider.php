<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Adapter\BatchDataMapper;

use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\AdvancedSearch\Model\Adapter\DataMapper\AdditionalFieldsProviderInterface;
use Magento\CatalogSearch\Model\Indexer\Fulltext\Action\DataProvider;
use Magento\Elasticsearch\Model\ResourceModel\Index;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Provide data mapping for customer price fields
 */
class CustomerPriceFieldsProvider implements AdditionalFieldsProviderInterface
{
    /**
     * @var CustomerPrice
     */
    private CustomerPrice $resource;

    /**
     * @var Index
     */
    private Index $priceResourceIndex;

    /**
     * @var DataProvider
     */
    private DataProvider $dataProvider;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * CustomerPriceFieldsProvider constructor
     *
     * @param CustomerPrice $resource
     * @param Index $priceResourceIndex
     * @param DataProvider $dataProvider
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CustomerPrice $resource,
        Index $priceResourceIndex,
        DataProvider $dataProvider,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->priceResourceIndex = $priceResourceIndex;
        $this->dataProvider = $dataProvider;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function getFields(array $productIds, $storeId)
    {
        $fields = [];
        if (!$this->dataProvider->getSearchableAttribute('price')) {
            return $fields;
        }

        // get default price data for products
        $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
        $priceData = $this->priceResourceIndex->getPriceIndexData($productIds, $storeId);

        // get all customers for the current scope
        $select = $this->resource->getConnection()->select();
        $select->from($this->resource->getTable('customer_entity'), ['entity_id', 'group_id']);
        $select->where('website_id = ?', $websiteId);
        $customerIdsGroupIds = $this->resource->getConnection()->fetchPairs($select);

        // get all customer prices for products
        $select = $this->resource->getConnection()->select();
        $select->from($this->resource->getMainTable(), ['product_id', 'customer_id', 'price']);
        $select->where('product_id IN (?)', $productIds);
        $select->where('qty = 1');
        $customerPrices = [];
        foreach ($this->resource->getConnection()->fetchAssoc($select) as $priceRow) {
            $customerPrices[$priceRow['product_id']][$priceRow['customer_id']] = $priceRow['price'];
        }

        // generate fields for search
        foreach ($productIds as $productId) {
            $fields[$productId] = $this->getProductPriceData(
                $productId,
                $customerIdsGroupIds,
                $customerPrices,
                $priceData
            );
        }
        return $fields;
    }

    /**
     * Get product customer price data
     *
     * @param int $productId
     * @param array $customerIdsGroupIds
     * @param array $customerPrices
     * @param array $priceData
     * @return array
     */
    private function getProductPriceData(
        int $productId,
        array $customerIdsGroupIds,
        array $customerPrices,
        array $priceData
    ): array {
        $result = [];
        foreach ($customerIdsGroupIds as $customerId => $groupId) {
            $price = $customerPrices[$productId][$customerId] ?? $priceData[$productId][$groupId];
            $result['customer_price_' . $customerId] = sprintf('%F', $price);
        }
        return $result;
    }
}
