<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command;

use Exception;
use Jeysmook\CustomerPrices\Api\CustomerProviderInterface;
use Jeysmook\CustomerPrices\Api\CustomerResolverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Getting information about the current customer
 */
class CustomerProvider implements CustomerProviderInterface
{
    /**
     * @var CustomerResolverInterface
     */
    private CustomerResolverInterface $customerResolver;

    /**
     * @var CustomerRepositoryInterface
     */
    private CustomerRepositoryInterface $customerRepository;

    /**
     * @var CustomerInterface|null|bool
     */
    private $customer = false;

    /**
     * CustomerProvider constructor
     *
     * @param CustomerResolverInterface $customerResolver
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerResolverInterface $customerResolver,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerResolver = $customerResolver;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @inheritDoc
     */
    public function getCustomerId(): ?int
    {
        return $this->customerResolver->resolve();
    }

    /**
     * @inheritDoc
     */
    public function getCustomer(): ?CustomerInterface
    {
        if (false === $this->customer) {
            if (!$customerId = $this->getCustomerId()) {
                return $this->customer = null;
            }

            try {
                $this->customer = $this->customerRepository->getById($customerId);
            } catch (Exception $exception) {
                $this->customer = null;
            }
        }
        return $this->customer;
    }
}
