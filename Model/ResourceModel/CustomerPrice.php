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
    public function getMainTable()
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
}
