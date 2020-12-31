<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command;

use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Get the customer by customer price ID
 */
class GetCustomerByCustomerPriceId
{
    /**
     * @var CustomerPrice
     */
    private $customerPrice;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * GetCustomerByCustomerPriceId constructor
     *
     * @param CustomerPrice $customerPrice
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerPrice $customerPrice,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerPrice = $customerPrice;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get the product by customer price ID
     *
     * @param int $customerPriceId
     * @return CustomerInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute(int $customerPriceId): CustomerInterface
    {
        $select = $this->customerPrice->getConnection()->select();
        $select->from($this->customerPrice->getMainTable(), ['customer_id']);
        $select->where('item_id = ?', $customerPriceId);
        $select->limit(1);
        $customerId = $this->customerPrice->getConnection()->fetchOne($select);
        if (!$customerId) {
            throw new NoSuchEntityException();
        }
        return $this->customerRepository->getById((int)$customerId);
    }
}
