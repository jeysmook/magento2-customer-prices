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
use Jeysmook\CustomerPrices\Model\Command\GetCustomerPriceById;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Getting information about the current customer price only for adminhtml area
 */
class Locator
{
    /**
     * @var GetCustomerPriceById
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
     * @var array
     */
    private $cache = [];

    /**
     * Locator constructor
     *
     * @param GetCustomerPriceById $getCustomerPriceById
     * @param CustomerPriceInterfaceFactory $customerPriceFactory
     * @param RequestInterface $request
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        GetCustomerPriceById $getCustomerPriceById,
        CustomerPriceInterfaceFactory $customerPriceFactory,
        RequestInterface $request,
        StoreManagerInterface $storeManager
    ) {
        $this->getCustomerPriceById = $getCustomerPriceById;
        $this->customerPriceFactory = $customerPriceFactory;
        $this->request = $request;
        $this->storeManager = $storeManager;
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
        $storeId = $storeId ?: ($this->getCustomerPrice()->getWebsiteId() ?: null);
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
}
