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
use Jeysmook\CustomerPrices\Model\Config\Source\Website;
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
    private $customerResolver;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

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

    /**
     * @inheritDoc
     */
    public function getWebsiteId(): int
    {
        return $this->getCustomer() ? (int)$this->getCustomer()->getWebsiteId() : Website::DEFAULT_WEBSITE_ID;
    }
}
