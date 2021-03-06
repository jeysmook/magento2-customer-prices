<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * The resource of the customer price entity
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class CustomerPrice extends AbstractDb
{
    public const TABLE_NAME = 'jeysmook_customer_price';
    public const PK = 'item_id';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * CustomerPrice constructor
     *
     * @param Context $context
     * @param EntityManager $entityManager
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        EntityManager $entityManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);

        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    protected function _construct() // phpcs:ignore PSR2.Methods.MethodDeclaration
    {
        $this->_init(self::TABLE_NAME, self::PK);
    }

    /**
     * Get main table
     *
     * @return string
     */
    public function getMainTable(): string
    {
        return $this->getTable(self::TABLE_NAME);
    }

    /**
     * @inheritDoc
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $this->entityManager->load($object, (int)$value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }

    /**
     * Get price index data for scope (website)
     *
     * @param int[] $productIds
     * @param int $websiteId
     * @return array
     */
    public function getPriceIndexData(array $productIds, int $websiteId): array
    {
        $select = $this->getConnection()->select();
        $select->from(['main' => $this->getMainTable()], ['product_id', 'customer_id', 'price']);
        $select->joinInner(
            ['customer' => $this->getTable('customer_entity')],
            'customer.entity_id = main.customer_id AND customer.website_id = ' . $websiteId,
            ''
        );
        $select->where('main.product_id IN (?)', $productIds);
        $select->where('main.qty = 1');
        $select->where('main.website_id = ?', $websiteId);

        $customerPrices = [];
        foreach ($this->getConnection()->fetchAssoc($select) as $priceRow) {
            $customerPrices[$priceRow['product_id']][$priceRow['customer_id']] = $priceRow['price'];
        }
        return $customerPrices;
    }
}
