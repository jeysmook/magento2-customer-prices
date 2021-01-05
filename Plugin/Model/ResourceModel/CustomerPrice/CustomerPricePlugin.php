<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Plugin\Model\ResourceModel\CustomerPrice;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\CatalogSearch\Model\Indexer\Fulltext;
use Magento\Framework\App\Cache\FlushCacheByTags;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\PageCache\Model\Cache\Type;

/**
 * Re-index and flush cache for catalog
 */
class CustomerPricePlugin
{
    /**
     * @var IndexerRegistry
     */
    private $indexerRegistry;

    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;

    /**
     * @var FlushCacheByTags
     */
    private $flushCacheByTags;

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * CustomerPricePlugin constructor
     *
     * @param IndexerRegistry $indexerRegistry
     * @param TypeListInterface $cacheTypeList
     * @param FlushCacheByTags $flushCacheByTags
     * @param ProductInterfaceFactory $productFactory
     */
    public function __construct(
        IndexerRegistry $indexerRegistry,
        TypeListInterface $cacheTypeList,
        FlushCacheByTags $flushCacheByTags,
        ProductInterfaceFactory $productFactory
    ) {
        $this->indexerRegistry = $indexerRegistry;
        $this->cacheTypeList = $cacheTypeList;
        $this->flushCacheByTags = $flushCacheByTags;
        $this->productFactory = $productFactory;
    }

    /**
     * Re-index the catalog search indexer after saving the entity
     *
     * @param CustomerPrice $customerPrice
     * @param CustomerPriceInterface $entity
     * @return void
     * @see CustomerPrice::save()
     */
    public function beforeSave(
        CustomerPrice $customerPrice,
        CustomerPriceInterface $entity
    ): void {
        $customerPrice->addCommitCallback(function () use ($entity) {
            $this->reindexRow((int)$entity->getProductId());
        });
    }

    /**
     * Clean up the full page cache after saving
     *
     * @param CustomerPrice $customerPrice
     * @param CustomerPrice $result
     * @param CustomerPriceInterface $entity
     * @return CustomerPrice
     * @see CustomerPrice::save()
     */
    public function afterSave(
        CustomerPrice $customerPrice,
        CustomerPrice $result,
        CustomerPriceInterface $entity
    ): CustomerPrice {
        $this->flushCacheAfterSave(
            $customerPrice,
            $this->productFactory->create()
                ->setId($entity->getProductId())
        );
        return $result;
    }

    /**
     * Re-index the catalog search indexer after deleting the entity
     *
     * @param CustomerPrice $customerPrice
     * @param CustomerPriceInterface $entity
     * @return void
     * @see CustomerPrice::delete()
     */
    public function beforeDelete(
        CustomerPrice $customerPrice,
        CustomerPriceInterface $entity
    ): void {
        $customerPrice->addCommitCallback(function () use ($entity) {
            $this->reindexRow((int)$entity->getProductId());
        });
    }

    /**
     * Clean up the full page cache after deleting
     *
     * @param CustomerPrice $customerPrice
     * @param CustomerPrice $result
     * @param CustomerPriceInterface $entity
     * @return CustomerPrice
     * @see CustomerPrice::delete()
     */
    public function afterDelete(
        CustomerPrice $customerPrice,
        CustomerPrice $result,
        CustomerPriceInterface $entity
    ): CustomerPrice {
        $this->flushCacheAfterDelete(
            $customerPrice,
            $this->productFactory->create()->setId($entity->getProductId())
        );
        return $result;
    }

    /**
     * Re-index product by ID
     *
     * @param int $productId
     * @return void
     */
    private function reindexRow(int $productId): void
    {
        $indexer = $this->indexerRegistry->get(Fulltext::INDEXER_ID);
        if (!$indexer->isScheduled()) {
            $indexer->reindexRow($productId);
        }
    }

    /**
     * Flush cache after saving the entity
     *
     * @param AbstractResource $resource
     * @param AbstractModel $entity
     */
    private function flushCacheAfterSave(
        AbstractResource $resource,
        AbstractModel $entity
    ): void {
        // clean up the entity cache by tags
        $this->flushCacheByTags->afterSave($resource, $resource, $entity);

        if (!$this->isScheduled(Fulltext::INDEXER_ID)) {
            // clean up full page cache
            $this->cacheTypeList->cleanType(Type::TYPE_IDENTIFIER);
        }
    }

    /**
     * Flush cache after deleting the entity
     *
     * @param AbstractResource $resource
     * @param AbstractModel $entity
     */
    private function flushCacheAfterDelete(
        AbstractResource $resource,
        AbstractModel $entity
    ): void {
        // clean up the entity cache by tags
        $this->flushCacheByTags->afterDelete($resource, $resource, $entity);

        if (!$this->isScheduled(Fulltext::INDEXER_ID)) {
            // clean up full page cache
            $this->cacheTypeList->cleanType(Type::TYPE_IDENTIFIER);
        }
    }

    /**
     * Is scheduled the indexer?
     *
     * @param string $indexer
     * @return bool
     */
    public function isScheduled(string $indexer): bool
    {
        $indexer = $this->indexerRegistry->get($indexer);
        return $indexer->isScheduled();
    }
}
