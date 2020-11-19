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
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreFactory;

/**
 * Getting information about the current customer price only for adminhtml area
 */
class Locator
{
    /**
     * @var GetCustomerPriceById
     */
    private GetCustomerPriceById $getCustomerPriceById;

    /**
     * @var CustomerPriceInterfaceFactory
     */
    private CustomerPriceInterfaceFactory $customerPriceFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var StoreFactory
     */
    private StoreFactory $storeFactory;

    /**
     * Locator constructor
     *
     * @param GetCustomerPriceById $getCustomerPriceById
     * @param CustomerPriceInterfaceFactory $customerPriceFactory
     * @param RequestInterface $request
     * @param StoreFactory $storeFactory
     */
    public function __construct(
        GetCustomerPriceById $getCustomerPriceById,
        CustomerPriceInterfaceFactory $customerPriceFactory,
        RequestInterface $request,
        StoreFactory $storeFactory
    ) {
        $this->getCustomerPriceById = $getCustomerPriceById;
        $this->customerPriceFactory = $customerPriceFactory;
        $this->request = $request;
        $this->storeFactory = $storeFactory;
    }

    /**
     * Get the current customer price
     *
     * @return CustomerPriceInterface
     */
    public function getCustomerPrice(): CustomerPriceInterface
    {
        try {
            return $this->getCustomerPriceById->execute(
                (int)$this->request->getParam('item_id')
            );
        } catch (Exception $exception) {
            return $this->customerPriceFactory->create();
        }
    }

    /**
     * Get selected store
     *
     * @return StoreInterface
     */
    public function getStore(): StoreInterface
    {
        $store = $this->storeFactory->create();
        try {
            return $store->load((int)$this->request->getParam('store'));
        } catch (Exception $exception) {
            return $store;
        }
        return $store;
    }
}
