<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterfaceFactory;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Get the product by customer price ID
 */
class GetProductByCustomerPriceId
{
    /**
     * @var CustomerPrice
     */
    private $customerPrice;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * GetProductByCustomerPriceId constructor
     *
     * @param CustomerPrice $customerPrice
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CustomerPrice $customerPrice,
        ProductRepositoryInterface $productRepository
    ) {
        $this->customerPrice = $customerPrice;
        $this->productRepository = $productRepository;
    }

    /**
     * Get the product by customer price ID
     *
     * @param int $customerPriceId
     * @param int|null $storeId
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $customerPriceId, int $storeId = null): ProductInterface
    {
        $select = $this->customerPrice->getConnection()->select();
        $select->from($this->customerPrice->getMainTable(), ['product_id']);
        $select->where('item_id = ?', $customerPriceId);
        $select->limit(1);
        $productId = $this->customerPrice->getConnection()->fetchOne($select);
        if (!$productId) {
            throw new NoSuchEntityException();
        }
        return $this->productRepository->getById((int)$productId, false, $storeId);
    }
}
