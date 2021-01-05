<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\CustomerPrice;

use Exception;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterfaceFactory;
use Jeysmook\CustomerPrices\Model\Command\CustomerPrice\GetById;
use Jeysmook\CustomerPrices\Model\Command\GetCustomerByCustomerPriceId;
use Jeysmook\CustomerPrices\Model\Command\GetProductByCustomerPriceId;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Getting information about the current customer price only for adminhtml area
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Locator
{
    /**
     * @var GetById
     */
    private $getCustomerPriceById;

    /**
     * @var CustomerPriceInterfaceFactory
     */
    private $customerPriceFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var GetProductByCustomerPriceId
     */
    private $getProductByCustomerPriceId;

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * @var CustomerInterfaceFactory
     */
    private $customerFactory;

    /**
     * @var GetCustomerByCustomerPriceId
     */
    private $getCustomerByCustomerPriceId;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * Locator constructor
     *
     * @param GetById $getCustomerPriceById
     * @param CustomerPriceInterfaceFactory $customerPriceFactory
     * @param RequestInterface $request
     * @param StoreManagerInterface $storeManager
     * @param GetProductByCustomerPriceId $getProductByCustomerPriceId
     * @param ProductInterfaceFactory $productFactory
     * @param CustomerInterfaceFactory $customerFactory
     * @param GetCustomerByCustomerPriceId $getCustomerByCustomerPriceId
     */
    public function __construct(
        GetById $getCustomerPriceById,
        CustomerPriceInterfaceFactory $customerPriceFactory,
        RequestInterface $request,
        StoreManagerInterface $storeManager,
        GetProductByCustomerPriceId $getProductByCustomerPriceId,
        ProductInterfaceFactory $productFactory,
        CustomerInterfaceFactory $customerFactory,
        GetCustomerByCustomerPriceId $getCustomerByCustomerPriceId
    ) {
        $this->getCustomerPriceById = $getCustomerPriceById;
        $this->customerPriceFactory = $customerPriceFactory;
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->getProductByCustomerPriceId = $getProductByCustomerPriceId;
        $this->productFactory = $productFactory;
        $this->customerFactory = $customerFactory;
        $this->getCustomerByCustomerPriceId = $getCustomerByCustomerPriceId;
    }

    /**
     * Get the current customer price
     *
     * @return CustomerPriceInterface
     */
    public function getCustomerPrice(): CustomerPriceInterface
    {
        $cacheKey = 'current_customer';
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        try {
            $customer = $this->getCustomerPriceById->execute(
                (int)$this->request->getParam('item_id')
            );
        } catch (Exception $exception) {
            $customer = $this->customerPriceFactory->create();
        }
        return $this->catch[$cacheKey] = $customer;
    }

    /**
     * Get selected store
     *
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore(): StoreInterface
    {
        $storeId = $this->request->getParam('store');
        $storeId = $storeId ?: ((($store = $this->resolveStore()) ? $store->getId() : null) ?: null);
        $cacheKey = $storeId ? 'store_' . $storeId : 'store_default';
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        try {
            return $this->cache[$cacheKey] = $storeId
                ? $this->storeManager->getStore($storeId)
                : $this->storeManager->getStore();
        } catch (Exception $exception) {
            throw new NoSuchEntityException(__('The store not found.'));
        }
    }

    /**
     * Get the related product of the current customer price
     *
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface
    {
        if (isset($this->cache['product'])) {
            return $this->cache['product'];
        }

        try {
            return $this->cache['product'] = $this->getProductByCustomerPriceId->execute(
                (int)$this->getCustomerPrice()->getItemId(),
                (int)$this->getStore()->getId()
            );
        } catch (NoSuchEntityException $exception) {
            return $this->cache['product'] = $this->productFactory->create();
        }
    }

    /**
     * Get the realted customer of the current customer price
     *
     * @return CustomerInterface
     */
    public function getCustomer(): CustomerInterface
    {
        if (isset($this->cache['customer'])) {
            return $this->cache['customer'];
        }

        try {
            return $this->cache['customer'] = $this->getCustomerByCustomerPriceId->execute(
                (int)$this->getCustomerPrice()->getItemId()
            );
        } catch (LocalizedException $exception) {
            return $this->cache['customer'] = $this->customerFactory->create();
        }
    }

    /**
     * Get current store ID from the request
     *
     * @return int|null
     */
    public function getRequestStoreId(): ?int
    {
        $storeId = $this->request->getParam('store');
        return (string)$storeId !== '' ? (int)$storeId : null;
    }

    /**
     * Resolves the store for the current customer price
     *
     * @return StoreInterface|null
     */
    private function resolveStore(): ?StoreInterface
    {
        foreach ($this->storeManager->getStores(false) as $store) {
            if ($store->getWebsiteId() == $this->getCustomerPrice()->getWebsiteId()) {
                return $store;
            }
        }
        return null;
    }
}
